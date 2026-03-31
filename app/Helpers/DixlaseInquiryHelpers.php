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

use App\Contracts\PluginIntegration\PrivacyPolicyProviderInterface;
use Plugins\DixlaseInquiry\App\Models\DixlaseInquirySetting;

if (! function_exists('dls_inquiry_form')) {
    /**
     * 問い合わせフォームを表示する
     *
     * @param  array  $options  オプション設定
     *                          - 'class' => string 追加のCSSクラス
     *                          - 'id' => string カスタムID
     * @return string|null レンダリングされたHTML、またはプラグインが無効な場合はnull
     */
    function dls_inquiry_form(array $options = []): ?string
    {
        try {
            $settings = DixlaseInquirySetting::getSettings();

            if (! $settings || empty($settings->admin_email)) {
                return null;
            }

            // フォームロケールを適用（'auto'時は現在のロケールを維持）
            $formLocale = $settings->lang ?? 'auto';
            if ($formLocale !== 'auto') {
                app()->setLocale($formLocale);
            }

            // プライバシーポリシーURLを解決
            $privacyUrl = null;
            if (app()->bound(PrivacyPolicyProviderInterface::class)) {
                $provider = app(PrivacyPolicyProviderInterface::class);
                if ($provider->isPrivacyPolicyEnabled()) {
                    $privacyUrl = $provider->getPrivacyPolicyUrl();
                }
            }
            if (! $privacyUrl && ! empty($settings->privacy_policy_url)) {
                $privacyUrl = $settings->privacy_policy_url;
            }

            // 性別オプション配列
            $genderOptions = [
                ['value' => 'male', 'label' => __('dixlase-inquiry::front.form.gender_male'), 'icon' => 'fas fa-mars', 'color' => 'blue'],
                ['value' => 'female', 'label' => __('dixlase-inquiry::front.form.gender_female'), 'icon' => 'fas fa-venus', 'color' => 'red'],
            ];
            if ($settings->show_gender_other ?? false) {
                $genderOptions[] = ['value' => 'other', 'label' => __('dixlase-inquiry::front.form.gender_other'), 'icon' => 'fas fa-genderless', 'color' => 'purple'];
            }
            if ($settings->show_gender_prefer_not_to_say ?? false) {
                $genderOptions[] = ['value' => 'prefer_not_to_say', 'label' => __('dixlase-inquiry::front.form.gender_prefer_not_to_say'), 'icon' => 'fas fa-user-secret', 'color' => 'gray'];
            }

            // 都道府県リスト
            $prefectureList = __('dixlase-inquiry::front.prefectures');
            $prefectures = [];
            if (is_array($prefectureList)) {
                foreach ($prefectureList as $name) {
                    $prefectures[$name] = $name;
                }
            }

            $formHtml = view('dixlase-inquiry::front.inquiries.embed-form', [
                'settings' => $settings,
                'options' => $options,
                'privacyUrl' => $privacyUrl,
                'genderOptions' => $genderOptions,
                'prefectures' => $prefectures,
            ])->render();

            // render() で文字列化すると @push が親レイアウトに届かないため、
            // アセットタグをフォームHTML出力に直接追加する
            $assets = load_plugin_assets('DixlaseInquiry', ['css/style.scss', 'js/app.js']);

            return $formHtml.$assets;
        } catch (\Exception $e) {
            \Log::error('dls_inquiry_form error: '.$e->getMessage());

            return null;
        }
    }
}

if (! function_exists('dls_inquiry_settings')) {
    /**
     * 問い合わせ設定を取得する
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

if (! function_exists('dls_inquiry_enabled')) {
    /**
     * 問い合わせプラグインが有効かどうかを確認する
     */
    function dls_inquiry_enabled(): bool
    {
        try {
            $settings = DixlaseInquirySetting::getSettings();

            return $settings && ! empty($settings->admin_email);
        } catch (\Exception $e) {
            return false;
        }
    }
}
