<?php

/**
 * This file is part of Dixlase Inquiry.
 *
 * Copyright (C) 2026 exc-D inc.
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

namespace Plugins\DixlaseInquiry\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class InquiryRolePermissionSeeder extends Seeder
{
    /**
     * シーダー実行
     */
    public function run(): void
    {
        // テーブルが存在しない場合はスキップ（DixlaseUsersプラグイン未インストール時）
        if (!Schema::hasTable('members_role_permissions')) {
            return;
        }

        $permissions = [
            [
                'menu_key' => 'settings.inquiries',
                'access_roles' => '9',
                'view_roles'  => '9',
            ],
        ];

        foreach ($permissions as $permission) {
            // 既存のレコードをチェック
            $exists = DB::table('members_role_permissions')
                ->where('menu_key', $permission['menu_key'])
                ->exists();

            if (!$exists) {
                DB::table('members_role_permissions')->insert([
                    'menu_key' => $permission['menu_key'],
                    'access_roles' => $permission['access_roles'],
                    'view_roles' => $permission['view_roles'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
