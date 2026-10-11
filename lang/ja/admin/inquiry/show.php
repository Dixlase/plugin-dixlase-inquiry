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
    'heading' => '問い合わせ詳細',
    'description' => '問い合わせの詳細を確認し、ステータスを管理します。',

    'back_to_list' => '一覧に戻る',
    'inquiry_info' => '問い合わせ情報',
    'meta_info' => 'メタ情報',

    'name' => '名前',
    'email' => 'メールアドレス',
    'subject' => '件名',
    'phone' => '電話番号',
    'postal_code' => '郵便番号',
    'address' => '住所',
    'gender' => '性別',
    'message' => 'お問い合わせ内容',

    'status' => 'ステータス',
    'change_status' => 'ステータス変更',
    'submitted_at' => '送信日時',
    'read_at' => '既読日時',
    'ip_address' => 'IPアドレス',
    'user_agent' => 'ユーザーエージェント',
    'lang' => 'フォーム言語',
    'privacy_agreed' => 'プライバシー同意',
    'privacy_agreed_yes' => ':date に同意',
    'privacy_agreed_no' => '未同意',
    'not_set' => '-',

    'delete' => '削除',
    'delete_confirm_title' => '問い合わせの削除',
    'delete_confirm_message' => 'この問い合わせをゴミ箱に移動します。30 日以内であればゴミ箱から復元できます。',
    'status_updated' => 'ステータスが更新されました。',

    // 保存期間(expires_at)の個別制御
    'retention_title' => '保存期間',
    'retention_indefinite' => '無期限(自動削除なし)',
    'retention_days_remaining' => '残り :days 日',
    'retention_expires_today' => '本日期限切れ',
    'retention_expired' => ':days 日前に期限切れ',
    'retention_mode_label' => '保存期間を変更',
    'retention_option_indefinite' => '無期限(自動削除しない)',
    'retention_option_days' => '今日から :days 日',
    'retention_option_custom' => 'カスタム日時',
    'retention_custom_date_label' => '削除予定日時',
    'retention_update' => '保存期間を更新',
    'expires_at_updated' => '保存期間を更新しました。',
];
