{{--
This file is part of Dixlase Inquiry.

Copyright (C) 2026 exc-D inc.
Website: https://exc-d.com

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program. If not, see <https://www.gnu.org/licenses/>.
--}}

@extends('layouts.admin')

@section('content')
<div class="mx-auto">
    <form id="form-basic-settings-form" action="{{ route('dixlase-inquiry::admin.inquiry.settings.form-basic.update') }}" method="POST" x-data="{
        selectedLocale: '{{ old('lang', $settings->lang ?? 'auto') }}',
        useSinglePage: '{{ old('use_single_page', $settings->use_single_page ?? true) ? '1' : '0' }}',
        inquiryUrlSlug: '{{ old('inquiry_url_slug', $settings->inquiry_url_slug ?? 'inquiry') }}',
        nameOrderWestern: '{{ old('name_order_western', $settings->name_order_western ?? false) ? '1' : '0' }}',
        showSubject: '{{ old('show_subject', $settings->show_subject ?? false) ? '1' : '0' }}',
        subjectRequired: '{{ old('subject_required', $settings->subject_required ?? false) ? '1' : '0' }}',
        showPostalCode: '{{ old('show_postal_code', $settings->show_postal_code ?? false) ? '1' : '0' }}',
        postalCodeRequired: '{{ old('postal_code_required', $settings->postal_code_required ?? false) ? '1' : '0' }}',
        get showAddress() { return this.showPostalCode; },
        get addressRequired() { return this.postalCodeRequired; },
        showPhone: '{{ old('show_phone', $settings->show_phone ?? true) ? '1' : '0' }}',
        phoneRequired: '{{ old('phone_required', $settings->phone_required ?? false) ? '1' : '0' }}',
        showGender: '{{ old('show_gender', $settings->show_gender ?? false) ? '1' : '0' }}',
        genderRequired: '{{ old('gender_required', $settings->gender_required ?? false) ? '1' : '0' }}',
        showGenderOther: '{{ old('show_gender_other', $settings->show_gender_other ?? false) ? '1' : '0' }}',
        showGenderPreferNotToSay: '{{ old('show_gender_prefer_not_to_say', $settings->show_gender_prefer_not_to_say ?? false) ? '1' : '0' }}',
        emailConfirmPasteDisabled: '{{ old('email_confirm_paste_disabled', $settings->email_confirm_paste_disabled ?? true) ? '1' : '0' }}',
        showKana: '{{ old('show_kana', $settings->show_kana ?? false) ? '1' : '0' }}',
        requireKana: '{{ old('require_kana', $settings->require_kana ?? false) ? '1' : '0' }}',
        privacyConsentEnabled: '{{ old('privacy_consent_enabled', $settings->privacy_consent_enabled ?? false) ? '1' : '0' }}',
        privacyPolicyUrl: '{{ old('privacy_policy_url', $settings->privacy_policy_url ?? '') }}',
        privacyConsentText: '{{ old('privacy_consent_text', $settings->privacy_consent_text ?? '') }}',
        throttleEnabled: '{{ old('throttle_enabled', $settings->throttle_enabled ?? true) ? '1' : '0' }}',
        throttleMaxAttempts: '{{ old('throttle_max_attempts', $settings->throttle_max_attempts ?? 3) }}',
        throttleDecayMinutes: '{{ old('throttle_decay_minutes', $settings->throttle_decay_minutes ?? 5) }}',
        showConfirmationPage: '{{ old('show_confirmation_page', $settings->show_confirmation_page ?? true) ? '1' : '0' }}',
        labels: {{ Js::from($formTranslations) }},
        defaultLocale: '{{ config('app.locale') }}',
        getLabel(key) {
            const locale = this.selectedLocale === 'auto' ? this.defaultLocale : this.selectedLocale;
            return this.labels[locale]?.[key] ?? key;
        },
        openPreview() {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '{{ route('dixlase-inquiry::admin.inquiry.settings.form-basic.preview.store') }}';
            form.target = '_blank';
            const csrf = document.createElement('input');
            csrf.type = 'hidden';
            csrf.name = '_token';
            csrf.value = '{{ csrf_token() }}';
            form.appendChild(csrf);
            const settings = {
                lang: this.selectedLocale,
                name_order_western: this.nameOrderWestern,
                show_subject: this.showSubject,
                subject_required: this.subjectRequired,
                show_postal_code: this.showPostalCode,
                postal_code_required: this.postalCodeRequired,
                show_address: this.showPostalCode,
                address_required: this.postalCodeRequired,
                show_phone: this.showPhone,
                phone_required: this.phoneRequired,
                show_gender: this.showGender,
                gender_required: this.genderRequired,
                show_gender_other: this.showGenderOther,
                show_gender_prefer_not_to_say: this.showGenderPreferNotToSay,
                email_confirm_paste_disabled: this.emailConfirmPasteDisabled,
                show_kana: this.showKana,
                require_kana: this.requireKana,
                use_single_page: this.useSinglePage,
                inquiry_url_slug: this.inquiryUrlSlug,
                privacy_consent_enabled: this.privacyConsentEnabled,
                privacy_policy_url: this.privacyPolicyUrl,
                privacy_consent_text: this.privacyConsentText,
                throttle_enabled: this.throttleEnabled,
                throttle_max_attempts: this.throttleMaxAttempts,
                throttle_decay_minutes: this.throttleDecayMinutes,
                show_confirmation_page: this.showConfirmationPage,
            };
            Object.entries(settings).forEach(([k, v]) => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = k;
                input.value = v ?? '';
                form.appendChild(input);
            });
            document.body.appendChild(form);
            form.submit();
            form.remove();
        },
    }">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            {{-- 左カラム: 設定フォーム --}}
            <div>
                {{-- フォーム設定セクション --}}
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">{{ __('dixlase-inquiry::admin/inquiry/settings/form-basic.section_form_settings') }}</h2>
                <section class="mb-8">
                    {{-- 言語セレクタ --}}
                    <fieldset>
                        <legend>{{ __('dixlase-inquiry::admin/inquiry/settings/form-basic.lang') }}</legend>

                        <x-form-radio-card-group
                            name="lang"
                            :options="[
                                [
                                    'value' => 'auto',
                                    'label' => __('dixlase-inquiry::admin/inquiry/settings/form-basic.lang_auto'),
                                    'description' => __('dixlase-inquiry::admin/inquiry/settings/form-basic.lang_auto_desc'),
                                    'icon' => 'fas fa-magic',
                                ],
                                [
                                    'value' => 'ja',
                                    'label' => '日本語',
                                    'description' => 'Japanese',
                                    'icon' => 'fas fa-flag',
                                ],
                                [
                                    'value' => 'en',
                                    'label' => 'English',
                                    'description' => '英語',
                                    'icon' => 'fas fa-globe',
                                ],
                            ]"
                            :value="old('lang', $settings->lang ?? 'auto')"
                            xModel="selectedLocale"
                            :columns="3"
                            color="primary"
                        />
                        <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                            {{ __('dixlase-inquiry::admin/inquiry/settings/form-basic.lang_help') }}
                        </p>
                    </fieldset>

                    {{-- 入力形式 --}}
                    <fieldset>
                        <legend>{{ __('dixlase-inquiry::admin/inquiry/settings/form-basic.format_style') }}</legend>

                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                            <div class="lg:col-span-2">
                                <x-form-radio-card-group
                                    name="name_order_western"
                                    :options="[
                                        [
                                            'value' => '0',
                                            'label' => __('dixlase-inquiry::admin/inquiry/settings/form-basic.format_japanese'),
                                            'description' => __('dixlase-inquiry::admin/inquiry/settings/form-basic.format_japanese_desc'),
                                            'icon' => 'fas fa-flag',
                                        ],
                                        [
                                            'value' => '1',
                                            'label' => __('dixlase-inquiry::admin/inquiry/settings/form-basic.format_western'),
                                            'description' => __('dixlase-inquiry::admin/inquiry/settings/form-basic.format_western_desc'),
                                            'icon' => 'fas fa-globe',
                                        ],
                                    ]"
                                    :value="old('name_order_western', $settings->name_order_western ?? false) ? '1' : '0'"
                                    xModel="nameOrderWestern"
                                    :columns="2"
                                    color="primary"
                                />
                                <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                                    {{ __('dixlase-inquiry::admin/inquiry/settings/form-basic.format_style_help') }}
                                </p>
                            </div>
                        </div>
                    </fieldset>
                </section>

                {{-- フィールド設定セクション --}}
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">{{ __('dixlase-inquiry::admin/inquiry/settings/form-basic.section_field_settings') }}</h2>
                <section class="mb-8">
                    <fieldset>
                        <legend>{{ __('dixlase-inquiry::admin/inquiry/settings/form-basic.field_settings') }}</legend>

                        <x-ui-message type="info" :message="__('dixlase-inquiry::admin/inquiry/settings/form-basic.required_fields_note')" textSize="text-sm" />

                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                            {{-- メールアドレス確認欄：ペースト制限 --}}
                            <div class="lg:col-span-2">
                                <x-form-toggle
                                    name="email_confirm_paste_disabled"
                                    :label="__('dixlase-inquiry::admin/inquiry/settings/form-basic.email_confirm_paste_disabled')"
                                    :checked="old('email_confirm_paste_disabled', $settings->email_confirm_paste_disabled ?? true)"
                                    xModel="emailConfirmPasteDisabled"
                                />
                                <p class="text-sm text-gray-600 dark:text-gray-400">
                                    {{ __('dixlase-inquiry::admin/inquiry/settings/form-basic.email_confirm_paste_disabled_help') }}
                                </p>
                            </div>

                            {{-- カタカナ（フリガナ）フィールド --}}
                            <div :class="{ 'opacity-50 pointer-events-none': nameOrderWestern === '1' }">
                                <x-form-toggle
                                    name="show_kana"
                                    :label="__('dixlase-inquiry::admin/inquiry/settings/form-basic.show_kana')"
                                    :checked="old('show_kana', $settings->show_kana ?? false)"
                                    xModel="showKana"
                                    ::disabled="nameOrderWestern === '1'"
                                />
                            </div>
                            <div :class="{ 'opacity-50 pointer-events-none': nameOrderWestern === '1' }">
                                <x-form-toggle
                                    name="require_kana"
                                    :label="__('dixlase-inquiry::admin/inquiry/settings/form-basic.require_kana')"
                                    :checked="old('require_kana', $settings->require_kana ?? false)"
                                    xBind="(showKana === '1' && nameOrderWestern === '0')"
                                    xModel="requireKana"
                                />
                            </div>
                            <div class="lg:col-span-2">
                                <x-ui-message type="info" :message="__('dixlase-inquiry::admin/inquiry/settings/form-basic.show_kana_help')" textSize="text-sm" />
                            </div>
                            {{-- 題名フィールド --}}
                            <div>
                                <x-form-toggle
                                    name="show_subject"
                                    :label="__('dixlase-inquiry::admin/inquiry/settings/form-basic.show_subject')"
                                    :checked="old('show_subject', $settings->show_subject ?? false)"
                                    xModel="showSubject"
                                />
                            </div>
                            <div>
                                <x-form-toggle
                                    name="subject_required"
                                    :label="__('dixlase-inquiry::admin/inquiry/settings/form-basic.subject_required')"
                                    :checked="old('subject_required', $settings->subject_required ?? false)"
                                    xBind="(showSubject === '1')"
                                    xModel="subjectRequired"
                                />
                            </div>

                            {{-- 郵便番号・住所フィールド --}}
                            <div>
                                <x-form-toggle
                                    name="show_postal_code"
                                    :label="__('dixlase-inquiry::admin/inquiry/settings/form-basic.show_postal_address')"
                                    :checked="old('show_postal_code', $settings->show_postal_code ?? false)"
                                    xModel="showPostalCode"
                                />
                                <input type="hidden" name="show_address" :value="showPostalCode">
                            </div>
                            <div>
                                <x-form-toggle
                                    name="postal_code_required"
                                    :label="__('dixlase-inquiry::admin/inquiry/settings/form-basic.postal_address_required')"
                                    :checked="old('postal_code_required', $settings->postal_code_required ?? false)"
                                    xBind="(showPostalCode === '1')"
                                    xModel="postalCodeRequired"
                                />
                                <input type="hidden" name="address_required" :value="postalCodeRequired">
                            </div>

                            {{-- 電話番号フィールド --}}
                            <div>
                                <x-form-toggle
                                    name="show_phone"
                                    :label="__('dixlase-inquiry::admin/inquiry/settings/form-basic.show_phone')"
                                    :checked="old('show_phone', $settings->show_phone ?? true)"
                                    xModel="showPhone"
                                />
                            </div>
                            <div>
                                <x-form-toggle
                                    name="phone_required"
                                    :label="__('dixlase-inquiry::admin/inquiry/settings/form-basic.phone_required')"
                                    :checked="old('phone_required', $settings->phone_required ?? false)"
                                    xBind="(showPhone === '1')"
                                    xModel="phoneRequired"
                                />
                            </div>

                            {{-- 性別フィールド --}}
                            <div>
                                <x-form-toggle
                                    name="show_gender"
                                    :label="__('dixlase-inquiry::admin/inquiry/settings/form-basic.show_gender')"
                                    :checked="old('show_gender', $settings->show_gender ?? false)"
                                    xModel="showGender"
                                />
                            </div>
                            <div>
                                <x-form-toggle
                                    name="gender_required"
                                    :label="__('dixlase-inquiry::admin/inquiry/settings/form-basic.gender_required')"
                                    :checked="old('gender_required', $settings->gender_required ?? false)"
                                    xBind="(showGender === '1')"
                                    xModel="genderRequired"
                                />
                            </div>

                            {{-- 性別サブオプション --}}
                            <div class="lg:col-span-2 ml-6 pl-4 border-l-2 border-gray-200 dark:border-gray-700" :class="{ 'opacity-50 pointer-events-none': showGender === '0' }">
                                <x-ui-message type="info" :message="__('dixlase-inquiry::admin/inquiry/settings/form-basic.gender_default_note')" textSize="text-sm" />
                                <div class="space-y-3">
                                    <x-form-toggle
                                        name="show_gender_other"
                                        :label="__('dixlase-inquiry::admin/inquiry/settings/form-basic.show_gender_other')"
                                        :checked="old('show_gender_other', $settings->show_gender_other ?? false)"
                                        xModel="showGenderOther"
                                        ::disabled="showGender === '0'"
                                    />
                                    <x-form-toggle
                                        name="show_gender_prefer_not_to_say"
                                        :label="__('dixlase-inquiry::admin/inquiry/settings/form-basic.show_gender_prefer_not_to_say')"
                                        :checked="old('show_gender_prefer_not_to_say', $settings->show_gender_prefer_not_to_say ?? false)"
                                        xModel="showGenderPreferNotToSay"
                                        ::disabled="showGender === '0'"
                                    />
                                </div>
                            </div>


                        </div>
                    </fieldset>
                </section>

                {{-- フォーム表示設定セクション --}}
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">{{ __('dixlase-inquiry::admin/inquiry/settings/form-basic.section_display_settings') }}</h2>
                <section class="mb-8">
                    <fieldset>
                        <legend>{{ __('dixlase-inquiry::admin/inquiry/settings/form-basic.form_type') }}</legend>

                        <x-form-radio-card-group
                            name="use_single_page"
                            :options="[
                                [
                                    'value' => '1',
                                    'label' => __('dixlase-inquiry::admin/inquiry/settings/form-basic.single_page'),
                                    'description' => __('dixlase-inquiry::admin/inquiry/settings/form-basic.single_page_desc'),
                                    'icon' => 'fas fa-file',
                                ],
                                [
                                    'value' => '0',
                                    'label' => __('dixlase-inquiry::admin/inquiry/settings/form-basic.separate_pages'),
                                    'description' => __('dixlase-inquiry::admin/inquiry/settings/form-basic.separate_pages_desc'),
                                    'icon' => 'fas fa-copy',
                                ],
                            ]"
                            :value="old('use_single_page', $settings->use_single_page ?? true) ? '1' : '0'"
                            xModel="useSinglePage"
                            :columns="2"
                            color="primary"
                        />

                        {{-- 別ページ選択時: URL編集フィールド --}}
                        <div x-show="useSinglePage === '0'" x-cloak class="mt-4">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                {{ __('dixlase-inquiry::admin/inquiry/settings/form-basic.inquiry_url') }}
                            </label>
                            <div class="flex items-center gap-2">
                                <span class="text-gray-600 dark:text-gray-400">{{ url('/') }}/</span>
                                <div class="flex-1">
                                    <input type="text"
                                           name="inquiry_url_slug"
                                           x-model="inquiryUrlSlug"
                                           placeholder="inquiry"
                                           class="block w-full px-3 py-2 bg-gray-50 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm dark:bg-gray-800 dark:border-gray-500 dark:focus:border-indigo-500 dark:focus:ring-indigo-500 dark:text-white">
                                </div>
                            </div>
                            <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                                {{ __('dixlase-inquiry::admin/inquiry/settings/form-basic.inquiry_url_slug_help') }}
                            </p>
                        </div>

                        {{-- シングルページ選択時: hidden field --}}
                        <div x-show="useSinglePage === '1'" x-cloak>
                            <input type="hidden" name="inquiry_url_slug" :value="inquiryUrlSlug">
                        </div>
                    </fieldset>

                    {{-- 確認画面設定 --}}
                    <fieldset>
                        <x-form-toggle
                            name="show_confirmation_page"
                            :label="__('dixlase-inquiry::admin/inquiry/settings/form-basic.show_confirmation')"
                            :checked="old('show_confirmation_page', $settings->show_confirmation_page ?? true)"
                            xModel="showConfirmationPage"
                        />
                        <p class="text-sm text-gray-600 dark:text-gray-400">
                            {{ __('dixlase-inquiry::admin/inquiry/settings/form-basic.show_confirmation_help') }}
                        </p>
                    </fieldset>
                </section>

                {{-- プライバシー同意設定セクション --}}
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">{{ __('dixlase-inquiry::admin/inquiry/settings/form-basic.section_privacy') }}</h2>
                <section class="mb-8">
                    <fieldset>
                        <legend>{{ __('dixlase-inquiry::admin/inquiry/settings/form-basic.privacy_settings') }}</legend>

                        <div class="space-y-4">
                            <div>
                                <x-form-toggle
                                    name="privacy_consent_enabled"
                                    :label="__('dixlase-inquiry::admin/inquiry/settings/form-basic.privacy_consent_enabled')"
                                    :checked="old('privacy_consent_enabled', $settings->privacy_consent_enabled ?? false)"
                                    xModel="privacyConsentEnabled"
                                />
                                <p class="text-sm text-gray-600 dark:text-gray-400">
                                    {{ __('dixlase-inquiry::admin/inquiry/settings/form-basic.privacy_consent_enabled_help') }}
                                </p>
                            </div>

                            <div :class="{ 'opacity-50 pointer-events-none': privacyConsentEnabled === '0' }" class="space-y-4 ml-4 pl-4 border-l-2 border-gray-200 dark:border-gray-700">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        {{ __('dixlase-inquiry::admin/inquiry/settings/form-basic.privacy_policy_url') }}
                                    </label>
                                    <input type="url"
                                           name="privacy_policy_url"
                                           x-model="privacyPolicyUrl"
                                           :disabled="privacyConsentEnabled === '0'"
                                           placeholder="https://example.com/privacy"
                                           class="block w-full px-3 py-2 bg-gray-50 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm dark:bg-gray-800 dark:border-gray-500 dark:focus:border-indigo-500 dark:focus:ring-indigo-500 dark:text-white">
                                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                        {{ __('dixlase-inquiry::admin/inquiry/settings/form-basic.privacy_policy_url_help') }}
                                    </p>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        {{ __('dixlase-inquiry::admin/inquiry/settings/form-basic.privacy_consent_text') }}
                                    </label>
                                    <input type="text"
                                           name="privacy_consent_text"
                                           x-model="privacyConsentText"
                                           :disabled="privacyConsentEnabled === '0'"
                                           placeholder="{{ __('dixlase-inquiry::admin/inquiry/settings/form-basic.privacy_consent_text_placeholder') }}"
                                           class="block w-full px-3 py-2 bg-gray-50 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm dark:bg-gray-800 dark:border-gray-500 dark:focus:border-indigo-500 dark:focus:ring-indigo-500 dark:text-white">
                                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                        {{ __('dixlase-inquiry::admin/inquiry/settings/form-basic.privacy_consent_text_help') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </fieldset>
                </section>

                {{-- 送信間隔制限セクション --}}
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">{{ __('dixlase-inquiry::admin/inquiry/settings/form-basic.section_throttle') }}</h2>
                <section class="mb-8">
                    <fieldset>
                        <legend>{{ __('dixlase-inquiry::admin/inquiry/settings/form-basic.throttle_settings') }}</legend>

                        <div class="space-y-4">
                            <div>
                                <x-form-toggle
                                    name="throttle_enabled"
                                    :label="__('dixlase-inquiry::admin/inquiry/settings/form-basic.throttle_enabled')"
                                    :checked="old('throttle_enabled', $settings->throttle_enabled ?? true)"
                                    xModel="throttleEnabled"
                                />
                                <p class="text-sm text-gray-600 dark:text-gray-400">
                                    {{ __('dixlase-inquiry::admin/inquiry/settings/form-basic.throttle_enabled_help') }}
                                </p>
                            </div>

                            <div :class="{ 'opacity-50 pointer-events-none': throttleEnabled === '0' }" class="ml-4 pl-4 border-l-2 border-gray-200 dark:border-gray-700">
                                <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                            {{ __('dixlase-inquiry::admin/inquiry/settings/form-basic.throttle_max_attempts') }}
                                        </label>
                                        <input type="number"
                                               name="throttle_max_attempts"
                                               x-model="throttleMaxAttempts"
                                               :disabled="throttleEnabled === '0'"
                                               min="1"
                                               max="100"
                                               class="block w-full px-3 py-2 bg-gray-50 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm dark:bg-gray-800 dark:border-gray-500 dark:focus:border-indigo-500 dark:focus:ring-indigo-500 dark:text-white">
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                            {{ __('dixlase-inquiry::admin/inquiry/settings/form-basic.throttle_decay_minutes') }}
                                        </label>
                                        <input type="number"
                                               name="throttle_decay_minutes"
                                               x-model="throttleDecayMinutes"
                                               :disabled="throttleEnabled === '0'"
                                               min="1"
                                               max="1440"
                                               class="block w-full px-3 py-2 bg-gray-50 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm dark:bg-gray-800 dark:border-gray-500 dark:focus:border-indigo-500 dark:focus:ring-indigo-500 dark:text-white">
                                    </div>
                                </div>
                                <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                                    {{ __('dixlase-inquiry::admin/inquiry/settings/form-basic.throttle_help') }}
                                </p>
                            </div>
                        </div>
                    </fieldset>
                </section>
            </div>

            {{-- 右カラム: リアルタイムプレビュー --}}
            <div class="xl:sticky xl:top-4 xl:self-start">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-xl font-semibold">{{ __('dixlase-inquiry::admin/inquiry/settings/form-basic.form_preview') }}</h2>
                    <x-form-button
                        type="button"
                        variant="secondary"
                        size="sm"
                        icon="fas fa-external-link-alt"
                        :label="__('dixlase-inquiry::admin/inquiry/settings/form-basic.preview_button')"
                        x-on:click="openPreview()"
                    />
                </div>
                <div class="p-4 bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-lg">
                    @include('dixlase-inquiry::admin.inquiry.partials.form-preview')
                </div>
            </div>
        </div>

    </form>
</div>
@endsection

@section('save')
    <x-admin.save-button
        id_confirmation="confirmFormBasicSettingsModal"
        :label="__('common.save')"
        :title="__('dixlase-inquiry::admin/inquiry/settings/form-basic.confirm_title')"
        :message="__('dixlase-inquiry::admin/inquiry/settings/form-basic.confirm_message')"
        :confirm_label="__('common.save')"
        :cancel_label="__('common.cancel')"
        form="form-basic-settings-form"
    />
@endsection
