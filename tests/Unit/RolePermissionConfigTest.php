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
use PHPUnit\Framework\TestCase;

/**
 * Verifies the plugin's `config/admin/roles.php` and `config/admin/navigation.php`
 * are wired together so core's PermissionRegistry can discover the
 * inquiry menus and the sidebar can route checks through
 * `canEditPluginMenu()` / `canViewPluginMenu()`.
 */
class RolePermissionConfigTest extends TestCase
{
    /** @var array<string, mixed> */
    private array $roles;

    /** @var array<string, mixed> */
    private array $navigation;

    protected function setUp(): void
    {
        parent::setUp();

        $this->roles = require __DIR__.'/../../config/admin/roles.php';
        $this->navigation = require __DIR__.'/../../config/admin/navigation.php';
    }

    public function test_roles_config_returns_permissions_array(): void
    {
        $this->assertArrayHasKey('permissions', $this->roles);
        $this->assertArrayHasKey('inquiry', $this->roles['permissions']);
    }

    /**
     * Most inquiry menu_keys default to ADMIN so the menu is visible
     * and editable to admins out of the box. `admin-notification` is
     * intentionally omitted — that leaf sets the address every
     * visitor's inquiry (with their PII and message body) is forwarded
     * to, so its edit right defaults to SUPER_ADMIN. That exception is
     * covered by `test_admin_notification_defaults_to_super_admin_edit`
     * below.
     */
    public function test_all_inquiry_menu_keys_default_to_admin(): void
    {
        // inquiry.index moved to EDITOR with the rest of the handling
        // routes; it is asserted in
        // test_inquiry_handling_keys_are_editor below.
        $expected = [
            ['inquiry', 'settings', 'index'],
            ['inquiry', 'settings', 'form-basic'],
            ['inquiry', 'settings', 'completion'],
            ['inquiry', 'settings', 'auto-reply'],
            ['inquiry', 'settings', 'privacy'],
        ];

        foreach ($expected as $path) {
            $node = $this->roles['permissions'];
            foreach ($path as $segment) {
                $node = $node[$segment] ?? $node['children'][$segment] ?? null;
                $this->assertIsArray($node, 'Missing permission segment: '.implode('.', $path));
            }

            $this->assertArrayHasKey('access_roles', $node, 'Leaf missing access_roles: '.implode('.', $path));
            $this->assertSame(
                MemberRole::ADMIN->value,
                $node['access_roles'],
                'access_roles default should be ADMIN for: '.implode('.', $path),
            );
            $this->assertSame(
                MemberRole::ADMIN->value,
                $node['view_roles'],
                'view_roles default should be ADMIN for: '.implode('.', $path),
            );
        }
    }

    /**
     * `admin-notification` sets the address every visitor's inquiry
     * (with their PII and message body) is forwarded to. Only
     * SUPER_ADMIN may EDIT it, so a delegated admin cannot silently
     * redirect submissions off-site. ADMIN may still VIEW the setting
     * so the sidebar entry remains discoverable and the surrounding
     * form-basic / completion / auto-reply pages stay editable in the
     * same sitting.
     */
    public function test_admin_notification_defaults_to_super_admin_edit(): void
    {
        $node = $this->roles['permissions'];
        foreach (['inquiry', 'settings', 'admin-notification'] as $segment) {
            $node = $node[$segment] ?? $node['children'][$segment] ?? null;
            $this->assertIsArray($node, 'Missing permission segment for admin-notification');
        }

        $this->assertSame(
            MemberRole::SUPER_ADMIN->value,
            $node['access_roles'],
            'admin-notification edit default must be SUPER_ADMIN only',
        );
        $this->assertSame(
            MemberRole::ADMIN->value,
            $node['view_roles'],
            'admin-notification must remain visible to ADMIN',
        );
    }

    /**
     * The sidebar uses `plugin_slug` to dispatch permission checks
     * through `canEditPluginMenu()`. Without it, the check falls back
     * to core's PermissionRegistry which has no `inquiry` entry and the
     * menu disappears for everyone below SUPER_ADMIN.
     */
    public function test_navigation_declares_plugin_slug(): void
    {
        $this->assertArrayHasKey('inquiry', $this->navigation);
        $this->assertArrayHasKey('plugin_slug', $this->navigation['inquiry']);
        $this->assertSame(
            'DixlaseInquiry',
            $this->navigation['inquiry']['plugin_slug'],
            'plugin_slug must be the PascalCase directory name, '
            .'not the kebab-case slug from plugin.json, because '
            .'PermissionRegistry resolves config/admin/roles.php via the directory basename.',
        );
    }

    /**
     * The roles.php nesting must mirror navigation.php so dot-notation
     * lookups like `inquiry.settings.form-basic` succeed in
     * `PermissionRegistry::getPluginEffective()`.
     */
    public function test_settings_children_match_navigation(): void
    {
        $navSettingsChildren = array_keys($this->navigation['inquiry']['children']['settings']['children'] ?? []);
        $rolesSettingsChildren = array_keys($this->roles['permissions']['inquiry']['children']['settings']['children'] ?? []);

        sort($navSettingsChildren);
        sort($rolesSettingsChildren);

        $this->assertSame(
            $navSettingsChildren,
            $rolesSettingsChildren,
            'config/admin/roles.php and config/admin/navigation.php must declare the same settings child keys',
        );
    }

