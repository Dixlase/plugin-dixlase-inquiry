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
        'inquiry.settings.index',
        'inquiry.settings.form-basic',
        'inquiry.settings.completion',
        'inquiry.settings.admin-notification',
        'inquiry.settings.auto-reply',
    ];

    /**
     * Menu keys whose EDIT (`access_roles`) default is SUPER_ADMIN
     * instead of the plain ADMIN default the rest of the plugin ships.
     * See `config/admin/roles.php` for the rationale — currently only
     * `admin-notification`, which routes visitor PII to a configurable
     * recipient address.
     */
    /**
     * Handling an inquiry -- opening the list, reading one, moving it
     * through its statuses -- is delegated work, so these resolve to
     * EDITOR. Deleting (`inquiry.destroy`, no SoftDeletes behind it) and
     * closing the public form (`inquiry.toggle-accepting`) are not part of
     * handling and stay at ADMIN; they are covered by
     * test_handling_is_editor_but_destructive_actions_are_not below.
     */
    private const EDITOR_MENU_KEYS = [
        'inquiry.index',
        'inquiry.show',
        'inquiry.status.update',
        'inquiry.bulk-status',
    ];

    private const SUPER_ADMIN_EDIT_MENU_KEYS = [
        'inquiry.settings.admin-notification',
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
            if (in_array($menuKey, self::SUPER_ADMIN_EDIT_MENU_KEYS, true)) {
                // Covered by test_super_admin_edit_menu_keys_resolve_to_super_admin.
                continue;
            }

            $effective = PermissionRegistry::getPluginEffective(self::PLUGIN_SLUG, $menuKey);

            $this->assertNotNull($effective, "PermissionRegistry returned null for {$menuKey}");
            $this->assertSame(MemberRole::ADMIN->value, $effective['access_roles'], "access_roles default for {$menuKey}");
            $this->assertSame(MemberRole::ADMIN->value, $effective['view_roles'], "view_roles default for {$menuKey}");
            $this->assertFalse($effective['is_overridden'], "is_overridden should be false for {$menuKey} with no DB row");
        }
    }

    /**
     * Mirror of `test_each_menu_key_resolves_to_admin_default` for the
     * SUPER_ADMIN-only leaves: EDIT resolves to SUPER_ADMIN, VIEW stays
     * at ADMIN, no override present.
     */
    public function test_super_admin_edit_menu_keys_resolve_to_super_admin(): void
    {
        foreach (self::SUPER_ADMIN_EDIT_MENU_KEYS as $menuKey) {
            $effective = PermissionRegistry::getPluginEffective(self::PLUGIN_SLUG, $menuKey);

            $this->assertNotNull($effective, "PermissionRegistry returned null for {$menuKey}");
            $this->assertSame(MemberRole::SUPER_ADMIN->value, $effective['access_roles'], "access_roles default for {$menuKey}");
            $this->assertSame(MemberRole::ADMIN->value, $effective['view_roles'], "view_roles default for {$menuKey}");
            $this->assertFalse($effective['is_overridden'], "is_overridden should be false for {$menuKey} with no DB row");
        }
    }

    public function test_admin_role_can_access_all_inquiry_menus_by_default(): void
    {
        foreach (self::MENU_KEYS as $menuKey) {
            if (in_array($menuKey, self::SUPER_ADMIN_EDIT_MENU_KEYS, true)) {
                // Covered by test_admin_role_cannot_edit_super_admin_only_menus.
                continue;
            }

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

    /**
     * ADMIN can still VIEW the SUPER_ADMIN-only leaves (so the menu
     * remains discoverable in the sidebar), but cannot EDIT them —
     * that is the whole point of the elevation.
     */
    public function test_admin_role_cannot_edit_super_admin_only_menus(): void
    {
        foreach (self::SUPER_ADMIN_EDIT_MENU_KEYS as $menuKey) {
            $this->assertFalse(
                PermissionRegistry::canAccessPlugin(self::PLUGIN_SLUG, $menuKey, MemberRole::ADMIN),
                "ADMIN should NOT be able to edit {$menuKey} (super_admin only by default)",
            );
            $this->assertTrue(
                PermissionRegistry::canViewPlugin(self::PLUGIN_SLUG, $menuKey, MemberRole::ADMIN),
                "ADMIN should still be able to view {$menuKey}",
            );
        }
    }

    public function test_editor_role_is_denied_by_default(): void
    {
        // These keys are the settings screens, which stay ADMIN: EDITOR (8)
        // is below ADMIN (9) so it is denied. The inquiry-handling keys are
        // deliberately EDITOR and live in EDITOR_MENU_KEYS instead.
        // Operators may still grant EDITOR access to a settings screen via
        // the admin UI, which writes an override — that branch is covered by
        // core's RolePermissionOverride tests.
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

    /**
     * The point of the split: an editor can work a submission end to end,
     * and cannot destroy it or take the public form offline.
     *
     * inquiry.destroy calls delete() on a model with no SoftDeletes, so the
     * row and the visitor PII it holds are gone for good. Granting EDITOR
     * the handling routes without pinning these two would hand that out
     * silently, since undeclared keys fall back to ADMIN and a later edit
     * to roles.php could move them without anything failing.
     */
    public function test_handling_is_editor_but_destructive_actions_are_not(): void
    {
        foreach (self::EDITOR_MENU_KEYS as $menuKey) {
            $effective = PermissionRegistry::getPluginEffective(self::PLUGIN_SLUG, $menuKey);

            $this->assertNotNull($effective, "PermissionRegistry returned null for {$menuKey}");
            $this->assertSame(
                MemberRole::EDITOR->value,
                $effective['access_roles'],
                "{$menuKey} is part of handling an inquiry and must be reachable by EDITOR."
            );
        }

        foreach (['inquiry.destroy', 'inquiry.toggle-accepting'] as $menuKey) {
            $effective = PermissionRegistry::getPluginEffective(self::PLUGIN_SLUG, $menuKey);

            $this->assertSame(
                MemberRole::ADMIN->value,
                $effective['access_roles'],
                "{$menuKey} destroys data or takes the public form offline and must stay above EDITOR."
            );
        }
    }

    /**
     * An editor reaching the list but not a single inquiry would be the
     * same dead end the authorization gate created elsewhere: the screen
     * loads, every row 403s.
     */
    public function test_an_editor_can_reach_both_the_list_and_a_single_inquiry(): void
    {
        foreach (['inquiry.index', 'inquiry.show'] as $menuKey) {
            $this->assertTrue(
                PermissionRegistry::canAccessPlugin(self::PLUGIN_SLUG, $menuKey, MemberRole::EDITOR),
                "EDITOR must reach {$menuKey}; the list is useless without the detail screen."
            );
        }
    }

    /**
     * The gate stops an editor at the route, but the controller carries its
     * own authorizeEdit() call as a second layer. Those calls all named
     * `inquiry.index`, which was harmless while every key was ADMIN and
     * became wrong the moment the list moved to EDITOR: delete and
     * toggle-accepting would have authorised against the list's role.
     *
     * Asserted from the source because authorizeEdit() is private and the
     * actions it guards need a full request to reach.
     */
    public function test_each_action_authorises_against_its_own_permission_key(): void
    {
        $source = file_get_contents(
            dirname(__DIR__, 2).'/app/Http/Controllers/Admin/DixlaseInquiryAdminController.php'
        );

        foreach ([
            'destroy' => 'inquiry.destroy',
            'updateStatus' => 'inquiry.status.update',
            'bulkUpdateStatus' => 'inquiry.bulk-status',
            'toggleAccepting' => 'inquiry.toggle-accepting',
        ] as $action => $key) {
            $this->assertStringContainsString(
                "authorizeEdit('{$key}')",
                $source,
                "{$action}() must authorise against {$key}, not against whatever the list key happens to be."
            );
        }

        $this->assertStringNotContainsString(
            "authorizeEdit('inquiry.index')",
            $source,
            'No action should authorise against the list key: it is EDITOR, and the destructive actions are not.'
        );
    }
}
