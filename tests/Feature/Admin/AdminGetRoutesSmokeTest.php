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

declare(strict_types=1);

namespace Plugins\DixlaseInquiry\Tests\Feature\Admin;

use App\Enums\MemberRole;
use App\Models\Member;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;
use Plugins\DixlaseInquiry\App\Models\DixlaseInquiry;
use Tests\TestCase;

/**
 * Admin GET smoke test.
 *
 * Opens every admin GET route this plugin registers, and asserts the
 * role-specific status it should produce. Captures the class of release
 * regression that showed up in the Inquiry 0.3.0 incident (a controller
 * / view / lang-key crash that only surfaces during the real request
 * lifecycle: middleware stack → controller → view resolution → Blade
 * compile).
 *
 * In scope:
 *   - every GET route under `dixlase-inquiry::admin.inquiry.*`
 *   - **both** roles — ADMIN and EDITOR — with the status each should
 *     receive, not just "below 500"
 *   - the plugin admin middleware stack that gates by role
 *     (`plugin.admin.access`), so an editor hitting an admin-only route
 *     meets the gate rather than slipping past a reduced test stack
 *   - a route whose placeholder has no supplied value fails the test
 *     loudly instead of silently returning 404 on a literal path
 *   - a discovery pass that finds nothing fails the test
 *     (`assertContains` on anchor route names)
 *
 * Out of scope:
 *   - `admin.ip`, `admin.no-cache`, `member.active`, `verified`,
 *     `log.admin.activity`, `CheckLockdown` — those are core-side gates
 *     verified by core's own tests; wiring the full real stack in the
 *     plugin harness adds fixture surface without catching anything the
 *     plugin ships
 *   - POST / PATCH / DELETE write flows
 *   - MySQL vs SQLite behaviour differences
 *   - CSP / inline-handler scanning
 */
class AdminGetRoutesSmokeTest extends TestCase
{
    use RefreshDatabase;

    /** Route-name prefix the plugin stamps on every admin route. */
    private const ROUTE_NAME_PREFIX = 'dixlase-inquiry::admin.inquiry.';

    /**
     * Routes an EDITOR is expected to be denied on (plugin.admin.access
     * returns 403 for them). Everything else under the prefix returns
     * 200 for EDITOR. Mirror of config/admin/roles.php: ADMIN-tier
     * entries deny EDITOR; EDITOR-tier entries grant them.
     *
     * The admin-notification setting is `view_roles=ADMIN,
     * access_roles=SUPER_ADMIN`: ADMIN may VIEW (GET) the page but not
     * EDIT (POST), so ADMIN still gets 200 here while EDITOR is denied.
     */
    private const EDITOR_DENIED_ROUTES = [
        'dixlase-inquiry::admin.inquiry.settings.index',
        'dixlase-inquiry::admin.inquiry.settings.form-basic',
        'dixlase-inquiry::admin.inquiry.settings.form-basic.preview',
        'dixlase-inquiry::admin.inquiry.settings.completion',
        'dixlase-inquiry::admin.inquiry.settings.admin-notification',
        'dixlase-inquiry::admin.inquiry.settings.auto-reply',
        'dixlase-inquiry::admin.inquiry.settings.privacy',
    ];

    private DixlaseInquiry $inquiry;

    private DixlaseInquiry $trashedInquiry;

    private string $themeDirectory;

    /** Snapshot of INSTALLED before setUp; see #64. */
    private ?string $installedEnvBefore = null;

    private ?string $installedServerBefore = null;

    protected function setUp(): void
    {
        parent::setUp();

        $this->installedEnvBefore = array_key_exists('INSTALLED', $_ENV) ? (string) $_ENV['INSTALLED'] : null;
        $this->installedServerBefore = array_key_exists('INSTALLED', $_SERVER) ? (string) $_SERVER['INSTALLED'] : null;
        $_ENV['INSTALLED'] = 'true';
        $_SERVER['INSTALLED'] = 'true';

        // Pin a valid 32-byte AES-256-CBC key regardless of what the
        // surrounding harness supplied; the plugin CI workflow injects
        // an APP_KEY whose base64_decode is not 32 bytes, so AES-256-CBC
        // rejects it with "Unsupported cipher or incorrect key length".
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

        // Preview-mode front views extend `themes::layouts.app`. Production
        // supplies that through the active theme; the test harness does
        // not. A bare layout in a throwaway dir stands in; tearDown
        // removes it.
        $this->themeDirectory = storage_path('framework/testing/inquiry-admin-theme-'.uniqid());
        File::ensureDirectoryExists($this->themeDirectory.'/layouts');
        File::put(
            $this->themeDirectory.'/layouts/app.blade.php',
            '<!DOCTYPE html><html><head><title>test-theme-stub</title></head><body>@yield(\'content\')</body></html>',
        );
        View::addNamespace('themes', [$this->themeDirectory]);

        // Register the plugin's admin routes with the role-gating
        // middleware in the stack. web + auth:member supply session and
        // authentication; plugin.admin.access supplies the role gate
        // the Inquiry 0.3.0 class of regression would land in. We
        // intentionally do NOT include admin.ip / admin.no-cache /
        // member.active / verified / log.admin.activity /
        // CheckLockdown — those are core-side gates covered by core
        // tests, and the plugin never crashes inside them.
        $adminUrl = (string) config('admin.admin_url', 'admin');
        $router = app('router');
        $router->group([
            'prefix' => $adminUrl,
            'middleware' => ['web', 'auth:member', 'plugin.admin.access:DixlaseInquiry'],
        ], function () {
            include base_path('plugins/DixlaseInquiry/routes/admin.php');
        });
        $router->getRoutes()->refreshNameLookups();
        $router->getRoutes()->refreshActionLookups();

        $this->inquiry = DixlaseInquiry::factory()->create();
        $this->trashedInquiry = DixlaseInquiry::factory()->create();
        $this->trashedInquiry->delete();
    }

