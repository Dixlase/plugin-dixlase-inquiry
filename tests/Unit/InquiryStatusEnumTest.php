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

namespace Plugins\DixlaseInquiry\Tests\Unit;

use PHPUnit\Framework\TestCase;
use Plugins\DixlaseInquiry\App\Enums\InquiryStatus;

class InquiryStatusEnumTest extends TestCase
{
    /**
     * 全ステータスケースが定義されている
     */
    public function test_all_status_cases_exist(): void
    {
        $cases = InquiryStatus::cases();

        $this->assertCount(3, $cases);
        $this->assertContains(InquiryStatus::New, $cases);
        $this->assertContains(InquiryStatus::InProgress, $cases);
        $this->assertContains(InquiryStatus::Completed, $cases);
    }

    /**
     * ステータスの値が正しい
     */
    public function test_status_values(): void
    {
        $this->assertEquals('new', InquiryStatus::New->value);
        $this->assertEquals('in_progress', InquiryStatus::InProgress->value);
        $this->assertEquals('completed', InquiryStatus::Completed->value);
    }

    /**
     * CSSクラスが空でない
     */
    public function test_css_class_returns_non_empty_string(): void
    {
        foreach (InquiryStatus::cases() as $status) {
            $this->assertNotEmpty($status->cssClass(), "cssClass() for {$status->value} is empty");
        }
    }

    /**
     * 文字列からステータスを生成できる
     */
    public function test_from_string(): void
    {
        $this->assertEquals(InquiryStatus::New, InquiryStatus::from('new'));
        $this->assertEquals(InquiryStatus::InProgress, InquiryStatus::from('in_progress'));
        $this->assertEquals(InquiryStatus::Completed, InquiryStatus::from('completed'));
    }

    /**
     * 不正な値でエラーが発生する
     */
    public function test_invalid_value_throws_error(): void
    {
        $this->expectException(\ValueError::class);
        InquiryStatus::from('invalid');
    }
}
