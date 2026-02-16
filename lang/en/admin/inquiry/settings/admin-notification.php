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
    'heading' => 'Admin Notification Settings',
    'description' => 'Configure the email address, subject, and body for admin notification emails.',

    'admin_email' => 'Recipient Email Address',
    'admin_email_help' => 'Enter the email address where inquiry messages will be sent.',
    'subject' => 'Admin Notification Subject',
    'body' => 'Admin Notification Message Body',
    'body_help' => 'Available variables: {{name}}, {{email}}, {{subject}}, {{postal_code}}, {{address}}, {{phone}}, {{message}}',

    'mail_test_required' => 'To use the contact form functionality, please complete mail server settings and mail tests in the <a href=":url" class="text-blue-600 hover:text-blue-800 underline">base settings</a>. The contact form will not function properly without proper mail server configuration.',

    'confirm_title' => 'Save Admin Notification Settings',
    'confirm_message' => 'Are you sure you want to save the admin notification settings?',
    'settings_updated' => 'Admin notification settings have been updated.',
];
