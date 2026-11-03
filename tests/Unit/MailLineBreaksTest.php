<?php

/**
 * This file is part of the Dixlase Inquiry plugin.
 *
 * Copyright (C) 2026 exc-D inc. and Dixlase contributors
 * https://exc-d.com
 */

namespace Plugins\DixlaseInquiry\Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Plugins\DixlaseInquiry\App\Mail\DixlaseInquiryAdminNotification;
use Plugins\DixlaseInquiry\App\Mail\DixlaseInquiryAutoReply;
use Plugins\DixlaseInquiry\App\Models\DixlaseInquirySetting;
use Plugins\DixlaseInquiry\App\Support\MailMarkdown;
use Tests\TestCase;

/**
 * The message keeps the line breaks the visitor typed.
 *
 * escape() leaves newlines alone, and a bare newline in Markdown is a soft
 * break, so a message written in paragraphs arrived as one run-on line in
 * the HTML part of both mails (#94). escapeBlock() turns each newline into
 * a hard break; the single-line fields stay on escape().
 */
class MailLineBreaksTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->artisan('migrate', [
            '--path' => base_path('plugins/DixlaseInquiry/database/migrations'),
            '--realpath' => true,
        ]);

        $this->app['translator']->addNamespace('dixlase-inquiry', dirname(__DIR__, 2).'/lang');

        // Resolve the views from this repository rather than through
        // base_path(), so the test exercises the templates it ships with
        // even when the checkout is not the one installed under plugins/.
        $this->app['view']->addNamespace('dixlase-inquiry', dirname(__DIR__, 2).'/resources/views');
    }

    public function test_a_newline_becomes_a_hard_break(): void
    {
        $this->assertSame("one  \ntwo", MailMarkdown::escapeBlock("one\ntwo"));
    }

    public function test_windows_line_endings_produce_one_break_each(): void
    {
        $this->assertSame("one  \ntwo", MailMarkdown::escapeBlock("one\r\ntwo"));
        $this->assertSame("one  \ntwo", MailMarkdown::escapeBlock("one\rtwo"));
    }

    public function test_a_blank_line_still_separates_paragraphs(): void
    {
        // The two spaces left on the empty line do not make it non-blank
        // for Markdown, so the paragraph break survives.
        $this->assertSame("one  \n  \ntwo", MailMarkdown::escapeBlock("one\n\ntwo"));
    }

    public function test_markdown_is_still_escaped(): void
    {
        $this->assertSame(
            "\\[Reset\\]\\(https://evil.example\\)  \nplain",
            MailMarkdown::escapeBlock("[Reset](https://evil.example)\nplain")
        );
        $this->assertSame('', MailMarkdown::escapeBlock(null));
    }

    public function test_single_line_fields_gain_no_trailing_break(): void
    {
        $this->assertSame('Visitor Name', MailMarkdown::escape('Visitor Name'));
    }

    public function test_both_mails_render_the_breaks(): void
    {
        $data = [
            'name' => 'Visitor',
            'email' => 'visitor@example.com',
            'subject' => 'Subject',
            'message' => "First line\nSecond line\n\nA new paragraph",
        ];
        $settings = DixlaseInquirySetting::getSettings();

        foreach ([new DixlaseInquiryAutoReply($data, $settings), new DixlaseInquiryAdminNotification($data, $settings)] as $mail) {
            $html = $mail->render();

            $this->assertMatchesRegularExpression(
                '/First line\s*<br\s*\/?>\s*Second line/i',
                $html,
                $mail::class.': the two lines were joined instead of broken.'
            );
            $this->assertStringContainsString('A new paragraph', $html, $mail::class);
        }
    }
}
