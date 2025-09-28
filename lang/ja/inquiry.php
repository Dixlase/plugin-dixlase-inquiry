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
    ],
    'admin' => [
        'settings' => [
            'title' => '問い合わせフォーム設定',
            'basic' => [
                'title' => '基本設定',
                'admin_email' => '送信先メールアドレス',
                'admin_email_help' => '問い合わせ内容が送信されるメールアドレスを入力してください。',
            ],
            'form_fields' => [
                'title' => 'フォーム項目設定',
                'name_order' => '名前の表示順序',
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
            ],
            'admin_notification' => [
                'title' => '管理者通知設定',
                'subject' => '管理者通知の件名',
                'body' => '管理者通知の本文',
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
                'use_recaptcha' => 'reCAPTCHAを使用する',
                'recaptcha_help' => 'スパム対策としてreCAPTCHAを有効にします。事前にセキュリティ設定でreCAPTCHAの設定が必要です。',
            ],
            'confirm_title' => '問い合わせ設定の保存',
            'confirm_message' => '問い合わせ設定を保存してもよろしいですか？',
        ],
    ],

    'messages' => [
        'settings_updated' => '設定が正常に更新されました。',
        'validation_error' => '入力内容に誤りがあります。',
    ],
];
