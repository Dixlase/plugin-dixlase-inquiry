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

use App\Helpers\LocaleHelper;
use Plugins\DixlaseMultilingual\App\Services\EnabledLocaleResolver;
use Throwable;

/**
 * Bridges the inquiry plugin to the optional Dixlase Multilingual plugin.
 *
 * When the multilingual plugin is installed and active, the form's selectable
 * locales and the "auto" resolution follow that plugin's settings. When it is
 * absent, the form falls back to the inquiry plugin's static config and the
 * site's primary locale from core.
 */
class InquiryLocaleSupport
{
    /**
     * Whether the multilingual plugin is installed and bound in the container.
     */
    public static function multilingualEnabled(): bool
    {
        return class_exists(EnabledLocaleResolver::class)
            && app()->bound(EnabledLocaleResolver::class);
    }

    /**
     * Locales selectable as form language.
     *
     * Priority:
     *   1. Multilingual plugin's enabled locales (when active)
     *   2. config('dixlase-inquiry.locales') fallback
     *   3. LocaleHelper::supportedLocales()
     *
     * @return list<string>
     */
    public static function enabledLocales(): array
    {
        if (self::multilingualEnabled()) {
            try {
                $locales = app(EnabledLocaleResolver::class)->getEnabledLocales();
                if (! empty($locales)) {
                    return array_values($locales);
                }
            } catch (Throwable) {
                // Fall through to fallback paths.
            }
        }

        $configured = config('dixlase-inquiry.locales');
        if (is_array($configured) && ! empty($configured)) {
            return array_values($configured);
        }

        return LocaleHelper::supportedLocales();
    }

    /**
     * Locale used when the form is rendered at the root URL with `lang='auto'`.
     *
     * - Multilingual active: the plugin's configured default (its "fixed"
     *   selection wins; "auto" mode delegates to the request locale already
     *   resolved by core middleware via Accept-Language → site primary).
     * - Multilingual inactive: the site's basic-settings primary locale.
     */
    public static function resolvedDefaultLocale(): string
    {
        if (self::multilingualEnabled()) {
            try {
                $resolver = app(EnabledLocaleResolver::class);
                if ($resolver->getDefaultLocaleMode() === EnabledLocaleResolver::DEFAULT_MODE_FIXED) {
                    return $resolver->getFallbackLocale();
                }
            } catch (Throwable) {
                // Fall through.
            }
        }

        try {
            return LocaleHelper::getSiteDefaultLocale();
        } catch (Throwable) {
            return app()->getLocale();
        }
    }

    /**
     * Effective locale for a given form `lang` setting.
     *
     * - Explicit value (e.g. 'ja', 'en') → returned as-is.
     * - 'auto' with multilingual active → defers to `app()->getLocale()`,
     *   which the core SetFrontLocale middleware has already cascaded through
     *   URL prefix → cookie → Accept-Language → multilingual default → site
     *   primary.
     * - 'auto' without multilingual → bypasses Accept-Language and uses the
     *   site's basic-settings default, so a single-language site never serves
     *   the form in a non-site language just because the visitor's browser
     *   prefers it.
     */
    public static function resolveFormLocale(string $langSetting): string
    {
        if ($langSetting !== 'auto' && $langSetting !== '') {
            return $langSetting;
        }

        if (self::multilingualEnabled()) {
            return app()->getLocale();
        }

        try {
            return LocaleHelper::getSiteDefaultLocale();
        } catch (Throwable) {
            // Site context may be unavailable in CLI/queue/test contexts.
            return app()->getLocale();
        }
    }

    /**
     * Options for the form-language `<select>` in admin settings.
     * 'auto' is always present as the first entry.
     *
     * @return array<string, string>
     */
    public static function langSelectOptions(): array
    {
        $options = [
            'auto' => __('dixlase-inquiry::admin/inquiry/settings/form-basic.lang_auto'),
        ];

        foreach (self::enabledLocales() as $locale) {
            $options[$locale] = LocaleHelper::getLocaleName($locale, native: true);
        }

        return $options;
    }
}
