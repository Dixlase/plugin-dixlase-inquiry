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

namespace Plugins\DixlaseInquiry\App\Enums;

/**
 * 問い合わせステータス
 */
enum InquiryStatus: string
{
    case New = 'new';
    case InProgress = 'in_progress';
    case Completed = 'completed';

    /**
     * ステータスのラベルを取得
     */
    public function label(): string
    {
        return __('dixlase-inquiry::admin/inquiry/status.'.$this->value);
    }

    /**
     * ステータスのCSSクラスを取得
     */
    public function cssClass(): string
    {
        return match ($this) {
            self::New => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300',
            self::InProgress => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300',
            self::Completed => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300',
        };
    }
}
