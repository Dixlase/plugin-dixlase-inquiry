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

/**
 * Database cleanup jobs exposed to core's DatabaseCleanupService.
 *
 * Registered here so the admin cleanup screen can list this plugin's
 * retention jobs alongside core's built-ins, and `php artisan
 * dls:cleanup --type=plugin:dixlase-inquiry:soft_deleted_inquiries` can
 * be run manually or wired into a scheduled task.
 *
 * Schema per entry (see core `app/Services/DatabaseCleanupService.php`):
 *   - `table`             (required) fully qualified table name
 *   - `date_column`       (required) column compared against the cutoff
 *   - `default_days`      (required) retention window; int days, or
 *                         null when the entry uses `additional_conditions`
 *   - `description`       locale array or a translation key
 *   - `enabled`           default true; set false to hide from the UI
 *   - `date_column_type`  `datetime` | `timestamp`
 */

return [
    // Permanent-delete rows that have been in the trash longer than the
    // retention window. Pairs with the SoftDeletes trait on
    // `DixlaseInquiry` — `destroy()` sets `deleted_at`, restore/force
    // exit through the trash UI, and this job sweeps whatever was left
    // once the visitor PII no longer needs to be kept.
    //
    // 30 days matches typical operator expectation for "I deleted this,
    // it should really be gone soon" while still giving several weeks
    // to notice an accidental removal.
    'soft_deleted_inquiries' => [
        'table' => 'plg_dixlase_inquiries',
        'date_column' => 'deleted_at',
        'default_days' => 30,
        'description' => [
            'ja' => 'ゴミ箱内の問い合わせ（削除から一定期間経過したもの）',
            'en' => 'Trashed inquiries older than the retention window',
        ],
        'enabled' => true,
        'date_column_type' => 'datetime',
    ],
];
