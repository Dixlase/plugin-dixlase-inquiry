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

namespace Plugins\DixlaseInquiry\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Plugins\DixlaseInquiry\App\Models\DixlaseInquirySetting;

class InquirySettingsSeeder extends Seeder
{
    /**
     * シーダー実行
     */
    public function run(): void
    {
        // 基本設定からシステム管理者メールアドレスと言語設定を取得
        $systemAdminEmail = $this->getBaseSetting('system_admin_email', '');
        $locale = $this->getBaseSetting('locale', 'ja');

        // 言語ファイルからデフォルトテキストを取得
        $texts = $this->getLocalizedTexts($locale);

        $defaults = [
            // 問い合わせ受付状態
            'accepting_inquiries' => '1',

            // フォーム見出し・説明（インストール時のロケールに基づくデフォルト値）
            'form_heading' => $texts['form_heading'],
            'form_description' => $texts['form_description'],

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

            // 完了ページ設定（言語別）
            'completion_title' => $texts['completion_title'],
            'completion_message' => $texts['completion_message'],

            // フォーム表示設定
            'show_phone' => '0',
            'phone_required' => '0',
            'show_address' => '0',
            'address_required' => '0',
            'postal_code_required' => '0',
            'show_subject' => '0',
            'subject_required' => '0',
            'show_gender_other' => '0',
            'show_gender_prefer_not_to_say' => '0',
            'email_confirm_paste_disabled' => '1',
            'use_single_page' => '1',
            'show_confirmation_page' => '1',
            'inquiry_url_slug' => 'inquiry',
            'name_order_western' => $locale === 'en' ? '1' : '0',
            'lang' => $locale,
        ];

        foreach ($defaults as $name => $value) {
            DixlaseInquirySetting::updateOrCreate(
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
        $langPath = dirname(__DIR__, 2).'/lang';

        // 管理者通知の翻訳ファイル読み込み
        $adminNotificationFile = "{$langPath}/{$locale}/admin/inquiry/settings/admin-notification.php";
        if (! file_exists($adminNotificationFile)) {
            $adminNotificationFile = "{$langPath}/en/admin/inquiry/settings/admin-notification.php";
        }
        $adminNotification = require $adminNotificationFile;

        // 自動返信の翻訳ファイル読み込み
        $autoReplyFile = "{$langPath}/{$locale}/admin/inquiry/settings/auto-reply.php";
        if (! file_exists($autoReplyFile)) {
            $autoReplyFile = "{$langPath}/en/admin/inquiry/settings/auto-reply.php";
        }
        $autoReply = require $autoReplyFile;

        // フロント翻訳ファイル読み込み（見出し・説明）
        $frontFile = "{$langPath}/{$locale}/front.php";
        if (! file_exists($frontFile)) {
            $frontFile = "{$langPath}/en/front.php";
        }
        $front = require $frontFile;

        // 完了ページの翻訳ファイル読み込み
        $completionFile = "{$langPath}/{$locale}/admin/inquiry/settings/completion.php";
        if (! file_exists($completionFile)) {
            $completionFile = "{$langPath}/en/admin/inquiry/settings/completion.php";
        }
        $completion = require $completionFile;

        return [
            'form_heading' => $front['form']['heading'] ?? 'Contact Us',
            'form_description' => $front['form']['default_description'] ?? '',
            'subject' => $adminNotification['default_subject'] ?? 'Inquiry Received',
            'body' => $adminNotification['default_body'] ?? '',
            'auto_reply_subject' => $autoReply['default_subject'] ?? 'Thank you for your inquiry',
            'auto_reply_body' => $autoReply['default_body'] ?? '',
            'completion_title' => $completion['default_title'] ?? 'Message Sent',
            'completion_message' => $completion['default_message'] ?? '',
        ];
    }
}
