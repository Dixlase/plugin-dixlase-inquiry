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

    {{-- 警告メッセージ --}}
    @if(empty($settings->admin_email))
        <x-ui-message
            type="warning"
            icon="fas fa-exclamation-triangle"
            :message="__('dixlase-inquiry::admin/inquiry/settings/index.warning_admin_email', ['url' => route('dixlase-inquiry::admin.inquiry.settings.admin-notification')])"
        />
    @endif

    @if($settings->auto_reply_enabled && empty($settings->auto_reply_from_email))
        <x-ui-message
            type="warning"
            icon="fas fa-exclamation-triangle"
            :message="__('dixlase-inquiry::admin/inquiry/settings/index.warning_auto_reply_email', ['url' => route('dixlase-inquiry::admin.inquiry.settings.auto-reply')])"
        />
    @endif

    {{-- CAPTCHA未有効の案内 --}}
    @if(!$captchaEnabled)
        <x-ui-message
            type="notice"
            icon="fas fa-shield-alt"
            :message="__('dixlase-inquiry::admin/inquiry/settings/index.notice_captcha_disabled', ['url' => route('admin.settings.security.captcha')])"
        />
    @endif

    {{-- 設定カード一覧 --}}
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-2 gap-4 mb-8">

        {{-- フォーム設定 --}}
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
                    {{ __('dixlase-inquiry::admin/inquiry/settings/index.status.lang') }}:
                    {{ strtoupper($settings->lang ?? 'ja') }}
                    /
                    {{ __('dixlase-inquiry::admin/inquiry/settings/index.status.name_format') }}:
                    {{ $settings->name_order_western ? __('dixlase-inquiry::admin/inquiry/settings/index.status.western') : __('dixlase-inquiry::admin/inquiry/settings/index.status.japanese') }}
                    /
                    {{ __('dixlase-inquiry::admin/inquiry/settings/index.status.display_method') }}:
                    {{ $settings->use_single_page ? __('dixlase-inquiry::admin/inquiry/settings/index.status.single_page') : __('dixlase-inquiry::admin/inquiry/settings/index.status.separate_pages') }}
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

    {{-- 埋め込み方法 --}}
    <section class="mb-8">
        <h2 class="text-xl font-semibold mb-4">{{ __('dixlase-inquiry::admin/inquiry/settings/index.embedding_methods') }}</h2>

        <div class="mb-4 p-4 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg">
            <div class="flex items-start">
                <i class="fas fa-info-circle text-blue-600 dark:text-blue-400 mt-0.5 mr-2"></i>
                <div class="flex-1">
                    <p class="text-sm text-blue-800 dark:text-blue-200 font-medium mb-1">
                        {{ __('dixlase-inquiry::admin/inquiry/settings/index.usage_instruction_title') }}
                    </p>
                    <p class="text-xs text-blue-700 dark:text-blue-300">
                        {{ __('dixlase-inquiry::admin/inquiry/settings/index.usage_instruction_text') }}
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
                        {{ __('dixlase-inquiry::admin/inquiry/settings/index.blade_directive') }}
                    </p>
                </div>
                <div class="bg-gray-100 dark:bg-gray-700 p-3 rounded-md mb-2">
                    <code class="text-sm text-gray-800 dark:text-gray-200">{{ '@' }}inquiry</code>
                </div>
                <p class="text-xs text-gray-600 dark:text-gray-400">
                    {{ __('dixlase-inquiry::admin/inquiry/settings/index.blade_directive_help') }}
                </p>
            </div>

            {{-- ショートコード --}}
            <div class="p-4 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg">
                <p class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                    {{ __('dixlase-inquiry::admin/inquiry/settings/index.shortcode') }}
                </p>
                <div class="bg-gray-100 dark:bg-gray-700 p-3 rounded-md mb-2">
                    <code class="text-sm text-gray-800 dark:text-gray-200">[inquiry]</code>
                </div>
                <p class="text-xs text-gray-600 dark:text-gray-400">
                    {{ __('dixlase-inquiry::admin/inquiry/settings/index.shortcode_help') }}
                </p>
            </div>
        </div>
    </section>
    
</div>
@endsection
