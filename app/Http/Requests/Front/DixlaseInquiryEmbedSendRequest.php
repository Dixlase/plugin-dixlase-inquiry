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

namespace Plugins\DixlaseInquiry\App\Http\Requests\Front;

use App\Helpers\CaptchaHelper;
use Illuminate\Foundation\Http\FormRequest;
use Plugins\DixlaseInquiry\App\Models\DixlaseInquirySetting;

class DixlaseInquiryEmbedSendRequest extends FormRequest
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
     * SubmitRequestと同等のフルバリデーション
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $settings = DixlaseInquirySetting::getSettings();
        $isWestern = (bool) ($settings->name_order_western ?? false);

        $rules = [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'message' => 'required|string|max:5000',
        ];

        // 題名
        if ($settings->show_subject ?? false) {
            $rules['subject'] = ($settings->subject_required ?? false)
                ? 'required|string|max:255'
                : 'nullable|string|max:255';
        }

        // 郵便番号
        if ($settings->show_postal_code ?? false) {
            if ($isWestern) {
                $postalCodeRule = 'regex:/^[A-Za-z0-9\s\-]{3,10}$/';
                $rules['postal_code'] = ($settings->postal_code_required ?? false)
                    ? ['required', 'string', $postalCodeRule]
                    : ['nullable', 'string', $postalCodeRule];
            } else {
                $rules['postal_code_1'] = ($settings->postal_code_required ?? false)
                    ? 'required|string|digits:3'
                    : 'nullable|string|digits:3';
                $rules['postal_code_2'] = ($settings->postal_code_required ?? false)
                    ? 'required|string|digits:4'
                    : 'nullable|string|digits:4';
            }
        }

        // 住所
        if ($settings->show_address ?? false) {
            if ($isWestern) {
                $rules['street_address'] = ($settings->address_required ?? false)
                    ? 'required|string|max:255'
                    : 'nullable|string|max:255';
                $rules['building'] = 'nullable|string|max:255';
                $rules['city'] = ($settings->address_required ?? false)
                    ? 'required|string|max:100'
                    : 'nullable|string|max:100';
                $rules['state'] = 'nullable|string|max:100';
                $rules['country'] = 'nullable|string|max:100';
            } else {
                $rules['prefecture'] = ($settings->address_required ?? false)
                    ? 'required|string|max:10'
                    : 'nullable|string|max:10';
                $rules['city'] = ($settings->address_required ?? false)
                    ? 'required|string|max:100'
                    : 'nullable|string|max:100';
                $rules['address_line'] = ($settings->address_required ?? false)
                    ? 'required|string|max:255'
                    : 'nullable|string|max:255';
                $rules['building'] = 'nullable|string|max:255';
            }
        }

        // 電話番号
        if ($settings->show_phone ?? true) {
            if ($isWestern) {
                $phoneRule = 'regex:/^[\+]?[0-9\s\-\(\)]{7,20}$/';
                $rules['phone'] = ($settings->phone_required ?? false)
                    ? ['required', 'string', $phoneRule]
                    : ['nullable', 'string', $phoneRule];
            } else {
                $rules['phone_1'] = ($settings->phone_required ?? false)
                    ? ['required', 'string', 'regex:/^\d{1,5}$/']
                    : ['nullable', 'string', 'regex:/^\d{1,5}$/'];
                $rules['phone_2'] = ($settings->phone_required ?? false)
                    ? ['required', 'string', 'regex:/^\d{1,4}$/']
                    : ['nullable', 'string', 'regex:/^\d{1,4}$/'];
                $rules['phone_3'] = ($settings->phone_required ?? false)
                    ? ['required', 'string', 'regex:/^\d{4}$/']
                    : ['nullable', 'string', 'regex:/^\d{4}$/'];
            }
        }

        // カタカナ（日本式かつshow_kana有効時のみ）
        if (!$isWestern && ($settings->show_kana ?? false)) {
            $kanaRule = 'regex:/^[ァ-ヶー]+$/u';
            $rules['last_name_kana'] = ($settings->require_kana ?? false)
                ? ['required', 'string', 'max:50', $kanaRule]
                : ['nullable', 'string', 'max:50', $kanaRule];
            $rules['first_name_kana'] = ($settings->require_kana ?? false)
                ? ['required', 'string', 'max:50', $kanaRule]
                : ['nullable', 'string', 'max:50', $kanaRule];
        }

        // 性別（有効な選択肢のみ許可）
        if ($settings->show_gender ?? false) {
            $genderValues = ['male', 'female'];
            if ($settings->show_gender_other ?? false) {
                $genderValues[] = 'other';
            }
            if ($settings->show_gender_prefer_not_to_say ?? false) {
                $genderValues[] = 'prefer_not_to_say';
            }
            $genderOptions = 'in:' . implode(',', $genderValues);
            $rules['gender'] = ($settings->gender_required ?? false)
                ? ['required', 'string', $genderOptions]
                : ['nullable', 'string', $genderOptions];
        }

        // プライバシー同意
        if ($settings->privacy_consent_enabled ?? false) {
            $rules['privacy_agreed'] = ['required', 'accepted'];
        }

        // CAPTCHA（CaptchaHelper経由で有効判定）
        if (CaptchaHelper::shouldShowCaptcha('dixlase-inquiry.inquiry_contact')) {
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
        $isWestern = (bool) ($settings->name_order_western ?? false);

        $messages = [
            'last_name.required' => __('dixlase-inquiry::front.validation.last_name_required'),
            'first_name.required' => __('dixlase-inquiry::front.validation.first_name_required'),
            'email.required' => __('dixlase-inquiry::front.validation.email_required'),
            'email.email' => __('dixlase-inquiry::front.validation.email_invalid'),
            'message.required' => __('dixlase-inquiry::front.validation.message_required'),
            'subject.required' => __('dixlase-inquiry::front.validation.subject_required'),
            'gender.required' => __('dixlase-inquiry::front.validation.gender_required'),
            'gender.in' => __('dixlase-inquiry::front.validation.gender_invalid'),
            'privacy_agreed.required' => __('dixlase-inquiry::front.validation.privacy_agreed_required'),
            'privacy_agreed.accepted' => __('dixlase-inquiry::front.validation.privacy_agreed_required'),
            'g-recaptcha-response.required' => __('dixlase-inquiry::front.validation.recaptcha_required'),
        ];

        // カタカナバリデーションメッセージ
        $messages['last_name_kana.required'] = __('dixlase-inquiry::front.validation.last_name_kana_required');
        $messages['last_name_kana.regex'] = __('dixlase-inquiry::front.validation.last_name_kana_katakana');
        $messages['first_name_kana.required'] = __('dixlase-inquiry::front.validation.first_name_kana_required');
        $messages['first_name_kana.regex'] = __('dixlase-inquiry::front.validation.first_name_kana_katakana');

        if ($isWestern) {
            $messages['postal_code.required'] = __('dixlase-inquiry::front.validation.postal_code_required');
            $messages['postal_code.regex'] = __('dixlase-inquiry::front.validation.postal_code_format_western');
            $messages['street_address.required'] = __('dixlase-inquiry::front.validation.street_address_required');
            $messages['city.required'] = __('dixlase-inquiry::front.validation.city_required');
            $messages['phone.required'] = __('dixlase-inquiry::front.validation.phone_required');
            $messages['phone.regex'] = __('dixlase-inquiry::front.validation.phone_format_western');
        } else {
            $messages['postal_code_1.required'] = __('dixlase-inquiry::front.validation.postal_code_1_required');
            $messages['postal_code_1.digits'] = __('dixlase-inquiry::front.validation.postal_code_1_digits');
            $messages['postal_code_2.required'] = __('dixlase-inquiry::front.validation.postal_code_2_required');
            $messages['postal_code_2.digits'] = __('dixlase-inquiry::front.validation.postal_code_2_digits');
            $messages['prefecture.required'] = __('dixlase-inquiry::front.validation.prefecture_required');
            $messages['city.required'] = __('dixlase-inquiry::front.validation.city_required');
            $messages['address_line.required'] = __('dixlase-inquiry::front.validation.address_line_required');
            $messages['phone_1.required'] = __('dixlase-inquiry::front.validation.phone_1_required');
            $messages['phone_1.regex'] = __('dixlase-inquiry::front.validation.phone_1_format');
            $messages['phone_2.required'] = __('dixlase-inquiry::front.validation.phone_2_required');
            $messages['phone_2.regex'] = __('dixlase-inquiry::front.validation.phone_2_format');
            $messages['phone_3.required'] = __('dixlase-inquiry::front.validation.phone_3_required');
            $messages['phone_3.regex'] = __('dixlase-inquiry::front.validation.phone_3_format');
        }

        return $messages;
    }
}
