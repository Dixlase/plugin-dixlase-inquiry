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

    // 言語設定
    'form_locale' => 'Form Language',
    'form_locale_help' => 'Select the language for the inquiry form displayed to visitors.',

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
    'show_confirmation' => 'Show Confirmation Screen',
    'show_confirmation_help' => 'If unchecked, the form will be submitted immediately after input.',

    // プレビュー
    'form_preview' => 'Form Preview',
    'open_form_page' => 'Open Form Page',

    'confirm_title' => 'Save Form Settings',
    'confirm_message' => 'Are you sure you want to save the form settings?',
    'settings_updated' => 'Form settings have been updated.',
];
