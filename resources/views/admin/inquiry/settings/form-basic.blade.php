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
        init() {
            $dispatch('right-sidebar-active');
        },
    }">
        @csrf

        {{-- Main content: Form preview --}}
        <h2 class="text-xl font-semibold mb-4">{{ __('dixlase-inquiry::admin/inquiry/settings/form-basic.form_preview') }}</h2>
        <div class="p-4 bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-lg">
            @include('dixlase-inquiry::admin.inquiry.partials.form-preview')
        </div>

        {{-- Right sidebar --}}
        @include('dixlase-inquiry::admin.inquiry.settings.partials._right-sidebar')

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
