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
 */

return [
    'heading' => 'Inquiry Settings',
    'description' => 'Overview of all inquiry form settings. Select a category to configure.',

    'nav' => [
        'form_basic' => 'Form Basic Settings',
        'form_display' => 'Form Display Settings',
        'completion' => 'Completion Page Settings',
        'admin_notification' => 'Admin Notification Settings',
        'auto_reply' => 'Auto-Reply Settings',
    ],

    'cards' => [
        'form_basic_desc' => 'Name format, field visibility, and required settings.',
        'form_display_desc' => 'Display method (single/separate page), URL slug, and confirmation screen.',
        'completion_desc' => 'Title and message shown after form submission.',
        'admin_notification_desc' => 'Email address, subject, and body for admin notifications.',
        'auto_reply_desc' => 'Auto-reply toggle, sender, subject, and body.',
    ],

    'status' => [
        'name_format' => 'Name Format',
        'japanese' => 'Japanese',
        'western' => 'Western',
        'display_method' => 'Display Method',
        'single_page' => 'Single Page',
        'separate_pages' => 'Separate Pages',
        'confirmation' => 'Confirmation',
        'enabled' => 'Enabled',
        'disabled' => 'Disabled',
        'admin_email' => 'Recipient',
        'auto_reply' => 'Auto-Reply',
    ],
];
