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

namespace Plugins\DixlaseInquiry\App\Traits;

use App\Contracts\PluginIntegration\PrivacyPolicyProviderInterface;
use Illuminate\Http\Request;
use Plugins\DixlaseInquiry\App\Enums\InquiryStatus;
use Plugins\DixlaseInquiry\App\Models\DixlaseInquiry;

/**
 * フォームデータ処理の共通トレイト
 * FrontController / AdminController のプレビュー機能で共有
 */
trait InquiryFormDataTrait
{
    /**
     * CAPTCHA フォームキー（{slug}.{form_key} 形式）
     * CaptchaService がプラグインslugをプレフィックスに付与するため
     */
    protected const CAPTCHA_FORM_KEY = 'dixlase-inquiry.inquiry_contact';

    /**
     * Apply the form locale to the application.
     *
     * Resolution is delegated to InquiryLocaleSupport::resolveFormLocale so
     * the multilingual plugin's settings (when active) and the site's basic
     * settings (when not) are honoured consistently. After locale resolution
     * the 'auto' name_order_western value is materialised to a bool for views
     * and validation.
     */
    protected function applyFormLocale(object $settings): void
    {
        app()->setLocale(
            \Plugins\DixlaseInquiry\App\Support\InquiryLocaleSupport::resolveFormLocale($settings->lang ?? 'auto')
        );

        $settings->name_order_western = \Plugins\DixlaseInquiry\App\Support\InquiryFormResolver::isWestern($settings);
    }

    /**
     * プライバシーポリシーURLを解決
     */
    protected function resolvePrivacyPolicyUrl(object $settings): ?string
    {
        // 法務プラグインが登録されていれば優先
        if (app()->bound(PrivacyPolicyProviderInterface::class)) {
            $provider = app(PrivacyPolicyProviderInterface::class);
            if ($provider->isPrivacyPolicyEnabled()) {
                return $provider->getPrivacyPolicyUrl();
            }
        }

        return ! empty($settings->privacy_policy_url) ? $settings->privacy_policy_url : null;
    }

    /**
     * 性別オプション配列（ラジオカード用）
     * 設定に応じて有効な選択肢のみ返す
     *
     * @return array<int, array{value: string, label: string, icon: string, color: string}>
     */
    protected function getGenderOptions(object $settings): array
    {
        $options = [
            ['value' => 'male', 'label' => __('dixlase-inquiry::front.form.gender_male'), 'icon' => 'fas fa-mars', 'color' => 'blue'],
            ['value' => 'female', 'label' => __('dixlase-inquiry::front.form.gender_female'), 'icon' => 'fas fa-venus', 'color' => 'red'],
        ];

        if ($settings->show_gender_other ?? false) {
            $options[] = ['value' => 'other', 'label' => __('dixlase-inquiry::front.form.gender_other'), 'icon' => 'fas fa-genderless', 'color' => 'purple'];
        }

        if ($settings->show_gender_prefer_not_to_say ?? false) {
            $options[] = ['value' => 'prefer_not_to_say', 'label' => __('dixlase-inquiry::front.form.gender_prefer_not_to_say'), 'icon' => 'fas fa-user-secret', 'color' => 'gray'];
        }

        return $options;
    }

    /**
     * 都道府県リスト取得
     *
     * @return array<string, string>
     */
    protected function getPrefectures(): array
    {
        $prefectures = __('dixlase-inquiry::front.prefectures');

        if (! is_array($prefectures)) {
            return [];
        }

        $result = [];
        foreach ($prefectures as $name) {
            $result[$name] = $name;
        }

        return $result;
    }

    /**
     * 問い合わせデータを準備
     * 分割フィールドの結合も行う
     *
     * @return array{name: string, name_kana: ?string, email: string, subject: ?string, phone: ?string, postal_code: ?string, address: ?string, gender: ?string, gender_value: ?string, message: string}
     */
    protected function prepareInquiryData(array $validated, object $settings): array
    {
        $isWestern = \Plugins\DixlaseInquiry\App\Support\InquiryFormResolver::isWestern($settings);

        // 名前を結合
        $fullName = $isWestern
            ? trim(($validated['first_name'] ?? '').' '.($validated['last_name'] ?? ''))
            : trim(($validated['last_name'] ?? '').' '.($validated['first_name'] ?? ''));

        // カタカナ名前を結合（日本式のみ）
        $nameKana = null;
        if (! $isWestern && ($settings->show_kana ?? false)) {
            $lastKana = $validated['last_name_kana'] ?? '';
            $firstKana = $validated['first_name_kana'] ?? '';
            if ($lastKana || $firstKana) {
                $nameKana = trim($lastKana.' '.$firstKana);
            }
        }

        // 郵便番号の結合
        $postalCode = $this->mergePostalCode($validated, $isWestern);

        // 住所の結合
        $address = $this->mergeAddress($validated, $isWestern);

        // 電話番号の結合
        $phone = $this->mergePhone($validated, $isWestern);

        return [
            'name' => $fullName,
            'name_kana' => $nameKana,
            'email' => $validated['email'] ?? '',
            'subject' => $validated['subject'] ?? null,
            'phone' => $phone,
            'postal_code' => $postalCode,
            'address' => $address,
            'gender' => isset($validated['gender']) ? $this->getGenderLabel($validated['gender']) : null,
            'gender_value' => $validated['gender'] ?? null,
            'message' => $validated['message'] ?? '',
        ];
    }

