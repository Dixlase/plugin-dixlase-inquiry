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

namespace Plugins\DixlaseInquiry\Tests\Unit;

use PHPUnit\Framework\TestCase;

class MigrationTest extends TestCase
{
    /**
     * 問い合わせテーブルのマイグレーションファイルが存在する
     */
    public function test_inquiries_migration_file_exists(): void
    {
        $this->assertFileExists(
            __DIR__ . '/../../database/migrations/0001_01_01_000002_create_inquiries_table.php'
        );
    }

    /**
     * マイグレーションが正しいテーブル名を使用している
     */
    public function test_migration_uses_correct_table_name(): void
    {
        $content = file_get_contents(
            __DIR__ . '/../../database/migrations/0001_01_01_000002_create_inquiries_table.php'
        );

        $this->assertStringContainsString('plg_dixlase_inquiries', $content);
    }

    /**
     * マイグレーションに必要なカラムが含まれる
     */
    public function test_migration_contains_required_columns(): void
    {
        $content = file_get_contents(
            __DIR__ . '/../../database/migrations/0001_01_01_000002_create_inquiries_table.php'
        );

        $requiredColumns = [
            'status',
            'name',
            'email',
            'subject',
            'phone',
            'postal_code',
            'address',
            'gender',
            'message',
            'ip_address',
            'user_agent',
            'lang',
            'privacy_agreed_at',
            'submitted_at',
            'read_at',
        ];

        foreach ($requiredColumns as $column) {
            $this->assertStringContainsString("'{$column}'", $content, "Column '{$column}' not found in migration");
        }
    }
}
