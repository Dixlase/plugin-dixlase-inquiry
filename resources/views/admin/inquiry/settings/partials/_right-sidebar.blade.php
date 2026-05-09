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

<x-admin.right-sidebar
    :openLabel="__('dixlase-inquiry::admin/inquiry/settings/form-basic.sidebar_open')"
    :closeLabel="__('dixlase-inquiry::admin/inquiry/settings/form-basic.sidebar_close')"
>

    {{-- Simple mode notice --}}
    @if($isSimpleMode)
        <x-ui-message type="info" :message="__('dixlase-inquiry::admin/inquiry/settings/form-basic.simple_mode_notice')" textSize="text-xs" />
    @endif

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

    {{-- Form settings section (hidden in simple mode) --}}
    @if($isSimpleMode)
        <input type="hidden" name="lang" value="auto">
        <input type="hidden" name="name_order_western" value="0">
    @else
        <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">
                {{ __('dixlase-inquiry::admin/inquiry/settings/form-basic.section_form_settings') }}
            </h3>

            {{-- Language selector --}}
            <div class="space-y-4">
                <div>
                    <x-form-label for="lang" :text="__('dixlase-inquiry::admin/inquiry/settings/form-basic.lang')" />
                    <x-form-select
                        name="lang"
                        :options="\Plugins\DixlaseInquiry\App\Support\InquiryLocaleSupport::langSelectOptions()"
                        :value="old('lang', $settings->lang ?? 'auto')"
                        xModel="selectedLocale"
                    />
                    @if(\Plugins\DixlaseInquiry\App\Support\InquiryLocaleSupport::multilingualEnabled())
                        <x-form-help-text :text="__('dixlase-inquiry::admin/inquiry/settings/form-basic.lang_help_multilingual')" />
                    @else
                        <x-form-help-text :text="__('dixlase-inquiry::admin/inquiry/settings/form-basic.lang_help')" />
                    @endif
                </div>

                {{-- Input format --}}
                <div>
                    <x-form-label for="name_order_western" :text="__('dixlase-inquiry::admin/inquiry/settings/form-basic.format_style')" />
                    @php
                        $rawNameOrder = old('name_order_western', $settings->name_order_western ?? '0');
                        $nameOrderValue = $rawNameOrder === 'auto' ? 'auto' : (filter_var($rawNameOrder, FILTER_VALIDATE_BOOLEAN) ? '1' : '0');
                    @endphp
                    <x-form-select
                        name="name_order_western"
                        :options="[
                            'auto' => __('dixlase-inquiry::admin/inquiry/settings/form-basic.format_auto'),
                            '0' => __('dixlase-inquiry::admin/inquiry/settings/form-basic.format_japanese'),
                            '1' => __('dixlase-inquiry::admin/inquiry/settings/form-basic.format_western'),
                        ]"
                        :value="$nameOrderValue"
                        xModel="nameOrderWestern"
                    />
                    @if(\Plugins\DixlaseInquiry\App\Support\InquiryLocaleSupport::multilingualEnabled())
                        <x-form-help-text :text="__('dixlase-inquiry::admin/inquiry/settings/form-basic.format_style_help_multilingual')" />
                    @else
                        <x-form-help-text :text="__('dixlase-inquiry::admin/inquiry/settings/form-basic.format_style_help')" />
                    @endif
                </div>

                {{-- Form heading --}}
                <div>
                    <x-form-label for="form_heading" :text="__('dixlase-inquiry::admin/inquiry/settings/form-basic.form_heading')" />
                    <x-form-text
                        name="form_heading"
                        :value="old('form_heading', $settings->form_heading ?? '')"
                        xModel="formHeading"
                    />
                    <x-form-help-text :text="__('dixlase-inquiry::admin/inquiry/settings/form-basic.form_heading_help')" />
                </div>

                {{-- Form description --}}
                <div>
                    <x-form-label for="form_description" :text="__('dixlase-inquiry::admin/inquiry/settings/form-basic.form_description')" />
                    <textarea
                        id="form_description"
                        name="form_description"
                        rows="3"
                        x-model="formDescription"
                        class="input-common block w-full p-2 bg-gray-50 dark:bg-gray-800 border border-gray-300 dark:border-gray-500 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm dark:text-white"
                    >{{ old('form_description', $settings->form_description ?? '') }}</textarea>
                    <x-form-help-text :text="__('dixlase-inquiry::admin/inquiry/settings/form-basic.form_description_help')" />
                </div>
            </div>
        </div>
    @endif

    {{-- Field settings section --}}
    <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">
            {{ __('dixlase-inquiry::admin/inquiry/settings/form-basic.section_field_settings') }}
        </h3>

        <x-ui-message type="info" :message="__('dixlase-inquiry::admin/inquiry/settings/form-basic.required_fields_note')" textSize="text-xs" />

        <div class="space-y-3">
            {{-- Email confirmation paste prevention (hidden in simple mode) --}}
            @if($isSimpleMode)
                <input type="hidden" name="email_confirm_paste_disabled" value="1">
            @else
                <x-form-toggle
                    name="email_confirm_paste_disabled"
                    :label="__('dixlase-inquiry::admin/inquiry/settings/form-basic.email_confirm_paste_disabled')"
                    :checked="old('email_confirm_paste_disabled', $settings->email_confirm_paste_disabled ?? true)"
                    xModel="emailConfirmPasteDisabled"
                />
            @endif

            {{-- Katakana (Furigana) --}}
            <div :class="{ 'opacity-50 pointer-events-none': nameOrderWesternResolved === '1' }">
                <x-form-toggle
                    name="show_kana"
                    :label="__('dixlase-inquiry::admin/inquiry/settings/form-basic.show_kana')"
                    :checked="old('show_kana', $settings->show_kana ?? false)"
                    xModel="showKana"
                    ::disabled="nameOrderWesternResolved === '1'"
                />
            </div>
            <div class="ml-4 pl-3 border-l-2 border-gray-200 dark:border-gray-700" :class="{ 'opacity-50 pointer-events-none': nameOrderWesternResolved === '1' }">
                <x-form-toggle
                    name="require_kana"
                    :label="__('dixlase-inquiry::admin/inquiry/settings/form-basic.require_kana')"
                    :checked="old('require_kana', $settings->require_kana ?? false)"
                    xBind="(showKana === '1' && nameOrderWesternResolved === '0')"
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
            <div class="ml-4 pl-3 border-l-2 border-gray-200 dark:border-gray-700">
                <x-form-toggle
                    name="subject_required"
                    :label="__('dixlase-inquiry::admin/inquiry/settings/form-basic.subject_required')"
                    :checked="old('subject_required', $settings->subject_required ?? false)"
                    xBind="(showSubject === '1')"
                    xModel="subjectRequired"
                />
            </div>

            {{-- Postal code & Address --}}
            <x-form-toggle
                name="show_address"
                :label="__('dixlase-inquiry::admin/inquiry/settings/form-basic.show_postal_address')"
                :checked="old('show_address', $settings->show_address ?? false)"
                xModel="showAddress"
            />
            <div class="ml-4 pl-3 border-l-2 border-gray-200 dark:border-gray-700">
                <x-form-toggle
                    name="postal_code_required"
                    :label="__('dixlase-inquiry::admin/inquiry/settings/form-basic.postal_address_required')"
                    :checked="old('postal_code_required', $settings->postal_code_required ?? false)"
                    xBind="(showAddress === '1')"
                    xModel="postalCodeRequired"
                />
            </div>

            {{-- Phone --}}
            <x-form-toggle
                name="show_phone"
                :label="__('dixlase-inquiry::admin/inquiry/settings/form-basic.show_phone')"
                :checked="old('show_phone', $settings->show_phone ?? true)"
                xModel="showPhone"
            />
            <div class="ml-4 pl-3 border-l-2 border-gray-200 dark:border-gray-700">
                <x-form-toggle
                    name="phone_required"
                    :label="__('dixlase-inquiry::admin/inquiry/settings/form-basic.phone_required')"
                    :checked="old('phone_required', $settings->phone_required ?? false)"
                    xBind="(showPhone === '1')"
                    xModel="phoneRequired"
                />
            </div>

            {{-- Gender --}}
            <x-form-toggle
                name="show_gender"
                :label="__('dixlase-inquiry::admin/inquiry/settings/form-basic.show_gender')"
                :checked="old('show_gender', $settings->show_gender ?? false)"
                xModel="showGender"
            />
            <div class="ml-4 pl-3 border-l-2 border-gray-200 dark:border-gray-700">
                <x-form-toggle
                    name="gender_required"
                    :label="__('dixlase-inquiry::admin/inquiry/settings/form-basic.gender_required')"
                    :checked="old('gender_required', $settings->gender_required ?? false)"
                    xBind="(showGender === '1')"
                    xModel="genderRequired"
                />
            </div>

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

    {{-- Display settings section (hidden in simple mode) --}}
    @if($isSimpleMode)
        <input type="hidden" name="use_single_page" value="1">
        <input type="hidden" name="inquiry_url_slug" value="{{ old('inquiry_url_slug', $settings->inquiry_url_slug ?? 'inquiry') }}">
        <input type="hidden" name="show_confirmation_page" value="1">
    @else
        <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">
                {{ __('dixlase-inquiry::admin/inquiry/settings/form-basic.section_display_settings') }}
            </h3>

            <div class="space-y-4">
                <div>
                    <x-form-label :label="__('dixlase-inquiry::admin/inquiry/settings/form-basic.form_type')" />
                    <x-form-select
                        name="use_single_page"
                        :options="[
                            '1' => __('dixlase-inquiry::admin/inquiry/settings/form-basic.single_page'),
                            '0' => __('dixlase-inquiry::admin/inquiry/settings/form-basic.separate_pages'),
                        ]"
                        :value="old('use_single_page', $settings->use_single_page ?? true) ? '1' : '0'"
                        xModel="useSinglePage"
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
    @endif

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

    {{-- Throttle section (hidden in simple mode) --}}
    @if($isSimpleMode)
        <input type="hidden" name="throttle_enabled" value="1">
        <input type="hidden" name="throttle_max_attempts" value="3">
        <input type="hidden" name="throttle_decay_minutes" value="5">
    @else
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
    @endif

</x-admin.right-sidebar>
