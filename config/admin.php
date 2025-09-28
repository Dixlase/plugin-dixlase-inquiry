<?php

/**
 * This file is part of DixlaseInquiry.
 *
 * Copyright (C) 2025 exc-D inc.
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
    'nav' => [
        'settings' => [
            'text' => 'common.settings', // 親項目のテキスト
            'icon' => 'fas fa-fw fa-cog', // 親項目のアイコン
            'can' => 'admin',
            'children' => [
                'inquiries' => [
                    'text' => 'dixlase-inquiry::admin.nav.settings.inquiry',
                    'route' => 'admin.dixlase-inquiry::admin.settings.inquiry',
                    'icon' => 'fas fa-fw fa-envelope',
                    'can' => 'admin',
                ],
            ],
        ],
    ],
];
