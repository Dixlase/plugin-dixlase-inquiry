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
            <span x-text="getLabel('name')"></span>
            <x-form-required-badge />
        </legend>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            {{-- 日本式: 姓が先 --}}
            <div x-show="nameOrderWestern == '0'">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    <span x-text="getLabel('last_name')"></span>
                    <span class="text-xs text-gray-500 dark:text-gray-400">(<span x-text="getLabel('last_name_label_ja')"></span>)</span>
                </label>
                <input type="text" disabled class="input-common my-2 w-full opacity-60">
            </div>
            <div x-show="nameOrderWestern == '0'">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    <span x-text="getLabel('first_name')"></span>
                    <span class="text-xs text-gray-500 dark:text-gray-400">(<span x-text="getLabel('first_name_label_ja')"></span>)</span>
                </label>
                <input type="text" disabled class="input-common my-2 w-full opacity-60">
            </div>
            {{-- 欧米式: 名が先 --}}
            <div x-show="nameOrderWestern == '1'">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    <span x-text="getLabel('first_name')"></span>
                    <span class="text-xs text-gray-500 dark:text-gray-400">(<span x-text="getLabel('first_name_label_en')"></span>)</span>
                </label>
                <input type="text" disabled class="input-common my-2 w-full opacity-60">
            </div>
            <div x-show="nameOrderWestern == '1'">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    <span x-text="getLabel('last_name')"></span>
                    <span class="text-xs text-gray-500 dark:text-gray-400">(<span x-text="getLabel('last_name_label_en')"></span>)</span>
                </label>
                <input type="text" disabled class="input-common my-2 w-full opacity-60">
            </div>
        </div>
    </fieldset>

    {{-- メールアドレスフィールド（常に表示・必須） --}}
    <fieldset>
        <legend>
            <span x-text="getLabel('email')"></span>
            <x-form-required-badge />
        </legend>
        <input type="email" disabled class="input-common my-2 w-full opacity-60">
    </fieldset>

    {{-- 題名フィールド --}}
    <div x-show="showSubject === '1'">
        <fieldset>
            <legend>
                <span x-text="getLabel('subject')"></span>
                <span x-show="subjectRequired === '1'">
                    <x-form-required-badge />
                </span>
            </legend>
            <input type="text" disabled class="input-common my-2 w-full opacity-60">
        </fieldset>
    </div>

    {{-- 郵便番号フィールド --}}
    <div x-show="showPostalCode === '1'">
        <fieldset>
            <legend>
                <span x-text="getLabel('postal_code')"></span>
                <span x-show="postalCodeRequired === '1'">
                    <x-form-required-badge />
                </span>
            </legend>
            <div x-show="nameOrderWestern == '0'" class="flex items-center gap-2">
                <input type="text" disabled maxlength="3" placeholder="123" class="input-common my-2 w-20 opacity-60">
                <span class="text-gray-500 dark:text-gray-400">-</span>
                <input type="text" disabled maxlength="4" placeholder="4567" class="input-common my-2 w-24 opacity-60">
            </div>
            <div x-show="nameOrderWestern == '1'">
                <input type="text" disabled class="input-common my-2 w-full opacity-60">
            </div>
        </fieldset>
    </div>

    {{-- 住所フィールド --}}
    <div x-show="showAddress === '1'">
        <fieldset>
            <legend>
                <span x-text="getLabel('address')"></span>
                <span x-show="addressRequired === '1'">
                    <x-form-required-badge />
                </span>
            </legend>
            <div x-show="nameOrderWestern == '0'" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        <span x-text="getLabel('prefecture')"></span>
                    </label>
                    <select disabled class="input-common my-2 w-full opacity-60">
                        <option>{{ __('common.please_select') }}</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        <span x-text="getLabel('city')"></span>
                    </label>
                    <input type="text" disabled class="input-common my-2 w-full opacity-60">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        <span x-text="getLabel('address_line')"></span>
                    </label>
                    <input type="text" disabled class="input-common my-2 w-full opacity-60">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        <span x-text="getLabel('building')"></span>
                    </label>
                    <input type="text" disabled class="input-common my-2 w-full opacity-60">
                </div>
            </div>
            <div x-show="nameOrderWestern == '1'" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        <span x-text="getLabel('country')"></span>
                    </label>
                    <select disabled class="input-common my-2 w-full opacity-60">
                        <option>{{ __('common.please_select') }}</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        <span x-text="getLabel('state_province')"></span>
                    </label>
                    <input type="text" disabled class="input-common my-2 w-full opacity-60">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        <span x-text="getLabel('city')"></span>
                    </label>
                    <input type="text" disabled class="input-common my-2 w-full opacity-60">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        <span x-text="getLabel('address_line')"></span>
                    </label>
                    <input type="text" disabled class="input-common my-2 w-full opacity-60">
                </div>
            </div>
        </fieldset>
    </div>

    {{-- 電話番号フィールド --}}
    <div x-show="showPhone === '1'">
        <fieldset>
            <legend>
                <span x-text="getLabel('phone')"></span>
                <span x-show="phoneRequired === '1'">
                    <x-form-required-badge />
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
                <input type="tel" disabled class="input-common my-2 w-full opacity-60">
            </div>
        </fieldset>
    </div>

    {{-- 性別フィールド --}}
    <div x-show="showGender === '1'">
        <fieldset>
            <legend>
                <span x-text="getLabel('gender')"></span>
                <span x-show="genderRequired === '1'">
                    <x-form-required-badge />
                </span>
            </legend>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                <label class="flex items-center p-3 border border-gray-300 dark:border-gray-600 rounded-lg cursor-not-allowed opacity-60">
                    <input type="radio" disabled class="mr-2">
                    <i class="fas fa-mars text-blue-500 mr-2"></i>
                    <span class="text-sm" x-text="getLabel('gender_male')"></span>
                </label>
                <label class="flex items-center p-3 border border-gray-300 dark:border-gray-600 rounded-lg cursor-not-allowed opacity-60">
                    <input type="radio" disabled class="mr-2">
                    <i class="fas fa-venus text-pink-500 mr-2"></i>
                    <span class="text-sm" x-text="getLabel('gender_female')"></span>
                </label>
                <label class="flex items-center p-3 border border-gray-300 dark:border-gray-600 rounded-lg cursor-not-allowed opacity-60">
                    <input type="radio" disabled class="mr-2">
                    <i class="fas fa-genderless text-purple-500 mr-2"></i>
                    <span class="text-sm" x-text="getLabel('gender_other')"></span>
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
            <span x-text="getLabel('message')"></span>
            <x-form-required-badge />
        </legend>
        <textarea disabled rows="6" class="input-common my-2 w-full opacity-60"></textarea>
    </fieldset>

    {{-- 送信ボタン --}}
    <div class="flex justify-center mt-6">
        <button type="button" disabled class="btn-primary px-8 py-3 opacity-60 cursor-not-allowed">
            <span x-text="getLabel('submit')"></span>
        </button>
    </div>
</div>
