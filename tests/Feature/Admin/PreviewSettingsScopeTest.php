<?php

/**
 * This file is part of DixlaseInquiry.
 *
 * Copyright (C) 2026 exc-D inc. and Dixlase contributors
 * https://exc-d.com
 *
 * DixlaseInquiry is dual-licensed. You may use this file under either:
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

namespace Plugins\DixlaseInquiry\Tests\Feature\Admin;

use App\Enums\MemberRole;
use App\Models\Member;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Mail;
use Plugins\DixlaseInquiry\App\Http\Controllers\Admin\DixlaseInquiryAdminController;
use Plugins\DixlaseInquiry\App\Http\Requests\Admin\DixlaseInquiryFormBasicPreviewRequest;
use Plugins\DixlaseInquiry\App\Http\Requests\Admin\DixlaseInquiryFormBasicRequest;
use Plugins\DixlaseInquiry\App\Mail\DixlaseInquiryAdminNotification;
use Plugins\DixlaseInquiry\App\Models\DixlaseInquirySetting;
use Tests\TestCase;

/**
 * What the form-basic preview may carry, and where the preview mail goes.
 *
 * The preview lets an administrator see the public form the settings on
 * that page produce before saving them. These tests pin what it accepts,
 * what the merge carries into the previewed settings, and that the mail
 * the preview sends is the mail the site sends.
 *
 * The controller actions are invoked directly rather than over HTTP, for
 * the reason AdminAuditLogTest records: the plugin's admin routes are
 * auto-registered with their full middleware stack and a test's manual
 * registration cannot deregister them, so an HTTP POST bounces.
 */
class PreviewSettingsScopeTest extends TestCase
{
    use RefreshDatabase;

    private Member $admin;

    /** Snapshot of INSTALLED before setUp; see AdminGetRoutesSmokeTest. */
    private ?string $installedEnvBefore = null;

    private ?string $installedServerBefore = null;

    protected function setUp(): void
    {
        parent::setUp();

        $this->installedEnvBefore = array_key_exists('INSTALLED', $_ENV) ? (string) $_ENV['INSTALLED'] : null;
        $this->installedServerBefore = array_key_exists('INSTALLED', $_SERVER) ? (string) $_SERVER['INSTALLED'] : null;

        $_ENV['INSTALLED'] = 'true';
        $_SERVER['INSTALLED'] = 'true';

        Artisan::call('migrate', [
            '--path' => 'plugins/DixlaseInquiry/database/migrations',
            '--realpath' => false,
        ]);

        $this->app['view']->addNamespace('dixlase-inquiry', base_path('plugins/DixlaseInquiry/resources/views'));
        $this->app['translator']->addNamespace('dixlase-inquiry', base_path('plugins/DixlaseInquiry/lang'));

        $this->admin = Member::factory()->create(['role' => MemberRole::ADMIN]);
    }

    protected function tearDown(): void
    {
        if ($this->installedEnvBefore === null) {
            unset($_ENV['INSTALLED']);
        } else {
            $_ENV['INSTALLED'] = $this->installedEnvBefore;
        }
        if ($this->installedServerBefore === null) {
            unset($_SERVER['INSTALLED']);
        } else {
            $_SERVER['INSTALLED'] = $this->installedServerBefore;
        }
        parent::tearDown();
    }

    /**
     * A validated preview request, built the way the preview button posts.
     *
     * @param  array<string, mixed>  $payload
     */
    private function previewRequest(array $payload): DixlaseInquiryFormBasicPreviewRequest
    {
        $request = DixlaseInquiryFormBasicPreviewRequest::create('/preview', 'POST', $payload);
        $request->setContainer($this->app);
        $request->setRedirector($this->app['redirect']);
        $request->setLaravelSession($this->app['session.store']);
        $request->validateResolved();

        return $request;
    }

    /**
     * The minimum the page's rules require.
     *
     * @return array<string, mixed>
     */
    private function validPayload(array $extra = []): array
    {
        return array_merge([
            'lang' => 'auto',
            'name_order_western' => 'auto',
        ], $extra);
    }

