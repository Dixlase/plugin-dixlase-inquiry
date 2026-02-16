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
 */

return [
    'heading' => 'フォーム基本設定',
    'description' => 'お問い合わせフォームの名前形式、フィールドの表示・必須設定を管理します。',

    'format_style' => '入力形式',
    'format_japanese' => '日本式',
    'format_japanese_desc' => '姓・名の順、郵便番号・電話番号は分割入力、住所は都道府県から入力',
    'format_western' => '欧米式',
    'format_western_desc' => '名・姓の順、郵便番号・電話番号は1フィールド、住所は1フィールド',
    'format_style_help' => '名前の順序（姓・名 / 名・姓）、郵便番号・電話番号の形式、住所の入力順序が切り替わります。',

    'field_settings' => 'フィールド設定',
    'required_fields_note' => '※ 名前・メールアドレス・内容は常に表示され、必須項目となります。',
    'show_subject' => '題名フィールドを表示',
    'subject_required' => '題名を必須にする',
    'show_postal_address' => '郵便番号・住所フィールドを表示',
    'postal_address_required' => '郵便番号・住所を必須にする',
    'show_phone' => '電話番号フィールドを表示',
    'phone_required' => '電話番号を必須にする',
    'show_gender' => '性別フィールドを表示',
    'gender_required' => '性別を必須にする',
    'show_confirmation' => '確認画面を表示する',
    'show_confirmation_help' => 'チェックを外すと、入力後すぐに送信されます。',

    'confirm_title' => 'フォーム基本設定の保存',
    'confirm_message' => 'フォーム基本設定を保存してもよろしいですか？',
    'settings_updated' => 'フォーム基本設定が更新されました。',
];
