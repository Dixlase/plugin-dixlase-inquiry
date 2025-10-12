{{--
This file is part of DixlaseInquiry.

Copyright (C) 2025 exc-D inc.
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

@extends('admin::partials.layout')

@section('title', __('dixlase-inquiry::admin.settings.inquiry.heading'))

@section('content')
    <!-- メールサーバー設定の確認メッセージ -->
    @if(!($mailConnectionTested && $mailSendTested && $mailReceiveTested))
        <div class="mb-6">
            @include('components.message', [
                'type' => 'warning',
                'message' => __('dixlase-inquiry::admin.settings.mail_test_required', ['url' => route('admin.settings.base')])
            ])
        </div>
    @endif

    <form id="inquiry-settings-form" action="{{ route('admin.dixlase-inquiry::admin.settings.inquiry.update') }}" method="POST">
        @csrf
        
        <!-- 基本設定 -->
        <section class="mb-8">
            <h2 class="text-xl font-semibold mb-4">{{ __('dixlase-inquiry::admin.settings.basic.title') }}</h2>
            
            <fieldset>
                <legend>{{ __('dixlase-inquiry::admin.settings.basic.admin_email') }}</legend>
                @include('components::form.text', [
                    'name' => 'admin_email',
                    'label' => __('dixlase-inquiry::admin.settings.basic.admin_email'),
                    'value' => old('admin_email', $settings->admin_email ?? ''),
                    'required' => true,
                    'help' => __('dixlase-inquiry::admin.settings.basic.admin_email_help')
                ])
            </fieldset>
        </section>


        <!-- フォーム表示設定 -->
        <section class="mb-8" x-data="{ 
            useSinglePage: {{ old('use_single_page', $settings->use_single_page ?? true) ? 'true' : 'false' }},
            inquiryUrlSlug: '{{ old('inquiry_url_slug', $settings->inquiry_url_slug ?? 'inquiry') }}'
        }">
            <h2 class="text-xl font-semibold mb-4">{{ __('dixlase-inquiry::admin.settings.display.title') }}</h2>
            
            <fieldset>
                <legend>{{ __('dixlase-inquiry::admin.settings.display.form_type') }}</legend>
            
            <div class="grid grid-cols-1 gap-6">
                @include('components::form.radio-group', [
                    'name' => 'use_single_page',
                    'label' => __('dixlase-inquiry::admin.settings.display.use_single_page'),
                    'options' => [
                        '1' => __('dixlase-inquiry::admin.settings.display.single_page'),
                        '0' => __('dixlase-inquiry::admin.settings.display.separate_pages')
                    ],
                    'value' => old('use_single_page', $settings->use_single_page ?? true) ? '1' : '0',
                    'help' => __('dixlase-inquiry::admin.settings.display.single_page_help'),
                    'xModel' => 'useSinglePage'
                ])

                <!-- シングルページ選択時: ショートコード表示 -->
                <div x-show="useSinglePage == '1'" x-cloak>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        {{ __('dixlase-inquiry::admin.settings.display.shortcode_label') }}
                    </label>
                    <div class="bg-gray-100 dark:bg-gray-700 p-4 rounded-md">
                        <code class="text-sm text-gray-800 dark:text-gray-200">[inquiry]</code>
                    </div>
                    <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                        {{ __('dixlase-inquiry::admin.settings.display.shortcode_help') }}
                    </p>
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

                @include('components::form.checkbox', [
                    'name' => 'show_confirmation_page',
                    'label' => __('dixlase-inquiry::admin.settings.display.show_confirmation'),
                    'checked' => old('show_confirmation_page', $settings->show_confirmation_page ?? true),
                    'help' => __('dixlase-inquiry::admin.settings.display.show_confirmation_help')
                ])
            </div>
            </fieldset>
        </section>
        
        <!-- フォーム項目設定 -->
        <section class="mb-8">
            <h2 class="text-xl font-semibold mb-4">{{ __('dixlase-inquiry::admin.settings.form_fields.title') }}</h2>
            
            <fieldset>
                <legend>{{ __('dixlase-inquiry::admin.settings.form_fields.name_order') }}</legend>
            
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- 名前の順序 -->
                <div class="lg:col-span-2">
                    @include('components::form.radio-group', [
                        'name' => 'name_order_western',
                        'label' => '名前の表示順序',
                        'options' => [
                            '0' => '日本式（姓・名）',
                            '1' => '欧米式（名・姓）'
                        ],
                        'value' => old('name_order_western', $settings->name_order_western ?? false) ? '1' : '0',
                        'help' => '英語版では自動的に欧米式（名・姓）の順序になります。'
                    ])
                </div>

                <!-- 題名フィールド -->
                <div>
                    @include('components::form.checkbox', [
                        'name' => 'show_subject',
                        'label' => '題名フィールドを表示',
                        'checked' => old('show_subject', $settings->show_subject ?? false)
                    ])
                </div>
                <div>
                    @include('components::form.checkbox', [
                        'name' => 'subject_required',
                        'label' => '題名を必須にする',
                        'checked' => old('subject_required', $settings->subject_required ?? false)
                    ])
                </div>

                <!-- 郵便番号フィールド -->
                <div>
                    @include('components::form.checkbox', [
                        'name' => 'show_postal_code',
                        'label' => '郵便番号フィールドを表示',
                        'checked' => old('show_postal_code', $settings->show_postal_code ?? false)
                    ])
                </div>
                <div>
                    @include('components::form.checkbox', [
                        'name' => 'postal_code_required',
                        'label' => '郵便番号を必須にする',
                        'checked' => old('postal_code_required', $settings->postal_code_required ?? false)
                    ])
                </div>

                <!-- 住所フィールド -->
                <div>
                    @include('components::form.checkbox', [
                        'name' => 'show_address',
                        'label' => '住所フィールドを表示',
                        'checked' => old('show_address', $settings->show_address ?? false)
                    ])
                </div>
                <div>
                    @include('components::form.checkbox', [
                        'name' => 'address_required',
                        'label' => '住所を必須にする',
                        'checked' => old('address_required', $settings->address_required ?? false)
                    ])
                </div>

                <!-- 電話番号フィールド -->
                <div>
                    @include('components::form.checkbox', [
                        'name' => 'show_phone',
                        'label' => '電話番号フィールドを表示',
                        'checked' => old('show_phone', $settings->show_phone ?? true)
                    ])
                </div>
                <div>
                    @include('components::form.checkbox', [
                        'name' => 'phone_required',
                        'label' => '電話番号を必須にする',
                        'checked' => old('phone_required', $settings->phone_required ?? false)
                    ])
                </div>
                </div>
            </fieldset>
        </section>

        

        <!-- 完了ページ設定 -->
        <section class="mb-8">
            <h2 class="text-xl font-semibold mb-4">{{ __('dixlase-inquiry::admin.settings.completion.title') }}</h2>
            
            <fieldset>
                <legend>{{ __('dixlase-inquiry::admin.settings.completion.title_text') }}</legend>
                @include('components::form.text', [
                    'name' => 'completion_title',
                    'label' => __('dixlase-inquiry::admin.settings.completion.title_text'),
                    'value' => old('completion_title', $settings->completion_title ?? '送信完了'),
                    'help' => __('dixlase-inquiry::admin.settings.completion.title_help')
                ])
            </fieldset>

            <fieldset>
                <legend>{{ __('dixlase-inquiry::admin.settings.completion.message') }}</legend>
                @include('components::form.textarea', [
                    'name' => 'completion_message',
                    'label' => __('dixlase-inquiry::admin.settings.completion.message'),
                    'value' => old('completion_message', $settings->completion_message ?? 'お問い合わせありがとうございました。<br>内容を確認の上、担当者よりご連絡させていただきます。'),
                    'rows' => 4,
                    'help' => __('dixlase-inquiry::admin.settings.completion.message_help')
                ])
            </fieldset>
        </section>

        <!-- 自動返信設定 -->
        <section class="mb-8">
            <h2 class="text-xl font-semibold mb-4">{{ __('dixlase-inquiry::admin.settings.auto_reply.title') }}</h2>
            
            <fieldset>

                <div class="grid grid-cols-1 gap-6">
                    @include('components::form.checkbox', [
                        'name' => 'auto_reply_enabled',
                        'label' => '自動返信を有効にする',
                        'checked' => old('auto_reply_enabled', $settings->auto_reply_enabled ?? true)
                    ])

                    <div x-data="{ autoReplyEnabled: {{ old('auto_reply_enabled', $settings->auto_reply_enabled ?? true) ? 'true' : 'false' }} }">
                        <div x-show="autoReplyEnabled" class="space-y-6">
                            <fieldset>
                                <legend>{{ __('dixlase-inquiry::admin.settings.auto_reply.from_email') }}</legend>
                                @include('components::form.text', [
                                    'name' => 'auto_reply_from_email',
                                    'label' => '自動返信の送信元メールアドレス',
                                    'value' => old('auto_reply_from_email', $settings->auto_reply_from_email ?? ''),
                                    'help' => '空の場合は、システムのデフォルト送信元アドレスが使用されます。'
                                ])
                            </fieldset>

                            <fieldset>
                                <legend>{{ __('dixlase-inquiry::admin.settings.auto_reply.subject') }}</legend>
                                @include('components::form.text', [
                                    'name' => 'auto_reply_subject',
                                    'label' => '自動返信の件名',
                                    'value' => old('auto_reply_subject', $settings->auto_reply_subject ?? 'お問い合わせを受け付けました'),
                                ])
                            </fieldset>

                            <fieldset>
                                <legend>{{ __('dixlase-inquiry::admin.settings.auto_reply.body') }}</legend>
                                @include('components::form.textarea', [
                                    'name' => 'auto_reply_body',
                                    'label' => '自動返信の本文',
                                    'value' => old('auto_reply_body', $settings->auto_reply_body ?? ''),
                                    'rows' => 8,
                                    'help' => '使用可能な変数: {{name}}, {{email}}, {{subject}}, {{postal_code}}, {{address}}, {{phone}}, {{message}}'
                                ])
                            </fieldset>
                        </div>
                    </div>
                </div>
            </fieldset>
        </section>

        <!-- 管理者通知設定 -->
        <section class="mb-8">
            <h2 class="text-xl font-semibold mb-4">{{ __('dixlase-inquiry::admin.settings.admin_notification.title') }}</h2>
            
            <fieldset>
                <legend>{{ __('dixlase-inquiry::admin.settings.admin_notification.subject') }}</legend>
                @include('components::form.text', [
                    'name' => 'subject',
                    'label' => '管理者通知の件名',
                    'value' => old('subject', $settings->subject ?? 'お問い合わせありがとうございます'),
                ])
            </fieldset>

            <fieldset>
                <legend>{{ __('dixlase-inquiry::admin.settings.admin_notification.body') }}</legend>
                @include('components::form.textarea', [
                    'name' => 'body',
                    'label' => '管理者通知の本文',
                    'value' => old('body', $settings->body ?? ''),
                    'rows' => 8,
                    'help' => '使用可能な変数: {{name}}, {{email}}, {{subject}}, {{postal_code}}, {{address}}, {{phone}}, {{message}}'
                ])
            </fieldset>
        </section>

        

        <!-- セキュリティ設定 -->
        <section class="mb-8">
            <h2 class="text-xl font-semibold mb-4">{{ __('dixlase-inquiry::admin.settings.security.title') }}</h2>
            
            <!-- CAPTCHA設定の確認メッセージ -->
            @if(!($captchaEnabled && !empty($captchaDriver) && $captchaAuthenticated))
                <div class="mb-4">
                    @include('components.message', [
                        'type' => 'warning',
                        'message' => __('dixlase-inquiry::admin.settings.captcha_test_required', ['url' => route('admin.settings.security')])
                    ])
                </div>
            @endif
            
            <fieldset>
                <legend>{{ __('dixlase-inquiry::admin.settings.security.use_recaptcha') }}</legend>
                
                <div class="grid grid-cols-1 gap-6">
                    @include('components::form.checkbox', [
                        'name' => 'use_recaptcha',
                        'label' => __('dixlase-inquiry::admin.settings.security.use_recaptcha'),
                        'checked' => old('use_recaptcha', $settings->use_recaptcha ?? false)
                    ])
                    
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
    @include('components.save', [
        'id' => 'confirmationModal',
        'label' => __('common.save'),
        'onclick' => "openModal('confirmInquirySettingsModal')",
        'title' => __('dixlase-inquiry::admin.settings.confirm_title'),
        'message' => __('dixlase-inquiry::admin.settings.confirm_message'),
        'confirm_label' => __('common.save'),
        'cancel_label' => __('common.cancel'),
        'form' => 'inquiry-settings-form',
    ])
@endsection

@push('scripts')
<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('inquirySettings', () => ({
        autoReplyEnabled: {{ old('auto_reply_enabled', $settings->auto_reply_enabled ?? true) ? 'true' : 'false' }},
        init() {
            // 自動返信チェックボックスの変更を監視
            this.$watch('autoReplyEnabled', (value) => {
                const checkbox = document.querySelector('input[name="auto_reply_enabled"]');
                if (checkbox) {
                    checkbox.checked = value;
                }
            });
        }
    }));
});

// 自動返信チェックボックスの変更イベントを監視
document.addEventListener('DOMContentLoaded', function() {
    const autoReplyCheckbox = document.querySelector('input[name="auto_reply_enabled"]');
    if (autoReplyCheckbox) {
        autoReplyCheckbox.addEventListener('change', function() {
            // Alpine.jsのデータを更新
            const component = document.querySelector('[x-data*="autoReplyEnabled"]');
            if (component && component._x_dataStack) {
                component._x_dataStack[0].autoReplyEnabled = this.checked;
            }
        });
    }
});
</script>
@endpush
