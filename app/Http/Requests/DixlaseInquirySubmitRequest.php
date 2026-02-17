<?php

/**
 * This file is part of Dixlase Inquiry.
 *
 * Copyright (C) 2026 exc-D inc.
 * https://exc-d.com
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <https://www.gnu.org/licenses/>.
 */

namespace Plugins\DixlaseInquiry\App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Plugins\DixlaseInquiry\App\Models\DixlaseInquirySetting;

class DixlaseInquirySubmitRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * バリデーションルールの設定
     * 設定に応じて動的にルールを生成
     */
    public function rules(): array
    {
        $settings = DixlaseInquirySetting::getSettings();
        $isWestern = (bool) ($settings['name_order_western'] ?? false);
        
        $rules = [
            'last_name' => 'required|string|max:255',
            'first_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'message' => 'required|string',
        ];
        
        // 題名（設定により必須/任意）
        if ($settings['show_subject']) {
            $rules['subject'] = $settings['subject_required'] 
                ? 'required|string|max:255' 
                : 'nullable|string|max:255';
        }
        
        // 郵便番号（設定により必須/任意、形式は日本式/欧米式で異なる）
        if ($settings['show_postal_code']) {
            $postalCodeRule = $isWestern
                ? 'regex:/^[A-Za-z0-9\s\-]{3,10}$/' // 欧米式: 英数字・スペース・ハイフン（3-10文字）
                : 'regex:/^\d{3}-?\d{4}$/';          // 日本式: 7桁（XXX-XXXX or XXXXXXX）
            
            $rules['postal_code'] = $settings['postal_code_required'] 
                ? ['required', 'string', $postalCodeRule]
                : ['nullable', 'string', $postalCodeRule];
        }
        
        // 住所（設定により必須/任意、形式は日本式/欧米式で異なる）
        if ($settings['show_address']) {
            if ($isWestern) {
                // 欧米式: 番地・市・州・国の4フィールド
                $rules['street_address'] = $settings['address_required'] 
                    ? 'required|string|max:255' 
                    : 'nullable|string|max:255';
                $rules['city'] = $settings['address_required'] 
                    ? 'required|string|max:100' 
                    : 'nullable|string|max:100';
                $rules['state'] = 'nullable|string|max:100';
                $rules['country'] = 'nullable|string|max:100';
            } else {
                // 日本式: 1フィールド
                $rules['address'] = $settings['address_required'] 
                    ? 'required|string|max:500' 
                    : 'nullable|string|max:500';
            }
        }
        
        // 電話番号（設定により必須/任意、形式は日本式/欧米式で異なる）
        if ($settings['show_phone']) {
            $phoneRule = $isWestern
                ? 'regex:/^[\+]?[0-9\s\-\(\)]{7,20}$/' // 欧米式: 国際形式対応（+含む7-20文字）
                : 'regex:/^[0-9\-]{10,13}$/';           // 日本式: 10-13桁（ハイフン含む）
            
            $rules['phone'] = $settings['phone_required'] 
                ? ['required', 'string', $phoneRule]
                : ['nullable', 'string', $phoneRule];
        }

        // 性別（設定により必須/任意）
        if ($settings['show_gender']) {
            $genderOptions = 'in:male,female,non_binary,other,prefer_not_to_say';
            $rules['gender'] = $settings['gender_required'] 
                ? ['required', 'string', $genderOptions]
                : ['nullable', 'string', $genderOptions];
        }
        
        // プライバシー同意（設定により必須）
        if ($settings->privacy_consent_enabled ?? false) {
            $rules['privacy_agreed'] = ['required', 'accepted'];
        }

        // reCAPTCHA（設定により必須）
        if ($settings['use_recaptcha']) {
            $rules['g-recaptcha-response'] = 'required|captcha';
        }

        return $rules;
    }

    /**
     * エラーメッセージのカスタマイズ
     */
    public function messages(): array
    {
        $settings = DixlaseInquirySetting::getSettings();
        $isWestern = (bool) ($settings['name_order_western'] ?? false);

        return [
            'last_name.required' => __('dixlase-inquiry::front.validation.last_name_required'),
            'first_name.required' => __('dixlase-inquiry::front.validation.first_name_required'),
            'email.required' => __('dixlase-inquiry::front.validation.email_required'),
            'email.email' => __('dixlase-inquiry::front.validation.email_invalid'),
            'message.required' => __('dixlase-inquiry::front.validation.message_required'),
            'subject.required' => __('dixlase-inquiry::front.validation.subject_required'),
            'postal_code.required' => __('dixlase-inquiry::front.validation.postal_code_required'),
            'postal_code.regex' => $isWestern
                ? __('dixlase-inquiry::front.validation.postal_code_format_western')
                : __('dixlase-inquiry::front.validation.postal_code_format_japanese'),
            // 日本式住所
            'address.required' => __('dixlase-inquiry::front.validation.address_required'),
            // 欧米式住所
            'street_address.required' => __('dixlase-inquiry::front.validation.street_address_required'),
            'city.required' => __('dixlase-inquiry::front.validation.city_required'),
            'state.required' => __('dixlase-inquiry::front.validation.state_required'),
            'phone.required' => __('dixlase-inquiry::front.validation.phone_required'),
            'phone.regex' => $isWestern
                ? __('dixlase-inquiry::front.validation.phone_format_western')
                : __('dixlase-inquiry::front.validation.phone_format_japanese'),
            'gender.required' => __('dixlase-inquiry::front.validation.gender_required'),
            'gender.in' => __('dixlase-inquiry::front.validation.gender_invalid'),
            'privacy_agreed.required' => __('dixlase-inquiry::front.validation.privacy_agreed_required'),
            'privacy_agreed.accepted' => __('dixlase-inquiry::front.validation.privacy_agreed_required'),
            'g-recaptcha-response.required' => __('dixlase-inquiry::front.validation.recaptcha_required'),
        ];
    }

    /**
     * バリデーション属性名のカスタマイズ
     */
    public function attributes(): array
    {
        return [
            'last_name' => __('dixlase-inquiry::front.form.last_name'),
            'first_name' => __('dixlase-inquiry::front.form.first_name'),
            'email' => __('dixlase-inquiry::front.form.email'),
            'message' => __('dixlase-inquiry::front.form.message'),
            'subject' => __('dixlase-inquiry::front.form.subject'),
            'postal_code' => __('dixlase-inquiry::front.form.postal_code'),
            // 日本式住所
            'address' => __('dixlase-inquiry::front.form.address'),
            // 欧米式住所
            'street_address' => __('dixlase-inquiry::front.form.street_address'),
            'city' => __('dixlase-inquiry::front.form.city'),
            'state' => __('dixlase-inquiry::front.form.state'),
            'country' => __('dixlase-inquiry::front.form.country'),
            'phone' => __('dixlase-inquiry::front.form.phone'),
            'gender' => __('dixlase-inquiry::front.form.gender'),
            'g-recaptcha-response' => __('dixlase-inquiry::front.form.recaptcha'),
        ];
    }
}
