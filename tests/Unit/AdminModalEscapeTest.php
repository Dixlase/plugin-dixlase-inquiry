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
 * Core's <x-ui-modal> prints its message prop raw ({!! $message !!}). Inquiry
 * fields are typed by anonymous visitors, so any of them spliced into a modal
 * message has to be escaped first — otherwise a name such as
 * <img src=x onerror=...> runs in the admin's session as soon as the trash
 * list renders, because every row's modal is pushed onto the page up front.
 */
class AdminModalEscapeTest extends TestCase
{
    public function test_visitor_fields_are_escaped_in_modal_messages(): void
    {
        $views = glob(__DIR__.'/../../resources/views/admin/**/*.blade.php') ?: [];
        $this->assertNotEmpty($views, 'admin views are expected under resources/views/admin');

        $checked = 0;
        foreach ($views as $path) {
            $source = (string) file_get_contents($path);
            preg_match_all('/:message="[^"]*"/', $source, $matches);

            foreach ($matches[0] as $message) {
                if (! str_contains($message, '$inquiry->')) {
                    continue;
                }

                $checked++;
                $this->assertDoesNotMatchRegularExpression(
                    '/(?<!e\()\$inquiry->\w+/',
                    $message,
                    basename($path).' passes a visitor-supplied field into a raw modal message: '.$message
                );
            }
        }

        $this->assertGreaterThan(0, $checked, 'the trash view is expected to put the inquiry name in a modal message');
    }
}
