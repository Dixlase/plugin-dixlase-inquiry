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
    'description' => 'Overview of all inquiry form settings. Check embedding methods and status at a glance.',

    'nav' => [
        'form_basic' => 'Form Settings',
        'completion' => 'Completion Page Settings',
        'admin_notification' => 'Admin Notification Settings',
        'auto_reply' => 'Auto-Reply Settings',
    ],

    'cards' => [
        'form_basic_desc' => 'Language, display method, name format, field visibility, and required settings.',
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
        'form_locale' => 'Form Language',
    ],

    // 埋め込み方法（form-previewから移動）
    'embedding_methods' => 'Embedding Methods',
    'usage_instruction_title' => 'How to Use',
    'usage_instruction_text' => 'Paste the code below into your theme\'s Blade template or page plugin content.',
    'blade_directive' => 'Blade Directive (Recommended)',
    'blade_directive_help' => 'Use in theme Blade templates. Best for Laravel developers.',
    'shortcode' => 'Shortcode',
    'shortcode_help' => 'Use in page plugin content. Easy for non-technical users.',

    // 警告メッセージ
    'warning_admin_email' => 'Admin notification email address is not configured. Please set it in <a href=":url" class="font-medium underline">Admin Notification Settings</a>.',
    'warning_auto_reply_email' => 'Auto-reply is enabled but the sender email address is not configured. Please set it in <a href=":url" class="font-medium underline">Auto-Reply Settings</a>.',

    // CAPTCHA案内メッセージ
    'notice_captcha_disabled' => 'CAPTCHA is not enabled for the inquiry form. To prevent spam, we recommend enabling CAPTCHA in <a href=":url" class="font-medium underline">Security Settings &gt; CAPTCHA</a>.',
];
