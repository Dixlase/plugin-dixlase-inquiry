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
    'heading' => 'フォーム表示設定',
    'description' => 'お問い合わせフォームの表示方式（シングル/別ページ）とURLスラッグを設定します。',

    'form_type' => 'フォーム表示方式',
    'single_page' => 'シングルページ（動的に確認画面・完了画面を表示）',
    'separate_pages' => '別ページ（入力画面・確認画面・完了画面を別々のページで表示）',
    'single_page_help' => 'シングルページ方式では、1つのページ内で入力から完了まで処理されます。',

    'inquiry_url' => '問い合わせページURL',
    'inquiry_url_slug_help' => '問い合わせページのURLを設定します（例: inquiry → /inquiry）。半角英数字とハイフンのみ使用可能です。',
    'preview_page_help' => '新しいタブで問い合わせページを開きます。設定を保存する前にプレビューできます。',

    'confirm_title' => 'フォーム表示設定の保存',
    'confirm_message' => 'フォーム表示設定を保存してもよろしいですか？',
    'settings_updated' => 'フォーム表示設定が更新されました。',
];
