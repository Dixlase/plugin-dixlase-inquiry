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

use App\Contracts\Multilingual\SingletonTranslationResolver;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Plugins\DixlaseInquiry\App\Models\DixlaseInquirySetting;
use Plugins\DixlaseInquiry\App\Multilingual\InquirySettingsProvider;
use Tests\TestCase;

/**
 * Phase-3 (singleton-cardinality) localization for the inquiry plugin's
 * six translatable settings.
 *
 * Covers the two read paths that replace the previous
 * `DixlaseInquirySettingsAggregate` aggregate model:
 *
 * 1. {@see InquirySettingsProvider} — primary-locale source the
 *    Multilingual plugin calls when no translation exists for the
 *    requested locale.
 * 2. `dls_inquiry_localized_setting()` — front-side helper that drives
 *    the actual lookup chain: current locale via
 *    `SingletonTranslationResolver`, then site default locale, then
 *    primary value.
 *
 * Also asserts the plugin.json shape matches the new singleton contract
 * (cardinality + provider + 6 expected fields).
 */
class InquirySettingsLocalizationTest extends TestCase
{
    use RefreshDatabase;

    private const TYPE_KEY = 'dixlase-inquiry:settings';

    protected function setUp(): void
    {
        parent::setUp();

        // Helpers file is normally loaded by DixlaseInquiryServiceProvider's
        // boot(), but the test environment runs with INSTALLED=false so the
        // provider never boots. require_once is idempotent.
        require_once __DIR__.'/../../app/Helpers/DixlaseInquiryHelpers.php';
    }

    private const EXPECTED_FIELDS = [
        'form_heading',
        'form_description',
        'completion_title',
        'completion_message',
        'auto_reply_subject',
        'auto_reply_body',
    ];

    public function test_provider_returns_primary_value_from_settings_table(): void
    {
        DixlaseInquirySetting::set('form_heading', 'プライマリ見出し');

        $this->assertSame(
            'プライマリ見出し',
            (new InquirySettingsProvider)->getPrimaryValue('form_heading'),
        );
    }

    public function test_provider_returns_null_for_missing_setting(): void
    {
        $this->assertNull((new InquirySettingsProvider)->getPrimaryValue('form_heading'));
    }

    public function test_helper_returns_primary_value_when_no_resolver_bound(): void
    {
        DixlaseInquirySetting::set('form_heading', 'プライマリ見出し');

        // No SingletonTranslationResolver bound — helper should fall
        // straight through to DixlaseInquirySetting::get().
        $this->assertFalse(app()->bound(SingletonTranslationResolver::class));

        $this->assertSame('プライマリ見出し', dls_inquiry_localized_setting('form_heading'));
    }

    public function test_helper_returns_resolver_value_for_current_locale(): void
    {
        DixlaseInquirySetting::set('form_heading', 'プライマリ見出し');

        $this->bindResolverReturning('form_heading', 'ja', '日本語見出し');
        app()->setLocale('ja');

        $this->assertSame('日本語見出し', dls_inquiry_localized_setting('form_heading'));
    }

    public function test_helper_falls_back_to_primary_when_no_translation_exists(): void
    {
        DixlaseInquirySetting::set('form_heading', 'プライマリ見出し');

        // Resolver returns null for every (typeKey, field, locale) so
        // the helper exhausts the resolver chain and falls back to
        // the primary value.
        $this->bindResolverReturning('form_heading', '__nope__', null);
        app()->setLocale('en');

        $this->assertSame('プライマリ見出し', dls_inquiry_localized_setting('form_heading'));
    }

    public function test_helper_returns_null_when_neither_translation_nor_primary_exists(): void
    {
        $this->bindResolverReturning('form_heading', '__nope__', null);
        app()->setLocale('en');

        $this->assertNull(dls_inquiry_localized_setting('form_heading'));
    }

    public function test_plugin_json_declares_singleton_with_provider(): void
    {
        $manifest = json_decode(
            (string) file_get_contents(__DIR__.'/../../plugin.json'),
            true,
        );

        $this->assertIsArray($manifest);

        $types = $manifest['multilingual_content']['types'] ?? [];
        $this->assertIsArray($types);
        $this->assertCount(1, $types);

        $type = $types[0];
        $this->assertSame(self::TYPE_KEY, $type['key']);
        $this->assertSame('singleton', $type['cardinality']);
        $this->assertSame(
            'Plugins\\DixlaseInquiry\\App\\Multilingual\\InquirySettingsProvider',
            $type['provider'],
        );
        $this->assertArrayNotHasKey(
            'model',
            $type,
            'Phase-3 singleton types must not carry the legacy `model` key.',
        );

        $declared = array_map(static fn (array $f): string => $f['name'], $type['fields']);
        $this->assertSame(
            self::EXPECTED_FIELDS,
            $declared,
            'Translatable field list must match the design memo scope: '
            .'form/completion text + auto-reply email — admin-notification '
            .'subject/body are intentionally excluded.',
        );
    }

    /**
     * Bind an in-memory {@see SingletonTranslationResolver} that returns
     * `$value` for the matching `(field, locale)` and null otherwise.
     */
    private function bindResolverReturning(string $field, string $locale, ?string $value): void
    {
        $resolver = new class($field, $locale, $value) implements SingletonTranslationResolver
        {
            public function __construct(
                private readonly string $expectedField,
                private readonly string $expectedLocale,
                private readonly ?string $value,
            ) {}

            public function resolve(string $typeKey, string $field, string $locale): mixed
            {
                if ($field === $this->expectedField && $locale === $this->expectedLocale) {
                    return $this->value;
                }

                return null;
            }

            public function store(string $typeKey, string $field, mixed $value, string $locale): void {}

            public function all(string $typeKey, string $field): array
            {
                return [];
            }

            public function exists(string $typeKey, string $field, string $locale): bool
            {
                return false;
            }

            public function delete(string $typeKey, string $field, ?string $locale = null): void {}

            public function getAvailableLocales(string $typeKey): array
            {
                return [];
            }
        };

        app()->instance(SingletonTranslationResolver::class, $resolver);
    }
}
