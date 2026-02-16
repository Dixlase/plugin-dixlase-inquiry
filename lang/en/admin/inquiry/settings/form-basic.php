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

    // 言語設定
    'form_locale' => 'Form Language',
    'form_locale_help' => 'Select the language for the inquiry form displayed to visitors.',

    // フォーム表示方式（form-displayから移動）
    'form_type' => 'Form Display Method',
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
    'show_subject' => 'Show Subject Field',
    'subject_required' => 'Make Subject Required',
    'show_postal_address' => 'Show Postal Code & Address Fields',
    'postal_address_required' => 'Make Postal Code & Address Required',
    'show_phone' => 'Show Phone Number Field',
    'phone_required' => 'Make Phone Number Required',
    'show_gender' => 'Show Gender Field',
    'gender_required' => 'Make Gender Required',
    'show_confirmation' => 'Show Confirmation Screen',
    'show_confirmation_help' => 'If unchecked, the form will be submitted immediately after input.',

    // プレビュー
    'form_preview' => 'Form Preview',

    'confirm_title' => 'Save Form Settings',
    'confirm_message' => 'Are you sure you want to save the form settings?',
    'settings_updated' => 'Form settings have been updated.',
];
