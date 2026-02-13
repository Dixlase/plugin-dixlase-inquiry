{{--
This file is part of Dixlase Inquiry.

Copyright (C) 2026 exc-D inc.
https://exc-d.com

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

@section('title', $settings->completion_title ?? __('dixlase-inquiry::front.complete.title'))

@section('content')
    <div class="dixlase-inquiry">
        <!-- メインコンテンツ -->
        <div class="container mx-auto py-8 px-4 sm:px-6 lg:px-8">
            <article class="bg-white dark:bg-gray-800 shadow-lg rounded-lg overflow-hidden">
                <!-- ページヘッダー -->
                <header class="px-6 py-8 border-b border-gray-200 dark:border-gray-700">
                    <h1 class="text-4xl font-bold text-gray-900 dark:text-white mb-4">
                        {{ $settings->completion_title ?? __('dixlase-inquiry::front.complete.title') }}
                    </h1>
                </header>
                
                <!-- 完了メッセージ -->
                <div class="px-6 py-8">
                    <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg p-6 mb-6">
        <div class="flex items-start">
            <div class="flex-shrink-0">
                <i class="fas fa-check-circle text-green-600 dark:text-green-400 text-3xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-green-800 dark:text-green-300">
                    {!! $settings->completion_message ?? __('dixlase-inquiry::front.complete.message') !!}
                </p>
            </div>
        </div>
    </div>

    <!-- 送信内容の概要（オプション） -->
    @if(isset($inquiry))
    <div class="mt-8">
        <h3 class="text-lg font-semibold mb-4">{{ __('dixlase-inquiry::front.complete.inquiry_details') }}</h3>
        
        <div class="space-y-3">
            <!-- 受付番号 -->
            @if(!empty($inquiry->id))
            <div class="flex">
                <dt class="w-1/3 font-semibold text-gray-700 dark:text-gray-300">
                    {{ __('dixlase-inquiry::front.complete.inquiry_number') }}
                </dt>
                <dd class="w-2/3 text-gray-900 dark:text-white">
                    #{{ str_pad($inquiry->id, 6, '0', STR_PAD_LEFT) }}
                </dd>
            </div>
            @endif

            <!-- 送信日時 -->
            <div class="flex">
                <dt class="w-1/3 font-semibold text-gray-700 dark:text-gray-300">
                    {{ __('dixlase-inquiry::front.complete.submitted_at') }}
                </dt>
                <dd class="w-2/3 text-gray-900 dark:text-white">
                    {{ $inquiry->created_at->format('Y年m月d日 H:i') }}
                </dd>
            </div>

            <!-- メールアドレス -->
            <div class="flex">
                <dt class="w-1/3 font-semibold text-gray-700 dark:text-gray-300">
                    {{ __('dixlase-inquiry::front.form.email') }}
                </dt>
                <dd class="w-2/3 text-gray-900 dark:text-white">
                    {{ $inquiry->email }}
                </dd>
            </div>
        </div>

        <div class="mt-6 p-4 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded">
            <p class="text-sm text-blue-800 dark:text-blue-300">
                <i class="fas fa-info-circle mr-2"></i>
                {{ __('dixlase-inquiry::front.complete.auto_reply_notice') }}
            </p>
        </div>
    </div>
    @endif

    <!-- トップページへ戻るボタン -->
    <div class="mt-8">
        <x-form-button
            type="link"
            :href="route('welcome')"
            variant="primary"
            :label="__('dixlase-inquiry::front.complete.back_to_home')"
            icon="fas fa-home"
        />
    </div>
                </div>
            </article>
        </div>
    </div>
@endsection
