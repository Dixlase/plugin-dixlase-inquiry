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
    'heading' => '問い合わせ一覧',
    'description' => '問い合わせフォームから受信した全ての問い合わせを確認・管理します。',

    'search_title' => '検索',
    'search_placeholder' => '名前、メール、件名、内容で検索...',
    'status_filter' => 'ステータス',
    'all_statuses' => 'すべてのステータス',
    'unread_count' => ':count 件未読',

    // 一括操作
    'bulk_selected' => '件選択中',
    'bulk_apply' => '適用',
    'bulk_status_updated' => ':count 件のステータスを更新しました。',

    'table' => [
        'caption' => '問い合わせ一覧',
        'id' => 'ID',
        'status' => 'ステータス',
        'name' => '名前',
        'email' => 'メール',
        'subject' => '件名',
        'submitted_at' => '送信日時',
        'actions' => '操作',
    ],

    'no_inquiries' => '問い合わせが見つかりません。',
    'view' => '詳細',
    'delete' => '削除',
    'confirm_delete_title' => '問い合わせの削除',
    'confirm_delete' => 'この問い合わせを削除しますか？この操作は元に戻せません。',
    'deleted' => '問い合わせが削除されました。',
];
