{{--
This file is part of Dixlase Inquiry.

Copyright (C) 2026 exc-D inc. and Dixlase contributors
https://exc-d.com

Dixlase Inquiry is dual-licensed. You may use this file under either:

  (a) the GNU General Public License version 3 or later, as published
      by the Free Software Foundation; or

  (b) a commercial license agreement obtained from exc-D inc.

Unless you have entered into a commercial license agreement, this
file is governed by the GPL terms below.

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
<div class="mx-auto"
     x-data="{
        storeInquiries: {{ old('store_inquiries', $settings->store_inquiries) ? 'true' : 'false' }},
        retentionMode: '{{ old('retention_mode', $retentionMode) }}',
     }">
    <form id="privacy-settings-form" action="{{ route('dixlase-inquiry::admin.inquiry.settings.privacy.update') }}" method="POST">
        @csrf

        {{-- 保存ポリシーの説明 --}}
        <x-ui-message
            type="info"
            icon="fas fa-info-circle"
            :message="__('dixlase-inquiry::admin/inquiry/settings/privacy.intro')"
        />

        <section>
            <fieldset>
                <legend>{{ __('dixlase-inquiry::admin/inquiry/settings/privacy.store_section') }}</legend>

                <x-form-toggle
                    name="store_inquiries"
                    :label="__('dixlase-inquiry::admin/inquiry/settings/privacy.store_label')"
                    :checked="old('store_inquiries', $settings->store_inquiries)"
                    xModel="storeInquiries"
                />
                <p class="text-sm text-gray-600 dark:text-gray-400">
                    {{ __('dixlase-inquiry::admin/inquiry/settings/privacy.store_help') }}
                </p>
            </fieldset>

            {{-- 保存期間（保存ONのときだけ表示） --}}
            <fieldset x-show="storeInquiries" x-cloak>
                <legend>{{ __('dixlase-inquiry::admin/inquiry/settings/privacy.retention_section') }}</legend>

                <x-form-label for="retention_mode" :label="__('dixlase-inquiry::admin/inquiry/settings/privacy.retention_mode_label')" />
                <x-form-select
                    name="retention_mode"
                    :value="old('retention_mode', $retentionMode)"
                    :options="[
                        'indefinite' => __('dixlase-inquiry::admin/inquiry/settings/privacy.retention_option_indefinite'),
                        '30' => __('dixlase-inquiry::admin/inquiry/settings/privacy.retention_option_days', ['days' => 30]),
                        '90' => __('dixlase-inquiry::admin/inquiry/settings/privacy.retention_option_days', ['days' => 90]),
                        '180' => __('dixlase-inquiry::admin/inquiry/settings/privacy.retention_option_days', ['days' => 180]),
                        '365' => __('dixlase-inquiry::admin/inquiry/settings/privacy.retention_option_days', ['days' => 365]),
                        'custom' => __('dixlase-inquiry::admin/inquiry/settings/privacy.retention_option_custom'),
                    ]"
                    xModel="retentionMode"
                />
                <p class="text-sm text-gray-600 dark:text-gray-400">
                    {{ __('dixlase-inquiry::admin/inquiry/settings/privacy.retention_help') }}
                </p>

                <div x-show="retentionMode === 'custom'" x-cloak class="mt-4">
                    <x-form-label for="retention_days_custom" :label="__('dixlase-inquiry::admin/inquiry/settings/privacy.retention_custom_label')" />
                    <x-form-text
                        name="retention_days_custom"
                        type="number"
                        min="1"
                        max="3650"
                        :value="old('retention_days_custom', $retentionCustom)"
                        :placeholder="__('dixlase-inquiry::admin/inquiry/settings/privacy.retention_custom_placeholder')"
                    />
                    <p class="text-sm text-gray-600 dark:text-gray-400">
                        {{ __('dixlase-inquiry::admin/inquiry/settings/privacy.retention_custom_help') }}
                    </p>
                </div>
            </fieldset>
        </section>
    </form>
</div>
@endsection

@section('save')
    <x-admin.save-button
        id_confirmation="confirmPrivacySettingsModal"
        :label="__('common.save')"
        :title="__('dixlase-inquiry::admin/inquiry/settings/privacy.confirm_title')"
        :message="__('dixlase-inquiry::admin/inquiry/settings/privacy.confirm_message')"
        :confirm_label="__('common.save')"
        :cancel_label="__('common.cancel')"
        form="privacy-settings-form"
    />
@endsection
