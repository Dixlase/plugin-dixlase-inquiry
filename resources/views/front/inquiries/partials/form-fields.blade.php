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

{{-- フォームフィールド共通パーシャル（form / preview で共有） --}}

        {{-- 1. 名前（2カラム） --}}
        <fieldset class="border-0 p-0 m-0">
            <legend class="block w-full font-medium text-sm text-gray-700 dark:text-gray-300 mb-2 pt-4">
                {{ __('dixlase-inquiry::front.form.name') }}
                <x-form-required-badge />
            </legend>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @if($settings->name_order_western ?? false)
                {{-- 欧米式（名・姓） --}}
                <div>
                    <x-form-text
                        name="first_name"
                        :value="old('first_name')"
                        :required="true"
                        :placeholder="__('dixlase-inquiry::front.form.first_name_placeholder')"
                        :label="__('dixlase-inquiry::front.form.first_name')"
                    />
                </div>
                <div>
                    <x-form-text
                        name="last_name"
                        :value="old('last_name')"
                        :required="true"
                        :placeholder="__('dixlase-inquiry::front.form.last_name_placeholder')"
                        :label="__('dixlase-inquiry::front.form.last_name')"
                    />
                </div>
                @else
                {{-- 日本式（姓・名） --}}
                <div>
                    <x-form-text
                        name="last_name"
                        :value="old('last_name')"
                        :required="true"
                        :placeholder="__('dixlase-inquiry::front.form.last_name_placeholder')"
                        :label="__('dixlase-inquiry::front.form.last_name')"
                    />
                </div>
                <div>
                    <x-form-text
                        name="first_name"
                        :value="old('first_name')"
                        :required="true"
                        :placeholder="__('dixlase-inquiry::front.form.first_name_placeholder')"
                        :label="__('dixlase-inquiry::front.form.first_name')"
                    />
                </div>
                @endif
            </div>
        </fieldset>

        {{-- 1b. カタカナ（日本式+カナONの場合のみ） --}}
        @if(!($settings->name_order_western ?? false) && ($settings->show_kana ?? false))
        <fieldset class="border-0 p-0 m-0">
            <legend class="block w-full font-medium text-sm text-gray-700 dark:text-gray-300 mb-2 pt-4">
                {{ __('dixlase-inquiry::front.form.kana') }}
                @if($settings->require_kana ?? false)
                    <x-form-required-badge />
                @endif
            </legend>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <x-form-text
                        name="last_name_kana"
                        :value="old('last_name_kana')"
                        :required="$settings->require_kana ?? false"
                        :placeholder="__('dixlase-inquiry::front.form.last_name_kana_placeholder')"
                        :label="__('dixlase-inquiry::front.form.last_name_kana')"
                    />
                </div>
                <div>
                    <x-form-text
                        name="first_name_kana"
                        :value="old('first_name_kana')"
                        :required="$settings->require_kana ?? false"
                        :placeholder="__('dixlase-inquiry::front.form.first_name_kana_placeholder')"
                        :label="__('dixlase-inquiry::front.form.first_name_kana')"
                    />
                </div>
            </div>
        </fieldset>
        @endif

        {{-- 2. メールアドレス --}}
        <fieldset class="border-0 p-0 m-0">
            <legend class="block w-full font-medium text-sm text-gray-700 dark:text-gray-300 mb-2 pt-4">
                {{ __('dixlase-inquiry::front.form.email') }}
                <x-form-required-badge />
            </legend>
            <x-form-text
                type="email"
                name="email"
                :value="old('email')"
                :required="true"
                :placeholder="__('dixlase-inquiry::front.form.email_placeholder')"
            />
        </fieldset>

        {{-- 2b. メールアドレス（確認） --}}
        <fieldset class="border-0 p-0 m-0">
            <legend class="block w-full font-medium text-sm text-gray-700 dark:text-gray-300 mb-2 pt-4">
                {{ __('dixlase-inquiry::front.form.email_confirmation') }}
                <x-form-required-badge />
            </legend>
            @if($settings->email_confirm_paste_disabled ?? true)
            <div x-data x-on:paste.prevent>
            @endif
            <x-form-text
                type="email"
                name="email_confirmation"
                :value="old('email_confirmation')"
                :required="true"
                :placeholder="__('dixlase-inquiry::front.form.email_confirmation_placeholder')"
                autocomplete="off"
            />
            @if($settings->email_confirm_paste_disabled ?? true)
            </div>
            @endif
            <x-form-help-text :text="__('dixlase-inquiry::front.form.email_confirmation_help')" />
        </fieldset>

        {{-- 3. 郵便番号 --}}
        @if($settings->show_address ?? false)
        <fieldset class="border-0 p-0 m-0">
            <legend class="block w-full font-medium text-sm text-gray-700 dark:text-gray-300 mb-2 pt-4">
                {{ __('dixlase-inquiry::front.form.postal_code') }}
                @if($settings->postal_code_required ?? false)
                    <x-form-required-badge />
                @endif
            </legend>
            @if($settings->name_order_western ?? false)
                {{-- 欧米式: 単一フィールド --}}
                <x-form-text
                    name="postal_code"
                    :value="old('postal_code')"
                    :required="$settings->postal_code_required ?? false"
                    :placeholder="__('dixlase-inquiry::front.form.postal_code_placeholder')"
                />
            @else
                {{-- 日本式: 2分割（3桁-4桁） --}}
                <div class="flex items-center gap-2">
                    <x-form-text
                        name="postal_code_1"
                        :value="old('postal_code_1')"
                        :required="$settings->postal_code_required ?? false"
                        :placeholder="__('dixlase-inquiry::front.form.postal_code_1_placeholder')"
                        maxlength="3"
                        class="w-24"
                    />
                    <span class="text-gray-500 dark:text-gray-400">-</span>
                    <x-form-text
                        name="postal_code_2"
                        :value="old('postal_code_2')"
                        :required="$settings->postal_code_required ?? false"
                        :placeholder="__('dixlase-inquiry::front.form.postal_code_2_placeholder')"
                        maxlength="4"
                        class="w-28"
                    />
                </div>
            @endif
        </fieldset>
        @endif

        {{-- 4. 住所 --}}
        @if($settings->show_address ?? false)
        <fieldset class="border-0 p-0 m-0">
            <legend class="block w-full font-medium text-sm text-gray-700 dark:text-gray-300 mb-2 pt-4">
                {{ __('dixlase-inquiry::front.form.address') }}
                @if($settings->address_required ?? false)
                    <x-form-required-badge />
                @endif
            </legend>
            @if($settings->name_order_western ?? false)
                {{-- 欧米式住所: Address Line → Building → City → State → Country --}}
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            {{ __('dixlase-inquiry::front.form.street_address') }}
                        </label>
                        <x-form-text
                            name="street_address"
                            :value="old('street_address')"
                            :required="$settings->address_required ?? false"
                            :placeholder="__('dixlase-inquiry::front.form.street_address_placeholder')"
                        />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            {{ __('dixlase-inquiry::front.form.building') }}
                        </label>
                        <x-form-text
                            name="building"
                            :value="old('building')"
                            :required="false"
                            :placeholder="__('dixlase-inquiry::front.form.building_placeholder')"
                        />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            {{ __('dixlase-inquiry::front.form.city') }}
                        </label>
                        <x-form-text
                            name="city"
                            :value="old('city')"
                            :required="$settings->address_required ?? false"
                            :placeholder="__('dixlase-inquiry::front.form.city_placeholder')"
                        />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            {{ __('dixlase-inquiry::front.form.state') }}
                        </label>
                        <x-form-text
                            name="state"
                            :value="old('state')"
                            :required="false"
                            :placeholder="__('dixlase-inquiry::front.form.state_placeholder')"
                        />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            {{ __('dixlase-inquiry::front.form.country') }}
                        </label>
                        <x-form-text
                            name="country"
                            :value="old('country')"
                            :required="false"
                            :placeholder="__('dixlase-inquiry::front.form.country_placeholder')"
                        />
                    </div>
                </div>
            @else
                {{-- 日本式住所: 都道府県(select) + 市区町村 + 番地 + 建物名 --}}
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            {{ __('dixlase-inquiry::front.form.prefecture') }}
                        </label>
                        <x-form-select
                            name="prefecture"
                            :value="old('prefecture')"
                            :required="$settings->address_required ?? false"
                            :options="array_merge(['' => __('dixlase-inquiry::front.form.prefecture_placeholder')], $prefectures)"
                        />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            {{ __('dixlase-inquiry::front.form.city') }}
                        </label>
                        <x-form-text
                            name="city"
                            :value="old('city')"
                            :required="$settings->address_required ?? false"
                            :placeholder="__('dixlase-inquiry::front.form.city_placeholder')"
                        />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            {{ __('dixlase-inquiry::front.form.address_line') }}
                        </label>
                        <x-form-text
                            name="address_line"
                            :value="old('address_line')"
                            :required="$settings->address_required ?? false"
                            :placeholder="__('dixlase-inquiry::front.form.address_line_placeholder')"
                        />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            {{ __('dixlase-inquiry::front.form.building') }}
                        </label>
                        <x-form-text
                            name="building"
                            :value="old('building')"
                            :required="false"
                            :placeholder="__('dixlase-inquiry::front.form.building_placeholder')"
                        />
                    </div>
                </div>
            @endif
        </fieldset>
        @endif

        {{-- 5. 電話番号 --}}
        @if($settings->show_phone ?? true)
        <fieldset class="border-0 p-0 m-0">
            <legend class="block w-full font-medium text-sm text-gray-700 dark:text-gray-300 mb-2 pt-4">
                {{ __('dixlase-inquiry::front.form.phone') }}
                @if($settings->phone_required ?? false)
                    <x-form-required-badge />
                @endif
            </legend>
            @if($settings->name_order_western ?? false)
                {{-- 欧米式: 単一フィールド --}}
                <x-form-text
                    type="tel"
                    name="phone"
                    :value="old('phone')"
                    :required="$settings->phone_required ?? false"
                    :placeholder="__('dixlase-inquiry::front.form.phone_placeholder')"
                />
            @else
                {{-- 日本式: 3分割 --}}
                <div class="flex items-center gap-2">
                    <x-form-text
                        type="tel"
                        name="phone_1"
                        :value="old('phone_1')"
                        :required="$settings->phone_required ?? false"
                        :placeholder="__('dixlase-inquiry::front.form.phone_1_placeholder')"
                        maxlength="5"
                        class="w-24"
                    />
                    <span class="text-gray-500 dark:text-gray-400">-</span>
                    <x-form-text
                        type="tel"
                        name="phone_2"
                        :value="old('phone_2')"
                        :required="$settings->phone_required ?? false"
                        :placeholder="__('dixlase-inquiry::front.form.phone_2_placeholder')"
                        maxlength="4"
                        class="w-24"
                    />
                    <span class="text-gray-500 dark:text-gray-400">-</span>
                    <x-form-text
                        type="tel"
                        name="phone_3"
                        :value="old('phone_3')"
                        :required="$settings->phone_required ?? false"
                        :placeholder="__('dixlase-inquiry::front.form.phone_3_placeholder')"
                        maxlength="4"
                        class="w-24"
                    />
                </div>
            @endif
        </fieldset>
        @endif

        {{-- 6. 性別（ラジオカード） --}}
        @if($settings->show_gender ?? false)
        <fieldset class="border-0 p-0 m-0">
            <legend class="block w-full font-medium text-sm text-gray-700 dark:text-gray-300 mb-2 pt-4">
                {{ __('dixlase-inquiry::front.form.gender') }}
                @if($settings->gender_required ?? false)
                    <x-form-required-badge />
                @endif
            </legend>
            <x-form-radio-card-group
                name="gender"
                :options="$genderOptions"
                :value="old('gender')"
                :columns="4"
                color="primary"
            />
        </fieldset>
        @endif

        {{-- 7. 題名（条件付き表示・お問い合わせ内容の直前） --}}
        @if($settings->show_subject ?? false)
        <fieldset class="border-0 p-0 m-0">
            <legend class="block w-full font-medium text-sm text-gray-700 dark:text-gray-300 mb-2 pt-4">
                {{ __('dixlase-inquiry::front.form.subject') }}
                @if($settings->subject_required ?? false)
                    <x-form-required-badge />
                @endif
            </legend>
            <x-form-text
                name="subject"
                :value="old('subject')"
                :required="$settings->subject_required ?? false"
                :placeholder="__('dixlase-inquiry::front.form.subject_placeholder')"
            />
        </fieldset>
        @endif

        {{-- 8. お問い合わせ内容 --}}
        <fieldset class="border-0 p-0 m-0">
            <legend class="block w-full font-medium text-sm text-gray-700 dark:text-gray-300 mb-2 pt-4">
                {{ __('dixlase-inquiry::front.form.message') }}
                <x-form-required-badge />
            </legend>
            <x-form-textarea
                name="message"
                :value="old('message')"
                :required="true"
                :rows="6"
                :placeholder="__('dixlase-inquiry::front.form.message_placeholder')"
            />
        </fieldset>

        {{-- 9. プライバシー同意 --}}
        @if($settings->privacy_consent_enabled ?? false)
        <fieldset class="border-0 p-0 m-0 mt-6">
            <div class="flex items-center space-x-3 my-3">
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="hidden" name="privacy_agreed" value="0">
                    <input type="checkbox" name="privacy_agreed" value="1" required
                           {{ old('privacy_agreed') ? 'checked' : '' }}
                           class="sr-only peer">
                    <div class="w-11 h-6 rounded-full bg-gray-300 dark:bg-gray-600 peer-checked:bg-blue-600 dark:peer-checked:bg-blue-500 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-offset-2 peer-focus:ring-blue-500 transition-colors"></div>
                    <div class="absolute left-1 top-1 w-4 h-4 bg-white border border-gray-300 rounded-full transition-all peer-checked:translate-x-full peer-checked:border-white"></div>
                </label>
                <span class="text-sm text-gray-700 dark:text-gray-300">
                    @if(!empty($settings->privacy_consent_text))
                        @if(!empty($privacyUrl))
                            <a href="{{ $privacyUrl }}" target="_blank" class="text-blue-600 hover:underline dark:text-blue-400">{{ $settings->privacy_consent_text }}</a>
                        @else
                            {{ $settings->privacy_consent_text }}
                        @endif
                    @else
                        @if(!empty($privacyUrl))
                            {!! __('dixlase-inquiry::front.form.privacy_consent', ['url' => $privacyUrl]) !!}
                        @else
                            {{ __('dixlase-inquiry::front.form.privacy_consent_default') }}
                        @endif
                    @endif
                </span>
            </div>
            @error('privacy_agreed')
                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
            @enderror
        </fieldset>
        @endif

        {{-- CAPTCHA --}}
        <x-captcha :enabled="$captchaEnabled ?? false" :widget="$captchaWidget ?? null" />

        {{-- プレビュー時: hidden fields --}}
        @if($isPreview ?? false)
            <input type="hidden" name="_preview_save_to_db" :value="previewSaveToDb ? '1' : '0'">
            <input type="hidden" name="_preview_send_email" :value="previewSendEmail ? '1' : '0'">
        @endif

        {{-- 10. 送信ボタン --}}
        <div class="flex justify-center gap-2 mt-6">
            <x-form-button
                type="submit"
                variant="primary"
                :label="$settings->show_confirmation_page ?? true ? __('dixlase-inquiry::front.buttons.confirm') : __('dixlase-inquiry::front.form.submit')"
                icon="fas fa-paper-plane"
            />
        </div>
