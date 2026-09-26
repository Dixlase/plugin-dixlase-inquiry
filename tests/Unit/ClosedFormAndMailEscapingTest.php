<?php

/**
 * This file is part of the Dixlase Inquiry plugin.
 *
 * Copyright (C) 2026 exc-D inc. and Dixlase contributors
 * https://exc-d.com
 */

namespace Plugins\DixlaseInquiry\Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Plugins\DixlaseInquiry\App\Http\Controllers\Front\DixlaseInquiryFrontController;
use Plugins\DixlaseInquiry\App\Http\Requests\DixlaseInquirySubmitRequest;
use Plugins\DixlaseInquiry\App\Mail\DixlaseInquiryAdminNotification;
use Plugins\DixlaseInquiry\App\Mail\DixlaseInquiryAutoReply;
use Plugins\DixlaseInquiry\App\Models\DixlaseInquirySetting;
use Plugins\DixlaseInquiry\App\Support\MailMarkdown;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Tests\TestCase;

/**
 * Closing the form must close the submission endpoints too, and visitor text
 * must not become live Markdown (links, images) in the mails the site sends.
 * The auto-reply, which mails any address a visitor types in, starts off.
 */
class ClosedFormAndMailEscapingTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->artisan('migrate', [
            '--path' => base_path('plugins/DixlaseInquiry/database/migrations'),
            '--realpath' => true,
        ]);

        $this->app['translator']->addNamespace('dixlase-inquiry', base_path('plugins/DixlaseInquiry/lang'));
        $this->app['view']->addNamespace('dixlase-inquiry', base_path('plugins/DixlaseInquiry/resources/views'));
    }

    public function test_send_is_refused_while_the_form_is_closed(): void
    {
        DixlaseInquirySetting::set('accepting_inquiries', '0');
        DixlaseInquirySetting::set('use_single_page', '0');

        $this->expectException(NotFoundHttpException::class);

        (new DixlaseInquiryFrontController())->send(DixlaseInquirySubmitRequest::create('/inquiry/send', 'POST'));
    }

    public function test_confirm_is_refused_while_the_form_is_closed(): void
    {
        DixlaseInquirySetting::set('accepting_inquiries', '0');
        DixlaseInquirySetting::set('use_single_page', '0');
        DixlaseInquirySetting::set('show_confirmation_page', '1');

        $this->expectException(NotFoundHttpException::class);

        (new DixlaseInquiryFrontController())->confirm(\Illuminate\Http\Request::create('/inquiry/confirm', 'POST'));
    }

    public function test_the_auto_reply_is_off_by_default(): void
    {
        $this->assertFalse(DixlaseInquirySetting::getDefaultSettings()->auto_reply_enabled);
    }

    public function test_markdown_syntax_is_escaped(): void
    {
        $this->assertSame(
            '\\[Reset\\]\\(https://evil.example\\) \\!\\[i\\]\\(x\\) \\*b\\* a\\_b',
            MailMarkdown::escape('[Reset](https://evil.example) ![i](x) *b* a_b')
        );
        // Characters Blade turns into entities are left for Blade.
        $this->assertSame('<a> & "q"', MailMarkdown::escape('<a> & "q"'));
        $this->assertSame('', MailMarkdown::escape(null));
    }

    public function test_visitor_links_do_not_become_links_in_either_mail(): void
    {
        $data = [
            'name' => '[Admin](https://evil.example/n)',
            'email' => 'visitor@example.com',
            'subject' => '![x](https://evil.example/i.png)',
            'message' => "Hello\n\n[Reset your password](https://evil.example/reset)",
        ];
        $settings = DixlaseInquirySetting::getSettings();

        foreach ([new DixlaseInquiryAutoReply($data, $settings), new DixlaseInquiryAdminNotification($data, $settings)] as $mail) {
            $html = $mail->render();

            $this->assertStringNotContainsString('href="https://evil.example', $html, $mail::class);
            $this->assertStringNotContainsString('src="https://evil.example', $html, $mail::class);
            $this->assertStringContainsString('Reset your password', $html, $mail::class);
        }
    }
}
