<?php

/**
 * This file is part of Dixlase.
 *
 * Copyright (C) 2025 exc-D inc.
 * https://exc-d.com
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program. If not, see <https://www.gnu.org/licenses/>.
 */

return [
    /*
    |--------------------------------------------------------------------------
    | Form Definitions
    |--------------------------------------------------------------------------
    |
    | Define all forms in this plugin that can use CAPTCHA verification.
    | Each form has:
    | - name: Translation key for display name
    | - route: Route name where the form is submitted
    | - category: Category for grouping in UI
    | - default_enabled: Default state when first registered
    | - priority: Display order (lower = higher priority)
    |
    */

    'forms' => [
        'inquiry_contact' => [
            'name' => 'dixlase-inquiry::captcha.forms.inquiry_contact',
            'route' => 'inquiry.send',
            'category' => 'contact',
            'default_enabled' => true,
            'priority' => 200,
        ],
    ],
];
