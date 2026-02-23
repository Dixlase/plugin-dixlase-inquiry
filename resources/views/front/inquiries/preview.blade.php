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

@extends('themes::layouts.app')

@section('title', __('dixlase-inquiry::front.form.title') . ' - ' . __('dixlase-inquiry::admin/inquiry/settings/form-basic.preview_toolbar_title'))

@section('content')
    <div class="dixlase-inquiry" x-data="{ previewSaveToDb: false, previewSendEmail: false }">
        {{-- プレビューツールバー --}}
        <div class="bg-amber-50 dark:bg-amber-900/30 border-b border-amber-200 dark:border-amber-700">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-3">
                <div class="flex flex-wrap items-center gap-4">
                    <div class="flex items-center gap-2">
                        <i class="fas fa-eye text-amber-600 dark:text-amber-400"></i>
                        <span class="font-semibold text-amber-800 dark:text-amber-300">
                            {{ __('dixlase-inquiry::admin/inquiry/settings/form-basic.preview_toolbar_title') }}
                        </span>
                    </div>
                    <div class="flex items-center gap-6">
                        <label class="flex items-center gap-2 text-sm text-amber-800 dark:text-amber-300">
                            <input type="checkbox" x-model="previewSaveToDb"
                                   class="rounded border-amber-300 text-amber-600 shadow-sm focus:ring-amber-500 dark:border-amber-600 dark:bg-amber-800">
                            <span>{{ __('dixlase-inquiry::admin/inquiry/settings/form-basic.preview_save_to_db') }}</span>
                        </label>
                        <label class="flex items-center gap-2 text-sm text-amber-800 dark:text-amber-300">
                            <input type="checkbox" x-model="previewSendEmail"
                                   class="rounded border-amber-300 text-amber-600 shadow-sm focus:ring-amber-500 dark:border-amber-600 dark:bg-amber-800">
                            <span>{{ __('dixlase-inquiry::admin/inquiry/settings/form-basic.preview_send_email') }}</span>
                        </label>
                    </div>
                    <p class="text-xs text-amber-600 dark:text-amber-400">
                        {{ __('dixlase-inquiry::admin/inquiry/settings/form-basic.preview_toolbar_help') }}
                    </p>
                </div>
            </div>
        </div>

        {{-- メインコンテンツ --}}
        <div class="container mx-auto py-8 px-4 sm:px-6 lg:px-8">
            <article class="bg-white dark:bg-gray-800 shadow-lg rounded-lg overflow-hidden">
                {{-- ページヘッダー --}}
                <header class="px-6 py-8 border-b border-gray-200 dark:border-gray-700">
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white text-center">
                        {{ __('dixlase-inquiry::front.form.heading') }}
                    </h1>
                </header>

                {{-- フォームコンテンツ --}}
                <div class="px-6 py-8">
                    <form action="{{ $formAction }}" method="POST">
                        @csrf
                        @include('dixlase-inquiry::front.inquiries.partials.form-fields', [
                            'isPreview' => true,
                        ])
                    </form>
                </div>
            </article>
        </div>
    </div>
@endsection
