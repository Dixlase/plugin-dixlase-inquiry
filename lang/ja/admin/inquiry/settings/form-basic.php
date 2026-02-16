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
    'heading' => 'フォーム設定',
    'description' => '言語、表示方式、名前形式、フィールドの表示・必須設定を管理します。',

    // 言語設定
    'form_locale' => 'フォーム言語',
    'form_locale_help' => '訪問者に表示されるお問い合わせフォームの言語を選択します。',

    // フォーム表示方式（form-displayから移動）
    'form_type' => 'フォーム表示方式',
    'single_page' => 'シングルページ',
    'single_page_desc' => '入力・確認・完了を1つのページで動的に処理',
    'separate_pages' => '別ページ',
    'separate_pages_desc' => '入力・確認・完了を別々のURLのページで表示',
    'single_page_help' => 'シングルページ方式では、1つのページ内で入力から完了まで処理されます。',
    'inquiry_url' => '問い合わせページURL',
    'inquiry_url_slug_help' => '問い合わせページのURLを設定します（例: inquiry → /inquiry）。半角英数字とハイフンのみ使用可能です。',
    'preview_page_help' => '新しいタブで問い合わせページを開きます。設定を保存する前にプレビューできます。',

    // 入力形式
    'format_style' => '入力形式',
    'format_japanese' => '日本式',
    'format_japanese_desc' => '姓・名の順、郵便番号・電話番号は分割入力、住所は都道府県から入力',
    'format_western' => '欧米式',
    'format_western_desc' => '名・姓の順、郵便番号・電話番号は1フィールド、住所は1フィールド',
    'format_style_help' => '名前の順序（姓・名 / 名・姓）、郵便番号・電話番号の形式、住所の入力順序が切り替わります。',

    // フィールド設定
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

    // プレビュー
    'form_preview' => 'フォームプレビュー',

    'confirm_title' => 'フォーム設定の保存',
    'confirm_message' => 'フォーム設定を保存してもよろしいですか？',
    'settings_updated' => 'フォーム設定が更新されました。',
];
