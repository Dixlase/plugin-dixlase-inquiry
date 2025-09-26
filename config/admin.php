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
            '_insert_after' => 'front', // フロントページ管理のあとに追加、または '_insert_before' => 'media'
            'text' => 'pages-plugin::admin.nav.inquiries.text',
            'icon' => 'fas fa-fw fa-file-alt', // ページ管理
            'can' => 'admin',
            'children' => [
                'index' => [
                    'text' => 'pages-plugin::admin.nav.inquiries.index',
                    'route' => 'pages-plugin::admin.inquiries.index',
                    'icon' => 'fas fa-fw fa-file', // ページ一覧
                    'can' => 'admin',
                ],
                'create' => [
                    'text' => 'pages-plugin::admin.nav.inquiries.create',
                    'route' => 'pages-plugin::admin.inquiries.create',
                    'icon' => 'fas fa-fw fa-file-circle-plus', // ページ作成
                    'can' => 'admin',
                ],
            ],
        ],
    ],
];