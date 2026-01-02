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

namespace Plugins\DixlaseInquiry\App\Shortcodes;

use Plugins\DixlaseInquiry\App\Models\DixlaseInquirySetting;

class DixlaseInquiryFormShortcode
{
    /**
     * ショートコードをレンダリング
     *
     * @param array $attributes ショートコードの属性
     * @param string|null $content ショートコードの内容
     * @return string
     */
    public function render($attributes = [], $content = null)
    {
        // 問い合わせ設定を取得
        $settings = DixlaseInquirySetting::getSettings();
        
        // 埋め込みフォームをレンダリング
        try {
            return view('dixlase-inquiry::front.inquiries.embed-form', [
                'settings' => $settings,
                'attributes' => $attributes,
            ])->render();
        } catch (\Exception $e) {
            \Log::error('InquiryFormShortcode render error: ' . $e->getMessage());
            return '<!-- Inquiry form error: ' . e($e->getMessage()) . ' -->';
        }
    }
}