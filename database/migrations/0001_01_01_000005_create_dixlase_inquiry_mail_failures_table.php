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

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Append-only log of inquiry mail-send failures.
     *
     * Columns intentionally carry metadata only -- no recipient address,
     * subject, body or inquiry fields. The point is to count failures
     * for the dashboard notification and to help operators triage SMTP
     * issues; the exception's own message still lands in laravel.log
     * for the full diagnostic, which is one place rather than two.
     */
    public function up(): void
    {
        Schema::create('plg_dixlase_inquiry_mail_failures', function (Blueprint $table) {
            $table->id();
            $table->timestamp('failed_at')->index();
            // Which controller path failed: send | submit | embed_send.
            $table->string('context', 32);
            // Whose mail failed: admin (operator notification) vs visitor (auto-reply).
            // Admin failures are what the silent-loss fix hinges on.
            $table->string('recipient_role', 16);
            // Mailable class basename, or 'raw' for Mail::raw() paths.
            $table->string('mailable', 128)->nullable();
            // Fully qualified exception class, e.g. Symfony\Component\Mailer\Exception\TransportException.
            $table->string('exception_class', 191);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('plg_dixlase_inquiry_mail_failures');
    }
};
