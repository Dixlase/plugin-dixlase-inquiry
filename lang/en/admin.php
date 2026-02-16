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
    'plugin' => [
        'name' => 'Dixlase Contact Form',
        'description' => 'Adds comprehensive contact form functionality to your website. Features customizable form fields, auto-reply, admin notifications, reCAPTCHA support, and more.',
    ],

    'inquiry' => [
        'title' => 'Inquiry List',
        'list_coming_soon' => 'Inquiry list feature is coming soon.',
    ],

    'form' => [
        'title' => 'Contact Us',
        'fields' => [
            'first_name' => 'First Name',
            'last_name' => 'Last Name',
            'email' => 'Email Address',
            'subject' => 'Subject',
            'postal_code' => 'Postal Code',
            'address' => 'Address',
            'phone' => 'Phone Number',
            'message' => 'Message',
        ],
        'placeholders' => [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'example@example.com',
            'subject' => 'Inquiry about...',
            'postal_code' => '12345',
            'address' => '123 Main St, City...',
            'phone' => '+1-234-567-8900',
            'message' => 'Please enter your message here',
        ],
        'buttons' => [
            'confirm' => 'Confirm',
            'back' => 'Back',
            'send' => 'Send',
        ],
    ],

    'confirmation' => [
        'title' => 'Confirm Your Information',
        'message' => 'Please confirm the information below is correct:',
    ],

    'complete' => [
        'title' => 'Message Sent',
        'message' => 'Thank you for your inquiry.<br>We will review your message and get back to you soon.',
    ],

    'validation' => [
        'required' => 'The :attribute field is required.',
        'email' => 'Please enter a valid email address for :attribute.',
        'admin_email_required' => 'Admin email address is required.',
        'admin_email_invalid' => 'Please enter a valid email address.',
        'auto_reply_from_email_invalid' => 'Auto-reply from email address is invalid.',
        'inquiry_url_slug_required' => 'URL slug is required.',
        'inquiry_url_slug_format' => 'URL slug can only contain lowercase letters, numbers, and hyphens (-).',
    ],

    'messages' => [
        'settings_updated' => 'Settings have been successfully updated.',
        'validation_error' => 'There are errors in the input.',
    ],
];
