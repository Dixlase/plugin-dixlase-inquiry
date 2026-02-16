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
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4 mb-8">

        {{-- フォーム基本設定 --}}
        <a href="{{ route('dixlase-inquiry::admin.inquiry.settings.form-basic') }}" class="block p-4 bg-white dark:bg-gray-800 rounded-lg shadow hover:shadow-md transition-shadow border border-gray-200 dark:border-gray-700">
            <div class="flex items-center justify-between mb-3">
                <div class="flex items-center">
                    <i class="fas fa-sliders-h text-blue-500 text-xl mr-3"></i>
                    <h3 class="font-semibold text-gray-900 dark:text-white">{{ __('dixlase-inquiry::admin/inquiry/settings/index.nav.form_basic') }}</h3>
                </div>
                <i class="fas fa-chevron-right text-gray-400"></i>
            </div>
            <div class="text-sm text-gray-600 dark:text-gray-400">
                <p>{{ __('dixlase-inquiry::admin/inquiry/settings/index.cards.form_basic_desc') }}</p>
                <p class="text-xs mt-2">
                    {{ __('dixlase-inquiry::admin/inquiry/settings/index.status.name_format') }}:
                    {{ $settings->name_order_western ? __('dixlase-inquiry::admin/inquiry/settings/index.status.western') : __('dixlase-inquiry::admin/inquiry/settings/index.status.japanese') }}
                </p>
            </div>
        </a>

        {{-- フォーム表示設定 --}}
        <a href="{{ route('dixlase-inquiry::admin.inquiry.settings.form-display') }}" class="block p-4 bg-white dark:bg-gray-800 rounded-lg shadow hover:shadow-md transition-shadow border border-gray-200 dark:border-gray-700">
            <div class="flex items-center justify-between mb-3">
                <div class="flex items-center">
                    <i class="fas fa-desktop text-green-500 text-xl mr-3"></i>
                    <h3 class="font-semibold text-gray-900 dark:text-white">{{ __('dixlase-inquiry::admin/inquiry/settings/index.nav.form_display') }}</h3>
                </div>
                <i class="fas fa-chevron-right text-gray-400"></i>
            </div>
            <div class="text-sm text-gray-600 dark:text-gray-400">
                <p>{{ __('dixlase-inquiry::admin/inquiry/settings/index.cards.form_display_desc') }}</p>
                <p class="text-xs mt-2">
                    {{ __('dixlase-inquiry::admin/inquiry/settings/index.status.display_method') }}:
                    {{ $settings->use_single_page ? __('dixlase-inquiry::admin/inquiry/settings/index.status.single_page') : __('dixlase-inquiry::admin/inquiry/settings/index.status.separate_pages') }}
                    /
                    {{ __('dixlase-inquiry::admin/inquiry/settings/index.status.confirmation') }}:
                    {{ $settings->show_confirmation_page ? __('dixlase-inquiry::admin/inquiry/settings/index.status.enabled') : __('dixlase-inquiry::admin/inquiry/settings/index.status.disabled') }}
                </p>
            </div>
        </a>

        {{-- 完了ページ設定 --}}
        <a href="{{ route('dixlase-inquiry::admin.inquiry.settings.completion') }}" class="block p-4 bg-white dark:bg-gray-800 rounded-lg shadow hover:shadow-md transition-shadow border border-gray-200 dark:border-gray-700">
            <div class="flex items-center justify-between mb-3">
                <div class="flex items-center">
                    <i class="fas fa-check-circle text-purple-500 text-xl mr-3"></i>
                    <h3 class="font-semibold text-gray-900 dark:text-white">{{ __('dixlase-inquiry::admin/inquiry/settings/index.nav.completion') }}</h3>
                </div>
                <i class="fas fa-chevron-right text-gray-400"></i>
            </div>
            <div class="text-sm text-gray-600 dark:text-gray-400">
                <p>{{ __('dixlase-inquiry::admin/inquiry/settings/index.cards.completion_desc') }}</p>
            </div>
        </a>

        {{-- 管理者通知設定 --}}
        <a href="{{ route('dixlase-inquiry::admin.inquiry.settings.admin-notification') }}" class="block p-4 bg-white dark:bg-gray-800 rounded-lg shadow hover:shadow-md transition-shadow border border-gray-200 dark:border-gray-700">
            <div class="flex items-center justify-between mb-3">
                <div class="flex items-center">
                    <i class="fas fa-bell text-orange-500 text-xl mr-3"></i>
                    <h3 class="font-semibold text-gray-900 dark:text-white">{{ __('dixlase-inquiry::admin/inquiry/settings/index.nav.admin_notification') }}</h3>
                </div>
                <i class="fas fa-chevron-right text-gray-400"></i>
            </div>
            <div class="text-sm text-gray-600 dark:text-gray-400">
                <p>{{ __('dixlase-inquiry::admin/inquiry/settings/index.cards.admin_notification_desc') }}</p>
                <p class="text-xs mt-2">
                    {{ __('dixlase-inquiry::admin/inquiry/settings/index.status.admin_email') }}:
                    {{ $settings->admin_email ?: '-' }}
                </p>
            </div>
        </a>

        {{-- 自動返信設定 --}}
        <a href="{{ route('dixlase-inquiry::admin.inquiry.settings.auto-reply') }}" class="block p-4 bg-white dark:bg-gray-800 rounded-lg shadow hover:shadow-md transition-shadow border border-gray-200 dark:border-gray-700">
            <div class="flex items-center justify-between mb-3">
                <div class="flex items-center">
                    <i class="fas fa-reply-all text-teal-500 text-xl mr-3"></i>
                    <h3 class="font-semibold text-gray-900 dark:text-white">{{ __('dixlase-inquiry::admin/inquiry/settings/index.nav.auto_reply') }}</h3>
                </div>
                <i class="fas fa-chevron-right text-gray-400"></i>
            </div>
            <div class="text-sm text-gray-600 dark:text-gray-400">
                <p>{{ __('dixlase-inquiry::admin/inquiry/settings/index.cards.auto_reply_desc') }}</p>
                <p class="text-xs mt-2">
                    {{ __('dixlase-inquiry::admin/inquiry/settings/index.status.auto_reply') }}:
                    {{ $settings->auto_reply_enabled ? __('dixlase-inquiry::admin/inquiry/settings/index.status.enabled') : __('dixlase-inquiry::admin/inquiry/settings/index.status.disabled') }}
                </p>
            </div>
        </a>

    </div>
</div>
@endsection
