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

class DixlaseInquirySettingsRequest extends FormRequest
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
     */
    public function rules(): array
    {
        return [
            'admin_email' => 'required|email|max:255',
            'subject' => 'nullable|string|max:255',
            'body' => 'nullable|string',
            'completion_title' => 'nullable|string|max:255',
            'completion_message' => 'nullable|string',
            'use_recaptcha' => 'boolean',
            'show_phone' => 'boolean',
            'phone_required' => 'boolean',
            'show_address' => 'boolean',
            'address_required' => 'boolean',
            'show_subject' => 'boolean',
            'subject_required' => 'boolean',
            'show_postal_code' => 'boolean',
            'postal_code_required' => 'boolean',
            'auto_reply_enabled' => 'boolean',
            'auto_reply_from_email' => 'nullable|email|max:255',
            'auto_reply_subject' => 'nullable|string|max:255',
            'auto_reply_body' => 'nullable|string',
            'use_single_page' => 'boolean',
            'show_confirmation_page' => 'boolean',
            'inquiry_url_slug' => 'required|string|max:100|regex:/^[a-z0-9\-]+$/',
            'name_order_western' => 'boolean',
        ];
    }

    /**
     * バリデーション前のデータ準備
     * チェックボックスの未チェック時の値を0に設定
     */
    protected function prepareForValidation(): void
    {
        $booleanFields = [
            'use_recaptcha',
            'show_phone',
            'phone_required',
            'show_address',
            'address_required',
            'show_subject',
            'subject_required',
            'show_postal_code',
            'postal_code_required',
            'auto_reply_enabled',
            'use_single_page',
            'show_confirmation_page',
            'name_order_western',
        ];

        $data = [];
        foreach ($booleanFields as $field) {
            $data[$field] = $this->has($field) ? (bool) $this->input($field) : false;
        }

        $this->merge($data);
    }

    /**
     * エラーメッセージのカスタマイズ
     */
    public function messages(): array
    {
        return [
            'admin_email.required' => __('dixlase-inquiry::admin.validation.admin_email_required'),
            'admin_email.email' => __('dixlase-inquiry::admin.validation.admin_email_invalid'),
            'auto_reply_from_email.email' => __('dixlase-inquiry::admin.validation.auto_reply_from_email_invalid'),
            'inquiry_url_slug.required' => __('dixlase-inquiry::admin.validation.inquiry_url_slug_required'),
            'inquiry_url_slug.regex' => __('dixlase-inquiry::admin.validation.inquiry_url_slug_format'),
        ];
    }
}
