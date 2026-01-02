<?php

/**
 * This file is part of Dixlase Inquiry.
 *
 * Copyright (C) 2026 exc-D inc.
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

use Plugins\DixlaseInquiry\App\Models\DixlaseInquirySetting;

if (!function_exists('dls_inquiry_form')) {
    /**
     * 問い合わせフォームを表示する
     * 
     * @param array $options オプション設定
     *   - 'class' => string 追加のCSSクラス
     *   - 'id' => string カスタムID
     * @return string|null レンダリングされたHTML、またはプラグインが無効な場合はnull
     */
    function dls_inquiry_form(array $options = []): ?string
    {
        try {
            $settings = DixlaseInquirySetting::getSettings();
            
            if (!$settings || empty($settings->admin_email)) {
                return null;
            }
            
            return view('dixlase-inquiry::front.inquiries.embed-form', [
                'settings' => $settings,
                'options' => $options,
            ])->render();
        } catch (\Exception $e) {
            \Log::error('dls_inquiry_form error: ' . $e->getMessage());
            return null;
        }
    }
}

if (!function_exists('dls_inquiry_settings')) {
    /**
     * 問い合わせ設定を取得する
     * 
     * @return \Plugins\DixlaseInquiry\App\Models\DixlaseInquirySetting|null
     */
    function dls_inquiry_settings(): ?DixlaseInquirySetting
    {
        try {
            return DixlaseInquirySetting::getSettings();
        } catch (\Exception $e) {
            return null;
        }
    }
}

if (!function_exists('dls_inquiry_enabled')) {
    /**
     * 問い合わせプラグインが有効かどうかを確認する
     * 
     * @return bool
     */
    function dls_inquiry_enabled(): bool
    {
        try {
            $settings = DixlaseInquirySetting::getSettings();
            return $settings && !empty($settings->admin_email);
        } catch (\Exception $e) {
            return false;
        }
    }
}
