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

namespace Plugins\DixlaseInquiry\App\Shortcodes;

use App\Contracts\PluginIntegration\PrivacyPolicyProviderInterface;
use Plugins\DixlaseInquiry\App\Models\DixlaseInquirySetting;

class DixlaseInquiryFormShortcode
{
    /**
     * ショートコードをレンダリング
     *
     * @param array<string, mixed> $attributes ショートコードの属性
     * @param string|null $content ショートコードの内容
     */
    public function render(array $attributes = [], ?string $content = null): string
    {
        // 問い合わせ設定を取得
        $settings = DixlaseInquirySetting::getSettings();

        // フォームロケールを適用（'auto'時は現在のロケールを維持）
        $formLocale = $settings->lang ?? 'auto';
        if ($formLocale !== 'auto') {
            app()->setLocale($formLocale);
        }

        // プライバシーポリシーURLを解決
        $privacyUrl = $this->resolvePrivacyPolicyUrl($settings);

        // 性別オプション配列
        $genderOptions = [
            ['value' => 'male', 'label' => __('dixlase-inquiry::front.form.gender_male'), 'icon' => 'fas fa-mars', 'color' => 'blue'],
            ['value' => 'female', 'label' => __('dixlase-inquiry::front.form.gender_female'), 'icon' => 'fas fa-venus', 'color' => 'red'],
            ['value' => 'other', 'label' => __('dixlase-inquiry::front.form.gender_other'), 'icon' => 'fas fa-genderless', 'color' => 'purple'],
            ['value' => 'prefer_not_to_say', 'label' => __('dixlase-inquiry::front.form.gender_prefer_not_to_say'), 'icon' => 'fas fa-user-secret', 'color' => 'gray'],
        ];

        // 都道府県リスト
        $prefectureList = __('dixlase-inquiry::front.prefectures');
        $prefectures = [];
        if (is_array($prefectureList)) {
            foreach ($prefectureList as $name) {
                $prefectures[$name] = $name;
            }
        }

        // 埋め込みフォームをレンダリング
        try {
            return view('dixlase-inquiry::front.inquiries.embed-form', [
                'settings' => $settings,
                'attributes' => $attributes,
                'privacyUrl' => $privacyUrl,
                'genderOptions' => $genderOptions,
                'prefectures' => $prefectures,
            ])->render();
        } catch (\Exception $e) {
            \Log::error('InquiryFormShortcode render error: ' . $e->getMessage());
            return '<!-- Inquiry form error: ' . e($e->getMessage()) . ' -->';
        }
    }

    /**
     * プライバシーポリシーURLを解決
     */
    private function resolvePrivacyPolicyUrl(object $settings): ?string
    {
        if (app()->bound(PrivacyPolicyProviderInterface::class)) {
            $provider = app(PrivacyPolicyProviderInterface::class);
            if ($provider->isPrivacyPolicyEnabled()) {
                return $provider->getPrivacyPolicyUrl();
            }
        }

        return !empty($settings->privacy_policy_url) ? $settings->privacy_policy_url : null;
    }
}
