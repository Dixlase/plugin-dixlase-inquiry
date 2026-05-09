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

            // Apply the form locale temporarily; restore after rendering.
            $originalLocale = app()->getLocale();
            app()->setLocale(
                \Plugins\DixlaseInquiry\App\Support\InquiryLocaleSupport::resolveFormLocale($settings->lang ?? 'auto')
            );

            // Materialise 'auto' name_order_western to a bool so the Blade
            // template can rely on a concrete value.
            $settings = clone $settings;
            $settings->name_order_western = \Plugins\DixlaseInquiry\App\Support\InquiryFormResolver::isWestern($settings);

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

            // CAPTCHA
            $captchaFormKey = 'dixlase-inquiry.inquiry_contact';
            $captchaEnabled = \App\Helpers\CaptchaHelper::shouldShowCaptcha($captchaFormKey);
            $captchaWidget = $captchaEnabled ? \App\Helpers\CaptchaHelper::renderWidget($captchaFormKey) : null;

            $formHtml = view('dixlase-inquiry::front.inquiries.embed-form', [
                'settings' => $settings,
                'options' => $options,
                'privacyUrl' => $privacyUrl,
                'genderOptions' => $genderOptions,
                'prefectures' => $prefectures,
                'captchaEnabled' => $captchaEnabled,
                'captchaWidget' => $captchaWidget,
            ])->render();

            // フォームレンダリング後にロケールを元に戻す
            app()->setLocale($originalLocale);

            // render() で文字列化すると @push が親レイアウトに届かないため、
            // アセットタグをフォームHTML出力に直接追加する
            $assets = load_plugin_assets('DixlaseInquiry', ['css/style.scss', 'js/app.js']);

            return $formHtml.$assets;
        } catch (\Exception $e) {
            app()->setLocale($originalLocale ?? app()->getLocale());
            \Log::error('dls_inquiry_form error: '.$e->getMessage());

            return null;
        }
    }
}

if (! function_exists('dls_inquiry_section')) {
    /**
     * 問い合わせセクション全体（見出し+説明文+フォームまたはリンクボタン）を表示する
     *
     * @return string|null レンダリングされたHTML、またはプラグインが無効な場合はnull
     */
    function dls_inquiry_section(): ?string
    {
        if (! function_exists('dls_inquiry_enabled') || ! dls_inquiry_enabled()) {
            return null;
        }

        try {
            $settings = DixlaseInquirySetting::getSettings();

            if (! $settings) {
                return null;
            }

            // シングルページモードならフォームHTMLを生成
            $formHtml = ($settings->use_single_page ?? true) ? dls_inquiry_form() : '';

            return view('dixlase-inquiry::front.inquiries.section', [
                'settings' => $settings,
                'formHtml' => $formHtml ?? '',
            ])->render();
        } catch (\Exception $e) {
            \Log::error('dls_inquiry_section error: '.$e->getMessage());

            return null;
        }
    }
}

if (! function_exists('dls_inquiry_settings')) {
    /**
     * 問い合わせ設定を取得する
     */
    function dls_inquiry_settings(): ?object
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

            return $settings && ! empty($settings->admin_email) && ($settings->accepting_inquiries ?? true);
        } catch (\Exception $e) {
            return false;
        }
    }
}

if (! function_exists('dls_inquiry_is_western')) {
    /**
     * Resolve whether the name order should be treated as western.
     *
     * Delegates to InquiryFormResolver so the locale fallback rules stay in
     * a single place (multilingual plugin → middleware-set locale; otherwise
     * → site basic-settings default).
     *
     * @param  object  $settings  問い合わせ設定オブジェクト
     */
    function dls_inquiry_is_western(object $settings): bool
    {
        return \Plugins\DixlaseInquiry\App\Support\InquiryFormResolver::isWestern($settings);
    }
}
