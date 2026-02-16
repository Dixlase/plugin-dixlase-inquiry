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
    'heading' => 'Form Basic Settings',
    'description' => 'Configure name format, field visibility, and required settings for the inquiry form.',

    'format_style' => 'Input Format',
    'format_japanese' => 'Japanese Style',
    'format_japanese_desc' => 'Last/First name order, split postal code/phone, prefecture-based address',
    'format_western' => 'Western Style',
    'format_western_desc' => 'First/Last name order, single field postal code/phone, single field address',
    'format_style_help' => 'Switches name order (Last/First vs First/Last), postal code/phone number formats, and address input order.',

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

    'confirm_title' => 'Save Form Basic Settings',
    'confirm_message' => 'Are you sure you want to save the form basic settings?',
    'settings_updated' => 'Form basic settings have been updated.',
];
