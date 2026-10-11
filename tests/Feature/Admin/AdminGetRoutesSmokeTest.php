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

namespace Plugins\DixlaseInquiry\Tests\Feature\Admin;

use App\Enums\MemberRole;
use App\Models\Member;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;
use Plugins\DixlaseInquiry\App\Models\DixlaseInquiry;
use Tests\TestCase;

/**
 * Admin GET smoke test.
 *
 * Opens every admin GET route this plugin registers and asserts the
 * server does not return 5xx, for both the admin and the editor role.
 *
 * This catches the class of release regression that produced the 0.3.0
 * incident: a controller / view / lang-key crash that only surfaces
 * during the real request lifecycle (middleware stack → controller →
 * view resolution → Blade compile). Unit tests on route config or
 * permissions do not fire that lifecycle, so a page can declare clean
 * and still crash on open.
 *
 * The test is deliberately narrow:
 *
 *   - **In scope**: status < 500 for every admin GET route under
 *     `dixlase-inquiry::admin.inquiry.*` for admin and editor roles.
 *   - **Out of scope**: 200 vs 403 role boundaries
 *     (RolePermissionConfigTest covers the declarative side); POST /
 *     PATCH / DELETE write flows; MySQL vs SQLite behaviour
 *     differences; CSP / inline-handler scanning.
 *
 * The route list is read from Route::getRoutes() by name prefix, so a
 * new admin GET route added later is picked up with no change here.
 */
class AdminGetRoutesSmokeTest extends TestCase
{
    use RefreshDatabase;

    /** Route-name prefix the plugin stamps on every admin route. */
    private const ROUTE_NAME_PREFIX = 'dixlase-inquiry::admin.inquiry.';

    private Member $admin;

    private Member $editor;

    private DixlaseInquiry $inquiry;

    private DixlaseInquiry $trashedInquiry;

    protected function setUp(): void
    {
        parent::setUp();

        $_ENV['INSTALLED'] = 'true';
        $_SERVER['INSTALLED'] = 'true';

        // The plugin's own migrations are not auto-applied in this harness.
        // Apply them explicitly so the controllers have schema to query.
        Artisan::call('migrate', [
            '--path' => 'plugins/DixlaseInquiry/database/migrations',
            '--realpath' => false,
        ]);

        // View + translator namespaces so @extends('dixlase-inquiry::...')
        // and __('dixlase-inquiry::...') resolve during the request lifecycle.
        $this->app['view']->addNamespace('dixlase-inquiry', base_path('plugins/DixlaseInquiry/resources/views'));
        $this->app['translator']->addNamespace('dixlase-inquiry', base_path('plugins/DixlaseInquiry/lang'));

        // Register the plugin's admin routes under the admin URL prefix with a
        // reduced middleware stack. Core's `web + auth:member` is enough to
        // reach every controller; stripping the IP / lockdown / activity-log
        // layers keeps the test deterministic without pulling in test
        // fixtures for each of them. plugin.admin.access is intentionally
        // left off here — role-gating is a separate assertion
        // (RolePermissionConfigTest) and this test only cares about 5xx.
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

        // Fixtures for `{id}` placeholders. Keep separate rows for the normal
        // show screen and the (currently zero but future-proof) trash GETs so
        // one test does not accidentally rely on the other's state.
        $this->admin = Member::factory()->create(['role' => MemberRole::ADMIN]);
        $this->editor = Member::factory()->create(['role' => MemberRole::EDITOR]);
        $this->inquiry = DixlaseInquiry::factory()->create();
        $this->trashedInquiry = DixlaseInquiry::factory()->create();
        $this->trashedInquiry->delete();
    }

    protected function tearDown(): void
    {
        $_ENV['INSTALLED'] = 'false';
        $_SERVER['INSTALLED'] = 'false';
        parent::tearDown();
    }

    public function test_no_admin_get_route_returns_5xx_for_admin_role(): void
    {
        $urls = $this->adminGetRouteUrls();

        foreach ($urls as $name => $url) {
            $response = $this->actingAs($this->admin, 'member')->get($url);

            $this->assertLessThan(
                500,
                $response->status(),
                "GET {$url} ({$name}) returned {$response->status()} as admin",
            );
        }
    }

    public function test_no_admin_get_route_returns_5xx_for_editor_role(): void
    {
        $urls = $this->adminGetRouteUrls();

        foreach ($urls as $name => $url) {
            $response = $this->actingAs($this->editor, 'member')->get($url);

            $this->assertLessThan(
                500,
                $response->status(),
                "GET {$url} ({$name}) returned {$response->status()} as editor",
            );
        }
    }

    /**
     * Discover every admin GET route the plugin registered and resolve
     * `{id}` placeholders against the seeded fixtures.
     *
     * @return array<string, string>  Map of route name to request URL.
     */
    private function adminGetRouteUrls(): array
    {
        $urls = [];
        foreach (Route::getRoutes() as $route) {
            $name = $route->getName();
            if ($name === null || ! str_starts_with($name, self::ROUTE_NAME_PREFIX)) {
                continue;
            }
            if (! in_array('GET', $route->methods(), true)) {
                continue;
            }

            $uri = $route->uri();
            if (str_contains($uri, '{id}')) {
                // Trash sub-tree uses the soft-deleted row so routes bound to
                // withTrashed() lookups can find it; everything else points
                // at the live row.
                $isTrash = str_contains($name, '::admin.inquiry.trash.');
                $uri = str_replace(
                    '{id}',
                    (string) ($isTrash ? $this->trashedInquiry->id : $this->inquiry->id),
                    $uri,
                );
            }
            $urls[$name] = '/'.ltrim($uri, '/');
        }

        $this->assertNotEmpty(
            $urls,
            'No admin GET routes were discovered; did routes/admin.php load correctly in setUp?',
        );

        return $urls;
    }
}
