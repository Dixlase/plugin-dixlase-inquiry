<?php

/**
 * This file is part of DixlaseInquiry.
 *
 * Copyright (C) 2025 exc-D inc.
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

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('inquiry_settings', function (Blueprint $table) {
            $table->id();
            $table->string('admin_email');
            $table->string('subject')->default('お問い合わせありがとうございます');
            $table->text('body')->nullable();
            $table->boolean('use_recaptcha')->default(false);
            $table->boolean('show_phone')->default(true);
            $table->boolean('phone_required')->default(false);
            $table->boolean('show_address')->default(true);
            $table->boolean('address_required')->default(false);
            
            // お問い合わせ設定
            $table->boolean('show_subject')->default(true);
            $table->boolean('subject_required')->default(false);
            $table->boolean('show_postal_code')->default(true);
            $table->boolean('postal_code_required')->default(false);
            
            // 自動返信設定
            $table->boolean('auto_reply_enabled')->default(true);
            $table->string('auto_reply_from_email')->nullable();
            $table->string('auto_reply_subject')->default('お問い合わせを受け付けました');
            $table->text('auto_reply_body')->nullable();
            
            // フォーム表示設定
            $table->boolean('use_single_page')->default(true);
            $table->boolean('show_confirmation_page')->default(true);
            
            // 名前フィールドの設定（多言語対応）
            $table->boolean('name_order_western')->default(false); // false=姓名, true=名姓
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inquiry_settings');
    }
};