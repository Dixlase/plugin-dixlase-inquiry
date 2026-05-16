<?php

/**
 * This file is part of Dixlase Inquiry.
 *
 * Copyright (C) 2026 exc-D inc.
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

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Aggregate row that gives DixlaseMultilingual a stable
     * `translatable_id` to anchor inquiry-settings translations against.
     *
     * Inquiry settings live in the key-value `plg_dixlase_inquiry_settings`
     * table, which has no row that the polymorphic translations table
     * (`plg_dixlase_multilingual_translations`) can point at via
     * `(translatable_type, translatable_id)`. The aggregate exists solely
     * to provide that anchor: it carries no business columns, and the
     * primary-locale value for each translatable setting is still read
     * from the key-value table (see DixlaseInquirySettingsAggregate::
     * getOriginalValue()).
     *
     * Singleton-style: exactly one row site-wide is seeded at install.
     * If/when the inquiry settings table itself gains multi-site
     * partitioning, this aggregate can be widened to one row per site
     * and the model can adopt BelongsToSite at that point.
     */
    public function up(): void
    {
        Schema::create('plg_dixlase_inquiry_settings_aggregate', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('plg_dixlase_inquiry_settings_aggregate');
    }
};