    /**
     * Persist the inquiry when the opt-in setting is on; otherwise skip.
     *
     * Returns null when the site has `store_inquiries` off, so submissions
     * still trigger the configured notification and auto-reply mails but no
     * row is written. When persistence is on, the row's `expires_at` is set
     * from `retention_days` (null = indefinite retention; applied by the
     * prune command).
     *
     * `send()` / `embedSend()` / `submit()` do not use the return value;
     * `previewSend()` already handles the null case for its complete view.
     */
    protected function saveInquiry(array $inquiryData, Request $request, object $settings): ?DixlaseInquiry
    {
        if (! $this->shouldPersistInquiry($settings)) {
            return null;
        }

        $retentionDays = $this->normalizeRetentionDays($settings->retention_days ?? null);
        $expiresAt = $retentionDays !== null ? now()->addDays($retentionDays) : null;

        return DixlaseInquiry::create([
            'status' => InquiryStatus::New,
            'name' => $inquiryData['name'],
            'name_kana' => $inquiryData['name_kana'] ?? null,
            'email' => $inquiryData['email'],
            'subject' => $inquiryData['subject'],
            'phone' => $inquiryData['phone'],
            'postal_code' => $inquiryData['postal_code'],
            'address' => $inquiryData['address'],
            'gender' => $inquiryData['gender_value'] ?? null,
            'message' => $inquiryData['message'],
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'lang' => $settings->lang ?? 'ja',
            'privacy_agreed_at' => $request->has('privacy_agreed') ? now() : null,
            'submitted_at' => now(),
            'expires_at' => $expiresAt,
        ]);
    }

    /**
     * Decide whether this submission should be written to plg_dixlase_inquiries.
     *
     * The KV setting is stored as '0' / '1'; filter_var normalises both the
     * stored string and a direct boolean default so callers can safely pass
     * the value from DixlaseInquirySetting::getSettings() without extra casts.
     */
    protected function shouldPersistInquiry(object $settings): bool
    {
        return filter_var($settings->store_inquiries ?? false, FILTER_VALIDATE_BOOLEAN);
    }

    /**
     * Normalise the retention_days setting into a positive integer or null.
     *
     * Returns null for null, empty string, zero, or any non-positive value
     * (indefinite retention). Returns a positive int otherwise. Callers add
     * this many days to submitted_at to compute expires_at.
     */
    protected function normalizeRetentionDays(mixed $value): ?int
    {
        if ($value === null || $value === '') {
            return null;
        }
        $days = (int) $value;
        return $days > 0 ? $days : null;
    }

    /**
     * 郵便番号を結合
     * 日本式: postal_code_1 + '-' + postal_code_2
     * 欧米式: postal_code そのまま
     */
    protected function mergePostalCode(array $validated, bool $isWestern): ?string
    {
        if ($isWestern) {
            return $validated['postal_code'] ?? null;
        }

        $part1 = $validated['postal_code_1'] ?? null;
        $part2 = $validated['postal_code_2'] ?? null;

        if ($part1 && $part2) {
            return $part1.'-'.$part2;
        }

        // 旧形式のフォールバック
        return $validated['postal_code'] ?? null;
    }

    /**
     * 住所を結合
     * 日本式: 都道府県 + 市区町村 + 番地 + 建物名
     * 欧米式: street_address, building, city, state, postal_code, country
     */
    protected function mergeAddress(array $validated, bool $isWestern): ?string
    {
        if ($isWestern) {
            $parts = array_filter([
                $validated['street_address'] ?? null,
                $validated['building'] ?? null,
                $validated['city'] ?? null,
                $validated['state'] ?? null,
                $validated['country'] ?? null,
            ]);

            return ! empty($parts) ? implode(', ', $parts) : ($validated['address'] ?? null);
        }

        // 日本式分割フィールド
        $prefecture = $validated['prefecture'] ?? null;
        $city = $validated['city'] ?? null;
        $addressLine = $validated['address_line'] ?? null;
        $building = $validated['building'] ?? null;

        if ($prefecture || $city || $addressLine) {
            $combined = ($prefecture ?? '').($city ?? '').($addressLine ?? '');
            if ($building) {
                $combined .= ' '.$building;
            }

            return trim($combined) ?: null;
        }

        // 旧形式のフォールバック
        return $validated['address'] ?? null;
    }

    /**
     * 電話番号を結合
     * 日本式: phone_1 + '-' + phone_2 + '-' + phone_3
     * 欧米式: phone そのまま
     */
    protected function mergePhone(array $validated, bool $isWestern): ?string
    {
        if ($isWestern) {
            return $validated['phone'] ?? null;
        }

        $part1 = $validated['phone_1'] ?? null;
        $part2 = $validated['phone_2'] ?? null;
        $part3 = $validated['phone_3'] ?? null;

        if ($part1 && $part2 && $part3) {
            return $part1.'-'.$part2.'-'.$part3;
        }

        // 旧形式のフォールバック
        return $validated['phone'] ?? null;
    }

    /**
     * 性別のラベルを取得
     */
    protected function getGenderLabel(?string $gender): ?string
    {
        if (empty($gender)) {
            return null;
        }

        $labels = [
            'male' => __('dixlase-inquiry::front.form.gender_male'),
            'female' => __('dixlase-inquiry::front.form.gender_female'),
            'non_binary' => __('dixlase-inquiry::front.form.gender_non_binary'),
            'other' => __('dixlase-inquiry::front.form.gender_other'),
            'prefer_not_to_say' => __('dixlase-inquiry::front.form.gender_prefer_not_to_say'),
        ];

        return $labels[$gender] ?? $gender;
    }
}
