<?php

/**
 * This file is part of Dixlase Inquiry.
 *
 * Copyright (C) 2026 exc-D inc. and Dixlase contributors
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
    'heading' => 'Inquiry Detail',
    'description' => 'View inquiry details and manage its status.',

    'back_to_list' => 'Back to List',
    'inquiry_info' => 'Inquiry Information',
    'meta_info' => 'Meta Information',

    'name' => 'Name',
    'email' => 'Email',
    'subject' => 'Subject',
    'phone' => 'Phone',
    'postal_code' => 'Postal Code',
    'address' => 'Address',
    'gender' => 'Gender',
    'message' => 'Message',

    'status' => 'Status',
    'change_status' => 'Change Status',
    'submitted_at' => 'Submitted At',
    'read_at' => 'Read At',
    'ip_address' => 'IP Address',
    'user_agent' => 'User Agent',
    'lang' => 'Form Language',
    'privacy_agreed' => 'Privacy Consent',
    'privacy_agreed_yes' => 'Agreed at :date',
    'privacy_agreed_no' => 'Not agreed',
    'not_set' => '-',

    'delete' => 'Delete',
    'delete_confirm_title' => 'Delete Inquiry',
    'delete_confirm_message' => 'This will move the inquiry to the trash. You can restore it from the trash within 30 days before it is permanently deleted.',
    'status_updated' => 'The inquiry status has been updated.',

    // Per-row retention (expires_at) controls.
    'retention_title' => 'Retention',
    'retention_indefinite' => 'No expiry (indefinite)',
    'retention_days_remaining' => ':days days remaining',
    'retention_expires_today' => 'Expires today',
    'retention_expired' => 'Expired :days days ago',
    'retention_mode_label' => 'Change retention',
    'retention_option_indefinite' => 'Indefinite (no auto-delete)',
    'retention_option_days' => ':days days from today',
    'retention_option_custom' => 'Custom date',
    'retention_custom_date_label' => 'Expires at',
    'retention_update' => 'Update retention',
    'expires_at_updated' => 'Retention has been updated.',
];
