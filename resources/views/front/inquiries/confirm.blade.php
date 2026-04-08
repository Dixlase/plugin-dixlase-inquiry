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
        <div class="container mx-auto pt-32 pb-16 px-4 sm:px-6 lg:px-8">
            <article class="bg-white dark:bg-gray-800 shadow-lg rounded-lg overflow-hidden max-w-2xl mx-auto">
                {{-- 確認内容 --}}
                <div class="px-8 py-10">
                    {{-- 確認メッセージ --}}
                    <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-6 text-center mb-8">
                        <div class="text-blue-600 dark:text-blue-400 mb-3"><i class="fas fa-clipboard-check text-3xl"></i></div>
                        <h1 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">{{ __('dixlase-inquiry::front.confirmation.title') }}</h1>
                        <p class="text-gray-600 dark:text-gray-400 text-sm">{!! __('dixlase-inquiry::front.confirmation.message') !!}</p>
                    </div>
                    <form action="{{ ($isPreview ?? false) ? route('dixlase-inquiry::admin.inquiry.settings.form-basic.preview.send') : route('inquiry.send') }}" method="POST">
                        @csrf

                        <dl class="space-y-3">
                            {{-- 名前 --}}
                            <div class="border-b border-gray-200 dark:border-gray-700 pb-2">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('dixlase-inquiry::front.form.name') }}</dt>
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

                            {{-- カタカナ --}}
                            @if(!($settings->name_order_western ?? false) && ($settings->show_kana ?? false))
                                @if(!empty($data['last_name_kana']) || !empty($data['first_name_kana']))
                                <div class="border-b border-gray-200 dark:border-gray-700 pb-2">
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('dixlase-inquiry::front.form.kana') }}</dt>
                                    <dd class="mt-1 text-gray-900 dark:text-white">{{ ($data['last_name_kana'] ?? '') . ' ' . ($data['first_name_kana'] ?? '') }}</dd>
                                    <input type="hidden" name="last_name_kana" value="{{ $data['last_name_kana'] ?? '' }}">
                                    <input type="hidden" name="first_name_kana" value="{{ $data['first_name_kana'] ?? '' }}">
                                </div>
                                @endif
                            @endif

                            {{-- メールアドレス --}}
                            <div class="border-b border-gray-200 dark:border-gray-700 pb-2">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('dixlase-inquiry::front.form.email') }}</dt>
                                <dd class="mt-1 text-gray-900 dark:text-white">{{ $data['email'] }}</dd>
                                <input type="hidden" name="email" value="{{ $data['email'] }}">
                            </div>

                            {{-- 郵便番号 --}}
                            @if($settings->name_order_western ?? false)
                                @if(!empty($data['postal_code']))
                                <div class="border-b border-gray-200 dark:border-gray-700 pb-2">
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('dixlase-inquiry::front.form.postal_code') }}</dt>
                                    <dd class="mt-1 text-gray-900 dark:text-white">{{ $data['postal_code'] }}</dd>
                                    <input type="hidden" name="postal_code" value="{{ $data['postal_code'] }}">
                                </div>
                                @endif
                            @else
                                @if(!empty($data['postal_code_1']) && !empty($data['postal_code_2']))
                                <div class="border-b border-gray-200 dark:border-gray-700 pb-2">
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('dixlase-inquiry::front.form.postal_code') }}</dt>
                                    <dd class="mt-1 text-gray-900 dark:text-white">{{ $data['postal_code_1'] }}-{{ $data['postal_code_2'] }}</dd>
                                    <input type="hidden" name="postal_code_1" value="{{ $data['postal_code_1'] }}">
                                    <input type="hidden" name="postal_code_2" value="{{ $data['postal_code_2'] }}">
                                </div>
                                @endif
                            @endif

                            {{-- 住所 --}}
                            @if($settings->name_order_western ?? false)
                                @if(!empty($data['street_address']) || !empty($data['city']))
                                <div class="border-b border-gray-200 dark:border-gray-700 pb-2">
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('dixlase-inquiry::front.form.address') }}</dt>
                                    <dd class="mt-1 text-gray-900 dark:text-white">{{ collect([$data['street_address'] ?? null, $data['building'] ?? null, $data['city'] ?? null, $data['state'] ?? null, $data['country'] ?? null])->filter()->implode(', ') }}</dd>
                                    <input type="hidden" name="street_address" value="{{ $data['street_address'] ?? '' }}">
                                    <input type="hidden" name="building" value="{{ $data['building'] ?? '' }}">
                                    <input type="hidden" name="city" value="{{ $data['city'] ?? '' }}">
                                    <input type="hidden" name="state" value="{{ $data['state'] ?? '' }}">
                                    <input type="hidden" name="country" value="{{ $data['country'] ?? '' }}">
                                </div>
                                @endif
                            @else
                                @if(!empty($data['prefecture']) || !empty($data['city']) || !empty($data['address_line']))
                                <div class="border-b border-gray-200 dark:border-gray-700 pb-2">
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('dixlase-inquiry::front.form.address') }}</dt>
                                    <dd class="mt-1 text-gray-900 dark:text-white">{{ ($data['prefecture'] ?? '') . ($data['city'] ?? '') . ($data['address_line'] ?? '') }}{{ !empty($data['building']) ? ' ' . $data['building'] : '' }}</dd>
                                    <input type="hidden" name="prefecture" value="{{ $data['prefecture'] ?? '' }}">
                                    <input type="hidden" name="city" value="{{ $data['city'] ?? '' }}">
                                    <input type="hidden" name="address_line" value="{{ $data['address_line'] ?? '' }}">
                                    <input type="hidden" name="building" value="{{ $data['building'] ?? '' }}">
                                </div>
                                @endif
                            @endif

                            {{-- 電話番号 --}}
                            @if($settings->name_order_western ?? false)
                                @if(!empty($data['phone']))
                                <div class="border-b border-gray-200 dark:border-gray-700 pb-2">
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('dixlase-inquiry::front.form.phone') }}</dt>
                                    <dd class="mt-1 text-gray-900 dark:text-white">{{ $data['phone'] }}</dd>
                                    <input type="hidden" name="phone" value="{{ $data['phone'] }}">
                                </div>
                                @endif
                            @else
                                @if(!empty($data['phone_1']) && !empty($data['phone_2']) && !empty($data['phone_3']))
                                <div class="border-b border-gray-200 dark:border-gray-700 pb-2">
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('dixlase-inquiry::front.form.phone') }}</dt>
                                    <dd class="mt-1 text-gray-900 dark:text-white">{{ $data['phone_1'] }}-{{ $data['phone_2'] }}-{{ $data['phone_3'] }}</dd>
                                    <input type="hidden" name="phone_1" value="{{ $data['phone_1'] }}">
                                    <input type="hidden" name="phone_2" value="{{ $data['phone_2'] }}">
                                    <input type="hidden" name="phone_3" value="{{ $data['phone_3'] }}">
                                </div>
                                @endif
                            @endif

                            {{-- 性別 --}}
                            @if(!empty($data['gender']))
                            <div class="border-b border-gray-200 dark:border-gray-700 pb-2">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('dixlase-inquiry::front.form.gender') }}</dt>
                                <dd class="mt-1 text-gray-900 dark:text-white">{{ collect($genderOptions)->firstWhere('value', $data['gender'])['label'] ?? $data['gender'] }}</dd>
                                <input type="hidden" name="gender" value="{{ $data['gender'] }}">
                            </div>
                            @endif

                            {{-- 題名 --}}
                            @if(!empty($data['subject']))
                            <div class="border-b border-gray-200 dark:border-gray-700 pb-2">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('dixlase-inquiry::front.form.subject') }}</dt>
                                <dd class="mt-1 text-gray-900 dark:text-white">{{ $data['subject'] }}</dd>
                                <input type="hidden" name="subject" value="{{ $data['subject'] }}">
                            </div>
                            @endif

                            {{-- 問い合わせ内容 --}}
                            <div class="pb-2">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('dixlase-inquiry::front.form.message') }}</dt>
                                <dd class="mt-1 text-gray-900 dark:text-white whitespace-pre-wrap">{{ $data['message'] }}</dd>
                                <input type="hidden" name="message" value="{{ $data['message'] }}">
                            </div>
                        </dl>

                        {{-- プライバシー同意（hiddenで再送信） --}}
                        @if(!empty($data['privacy_agreed']))
                            <input type="hidden" name="privacy_agreed" value="{{ $data['privacy_agreed'] }}">
                        @endif

                        {{-- プレビュー時: トグル値を引き継ぎ --}}
                        @if($isPreview ?? false)
                            <input type="hidden" name="_preview_save_to_db" value="{{ $data['_preview_save_to_db'] ?? '0' }}">
                            <input type="hidden" name="_preview_send_email" value="{{ $data['_preview_send_email'] ?? '0' }}">
                        @endif

                        {{-- ボタン --}}
                        <div class="flex gap-3 pt-6 justify-center">
                            <button type="button" onclick="history.back()"
                                class="inline-flex items-center px-4 py-2 bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 font-medium rounded-md transition-colors duration-200">
                                <i class="fas fa-arrow-left mr-2"></i>
                                {{ __('dixlase-inquiry::front.buttons.back') }}
                            </button>
                            <button type="submit"
                                class="inline-flex items-center px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-md transition-colors duration-200">
                                <i class="fas fa-paper-plane mr-2"></i>
                                {{ __('dixlase-inquiry::front.buttons.send') }}
                            </button>
                        </div>
                    </form>
                </div>
            </article>
        </div>
    </div>
@endsection
