<?php

/**
 * This file is part of Dixlase Inquiry.
 *
 * Copyright (C) 2026 exc-D inc.
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
        'heading' => 'お問い合わせ',
        'title' => 'お問い合わせ',
        'name' => 'お名前',
        'last_name' => '姓',
        'last_name_label_ja' => '姓',
        'last_name_label_en' => 'Last Name',
        'first_name' => '名',
        'first_name_label_ja' => '名',
        'first_name_label_en' => 'First Name',
        'email' => 'メールアドレス',
        'subject' => '題名',
        'postal_code' => '郵便番号',
        'address' => '住所',
        'country' => '国',
        'state_province' => '州・県',
        'prefecture' => '都道府県',
        'city' => '市区町村',
        'address_line' => '番地',
        'building' => '建物名・マンション名',
        'phone' => '電話番号',
        'message' => 'お問い合わせ内容',
        'recaptcha' => 'reCAPTCHA',
        'submit' => '送信する',
        'confirm' => '確認する',
        'last_name_placeholder' => '山田',
        'first_name_placeholder' => '太郎',
        'email_placeholder' => 'example@example.com',
        'subject_placeholder' => 'お問い合わせの件について',
        'postal_code_placeholder' => '123-4567',
        'address_placeholder' => '東京都渋谷区...',
        'state_province_placeholder' => 'California',
        'city_placeholder' => '渋谷区',
        'city_placeholder_en' => 'Los Angeles',
        'address_line_placeholder' => '神南1-2-3',
        'address_line_placeholder_en' => '123 Main Street',
        'building_placeholder' => 'ABCビル 5階',
        'phone_placeholder' => '03-1234-5678',
        'message_placeholder' => 'お問い合わせ内容をご記入ください',
        // 欧米式住所フィールド
        'street_address' => '番地・建物名',
        'city' => '市区町村',
        'state' => '都道府県',
        'country' => '国',
        'street_address_placeholder' => '1-2-3 神南, ABCビル 5階',
        'city_placeholder' => '渋谷区',
        'state_placeholder' => '東京都',
        'country_placeholder' => '日本',
        // 性別フィールド
        'gender' => '性別',
        'gender_select' => '選択してください',
        'gender_male' => '男性',
        'gender_female' => '女性',
        'gender_non_binary' => 'ノンバイナリー',
        'gender_other' => 'その他',
        'gender_prefer_not_to_say' => '回答しない',
        'success_message' => 'お問い合わせありがとうございました。内容を確認の上、担当者よりご連絡させていただきます。',
        'privacy_consent' => '<a href=":url" target="_blank" class="text-blue-600 hover:underline dark:text-blue-400">プライバシーポリシー</a>に同意します。',
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

    'confirmation' => [
        'title' => '入力内容の確認',
        'message' => '以下の内容でお間違いありませんか？',
    ],

    'complete' => [
        'title' => '送信完了',
        'message' => 'お問い合わせありがとうございました。<br>内容を確認の上、担当者よりご連絡させていただきます。',
        'inquiry_details' => '送信内容',
        'inquiry_number' => '受付番号',
        'submitted_at' => '送信日時',
        'auto_reply_notice' => '確認メールを送信しました。メールが届かない場合は、迷惑メールフォルダをご確認ください。',
        'back_to_home' => 'トップページへ戻る',
    ],

    'validation' => [
        'last_name_required' => '姓は必須です。',
        'first_name_required' => '名は必須です。',
        'email_required' => 'メールアドレスは必須です。',
        'email_invalid' => '有効なメールアドレスを入力してください。',
        'message_required' => 'お問い合わせ内容は必須です。',
        'subject_required' => '題名は必須です。',
        'postal_code_required' => '郵便番号は必須です。',
        'postal_code_format_japanese' => '郵便番号は「123-4567」または「1234567」の形式で入力してください。',
        'postal_code_format_western' => '郵便番号は3〜10文字の英数字で入力してください。',
        'address_required' => '住所は必須です。',
        'street_address_required' => '番地・建物名は必須です。',
        'city_required' => '市区町村は必須です。',
        'state_required' => '都道府県は必須です。',
        'phone_required' => '電話番号は必須です。',
        'phone_format_japanese' => '電話番号は10〜13桁の数字（ハイフン可）で入力してください。',
        'phone_format_western' => '電話番号は有効な形式で入力してください（例: +1-234-567-8900）。',
        'gender_required' => '性別を選択してください。',
        'gender_invalid' => '有効な性別を選択してください。',
        'recaptcha_required' => 'reCAPTCHA認証が必要です。',
        'privacy_agreed_required' => 'プライバシーポリシーに同意してください。',
    ],

    'messages' => [
        'service_unavailable' => 'お問い合わせ機能は現在ご利用いただけません。',
        'submit_success' => 'お問い合わせが送信されました。ありがとうございます。',
        'submit_error' => 'お問い合わせの送信中にエラーが発生しました。しばらくしてからもう一度お試しください。',
    ],

    'mail' => [
        'new_inquiry_subject' => '新しいお問い合わせがありました',
        'auto_reply_subject' => 'お問い合わせを受け付けました',
        'admin' => [
            'title' => '新しいお問い合わせ',
            'intro' => '以下の内容でお問い合わせを受け付けました。',
        ],
        'auto_reply' => [
            'title' => 'お問い合わせありがとうございます',
            'greeting' => '様',
            'intro' => 'この度は、お問い合わせいただきありがとうございます。以下の内容で承りました。内容を確認の上、担当者よりご連絡させていただきます。',
            'footer' => '今後ともよろしくお願いいたします。',
        ],
    ],
];
