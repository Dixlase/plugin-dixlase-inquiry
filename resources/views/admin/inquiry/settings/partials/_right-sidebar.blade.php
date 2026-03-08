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

{{-- Right sidebar toggle button (Desktop only) --}}
<button type="button"
        @click="toggleRightSidebar()"
        class="hidden lg:flex fixed top-14 right-0 z-40 items-center backdrop-blur-sm dark:bg-gray-900/75 bg-white/75 text-blue-400 dark:text-white px-1.5 py-4 rounded-l-lg shadow-md border border-r-0 border-gray-300 dark:border-gray-500 hover:bg-gray-100 dark:hover:bg-gray-800"
        :class="{
            'translate-x-0': rightSidebarCollapsed,
            '-translate-x-80': !rightSidebarCollapsed
        }"
        :style="rightSidebarReady ? 'transition: transform 200ms ease-in-out' : ''"
        :aria-label="rightSidebarCollapsed
            ? '{{ __('dixlase-inquiry::admin/inquiry/settings/form-basic.sidebar_open') }}'
            : '{{ __('dixlase-inquiry::admin/inquiry/settings/form-basic.sidebar_close') }}'">
    <i class="fas text-sm" :class="rightSidebarCollapsed ? 'fa-chevron-left' : 'fa-chevron-right'"></i>
</button>

