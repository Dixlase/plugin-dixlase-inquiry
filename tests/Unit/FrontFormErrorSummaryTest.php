<?php

/**
 * This file is part of the Dixlase Inquiry plugin.
 *
 * Copyright (C) 2026 exc-D inc. and Dixlase contributors
 * https://exc-d.com
 */

namespace Plugins\DixlaseInquiry\Tests\Unit;

use Tests\TestCase;

/**
 * The normal form used to swallow every validation failure: a rejected
 * CAPTCHA token or a missing field sent the visitor back to the form with
 * nothing displayed, while the embed form listed the errors. Both views
 * must render the error summary.
 */
class FrontFormErrorSummaryTest extends TestCase
{
    public function test_both_front_forms_render_the_validation_error_summary(): void
    {
        foreach (['form', 'embed-form'] as $view) {
            $source = (string) file_get_contents(__DIR__.'/../../resources/views/front/inquiries/'.$view.'.blade.php');

            $this->assertStringContainsString('$errors->any()', $source, "{$view}.blade.php must check for validation errors.");
            $this->assertStringContainsString('$errors->all()', $source, "{$view}.blade.php must list every validation error.");
        }
    }

    /**
     * The embed form submits by fetch. Its failure handling has to read both
     * validation payload shapes core can return, hand the visitor a fresh
     * CAPTCHA token, and bring the error box into view — otherwise a rejected
     * token looks like "the form just came back".
     */
    public function test_embed_form_script_recovers_from_a_rejected_submission(): void
    {
        $source = (string) file_get_contents(__DIR__.'/../../resources/src/js/components/embed-form.js');

        $this->assertStringContainsString("'Accept': 'application/json'", $source);
        $this->assertStringContainsString('data.error.details', $source, 'must read the Dixlase API error envelope');
        $this->assertStringContainsString('data.errors', $source, 'must read the default Laravel validation payload');
        $this->assertStringContainsString('response.redirected', $source, 'a followed redirect is not a completed submission');
        $this->assertStringContainsString('resetCaptchaWidget()', $source);
        $this->assertStringContainsString('scrollToErrors()', $source);
        $this->assertSame(2, substr_count($source, 'this.scrollToErrors()'), 'both submit paths must scroll to the error box');
    }
}