    public function test_a_setting_from_another_page_does_not_survive_the_preview_request(): void
    {
        $request = $this->previewRequest($this->validPayload([
            'admin_email' => 'elsewhere@example.test',
            'auto_reply_enabled' => '1',
            'store_inquiries' => '1',
            'retention_days' => '1',
        ]));

        $validated = $request->validated();

        foreach (['admin_email', 'auto_reply_enabled', 'store_inquiries', 'retention_days'] as $key) {
            $this->assertArrayNotHasKey($key, $validated, "{$key} belongs to another settings page and must not pass through the preview.");
        }

        // ...and the page's own fields still do.
        $this->assertSame('auto', $validated['lang']);
    }

    public function test_the_booleans_the_preview_button_sends_keep_their_value(): void
    {
        // The button builds its request from Alpine state, so these
        // arrive as strings. `(bool) 'false'` is true, which is why the
        // preview cannot reuse the save path's cast.
        $request = $this->previewRequest($this->validPayload([
            'show_subject' => 'true',
            'show_phone' => 'false',
            'show_kana' => 'true',
        ]));

        $validated = $request->validated();

        $this->assertTrue($validated['show_subject']);
        $this->assertFalse($validated['show_phone'], 'A toggle switched off in the page must stay off in the preview.');
        $this->assertTrue($validated['show_kana']);
        // Absent means off, as on the save path.
        $this->assertFalse($validated['show_address']);
    }

    public function test_the_preview_merge_ignores_a_key_outside_the_page(): void
    {
        DixlaseInquirySetting::updateSettings(['admin_email' => 'stored@example.test']);

        // A session written by a version that accepted anything.
        session(['inquiry_preview_settings' => [
            'form_heading' => 'Preview heading',
            'admin_email' => 'elsewhere@example.test',
        ]]);

        $controller = app(DixlaseInquiryAdminController::class);
        $method = new \ReflectionMethod($controller, 'getPreviewSettings');
        $settings = $method->invoke($controller);

        $this->assertSame('Preview heading', $settings->form_heading, 'The page\'s own field is previewed.');
        $this->assertSame('stored@example.test', $settings->admin_email, 'A key outside the page must not be merged.');
    }

    public function test_the_preview_mail_goes_to_the_stored_recipient(): void
    {
        Mail::fake();

        DixlaseInquirySetting::updateSettings([
            'admin_email' => 'stored@example.test',
            'auto_reply_enabled' => false,
        ]);

        session(['inquiry_preview_settings' => ['admin_email' => 'elsewhere@example.test']]);

        $this->actingAs($this->admin, 'member');

        $request = \Illuminate\Http\Request::create('/preview/send', 'POST', [
            'name' => 'Preview Visitor',
            'email' => 'visitor@example.test',
            'message' => 'Preview body',
            '_preview_send_email' => '1',
        ]);
        $request->setLaravelSession($this->app['session.store']);

        app(DixlaseInquiryAdminController::class)->previewSend($request);

        Mail::assertSent(DixlaseInquiryAdminNotification::class, function ($mail) {
            return $mail->hasTo('stored@example.test');
        });
        Mail::assertNotSent(DixlaseInquiryAdminNotification::class, function ($mail) {
            return $mail->hasTo('elsewhere@example.test');
        });
    }

    public function test_the_preview_accepts_exactly_the_fields_the_button_sends(): void
    {
        // If a field is added to the button without a rule, it is dropped
        // silently and the preview stops reflecting it; if a rule is added
        // without the button, the rule is dead. Pin the two together.
        $blade = (string) file_get_contents(
            base_path('plugins/DixlaseInquiry/resources/views/admin/inquiry/settings/form-basic.blade.php')
        );

        $start = strpos($blade, 'const settings = {');
        $this->assertNotFalse($start, 'The preview button builds its payload from a `const settings = {...}` literal.');
        $end = strpos($blade, '};', $start);
        $posted = [];
        preg_match_all('/^\s{16}([a-z0-9_]+):/m', substr($blade, $start, $end - $start), $m);
        $posted = $m[1];

        $ruleKeys = array_keys((new DixlaseInquiryFormBasicRequest())->rules());

        sort($posted);
        sort($ruleKeys);
        $this->assertSame($ruleKeys, $posted);
    }

    public function test_the_two_requests_share_one_boolean_list(): void
    {
        $this->assertNotEmpty(DixlaseInquiryFormBasicRequest::BOOLEAN_FIELDS);
        $this->assertSame(
            DixlaseInquiryFormBasicRequest::BOOLEAN_FIELDS,
            DixlaseInquiryFormBasicPreviewRequest::BOOLEAN_FIELDS,
            'The preview request normalizes the same fields the save path declares.'
        );
    }
}
