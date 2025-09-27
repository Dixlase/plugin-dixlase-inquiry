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

namespace Plugins\DixlaseInquiry\App\Models;

use Illuminate\Database\Eloquent\Model;

class InquirySetting extends Model
{
    protected $table = 'inquiry_settings';

    protected $fillable = [
        'admin_email',
        'subject',
        'body',
        'use_recaptcha',
        'show_phone',
        'phone_required',
        'show_address',
        'address_required',
        'show_subject',
        'subject_required',
        'show_postal_code',
        'postal_code_required',
        'auto_reply_enabled',
        'auto_reply_from_email',
        'auto_reply_subject',
        'auto_reply_body',
        'use_single_page',
        'show_confirmation_page',
        'name_order_western',
    ];

    protected $casts = [
        'use_recaptcha' => 'boolean',
        'show_phone' => 'boolean',
        'phone_required' => 'boolean',
        'show_address' => 'boolean',
        'address_required' => 'boolean',
        'show_subject' => 'boolean',
        'subject_required' => 'boolean',
        'show_postal_code' => 'boolean',
        'postal_code_required' => 'boolean',
        'auto_reply_enabled' => 'boolean',
        'use_single_page' => 'boolean',
        'show_confirmation_page' => 'boolean',
        'name_order_western' => 'boolean',
    ];

    /**
     * 設定を取得（存在しない場合はデフォルト値を返す）
     */
    public static function getSettings()
    {
        $settings = self::first();
        
        if (!$settings) {
            return self::getDefaultSettings();
        }
        
        return $settings;
    }

    /**
     * デフォルト設定値を取得
     */
    public static function getDefaultSettings()
    {
        return (object)[
            'admin_email' => '',
            'subject' => 'お問い合わせありがとうございます',
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
            'auto_reply_subject' => 'お問い合わせを受け付けました',
            'auto_reply_body' => "この度は、お問い合わせいただきありがとうございます。\n\n以下の内容で承りました。\n内容を確認の上、担当者よりご連絡させていただきます。\n\nお名前: {{name}}\nメールアドレス: {{email}}\n題名: {{subject}}\n\nお問い合わせ内容:\n{{message}}\n\n今後ともよろしくお願いいたします。",
            'use_single_page' => true,
            'show_confirmation_page' => true,
            'name_order_western' => false,
        ];
    }

    /**
     * 設定を更新または作成
     */
    public static function updateSettings(array $data)
    {
        $settings = self::first();
        
        if ($settings) {
            $settings->update($data);
        } else {
            self::create($data);
        }
        
        return true;
    }
}
