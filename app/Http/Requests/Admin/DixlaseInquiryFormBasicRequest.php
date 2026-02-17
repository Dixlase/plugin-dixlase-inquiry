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

namespace Plugins\DixlaseInquiry\App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class DixlaseInquiryFormBasicRequest extends FormRequest
{
    /**
     * リクエストの認可判定
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * バリデーションルールの設定
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'form_locale' => 'required|string|in:' . implode(',', config('dixlase-inquiry.locales', ['ja', 'en'])),
            'use_single_page' => 'boolean',
            'inquiry_url_slug' => 'required|string|max:100|regex:/^[a-z0-9\-]+$/',
            'name_order_western' => 'boolean',
            'show_subject' => 'boolean',
            'subject_required' => 'boolean',
            'show_postal_code' => 'boolean',
            'postal_code_required' => 'boolean',
            'show_phone' => 'boolean',
            'phone_required' => 'boolean',
            'show_gender' => 'boolean',
            'gender_required' => 'boolean',
            'show_confirmation_page' => 'boolean',
            'privacy_consent_enabled' => 'boolean',
            'privacy_policy_url' => 'nullable|url|max:500',
            'privacy_consent_text' => 'nullable|string|max:500',
            'throttle_enabled' => 'boolean',
            'throttle_max_attempts' => 'required_if:throttle_enabled,true|integer|min:1|max:100',
            'throttle_decay_minutes' => 'required_if:throttle_enabled,true|integer|min:1|max:1440',
        ];
    }

    /**
     * バリデーション前のデータ準備
     * チェックボックスの未チェック時の値を false に設定
     */
    protected function prepareForValidation(): void
    {
        $booleanFields = [
            'use_single_page',
            'name_order_western',
            'show_subject',
            'subject_required',
            'show_postal_code',
            'postal_code_required',
            'show_phone',
            'phone_required',
            'show_gender',
            'gender_required',
            'show_confirmation_page',
            'privacy_consent_enabled',
            'throttle_enabled',
        ];

        $data = [];
        foreach ($booleanFields as $field) {
            $data[$field] = $this->has($field) ? (bool) $this->input($field) : false;
        }

        // 住所は郵便番号と連動
        $data['show_address'] = $data['show_postal_code'];
        $data['address_required'] = $data['postal_code_required'];

        $this->merge($data);
    }

    /**
     * エラーメッセージのカスタマイズ
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'inquiry_url_slug.required' => __('dixlase-inquiry::admin.validation.inquiry_url_slug_required'),
            'inquiry_url_slug.regex' => __('dixlase-inquiry::admin.validation.inquiry_url_slug_format'),
        ];
    }
}
