{{--
This file is part of Dixlase Inquiry.

Copyright (C) 2026 exc-D inc.
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

@extends('themes::layouts.app')

@section('title', $settings->completion_title ?? __('dixlase-inquiry::front.complete.title'))

@section('content')
    <div class="dixlase-inquiry">
        <div class="container mx-auto pt-32 pb-16 px-4 sm:px-6 lg:px-8">
            <article class="bg-white dark:bg-gray-800 shadow-lg rounded-lg overflow-hidden max-w-2xl mx-auto">
                {{-- プレビュー時の情報バナー --}}
                @if($isPreview ?? false)
                <div class="bg-amber-50 dark:bg-amber-900/20 border-b border-amber-200 dark:border-amber-800 px-8 py-4">
                    <div class="flex items-start">
                        <i class="fas fa-eye text-amber-600 dark:text-amber-400 text-lg mt-0.5"></i>
                        <div class="ml-3 text-sm text-amber-800 dark:text-amber-300">
                            <p class="font-semibold">{{ __('dixlase-inquiry::admin/inquiry/settings/form-basic.preview_toolbar_title') }}</p>
                            @if(!($previewSavedToDb ?? false) && !($previewSentEmail ?? false))
                                <p>{{ __('dixlase-inquiry::admin/inquiry/settings/form-basic.preview_completed_no_save') }}</p>
                            @else
                                @if($previewSavedToDb ?? false)
                                    <p><i class="fas fa-database mr-1"></i> {{ __('dixlase-inquiry::admin/inquiry/settings/form-basic.preview_save_to_db') }}: ON</p>
                                @endif
                                @if($previewSentEmail ?? false)
                                    <p><i class="fas fa-envelope mr-1"></i> {{ __('dixlase-inquiry::admin/inquiry/settings/form-basic.preview_send_email') }}: ON</p>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>
                @endif

                {{-- 完了メッセージ --}}
                <div class="px-8 py-10">
                    <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg p-6 text-center">
                        <div class="text-green-600 dark:text-green-400 mb-3"><i class="fas fa-check-circle text-3xl"></i></div>
                        <h1 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">
                            {{ $settings->completion_title ?? __('dixlase-inquiry::front.complete.title') }}
                        </h1>
                        <p class="text-gray-600 dark:text-gray-400 text-sm">
                            {!! $settings->completion_message ?? __('dixlase-inquiry::front.complete.message') !!}
                        </p>
                    </div>

                    {{-- 送信内容の概要 --}}
                    @if(isset($inquiry))
                    <div class="mt-8">
                        <h3 class="text-base font-semibold text-gray-900 dark:text-white mb-4">{{ __('dixlase-inquiry::front.complete.inquiry_details') }}</h3>
                        <dl class="space-y-3">
                            @if(!empty($inquiry->id))
                            <div class="border-b border-gray-200 dark:border-gray-700 pb-2">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('dixlase-inquiry::front.complete.inquiry_number') }}</dt>
                                <dd class="mt-1 text-gray-900 dark:text-white">#{{ str_pad($inquiry->id, 6, '0', STR_PAD_LEFT) }}</dd>
                            </div>
                            @endif
                            <div class="border-b border-gray-200 dark:border-gray-700 pb-2">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('dixlase-inquiry::front.complete.submitted_at') }}</dt>
                                <dd class="mt-1 text-gray-900 dark:text-white">{{ $inquiry->created_at->format('Y年m月d日 H:i') }}</dd>
                            </div>
                            <div class="pb-2">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('dixlase-inquiry::front.form.email') }}</dt>
                                <dd class="mt-1 text-gray-900 dark:text-white">{{ $inquiry->email }}</dd>
                            </div>
                        </dl>

                        <div class="mt-6 p-4 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg">
                            <p class="text-sm text-blue-800 dark:text-blue-300">
                                <i class="fas fa-info-circle mr-2"></i>
                                {{ __('dixlase-inquiry::front.complete.auto_reply_notice') }}
                            </p>
                        </div>
                    </div>
                    @endif

                    {{-- トップページへ戻るボタン --}}
                    <div class="mt-8 text-center">
                        <a href="{{ route('welcome') }}"
                            class="inline-flex items-center px-4 py-2 bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 font-medium rounded-md transition-colors duration-200">
                            <i class="fas fa-home mr-2"></i>
                            {{ __('dixlase-inquiry::front.complete.back_to_home') }}
                        </a>
                    </div>
                </div>
            </article>
        </div>
    </div>
@endsection
