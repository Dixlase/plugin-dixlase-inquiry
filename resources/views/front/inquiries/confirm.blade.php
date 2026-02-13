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

@section('title', __('dixlase-inquiry::front.confirmation.title'))

@section('content')
    <div class="dixlase-inquiry">
        <!-- メインコンテンツ -->
        <div class="container mx-auto py-8 px-4 sm:px-6 lg:px-8">
            <article class="bg-white dark:bg-gray-800 shadow-lg rounded-lg overflow-hidden">
                <!-- ページヘッダー -->
                <header class="px-6 py-8 border-b border-gray-200 dark:border-gray-700">
                    <h1 class="text-4xl font-bold text-gray-900 dark:text-white mb-4">
                        {{ __('dixlase-inquiry::front.confirmation.title') }}
                    </h1>
                    <p class="text-gray-600 dark:text-gray-400">
                        {{ __('dixlase-inquiry::front.confirmation.message') }}
                    </p>
                </header>
                
                <!-- 確認内容 -->
                <div class="px-6 py-8">
                    <form action="{{ route('inquiry.send') }}" method="POST">
                        @csrf

                        <!-- 入力内容の確認表示 -->
                        <div class="space-y-4">
            <!-- 題名 -->
            @if(!empty($data['subject']))
            <div class="border-b pb-2">
                <dt class="font-semibold text-gray-700 dark:text-gray-300">
                    {{ __('dixlase-inquiry::front.form.subject') }}
                </dt>
                <dd class="mt-1 text-gray-900 dark:text-white">
                    {{ $data['subject'] }}
                </dd>
                <input type="hidden" name="subject" value="{{ $data['subject'] }}">
            </div>
            @endif

            <!-- 名前（日本式：姓・名 / 欧米式：名・姓） -->
            <div class="border-b pb-2">
                <dt class="font-semibold text-gray-700 dark:text-gray-300">
                    {{ __('dixlase-inquiry::front.form.name') }}
                </dt>
                <dd class="mt-1 text-gray-900 dark:text-white">
                    @if($settings->name_order_western ?? false)
                        {{ $data['first_name'] }} {{ $data['last_name'] }}
                    @else
                        {{ $data['last_name'] }} {{ $data['first_name'] }}
                    @endif
                </dd>
                <input type="hidden" name="first_name" value="{{ $data['first_name'] }}">
                <input type="hidden" name="last_name" value="{{ $data['last_name'] }}">
            </div>

            <!-- メールアドレス -->
            <div class="border-b pb-2">
                <dt class="font-semibold text-gray-700 dark:text-gray-300">
                    {{ __('dixlase-inquiry::front.form.email') }}
                </dt>
                <dd class="mt-1 text-gray-900 dark:text-white">
                    {{ $data['email'] }}
                </dd>
                <input type="hidden" name="email" value="{{ $data['email'] }}">
            </div>

            <!-- 郵便番号 -->
            @if(!empty($data['postal_code']))
            <div class="border-b pb-2">
                <dt class="font-semibold text-gray-700 dark:text-gray-300">
                    {{ __('dixlase-inquiry::front.form.postal_code') }}
                </dt>
                <dd class="mt-1 text-gray-900 dark:text-white">
                    {{ $data['postal_code'] }}
                </dd>
                <input type="hidden" name="postal_code" value="{{ $data['postal_code'] }}">
            </div>
            @endif

            <!-- 住所 -->
            @if(!empty($data['address']))
            <div class="border-b pb-2">
                <dt class="font-semibold text-gray-700 dark:text-gray-300">
                    {{ __('dixlase-inquiry::front.form.address') }}
                </dt>
                <dd class="mt-1 text-gray-900 dark:text-white">
                    {{ $data['address'] }}
                </dd>
                <input type="hidden" name="address" value="{{ $data['address'] }}">
            </div>
            @endif

            <!-- 電話番号 -->
            @if(!empty($data['phone']))
            <div class="border-b pb-2">
                <dt class="font-semibold text-gray-700 dark:text-gray-300">
                    {{ __('dixlase-inquiry::front.form.phone') }}
                </dt>
                <dd class="mt-1 text-gray-900 dark:text-white">
                    {{ $data['phone'] }}
                </dd>
                <input type="hidden" name="phone" value="{{ $data['phone'] }}">
            </div>
            @endif

            <!-- 問い合わせ内容 -->
            <div class="border-b pb-2">
                <dt class="font-semibold text-gray-700 dark:text-gray-300">
                    {{ __('dixlase-inquiry::front.form.message') }}
                </dt>
                <dd class="mt-1 text-gray-900 dark:text-white whitespace-pre-wrap">
                    {{ $data['message'] }}
                </dd>
                <input type="hidden" name="message" value="{{ $data['message'] }}">
            </div>
        </div>

        <!-- ボタン -->
        <div class="flex gap-2 mt-6">
            <x-form-button
                type="submit"
                variant="primary"
                :label="__('dixlase-inquiry::front.buttons.send')"
                icon="fas fa-paper-plane"
            />

            <x-form-button
                type="button"
                variant="secondary"
                :label="__('dixlase-inquiry::front.buttons.back')"
                icon="fas fa-arrow-left"
                onclick="history.back()"
            />
        </div>
                        </div>
                    </form>
                </div>
            </article>
        </div>
    </div>
@endsection
