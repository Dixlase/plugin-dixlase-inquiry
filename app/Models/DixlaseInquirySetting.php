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

namespace Plugins\DixlaseInquiry\App\Models;

use Illuminate\Database\Eloquent\Model;

class DixlaseInquirySetting extends Model
{
    protected $table = 'plg_dixlase_inquiry_settings';

    protected $fillable = [
        'name',
        'value',
    ];

    /**
     * 設定値を取得
     */
    public static function get(string $name, $default = null)
    {
        $setting = self::where('name', $name)->first();

        return $setting ? $setting->value : $default;
    }

    /**
     * 設定値を保存
     */
    public static function set(string $name, $value): void
    {
        self::updateOrCreate(
            ['name' => $name],
            ['value' => $value]
        );
    }

    /**
     * 設定を取得（存在しない場合はデフォルト値を返す）
     */
    public static function getSettings()
    {
        $defaults = self::getDefaultSettings();
        $settings = new \stdClass();

        foreach ($defaults as $key => $defaultValue) {
            $value = self::get($key, $defaultValue);

            // boolean型の設定値を適切に変換
            if (is_bool($defaultValue)) {
                $settings->$key = filter_var($value, FILTER_VALIDATE_BOOLEAN);
            } else {
                $settings->$key = $value;
            }
        }

        return $settings;
    }

    /**
     * デフォルト設定値を取得
     */
    public static function getDefaultSettings()
    {
        return (object) [
            'accepting_inquiries' => true,
            'form_heading' => '',
            'form_description' => '',
            'admin_email' => '',
            'subject' => 'お問い合わせありがとうございます',
            'body' => "以下の内容でお問い合わせを受け付けました。\n\nお名前: {{name}}\nメールアドレス: {{email}}\n題名: {{subject}}\n郵便番号: {{postal_code}}\n住所: {{address}}\n電話番号: {{phone}}\n\nお問い合わせ内容:\n{{message}}",
            'show_phone' => false,
            'phone_required' => false,
            'show_address' => false,
            'address_required' => false,
            'show_subject' => false,
            'subject_required' => false,
            'postal_code_required' => false,
            'show_gender' => false,
            'gender_required' => false,
            'show_gender_other' => false,
            'show_gender_prefer_not_to_say' => false,
            'show_kana' => false,
            'require_kana' => false,
            'email_confirm_paste_disabled' => true,
            // Off by default: it mails any address a visitor enters (see the seeder).
            'auto_reply_enabled' => false,
            'auto_reply_from_email' => '',
            'auto_reply_subject' => 'お問い合わせを受け付けました',
            'auto_reply_body' => "この度は、お問い合わせいただきありがとうございます。\n\n以下の内容で承りました。\n内容を確認の上、担当者よりご連絡させていただきます。\n\nお名前: {{name}}\nメールアドレス: {{email}}\n題名: {{subject}}\n\nお問い合わせ内容:\n{{message}}\n\n今後ともよろしくお願いいたします。",
            'use_single_page' => true,
            'show_confirmation_page' => true,
            'inquiry_url_slug' => 'inquiry',
            'name_order_western' => 'auto',
            'lang' => 'auto',
            'privacy_consent_enabled' => false,
            'privacy_policy_url' => '',
            'privacy_consent_text' => '',
            'throttle_enabled' => true,
            'throttle_max_attempts' => 3,
            'throttle_decay_minutes' => 5,
            'completion_title' => '',
            'completion_message' => '',
            // Privacy: opt-in persistence. Off by default so a site that only
            // needs the email notification path never grows a plg_dixlase_inquiries
            // table of submitter PII without the operator explicitly deciding to.
            'store_inquiries' => false,
            // Days after submitted_at until the prune command deletes the row.
            // NULL means indefinite retention (no auto-prune); applies only when
            // store_inquiries is on.
            'retention_days' => 90,
        ];
    }

    /**
     * 設定を更新または作成
     */
    public static function updateSettings(array $data)
    {
        foreach ($data as $name => $value) {
            // boolean値を文字列に変換
            if (is_bool($value)) {
                $value = $value ? '1' : '0';
            }

            self::set($name, $value);
        }

        return true;
    }
}
