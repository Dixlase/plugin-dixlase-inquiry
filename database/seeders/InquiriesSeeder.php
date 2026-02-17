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

namespace Plugins\DixlaseInquiry\Database\Seeders;

use Illuminate\Database\Seeder;
use Plugins\DixlaseInquiry\App\Models\DixlaseInquiry;

class InquiriesSeeder extends Seeder
{
    /**
     * サンプル問い合わせデータを生成
     */
    public function run(): void
    {
        DixlaseInquiry::factory()->count(5)->statusNew()->create();
        DixlaseInquiry::factory()->count(3)->inProgress()->create();
        DixlaseInquiry::factory()->count(2)->completed()->create();
    }
}