    /**
     * plugin.json must advertise that `config/admin/roles.php` exists so
     * `dls:plugin:audit` / discovery tooling reads it.
     */
    public function test_plugin_json_declares_roles_config(): void
    {
        $pluginJson = json_decode(
            (string) file_get_contents(__DIR__.'/../../plugin.json'),
            true,
        );

        $this->assertIsArray($pluginJson);
        $this->assertTrue(
            (bool) ($pluginJson['declares']['configs']['roles'] ?? false),
            'plugin.json declares.configs.roles must be true',
        );
    }

    /**
     * Handling routes -- including the reversible soft-delete -- are EDITOR;
     * only the operations that cannot be taken back stay above.
     * Asserted against the config file itself, so a future edit that widens
     * permanent-delete has to fail here rather than only in a resolution test.
     */
    public function test_inquiry_handling_keys_are_editor(): void
    {
        $children = $this->roles['permissions']['inquiry']['children'];

        // destroy() is EDITOR now that it moves the inquiry to the trash
        // instead of dropping the row; permanent delete lives in
        // trash.force-destroy / trash.empty and stays ADMIN below.
        foreach (['index', 'show', 'bulk-status', 'destroy'] as $key) {
            $this->assertSame(
                MemberRole::EDITOR->value,
                $children[$key]['access_roles'] ?? null,
                "inquiry.{$key} is part of handling an inquiry and should be EDITOR."
            );
        }

        $this->assertSame(
            MemberRole::EDITOR->value,
            $children['status']['children']['update']['access_roles'] ?? null,
            'inquiry.status.update is a reversible status change and should be EDITOR.'
        );

        // toggle-accepting closes the public form -- a site-availability
        // decision -- so it stays out of the handling role.
        $this->assertSame(
            MemberRole::ADMIN->value,
            $children['toggle-accepting']['access_roles'] ?? null,
            'inquiry.toggle-accepting closes the public form and must stay above EDITOR.'
        );
    }

    /**
     * `expires-at.update` is a per-row retention edit from the detail
     * screen. Retention is a privacy policy decision (same tier as the
     * settings pages above) rather than EDITOR triage work: editors can
     * read PII while handling an inquiry, but how long that PII sits
     * around is the admin's call.
     */
    public function test_expires_at_update_defaults_to_admin(): void
    {
        $node = $this->roles['permissions'];
        foreach (['inquiry', 'expires-at', 'update'] as $segment) {
            $node = $node[$segment] ?? $node['children'][$segment] ?? null;
            $this->assertIsArray($node, 'Missing permission segment for inquiry.expires-at.update');
        }

        $this->assertSame(
            MemberRole::ADMIN->value,
            $node['access_roles'] ?? null,
            'inquiry.expires-at.update must be ADMIN (retention is a policy decision).',
        );
        $this->assertSame(
            MemberRole::ADMIN->value,
            $node['view_roles'] ?? null,
        );
    }

    /**
     * The trash subtree splits reversible actions (view, restore) from
     * irreversible ones (force-destroy, empty). Restoring is the whole
     * point of the trash so it must be reachable by the same role that
     * can send items there; permanent deletion bypasses restore and
     * needs to stay at ADMIN.
     *
     * The parent `trash` node is children-only. Every existing nested
     * key in this plugin follows that shape; a parent that carries
     * both `access_roles` and `children` has no working example in the
     * codebase and, per resolver history, needs the core fix that
     * merges the two. Keeping the list key at `trash.index` sidesteps
     * that dependency.
     */
    public function test_trash_keys_have_expected_roles(): void
    {
        $trash = $this->roles['permissions']['inquiry']['children']['trash'] ?? null;
        $this->assertIsArray($trash, 'inquiry.trash node must exist.');
        $this->assertArrayNotHasKey(
            'access_roles',
            $trash,
            'inquiry.trash must be children-only; a parent+access_roles+children shape has no working example in the codebase.',
        );
        $this->assertArrayHasKey('children', $trash, 'inquiry.trash must have children.');

        $trashChildren = $trash['children'];

        foreach (['index', 'restore'] as $key) {
            $this->assertSame(
                MemberRole::EDITOR->value,
                $trashChildren[$key]['access_roles'] ?? null,
                "inquiry.trash.{$key} is reversible (viewing or restoring) and should be EDITOR."
            );
        }

        foreach (['force-destroy', 'empty'] as $key) {
            $this->assertSame(
                MemberRole::ADMIN->value,
                $trashChildren[$key]['access_roles'] ?? null,
                "inquiry.trash.{$key} permanently deletes visitor PII and must stay above EDITOR."
            );
        }
    }
}
