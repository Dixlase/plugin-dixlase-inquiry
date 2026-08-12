<?php

/**
 * This file is part of the Dixlase Inquiry plugin.
 *
 * Copyright (C) 2026 exc-D inc. and Dixlase contributors
 * https://exc-d.com
 */

namespace Plugins\DixlaseInquiry\Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Validator;
use PHPUnit\Framework\Attributes\DataProvider;
use Plugins\DixlaseInquiry\App\Http\Requests\Admin\DixlaseInquiryFormBasicRequest;
use Tests\TestCase;

/**
 * privacy_policy_url is interpolated into an href by
 *
 *     __('dixlase-inquiry::front.form.privacy_consent', ['url' => $privacyUrl])
 *
 * and __() does not escape its replacements. Measured before the fix:
 *
 *     url = " onmouseover="alert(1)
 *     -> <a href="" onmouseover="alert(1)" target="_blank" ...>プライバシーポリシー</a>
 *
 * The old rule accepted any value starting with '/' without further checking,
 * so `/policy" onmouseover="alert(1)` passed. That label renders on the public
 * contact form (form-fields.blade.php and embed-form.blade.php), not only in
 * the admin theme preview -- it reaches unauthenticated visitors.
 *
 * The custom-text path was already safe: those views wrap
 * privacy_consent_text in e(). Only the translation-key path was exposed,
 * which is the kind of split that makes a file look reviewed.
 */
class PrivacyPolicyUrlValidationTest extends TestCase
{
    use RefreshDatabase;

    private function passes(string $url): bool
    {
        $rules = (new DixlaseInquiryFormBasicRequest())->rules();

        return ! Validator::make(
            ['privacy_policy_url' => $url],
            ['privacy_policy_url' => $rules['privacy_policy_url']]
        )->fails();
    }

    /**
     * @return list<array{0: string}>
     */
    public static function dangerousUrls(): array
    {
        return [
            'attribute break via path' => ['/policy" onmouseover="alert(1)'],
            'attribute break at root' => ['/" onmouseover="alert(1)'],
            'single quote break' => ["/policy' onmouseover='alert(1)"],
            'tag injection' => ['/policy<script>alert(1)</script>'],
            'javascript scheme' => ['javascript:alert(1)'],
            // FILTER_VALIDATE_URL accepts this one, which is why the scheme is
            // checked separately.
            'javascript with slashes' => ['javascript://%0aalert(1)'],
            'protocol relative' => ['//evil.example.com'],
        ];
    }

    #[DataProvider('dangerousUrls')]
    public function test_dangerous_values_are_refused(string $url): void
    {
        $this->assertFalse(
            $this->passes($url),
            "privacy_policy_url is written into an href without escaping, so it must refuse: {$url}"
        );
    }

    /**
     * @return list<array{0: string}>
     */
    public static function legitimateUrls(): array
    {
        return [
            'site relative' => ['/privacy-policy'],
            'nested path' => ['/legal/privacy'],
            'https' => ['https://example.com/privacy'],
            'http' => ['http://example.com/privacy'],
            'with query' => ['https://example.com/privacy?lang=ja'],
            'empty' => [''],
        ];
    }

    #[DataProvider('legitimateUrls')]
    public function test_ordinary_urls_are_still_accepted(string $url): void
    {
        $this->assertTrue(
            $this->passes($url),
            "Operators set real policy URLs here; the rule must still accept: {$url}"
        );
    }
}
