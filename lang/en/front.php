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
    'form' => [
        'heading' => 'Contact Us',
        'default_description' => "Feel free to reach out to us.\nPlease fill in the form below and submit your inquiry.",
        'go_to_form' => 'Contact Us',
        'title' => 'Contact Us',
        'name' => 'Name',
        'last_name' => 'Last Name',
        'last_name_label_ja' => '姓',
        'last_name_label_en' => 'Last Name',
        'first_name' => 'First Name',
        'first_name_label_ja' => '名',
        'first_name_label_en' => 'First Name',
        'email' => 'Email Address',
        'email_confirmation' => 'Email Address (Confirmation)',
        'email_confirmation_placeholder' => 'Enter your email address again',
        'email_confirmation_help' => 'Please re-enter your email address for confirmation.',
        'subject' => 'Subject',
        'postal_code' => 'Postal Code / ZIP Code',
        'address' => 'Address',
        'street_address' => 'Street Address',
        'country' => 'Country',
        'state' => 'State / Province',
        'state_province' => 'State / Province',
        'prefecture' => 'Prefecture',
        'city' => 'City',
        'address_line' => 'Address Line',
        'building' => 'Building / Apartment',
        'phone' => 'Phone Number',
        'message' => 'Message',
        'recaptcha' => 'reCAPTCHA',
        'submit' => 'Send',
        'confirm' => 'Confirm',
        'first_name_placeholder' => 'John',
        'last_name_placeholder' => 'Doe',
        'email_placeholder' => 'example@example.com',
        'subject_placeholder' => 'Inquiry about...',
        'postal_code_placeholder' => '12345',
        'postal_code_1_placeholder' => '123',
        'postal_code_2_placeholder' => '4567',
        'address_placeholder' => '123 Main St, City...',
        'prefecture_placeholder' => 'Select prefecture',
        'city_placeholder' => 'Los Angeles',
        'address_line_placeholder' => '123 Main Street',
        'building_placeholder' => 'ABC Building 5F',
        'street_address_placeholder' => '123 Main Street',
        'city_placeholder_en' => 'Los Angeles',
        'state_placeholder' => 'California',
        'state_province_placeholder' => 'California',
        'country_placeholder' => 'United States',
        'phone_placeholder' => '+1-234-567-8900',
        'phone_1_placeholder' => '090',
        'phone_2_placeholder' => '1234',
        'phone_3_placeholder' => '5678',
        'message_placeholder' => 'Please enter your message here',
        // Katakana (Furigana) field
        'kana' => 'Furigana',
        'last_name_kana' => 'Last Name (Katakana)',
        'first_name_kana' => 'First Name (Katakana)',
        'last_name_kana_placeholder' => 'ヤマダ',
        'first_name_kana_placeholder' => 'タロウ',
        // Gender field
        'gender' => 'Gender',
        'gender_select' => 'Please select',
        'gender_male' => 'Male',
        'gender_female' => 'Female',
        'gender_non_binary' => 'Non-binary',
        'gender_other' => 'Other',
        'gender_prefer_not_to_say' => 'Prefer not to say',
        'success_message' => 'Thank you for your inquiry. We will review your message and get back to you soon.',
        'privacy_consent' => 'I agree to the <a href=":url" target="_blank" class="text-blue-600 hover:underline dark:text-blue-400">Privacy Policy</a>.',
        'privacy_consent_default' => 'I agree to the Privacy Policy.',
        'privacy_policy_label' => 'Privacy Policy',
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
        'message' => 'Please confirm the information below is correct.<br>If everything looks good, press the submit button.',
    ],

    'complete' => [
        'title' => 'Message Sent',
        'message' => 'Thank you for your inquiry.<br>We will review your message and get back to you soon.',
        'inquiry_details' => 'Inquiry Details',
        'inquiry_number' => 'Inquiry Number',
        'submitted_at' => 'Submitted At',
        'auto_reply_notice' => 'A confirmation email has been sent. If you do not receive it, please check your spam folder.',
        'back_to_home' => 'Back to Home',
        'back_to_form' => 'Back to Form',
    ],

    'validation' => [
        'last_name_required' => 'Last name is required.',
        'first_name_required' => 'First name is required.',
        'email_required' => 'Email address is required.',
        'email_invalid' => 'Please enter a valid email address.',
        'email_confirmation_required' => 'Please re-enter your email address for confirmation.',
        'email_confirmation_mismatch' => 'The email addresses do not match.',
        'message_required' => 'Message is required.',
        'subject_required' => 'Subject is required.',
        'postal_code_required' => 'Postal code is required.',
        'postal_code_format_japanese' => 'Please enter postal code in format "123-4567" or "1234567".',
        'postal_code_format_western' => 'Please enter a valid postal/ZIP code (3-10 alphanumeric characters).',
        'postal_code_1_required' => 'The first 3 digits of postal code are required.',
        'postal_code_1_digits' => 'The first part of postal code must be 3 digits.',
        'postal_code_2_required' => 'The last 4 digits of postal code are required.',
        'postal_code_2_digits' => 'The last part of postal code must be 4 digits.',
        'prefecture_required' => 'Please select a prefecture.',
        'city_required' => 'City is required.',
        'address_line_required' => 'Address line is required.',
        'address_required' => 'Address is required.',
        'street_address_required' => 'Street address is required.',
        'state_required' => 'State/Province is required.',
        'phone_required' => 'Phone number is required.',
        'phone_format_japanese' => 'Please enter a valid Japanese phone number (10-13 digits).',
        'phone_format_western' => 'Please enter a valid phone number (e.g., +1-234-567-8900).',
        'phone_1_required' => 'Area code is required.',
        'phone_1_format' => 'Area code must be 1-5 digits.',
        'phone_2_required' => 'Exchange number is required.',
        'phone_2_format' => 'Exchange number must be 1-4 digits.',
        'phone_3_required' => 'Subscriber number is required.',
        'phone_3_format' => 'Subscriber number must be 4 digits.',
        'last_name_kana_required' => 'Last name (Katakana) is required.',
        'first_name_kana_required' => 'First name (Katakana) is required.',
        'last_name_kana_katakana' => 'Last name must be in Katakana.',
        'first_name_kana_katakana' => 'First name must be in Katakana.',
        'gender_required' => 'Please select your gender.',
        'gender_invalid' => 'Please select a valid gender option.',
        'recaptcha_required' => 'reCAPTCHA verification is required.',
        'privacy_agreed_required' => 'You must agree to the privacy policy.',
    ],

    'messages' => [
        'service_unavailable' => 'The contact form is currently unavailable.',
        'submit_success' => 'Your inquiry has been sent successfully. Thank you.',
        'submit_error' => 'An error occurred while sending your inquiry. Please try again later.',
    ],

    'mail' => [
        'new_inquiry_subject' => 'New Inquiry Received',
        'auto_reply_subject' => 'Thank you for your inquiry',
        'regards' => 'Regards,',
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