    protected function tearDown(): void
    {
        if (isset($this->themeDirectory) && File::isDirectory($this->themeDirectory)) {
            File::deleteDirectory($this->themeDirectory);
        }
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

    public function test_every_admin_get_route_opens_for_admin(): void
    {
        $this->assertScreensOpenFor(MemberRole::ADMIN);
    }

    public function test_every_admin_get_route_opens_for_editor(): void
    {
        $this->assertScreensOpenFor(MemberRole::EDITOR);
    }

    private function assertScreensOpenFor(MemberRole $role): void
    {
        $member = Member::factory()->create(['role' => $role]);
        $this->actingAs($member, 'member');

        $opened = [];
        $failures = [];

        foreach (Route::getRoutes()->getRoutesByName() as $name => $route) {
            if (! str_starts_with($name, self::ROUTE_NAME_PREFIX)) {
                continue;
            }
            if (! in_array('GET', $route->methods(), true)) {
                continue;
            }

            $expectedStatus = $this->expectedStatusFor($role, $name);
            $response = $this->get(route($name, $this->parametersFor($route, $name)));
            $opened[] = $name;

            $actual = $response->baseResponse->getStatusCode();
            if ($actual !== $expectedStatus) {
                $failures[] = sprintf(
                    '%s -> expected %d, got %d%s',
                    $name,
                    $expectedStatus,
                    $actual,
                    $response->exception
                        ? ' ('.get_class($response->exception).': '.$response->exception->getMessage().')'
                        : '',
                );
            }
        }

        // A discovery pass that finds nothing would otherwise return a
        // green test. Pin a handful of anchor routes that must show up.
        $this->assertContains('dixlase-inquiry::admin.inquiry.index', $opened);
        $this->assertContains('dixlase-inquiry::admin.inquiry.settings.index', $opened);
        $this->assertSame([], $failures, implode("\n", $failures));
    }

    /**
     * Expected HTTP status for `$role` on route `$name`.
     *
     * ADMIN sees every admin GET route (200). EDITOR is denied by
     * plugin.admin.access on the ADMIN-tier settings routes
     * (listed in EDITOR_DENIED_ROUTES); everything else returns 200.
     */
    private function expectedStatusFor(MemberRole $role, string $name): int
    {
        if ($role === MemberRole::EDITOR && in_array($name, self::EDITOR_DENIED_ROUTES, true)) {
            return 403;
        }

        return 200;
    }

    /**
     * Resolve route parameters generically from `parameterNames()`.
     *
     * A placeholder without a supplied test value fails the test loudly
     * instead of letting a literal `{slug}` go out on the wire and
     * return 404.
     *
     * `{id}` is context-sensitive: trash routes point at the soft-
     * deleted row (so withTrashed() lookups find it); everything else
     * points at the live row.
     *
     * @return array<string, int|string>
     */
    private function parametersFor(\Illuminate\Routing\Route $route, string $name): array
    {
        $isTrash = str_contains($name, '::admin.inquiry.trash.');

        $values = [
            'id' => $isTrash ? $this->trashedInquiry->id : $this->inquiry->id,
        ];

        $parameters = [];
        foreach ($route->parameterNames() as $parameter) {
            $this->assertArrayHasKey(
                $parameter,
                $values,
                "No test value supplied for route parameter {{$parameter}} on {$name}. ".
                'Extend AdminGetRoutesSmokeTest::parametersFor() so a new placeholder cannot slip through as a literal path segment.',
            );
            $parameters[$parameter] = $values[$parameter];
        }

        return $parameters;
    }
}
