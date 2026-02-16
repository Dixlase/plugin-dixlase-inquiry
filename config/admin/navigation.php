<?php

/**
 * This file is part of Dixlase Inquiry.
 *
 * Copyright (C) 2026 exc-D inc.
 * https://exc-d.com
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

return [
    // 問い合わせ管理
    'inquiry' => [
        'text' => 'dixlase-inquiry::admin/navigation.inquiry',
        'icon' => 'fas fa-fw fa-envelope',
        'can' => 'admin',
        'children' => [
            'index' => [
                'text' => 'dixlase-inquiry::admin/navigation.inquiry_list',
                'route' => 'dixlase-inquiry::admin.inquiry.index',
                'icon' => 'fas fa-fw fa-list',
                'can' => 'admin',
            ],
            'settings' => [
                'text' => 'dixlase-inquiry::admin/navigation.inquiry_settings',
                'icon' => 'fas fa-fw fa-cog',
                'can' => 'admin',
                'children' => [
                    'index' => [
                        'text' => 'dixlase-inquiry::admin/navigation.settings_nav.index',
                        'route' => 'dixlase-inquiry::admin.inquiry.settings.index',
                        'icon' => 'fas fa-fw fa-list-alt',
                        'can' => 'admin',
                    ],
                    'form-preview' => [
                        'text' => 'dixlase-inquiry::admin/navigation.settings_nav.form_preview',
                        'route' => 'dixlase-inquiry::admin.inquiry.settings.form-preview',
                        'icon' => 'fas fa-fw fa-eye',
                        'can' => 'admin',
                    ],
                    'form-basic' => [
                        'text' => 'dixlase-inquiry::admin/navigation.settings_nav.form_basic',
                        'route' => 'dixlase-inquiry::admin.inquiry.settings.form-basic',
                        'icon' => 'fas fa-fw fa-sliders-h',
                        'can' => 'admin',
                    ],
                    'form-display' => [
                        'text' => 'dixlase-inquiry::admin/navigation.settings_nav.form_display',
                        'route' => 'dixlase-inquiry::admin.inquiry.settings.form-display',
                        'icon' => 'fas fa-fw fa-desktop',
                        'can' => 'admin',
                    ],
                    'completion' => [
                        'text' => 'dixlase-inquiry::admin/navigation.settings_nav.completion',
                        'route' => 'dixlase-inquiry::admin.inquiry.settings.completion',
                        'icon' => 'fas fa-fw fa-check-circle',
                        'can' => 'admin',
                    ],
                    'admin-notification' => [
                        'text' => 'dixlase-inquiry::admin/navigation.settings_nav.admin_notification',
                        'route' => 'dixlase-inquiry::admin.inquiry.settings.admin-notification',
                        'icon' => 'fas fa-fw fa-bell',
                        'can' => 'admin',
                    ],
                    'auto-reply' => [
                        'text' => 'dixlase-inquiry::admin/navigation.settings_nav.auto_reply',
                        'route' => 'dixlase-inquiry::admin.inquiry.settings.auto-reply',
                        'icon' => 'fas fa-fw fa-reply-all',
                        'can' => 'admin',
                    ],
                ],
            ],
        ],
    ],
];
