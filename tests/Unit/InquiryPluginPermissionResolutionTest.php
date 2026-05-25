<?php

/**
 * This file is part of Dixlase Inquiry.
 *
 * Copyright (C) 2026 exc-D inc.
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

namespace Plugins\DixlaseInquiry\Tests\Unit;

use App\Enums\MemberRole;
use App\Services\PermissionRegistry;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * End-to-end check that core's PermissionRegistry can resolve each
 * inquiry menu_key from the plugin's `config/admin/roles.php` and that the
 * resulting defaults match the expectation declared in the plugin
 * (ADMIN for both view and access).
 *
 * Whereas `RolePermissionConfigTest` is a pure file-level assertion
 * (no Laravel container), this test exercises the actual code path
 * the sidebar and controller use.
 */
class InquiryPluginPermissionResolutionTest extends TestCase
{
    use RefreshDatabase;

    /**
     * PermissionRegistry resolves `plugins/{slug}/config/admin/roles.php` from
     * the directory basename, so the slug must be the PascalCase
     * directory name, not the kebab-case `slug` from plugin.json.
     */
    private const PLUGIN_SLUG = 'DixlaseInquiry';

    private const MENU_KEYS = [
        'inquiry.index',
        'inquiry.settings.index',
        'inquiry.settings.form-basic',
        'inquiry.settings.completion',
        'inquiry.settings.admin-notification',
        'inquiry.settings.auto-reply',
    ];

    protected function setUp(): void
    {
        parent::setUp();

        // Pin the cache to the in-memory array store so PermissionRegistry's
        // internal `Cache::remember()` calls don't try to hit a `dls_cache`
        // database table the unit-test schema doesn't migrate.
        config(['cache.default' => 'array']);
    }

    public function test_each_menu_key_resolves_to_admin_default(): void
    {
        foreach (self::MENU_KEYS as $menuKey) {
            $effective = PermissionRegistry::getPluginEffective(self::PLUGIN_SLUG, $menuKey);

            $this->assertNotNull($effective, "PermissionRegistry returned null for {$menuKey}");
            $this->assertSame(MemberRole::ADMIN->value, $effective['access_roles'], "access_roles default for {$menuKey}");
            $this->assertSame(MemberRole::ADMIN->value, $effective['view_roles'], "view_roles default for {$menuKey}");
            $this->assertFalse($effective['is_overridden'], "is_overridden should be false for {$menuKey} with no DB row");
        }
    }

    public function test_admin_role_can_access_all_inquiry_menus_by_default(): void
    {
        foreach (self::MENU_KEYS as $menuKey) {
            $this->assertTrue(
                PermissionRegistry::canAccessPlugin(self::PLUGIN_SLUG, $menuKey, MemberRole::ADMIN),
                "ADMIN should have access to {$menuKey} by default",
            );
            $this->assertTrue(
                PermissionRegistry::canViewPlugin(self::PLUGIN_SLUG, $menuKey, MemberRole::ADMIN),
                "ADMIN should be able to view {$menuKey} by default",
            );
        }
    }

    public function test_editor_role_is_denied_by_default(): void
    {
        // Defaults are ADMIN; EDITOR (8) is below ADMIN (9) so should be
        // denied. Operators may grant EDITOR access via the admin UI,
        // which writes an override — that branch is covered by core's
        // RolePermissionOverride tests.
        foreach (self::MENU_KEYS as $menuKey) {
            $this->assertFalse(
                PermissionRegistry::canAccessPlugin(self::PLUGIN_SLUG, $menuKey, MemberRole::EDITOR),
                "EDITOR should NOT have access to {$menuKey} by default",
            );
        }
    }

    public function test_super_admin_bypasses_all_checks(): void
    {
        foreach (self::MENU_KEYS as $menuKey) {
            $this->assertTrue(
                PermissionRegistry::canAccessPlugin(self::PLUGIN_SLUG, $menuKey, MemberRole::SUPER_ADMIN),
                "SUPER_ADMIN must have access to {$menuKey}",
            );
        }
    }

    public function test_flat_enumeration_includes_all_menu_keys(): void
    {
        $flat = PermissionRegistry::getAllPluginPermissionsFlat(self::PLUGIN_SLUG);

        foreach (self::MENU_KEYS as $menuKey) {
            $this->assertArrayHasKey(
                $menuKey,
                $flat,
                "Flat enumeration must include {$menuKey} so the admin UI renders a toggle row",
            );
        }
    }
}
