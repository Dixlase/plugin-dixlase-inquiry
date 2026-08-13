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
 * Settings default to ADMIN so the configuration screens are admin-only
 * out of the box. The inquiry-handling routes (list, detail, status
 * changes) default to EDITOR instead: answering submissions is delegated
 * work, and it cannot be done without reading the submission. Deleting a
 * submission and closing the public form stay at ADMIN.
 *
 * Operators can move any threshold via the "Member role permissions"
 * admin screen; changes are persisted in `role_permission_overrides` as
 * a delta rather than edited here.
 */

return [
    'permissions' => [
        'inquiry' => [
            'children' => [
                // Handling submissions is delegated work, so the routes an
                // operator uses to triage them are EDITOR: open the list,
                // read one, move it through its statuses.
                //
                // This grants EDITOR sight of visitor PII -- name, address,
                // phone, email, IP, message body. That is inherent to the
                // job rather than incidental: an inquiry cannot be answered
                // without reading it. Anything that goes beyond answering
                // stays above EDITOR, below.
                'index' => [
                    'access_roles' => MemberRole::EDITOR->value,
                    'view_roles' => MemberRole::EDITOR->value,
                ],
                'show' => [
                    'access_roles' => MemberRole::EDITOR->value,
                    'view_roles' => MemberRole::EDITOR->value,
                ],
                // Status transitions, single and bulk. Reversible: any
                // status can be set again afterwards.
                'status' => [
                    'children' => [
                        'update' => [
                            'access_roles' => MemberRole::EDITOR->value,
                            'view_roles' => MemberRole::EDITOR->value,
                        ],
                    ],
                ],
                'bulk-status' => [
                    'access_roles' => MemberRole::EDITOR->value,
                    'view_roles' => MemberRole::EDITOR->value,
                ],

                // destroy() calls delete() on a model without SoftDeletes,
                // so the submission and its PII are gone for good. Nothing
                // in the handling workflow needs that.
                'destroy' => [
                    'access_roles' => MemberRole::ADMIN->value,
                    'view_roles' => MemberRole::ADMIN->value,
                ],
                // Closes the public form for the whole site. That is a
                // site-availability decision, not inquiry handling.
                'toggle-accepting' => [
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
