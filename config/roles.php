<?php

/**
 * This file is part of Dixlase Inquiry.
 *
 * Copyright (C) 2026 exc-D inc.
 * Website: https://exc-d.com
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

use App\Enums\MemberRole;

/**
 * プラグインのデフォルト権限設定
 * 
 * 各メニュー/機能に対するデフォルトの権限を定義します。
 * 管理画面で変更された場合のみ、role_permission_overrides テーブルに差分が保存されます。
 * 
 * 構造は config/admin.php の nav 構造と同じネスト形式です。
 */

return [
    'permissions' => [
        // 問い合わせ管理
        'inquiry' => [
            'children' => [
                // 問い合わせ一覧
                'inquiry_list' => [
                    'access_roles' => MemberRole::EDITOR->value,
                    'view_roles' => MemberRole::EDITOR->value,
                ],
                // 問い合わせ設定（管理者以上）
                'inquiry_settings' => [
                    'access_roles' => MemberRole::SUPER_ADMIN->value,
                    'view_roles' => MemberRole::SUPER_ADMIN->value,
                ],
            ],
        ],
    ],
];
