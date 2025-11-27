<?php

/**
 * This file is part of DixlaseInquiry.
 *
 * Copyright (C) 2025 exc-D inc.
 * https://exc-d.com
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <https://www.gnu.org/licenses/>.
 */

return [
    'plugin' => [
        'name' => 'Dixlase お問い合わせフォーム',
        'description' => 'ウェブサイトにお問い合わせフォーム機能を追加します。カスタマイズ可能なフォーム項目、自動返信、管理者通知、reCAPTCHA対応など包括的な機能を提供します。',
    ],
    
    'nav' => [
        'settings' => [
            'inquiry' => 'お問い合わせ設定',
        ],
    ],
    
    'form' => [
        'title' => 'お問い合わせ',
        'fields' => [
            'last_name' => '姓',
            'first_name' => '名',
            'email' => 'メールアドレス',
            'subject' => '題名',
            'postal_code' => '郵便番号',
            'address' => '住所',
            'phone' => '電話番号',
            'message' => 'お問い合わせ内容',
        ],
        'placeholders' => [
            'last_name' => '山田',
            'first_name' => '太郎',
            'email' => 'example@example.com',
            'subject' => 'お問い合わせの件について',
            'postal_code' => '123-4567',
            'address' => '東京都渋谷区...',
            'phone' => '03-1234-5678',
            'message' => 'お問い合わせ内容をご記入ください',
        ],
        'buttons' => [
            'confirm' => '確認する',
            'back' => '戻る',
            'send' => '送信する',
        ],
    ],
    'confirmation' => [
        'title' => '入力内容の確認',
        'message' => '以下の内容でお間違いありませんか？',
    ],
    'complete' => [
        'title' => '送信完了',
        'message' => 'お問い合わせありがとうございました。<br>内容を確認の上、担当者よりご連絡させていただきます。',
    ],
    'validation' => [
        'required' => ':attributeは必須項目です。',
        'email' => ':attributeは有効なメールアドレスを入力してください。',
        'admin_email_required' => '管理者メールアドレスは必須です。',
        'admin_email_invalid' => '有効なメールアドレスを入力してください。',
        'auto_reply_from_email.email' => '自動返信の送信元メールアドレスが無効です。',
        'inquiry_url_slug_required' => 'URLスラッグは必須です。',
        'inquiry_url_slug_format' => 'URLスラッグは半角英数字とハイフン(-)のみ使用できます。',
        'last_name_required' => '姓は必須です。',
        'first_name_required' => '名は必須です。',
        'email_required' => 'メールアドレスは必須です。',
        'email_invalid' => '有効なメールアドレスを入力してください。',
        'message_required' => 'お問い合わせ内容は必須です。',
        'subject_required' => '題名は必須です。',
        'postal_code_required' => '郵便番号は必須です。',
        'address_required' => '住所は必須です。',
        'phone_required' => '電話番号は必須です。',
        'recaptcha_required' => 'reCAPTCHA認証が必要です。',
    ],
    'settings' => [
            'inquiry' => [
                'heading' => 'お問い合わせ設定',
            ],
            'title' => '問い合わせフォーム設定',
            'basic' => [
                'title' => '基本設定',
                'admin_email' => '送信先メールアドレス',
                'admin_email_help' => '問い合わせ内容が送信されるメールアドレスを入力してください。',
            ],
            'form_fields' => [
                'title' => 'フォーム項目設定',
                'show_name' => '名前フィールドを表示',
                'name_required' => '名前を必須にする',
                'show_email' => 'メールアドレスフィールドを表示',
                'email_required' => 'メールアドレスを必須にする',
                'name_order' => '名前の表示順序',
                'name_order_japanese' => '日本式（姓・名）',
                'name_order_western' => '欧米式（名・姓）',
                'name_order_help' => '英語版では自動的に欧米式（名・姓）の順序になります。',
                'show_subject' => '題名フィールドを表示',
                'subject_required' => '題名を必須にする',
                'show_postal_code' => '郵便番号フィールドを表示',
                'postal_code_required' => '郵便番号を必須にする',
                'show_address' => '住所フィールドを表示',
                'address_required' => '住所を必須にする',
                'show_phone' => '電話番号フィールドを表示',
                'phone_required' => '電話番号を必須にする',
            ],
            'display' => [
                'title' => 'フォーム表示設定',
                'form_type' => 'フォーム表示方式',
                'use_single_page' => 'フォーム表示方式',
                'single_page' => 'シングルページ（動的に確認画面・完了画面を表示）',
                'separate_pages' => '別ページ（入力画面・確認画面・完了画面を別々のページで表示）',
                'single_page_help' => 'シングルページ方式では、1つのページ内で入力から完了まで処理されます。',
                'shortcode_label' => 'ショートコード',
                'shortcode_help' => 'このショートコードをページやテーマに貼り付けて使用してください。',
                'inquiry_url' => '問い合わせページURL',
                'inquiry_url_slug' => 'URLスラッグ',
                'inquiry_url_slug_help' => '問い合わせページのURLを設定します（例: inquiry → /inquiry）。半角英数字とハイフンのみ使用可能です。',
                'preview_page_help' => '新しいタブで問い合わせページを開きます。設定を保存する前にプレビューできます。',
                'show_confirmation' => '確認画面を表示する',
                'show_confirmation_help' => 'チェックを外すと、入力後すぐに送信されます。',
            ],
            'auto_reply' => [
                'title' => '自動返信設定',
                'enabled' => '自動返信を有効にする',
                'from_email' => '自動返信の送信元メールアドレス',
                'from_email_help' => '空の場合は、システムのデフォルト送信元アドレスが使用されます。',
                'subject' => '自動返信の件名',
                'body' => '自動返信の本文',
                'body_help' => '使用可能な変数: {{name}}, {{email}}, {{subject}}, {{postal_code}}, {{address}}, {{phone}}, {{message}}',
                'default_subject' => 'お問い合わせありがとうございます',
                'default_body' => "この度は、お問い合わせいただきありがとうございます。\n\n以下の内容で承りました。\n内容を確認の上、担当者よりご連絡させていただきます。\n\nお名前: {{name}}\nメールアドレス: {{email}}\n題名: {{subject}}\n\nお問い合わせ内容:\n{{message}}\n\n今後ともよろしくお願いいたします。",
            ],
            'admin_notification' => [
                'title' => '管理者通知設定',
                'admin_email' => '送信先メールアドレス',
                'admin_email_help' => '問い合わせ内容が送信されるメールアドレスを入力してください。',
                'subject' => '管理者通知の件名',
                'body' => '管理者通知の本文',
                'body_help' => '使用可能な変数: {{name}}, {{email}}, {{subject}}, {{postal_code}}, {{address}}, {{phone}}, {{message}}',
                'default_subject' => 'お問い合わせを受け付けました',
                'default_body' => "以下の内容でお問い合わせを受け付けました。\n\nお名前: {{name}}\nメールアドレス: {{email}}\n題名: {{subject}}\n郵便番号: {{postal_code}}\n住所: {{address}}\n電話番号: {{phone}}\n\nお問い合わせ内容:\n{{message}}",
            ],
            'completion' => [
                'title' => '完了ページ設定',
                'title_text' => '完了ページの見出し',
                'title_help' => '問い合わせ送信完了時に表示される見出しテキスト',
                'message' => '完了ページのメッセージ',
                'message_help' => '問い合わせ送信完了時に表示されるメッセージ。HTMLタグが使用できます。',
            ],
            'security' => [
                'title' => 'セキュリティ設定',
                'use_recaptcha' => 'CAPTCHAを使用する',
                'recaptcha_help' => 'スパム対策としてCAPTCHAを有効にします。事前にセキュリティ設定でCAPTCHAの設定が必要です。',
            ],
            'confirm_title' => '問い合わせ設定の保存',
            'confirm_message' => '問い合わせ設定を保存してもよろしいですか？',
            'mail_test_required' => '問い合わせフォーム機能を使用するには、<a href=":url" class="text-blue-600 hover:text-blue-800 underline">基本設定</a>でメールサーバー設定とメールテストをすべて完了してください。メールサーバーが設定されていない場合、問い合わせフォームは正常に動作しません。',
            'captcha_test_required' => 'CAPTCHA機能を使用するには、<a href=":url" class="text-blue-600 hover:text-blue-800 underline">セキュリティ設定</a>でCAPTCHA設定と認証テストをすべて完了してください。CAPTCHA設定が未完了の場合、CAPTCHA機能は正常に動作しません。',
        ],

    'messages' => [
        'settings_updated' => '設定が正常に更新されました。',
        'validation_error' => '入力内容に誤りがあります。',
    ],
];