{{-- Right sidebar --}}
<div class="mt-6 lg:mt-0 space-y-6 lg:fixed lg:top-12 lg:right-0 lg:bottom-0 lg:w-80 lg:z-30 lg:overflow-y-auto lg:bg-white/75 dark:lg:bg-gray-900/75 lg:backdrop-blur-sm lg:border-l lg:border-gray-200 dark:lg:border-gray-600 lg:shadow-md lg:px-6 lg:py-6"
     :class="{
         'lg:translate-x-80': rightSidebarCollapsed,
         'lg:translate-x-0': !rightSidebarCollapsed
     }"
     :style="rightSidebarReady ? 'transition: transform 300ms ease-in-out' : ''">

    {{-- Preview button --}}
    <div>
        <x-form-button
            type="button"
            icon="fas fa-external-link-alt"
            variant="secondary"
            size="sm"
            @click="openPreview()"
            class="w-full"
        >
            {{ __('dixlase-inquiry::admin/inquiry/settings/form-basic.preview_button') }}
        </x-form-button>
        <x-form-help-text :text="__('dixlase-inquiry::admin/inquiry/settings/form-basic.preview_page_help')" />
    </div>

    {{-- Form settings section --}}
    <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">
            {{ __('dixlase-inquiry::admin/inquiry/settings/form-basic.section_form_settings') }}
        </h3>

        {{-- Language selector --}}
        <div class="space-y-4">
            <div>
                <x-form-label :label="__('dixlase-inquiry::admin/inquiry/settings/form-basic.lang')" />
                <x-form-radio-card-group
                    name="lang"
                    :options="[
                        [
                            'value' => 'auto',
                            'label' => __('dixlase-inquiry::admin/inquiry/settings/form-basic.lang_auto'),
                            'description' => __('dixlase-inquiry::admin/inquiry/settings/form-basic.lang_auto_desc'),
                            'icon' => 'fas fa-magic',
                        ],
                        [
                            'value' => 'ja',
                            'label' => '日本語',
                            'description' => 'Japanese',
                            'icon' => 'fas fa-flag',
                        ],
                        [
                            'value' => 'en',
                            'label' => 'English',
                            'description' => '英語',
                            'icon' => 'fas fa-globe',
                        ],
                    ]"
                    :value="old('lang', $settings->lang ?? 'auto')"
                    xModel="selectedLocale"
                    :columns="1"
                    color="primary"
                />
                <x-form-help-text :text="__('dixlase-inquiry::admin/inquiry/settings/form-basic.lang_help')" />
            </div>

            {{-- Input format --}}
            <div>
                <x-form-label :label="__('dixlase-inquiry::admin/inquiry/settings/form-basic.format_style')" />
                <x-form-radio-card-group
                    name="name_order_western"
                    :options="[
                        [
                            'value' => '0',
                            'label' => __('dixlase-inquiry::admin/inquiry/settings/form-basic.format_japanese'),
                            'description' => __('dixlase-inquiry::admin/inquiry/settings/form-basic.format_japanese_desc'),
                            'icon' => 'fas fa-flag',
                        ],
                        [
                            'value' => '1',
                            'label' => __('dixlase-inquiry::admin/inquiry/settings/form-basic.format_western'),
                            'description' => __('dixlase-inquiry::admin/inquiry/settings/form-basic.format_western_desc'),
                            'icon' => 'fas fa-globe',
                        ],
                    ]"
                    :value="old('name_order_western', $settings->name_order_western ?? false) ? '1' : '0'"
                    xModel="nameOrderWestern"
                    :columns="1"
                    color="primary"
                />
                <x-form-help-text :text="__('dixlase-inquiry::admin/inquiry/settings/form-basic.format_style_help')" />
            </div>
        </div>
    </div>

    {{-- Field settings section --}}
    <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">
            {{ __('dixlase-inquiry::admin/inquiry/settings/form-basic.section_field_settings') }}
        </h3>

        <x-ui-message type="info" :message="__('dixlase-inquiry::admin/inquiry/settings/form-basic.required_fields_note')" textSize="text-xs" />

        <div class="space-y-3">
            {{-- Email confirmation paste prevention --}}
            <x-form-toggle
                name="email_confirm_paste_disabled"
                :label="__('dixlase-inquiry::admin/inquiry/settings/form-basic.email_confirm_paste_disabled')"
                :checked="old('email_confirm_paste_disabled', $settings->email_confirm_paste_disabled ?? true)"
                xModel="emailConfirmPasteDisabled"
            />

            {{-- Katakana (Furigana) --}}
            <div :class="{ 'opacity-50 pointer-events-none': nameOrderWestern === '1' }">
                <x-form-toggle
                    name="show_kana"
                    :label="__('dixlase-inquiry::admin/inquiry/settings/form-basic.show_kana')"
                    :checked="old('show_kana', $settings->show_kana ?? false)"
                    xModel="showKana"
                    ::disabled="nameOrderWestern === '1'"
                />
            </div>
            <div :class="{ 'opacity-50 pointer-events-none': nameOrderWestern === '1' }">
                <x-form-toggle
                    name="require_kana"
                    :label="__('dixlase-inquiry::admin/inquiry/settings/form-basic.require_kana')"
                    :checked="old('require_kana', $settings->require_kana ?? false)"
                    xBind="(showKana === '1' && nameOrderWestern === '0')"
                    xModel="requireKana"
                />
            </div>
            <x-ui-message type="info" :message="__('dixlase-inquiry::admin/inquiry/settings/form-basic.show_kana_help')" textSize="text-xs" />

            {{-- Subject --}}
            <x-form-toggle
                name="show_subject"
                :label="__('dixlase-inquiry::admin/inquiry/settings/form-basic.show_subject')"
                :checked="old('show_subject', $settings->show_subject ?? false)"
                xModel="showSubject"
            />
            <x-form-toggle
                name="subject_required"
                :label="__('dixlase-inquiry::admin/inquiry/settings/form-basic.subject_required')"
                :checked="old('subject_required', $settings->subject_required ?? false)"
                xBind="(showSubject === '1')"
                xModel="subjectRequired"
            />

            {{-- Postal code & Address --}}
            <x-form-toggle
                name="show_postal_code"
                :label="__('dixlase-inquiry::admin/inquiry/settings/form-basic.show_postal_address')"
                :checked="old('show_postal_code', $settings->show_postal_code ?? false)"
                xModel="showPostalCode"
            />
            <input type="hidden" name="show_address" :value="showPostalCode">
            <x-form-toggle
                name="postal_code_required"
                :label="__('dixlase-inquiry::admin/inquiry/settings/form-basic.postal_address_required')"
                :checked="old('postal_code_required', $settings->postal_code_required ?? false)"
                xBind="(showPostalCode === '1')"
                xModel="postalCodeRequired"
            />
            <input type="hidden" name="address_required" :value="postalCodeRequired">

            {{-- Phone --}}
            <x-form-toggle
                name="show_phone"
                :label="__('dixlase-inquiry::admin/inquiry/settings/form-basic.show_phone')"
                :checked="old('show_phone', $settings->show_phone ?? true)"
                xModel="showPhone"
            />
            <x-form-toggle
                name="phone_required"
                :label="__('dixlase-inquiry::admin/inquiry/settings/form-basic.phone_required')"
                :checked="old('phone_required', $settings->phone_required ?? false)"
                xBind="(showPhone === '1')"
                xModel="phoneRequired"
            />

            {{-- Gender --}}
            <x-form-toggle
                name="show_gender"
                :label="__('dixlase-inquiry::admin/inquiry/settings/form-basic.show_gender')"
                :checked="old('show_gender', $settings->show_gender ?? false)"
                xModel="showGender"
            />
            <x-form-toggle
                name="gender_required"
                :label="__('dixlase-inquiry::admin/inquiry/settings/form-basic.gender_required')"
                :checked="old('gender_required', $settings->gender_required ?? false)"
                xBind="(showGender === '1')"
                xModel="genderRequired"
            />

            {{-- Gender sub-options --}}
            <div class="ml-4 pl-3 border-l-2 border-gray-200 dark:border-gray-700 space-y-2" :class="{ 'opacity-50 pointer-events-none': showGender === '0' }">
                <x-ui-message type="info" :message="__('dixlase-inquiry::admin/inquiry/settings/form-basic.gender_default_note')" textSize="text-xs" />
                <x-form-toggle
                    name="show_gender_other"
                    :label="__('dixlase-inquiry::admin/inquiry/settings/form-basic.show_gender_other')"
                    :checked="old('show_gender_other', $settings->show_gender_other ?? false)"
                    xModel="showGenderOther"
                    ::disabled="showGender === '0'"
                />
                <x-form-toggle
                    name="show_gender_prefer_not_to_say"
                    :label="__('dixlase-inquiry::admin/inquiry/settings/form-basic.show_gender_prefer_not_to_say')"
                    :checked="old('show_gender_prefer_not_to_say', $settings->show_gender_prefer_not_to_say ?? false)"
                    xModel="showGenderPreferNotToSay"
                    ::disabled="showGender === '0'"
                />
            </div>
        </div>
    </div>

    {{-- Display settings section --}}
    <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">
            {{ __('dixlase-inquiry::admin/inquiry/settings/form-basic.section_display_settings') }}
        </h3>

        <div class="space-y-4">
            <div>
                <x-form-label :label="__('dixlase-inquiry::admin/inquiry/settings/form-basic.form_type')" />
                <x-form-radio-card-group
                    name="use_single_page"
                    :options="[
                        [
                            'value' => '1',
                            'label' => __('dixlase-inquiry::admin/inquiry/settings/form-basic.single_page'),
                            'description' => __('dixlase-inquiry::admin/inquiry/settings/form-basic.single_page_desc'),
                            'icon' => 'fas fa-file',
                        ],
                        [
                            'value' => '0',
                            'label' => __('dixlase-inquiry::admin/inquiry/settings/form-basic.separate_pages'),
                            'description' => __('dixlase-inquiry::admin/inquiry/settings/form-basic.separate_pages_desc'),
                            'icon' => 'fas fa-copy',
                        ],
                    ]"
                    :value="old('use_single_page', $settings->use_single_page ?? true) ? '1' : '0'"
                    xModel="useSinglePage"
                    :columns="1"
                    color="primary"
                />
            </div>

            {{-- URL slug (when separate pages selected) --}}
            <div x-show="useSinglePage === '0'" x-cloak>
                <x-form-label :label="__('dixlase-inquiry::admin/inquiry/settings/form-basic.inquiry_url')" />
                <div class="flex items-center gap-2">
                    <span class="text-sm text-gray-600 dark:text-gray-400">{{ url('/') }}/</span>
                    <div class="flex-1">
                        <input type="text"
                               name="inquiry_url_slug"
                               x-model="inquiryUrlSlug"
                               placeholder="inquiry"
                               class="block w-full px-3 py-2 bg-gray-50 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm dark:bg-gray-800 dark:border-gray-500 dark:focus:border-indigo-500 dark:focus:ring-indigo-500 dark:text-white">
                    </div>
                </div>
                <x-form-help-text :text="__('dixlase-inquiry::admin/inquiry/settings/form-basic.inquiry_url_slug_help')" />
            </div>

            {{-- Hidden field for URL slug when single page --}}
            <div x-show="useSinglePage === '1'" x-cloak>
                <input type="hidden" name="inquiry_url_slug" :value="inquiryUrlSlug">
            </div>

            {{-- Confirmation page --}}
            <x-form-toggle
                name="show_confirmation_page"
                :label="__('dixlase-inquiry::admin/inquiry/settings/form-basic.show_confirmation')"
                :checked="old('show_confirmation_page', $settings->show_confirmation_page ?? true)"
                xModel="showConfirmationPage"
            />
            <x-form-help-text :text="__('dixlase-inquiry::admin/inquiry/settings/form-basic.show_confirmation_help')" />
        </div>
    </div>

    {{-- Privacy consent section --}}
    <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">
            {{ __('dixlase-inquiry::admin/inquiry/settings/form-basic.section_privacy') }}
        </h3>

        <div class="space-y-4">
            <div>
                <x-form-toggle
                    name="privacy_consent_enabled"
                    :label="__('dixlase-inquiry::admin/inquiry/settings/form-basic.privacy_consent_enabled')"
                    :checked="old('privacy_consent_enabled', $settings->privacy_consent_enabled ?? false)"
                    xModel="privacyConsentEnabled"
                />
                <x-form-help-text :text="__('dixlase-inquiry::admin/inquiry/settings/form-basic.privacy_consent_enabled_help')" />
            </div>

            <div :class="{ 'opacity-50 pointer-events-none': privacyConsentEnabled === '0' }" class="space-y-3 ml-4 pl-3 border-l-2 border-gray-200 dark:border-gray-700">
                <div>
                    <x-form-label :label="__('dixlase-inquiry::admin/inquiry/settings/form-basic.privacy_policy_url')" />
                    <input type="url"
                           name="privacy_policy_url"
                           x-model="privacyPolicyUrl"
                           :disabled="privacyConsentEnabled === '0'"
                           placeholder="https://example.com/privacy"
                           class="block w-full px-3 py-2 bg-gray-50 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm dark:bg-gray-800 dark:border-gray-500 dark:focus:border-indigo-500 dark:focus:ring-indigo-500 dark:text-white">
                    <x-form-help-text :text="__('dixlase-inquiry::admin/inquiry/settings/form-basic.privacy_policy_url_help')" />
                </div>

                <div>
                    <x-form-label :label="__('dixlase-inquiry::admin/inquiry/settings/form-basic.privacy_consent_text')" />
                    <input type="text"
                           name="privacy_consent_text"
                           x-model="privacyConsentText"
                           :disabled="privacyConsentEnabled === '0'"
                           placeholder="{{ __('dixlase-inquiry::admin/inquiry/settings/form-basic.privacy_consent_text_placeholder') }}"
                           class="block w-full px-3 py-2 bg-gray-50 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm dark:bg-gray-800 dark:border-gray-500 dark:focus:border-indigo-500 dark:focus:ring-indigo-500 dark:text-white">
                    <x-form-help-text :text="__('dixlase-inquiry::admin/inquiry/settings/form-basic.privacy_consent_text_help')" />
                </div>
            </div>
        </div>
    </div>

    {{-- Throttle section --}}
    <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">
            {{ __('dixlase-inquiry::admin/inquiry/settings/form-basic.section_throttle') }}
        </h3>

        <div class="space-y-4">
            <div>
                <x-form-toggle
                    name="throttle_enabled"
                    :label="__('dixlase-inquiry::admin/inquiry/settings/form-basic.throttle_enabled')"
                    :checked="old('throttle_enabled', $settings->throttle_enabled ?? true)"
                    xModel="throttleEnabled"
                />
                <x-form-help-text :text="__('dixlase-inquiry::admin/inquiry/settings/form-basic.throttle_enabled_help')" />
            </div>

            <div :class="{ 'opacity-50 pointer-events-none': throttleEnabled === '0' }" class="ml-4 pl-3 border-l-2 border-gray-200 dark:border-gray-700 space-y-3">
                <div>
                    <x-form-label :label="__('dixlase-inquiry::admin/inquiry/settings/form-basic.throttle_max_attempts')" />
                    <input type="number"
                           name="throttle_max_attempts"
                           x-model="throttleMaxAttempts"
                           :disabled="throttleEnabled === '0'"
                           min="1"
                           max="100"
                           class="block w-full px-3 py-2 bg-gray-50 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm dark:bg-gray-800 dark:border-gray-500 dark:focus:border-indigo-500 dark:focus:ring-indigo-500 dark:text-white">
                </div>

                <div>
                    <x-form-label :label="__('dixlase-inquiry::admin/inquiry/settings/form-basic.throttle_decay_minutes')" />
                    <input type="number"
                           name="throttle_decay_minutes"
                           x-model="throttleDecayMinutes"
                           :disabled="throttleEnabled === '0'"
                           min="1"
                           max="1440"
                           class="block w-full px-3 py-2 bg-gray-50 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm dark:bg-gray-800 dark:border-gray-500 dark:focus:border-indigo-500 dark:focus:ring-indigo-500 dark:text-white">
                </div>
                <x-form-help-text :text="__('dixlase-inquiry::admin/inquiry/settings/form-basic.throttle_help')" />
            </div>
        </div>
    </div>

</div>
