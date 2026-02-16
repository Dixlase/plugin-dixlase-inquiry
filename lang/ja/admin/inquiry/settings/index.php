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
    'heading' => 'お問い合わせ設定',
    'description' => 'お問い合わせフォームの各種設定を管理します。カテゴリを選択して設定を変更してください。',

    'nav' => [
        'form_basic' => 'フォーム基本設定',
        'form_display' => 'フォーム表示設定',
        'completion' => '完了ページ設定',
        'admin_notification' => '管理者通知設定',
        'auto_reply' => '自動返信設定',
    ],

    'cards' => [
        'form_basic_desc' => '名前形式、フィールドの表示・必須設定を管理します。',
        'form_display_desc' => '表示方式（シングル/別ページ）、URLスラッグ、確認画面の設定。',
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
    ],
];
