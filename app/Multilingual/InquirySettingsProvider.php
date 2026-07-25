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

namespace Plugins\DixlaseInquiry\App\Multilingual;

use App\Contracts\Multilingual\TranslatableContentProvider;
use App\Helpers\LocaleHelper;
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

    /**
     * The locale the primary-stored values are written in.
     *
     * Sourced from the existing `lang` setting (already user-configurable
     * in the inquiry settings UI as "form language"). Its semantics
     * already match what we need here — `lang = 'en'` means the operator
     * authored the primary form_heading / completion_message / etc. in
     * English, so EN is the locale that should be excluded from the
     * translation editor and short-circuited in the localized helper.
     *
     * `lang = 'auto'` (or unset) means the operator did not explicitly
     * declare the language; we fall back to the site's default locale as
     * the most-likely-correct guess. Operators who write primary content
     * in a non-default language should set `lang` explicitly to avoid
     * the EN-fallback rendering mismatched language text under
     * `auto + multilingual` mode.
     *
     * Returns null only when LocaleHelper is unavailable; the helper /
     * editor treat null as "no primary locale configured, treat all
     * enabled locales as translatable" (= pre-Phase-B behaviour).
     */
    public function getPrimaryLocale(): ?string
    {
        $lang = DixlaseInquirySetting::get('lang');

        if (is_string($lang) && $lang !== '' && $lang !== 'auto') {
            return $lang;
        }

        try {
            return LocaleHelper::getSiteDefaultLocale();
        } catch (\Throwable) {
            return null;
        }
    }
}
