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
 * changes, and the reversible destroy that moves a submission to the
 * trash) default to EDITOR: answering submissions is delegated work and
 * cannot be done without reading them. Permanent deletion — emptying
 * the trash and per-row force-destroy — stays at ADMIN because it
 * bypasses restore. Closing the public form is also ADMIN as a
 * site-availability decision.
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

                // Per-row retention edit from the detail screen. This is
                // a policy decision rather than triage work — the same
                // tier as the privacy settings page below. An editor can
                // still read PII during triage; retention controls how
                // long that PII sits around after triage ends, which is
                // the admin's call.
                'expires-at' => [
                    'children' => [
                        'update' => [
                            'access_roles' => MemberRole::ADMIN->value,
                            'view_roles' => MemberRole::ADMIN->value,
                        ],
                    ],
                ],
                'bulk-status' => [
                    'access_roles' => MemberRole::EDITOR->value,
                    'view_roles' => MemberRole::EDITOR->value,
                ],

                // destroy() now runs on a SoftDeletes-enabled model: it
                // moves the row to the trash where the retention window
                // and the trash screen restore path apply. An accidental
                // click no longer loses the submission, so this is safe
                // to hand to EDITOR alongside the rest of the handling
                // workflow. Permanent deletion lives under `trash` below.
                'destroy' => [
                    'access_roles' => MemberRole::EDITOR->value,
                    'view_roles' => MemberRole::EDITOR->value,
                ],
                // Closes the public form for the whole site. That is a
                // site-availability decision, not inquiry handling.
                'toggle-accepting' => [
                    'access_roles' => MemberRole::ADMIN->value,
                    'view_roles' => MemberRole::ADMIN->value,
                ],

                // Trash: list, restore, force-destroy, empty.
                //
                // Split on "can this be taken back?" Restoring is
                // reversible so it stays with the handling role
                // (EDITOR). Force-destroy and empty bypass the trash
                // and drop the PII permanently, so they need ADMIN.
                //
                // The parent node is children-only; the list page's
                // permission lives in `trash.index`. Every existing
                // nested node in this plugin (and in core / sibling
                // plugins) follows this shape — a parent that carries
                // both `access_roles` and `children` has no working
                // example in the codebase and, per resolver history,
                // only takes effect when core includes the fix that
                // reconciles the two. Keeping the list key at
                // `trash.index` sidesteps that dependency.
                'trash' => [
                    'children' => [
                        'index' => [
                            'access_roles' => MemberRole::EDITOR->value,
                            'view_roles' => MemberRole::EDITOR->value,
                        ],
                        'restore' => [
                            'access_roles' => MemberRole::EDITOR->value,
                            'view_roles' => MemberRole::EDITOR->value,
                        ],
                        'force-destroy' => [
                            'access_roles' => MemberRole::ADMIN->value,
                            'view_roles' => MemberRole::ADMIN->value,
                        ],
                        'empty' => [
                            'access_roles' => MemberRole::ADMIN->value,
                            'view_roles' => MemberRole::ADMIN->value,
                        ],
                    ],
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
                        // Privacy settings control whether submissions are
                        // persisted at all and how long rows live before the
                        // prune job removes them. That shapes what visitor
                        // PII the site retains, so the same ADMIN tier as
                        // the other settings pages — not a loosening below
                        // it, because flipping persistence on retroactively
                        // starts collecting data under the policy.
                        'privacy' => [
                            'access_roles' => MemberRole::ADMIN->value,
                            'view_roles' => MemberRole::ADMIN->value,
                        ],
                    ],
                ],
            ],
        ],
    ],
];
