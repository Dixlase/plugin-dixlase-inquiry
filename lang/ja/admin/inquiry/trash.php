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
 */

return [
    'heading' => 'ゴミ箱',
    'description' => '削除した問い合わせはこちらに保管されます。誤って削除した場合はここから復元できます。',

    'retention_notice' => 'ゴミ箱内の問い合わせは削除から 30 日後に完全に削除されます。残しておきたいものは期限までに復元してください。',
    'gdpr_hint' => 'プライバシーに関する削除要請は、ゴミ箱に残っている状態では満たされません。その用途では「完全に削除」を使ってください。',

    'back_to_index' => '問い合わせ一覧に戻る',

    'table' => [
        'caption' => 'ゴミ箱の問い合わせ一覧',
        'id' => 'ID',
        'name' => 'お名前',
        'email' => 'メールアドレス',
        'subject' => '題名',
        'deleted_at' => '削除日時',
        'actions' => '操作',
    ],

    'no_trashed_inquiries' => 'ゴミ箱は空です。',

    'restore' => '復元',
    'restore_success' => '問い合わせを復元しました。',

    'force_destroy' => '完全に削除',
    'confirm_force_destroy_title' => '完全に削除',
    'confirm_force_destroy' => 'この問い合わせと個人情報を含む全データを完全に削除します。この操作は取り消せません。',
    'force_destroy_success' => '問い合わせを完全に削除しました。',

    'empty_trash' => 'ゴミ箱を空にする',
    'confirm_empty_title' => 'ゴミ箱を空にする',
    'confirm_empty' => 'ゴミ箱内のすべての問い合わせを完全に削除します。この操作は取り消せません。',
    'empty_trash_success' => 'ゴミ箱を空にしました（:count 件を完全に削除）。',
];
