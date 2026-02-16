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
        nameOrderWestern: '{{ old('name_order_western', $settings->name_order_western ?? false) ? '1' : '0' }}',
        showSubject: {{ old('show_subject', $settings->show_subject ?? false) ? 'true' : 'false' }},
        subjectRequired: {{ old('subject_required', $settings->subject_required ?? false) ? 'true' : 'false' }},
        showPostalCode: {{ old('show_postal_code', $settings->show_postal_code ?? false) ? 'true' : 'false' }},
        postalCodeRequired: {{ old('postal_code_required', $settings->postal_code_required ?? false) ? 'true' : 'false' }},
        get showAddress() { return this.showPostalCode; },
        get addressRequired() { return this.postalCodeRequired; },
        showPhone: {{ old('show_phone', $settings->show_phone ?? true) ? 'true' : 'false' }},
        phoneRequired: {{ old('phone_required', $settings->phone_required ?? false) ? 'true' : 'false' }},
        showGender: {{ old('show_gender', $settings->show_gender ?? false) ? 'true' : 'false' }},
        genderRequired: {{ old('gender_required', $settings->gender_required ?? false) ? 'true' : 'false' }},
    }">
        @csrf

        {{-- 入力形式 --}}
        <section class="mb-8">
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

        {{-- フィールド設定 --}}
        <section class="mb-8">
            <fieldset>
                <legend>{{ __('dixlase-inquiry::admin/inquiry/settings/form-basic.field_settings') }}</legend>

                <div class="mb-4 p-3 bg-gray-50 dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700">
                    <p class="text-sm text-gray-600 dark:text-gray-400">
                        <span class="font-medium">{{ __('dixlase-inquiry::admin/inquiry/settings/form-basic.required_fields_note') }}</span>
                    </p>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    {{-- 題名フィールド --}}
                    <div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="show_subject" x-model="showSubject" {{ old('show_subject', $settings->show_subject ?? false) ? 'checked' : '' }} class="sr-only peer">
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none dark:bg-gray-600 rounded-full peer peer-checked:bg-indigo-600 transition-colors"></div>
                            <div class="absolute left-1 top-1 w-4 h-4 bg-white border border-gray-300 rounded-full transition-all peer-checked:translate-x-full peer-checked:border-white"></div>
                            <span class="ml-3 text-sm text-gray-700 dark:text-gray-300">{{ __('dixlase-inquiry::admin/inquiry/settings/form-basic.show_subject') }}</span>
                        </label>
                    </div>
                    <div>
                        <x-form-toggle
                            name="subject_required"
                            :label="__('dixlase-inquiry::admin/inquiry/settings/form-basic.subject_required')"
                            :checked="old('subject_required', $settings->subject_required ?? false)"
                            xBind="showSubject"
                            xModel="subjectRequired"
                        />
                    </div>

                    {{-- 郵便番号・住所フィールド --}}
                    <div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="show_postal_code" x-model="showPostalCode" {{ old('show_postal_code', $settings->show_postal_code ?? false) ? 'checked' : '' }} class="sr-only peer">
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none dark:bg-gray-600 rounded-full peer peer-checked:bg-indigo-600 transition-colors"></div>
                            <div class="absolute left-1 top-1 w-4 h-4 bg-white border border-gray-300 rounded-full transition-all peer-checked:translate-x-full peer-checked:border-white"></div>
                            <span class="ml-3 text-sm text-gray-700 dark:text-gray-300">{{ __('dixlase-inquiry::admin/inquiry/settings/form-basic.show_postal_address') }}</span>
                        </label>
                        <input type="hidden" name="show_address" :value="showPostalCode ? '1' : '0'">
                    </div>
                    <div>
                        <x-form-toggle
                            name="postal_code_required"
                            :label="__('dixlase-inquiry::admin/inquiry/settings/form-basic.postal_address_required')"
                            :checked="old('postal_code_required', $settings->postal_code_required ?? false)"
                            xBind="showPostalCode"
                            xModel="postalCodeRequired"
                        />
                        <input type="hidden" name="address_required" :value="postalCodeRequired ? '1' : '0'">
                    </div>

                    {{-- 電話番号フィールド --}}
                    <div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="show_phone" x-model="showPhone" {{ old('show_phone', $settings->show_phone ?? true) ? 'checked' : '' }} class="sr-only peer">
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none dark:bg-gray-600 rounded-full peer peer-checked:bg-indigo-600 transition-colors"></div>
                            <div class="absolute left-1 top-1 w-4 h-4 bg-white border border-gray-300 rounded-full transition-all peer-checked:translate-x-full peer-checked:border-white"></div>
                            <span class="ml-3 text-sm text-gray-700 dark:text-gray-300">{{ __('dixlase-inquiry::admin/inquiry/settings/form-basic.show_phone') }}</span>
                        </label>
                    </div>
                    <div>
                        <x-form-toggle
                            name="phone_required"
                            :label="__('dixlase-inquiry::admin/inquiry/settings/form-basic.phone_required')"
                            :checked="old('phone_required', $settings->phone_required ?? false)"
                            xBind="showPhone"
                            xModel="phoneRequired"
                        />
                    </div>

                    {{-- 性別フィールド --}}
                    <div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="show_gender" x-model="showGender" {{ old('show_gender', $settings->show_gender ?? false) ? 'checked' : '' }} class="sr-only peer">
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none dark:bg-gray-600 rounded-full peer peer-checked:bg-indigo-600 transition-colors"></div>
                            <div class="absolute left-1 top-1 w-4 h-4 bg-white border border-gray-300 rounded-full transition-all peer-checked:translate-x-full peer-checked:border-white"></div>
                            <span class="ml-3 text-sm text-gray-700 dark:text-gray-300">{{ __('dixlase-inquiry::admin/inquiry/settings/form-basic.show_gender') }}</span>
                        </label>
                    </div>
                    <div>
                        <x-form-toggle
                            name="gender_required"
                            :label="__('dixlase-inquiry::admin/inquiry/settings/form-basic.gender_required')"
                            :checked="old('gender_required', $settings->gender_required ?? false)"
                            xBind="showGender"
                            xModel="genderRequired"
                        />
                    </div>
                </div>
            </fieldset>
        </section>

        {{-- 確認画面設定 --}}
        <section class="mb-8">
            <fieldset>
                <x-form-toggle
                    name="show_confirmation_page"
                    :label="__('dixlase-inquiry::admin/inquiry/settings/form-basic.show_confirmation')"
                    :checked="old('show_confirmation_page', $settings->show_confirmation_page ?? true)"
                />
                <p class="text-sm text-gray-600 dark:text-gray-400">
                    {{ __('dixlase-inquiry::admin/inquiry/settings/form-basic.show_confirmation_help') }}
                </p>
            </fieldset>
        </section>

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
