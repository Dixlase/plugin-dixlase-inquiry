<?php

/**
 * This file is part of Dixlase Inquiry.
 *
 * Copyright (C) 2026 exc-D inc. and Dixlase contributors
 * https://exc-d.com
 *
 * Dixlase Inquiry is dual-licensed. You may use this file under either:
 *
 *   (a) the GNU General Public License version 3 or later, as published
 *       by the Free Software Foundation; or
 *
 *   (b) a commercial license agreement obtained from exc-D inc.
 *
 * Unless you have entered into a commercial license agreement, this
 * file is governed by the GPL terms below.
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

use App\Rules\UniqueRouteSlug;
use Illuminate\Foundation\Http\FormRequest;
use Plugins\DixlaseInquiry\App\Support\InquiryLocaleSupport;

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
            'form_heading' => 'nullable|string|max:100',
            'form_description' => 'nullable|string|max:500',
            'lang' => 'required|string|in:auto,'.implode(',', InquiryLocaleSupport::enabledLocales()),
            'use_single_page' => 'boolean',
            'inquiry_url_slug' => ['nullable', 'string', 'max:100', 'regex:/^[a-z0-9\-]*$/', UniqueRouteSlug::for('dixlase-inquiry:directory')],
            'name_order_western' => 'required|string|in:0,1,auto',
            'show_subject' => 'boolean',
            'subject_required' => 'boolean',
            'show_address' => 'boolean',
            'postal_code_required' => 'boolean',
            'address_required' => 'boolean',
            'show_phone' => 'boolean',
            'phone_required' => 'boolean',
            'show_gender' => 'boolean',
            'gender_required' => 'boolean',
            'show_gender_other' => 'boolean',
            'show_gender_prefer_not_to_say' => 'boolean',
            'email_confirm_paste_disabled' => 'boolean',
            'show_kana' => 'boolean',
            'require_kana' => 'boolean',
            'show_confirmation_page' => 'boolean',
            'privacy_consent_enabled' => 'boolean',
            'privacy_policy_url' => [
                'nullable',
                'string',
                'max:500',
                function (string $attribute, mixed $value, \Closure $fail): void {
                    if ($value === null || $value === '') {
                        return;
                    }

                    if (! is_string($value)) {
                        $fail(__('dixlase-inquiry::admin.validation.privacy_policy_url_format'));

                        return;
                    }

                    // This value is interpolated into an href by
                    // __('...privacy_consent', ['url' => $privacyUrl]), and
                    // __() does not escape its replacements. A site-relative
                    // path used to be accepted with no further checking, so
                    //
                    //     /policy" onmouseover="alert(1)
                    //
                    // passed validation and closed the attribute in the
                    // rendered consent label -- on the public form, not just
                    // the admin preview. Characters that can end an attribute
                    // or open a tag are refused here regardless of the form
                    // the value takes.
                    if (preg_match('/["\'<>]/', $value) === 1) {
                        $fail(__('dixlase-inquiry::admin.validation.privacy_policy_url_format'));

                        return;
                    }

                    // Site-relative path. `//host` is excluded: it carries no
                    // scheme but inherits the page's and leaves the origin.
                    if (str_starts_with($value, '/')) {
                        if (str_starts_with($value, '//')) {
                            $fail(__('dixlase-inquiry::admin.validation.privacy_policy_url_format'));
                        }

                        return;
                    }

                    if (filter_var($value, FILTER_VALIDATE_URL) === false) {
                        $fail(__('dixlase-inquiry::admin.validation.privacy_policy_url_format'));

                        return;
                    }

                    // FILTER_VALIDATE_URL accepts javascript://%0aalert(1), so
                    // the scheme is checked separately. Control characters go
                    // first because browsers ignore them inside a scheme.
                    $scheme = strtolower((string) parse_url(
                        preg_replace('/[\x00-\x20]/', '', $value) ?? '',
                        PHP_URL_SCHEME
                    ));

                    if (! in_array($scheme, ['http', 'https'], true)) {
                        $fail(__('dixlase-inquiry::admin.validation.privacy_policy_url_format'));
                    }
                },
            ],
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
            'show_subject',
            'subject_required',
            'show_address',
            'postal_code_required',
            'address_required',
            'show_phone',
            'phone_required',
            'show_gender',
            'gender_required',
            'show_gender_other',
            'show_gender_prefer_not_to_say',
            'email_confirm_paste_disabled',
            'show_kana',
            'require_kana',
            'show_confirmation_page',
            'privacy_consent_enabled',
            'throttle_enabled',
        ];

        $data = [];
        foreach ($booleanFields as $field) {
            $data[$field] = $this->has($field) ? (bool) $this->input($field) : false;
        }

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
