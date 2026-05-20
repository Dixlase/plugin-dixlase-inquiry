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

use App\Contracts\TranslationResolver;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Plugins\DixlaseInquiry\App\Models\DixlaseInquirySetting;
use Plugins\DixlaseInquiry\App\Models\DixlaseInquirySettingsAggregate;
use Tests\TestCase;

/**
 * Verifies the singleton aggregate model used to expose six inquiry
 * settings to DixlaseMultilingual's central translation manager.
 *
 * The aggregate model itself stores no business data — the primary value
 * for each translatable field lives in `plg_dixlase_inquiry_settings`,
 * and per-locale overrides live in DixlaseMultilingual's polymorphic
 * translations table when that plugin is installed. The aggregate exists
 * only so the polymorphic table has a stable `(translatable_type,
 * translatable_id)` to anchor against.
 */
class TranslatableSettingsAggregateTest extends TestCase
{
    use RefreshDatabase;

    private const EXPECTED_FIELDS = [
        'form_heading',
        'form_description',
        'completion_title',
        'completion_message',
        'auto_reply_subject',
        'auto_reply_body',
    ];

    /**
     * Ensure the plugin-owned tables exist for the DB-backed tests below.
     *
     * The test environment runs with `INSTALLED=false`, so the plugin's
     * service provider never boots and its `loadMigrationsFrom()` call is
     * never reached. As a result `RefreshDatabase`'s `migrate:fresh`
     * creates only the core tables, and `plg_dixlase_inquiry_settings` /
     * `plg_dixlase_inquiry_settings_aggregate` are missing.
     *
     * Apply the two plugin-owned migrations this test exercises by
     * invoking their `up()` directly — no console kernel, no service
     * providers, no side effects. The `Schema::hasTable()` guard makes
     * it a no-op on an installed environment, where `migrate:fresh`
     * already created the same tables.
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->ensurePluginTable(
            'plg_dixlase_inquiry_settings',
            '0001_01_01_000001_create_inquiry_settings_table.php',
        );
        $this->ensurePluginTable(
            'plg_dixlase_inquiry_settings_aggregate',
            '0001_01_01_000004_create_inquiry_settings_aggregate_table.php',
        );
    }

    /**
     * Run a plugin migration's `up()` unless its table already exists.
     *
     * `require` (not `require_once`) is used so the call returns the
     * migration's anonymous-class instance even when the file has been
     * loaded by an earlier test in the same run.
     */
    private function ensurePluginTable(string $table, string $migrationFile): void
    {
        if (Schema::hasTable($table)) {
            return;
        }

        (require __DIR__.'/../../database/migrations/'.$migrationFile)->up();
    }

    public function test_aggregate_declares_exactly_the_six_translatable_fields(): void
    {
        $aggregate = new DixlaseInquirySettingsAggregate;

        $this->assertSame(
            self::EXPECTED_FIELDS,
            $aggregate->getTranslatableFields(),
            'Aggregate translatable list must match the design memo scope: '
            .'form/completion text + auto-reply email — admin-notification '
            .'subject/body are intentionally excluded.',
        );
    }

    public function test_aggregate_uses_the_expected_table_name(): void
    {
        $this->assertSame(
            'plg_dixlase_inquiry_settings_aggregate',
            (new DixlaseInquirySettingsAggregate)->getTable(),
        );
    }

    public function test_get_original_value_reads_from_inquiry_settings_table(): void
    {
        DixlaseInquirySetting::set('form_heading', 'プライマリ見出し');

        $aggregate = DixlaseInquirySettingsAggregate::query()->firstOrCreate(['id' => 1]);

        // Without a TranslationResolver bound, getTranslation() falls back
        // to getOriginalValue() — which we override to read from the
        // key-value settings table rather than from this aggregate's
        // (intentionally empty) own columns.
        $this->assertSame('プライマリ見出し', $aggregate->getTranslation('form_heading'));
    }

    public function test_get_translation_returns_resolver_value_when_bound(): void
    {
        DixlaseInquirySetting::set('form_heading', 'プライマリ見出し');

        $this->bindResolverReturning('form_heading', 'ja', '日本語見出し');

        $aggregate = DixlaseInquirySettingsAggregate::query()->firstOrCreate(['id' => 1]);
        app()->setLocale('ja');

        $this->assertSame(
            '日本語見出し',
            $aggregate->getTranslation('form_heading'),
            'When a TranslationResolver is bound and returns a value for '
            .'the current locale, the aggregate must surface that value '
            .'rather than the primary-locale fallback.',
        );
    }

    public function test_get_translation_falls_back_to_primary_when_locale_missing(): void
    {
        DixlaseInquirySetting::set('form_heading', 'プライマリ見出し');

        // Resolver returns null for the requested locale → trait falls
        // through to the fallback chain ending at getOriginalValue().
        $this->bindResolverReturning('form_heading', 'en', null);

        $aggregate = DixlaseInquirySettingsAggregate::query()->firstOrCreate(['id' => 1]);
        app()->setLocale('en');

        $this->assertSame('プライマリ見出し', $aggregate->getTranslation('form_heading'));
    }

    private function bindResolverReturning(string $field, string $locale, ?string $value): void
    {
        $resolver = new class($field, $locale, $value) implements TranslationResolver
        {
            public function __construct(
                private readonly string $expectedField,
                private readonly string $expectedLocale,
                private readonly ?string $value,
            ) {}

            public function resolve(Model $model, string $field, string $locale): mixed
            {
                if ($field === $this->expectedField && $locale === $this->expectedLocale) {
                    return $this->value;
                }

                return null;
            }

            public function store(Model $model, string $field, mixed $value, string $locale): void {}

            public function all(Model $model, string $field): array
            {
                return [];
            }

            public function exists(Model $model, string $field, string $locale): bool
            {
                return false;
            }

            public function delete(Model $model, string $field, ?string $locale = null): void {}

            public function getAvailableLocales(Model $model): array
            {
                return [];
            }

            public function copy(Model $source, Model $target, ?array $fields = null, ?array $locales = null): void {}
        };

        app()->instance(TranslationResolver::class, $resolver);
    }
}
