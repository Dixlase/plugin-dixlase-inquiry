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

use App\Contracts\Site\SiteContextInterface;
use App\Models\Site;
use Mockery;
use Plugins\DixlaseInquiry\App\Support\InquiryLocaleSupport;
use Plugins\DixlaseMultilingual\App\Services\EnabledLocaleResolver;
use Tests\TestCase;

/**
 * Verifies the multilingual integration contract for the inquiry form's
 * `lang='auto'` resolution.
 *
 * The "multilingual active" branch is exercised by binding a mock
 * resolver; the "no multilingual" branch by binding none, so
 * `multilingualEnabled()` reports false.
 */
class InquiryLocaleSupportTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_explicit_locale_short_circuits_resolver(): void
    {
        // Even if the resolver would return something else, an explicit
        // setting wins.
        $this->bindResolverMock(['en'], 'fixed', 'en');

        app()->setLocale('en');
        $this->assertSame('ja', InquiryLocaleSupport::resolveFormLocale('ja'));

        app()->setLocale('ja');
        $this->assertSame('en', InquiryLocaleSupport::resolveFormLocale('en'));
    }

    public function test_auto_with_multilingual_follows_app_locale(): void
    {
        // With multilingual active, auto defers to app()->getLocale() —
        // the value the SetFrontLocale middleware has resolved through
        // URL → cookie → Accept-Language → multilingual default.
        $this->bindResolverMock(['ja', 'en'], 'auto', 'en');

        app()->setLocale('ja');
        $this->assertSame('ja', InquiryLocaleSupport::resolveFormLocale('auto'));

        app()->setLocale('en');
        $this->assertSame('en', InquiryLocaleSupport::resolveFormLocale('auto'));
    }

    public function test_auto_without_multilingual_uses_site_default(): void
    {
        // With no resolver bound, multilingual is disabled. 'auto' must
        // resolve to the site's default locale — never app()->getLocale()
        // — so a single-language site never renders the form in the
        // visitor's browser language.
        $siteContext = Mockery::mock(SiteContextInterface::class);
        $siteContext->shouldReceive('currentSite')
            ->andReturn(new Site(['primary_locale' => 'ja']));
        app()->instance(SiteContextInterface::class, $siteContext);

        app()->setLocale('en');

        $this->assertSame('ja', InquiryLocaleSupport::resolveFormLocale('auto'));
    }

    public function test_enabled_locales_reflect_resolver(): void
    {
        $this->bindResolverMock(['en']);
        $this->assertSame(['en'], InquiryLocaleSupport::enabledLocales());

        $this->bindResolverMock(['ja', 'en']);
        $this->assertSame(['ja', 'en'], InquiryLocaleSupport::enabledLocales());
    }

    public function test_resolved_default_locale_in_fixed_mode_returns_multilingual_fallback(): void
    {
        $this->bindResolverMock(['ja', 'en'], 'fixed', 'ja');
        $this->assertSame('ja', InquiryLocaleSupport::resolvedDefaultLocale());

        $this->bindResolverMock(['ja', 'en'], 'fixed', 'en');
        $this->assertSame('en', InquiryLocaleSupport::resolvedDefaultLocale());
    }

    public function test_resolved_default_locale_in_auto_mode_falls_back_to_site_default(): void
    {
        // In 'auto' mode, the multilingual plugin does not pin a fixed
        // default — the inquiry plugin then asks core for the site's
        // basic-settings primary locale (or app locale if site context
        // isn't bootstrapped, which is the case in this unit test).
        $this->bindResolverMock(['ja', 'en'], 'auto', 'en');

        app()->setLocale('en');
        $this->assertContains(
            InquiryLocaleSupport::resolvedDefaultLocale(),
            \App\Helpers\LocaleHelper::supportedLocales(),
        );
    }

    public function test_lang_select_options_always_lead_with_auto(): void
    {
        $this->bindResolverMock(['ja', 'en']);

        $options = InquiryLocaleSupport::langSelectOptions();
        $this->assertArrayHasKey('auto', $options);
        $this->assertSame('auto', array_key_first($options));
        $this->assertArrayHasKey('ja', $options);
        $this->assertArrayHasKey('en', $options);
    }

    public function test_multilingual_enabled_reports_true_when_resolver_bound(): void
    {
        $this->bindResolverMock(['ja', 'en']);
        $this->assertTrue(InquiryLocaleSupport::multilingualEnabled());
    }

    /**
     * Mock the multilingual plugin's EnabledLocaleResolver and bind it as
     * the singleton instance.
     *
     * @param  list<string>  $enabled
     */
    private function bindResolverMock(
        array $enabled,
        string $defaultMode = 'auto',
        string $fallback = 'en',
    ): void {
        $mock = Mockery::mock(EnabledLocaleResolver::class);
        $mock->shouldReceive('getEnabledLocales')->andReturn($enabled);
        $mock->shouldReceive('getDefaultLocaleMode')->andReturn($defaultMode);
        $mock->shouldReceive('getFallbackLocale')->andReturn($fallback);

        app()->instance(EnabledLocaleResolver::class, $mock);
    }
}
