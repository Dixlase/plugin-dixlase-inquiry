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
    'form' => [
        'heading' => 'Contact Us',
        'title' => 'Contact Us',
        'name' => 'Name',
        'first_name' => 'First Name',
        'last_name' => 'Last Name',
        'email' => 'Email Address',
        'subject' => 'Subject',
        'postal_code' => 'Postal Code',
        'address' => 'Address',
        'phone' => 'Phone Number',
        'message' => 'Message',
        'recaptcha' => 'reCAPTCHA',
        'submit' => 'Send',
        'first_name_placeholder' => 'John',
        'last_name_placeholder' => 'Doe',
        'email_placeholder' => 'example@example.com',
        'subject_placeholder' => 'Inquiry about...',
        'postal_code_placeholder' => '12345',
        'address_placeholder' => '123 Main St, City...',
        'phone_placeholder' => '+1-234-567-8900',
        'message_placeholder' => 'Please enter your message here',
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

    'confirmation' => [
        'title' => 'Confirm Your Information',
        'message' => 'Please confirm the information below is correct:',
    ],

    'complete' => [
        'title' => 'Message Sent',
        'message' => 'Thank you for your inquiry.<br>We will review your message and get back to you soon.',
        'inquiry_details' => 'Inquiry Details',
        'inquiry_number' => 'Inquiry Number',
        'submitted_at' => 'Submitted At',
        'auto_reply_notice' => 'A confirmation email has been sent. If you do not receive it, please check your spam folder.',
        'back_to_home' => 'Back to Home',
    ],

    'validation' => [
        'last_name_required' => 'Last name is required.',
        'first_name_required' => 'First name is required.',
        'email_required' => 'Email address is required.',
        'email_invalid' => 'Please enter a valid email address.',
        'message_required' => 'Message is required.',
        'subject_required' => 'Subject is required.',
        'postal_code_required' => 'Postal code is required.',
        'address_required' => 'Address is required.',
        'phone_required' => 'Phone number is required.',
        'recaptcha_required' => 'reCAPTCHA verification is required.',
    ],

    'messages' => [
        'service_unavailable' => 'The contact form is currently unavailable.',
        'submit_success' => 'Your inquiry has been sent successfully. Thank you.',
        'submit_error' => 'An error occurred while sending your inquiry. Please try again later.',
    ],

    'mail' => [
        'new_inquiry_subject' => 'New Inquiry Received',
    ],
];
