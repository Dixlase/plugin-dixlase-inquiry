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
    'form' => [
        'heading' => 'Contact Us',
        'title' => 'Contact Us',
        'name' => 'Name',
        'last_name' => 'Last Name',
        'last_name_label_ja' => '姓',
        'last_name_label_en' => 'Last Name',
        'first_name' => 'First Name',
        'first_name_label_ja' => '名',
        'first_name_label_en' => 'First Name',
        'email' => 'Email Address',
        'subject' => 'Subject',
        'postal_code' => 'Postal Code / ZIP Code',
        'address' => 'Address',
        'country' => 'Country',
        'state_province' => 'State / Province',
        'prefecture' => 'Prefecture',
        'city' => 'City',
        'address_line' => 'Address Line',
        'building' => 'Building / Apartment',
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
        'state_province_placeholder' => 'California',
        'city_placeholder' => 'Tokyo',
        'city_placeholder_en' => 'Los Angeles',
        'address_line_placeholder' => '1-2-3 Shibuya',
        'address_line_placeholder_en' => '123 Main Street',
        'building_placeholder' => 'ABC Building 5F',
        'phone_placeholder' => '+1-234-567-8900',
        'message_placeholder' => 'Please enter your message here',
        // Western-style address fields
        'street_address' => 'Street Address',
        'city' => 'City',
        'state' => 'State / Province',
        'country' => 'Country',
        'street_address_placeholder' => '123 Main Street, Apt 5',
        'city_placeholder' => 'Los Angeles',
        'state_placeholder' => 'California',
        'country_placeholder' => 'United States',
        // Gender field
        'gender' => 'Gender',
        'gender_select' => 'Please select',
        'gender_male' => 'Male',
        'gender_female' => 'Female',
        'gender_non_binary' => 'Non-binary',
        'gender_other' => 'Other',
        'gender_prefer_not_to_say' => 'Prefer not to say',
        'success_message' => 'Thank you for your inquiry. We will review your message and get back to you soon.',
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
        'postal_code_format_japanese' => 'Please enter postal code in format "123-4567" or "1234567".',
        'postal_code_format_western' => 'Please enter a valid postal/ZIP code (3-10 alphanumeric characters).',
        'address_required' => 'Address is required.',
        'street_address_required' => 'Street address is required.',
        'city_required' => 'City is required.',
        'state_required' => 'State/Province is required.',
        'phone_required' => 'Phone number is required.',
        'phone_format_japanese' => 'Please enter a valid Japanese phone number (10-13 digits).',
        'phone_format_western' => 'Please enter a valid phone number (e.g., +1-234-567-8900).',
        'gender_required' => 'Please select your gender.',
        'gender_invalid' => 'Please select a valid gender option.',
        'recaptcha_required' => 'reCAPTCHA verification is required.',
    ],

    'messages' => [
        'service_unavailable' => 'The contact form is currently unavailable.',
        'submit_success' => 'Your inquiry has been sent successfully. Thank you.',
        'submit_error' => 'An error occurred while sending your inquiry. Please try again later.',
    ],

    'mail' => [
        'new_inquiry_subject' => 'New Inquiry Received',
        'auto_reply_subject' => 'Thank you for your inquiry',
        'admin' => [
            'title' => 'New Inquiry',
            'intro' => 'You have received a new inquiry with the following details.',
        ],
        'auto_reply' => [
            'title' => 'Thank you for your inquiry',
            'greeting' => '',
            'intro' => 'Thank you for contacting us. We have received your inquiry with the following details. Our team will review your message and get back to you soon.',
            'footer' => 'We appreciate your interest and look forward to assisting you.',
        ],
    ],
];
