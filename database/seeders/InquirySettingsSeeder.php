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

namespace Plugins\DixlaseInquiry\Database\Seeders;

use Illuminate\Database\Seeder;
use Plugins\DixlaseInquiry\App\Models\InquirySetting;

class InquirySettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // デフォルト設定が存在しない場合のみ作成
        if (!InquirySetting::exists()) {
            InquirySetting::create([
                'admin_email' => 'admin@example.com',
                'subject' => 'お問い合わせを受け付けました',
                'body' => "以下の内容でお問い合わせを受け付けました。\n\nお名前: {{name}}\nメールアドレス: {{email}}\n題名: {{subject}}\n郵便番号: {{postal_code}}\n住所: {{address}}\n電話番号: {{phone}}\n\nお問い合わせ内容:\n{{message}}",
                'use_recaptcha' => false,
                'show_phone' => true,
                'phone_required' => false,
                'show_address' => true,
                'address_required' => false,
                'show_subject' => true,
                'subject_required' => false,
                'show_postal_code' => true,
                'postal_code_required' => false,
                'auto_reply_enabled' => true,
                'auto_reply_from_email' => '',
                'auto_reply_subject' => 'お問い合わせありがとうございます',
                'auto_reply_body' => "この度は、お問い合わせいただきありがとうございます。\n\n以下の内容で承りました。\n内容を確認の上、担当者よりご連絡させていただきます。\n\nお名前: {{name}}\nメールアドレス: {{email}}\n題名: {{subject}}\n\nお問い合わせ内容:\n{{message}}\n\n今後ともよろしくお願いいたします。",
                'use_single_page' => true,
                'show_confirmation_page' => true,
                'name_order_western' => false,
            ]);
        }
    }
}
