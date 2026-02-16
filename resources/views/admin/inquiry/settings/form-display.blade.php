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
    <form id="form-display-settings-form" action="{{ route('dixlase-inquiry::admin.inquiry.settings.form-display.update') }}" method="POST" x-data="{
        useSinglePage: {{ old('use_single_page', $settings->use_single_page ?? true) ? 'true' : 'false' }},
        inquiryUrlSlug: '{{ old('inquiry_url_slug', $settings->inquiry_url_slug ?? 'inquiry') }}'
    }">
        @csrf

        {{-- フォーム表示方式 --}}
        <section class="mb-8">
            <fieldset>
                <legend>{{ __('dixlase-inquiry::admin/inquiry/settings/form-display.form_type') }}</legend>

                <div class="grid grid-cols-1 gap-6">
                    <x-form-radio-group
                        name="use_single_page"
                        :options="[
                            '1' => __('dixlase-inquiry::admin/inquiry/settings/form-display.single_page'),
                            '0' => __('dixlase-inquiry::admin/inquiry/settings/form-display.separate_pages')
                        ]"
                        :value="old('use_single_page', $settings->use_single_page ?? true) ? '1' : '0'"
                        xModel="useSinglePage"
                    />
                    <p class="text-sm text-gray-600 dark:text-gray-400">
                        {{ __('dixlase-inquiry::admin/inquiry/settings/form-display.single_page_help') }}
                    </p>

                    {{-- 別ページ選択時: URL編集フィールド --}}
                    <div x-show="!useSinglePage" x-cloak>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            {{ __('dixlase-inquiry::admin/inquiry/settings/form-display.inquiry_url') }}
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
                            {{ __('dixlase-inquiry::admin/inquiry/settings/form-display.inquiry_url_slug_help') }}
                        </p>

                        {{-- プレビューボタン --}}
                        <div class="mt-4">
                            <a :href="'{{ url('/') }}/' + inquiryUrlSlug"
                               target="_blank"
                               class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-md shadow-sm transition-colors duration-150">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                                {{ __('common.preview') }}
                            </a>
                            <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">
                                {{ __('dixlase-inquiry::admin/inquiry/settings/form-display.preview_page_help') }}
                            </p>
                        </div>
                    </div>

                    {{-- シングルページ選択時: hidden field で inquiry_url_slug を送信 --}}
                    <div x-show="useSinglePage" x-cloak>
                        <input type="hidden" name="inquiry_url_slug" :value="inquiryUrlSlug">
                    </div>
                </div>
            </fieldset>
        </section>

    </form>
</div>
@endsection

@section('save')
    <x-admin.save-button
        id_confirmation="confirmFormDisplaySettingsModal"
        :label="__('common.save')"
        :title="__('dixlase-inquiry::admin/inquiry/settings/form-display.confirm_title')"
        :message="__('dixlase-inquiry::admin/inquiry/settings/form-display.confirm_message')"
        :confirm_label="__('common.save')"
        :cancel_label="__('common.cancel')"
        form="form-display-settings-form"
    />
@endsection
