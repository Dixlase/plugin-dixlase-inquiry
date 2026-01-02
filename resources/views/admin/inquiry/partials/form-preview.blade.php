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

<div class="inquiry-form-preview">
    {{-- 名前フィールド（常に表示・必須） --}}
    <fieldset>
        <legend>
            {{ __('dixlase-inquiry::front.form.name') }}
            <x-form.required-badge />
        </legend>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            {{-- 日本式: 姓が先 --}}
            <div x-show="nameOrderWestern == '0'">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    {{ __('dixlase-inquiry::front.form.last_name') }}
                    <span class="text-xs text-gray-500 dark:text-gray-400">({{ __('dixlase-inquiry::front.form.last_name_label_ja') }})</span>
                </label>
                <input type="text" disabled placeholder="{{ __('dixlase-inquiry::front.form.last_name_placeholder') }}" class="input-common my-2 w-full opacity-60">
            </div>
            <div x-show="nameOrderWestern == '0'">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    {{ __('dixlase-inquiry::front.form.first_name') }}
                    <span class="text-xs text-gray-500 dark:text-gray-400">({{ __('dixlase-inquiry::front.form.first_name_label_ja') }})</span>
                </label>
                <input type="text" disabled placeholder="{{ __('dixlase-inquiry::front.form.first_name_placeholder') }}" class="input-common my-2 w-full opacity-60">
            </div>
            {{-- 欧米式: 名が先 --}}
            <div x-show="nameOrderWestern == '1'">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    {{ __('dixlase-inquiry::front.form.first_name') }}
                    <span class="text-xs text-gray-500 dark:text-gray-400">({{ __('dixlase-inquiry::front.form.first_name_label_en') }})</span>
                </label>
                <input type="text" disabled placeholder="{{ __('dixlase-inquiry::front.form.first_name_placeholder') }}" class="input-common my-2 w-full opacity-60">
            </div>
            <div x-show="nameOrderWestern == '1'">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    {{ __('dixlase-inquiry::front.form.last_name') }}
                    <span class="text-xs text-gray-500 dark:text-gray-400">({{ __('dixlase-inquiry::front.form.last_name_label_en') }})</span>
                </label>
                <input type="text" disabled placeholder="{{ __('dixlase-inquiry::front.form.last_name_placeholder') }}" class="input-common my-2 w-full opacity-60">
            </div>
        </div>
    </fieldset>

    {{-- メールアドレスフィールド（常に表示・必須） --}}
    <fieldset>
        <legend>
            {{ __('dixlase-inquiry::front.form.email') }}
            <x-form.required-badge />
        </legend>
        <input type="email" disabled placeholder="{{ __('dixlase-inquiry::front.form.email_placeholder') }}" class="input-common my-2 w-full opacity-60">
    </fieldset>

    {{-- 題名フィールド --}}
    <div x-show="showSubject">
        <fieldset>
            <legend>
                {{ __('dixlase-inquiry::front.form.subject') }}
                <span x-show="subjectRequired">
                    <x-form.required-badge />
                </span>
            </legend>
            <input type="text" disabled placeholder="{{ __('dixlase-inquiry::front.form.subject_placeholder') }}" class="input-common my-2 w-full opacity-60">
        </fieldset>
    </div>

    {{-- 郵便番号フィールド --}}
    <div x-show="showPostalCode">
        <fieldset>
            <legend>
                {{ __('dixlase-inquiry::front.form.postal_code') }}
                <span x-show="postalCodeRequired">
                    <x-form.required-badge />
                </span>
            </legend>
            <div x-show="nameOrderWestern == '0'" class="flex items-center gap-2">
                <input type="text" disabled maxlength="3" placeholder="123" class="input-common my-2 w-20 opacity-60">
                <span class="text-gray-500 dark:text-gray-400">-</span>
                <input type="text" disabled maxlength="4" placeholder="4567" class="input-common my-2 w-24 opacity-60">
            </div>
            <div x-show="nameOrderWestern == '1'">
                <input type="text" disabled placeholder="{{ __('dixlase-inquiry::front.form.postal_code_placeholder') }}" class="input-common my-2 w-full opacity-60">
            </div>
        </fieldset>
    </div>

    {{-- 住所フィールド --}}
    <div x-show="showAddress">
        <fieldset>
            <legend>
                {{ __('dixlase-inquiry::front.form.address') }}
                <span x-show="addressRequired">
                    <x-form.required-badge />
                </span>
            </legend>
            <div x-show="nameOrderWestern == '0'" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        {{ __('dixlase-inquiry::front.form.prefecture') }}
                    </label>
                    <select disabled class="input-common my-2 w-full opacity-60">
                        <option>{{ __('common.please_select') }}</option>
                        @foreach(config('regions.prefectures') as $code => $prefecture)
                            <option value="{{ $code }}">{{ __('regions.prefectures.' . $code) }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        {{ __('dixlase-inquiry::front.form.city') }}
                    </label>
                    <input type="text" disabled placeholder="{{ __('dixlase-inquiry::front.form.city_placeholder') }}" class="input-common my-2 w-full opacity-60">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        {{ __('dixlase-inquiry::front.form.address_line') }}
                    </label>
                    <input type="text" disabled placeholder="{{ __('dixlase-inquiry::front.form.address_line_placeholder') }}" class="input-common my-2 w-full opacity-60">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        {{ __('dixlase-inquiry::front.form.building') }}
                    </label>
                    <input type="text" disabled placeholder="{{ __('dixlase-inquiry::front.form.building_placeholder') }}" class="input-common my-2 w-full opacity-60">
                </div>
            </div>
            <div x-show="nameOrderWestern == '1'" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        {{ __('dixlase-inquiry::front.form.country') }}
                    </label>
                    <select disabled class="input-common my-2 w-full opacity-60">
                        <option>{{ __('common.please_select') }}</option>
                        @foreach(config('regions.countries') as $code => $country)
                            <option value="{{ $code }}">{{ __('regions.countries.' . $code) }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        {{ __('dixlase-inquiry::front.form.state_province') }}
                    </label>
                    <input type="text" disabled placeholder="{{ __('dixlase-inquiry::front.form.state_province_placeholder') }}" class="input-common my-2 w-full opacity-60">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        {{ __('dixlase-inquiry::front.form.city') }}
                    </label>
                    <input type="text" disabled placeholder="{{ __('dixlase-inquiry::front.form.city_placeholder_en') }}" class="input-common my-2 w-full opacity-60">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        {{ __('dixlase-inquiry::front.form.address_line') }}
                    </label>
                    <input type="text" disabled placeholder="{{ __('dixlase-inquiry::front.form.address_line_placeholder_en') }}" class="input-common my-2 w-full opacity-60">
                </div>
            </div>
        </fieldset>
    </div>

    {{-- 電話番号フィールド --}}
    <div x-show="showPhone">
        <fieldset>
            <legend>
                {{ __('dixlase-inquiry::front.form.phone') }}
                <span x-show="phoneRequired">
                    <x-form.required-badge />
                </span>
            </legend>
            <div x-show="nameOrderWestern == '0'" class="flex items-center gap-2">
                <input type="tel" disabled maxlength="5" placeholder="090" class="input-common my-2 w-20 opacity-60">
                <span class="text-gray-500 dark:text-gray-400">-</span>
                <input type="tel" disabled maxlength="4" placeholder="1234" class="input-common my-2 w-20 opacity-60">
                <span class="text-gray-500 dark:text-gray-400">-</span>
                <input type="tel" disabled maxlength="4" placeholder="5678" class="input-common my-2 w-20 opacity-60">
            </div>
            <div x-show="nameOrderWestern == '1'">
                <input type="tel" disabled placeholder="{{ __('dixlase-inquiry::front.form.phone_placeholder') }}" class="input-common my-2 w-full opacity-60">
            </div>
        </fieldset>
    </div>

    {{-- 性別フィールド --}}
    <div x-show="showGender">
        <fieldset>
            <legend>
                {{ __('dixlase-inquiry::front.form.gender') }}
                <span x-show="genderRequired">
                    <x-form.required-badge />
                </span>
            </legend>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                <label class="flex items-center p-3 border border-gray-300 dark:border-gray-600 rounded-lg cursor-not-allowed opacity-60">
                    <input type="radio" disabled class="mr-2">
                    <i class="fas fa-mars text-blue-500 mr-2"></i>
                    <span class="text-sm">{{ __('dixlase-inquiry::front.form.gender_male') }}</span>
                </label>
                <label class="flex items-center p-3 border border-gray-300 dark:border-gray-600 rounded-lg cursor-not-allowed opacity-60">
                    <input type="radio" disabled class="mr-2">
                    <i class="fas fa-venus text-pink-500 mr-2"></i>
                    <span class="text-sm">{{ __('dixlase-inquiry::front.form.gender_female') }}</span>
                </label>
                <label class="flex items-center p-3 border border-gray-300 dark:border-gray-600 rounded-lg cursor-not-allowed opacity-60">
                    <input type="radio" disabled class="mr-2">
                    <i class="fas fa-genderless text-purple-500 mr-2"></i>
                    <span class="text-sm">{{ __('dixlase-inquiry::front.form.gender_other') }}</span>
                </label>
                <label class="flex items-center p-3 border border-gray-300 dark:border-gray-600 rounded-lg cursor-not-allowed opacity-60">
                    <input type="radio" disabled class="mr-2">
                    <i class="fas fa-user-secret text-gray-500 mr-2"></i>
                    <span class="text-sm">{{ __('common.prefer_not_to_say') }}</span>
                </label>
            </div>
        </fieldset>
    </div>

    {{-- お問い合わせ内容フィールド（常に表示・必須） --}}
    <fieldset>
        <legend>
            {{ __('dixlase-inquiry::front.form.message') }}
            <x-form.required-badge />
        </legend>
        <textarea disabled rows="6" placeholder="{{ __('dixlase-inquiry::front.form.message_placeholder') }}" class="input-common my-2 w-full opacity-60"></textarea>
    </fieldset>

    {{-- 送信ボタン --}}
    <div class="flex justify-center mt-6">
        <button type="button" disabled class="btn-primary px-8 py-3 opacity-60 cursor-not-allowed">
            {{ __('dixlase-inquiry::front.form.submit') }}
        </button>
    </div>
</div>
