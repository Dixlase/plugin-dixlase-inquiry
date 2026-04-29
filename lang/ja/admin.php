<?php

/**
 * This file is part of Dixlase Inquiry.
 *
 * Copyright (C) 2026 exc-D inc.
 * https://exc-d.com
 *
 * Dixlase Inquiry is dual-licensed. You may use this file under either:
 *
 *   (a) the GNU General Public License version 3 or later, as published
 *       by the Free Software Foundation; or
 *
 *   (b) a commercial license agreement obtained from exc-D inc.
 *
 * Unless you have entered into a commercial license agreement, this
 * file is governed by the GPL terms below.
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
        'description' => 'ウェブサイトにお問い合わせフォーム機能を追加します。カスタマイズ可能なフォーム項目、自動返信、管理者通知、reCAPTCHA対応など包括的な機能を提供します。',
    ],

    'inquiry' => [
        'title' => 'お問い合わせ一覧',
        'list_coming_soon' => 'お問い合わせ一覧機能は近日公開予定です。',
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

    'messages' => [
        'settings_updated' => '設定が正常に更新されました。',
        'validation_error' => '入力内容に誤りがあります。',
    ],
];
