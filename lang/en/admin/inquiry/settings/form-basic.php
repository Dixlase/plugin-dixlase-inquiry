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
    'heading' => 'Form Settings',
    'description' => 'Configure language, display method, name format, field visibility, and required settings.',

    // セクション見出し
    'section_form_settings' => 'Form Settings',
    'section_field_settings' => 'Field Settings',
    'section_display_settings' => 'Display Settings',

    // Form heading & description
    'form_heading' => 'Form Heading',
    'form_heading_help' => 'Leave empty to hide the heading.',
    'form_description' => 'Form Description',
    'form_description_help' => 'Displayed below the form heading. Leave empty to hide the description.',

    // 言語設定
    'lang' => 'Form Language',
    'lang_auto' => 'Auto',
    'lang_auto_desc' => "Use the site's default language",
    'lang_help' => 'Select the language for the inquiry form displayed to visitors. Selecting "Auto" will use the site\'s default language.',

    // フォーム表示方式（form-displayから移動）
    'form_type' => 'Display Settings',
    'single_page' => 'Single Page',
    'single_page_desc' => 'Input, confirmation, and completion processed dynamically on one page',
    'separate_pages' => 'Separate Pages',
    'separate_pages_desc' => 'Input, confirmation, and completion on separate pages with unique URLs',
    'single_page_help' => 'Single page method processes everything from input to completion on one page.',
    'inquiry_url' => 'Inquiry Page URL',
    'inquiry_url_slug_help' => 'Set the URL for the inquiry page (e.g., inquiry -> /inquiry). Only lowercase letters, numbers, and hyphens are allowed.',
    'preview_page_help' => 'Opens the inquiry page in a new tab. You can preview before saving the settings.',

    // 入力形式
    'format_style' => 'Input Format',
    'format_japanese' => 'Japanese Style',
    'format_japanese_desc' => 'Last/First name order, split postal code/phone, prefecture-based address',
    'format_western' => 'Western Style',
    'format_western_desc' => 'First/Last name order, single field postal code/phone, single field address',
    'format_style_help' => 'Switches name order (Last/First vs First/Last), postal code/phone number formats, and address input order.',

    // フィールド設定
    'field_settings' => 'Field Settings',
    'required_fields_note' => '* Name, Email, and Message are always displayed and required.',
    'show_subject' => 'Subject',
    'subject_required' => 'Required',
    'show_postal_address' => 'Postal Code & Address',
    'postal_address_required' => 'Required',
    'show_phone' => 'Phone Number',
    'phone_required' => 'Required',
    'show_gender' => 'Gender',
    'gender_required' => 'Required',
    'show_gender_other' => 'Other',
    'show_gender_prefer_not_to_say' => 'Prefer not to say',
    'gender_default_note' => '* Male and Female are always enabled.',
    'email_confirm_paste_disabled' => 'Disable paste on email confirmation field',
    'email_confirm_paste_disabled_help' => 'When enabled, users cannot paste into the email confirmation field and must type the address manually.',
    'show_kana' => 'Katakana (Furigana)',
    'require_kana' => 'Required',
    'show_kana_help' => 'Only available when Japanese style is selected. Katakana fields are not displayed in Western style.',
    'show_confirmation' => 'Show Confirmation Screen',
    'show_confirmation_help' => 'If unchecked, the form will be submitted immediately after input.',

    // プレビュー
    'form_preview' => 'Form Preview',
    'open_form_page' => 'Open Form Page',
    'preview_button' => 'Preview',
    'preview_toolbar_title' => 'Preview Mode',
    'preview_save_to_db' => 'Save to DB',
    'preview_send_email' => 'Send Email',
    'preview_toolbar_help' => 'Both toggles are OFF by default. The form can be tested without saving data or sending emails.',
    'preview_completed_no_save' => 'Preview completed. No data was saved and no email was sent.',

    // プライバシー同意設定
    'section_privacy' => 'Privacy Consent',
    'privacy_settings' => 'Privacy Consent Settings',
    'privacy_consent_enabled' => 'Enable Privacy Consent Checkbox',
    'privacy_consent_enabled_help' => 'When enabled, users must agree to the privacy policy before submitting the form.',
    'privacy_policy_url' => 'Privacy Policy URL',
    'privacy_policy_url_help' => 'Enter the URL of your privacy policy page. If a legal plugin is installed, the URL will be automatically retrieved.',
    'privacy_consent_text' => 'Consent Text',
    'privacy_consent_text_placeholder' => 'I agree to the privacy policy',
    'privacy_consent_text_help' => 'Custom text for the consent checkbox. Leave empty to use the default text.',

    // 送信間隔制限設定
    'section_throttle' => 'Submission Rate Limit',
    'throttle_settings' => 'Rate Limit Settings',
    'throttle_enabled' => 'Enable Submission Rate Limit',
    'throttle_enabled_help' => 'Limits the number of submissions from the same IP address within a specified time period.',
    'throttle_max_attempts' => 'Maximum Submissions',
    'throttle_decay_minutes' => 'Time Period (minutes)',
    'throttle_help' => 'Example: Allow up to 3 submissions within 5 minutes from the same IP address.',

    // Simple mode
    'simple_mode_notice' => 'Some settings are automatically configured in Simple mode. Switch to Advanced mode to customize all settings.',

    // Sidebar
    'sidebar_open' => 'Open settings sidebar',
    'sidebar_close' => 'Close settings sidebar',

    'confirm_title' => 'Save Form Settings',
    'confirm_message' => 'Are you sure you want to save the form settings?',
    'settings_updated' => 'Form settings have been updated.',
];
