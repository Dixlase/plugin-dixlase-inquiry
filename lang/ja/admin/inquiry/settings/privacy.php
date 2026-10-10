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
    'heading' => 'プライバシー設定',
    'description' => 'お問い合わせ送信を DB に保存するかどうかと、保存期間を設定します。',

    'intro' => '保存オフでも、通知メールと自動返信メールは従来どおり送信されます。管理者側で履歴を管理する運用のときだけ保存をオンにしてください。',

    'store_section' => 'DB 保存',
    'store_label' => 'お問い合わせを DB に保存する',
    'store_help' => '既定: オフ。送信時のメール送信は常に行われますが、このトグルは管理画面の受信箱で後から確認できるよう DB に行を書き込むかどうかだけを制御します。',

    'retention_section' => '保存期間',
    'retention_mode_label' => '保存する期間',
    'retention_help' => '保存期間の起点は送信日時です。期限を過ぎた行は prune コマンドで削除されます。この設定を変更しても既存行には遡って適用されません(既存行は保存時の期限が維持されます。個別の変更は詳細画面から)。',

    'retention_option_indefinite' => '無期限(ずっと保存)',
    'retention_option_days' => ':days 日',
    'retention_option_custom' => 'カスタム',

    'retention_custom_label' => 'カスタム保存期間(日数)',
    'retention_custom_placeholder' => '例: 60',
    'retention_custom_help' => '保存する日数を入力してください(1〜3650 日)。',

    'confirm_title' => 'プライバシー設定の保存',
    'confirm_message' => 'プライバシー設定を保存してもよろしいですか? 新規送信から新しい方針が適用され、既存行は影響を受けません。',
    'settings_updated' => 'プライバシー設定が更新されました。',
];
