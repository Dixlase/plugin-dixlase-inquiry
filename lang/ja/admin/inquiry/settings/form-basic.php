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

    // セクション見出し
    'section_form_settings' => 'フォーム設定',
    'section_field_settings' => 'フィールド設定',
    'section_display_settings' => 'フォーム表示設定',

    // 言語設定
    'form_locale' => 'フォーム言語',
    'form_locale_auto' => '自動',
    'form_locale_auto_desc' => 'サイトのデフォルト言語を使用',
    'form_locale_help' => '訪問者に表示されるお問い合わせフォームの言語を選択します。「自動」を選択すると、サイトのデフォルト言語が使用されます。',

    // フォーム表示方式（form-displayから移動）
    'form_type' => 'フォーム表示設定',
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
    'show_subject' => '題名',
    'subject_required' => '必須',
    'show_postal_address' => '郵便番号・住所',
    'postal_address_required' => '必須',
    'show_phone' => '電話番号',
    'phone_required' => '必須',
    'show_gender' => '性別',
    'gender_required' => '必須',
    'show_kana' => 'フリガナ',
    'require_kana' => '必須',
    'show_kana_help' => '日本式選択時のみ使用できます。欧米式ではカタカナフィールドは表示されません。',
    'show_confirmation' => '確認画面を表示する',
    'show_confirmation_help' => 'チェックを外すと、入力後すぐに送信されます。',

    // プレビュー
    'form_preview' => 'フォームプレビュー',
    'open_form_page' => 'フォームページを開く',

    // プライバシー同意設定
    'section_privacy' => 'プライバシー同意',
    'privacy_settings' => 'プライバシー同意設定',
    'privacy_consent_enabled' => 'プライバシー同意チェックを有効にする',
    'privacy_consent_enabled_help' => '有効にすると、ユーザーはフォーム送信前にプライバシーポリシーへの同意が必要になります。',
    'privacy_policy_url' => 'プライバシーポリシーURL',
    'privacy_policy_url_help' => 'プライバシーポリシーページのURLを入力してください。法務プラグインがインストールされている場合、URLは自動取得されます。',
    'privacy_consent_text' => '同意文テキスト',
    'privacy_consent_text_placeholder' => 'プライバシーポリシーに同意します',
    'privacy_consent_text_help' => '同意チェックボックスのカスタムテキスト。空欄の場合はデフォルトのテキストが使用されます。',

    // 送信間隔制限設定
    'section_throttle' => '送信間隔制限',
    'throttle_settings' => '送信間隔制限設定',
    'throttle_enabled' => '送信間隔制限を有効にする',
    'throttle_enabled_help' => '同一IPアドレスからの一定時間内の送信回数を制限します。',
    'throttle_max_attempts' => '最大送信回数',
    'throttle_decay_minutes' => '制限期間（分）',
    'throttle_help' => '例: 同一IPアドレスから5分以内に最大3回まで送信を許可します。',

    'confirm_title' => 'フォーム設定の保存',
    'confirm_message' => 'フォーム設定を保存してもよろしいですか？',
    'settings_updated' => 'フォーム設定が更新されました。',
];
