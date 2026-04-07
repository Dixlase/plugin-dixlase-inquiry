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

<style @cspNonce>
.inquiry-preview-heading { font-size: 1.875rem !important; line-height: 2.25rem !important; font-weight: 700 !important; margin-bottom: 0.75rem !important; }
.inquiry-preview-description { font-size: 1rem !important; line-height: 1.5rem !important; margin-top: 0 !important; }
</style>
<div class="inquiry-form-preview">
    {{-- 見出し・説明（リアルタイム反映、空なら非表示） --}}
    <div class="text-center mb-6" x-show="formHeading || formDescription">
        <h3 x-show="formHeading" class="inquiry-preview-heading text-gray-900 dark:text-white"
            x-text="formHeading"></h3>
        <p x-show="formDescription" class="inquiry-preview-description text-gray-600 dark:text-gray-400"
            x-html="formDescription.replace(/\n/g, '<br>')"></p>
    </div>

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

    {{-- カタカナ（フリガナ）フィールド --}}
    <div x-show="showKana === '1' && nameOrderWestern == '0'">
        <fieldset>
            <legend>
                <span x-text="getLabel('kana')"></span>
                <span x-show="requireKana === '1'">
                    <x-form-required-badge />
                </span>
            </legend>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        <span x-text="getLabel('last_name_kana')"></span>
                    </label>
                    <input type="text" disabled class="input-common my-2 w-full opacity-60">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        <span x-text="getLabel('first_name_kana')"></span>
                    </label>
                    <input type="text" disabled class="input-common my-2 w-full opacity-60">
                </div>
            </div>
        </fieldset>
    </div>

    {{-- メールアドレスフィールド（常に表示・必須） --}}
    <fieldset>
        <legend>
            <span x-text="getLabel('email')"></span>
            <x-form-required-badge />
        </legend>
        <input type="email" disabled class="input-common my-2 w-full opacity-60">
    </fieldset>

    {{-- メールアドレス確認フィールド（常に表示・必須） --}}
    <fieldset>
        <legend>
            <span x-text="getLabel('email_confirmation')"></span>
            <x-form-required-badge />
        </legend>
        <input type="email" disabled class="input-common my-2 w-full opacity-60">
    </fieldset>

    {{-- 郵便番号フィールド --}}
    <div x-show="showAddress === '1'">
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
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        <span x-text="getLabel('city')"></span>
                    </label>
                    <input type="text" disabled class="input-common my-2 w-full opacity-60">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        <span x-text="getLabel('state_province')"></span>
                    </label>
                    <input type="text" disabled class="input-common my-2 w-full opacity-60">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        <span x-text="getLabel('country')"></span>
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
                <label x-show="showGenderOther === '1'" class="flex items-center p-3 border border-gray-300 dark:border-gray-600 rounded-lg cursor-not-allowed opacity-60">
                    <input type="radio" disabled class="mr-2">
                    <i class="fas fa-genderless text-purple-500 mr-2"></i>
                    <span class="text-sm" x-text="getLabel('gender_other')"></span>
                </label>
                <label x-show="showGenderPreferNotToSay === '1'" class="flex items-center p-3 border border-gray-300 dark:border-gray-600 rounded-lg cursor-not-allowed opacity-60">
                    <input type="radio" disabled class="mr-2">
                    <i class="fas fa-user-secret text-gray-500 mr-2"></i>
                    <span class="text-sm" x-text="getLabel('gender_prefer_not_to_say')"></span>
                </label>
            </div>
        </fieldset>
    </div>

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

    {{-- お問い合わせ内容フィールド（常に表示・必須） --}}
    <fieldset>
        <legend>
            <span x-text="getLabel('message')"></span>
            <x-form-required-badge />
        </legend>
        <textarea disabled rows="6" class="input-common my-2 w-full opacity-60"></textarea>
    </fieldset>

    {{-- プライバシー同意 --}}
    <div x-show="privacyConsentEnabled === '1'">
        <fieldset class="mt-6">
            <div class="flex items-center space-x-3 my-3 opacity-60">
                <label class="relative inline-flex items-center cursor-not-allowed">
                    <input type="checkbox" disabled class="sr-only peer">
                    <div class="w-11 h-6 rounded-full bg-gray-200 dark:bg-gray-600 peer-checked:bg-blue-600 dark:peer-checked:bg-blue-500"></div>
                    <div class="absolute left-1 top-1 w-4 h-4 bg-white border border-gray-300 rounded-full transition-all peer-checked:translate-x-full peer-checked:border-white"></div>
                </label>
                <span class="text-sm text-gray-700 dark:text-gray-300">
                    {{-- Custom text with URL: entire text linked --}}
                    <template x-if="privacyConsentText && privacyPolicyUrl">
                        <a :href="privacyPolicyUrl" target="_blank" class="text-blue-600 hover:underline dark:text-blue-400" x-text="privacyConsentText"></a>
                    </template>
                    {{-- Custom text without URL: plain text --}}
                    <template x-if="privacyConsentText && !privacyPolicyUrl">
                        <span x-text="privacyConsentText"></span>
                    </template>
                    {{-- Default text with URL: "Privacy Policy" part linked --}}
                    <template x-if="!privacyConsentText && privacyPolicyUrl">
                        <span>
                            <span x-text="getLabel('privacy_consent_default').split(getLabel('privacy_policy_label'))[0]"></span>
                            <a :href="privacyPolicyUrl" target="_blank" class="text-blue-600 hover:underline dark:text-blue-400" x-text="getLabel('privacy_policy_label')"></a>
                            <span x-text="getLabel('privacy_consent_default').split(getLabel('privacy_policy_label'))[1]"></span>
                        </span>
                    </template>
                    {{-- Default text without URL: plain text --}}
                    <template x-if="!privacyConsentText && !privacyPolicyUrl">
                        <span x-text="getLabel('privacy_consent_default')"></span>
                    </template>
                </span>
            </div>
        </fieldset>
    </div>

    {{-- 送信ボタン --}}
    <div class="flex justify-center mt-6">
        <x-form-button type="button" variant="primary" size="lg" :disabled="true" class="opacity-60 cursor-not-allowed" icon="fas fa-paper-plane">
            <span x-show="showConfirmationPage === '1'" x-text="getLabel('confirm_button')"></span>
            <span x-show="showConfirmationPage !== '1'" x-text="getLabel('submit')"></span>
        </x-form-button>
    </div>
</div>
