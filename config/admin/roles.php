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

use App\Enums\MemberRole;

/**
 * Plugin default permission settings.
 *
 * Defines the default access/view roles for each menu entry the plugin
 * exposes. The structure mirrors `config/admin/navigation.php` so that
 * `PermissionRegistry::getPluginEffective($slug, $menuKey)` can resolve
 * dot-notation keys like `inquiry.settings.form-basic` by walking the
 * nested `children` arrays.
 *
 * Defaults are intentionally set to ADMIN so that the inquiry menus are
 * visible and editable to admins out of the box. Operators can lower the
 * threshold (e.g. allow EDITOR to manage inquiry replies) via the
 * "Member role permissions" admin screen; any change is persisted in
 * `role_permission_overrides` as a delta.
 */

return [
    'permissions' => [
        'inquiry' => [
            'children' => [
                // Inquiry list + detail + status updates + delete.
                'index' => [
                    'access_roles' => MemberRole::ADMIN->value,
                    'view_roles' => MemberRole::ADMIN->value,
                ],

                // Settings hub and per-section pages.
                'settings' => [
                    'children' => [
                        'index' => [
                            'access_roles' => MemberRole::ADMIN->value,
                            'view_roles' => MemberRole::ADMIN->value,
                        ],
                        'form-basic' => [
                            'access_roles' => MemberRole::ADMIN->value,
                            'view_roles' => MemberRole::ADMIN->value,
                        ],
                        'completion' => [
                            'access_roles' => MemberRole::ADMIN->value,
                            'view_roles' => MemberRole::ADMIN->value,
                        ],
                        // Notification recipient routing: whoever edits this
                        // sets the address that receives every inquiry
                        // (visitor PII + message body). Admins may VIEW the
                        // setting, but only SUPER_ADMIN may EDIT it, so a
                        // delegated admin cannot silently redirect submissions.
                        'admin-notification' => [
                            'access_roles' => MemberRole::SUPER_ADMIN->value,
                            'view_roles' => MemberRole::ADMIN->value,
                        ],
                        'auto-reply' => [
                            'access_roles' => MemberRole::ADMIN->value,
                            'view_roles' => MemberRole::ADMIN->value,
                        ],
                    ],
                ],
            ],
        ],
    ],
];
