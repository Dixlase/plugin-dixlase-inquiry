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
use Illuminate\Support\Facades\DB;
use Plugins\DixlaseInquiry\App\Models\InquirySetting;

class InquirySettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 基本設定からシステム管理者メールアドレスと言語設定を取得
        $systemAdminEmail = $this->getBaseSetting('system_admin_email', '');
        $locale = $this->getBaseSetting('locale', 'ja');

        // 言語ファイルからデフォルトテキストを取得
        $texts = $this->getLocalizedTexts($locale);

        $defaults = [
            // メールアドレス設定（基本設定のシステム管理者メールアドレスを使用）
            'admin_email' => $systemAdminEmail,
            'auto_reply_from_email' => $systemAdminEmail,

            // 管理者通知設定（言語別）
            'subject' => $texts['subject'],
            'body' => $texts['body'],

            // 自動返信設定（言語別）
            'auto_reply_enabled' => '1',
            'auto_reply_subject' => $texts['auto_reply_subject'],
            'auto_reply_body' => $texts['auto_reply_body'],

            // フォーム表示設定
            'use_recaptcha' => '0',
            'show_phone' => '1',
            'phone_required' => '0',
            'show_address' => '0',
            'address_required' => '0',
            'show_subject' => '0',
            'subject_required' => '0',
            'show_postal_code' => '0',
            'postal_code_required' => '0',
            'use_single_page' => '1',
            'show_confirmation_page' => '1',
            'inquiry_url_slug' => 'inquiry',
            'name_order_western' => $locale === 'en' ? '1' : '0',
        ];

        foreach ($defaults as $name => $value) {
            InquirySetting::updateOrCreate(
                ['name' => $name],
                ['value' => $value]
            );
        }
    }

    /**
     * 基本設定から値を取得
     */
    protected function getBaseSetting(string $name, mixed $default = null): mixed
    {
        $setting = DB::table('base_settings')->where('name', $name)->first();

        return $setting?->value ?? $default;
    }

    /**
     * 言語ファイルからデフォルトテキストを取得
     */
    protected function getLocalizedTexts(string $locale): array
    {
        // プラグインの言語ファイルパス
        $langPath = dirname(__DIR__, 2) . '/lang';

        // 指定言語のファイルを読み込み、なければ英語をフォールバック
        $file = "{$langPath}/{$locale}/admin.php";
        if (!file_exists($file)) {
            $file = "{$langPath}/en/admin.php";
        }

        $translations = require $file;
        $settings = $translations['settings'] ?? [];

        return [
            'subject' => $settings['admin_notification']['default_subject'] ?? 'Inquiry Received',
            'body' => $settings['admin_notification']['default_body'] ?? '',
            'auto_reply_subject' => $settings['auto_reply']['default_subject'] ?? 'Thank you for your inquiry',
            'auto_reply_body' => $settings['auto_reply']['default_body'] ?? '',
        ];
    }
}
