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
use App\Models\AuditLog;
use App\Models\Member;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Plugins\DixlaseInquiry\App\Http\Requests\Admin\DixlaseInquiryPrivacyRequest;
use Plugins\DixlaseInquiry\App\Models\DixlaseInquiry;
use Plugins\DixlaseInquiry\App\Models\DixlaseInquirySetting;
use Tests\TestCase;

/**
 * Audit entries for the plugin's administrator-policy actions.
 *
 * These tests assert *that* the entries are emitted and *what* their
 * shape looks like, with special attention to the privacy invariant:
 * no visitor PII (name / email / message / phone) is written to the
 * audit log. Storing those would re-create the data that
 * `store_inquiries=false` is designed never to keep.
 *
 * The suite exercises each operator-policy entry point:
 *   - `updatePrivacy`         (settings toggle + retention days)
 *   - `updateExpiresAt`       (per-row retention edit)
 *   - `restore`               (trash restore)
 *   - `forceDestroy`          (permanent single-row delete)
 *   - `emptyTrash`            (permanent bulk delete)
 *   - `dls:inquiry:prune`     (scheduled / manual retention sweep)
 *   - `recordMailFailure`     (admin-side mail delivery metadata)
 */
class AdminAuditLogTest extends TestCase
{
    use RefreshDatabase;

    private Member $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $_ENV['INSTALLED'] = 'true';
        $_SERVER['INSTALLED'] = 'true';

        $rawKey = random_bytes(32);
        config([
            'app.key' => 'base64:'.base64_encode($rawKey),
            'app.cipher' => 'AES-256-CBC',
        ]);
        $this->app->instance('encrypter', new \Illuminate\Encryption\Encrypter($rawKey, 'AES-256-CBC'));

        Artisan::call('migrate', [
            '--path' => 'plugins/DixlaseInquiry/database/migrations',
            '--realpath' => false,
        ]);

        $this->app['view']->addNamespace('dixlase-inquiry', base_path('plugins/DixlaseInquiry/resources/views'));
        $this->app['translator']->addNamespace('dixlase-inquiry', base_path('plugins/DixlaseInquiry/lang'));

        $adminUrl = (string) config('admin.admin_url', 'admin');
        $router = app('router');
        $router->group([
            'prefix' => $adminUrl,
            'middleware' => ['web', 'auth:member'],
        ], function () {
            include base_path('plugins/DixlaseInquiry/routes/admin.php');
        });
        $router->getRoutes()->refreshNameLookups();
        $router->getRoutes()->refreshActionLookups();

