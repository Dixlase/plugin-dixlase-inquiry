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
        'admin_email_required' => 'Admin email address is required.',
        'admin_email_invalid' => 'Please enter a valid email address.',
        'auto_reply_from_email_invalid' => 'Auto-reply from email address is invalid.',
        'inquiry_url_slug_required' => 'URL slug is required.',
        'inquiry_url_slug_format' => 'URL slug can only contain lowercase letters, numbers, and hyphens (-).',
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
                'format_style' => 'Input Format',
                'format_japanese' => 'Japanese Style',
                'format_japanese_desc' => 'Last/First name order, split postal code/phone, prefecture-based address',
                'format_western' => 'Western Style',
                'format_western_desc' => 'First/Last name order, single field postal code/phone, single field address',
                'format_style_help' => 'Switches name order (Last/First vs First/Last), postal code/phone number formats, and address input order.',
                'field_settings' => 'Field Settings',
                'required_fields_note' => '* Name, Email, and Message are always displayed and required.',
                'show_name' => 'Show Name Field',
                'name_required' => 'Make Name Required',
                'show_email' => 'Show Email Field',
                'email_required' => 'Make Email Required',
                'show_message' => 'Show Message Field',
                'message_required' => 'Make Message Required',
                'name_order' => 'Name Display Order',
                'name_order_japanese' => 'Japanese Style (Last, First)',
                'name_order_western' => 'Western Style (First, Last)',
                'name_order_help' => 'English version automatically uses Western style (First, Last) order.',
                'show_subject' => 'Show Subject Field',
                'subject_required' => 'Make Subject Required',
                'show_postal_address' => 'Show Postal Code & Address Fields',
                'postal_address_required' => 'Make Postal Code & Address Required',
                'show_postal_code' => 'Show Postal Code Field',
                'postal_code_required' => 'Make Postal Code Required',
                'show_address' => 'Show Address Field',
                'address_required' => 'Make Address Required',
                'show_phone' => 'Show Phone Number Field',
                'phone_required' => 'Make Phone Number Required',
                'show_gender' => 'Show Gender Field',
                'gender_required' => 'Make Gender Required',
            ],
            'display' => [
                'title' => 'Form Display Settings',
                'form_type' => 'Form Display Method',
                'use_single_page' => 'Single Page (Dynamic confirmation and completion screens)',
                'single_page' => 'Single Page (Dynamic confirmation and completion screens)',
                'separate_pages' => 'Separate Pages (Input, confirmation, and completion screens on separate pages)',
                'single_page_help' => 'Single page method processes everything from input to completion on one page.',
                'shortcode_label' => 'Embedding Methods',
                'usage_instruction_title' => 'How to Use',
                'usage_instruction_text' => 'Paste the code below into your theme\'s Blade template or page plugin content. You can preview the actual display below.',
                'blade_directive' => 'Blade Directive (Recommended)',
                'blade_directive_help' => 'Use in theme Blade templates. Best for Laravel developers.',
                'shortcode' => 'Shortcode',
                'shortcode_help' => 'Use in page plugin content. Easy for non-technical users.',
                'form_preview' => 'Form Preview',
                'inquiry_url' => 'Inquiry Page URL',
                'inquiry_url_slug' => 'URL Slug',
                'inquiry_url_slug_help' => 'Set the URL for the inquiry page (e.g., inquiry → /inquiry). Only lowercase letters, numbers, and hyphens are allowed.',
                'preview_page_help' => 'Opens the inquiry page in a new tab. You can preview before saving the settings.',
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
                'default_subject' => 'Thank you for your inquiry',
                'default_body' => "Thank you for contacting us.\n\nWe have received your inquiry with the following details.\nOur team will review your message and get back to you soon.\n\nName: {{name}}\nEmail: {{email}}\nSubject: {{subject}}\n\nMessage:\n{{message}}\n\nBest regards.",
            ],
            'admin_notification' => [
                'title' => 'Admin Notification Settings',
                'admin_email' => 'Recipient Email Address',
                'admin_email_help' => 'Enter the email address where inquiry messages will be sent.',
                'subject' => 'Admin Notification Subject',
                'body' => 'Admin Notification Message Body',
                'body_help' => 'Available variables: {{name}}, {{email}}, {{subject}}, {{postal_code}}, {{address}}, {{phone}}, {{message}}',
                'default_subject' => 'Inquiry Received',
                'default_body' => "We have received the following inquiry.\n\nName: {{name}}\nEmail: {{email}}\nSubject: {{subject}}\nPostal Code: {{postal_code}}\nAddress: {{address}}\nPhone: {{phone}}\n\nMessage:\n{{message}}",
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
                'use_recaptcha' => 'Use CAPTCHA',
                'recaptcha_help' => 'Enable CAPTCHA for spam protection. CAPTCHA must be configured in security settings first.',
            ],
            'confirm_title' => 'Save Inquiry Settings',
            'confirm_message' => 'Are you sure you want to save the inquiry settings?',
            'mail_test_required' => 'To use the contact form functionality, please complete mail server settings and mail tests in the <a href=":url" class="text-blue-600 hover:text-blue-800 underline">base settings</a>. The contact form will not function properly without proper mail server configuration.',
            'captcha_test_required' => 'To use the CAPTCHA functionality, please complete CAPTCHA settings and authentication tests in the <a href=":url" class="text-blue-600 hover:text-blue-800 underline">security settings</a>. The CAPTCHA feature will not function properly without proper CAPTCHA configuration.',
        ],

    'messages' => [
        'settings_updated' => 'Settings have been successfully updated.',
        'validation_error' => 'There are errors in the input.',
    ],
];
