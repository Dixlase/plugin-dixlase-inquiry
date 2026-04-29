<?php

/**
 * This file is part of Dixlase Inquiry.
 *
 * Copyright (C) 2026 exc-D inc.
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

return [
    'heading' => 'Inquiry Settings',
    'description' => 'Overview of all inquiry form settings. Check embedding methods and status at a glance.',

    // Accepting inquiries
    'accepting_inquiries' => 'Accept Inquiries',
    'accepting_on' => 'Accepting inquiries',
    'accepting_on_desc' => 'The form is visible and visitors can submit inquiries.',
    'accepting_off' => 'Inquiries are paused',
    'accepting_off_desc' => 'The form is hidden and the inquiry page is inaccessible.',
    'accepting_toggle_success' => 'Inquiry acceptance status updated.',
    'accepting_toggle_error' => 'Failed to update inquiry acceptance status.',

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
        'lang' => 'Form Language',
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
