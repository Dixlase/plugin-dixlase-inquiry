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

class DixlaseInquiryAutoReplyRequest extends FormRequest
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
            'auto_reply_enabled' => 'boolean',
            'auto_reply_from_email' => 'nullable|email|max:255',
            'auto_reply_subject' => 'nullable|string|max:255',
            'auto_reply_body' => 'nullable|string',
        ];
    }

    /**
     * バリデーション前のデータ準備
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'auto_reply_enabled' => $this->has('auto_reply_enabled') ? (bool) $this->input('auto_reply_enabled') : false,
        ]);
    }

    /**
     * エラーメッセージのカスタマイズ
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'auto_reply_from_email.email' => __('dixlase-inquiry::admin.validation.auto_reply_from_email_invalid'),
        ];
    }
}
