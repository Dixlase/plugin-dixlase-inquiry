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
use Plugins\DixlaseInquiry\App\Models\DixlaseInquiry;

class DixlaseInquiryModelTest extends TestCase
{
    /**
     * テーブル名が正しい
     */
    public function test_table_name(): void
    {
        $model = new DixlaseInquiry();
        $this->assertEquals('plg_dixlase_inquiries', $model->getTable());
    }

    /**
     * fillableが正しく設定されている
     */
    public function test_fillable_contains_all_fields(): void
    {
        $model = new DixlaseInquiry();
        $fillable = $model->getFillable();

        $expectedFields = [
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

        foreach ($expectedFields as $field) {
            $this->assertContains($field, $fillable, "Field '{$field}' not found in fillable");
        }
    }

    /**
     * castsにdatetimeフィールドが含まれる
     */
    public function test_casts_contain_datetime_fields(): void
    {
        $model = new DixlaseInquiry();
        $casts = $model->getCasts();

        $this->assertArrayHasKey('submitted_at', $casts);
        $this->assertArrayHasKey('read_at', $casts);
        $this->assertArrayHasKey('privacy_agreed_at', $casts);
        $this->assertArrayHasKey('status', $casts);
    }

    /**
     * ファクトリが存在する
     */
    public function test_factory_class_exists(): void
    {
        $this->assertTrue(
            class_exists(\Plugins\DixlaseInquiry\Database\Factories\DixlaseInquiryFactory::class)
        );
    }
}
