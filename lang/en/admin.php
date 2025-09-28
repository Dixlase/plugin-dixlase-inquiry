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
    'plugin' => [
        'name' => 'Dixlase Contact Form',
        'description' => 'Adds comprehensive contact form functionality to your website. Features customizable form fields, auto-reply, admin notifications, reCAPTCHA support, and more.',
    ],
    
    'nav' => [
        'settings' => [
            'inquiry' => 'Inquiry Settings',
        ],
    ],

    'form' => [
        'title' => 'Contact Us',
        'fields' => [
            'first_name' => 'First Name', // 英語版では名前が先
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
    ],
    'settings' => [
            'inquiry' => [
                'heading' => 'Inquiry Settings',
            ],
            'title' => 'Contact Form Settings',
            'basic' => [
                'title' => 'Basic Settings',
                'admin_email' => 'Recipient Email Address',
                'admin_email_help' => 'Enter the email address where inquiry messages will be sent.',
            ],
            'form_fields' => [
                'title' => 'Form Field Settings',
                'name_order' => 'Name Display Order',
                'name_order_western' => 'Western Style (First, Last)',
                'name_order_help' => 'English version automatically uses Western style (First, Last) order.',
                'show_subject' => 'Show Subject Field',
                'subject_required' => 'Make Subject Required',
                'show_postal_code' => 'Show Postal Code Field',
                'postal_code_required' => 'Make Postal Code Required',
                'show_address' => 'Show Address Field',
                'address_required' => 'Make Address Required',
                'show_phone' => 'Show Phone Number Field',
                'phone_required' => 'Make Phone Number Required',
            ],
            'display' => [
                'title' => 'Form Display Settings',
                'form_type' => 'Form Display Method',
                'use_single_page' => 'Single Page (Dynamic confirmation and completion screens)',
                'single_page' => 'Single Page (Dynamic confirmation and completion screens)',
                'separate_pages' => 'Separate Pages (Input, confirmation, and completion screens on separate pages)',
                'single_page_help' => 'Single page method processes everything from input to completion on one page.',
                'show_confirmation' => 'Show Confirmation Screen',
                'show_confirmation_help' => 'If unchecked, the form will be submitted immediately after input.',
            ],
            'auto_reply' => [
                'title' => 'Auto-Reply Settings',
                'enabled' => 'Enable Auto-Reply',
                'from_email' => 'Auto-Reply From Email Address',
                'from_email_help' => 'If empty, the system default sender address will be used.',
                'subject' => 'Auto-Reply Subject',
                'body' => 'Auto-Reply Message Body',
                'body_help' => 'Available variables: {{name}}, {{email}}, {{subject}}, {{postal_code}}, {{address}}, {{phone}}, {{message}}',
            ],
            'admin_notification' => [
                'title' => 'Admin Notification Settings',
                'subject' => 'Admin Notification Subject',
                'body' => 'Admin Notification Message Body',
            ],
            'completion' => [
                'title' => 'Completion Page Settings',
                'title_text' => 'Completion Page Title',
                'title_help' => 'Title text displayed when inquiry submission is completed',
                'message' => 'Completion Page Message',
                'message_help' => 'Message displayed when inquiry submission is completed. HTML tags can be used.',
            ],
            'security' => [
                'title' => 'Security Settings',
                'use_recaptcha' => 'Use reCAPTCHA',
                'recaptcha_help' => 'Enable reCAPTCHA for spam protection. reCAPTCHA must be configured in security settings first.',
            ],
            'confirm_title' => 'Save Inquiry Settings',
            'confirm_message' => 'Are you sure you want to save the inquiry settings?',
        ],

    'messages' => [
        'settings_updated' => 'Settings have been successfully updated.',
        'validation_error' => 'There are errors in the input.',
    ],
];
