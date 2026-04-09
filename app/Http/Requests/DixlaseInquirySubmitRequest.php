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

use App\Helpers\CaptchaHelper;
use App\Traits\VerifiesCaptcha;
use Illuminate\Foundation\Http\FormRequest;
use Plugins\DixlaseInquiry\App\Models\DixlaseInquirySetting;

class DixlaseInquirySubmitRequest extends FormRequest
{
    use VerifiesCaptcha;

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
        $isWestern = (bool) ($settings->name_order_western ?? false);

        $rules = [
            'last_name' => 'required|string|max:255',
            'first_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'email_confirmation' => 'required|email|same:email',
            'message' => 'required|string',
        ];

        // 題名（設定により必須/任意）
        if ($settings->show_subject ?? false) {
            $rules['subject'] = ($settings->subject_required ?? false)
                ? 'required|string|max:255'
                : 'nullable|string|max:255';
        }

        // 郵便番号（設定により必須/任意、形式は日本式/欧米式で異なる）
        if ($settings->show_address ?? false) {
            if ($isWestern) {
                // 欧米式: 単一フィールド
                $postalCodeRule = 'regex:/^[A-Za-z0-9\s\-]{3,10}$/';
                $rules['postal_code'] = ($settings->postal_code_required ?? false)
                    ? ['required', 'string', $postalCodeRule]
                    : ['nullable', 'string', $postalCodeRule];
            } else {
                // 日本式: 2分割フィールド（3桁-4桁）
                $rules['postal_code_1'] = ($settings->postal_code_required ?? false)
                    ? 'required|string|digits:3'
                    : 'nullable|string|digits:3';
                $rules['postal_code_2'] = ($settings->postal_code_required ?? false)
                    ? 'required|string|digits:4'
                    : 'nullable|string|digits:4';
            }
        }

        // 住所（設定により必須/任意、形式は日本式/欧米式で異なる）
        if ($settings->show_address ?? false) {
            if ($isWestern) {
                // 欧米式: 番地・建物・市・州・郵便番号・国
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
                // 日本式: 都道府県・市区町村・番地・建物名
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

        // 電話番号（設定により必須/任意、形式は日本式/欧米式で異なる）
        if ($settings->show_phone ?? false) {
            if ($isWestern) {
                // 欧米式: 単一フィールド
                $phoneRule = 'regex:/^[\+]?[0-9\s\-\(\)]{7,20}$/';
                $rules['phone'] = ($settings->phone_required ?? false)
                    ? ['required', 'string', $phoneRule]
                    : ['nullable', 'string', $phoneRule];
            } else {
                // 日本式: 3分割フィールド
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
        if (! $isWestern && ($settings->show_kana ?? false)) {
            $kanaRule = 'regex:/^[ァ-ヶー]+$/u';
            $rules['last_name_kana'] = ($settings->require_kana ?? false)
                ? ['required', 'string', 'max:50', $kanaRule]
                : ['nullable', 'string', 'max:50', $kanaRule];
            $rules['first_name_kana'] = ($settings->require_kana ?? false)
                ? ['required', 'string', 'max:50', $kanaRule]
                : ['nullable', 'string', 'max:50', $kanaRule];
        }

        // 性別（設定により必須/任意、有効な選択肢のみ許可）
        if ($settings->show_gender ?? false) {
            $genderValues = ['male', 'female'];
            if ($settings->show_gender_other ?? false) {
                $genderValues[] = 'other';
            }
            if ($settings->show_gender_prefer_not_to_say ?? false) {
                $genderValues[] = 'prefer_not_to_say';
            }
            $genderOptions = 'in:'.implode(',', $genderValues);
            $rules['gender'] = ($settings->gender_required ?? false)
                ? ['required', 'string', $genderOptions]
                : ['nullable', 'string', $genderOptions];
        }

        // プライバシー同意（設定により必須）
        if ($settings->privacy_consent_enabled ?? false) {
            $rules['privacy_agreed'] = ['required', 'accepted'];
        }

        // CAPTCHA（ドライバー固有のフィールド名・ルールを動的取得）
        if (CaptchaHelper::shouldShowCaptcha('dixlase-inquiry.inquiry_contact')) {
            $rules = array_merge($rules, $this->getCaptchaRules());
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
            'email_confirmation.required' => __('dixlase-inquiry::front.validation.email_confirmation_required'),
            'email_confirmation.same' => __('dixlase-inquiry::front.validation.email_confirmation_mismatch'),
            'message.required' => __('dixlase-inquiry::front.validation.message_required'),
            'subject.required' => __('dixlase-inquiry::front.validation.subject_required'),
            'gender.required' => __('dixlase-inquiry::front.validation.gender_required'),
            'gender.in' => __('dixlase-inquiry::front.validation.gender_invalid'),
            'privacy_agreed.required' => __('dixlase-inquiry::front.validation.privacy_agreed_required'),
            'privacy_agreed.accepted' => __('dixlase-inquiry::front.validation.privacy_agreed_required'),
        ];

        // CAPTCHAエラーメッセージ（ドライバー固有のフィールド名に対応）
        foreach (array_keys($this->getCaptchaRules()) as $captchaField) {
            $messages["{$captchaField}.required"] = __('dixlase-inquiry::front.validation.recaptcha_required');
        }

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

    /**
     * バリデーション属性名のカスタマイズ
     */
    public function attributes(): array
    {
        $attributes = [
            'last_name' => __('dixlase-inquiry::front.form.last_name'),
            'first_name' => __('dixlase-inquiry::front.form.first_name'),
            'email' => __('dixlase-inquiry::front.form.email'),
            'email_confirmation' => __('dixlase-inquiry::front.form.email_confirmation'),
            'last_name_kana' => __('dixlase-inquiry::front.form.last_name_kana'),
            'first_name_kana' => __('dixlase-inquiry::front.form.first_name_kana'),
            'message' => __('dixlase-inquiry::front.form.message'),
            'subject' => __('dixlase-inquiry::front.form.subject'),
            'postal_code' => __('dixlase-inquiry::front.form.postal_code'),
            'postal_code_1' => __('dixlase-inquiry::front.form.postal_code'),
            'postal_code_2' => __('dixlase-inquiry::front.form.postal_code'),
            'prefecture' => __('dixlase-inquiry::front.form.prefecture'),
            'address_line' => __('dixlase-inquiry::front.form.address_line'),
            'building' => __('dixlase-inquiry::front.form.building'),
            'street_address' => __('dixlase-inquiry::front.form.street_address'),
            'city' => __('dixlase-inquiry::front.form.city'),
            'state' => __('dixlase-inquiry::front.form.state'),
            'country' => __('dixlase-inquiry::front.form.country'),
            'phone' => __('dixlase-inquiry::front.form.phone'),
            'phone_1' => __('dixlase-inquiry::front.form.phone'),
            'phone_2' => __('dixlase-inquiry::front.form.phone'),
            'phone_3' => __('dixlase-inquiry::front.form.phone'),
            'gender' => __('dixlase-inquiry::front.form.gender'),
        ];

        // CAPTCHAフィールド名の属性（ドライバー固有のフィールド名に対応）
        foreach (array_keys($this->getCaptchaRules()) as $captchaField) {
            $attributes[$captchaField] = __('dixlase-inquiry::front.form.recaptcha');
        }

        return $attributes;
    }
}
