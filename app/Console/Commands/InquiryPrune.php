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

namespace Plugins\DixlaseInquiry\App\Console\Commands;

use Illuminate\Console\Command;
use Plugins\DixlaseInquiry\App\Models\DixlaseInquiry;

/**
 * Delete inquiries whose retention window has passed.
 *
 * An inquiry's `expires_at` is set at submission time from the
 * `retention_days` setting (null means indefinite retention and is left
 * alone). This command removes rows where `expires_at` is in the past.
 * By default it force-deletes; `--soft` moves rows to the trash so the
 * existing 30-day trash retention applies after that.
 *
 * Scheduled daily at 03:15 by DixlaseInquiryServiceProvider.
 */
class InquiryPrune extends Command
{
    protected $signature = 'dls:inquiry:prune
                            {--soft : Soft-delete into the trash instead of hard-deleting}
                            {--dry-run : Count affected rows without deleting}';

    protected $description = 'Prune inquiries whose expires_at is in the past.';

    public function handle(): int
    {
        $query = DixlaseInquiry::query()
            ->whereNotNull('expires_at')
            ->where('expires_at', '<', now());

        $count = $query->count();

        if ($count === 0) {
            $this->info('No expired inquiries to prune.');
            return self::SUCCESS;
        }

        if ($this->option('dry-run')) {
            $this->info("Would prune {$count} expired " . ($count === 1 ? 'inquiry' : 'inquiries') . ' (dry-run; no changes made).');
            return self::SUCCESS;
        }

        // Chunk by id so memory stays bounded on a large retention backlog
        // and each row goes through the model so later deleting / deleted
        // hooks (attachment cleanup etc.) still fire.
        $soft = (bool) $this->option('soft');
        $deleted = 0;

        $query->chunkById(500, function ($rows) use (&$deleted, $soft) {
            foreach ($rows as $row) {
                $soft ? $row->delete() : $row->forceDelete();
                $deleted++;
            }
        });

        if ($soft) {
            $this->info("Soft-deleted {$deleted} expired " . ($deleted === 1 ? 'inquiry' : 'inquiries') . ' (moved to trash).');
        } else {
            $this->info("Permanently deleted {$deleted} expired " . ($deleted === 1 ? 'inquiry' : 'inquiries') . '.');
        }

        return self::SUCCESS;
    }
}
