{{--
This file is part of DixlaseInquiry.

Copyright (C) 2025 exc-D inc.
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

@extends('admin::partials.layout')

@section('content')
    <form id="inquiry-settings-form" action="{{ route('admin.dixlase-inquiry::admin.inquiries.settings.update') }}" method="POST">
        @csrf
        
        <!-- 基本設定 -->
        <section class="mb-8">
            <h2 class="text-xl font-semibold mb-4">{{ __('dixlase-inquiry::inquiry.admin.settings.basic.title') }}</h2>
            
            <fieldset>
                <legend>{{ __('dixlase-inquiry::inquiry.admin.settings.basic.admin_email') }}</legend>
                @include('components::form.text', [
                    'name' => 'admin_email',
                    'label' => __('dixlase-inquiry::inquiry.admin.settings.basic.admin_email'),
                    'value' => old('admin_email', $settings->admin_email ?? ''),
                    'required' => true,
                    'help' => __('dixlase-inquiry::inquiry.admin.settings.basic.admin_email_help')
                ])
            </fieldset>
        </section>


        <!-- フォーム表示設定 -->
        <section class="mb-8">
            <h2 class="text-xl font-semibold mb-4">{{ __('dixlase-inquiry::inquiry.admin.settings.display.title') }}</h2>
            
            <fieldset>
                <legend>{{ __('dixlase-inquiry::inquiry.admin.settings.display.form_type') }}</legend>
            
            <div class="grid grid-cols-1 gap-6">
                @include('components::form.radio-group', [
                    'name' => 'use_single_page',
                    'label' => 'フォーム表示方式',
                    'options' => [
                        '1' => 'シングルページ（動的に確認画面・完了画面を表示）',
                        '0' => '別ページ（入力画面・確認画面・完了画面を別々のページで表示）'
                    ],
                    'value' => old('use_single_page', $settings->use_single_page ?? true) ? '1' : '0',
                    'help' => 'シングルページ方式では、1つのページ内で入力から完了まで処理されます。'
                ])

                @include('components::form.checkbox', [
                    'name' => 'show_confirmation_page',
                    'label' => '確認画面を表示する',
                    'checked' => old('show_confirmation_page', $settings->show_confirmation_page ?? true),
                    'help' => 'チェックを外すと、入力後すぐに送信されます。'
                ])
            </div>
            </fieldset>
        </section>
        
        <!-- フォーム項目設定 -->
        <section class="mb-8">
            <h2 class="text-xl font-semibold mb-4">{{ __('dixlase-inquiry::inquiry.admin.settings.form_fields.title') }}</h2>
            
            <fieldset>
                <legend>{{ __('dixlase-inquiry::inquiry.admin.settings.form_fields.name_order') }}</legend>
            
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

                <!-- 題名 -->
                <div>
                    @include('components::form.checkbox', [
                        'name' => 'show_subject',
                        'label' => '題名フィールドを表示',
                        'checked' => old('show_subject', $settings->show_subject ?? true)
                    ])
                </div>
                <div>
                    @include('components::form.checkbox', [
                        'name' => 'subject_required',
                        'label' => '題名を必須にする',
                        'checked' => old('subject_required', $settings->subject_required ?? false)
                    ])
                </div>

                <!-- 郵便番号 -->
                <div>
                    @include('components::form.checkbox', [
                        'name' => 'show_postal_code',
                        'label' => '郵便番号フィールドを表示',
                        'checked' => old('show_postal_code', $settings->show_postal_code ?? true)
                    ])
                </div>
                <div>
                    @include('components::form.checkbox', [
                        'name' => 'postal_code_required',
                        'label' => '郵便番号を必須にする',
                        'checked' => old('postal_code_required', $settings->postal_code_required ?? false)
                    ])
                </div>

                <!-- 住所 -->
                <div>
                    @include('components::form.checkbox', [
                        'name' => 'show_address',
                        'label' => '住所フィールドを表示',
                        'checked' => old('show_address', $settings->show_address ?? true)
                    ])
                </div>
                <div>
                    @include('components::form.checkbox', [
                        'name' => 'address_required',
                        'label' => '住所を必須にする',
                        'checked' => old('address_required', $settings->address_required ?? false)
                    ])
                </div>

                <!-- 電話番号 -->
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
            <h2 class="text-xl font-semibold mb-4">{{ __('dixlase-inquiry::inquiry.admin.settings.completion.title') }}</h2>
            
            <fieldset>
                <legend>{{ __('dixlase-inquiry::inquiry.admin.settings.completion.title_text') }}</legend>
                @include('components::form.text', [
                    'name' => 'completion_title',
                    'label' => __('dixlase-inquiry::inquiry.admin.settings.completion.title_text'),
                    'value' => old('completion_title', $settings->completion_title ?? '送信完了'),
                    'help' => __('dixlase-inquiry::inquiry.admin.settings.completion.title_help')
                ])
            </fieldset>

            <fieldset>
                <legend>{{ __('dixlase-inquiry::inquiry.admin.settings.completion.message') }}</legend>
                @include('components::form.textarea', [
                    'name' => 'completion_message',
                    'label' => __('dixlase-inquiry::inquiry.admin.settings.completion.message'),
                    'value' => old('completion_message', $settings->completion_message ?? 'お問い合わせありがとうございました。<br>内容を確認の上、担当者よりご連絡させていただきます。'),
                    'rows' => 4,
                    'help' => __('dixlase-inquiry::inquiry.admin.settings.completion.message_help')
                ])
            </fieldset>
        </section>

        <!-- 自動返信設定 -->
        <section class="mb-8">
            <h2 class="text-xl font-semibold mb-4">{{ __('dixlase-inquiry::inquiry.admin.settings.auto_reply.title') }}</h2>
            
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
                                <legend>{{ __('dixlase-inquiry::inquiry.admin.settings.auto_reply.from_email') }}</legend>
                                @include('components::form.text', [
                                    'name' => 'auto_reply_from_email',
                                    'label' => '自動返信の送信元メールアドレス',
                                    'value' => old('auto_reply_from_email', $settings->auto_reply_from_email ?? ''),
                                    'help' => '空の場合は、システムのデフォルト送信元アドレスが使用されます。'
                                ])
                            </fieldset>

                            <fieldset>
                                <legend>{{ __('dixlase-inquiry::inquiry.admin.settings.auto_reply.subject') }}</legend>
                                @include('components::form.text', [
                                    'name' => 'auto_reply_subject',
                                    'label' => '自動返信の件名',
                                    'value' => old('auto_reply_subject', $settings->auto_reply_subject ?? 'お問い合わせを受け付けました'),
                                ])
                            </fieldset>

                            <fieldset>
                                <legend>{{ __('dixlase-inquiry::inquiry.admin.settings.auto_reply.body') }}</legend>
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
            <h2 class="text-xl font-semibold mb-4">{{ __('dixlase-inquiry::inquiry.admin.settings.admin_notification.title') }}</h2>
            
            <fieldset>
                <legend>{{ __('dixlase-inquiry::inquiry.admin.settings.admin_notification.subject') }}</legend>
                @include('components::form.text', [
                    'name' => 'subject',
                    'label' => '管理者通知の件名',
                    'value' => old('subject', $settings->subject ?? 'お問い合わせありがとうございます'),
                ])
            </fieldset>

            <fieldset>
                <legend>{{ __('dixlase-inquiry::inquiry.admin.settings.admin_notification.body') }}</legend>
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
            <h2 class="text-xl font-semibold mb-4">{{ __('dixlase-inquiry::inquiry.admin.settings.security.title') }}</h2>
            
            <fieldset>
                <legend>{{ __('dixlase-inquiry::inquiry.admin.settings.security.use_recaptcha') }}</legend>
                
                <div class="grid grid-cols-1 gap-6">
                    @include('components::form.checkbox', [
                        'name' => 'use_recaptcha',
                        'label' => __('dixlase-inquiry::inquiry.admin.settings.security.use_recaptcha'),
                        'checked' => old('use_recaptcha', $settings->use_recaptcha ?? false),
                        'help' => __('dixlase-inquiry::inquiry.admin.settings.security.recaptcha_help')
                    ])
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
        'title' => __('dixlase-inquiry::inquiry.admin.settings.confirm_title'),
        'message' => __('dixlase-inquiry::inquiry.admin.settings.confirm_message'),
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

// チェックボックスの変更を監視
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
