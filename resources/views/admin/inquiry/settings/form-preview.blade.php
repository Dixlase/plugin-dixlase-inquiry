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
<div class="mx-auto" x-data="{
    nameOrderWestern: '{{ $settings->name_order_western ? '1' : '0' }}',
    showSubject: {{ $settings->show_subject ? 'true' : 'false' }},
    subjectRequired: {{ $settings->subject_required ? 'true' : 'false' }},
    showPostalCode: {{ $settings->show_postal_code ? 'true' : 'false' }},
    postalCodeRequired: {{ $settings->postal_code_required ? 'true' : 'false' }},
    get showAddress() { return this.showPostalCode; },
    get addressRequired() { return this.postalCodeRequired; },
    showPhone: {{ $settings->show_phone ? 'true' : 'false' }},
    phoneRequired: {{ $settings->phone_required ? 'true' : 'false' }},
    showGender: {{ $settings->show_gender ? 'true' : 'false' }},
    genderRequired: {{ $settings->gender_required ? 'true' : 'false' }},
}">

    {{-- 埋め込み方法 --}}
    <section class="mb-8">
        <h2 class="text-xl font-semibold mb-4">{{ __('dixlase-inquiry::admin/inquiry/settings/form-preview.embedding_methods') }}</h2>

        <div class="mb-4 p-4 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg">
            <div class="flex items-start">
                <i class="fas fa-info-circle text-blue-600 dark:text-blue-400 mt-0.5 mr-2"></i>
                <div class="flex-1">
                    <p class="text-sm text-blue-800 dark:text-blue-200 font-medium mb-1">
                        {{ __('dixlase-inquiry::admin/inquiry/settings/form-preview.usage_instruction_title') }}
                    </p>
                    <p class="text-xs text-blue-700 dark:text-blue-300">
                        {{ __('dixlase-inquiry::admin/inquiry/settings/form-preview.usage_instruction_text') }}
                    </p>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            {{-- Bladeディレクティブ（推奨） --}}
            <div class="p-4 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg">
                <div class="flex items-center mb-2">
                    <i class="fas fa-star text-yellow-500 mr-2"></i>
                    <p class="text-sm font-semibold text-gray-700 dark:text-gray-300">
                        {{ __('dixlase-inquiry::admin/inquiry/settings/form-preview.blade_directive') }}
                    </p>
                </div>
                <div class="bg-gray-100 dark:bg-gray-700 p-3 rounded-md mb-2">
                    <code class="text-sm text-gray-800 dark:text-gray-200">{{ '@' }}inquiry</code>
                </div>
                <p class="text-xs text-gray-600 dark:text-gray-400">
                    {{ __('dixlase-inquiry::admin/inquiry/settings/form-preview.blade_directive_help') }}
                </p>
            </div>

            {{-- ショートコード --}}
            <div class="p-4 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg">
                <p class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                    {{ __('dixlase-inquiry::admin/inquiry/settings/form-preview.shortcode') }}
                </p>
                <div class="bg-gray-100 dark:bg-gray-700 p-3 rounded-md mb-2">
                    <code class="text-sm text-gray-800 dark:text-gray-200">[inquiry]</code>
                </div>
                <p class="text-xs text-gray-600 dark:text-gray-400">
                    {{ __('dixlase-inquiry::admin/inquiry/settings/form-preview.shortcode_help') }}
                </p>
            </div>
        </div>
    </section>

    {{-- フォームプレビュー --}}
    <section>
        <h2 class="text-xl font-semibold mb-4">{{ __('dixlase-inquiry::admin/inquiry/settings/form-preview.form_preview') }}</h2>

        <div class="p-4 bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-lg">
            @include('dixlase-inquiry::admin.inquiry.partials.form-preview')
        </div>
    </section>

</div>
@endsection
