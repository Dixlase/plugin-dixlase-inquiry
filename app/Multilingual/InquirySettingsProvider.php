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

namespace Plugins\DixlaseInquiry\App\Multilingual;

use App\Contracts\Multilingual\TranslatableContentProvider;
use Plugins\DixlaseInquiry\App\Models\DixlaseInquirySetting;

/**
 * Primary-locale value source for the inquiry plugin's six translatable
 * settings (form_heading / form_description / completion_title /
 * completion_message / auto_reply_subject / auto_reply_body).
 *
 * Registered in plugin.json as the `provider` for the
 * `dixlase-inquiry:settings` singleton type. DixlaseMultilingual calls
 * this when no per-locale translation exists for the requested locale,
 * so the inquiry form falls back to the primary value stored in the
 * existing key-value `plg_dixlase_inquiry_settings` table.
 *
 * The previous Phase-A implementation routed this read through a
 * `DixlaseInquirySettingsAggregate` Eloquent model (with a custom
 * `getOriginalValue()` override). With Phase-3 singleton cardinality
 * the aggregate is gone — the provider replaces it as the single
 * authoritative read path for primary-locale values.
 */
class InquirySettingsProvider implements TranslatableContentProvider
{
    public function getPrimaryValue(string $field): ?string
    {
        $value = DixlaseInquirySetting::get($field);

        return $value === null ? null : (string) $value;
    }
}
