<?php

/**
 * This file is part of Dixlase Inquiry.
 *
 * Copyright (C) 2026 exc-D inc. and Dixlase contributors
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
    'heading' => 'お問い合わせ設定',
    'description' => 'お問い合わせフォームの各種設定の概要です。埋め込み方法やステータスを一目で確認できます。',

    // 受付状態
    'accepting_inquiries' => 'お問い合わせの受付',
    'accepting_on' => 'お問い合わせを受け付けています',
    'accepting_on_desc' => 'フォームが表示され、訪問者からのお問い合わせを受け付けます。',
    'accepting_off' => 'お問い合わせの受付を停止しています',
    'accepting_off_desc' => 'フォームは非表示になり、お問い合わせページにもアクセスできません。',
    'accepting_toggle_success' => '受付状態を更新しました。',
    'accepting_toggle_error' => '受付状態の更新に失敗しました。',

    'nav' => [
        'form_basic' => 'フォーム設定',
        'completion' => '完了ページ設定',
        'admin_notification' => '管理者通知設定',
        'auto_reply' => '自動返信設定',
    ],

    'cards' => [
        'form_basic_desc' => '言語、表示方式、名前形式、フィールドの表示・必須設定を管理します。',
        'completion_desc' => 'フォーム送信後に表示される見出しとメッセージ。',
        'admin_notification_desc' => '管理者通知のメールアドレス、件名、本文。',
        'auto_reply_desc' => '自動返信の有効/無効、送信元、件名、本文。',
    ],

    'status' => [
        'name_format' => '名前形式',
        'japanese' => '日本式',
        'western' => '欧米式',
        'display_method' => '表示方式',
        'single_page' => 'シングルページ',
        'separate_pages' => '別ページ',
        'confirmation' => '確認画面',
        'enabled' => '有効',
        'disabled' => '無効',
        'admin_email' => '送信先',
        'auto_reply' => '自動返信',
        'lang' => 'フォーム言語',
    ],

    // 埋め込み方法（form-previewから移動）
    'embedding_methods' => '埋め込み方法',
    'usage_instruction_title' => '使用方法',
    'usage_instruction_text' => '以下のコードをテーマのBladeテンプレートまたはページ作成プラグインのコンテンツ内に貼り付けてください。',
    'blade_directive' => 'Bladeディレクティブ（推奨）',
    'blade_directive_help' => 'テーマのBladeテンプレート内で使用します。Laravel開発者に最適です。',
    'shortcode' => 'ショートコード',
    'shortcode_help' => 'ページ作成プラグインのコンテンツ内で使用します。非技術者でも簡単に使えます。',

    // 警告メッセージ
    'warning_admin_email' => '管理者通知メールアドレスが未設定です。<a href=":url" class="font-medium underline">管理者通知設定</a>で設定してください。',
    'warning_auto_reply_email' => '自動返信が有効ですが、送信元メールアドレスが未設定です。<a href=":url" class="font-medium underline">自動返信設定</a>で設定してください。',

    // CAPTCHA案内メッセージ
    'notice_captcha_disabled' => 'お問い合わせフォームのCAPTCHAが有効になっていません。スパム対策のため、<a href=":url" class="font-medium underline">セキュリティ設定 &gt; CAPTCHA</a>でCAPTCHAを有効にすることを推奨します。',
];
