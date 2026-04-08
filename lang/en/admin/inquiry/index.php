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
    'confirm_delete_title' => 'Delete Inquiry',
    'confirm_delete' => 'Are you sure you want to delete this inquiry? This action cannot be undone.',
    'deleted' => 'The inquiry has been deleted.',
];
