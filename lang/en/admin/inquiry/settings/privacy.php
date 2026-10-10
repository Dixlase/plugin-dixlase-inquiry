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
    'heading' => 'Privacy Settings',
    'description' => 'Control whether inquiry submissions are saved to the database and how long they are kept.',

    'intro' => 'When saving is off, submissions still trigger the configured notification and auto-reply mails, but no row is written to the inquiries table. Turn saving on only if you need an inquiry history that your operators will manage.',

    'store_section' => 'Database Storage',
    'store_label' => 'Save inquiries to the database',
    'store_help' => 'Off by default. Submissions always trigger mail; this toggle only controls whether a row is written for later review in the admin inbox.',

    'retention_section' => 'Retention Period',
    'retention_mode_label' => 'How long to keep each row',
    'retention_help' => 'The retention anchor is the submission timestamp. When a row\'s expiry passes, the prune command removes it. Setting this does not retroactively remove existing rows — those keep whatever expiry they had at submission time (edit per-row from the inquiry detail screen).',

    'retention_option_indefinite' => 'Indefinite (keep forever)',
    'retention_option_days' => ':days days',
    'retention_option_custom' => 'Custom',

    'retention_custom_label' => 'Custom retention (days)',
    'retention_custom_placeholder' => 'e.g. 60',
    'retention_custom_help' => 'Enter the number of days to keep each inquiry. Must be between 1 and 3650.',

    'confirm_title' => 'Save Privacy Settings',
    'confirm_message' => 'Are you sure you want to save the privacy settings? New submissions will follow the new policy; existing rows are unaffected.',
    'settings_updated' => 'Privacy settings have been updated.',
];
