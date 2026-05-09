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

namespace Plugins\DixlaseInquiry\App\Support;

/**
 * Resolves inquiry form settings that support an 'auto' value.
 */
class InquiryFormResolver
{
    /**
     * Resolve whether the name order should be western (first/last).
     *
     * - '1' (or true) → western
     * - '0' (or false) → Japanese
     * - 'auto' → western if the form's effective locale is not Japanese.
     *   The effective locale is derived through InquiryLocaleSupport so that
     *   isWestern() is correct even when the caller has not yet applied the
     *   form locale to the application.
     */
    public static function isWestern(object $settings): bool
    {
        $value = $settings->name_order_western ?? false;

        if ($value === 'auto') {
            $locale = InquiryLocaleSupport::resolveFormLocale($settings->lang ?? 'auto');

            return ! str_starts_with($locale, 'ja');
        }

        return filter_var($value, FILTER_VALIDATE_BOOLEAN);
    }
}
