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
        'inquiries' => [
            '_insert_after' => 'front', // フロントページ管理のあとに追加
            'text' => 'dixlase-inquiry::admin.nav.inquiries.text',
            'icon' => 'fas fa-fw fa-envelope', // 問い合わせ管理
            'can' => 'admin',
            'children' => [
                'index' => [
                    'text' => 'dixlase-inquiry::admin.nav.inquiries.index',
                    'route' => 'admin.inquiries.index',
                    'icon' => 'fas fa-fw fa-list', // 問い合わせ一覧
                    'can' => 'admin',
                ],
                'settings' => [
                    'text' => 'dixlase-inquiry::admin.nav.inquiries.settings',
                    'route' => 'admin.inquiries.settings',
                    'icon' => 'fas fa-fw fa-cog', // 設定
                    'can' => 'admin',
                ],
            ],
        ],
    ],
];