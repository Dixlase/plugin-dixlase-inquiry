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
 */

return [
    'heading' => 'Trashed Inquiries',
    'description' => 'Deleted inquiries stay here so you can restore them if the delete was a mistake.',

    'retention_notice' => 'Trashed inquiries are permanently deleted 30 days after removal. Restore anything you want to keep before then.',
    'gdpr_hint' => 'A privacy-driven deletion request is not satisfied while data still sits in the trash. Use Delete Permanently for those cases.',

    'back_to_index' => 'Back to inquiries',

    'table' => [
        'caption' => 'Trashed inquiries',
        'id' => 'ID',
        'name' => 'Name',
        'email' => 'Email',
        'subject' => 'Subject',
        'deleted_at' => 'Trashed',
        'actions' => 'Actions',
    ],

    'no_trashed_inquiries' => 'The trash is empty.',

    'restore' => 'Restore',
    'restore_success' => 'The inquiry has been restored.',

    'force_destroy' => 'Delete Permanently',
    'confirm_force_destroy_title' => 'Delete Permanently',
    'confirm_force_destroy' => 'This will permanently delete the inquiry and all of its personal data. This cannot be undone.',
    'force_destroy_success' => 'The inquiry has been permanently deleted.',

    'empty_trash' => 'Empty Trash',
    'confirm_empty_title' => 'Empty Trash',
    'confirm_empty' => 'This will permanently delete every trashed inquiry. This cannot be undone.',
    'empty_trash_success' => 'Emptied the trash (:count inquiry/inquiries permanently deleted).',
];
