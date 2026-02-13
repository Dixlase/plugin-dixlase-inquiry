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

@extends('layouts.admin')

@section('title', __('dixlase-inquiry::admin.settings.inquiry.heading'))

@section('content')
    <!-- メールサーバー設定の確認メッセージ -->
    @if(!($mailConnectionTested && $mailSendTested && $mailReceiveTested))
        <div class="mb-6">
            <x-ui-message
                type="warning"
                :message="__('dixlase-inquiry::admin.settings.mail_test_required', ['url' => route('admin.settings.base')])"
            />
        </div>
    @endif

    <form id="inquiry-settings-form" action="{{ route('dixlase-inquiry::admin.inquiry.settings.update') }}" method="POST" x-data="{
            nameOrderWestern: '{{ old('name_order_western', $settings->name_order_western ?? false) ? '1' : '0' }}',
            showSubject: {{ old('show_subject', $settings->show_subject ?? false) ? 'true' : 'false' }},
            subjectRequired: {{ old('subject_required', $settings->subject_required ?? false) ? 'true' : 'false' }},
            showPostalCode: {{ old('show_postal_code', $settings->show_postal_code ?? false) ? 'true' : 'false' }},
            postalCodeRequired: {{ old('postal_code_required', $settings->postal_code_required ?? false) ? 'true' : 'false' }},
            get showAddress() { return this.showPostalCode; },
            get addressRequired() { return this.postalCodeRequired; },
            showPhone: {{ old('show_phone', $settings->show_phone ?? true) ? 'true' : 'false' }},
            phoneRequired: {{ old('phone_required', $settings->phone_required ?? false) ? 'true' : 'false' }},
            showGender: {{ old('show_gender', $settings->show_gender ?? false) ? 'true' : 'false' }},
            genderRequired: {{ old('gender_required', $settings->gender_required ?? false) ? 'true' : 'false' }},
            useSinglePage: {{ old('use_single_page', $settings->use_single_page ?? true) ? 'true' : 'false' }},
            inquiryUrlSlug: '{{ old('inquiry_url_slug', $settings->inquiry_url_slug ?? 'inquiry') }}'
        }">
        @csrf

        <!-- フォームプレビュー -->
        <section>
            <h2 class="text-xl font-semibold mb-4">
                {{ __('dixlase-inquiry::admin.settings.display.form_preview') }}
            </h2>
             
            <div class="mt-6 p-4 bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-lg">
                @include('dixlase-inquiry::admin.inquiry.partials.form-preview')
            </div>
        </section>

        <!-- 1. フォーム項目設定 -->
        <section class="mb-8">
            <h2 class="text-xl font-semibold mb-4">{{ __('dixlase-inquiry::admin.settings.form_fields.title') }}</h2>
            
            <fieldset>
                <legend>{{ __('dixlase-inquiry::admin.settings.form_fields.format_style') }}</legend>
            
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                <!-- 日本式・欧米式の選択 -->
                <div class="lg:col-span-2">
                    <x-form-radio-card-group
                        name="name_order_western"
                        :options="[
                            [
                                'value' => '0',
                                'label' => __('dixlase-inquiry::admin.settings.form_fields.format_japanese'),
                                'description' => __('dixlase-inquiry::admin.settings.form_fields.format_japanese_desc'),
                                'icon' => 'fas fa-flag',
                            ],
                            [
                                'value' => '1',
                                'label' => __('dixlase-inquiry::admin.settings.form_fields.format_western'),
                                'description' => __('dixlase-inquiry::admin.settings.form_fields.format_western_desc'),
                                'icon' => 'fas fa-globe',
                            ],
                        ]"
                        :value="old('name_order_western', $settings->name_order_western ?? false) ? '1' : '0'"
                        xModel="nameOrderWestern"
                        :columns="2"
                        color="primary"
                    />
                    <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                        {{ __('dixlase-inquiry::admin.settings.form_fields.format_style_help') }}
                    </p>
                </div>
            </div>
            </fieldset>

            <fieldset class="mt-6">
                <legend>{{ __('dixlase-inquiry::admin.settings.form_fields.field_settings') }}</legend>

                <!-- 注釈: 必須フィールドの説明 -->
                <div class="mb-4 p-3 bg-gray-50 dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700">
                    <p class="text-sm text-gray-600 dark:text-gray-400">
                        <span class="font-medium">{{ __('dixlase-inquiry::admin.settings.form_fields.required_fields_note') }}</span>
                    </p>
                </div>
            
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                
                <!-- 題名フィールド -->
                <div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="show_subject" x-model="showSubject" {{ old('show_subject', $settings->show_subject ?? false) ? 'checked' : '' }} class="sr-only peer">
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none dark:bg-gray-600 rounded-full peer peer-checked:bg-indigo-600 transition-colors"></div>
                        <div class="absolute left-1 top-1 w-4 h-4 bg-white border border-gray-300 rounded-full transition-all peer-checked:translate-x-full peer-checked:border-white"></div>
                        <span class="ml-3 text-sm text-gray-700 dark:text-gray-300">{{ __('dixlase-inquiry::admin.settings.form_fields.show_subject') }}</span>
                    </label>
                </div>
                <div>
                    <x-form-toggle
                        name="subject_required"
                        :label="__('dixlase-inquiry::admin.settings.form_fields.subject_required')"
                        :checked="old('subject_required', $settings->subject_required ?? false)"
                        xBind="showSubject"
                        xModel="subjectRequired"
                    />
                </div>

                <!-- 郵便番号・住所フィールド -->
                <div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="show_postal_code" x-model="showPostalCode" {{ old('show_postal_code', $settings->show_postal_code ?? false) ? 'checked' : '' }} class="sr-only peer">
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none dark:bg-gray-600 rounded-full peer peer-checked:bg-indigo-600 transition-colors"></div>
                        <div class="absolute left-1 top-1 w-4 h-4 bg-white border border-gray-300 rounded-full transition-all peer-checked:translate-x-full peer-checked:border-white"></div>
                        <span class="ml-3 text-sm text-gray-700 dark:text-gray-300">{{ __('dixlase-inquiry::admin.settings.form_fields.show_postal_address') }}</span>
                    </label>
                    <input type="hidden" name="show_address" :value="showPostalCode ? '1' : '0'">
                </div>
                <div>
                    <x-form-toggle
                        name="postal_code_required"
                        :label="__('dixlase-inquiry::admin.settings.form_fields.postal_address_required')"
                        :checked="old('postal_code_required', $settings->postal_code_required ?? false)"
                        xBind="showPostalCode"
                        xModel="postalCodeRequired"
                    />
                    <input type="hidden" name="address_required" :value="postalCodeRequired ? '1' : '0'">
                </div>

                <!-- 電話番号フィールド -->
                <div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="show_phone" x-model="showPhone" {{ old('show_phone', $settings->show_phone ?? true) ? 'checked' : '' }} class="sr-only peer">
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none dark:bg-gray-600 rounded-full peer peer-checked:bg-indigo-600 transition-colors"></div>
                        <div class="absolute left-1 top-1 w-4 h-4 bg-white border border-gray-300 rounded-full transition-all peer-checked:translate-x-full peer-checked:border-white"></div>
                        <span class="ml-3 text-sm text-gray-700 dark:text-gray-300">{{ __('dixlase-inquiry::admin.settings.form_fields.show_phone') }}</span>
                    </label>
                </div>
                <div>
                    <x-form-toggle
                        name="phone_required"
                        :label="__('dixlase-inquiry::admin.settings.form_fields.phone_required')"
                        :checked="old('phone_required', $settings->phone_required ?? false)"
                        xBind="showPhone"
                        xModel="phoneRequired"
                    />
                </div>

                <!-- 性別フィールド -->
                <div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="show_gender" x-model="showGender" {{ old('show_gender', $settings->show_gender ?? false) ? 'checked' : '' }} class="sr-only peer">
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none dark:bg-gray-600 rounded-full peer peer-checked:bg-indigo-600 transition-colors"></div>
                        <div class="absolute left-1 top-1 w-4 h-4 bg-white border border-gray-300 rounded-full transition-all peer-checked:translate-x-full peer-checked:border-white"></div>
                        <span class="ml-3 text-sm text-gray-700 dark:text-gray-300">{{ __('dixlase-inquiry::admin.settings.form_fields.show_gender') }}</span>
                    </label>
                </div>
                <div>
                    <x-form-toggle
                        name="gender_required"
                        :label="__('dixlase-inquiry::admin.settings.form_fields.gender_required')"
                        :checked="old('gender_required', $settings->gender_required ?? false)"
                        xBind="showGender"
                        xModel="genderRequired"
                    />
                </div>
                </div>
            </fieldset>
        </section>


        <!-- 2. フォーム表示設定 -->
        <section class="mb-8">
            <h2 class="text-xl font-semibold mb-4">{{ __('dixlase-inquiry::admin.settings.display.title') }}</h2>
            
            <fieldset>
                <legend>{{ __('dixlase-inquiry::admin.settings.display.form_type') }}</legend>
            
            <div class="grid grid-cols-1 gap-6">
                <x-form-radio-group
                    name="use_single_page"
                    :options="[
                        '1' => __('dixlase-inquiry::admin.settings.display.single_page'),
                        '0' => __('dixlase-inquiry::admin.settings.display.separate_pages')
                    ]"
                    :value="old('use_single_page', $settings->use_single_page ?? true) ? '1' : '0'"
                    xModel="useSinglePage"
                />
                <p class="text-sm text-gray-600 dark:text-gray-400">
                    {{ __('dixlase-inquiry::admin.settings.display.single_page_help') }}
                </p>

                <!-- シングルページ選択時: ショートコード表示 -->
                <div x-show="useSinglePage == '1'" x-cloak>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">
                        {{ __('dixlase-inquiry::admin.settings.display.shortcode_label') }}
                    </label>
                    
                    <!-- 使用方法の説明 -->
                    <div class="mb-4 p-4 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg">
                        <div class="flex items-start">
                            <i class="fas fa-info-circle text-blue-600 dark:text-blue-400 mt-0.5 mr-2"></i>
                            <div class="flex-1">
                                <p class="text-sm text-blue-800 dark:text-blue-200 font-medium mb-1">
                                    {{ __('dixlase-inquiry::admin.settings.display.usage_instruction_title') }}
                                </p>
                                <p class="text-xs text-blue-700 dark:text-blue-300">
                                    {{ __('dixlase-inquiry::admin.settings.display.usage_instruction_text') }}
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Bladeディレクティブ（推奨） -->
                    <div class="mb-4 p-4 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg">
                        <div class="flex items-center mb-2">
                            <i class="fas fa-star text-yellow-500 mr-2"></i>
                            <p class="text-sm font-semibold text-gray-700 dark:text-gray-300">
                                {{ __('dixlase-inquiry::admin.settings.display.blade_directive') }}
                            </p>
                        </div>
                        <div class="bg-gray-100 dark:bg-gray-700 p-3 rounded-md mb-2">
                            <code class="text-sm text-gray-800 dark:text-gray-200">{{ '@' }}inquiry</code>
                        </div>
                        <p class="text-xs text-gray-600 dark:text-gray-400">
                            {{ __('dixlase-inquiry::admin.settings.display.blade_directive_help') }}
                        </p>
                    </div>
                    
                    <!-- ショートコード -->
                    <div class="p-4 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg">
                        <p class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                            {{ __('dixlase-inquiry::admin.settings.display.shortcode') }}
                        </p>
                        <div class="bg-gray-100 dark:bg-gray-700 p-3 rounded-md mb-2">
                            <code class="text-sm text-gray-800 dark:text-gray-200">[inquiry]</code>
                        </div>
                        <p class="text-xs text-gray-600 dark:text-gray-400">
                            {{ __('dixlase-inquiry::admin.settings.display.shortcode_help') }}
                        </p>
                    </div>
                    
                    
                </div>

                <!-- 別ページ選択時: URL編集フィールド -->
                <div x-show="useSinglePage == '0'" x-cloak>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        {{ __('dixlase-inquiry::admin.settings.display.inquiry_url') }}
                    </label>
                    <div class="flex items-center gap-2">
                        <span class="text-gray-600 dark:text-gray-400">{{ url('/') }}/</span>
                        <div class="flex-1">
                            <input type="text"
                                   name="inquiry_url_slug"
                                   x-model="inquiryUrlSlug"
                                   placeholder="inquiry"
                                   class="block w-full px-3 py-2 bg-gray-50 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm dark:bg-gray-800 dark:border-gray-500 dark:focus:border-indigo-500 dark:focus:ring-indigo-500 dark:text-white">
                        </div>
                    </div>
                    <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                        {{ __('dixlase-inquiry::admin.settings.display.inquiry_url_slug_help') }}
                    </p>
                    
                    <!-- プレビューボタン -->
                    <div class="mt-4">
                        <a :href="'{{ url('/') }}/' + inquiryUrlSlug" 
                           target="_blank"
                           class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-md shadow-sm transition-colors duration-150">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                            {{ __('common.preview') }}
                        </a>
                        <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">
                            {{ __('dixlase-inquiry::admin.settings.display.preview_page_help') }}
                        </p>
                    </div>
                </div>

                <x-form-toggle
                    name="show_confirmation_page"
                    :label="__('dixlase-inquiry::admin.settings.display.show_confirmation')"
                    :checked="old('show_confirmation_page', $settings->show_confirmation_page ?? true)"
                />
                <p class="text-sm text-gray-600 dark:text-gray-400">
                    {{ __('dixlase-inquiry::admin.settings.display.show_confirmation_help') }}
                </p>
            </div>
            </fieldset>
        </section>

        
        

        

        <!-- 3. 完了ページ設定 -->
        <section class="mb-8">
            <h2 class="text-xl font-semibold mb-4">{{ __('dixlase-inquiry::admin.settings.completion.title') }}</h2>
            
            <fieldset>
                <legend>{{ __('dixlase-inquiry::admin.settings.completion.title_text') }}</legend>
                <x-form-text
                    name="completion_title"
                    :value="old('completion_title', $settings->completion_title ?? '送信完了')"
                />
                <p class="text-sm text-gray-600 dark:text-gray-400">
                    {{ __('dixlase-inquiry::admin.settings.completion.title_help') }}
                </p>
            </fieldset>

            <fieldset>
                <legend>{{ __('dixlase-inquiry::admin.settings.completion.message') }}</legend>
                <x-form-textarea
                    name="completion_message"
                    :value="old('completion_message', $settings->completion_message ?? 'お問い合わせありがとうございました。<br>内容を確認の上、担当者よりご連絡させていただきます。')"
                    :rows="4"
                />
                <p class="text-sm text-gray-600 dark:text-gray-400">
                    {{ __('dixlase-inquiry::admin.settings.completion.message_help') }}
                </p>
            </fieldset>
        </section>

        <!-- 4. 管理者通知設定 -->
        <section class="mb-8">
            <h2 class="text-xl font-semibold mb-4">{{ __('dixlase-inquiry::admin.settings.admin_notification.title') }}</h2>
            
            <fieldset>
                <legend>{{ __('dixlase-inquiry::admin.settings.admin_notification.admin_email') }}</legend>
                <x-form-text
                    name="admin_email"
                    :value="old('admin_email', $settings->admin_email ?? '')"
                    :required="true"
                />
                <p class="text-sm text-gray-600 dark:text-gray-400">
                    {{ __('dixlase-inquiry::admin.settings.admin_notification.admin_email_help') }}
                </p>
            </fieldset>

            <fieldset>
                <legend>{{ __('dixlase-inquiry::admin.settings.admin_notification.subject') }}</legend>
                <x-form-text
                    name="subject"
                    :value="old('subject', $settings->subject ?? 'お問い合わせありがとうございます')"
                />
            </fieldset>

            <fieldset>
                <legend>{{ __('dixlase-inquiry::admin.settings.admin_notification.body') }}</legend>
                <x-form-textarea
                    name="body"
                    :value="old('body', $settings->body ?? '')"
                    :rows="8"
                />
                <p class="text-sm text-gray-600 dark:text-gray-400">
                    {{ __('dixlase-inquiry::admin.settings.admin_notification.body_help') }}
                </p>
            </fieldset>
        </section>

        <!-- 5. 自動返信設定 -->
        <section class="mb-8" x-data="{ autoReplyEnabled: {{ old('auto_reply_enabled', $settings->auto_reply_enabled ?? true) ? 'true' : 'false' }} }">
            <h2 class="text-xl font-semibold mb-4">{{ __('dixlase-inquiry::admin.settings.auto_reply.title') }}</h2>
            
            <fieldset>
                <div class="grid grid-cols-1 gap-6">
                    <div class="flex items-center space-x-3">
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox"
                                   name="auto_reply_enabled"
                                   x-model="autoReplyEnabled"
                                   {{ old('auto_reply_enabled', $settings->auto_reply_enabled ?? true) ? 'checked' : '' }}
                                   class="sr-only peer">
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none dark:bg-gray-600 rounded-full peer peer-checked:bg-indigo-600 transition-colors"></div>
                            <div class="absolute left-1 top-1 w-4 h-4 bg-white border border-gray-300 rounded-full transition-all peer-checked:translate-x-full peer-checked:border-white"></div>
                        </label>
                        <span class="text-sm text-gray-700 dark:text-gray-300">{{ __('dixlase-inquiry::admin.settings.auto_reply.enabled') }}</span>
                    </div>

                    <div x-show="autoReplyEnabled" x-cloak class="space-y-6">
                            <fieldset>
                                <legend>{{ __('dixlase-inquiry::admin.settings.auto_reply.from_email') }}</legend>
                                <x-form-text
                                    name="auto_reply_from_email"
                                    :value="old('auto_reply_from_email', $settings->auto_reply_from_email ?? '')"
                                />
                                <p class="text-sm text-gray-600 dark:text-gray-400">
                                    {{ __('dixlase-inquiry::admin.settings.auto_reply.from_email_help') }}
                                </p>
                            </fieldset>

                            <fieldset>
                                <legend>{{ __('dixlase-inquiry::admin.settings.auto_reply.subject') }}</legend>
                                <x-form-text
                                    name="auto_reply_subject"
                                    :value="old('auto_reply_subject', $settings->auto_reply_subject ?? 'お問い合わせを受け付けました')"
                                />
                            </fieldset>

                            <fieldset>
                                <legend>{{ __('dixlase-inquiry::admin.settings.auto_reply.body') }}</legend>
                                <x-form-textarea
                                    name="auto_reply_body"
                                    :value="old('auto_reply_body', $settings->auto_reply_body ?? '')"
                                    :rows="8"
                                />
                                <p class="text-sm text-gray-600 dark:text-gray-400">
                                    {{ __('dixlase-inquiry::admin.settings.auto_reply.body_help') }}
                                </p>
                            </fieldset>
                        </div>
                </div>
            </fieldset>
        </section>

        <!-- 6. セキュリティ設定 -->
        <section class="mb-8">
            <h2 class="text-xl font-semibold mb-4">{{ __('dixlase-inquiry::admin.settings.security.title') }}</h2>
            
            <!-- CAPTCHA設定の確認メッセージ -->
            @if(!($captchaEnabled && !empty($captchaDriver) && $captchaAuthenticated))
                <div class="mb-4">
                    <x-ui-message
                        type="warning"
                        :message="__('dixlase-inquiry::admin.settings.captcha_test_required', ['url' => route('admin.settings.security.captcha')])"
                    />
                </div>
            @endif
            
            <fieldset>
                <legend>{{ __('dixlase-inquiry::admin.settings.security.use_recaptcha') }}</legend>
                
                <div class="grid grid-cols-1 gap-6">
                    <x-form-toggle
                        name="use_recaptcha"
                        :label="__('dixlase-inquiry::admin.settings.security.use_recaptcha')"
                        :checked="old('use_recaptcha', $settings->use_recaptcha ?? false)"
                    />
                    
                    <!-- ヘルプテキスト -->
                    <div class="text-sm text-gray-600 dark:text-gray-400">
                        {{ __('dixlase-inquiry::admin.settings.security.recaptcha_help') }}
                    </div>
                </div>
            </fieldset>
        </section>

    </form>
@endsection

@section('save')
    <x-admin.save-button
        id_confirmation="confirmInquirySettingsModal"
        :label="__('common.save')"
        :title="__('dixlase-inquiry::admin.settings.confirm_title')"
        :message="__('dixlase-inquiry::admin.settings.confirm_message')"
        :confirm_label="__('common.save')"
        :cancel_label="__('common.cancel')"
        form="inquiry-settings-form"
    />
@endsection

