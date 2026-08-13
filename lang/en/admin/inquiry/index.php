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
    'heading' => 'Inquiries',
    'description' => 'View and manage all inquiries received through the contact form.',

    'search_title' => 'Search',
    'search_placeholder' => 'Search by name, email, subject, message...',
    'status_filter' => 'Status',
    'all_statuses' => 'All Statuses',
    'unread_count' => ':count unread',

    // Bulk actions
    'bulk_selected' => ' selected',
    'bulk_select_status' => 'Select status',
    'bulk_apply' => 'Apply',
    'bulk_confirm_title' => 'Bulk Status Update',
    'bulk_confirm_message' => 'Update the status of selected inquiries. Are you sure?',
    'bulk_status_updated' => ':count inquiry status(es) updated.',

    'table' => [
        'caption' => 'List of inquiries',
        'id' => 'ID',
        'status' => 'Status',
        'name' => 'Name',
        'email' => 'Email',
        'subject' => 'Subject',
        'submitted_at' => 'Submitted',
        'actions' => 'Actions',
    ],

    'no_inquiries' => 'No inquiries found.',
    'view' => 'View',
    'delete' => 'Delete',
    // Wording reflects the soft-delete behaviour: destroy() moves the
    // inquiry to the trash rather than dropping the row, so the operator
    // has 30 days to restore before the retention cleanup runs.
    'confirm_delete_title' => 'Move to Trash',
    'confirm_delete' => 'This will move the inquiry to the trash. You can restore it from the trash within 30 days before it is permanently deleted.',
    'deleted' => 'The inquiry has been moved to the trash.',

    // Trash access from the index toolbar. The link is rendered only when
    // the current viewer can access the trash screen (per config/admin/roles.php).
    'view_trash' => 'View Trash',
];
