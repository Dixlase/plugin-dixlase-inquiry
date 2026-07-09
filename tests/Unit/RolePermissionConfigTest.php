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
        $expected = [
            ['inquiry', 'index'],
            ['inquiry', 'settings', 'index'],
            ['inquiry', 'settings', 'form-basic'],
            ['inquiry', 'settings', 'completion'],
            ['inquiry', 'settings', 'auto-reply'],
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
}
