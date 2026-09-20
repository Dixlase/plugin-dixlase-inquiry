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
}