        $this->admin = Member::factory()->create(['role' => MemberRole::ADMIN]);
    }

    protected function tearDown(): void
    {
        $_ENV['INSTALLED'] = 'false';
        $_SERVER['INSTALLED'] = 'false';
        parent::tearDown();
    }

    public function test_update_privacy_emits_audit_entry(): void
    {
        // Call the controller action directly rather than through an HTTP
        // request. The HTTP route path hits a redirect loop in CI (the
        // PluginServiceProvider auto-loads the admin routes with their
        // full middleware stack, and the test's manual registration has
        // no way to deregister them first — a POST to this URL ends up
        // bouncing on the auto-loaded `admin.ip` layer before updatePrivacy
        // can run). Direct invocation of the controller is enough to prove
        // the audit entry: role-gating is covered by the permission config
        // tests, and the smoke test exercises the HTTP round trip.
        $this->actingAs($this->admin, 'member');

        $request = DixlaseInquiryPrivacyRequest::create(
            '/admin/inquiry/settings/privacy',
            'POST',
            [
                'store_inquiries' => '1',
                'retention_mode' => '180',
            ],
        );
        $request->setContainer(app())->setRedirector(app('redirect'));
        $request->validateResolved();

        $controller = app(\Plugins\DixlaseInquiry\App\Http\Controllers\Admin\DixlaseInquiryAdminController::class);
        $controller->updatePrivacy($request);

        $log = AuditLog::query()
            ->where('action', 'inquiry_settings_privacy_updated')
            ->firstOrFail();

        $this->assertSame('plugin', $log->category);
        $this->assertSame('dixlase-inquiry', $log->plugin_name);
        $this->assertSame($this->admin->id, (int) $log->actor_id);
        $this->assertSame(AuditLog::OUTCOME_SUCCESS, $log->outcome);
        $this->assertArrayHasKey('store_inquiries', $log->context);
        $this->assertArrayHasKey('retention_days', $log->context);
        $this->assertSame(['from' => false, 'to' => true], $log->context['store_inquiries']);
        $this->assertSame(180, $log->context['retention_days']['to']);
        $this->assertAuditContextHasNoPii($log->context);
    }

    public function test_update_expires_at_emits_audit_entry(): void
    {
        $inquiry = DixlaseInquiry::factory()->create(['expires_at' => now()->addDays(30)]);

        $this->actingAs($this->admin, 'member')
            ->patch("/admin/inquiry/{$inquiry->id}/expires-at", [
                'mode' => '365',
            ])
            ->assertRedirect();

        $log = AuditLog::query()
            ->where('action', 'inquiry_row_retention_updated')
            ->firstOrFail();

        $this->assertSame(DixlaseInquiry::class, $log->target_type);
        $this->assertSame($inquiry->id, (int) $log->target_id);
        $this->assertSame('365', $log->context['mode']);
        $this->assertArrayHasKey('expires_at', $log->context);
        $this->assertAuditContextHasNoPii($log->context);
    }

    public function test_restore_emits_audit_entry(): void
    {
        $inquiry = DixlaseInquiry::factory()->create();
        $inquiry->delete();

        $this->actingAs($this->admin, 'member')
            ->post("/admin/inquiry/trash/{$inquiry->id}/restore")
            ->assertRedirect();

        $log = AuditLog::query()
            ->where('action', 'inquiry_trash_item_restored')
            ->firstOrFail();

        $this->assertSame($inquiry->id, (int) $log->target_id);
        $this->assertSame($inquiry->id, $log->context['inquiry_id']);
        $this->assertAuditContextHasNoPii($log->context);
    }

    public function test_force_destroy_emits_warning_audit_entry(): void
    {
        $inquiry = DixlaseInquiry::factory()->create();
        $inquiry->delete();
        $destroyedId = $inquiry->id;

        $this->actingAs($this->admin, 'member')
            ->delete("/admin/inquiry/trash/{$inquiry->id}")
            ->assertRedirect();

        $log = AuditLog::query()
            ->where('action', 'inquiry_trash_item_force_destroyed')
            ->firstOrFail();

        $this->assertSame(AuditLog::SEVERITY_WARNING, $log->severity);
        $this->assertSame($destroyedId, $log->context['inquiry_id']);
        $this->assertAuditContextHasNoPii($log->context);
    }

    public function test_empty_trash_emits_warning_audit_entry(): void
    {
        DixlaseInquiry::factory()->count(3)->create()->each->delete();

        $this->actingAs($this->admin, 'member')
            ->post('/admin/inquiry/trash/empty')
            ->assertRedirect();

        $log = AuditLog::query()
            ->where('action', 'inquiry_trash_emptied')
            ->firstOrFail();

        $this->assertSame(AuditLog::SEVERITY_WARNING, $log->severity);
        $this->assertSame(3, $log->context['deleted_count']);
        $this->assertAuditContextHasNoPii($log->context);
    }

    public function test_prune_command_emits_audit_entry(): void
    {
        // The plugin ServiceProvider is what wires the Artisan command into
        // the kernel in production; the test harness has to do the same.
        $this->app[\Illuminate\Contracts\Console\Kernel::class]
            ->registerCommand(
                $this->app->make(\Plugins\DixlaseInquiry\App\Console\Commands\InquiryPrune::class),
            );

        DixlaseInquiry::factory()
            ->count(2)
            ->create(['expires_at' => now()->subDay()]);

        Artisan::call('dls:inquiry:prune');

        $log = AuditLog::query()
            ->where('action', 'inquiry_prune_executed')
            ->firstOrFail();

        $this->assertSame('dls:inquiry:prune', $log->target_label);
        $this->assertSame(2, $log->context['matched_count']);
        $this->assertSame(2, $log->context['deleted_count']);
        $this->assertFalse($log->context['soft_delete']);
        $this->assertFalse($log->context['dry_run']);
        $this->assertAuditContextHasNoPii($log->context);
    }

    public function test_mail_failure_emits_audit_entry_without_pii(): void
    {
        // Settings must allow storing so the failed-mail entry is reachable
        // the way the controllers reach it.
        DixlaseInquirySetting::set('store_inquiries', '1');

        $trait = new class
        {
            use \Plugins\DixlaseInquiry\App\Traits\InquiryFormDataTrait;

            public function expose(): void
            {
                $this->recordMailFailure(
                    'send',
                    'admin',
                    new \RuntimeException('smtp 550 for admin@example.com'),
                    \Plugins\DixlaseInquiry\App\Mail\DixlaseInquiryAdminNotification::class,
                );
            }
        };
        $trait->expose();

        $log = AuditLog::query()
            ->where('action', 'inquiry_mail_delivery_failed')
            ->firstOrFail();

        $this->assertSame(AuditLog::SEVERITY_WARNING, $log->severity);
        $this->assertSame(AuditLog::OUTCOME_FAILURE, $log->outcome);
        $this->assertSame('send', $log->context['send_context']);
        $this->assertSame('admin', $log->context['recipient_role']);
        $this->assertSame('DixlaseInquiryAdminNotification', $log->context['mailable']);
        $this->assertSame(\RuntimeException::class, $log->context['exception_class']);

        // Critical privacy check: the exception message contains an email
        // address, which must NOT reach the audit context.
        $this->assertAuditContextHasNoPii($log->context);
        $encoded = json_encode($log->context);
        $this->assertStringNotContainsString('admin@example.com', (string) $encoded);
        $this->assertStringNotContainsString('smtp 550', (string) $encoded);
    }

    /**
     * Guard the privacy invariant: no visitor PII in the context payload.
     *
     * Checks both exact-key presence and substring occurrence of values
     * that match PII-shaped patterns the plugin handles (email, name,
     * message body, phone).
     *
     * @param  array<string, mixed>  $context
     */
    private function assertAuditContextHasNoPii(array $context): void
    {
        foreach (['name', 'email', 'message', 'phone', 'address', 'subject', 'content', 'body'] as $forbiddenKey) {
            $this->assertArrayNotHasKey($forbiddenKey, $context, "Audit context must not contain '{$forbiddenKey}'");
        }

        $encoded = (string) json_encode($context);
        $this->assertDoesNotMatchRegularExpression('/[\w.-]+@[\w.-]+\.\w+/', $encoded, 'Audit context must not contain an email address');
    }
}
