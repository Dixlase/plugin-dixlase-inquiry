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

@section('title', __('dixlase-inquiry::front.form.title'))

@section('content')
    <div class="dixlase-inquiry">
        <!-- メインコンテンツ -->
        <div class="container mx-auto py-8 px-4 sm:px-6 lg:px-8">
            <article class="bg-white dark:bg-gray-800 shadow-lg rounded-lg overflow-hidden">
                <!-- ページヘッダー -->
                <header class="px-6 py-8 border-b border-gray-200 dark:border-gray-700">
                    <h1 class="text-4xl font-bold text-gray-900 dark:text-white mb-4">
                        {{ __('dixlase-inquiry::front.form.heading') }}
                    </h1>
                </header>

                <!-- フォームコンテンツ -->
                <div class="px-6 py-8">
                    <form action="{{ $settings->show_confirmation_page ? route('inquiry.confirm') : route('inquiry.send') }}" method="POST">
                        @csrf

        <!-- 題名 -->
        @if($settings->show_subject ?? false)
        <fieldset>
            <legend>{{ __('dixlase-inquiry::front.form.subject') }}</legend>
            <x-form-text
                name="subject"
                :value="old('subject')"
                :required="$settings->subject_required ?? false"
                :placeholder="__('dixlase-inquiry::front.form.subject_placeholder')"
            />
        </fieldset>
        @endif

        <!-- 名前（日本式：姓・名 / 欧米式：名・姓） -->
        @if($settings->name_order_western ?? false)
        <!-- 欧米式（名・姓） -->
        <fieldset>
            <legend>{{ __('dixlase-inquiry::front.form.first_name') }}</legend>
            <x-form-text
                name="first_name"
                :value="old('first_name')"
                :required="true"
                :placeholder="__('dixlase-inquiry::front.form.first_name_placeholder')"
            />
        </fieldset>

        <fieldset>
            <legend>{{ __('dixlase-inquiry::front.form.last_name') }}</legend>
            <x-form-text
                name="last_name"
                :value="old('last_name')"
                :required="true"
                :placeholder="__('dixlase-inquiry::front.form.last_name_placeholder')"
            />
        </fieldset>
        @else
        <!-- 日本式（姓・名） -->
        <fieldset>
            <legend>{{ __('dixlase-inquiry::front.form.last_name') }}</legend>
            <x-form-text
                name="last_name"
                :value="old('last_name')"
                :required="true"
                :placeholder="__('dixlase-inquiry::front.form.last_name_placeholder')"
            />
        </fieldset>

        <fieldset>
            <legend>{{ __('dixlase-inquiry::front.form.first_name') }}</legend>
            <x-form-text
                name="first_name"
                :value="old('first_name')"
                :required="true"
                :placeholder="__('dixlase-inquiry::front.form.first_name_placeholder')"
            />
        </fieldset>
        @endif

        <!-- メールアドレス -->
        <fieldset>
            <legend>{{ __('dixlase-inquiry::front.form.email') }}</legend>
            <x-form-text
                type="email"
                name="email"
                :value="old('email')"
                :required="true"
                :placeholder="__('dixlase-inquiry::front.form.email_placeholder')"
            />
        </fieldset>

        <!-- 郵便番号 -->
        @if($settings->show_postal_code ?? false)
        <fieldset>
            <legend>{{ __('dixlase-inquiry::front.form.postal_code') }}</legend>
            <x-form-text
                name="postal_code"
                :value="old('postal_code')"
                :required="$settings->postal_code_required ?? false"
                :placeholder="__('dixlase-inquiry::front.form.postal_code_placeholder')"
            />
        </fieldset>
        @endif

        <!-- 住所 -->
        @if($settings->show_address ?? false)
            @if($settings->name_order_western ?? false)
            <!-- 欧米式住所（番地→市→州→国） -->
            <fieldset>
                <legend>{{ __('dixlase-inquiry::front.form.street_address') }}</legend>
                <x-form-text
                    name="street_address"
                    :value="old('street_address')"
                    :required="$settings->address_required ?? false"
                    :placeholder="__('dixlase-inquiry::front.form.street_address_placeholder')"
                />
            </fieldset>

            <fieldset>
                <legend>{{ __('dixlase-inquiry::front.form.city') }}</legend>
                <x-form-text
                    name="city"
                    :value="old('city')"
                    :required="$settings->address_required ?? false"
                    :placeholder="__('dixlase-inquiry::front.form.city_placeholder')"
                />
            </fieldset>

            <fieldset>
                <legend>{{ __('dixlase-inquiry::front.form.state') }}</legend>
                <x-form-text
                    name="state"
                    :value="old('state')"
                    :required="false"
                    :placeholder="__('dixlase-inquiry::front.form.state_placeholder')"
                />
            </fieldset>

            <fieldset>
                <legend>{{ __('dixlase-inquiry::front.form.country') }}</legend>
                <x-form-text
                    name="country"
                    :value="old('country')"
                    :required="false"
                    :placeholder="__('dixlase-inquiry::front.form.country_placeholder')"
                />
            </fieldset>
            @else
            <!-- 日本式住所（都道府県→市区町村→番地） -->
            <fieldset>
                <legend>{{ __('dixlase-inquiry::front.form.address') }}</legend>
                <x-form-text
                    name="address"
                    :value="old('address')"
                    :required="$settings->address_required ?? false"
                    :placeholder="__('dixlase-inquiry::front.form.address_placeholder')"
                />
            </fieldset>
            @endif
        @endif

        <!-- 電話番号 -->
        @if($settings->show_phone ?? true)
        <fieldset>
            <legend>{{ __('dixlase-inquiry::front.form.phone') }}</legend>
            <x-form-text
                type="tel"
                name="phone"
                :value="old('phone')"
                :required="$settings->phone_required ?? false"
                :placeholder="__('dixlase-inquiry::front.form.phone_placeholder')"
            />
        </fieldset>
        @endif

        <!-- 性別 -->
        @if($settings->show_gender ?? false)
        <fieldset>
            <legend>{{ __('dixlase-inquiry::front.form.gender') }}</legend>
            <x-form-select
                name="gender"
                :value="old('gender')"
                :required="$settings->gender_required ?? false"
                :options="[
                    '' => __('dixlase-inquiry::front.form.gender_select'),
                    'male' => __('dixlase-inquiry::front.form.gender_male'),
                    'female' => __('dixlase-inquiry::front.form.gender_female'),
                    'non_binary' => __('dixlase-inquiry::front.form.gender_non_binary'),
                    'other' => __('dixlase-inquiry::front.form.gender_other'),
                    'prefer_not_to_say' => __('dixlase-inquiry::front.form.gender_prefer_not_to_say'),
                ]"
            />
        </fieldset>
        @endif

        <!-- 問い合わせ内容 -->
        <fieldset>
            <legend>{{ __('dixlase-inquiry::front.form.message') }}</legend>
            <x-form-textarea
                name="message"
                :value="old('message')"
                :required="true"
                :rows="5"
                :placeholder="__('dixlase-inquiry::front.form.message_placeholder')"
            />
        </fieldset>

        <!-- 送信ボタン -->
        <div class="flex gap-2 mt-6">
            <x-form-button
                type="submit"
                variant="primary"
                :label="__('dixlase-inquiry::front.form.confirm')"
                icon="fas fa-paper-plane"
            />
        </div>
                    </form>
                </div>
            </article>
        </div>
    </div>
@endsection
