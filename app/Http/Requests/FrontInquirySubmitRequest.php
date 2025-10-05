<?php

/**
 * This file is part of DixlaseInquiry.
 *
 * Copyright (C) 2025 exc-D inc.
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
use Plugins\DixlaseInquiry\App\Models\InquirySetting;

class FrontInquirySubmitRequest extends FormRequest
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
        $settings = InquirySetting::getSettings();
        
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
        
        // 郵便番号（設定により必須/任意）
        if ($settings['show_postal_code']) {
            $rules['postal_code'] = $settings['postal_code_required'] 
                ? 'required|string|max:10' 
                : 'nullable|string|max:10';
        }
        
        // 住所（設定により必須/任意）
        if ($settings['show_address']) {
            $rules['address'] = $settings['address_required'] 
                ? 'required|string|max:255' 
                : 'nullable|string|max:255';
        }
        
        // 電話番号（設定により必須/任意）
        if ($settings['show_phone']) {
            $rules['phone'] = $settings['phone_required'] 
                ? 'required|string|max:20' 
                : 'nullable|string|max:20';
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
        return [
            'last_name.required' => __('dixlase-inquiry::front.validation.last_name_required'),
            'first_name.required' => __('dixlase-inquiry::front.validation.first_name_required'),
            'email.required' => __('dixlase-inquiry::front.validation.email_required'),
            'email.email' => __('dixlase-inquiry::front.validation.email_invalid'),
            'message.required' => __('dixlase-inquiry::front.validation.message_required'),
            'subject.required' => __('dixlase-inquiry::front.validation.subject_required'),
            'postal_code.required' => __('dixlase-inquiry::front.validation.postal_code_required'),
            'address.required' => __('dixlase-inquiry::front.validation.address_required'),
            'phone.required' => __('dixlase-inquiry::front.validation.phone_required'),
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
            'address' => __('dixlase-inquiry::front.form.address'),
            'phone' => __('dixlase-inquiry::front.form.phone'),
            'g-recaptcha-response' => __('dixlase-inquiry::front.form.recaptcha'),
        ];
    }
}
