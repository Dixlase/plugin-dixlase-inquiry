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

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('plg_dixlase_inquiries', function (Blueprint $table) {
            // deleted_at powers Eloquent's SoftDeletes trait: the admin
            // list becomes reversible (destroy() sets this column instead
            // of dropping the row), the trash screen filters on it, and
            // the 30-day cleanup job forceDeletes rows older than the
            // retention window.
            $table->softDeletes();

            // The list and trash queries both filter on (status, deleted_at):
            // the list adds a WHERE deleted_at IS NULL, the trash view adds
            // WHERE deleted_at IS NOT NULL, and both usually filter by status
            // on top. A composite index lets either query short-circuit
            // instead of walking every row once submissions grow.
            $table->index(['status', 'deleted_at']);
        });
    }

    public function down(): void
    {
        Schema::table('plg_dixlase_inquiries', function (Blueprint $table) {
            $table->dropIndex(['status', 'deleted_at']);
            $table->dropSoftDeletes();
        });
    }
};
