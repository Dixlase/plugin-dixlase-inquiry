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

namespace Plugins\DixlaseInquiry\App\Http\Controllers\Front;

use App\Contracts\PluginIntegration\PrivacyPolicyProviderInterface;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Plugins\DixlaseInquiry\App\Enums\InquiryStatus;
use Plugins\DixlaseInquiry\App\Http\Requests\DixlaseInquirySubmitRequest;
use Plugins\DixlaseInquiry\App\Mail\DixlaseInquiryAdminNotification;
use Plugins\DixlaseInquiry\App\Mail\DixlaseInquiryAutoReply;
use Plugins\DixlaseInquiry\App\Models\DixlaseInquiry;
use Plugins\DixlaseInquiry\App\Models\DixlaseInquirySetting;
use Plugins\DixlaseInquiry\App\Http\Requests\Front\DixlaseInquiryEmbedSendRequest;

class DixlaseInquiryFrontController extends Controller
{
    public function __construct()
    {
    }

    /**
     * 問い合わせフォーム表示（別ページモード）
     */
    public function index()
    {
        $settings = DixlaseInquirySetting::getSettings();

        // シングルページモードの場合は404
        if ($settings->use_single_page) {
            abort(404);
        }

        $this->applyFormLocale($settings);

        $privacyUrl = $this->resolvePrivacyPolicyUrl($settings);

        return view('dixlase-inquiry::front.inquiries.form', [
            'settings' => $settings,
            'privacyUrl' => $privacyUrl,
            'genderOptions' => $this->getGenderOptions(),
            'prefectures' => $this->getPrefectures(),
        ]);
    }

    /**
     * 確認画面表示（別ページモード）
     */
    public function confirm(Request $request)
    {
        $settings = DixlaseInquirySetting::getSettings();

        // シングルページモードの場合は404
        if ($settings->use_single_page) {
            abort(404);
        }

        // 確認画面が無効の場合は404
        if (!$settings->show_confirmation_page) {
            abort(404);
        }

        $this->applyFormLocale($settings);

        return view('dixlase-inquiry::front.inquiries.confirm', [
            'settings' => $settings,
            'data' => $request->all(),
            'genderOptions' => $this->getGenderOptions(),
        ]);
    }

    /**
     * 送信処理（別ページモード）
     */
    public function send(Request $request)
    {
        $settings = DixlaseInquirySetting::getSettings();

        // シングルページモードの場合は404
        if ($settings->use_single_page) {
            abort(404);
        }

        $this->applyFormLocale($settings);

        $validated = $request->all();

        // 問い合わせデータを準備
        $inquiryData = $this->prepareInquiryData($validated, $settings);

        // DB保存
        $inquiry = $this->saveInquiry($inquiryData, $request, $settings);

        // メール送信（失敗してもDB保存は維持）
        try {
            Mail::to($settings->admin_email)->send(new DixlaseInquiryAdminNotification($inquiryData, $settings));

            if ($settings->auto_reply_enabled && !empty($validated['email'])) {
                Mail::to($validated['email'])->send(new DixlaseInquiryAutoReply($inquiryData, $settings));
            }
        } catch (\Exception $e) {
            \Log::error('Inquiry mail send failed: ' . $e->getMessage());
        }

        return view('dixlase-inquiry::front.inquiries.complete', [
            'settings' => $settings,
        ]);
    }

    /**
     * 問い合わせデータを準備
     * 分割フィールドの結合も行う
     */
    private function prepareInquiryData(array $validated, $settings): array
    {
        $isWestern = (bool) ($settings->name_order_western ?? false);

        // 名前を結合
        $fullName = $isWestern
            ? trim(($validated['first_name'] ?? '') . ' ' . ($validated['last_name'] ?? ''))
            : trim(($validated['last_name'] ?? '') . ' ' . ($validated['first_name'] ?? ''));

        // カタカナ名前を結合（日本式のみ）
        $nameKana = null;
        if (!$isWestern && ($settings->show_kana ?? false)) {
            $lastKana = $validated['last_name_kana'] ?? '';
            $firstKana = $validated['first_name_kana'] ?? '';
            if ($lastKana || $firstKana) {
                $nameKana = trim($lastKana . ' ' . $firstKana);
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
     * 郵便番号を結合
     * 日本式: postal_code_1 + '-' + postal_code_2
     * 欧米式: postal_code そのまま
     */
    private function mergePostalCode(array $validated, bool $isWestern): ?string
    {
        if ($isWestern) {
            return $validated['postal_code'] ?? null;
        }

        $part1 = $validated['postal_code_1'] ?? null;
        $part2 = $validated['postal_code_2'] ?? null;

        if ($part1 && $part2) {
            return $part1 . '-' . $part2;
        }

        // 旧形式のフォールバック
        return $validated['postal_code'] ?? null;
    }

    /**
     * 住所を結合
     * 日本式: 都道府県 + 市区町村 + 番地 + 建物名
     * 欧米式: street_address, building, city, state, postal_code, country
     */
    private function mergeAddress(array $validated, bool $isWestern): ?string
    {
        if ($isWestern) {
            $parts = array_filter([
                $validated['street_address'] ?? null,
                $validated['building'] ?? null,
                $validated['city'] ?? null,
                $validated['state'] ?? null,
                $validated['country'] ?? null,
            ]);

            return !empty($parts) ? implode(', ', $parts) : ($validated['address'] ?? null);
        }

        // 日本式分割フィールド
        $prefecture = $validated['prefecture'] ?? null;
        $city = $validated['city'] ?? null;
        $addressLine = $validated['address_line'] ?? null;
        $building = $validated['building'] ?? null;

        if ($prefecture || $city || $addressLine) {
            $combined = ($prefecture ?? '') . ($city ?? '') . ($addressLine ?? '');
            if ($building) {
                $combined .= ' ' . $building;
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
    private function mergePhone(array $validated, bool $isWestern): ?string
    {
        if ($isWestern) {
            return $validated['phone'] ?? null;
        }

        $part1 = $validated['phone_1'] ?? null;
        $part2 = $validated['phone_2'] ?? null;
        $part3 = $validated['phone_3'] ?? null;

        if ($part1 && $part2 && $part3) {
            return $part1 . '-' . $part2 . '-' . $part3;
        }

        // 旧形式のフォールバック
        return $validated['phone'] ?? null;
    }

    /**
     * 性別のラベルを取得
     */
    private function getGenderLabel(?string $gender): ?string
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

    /**
     * 性別オプション配列（ラジオカード用）
     *
     * @return array<int, array{value: string, label: string, icon: string, color: string}>
     */
    private function getGenderOptions(): array
    {
        return [
            ['value' => 'male', 'label' => __('dixlase-inquiry::front.form.gender_male'), 'icon' => 'fas fa-mars', 'color' => 'blue'],
            ['value' => 'female', 'label' => __('dixlase-inquiry::front.form.gender_female'), 'icon' => 'fas fa-venus', 'color' => 'red'],
            ['value' => 'other', 'label' => __('dixlase-inquiry::front.form.gender_other'), 'icon' => 'fas fa-genderless', 'color' => 'purple'],
            ['value' => 'prefer_not_to_say', 'label' => __('dixlase-inquiry::front.form.gender_prefer_not_to_say'), 'icon' => 'fas fa-user-secret', 'color' => 'gray'],
        ];
    }

    /**
     * 都道府県リスト取得
     *
     * @return array<string, string>
     */
    private function getPrefectures(): array
    {
        $prefectures = __('dixlase-inquiry::front.prefectures');

        if (!is_array($prefectures)) {
            return [];
        }

        $result = [];
        foreach ($prefectures as $name) {
            $result[$name] = $name;
        }

        return $result;
    }

    /**
     * フォームロケールを適用
     * 'auto'の場合は現在のロケールを維持
     */
    private function applyFormLocale(object $settings): void
    {
        $locale = $settings->form_locale ?? 'auto';
        if ($locale !== 'auto') {
            app()->setLocale($locale);
        }
    }

    /**
     * 問い合わせをDBに保存
     */
    private function saveInquiry(array $inquiryData, Request $request, $settings): DixlaseInquiry
    {
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
            'form_locale' => $settings->form_locale ?? 'ja',
            'privacy_agreed_at' => $request->has('privacy_agreed') ? now() : null,
            'submitted_at' => now(),
        ]);
    }

    /**
     * プライバシーポリシーURLを解決
     */
    private function resolvePrivacyPolicyUrl($settings): ?string
    {
        // 法務プラグインが登録されていれば優先
        if (app()->bound(PrivacyPolicyProviderInterface::class)) {
            $provider = app(PrivacyPolicyProviderInterface::class);
            if ($provider->isPrivacyPolicyEnabled()) {
                return $provider->getPrivacyPolicyUrl();
            }
        }

        return !empty($settings->privacy_policy_url) ? $settings->privacy_policy_url : null;
    }

    /**
     * フォーム表示（シングルページモード用・旧メソッド）
     */
    public function form()
    {
        $settings = DixlaseInquirySetting::getSettings();

        if (!$settings || empty($settings->admin_email)) {
            return view('dixlase-inquiry::front.inquiries.error', [
                'message' => __('dixlase-inquiry::front.messages.service_unavailable')
            ]);
        }

        $this->applyFormLocale($settings);

        $privacyUrl = $this->resolvePrivacyPolicyUrl($settings);

        return view('dixlase-inquiry::front.inquiries.form', [
            'settings' => $settings,
            'privacyUrl' => $privacyUrl,
            'genderOptions' => $this->getGenderOptions(),
            'prefectures' => $this->getPrefectures(),
        ]);
    }

    public function submit(DixlaseInquirySubmitRequest $request)
    {
        $settings = DixlaseInquirySetting::getSettings();

        if (!$settings || empty($settings['admin_email'])) {
            return response()->json([
                'success' => false,
                'message' => __('dixlase-inquiry::front.messages.service_unavailable')
            ], 400);
        }

        $this->applyFormLocale($settings);

        $validated = $request->validated();

        // 問い合わせデータを準備
        $inquiryData = $this->prepareInquiryData($validated, $settings);

        // DB保存
        $inquiry = $this->saveInquiry($inquiryData, $request, $settings);

        // メール送信
        try {
            $emailBody = $settings['body'];
            foreach ($validated as $key => $value) {
                $emailBody = str_replace('{{' . $key . '}}', $value, $emailBody);
            }

            \Mail::raw($emailBody, function ($message) use ($settings, $validated) {
                $message->to($settings['admin_email'])
                        ->subject(__('dixlase-inquiry::front.mail.new_inquiry_subject'));
            });

            if ($settings['auto_reply_enabled'] && !empty($settings['auto_reply_subject']) && !empty($settings['auto_reply_body'])) {
                $replyBody = $settings['auto_reply_body'];
                foreach ($validated as $key => $value) {
                    $replyBody = str_replace('{{' . $key . '}}', $value, $replyBody);
                }

                \Mail::raw($replyBody, function ($message) use ($settings, $validated) {
                    $message->to($validated['email'])
                            ->subject($settings['auto_reply_subject']);
                });
            }

            return response()->json([
                'success' => true,
                'message' => __('dixlase-inquiry::front.messages.submit_success')
            ]);

        } catch (\Exception $e) {
            \Log::error('Inquiry send failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => __('dixlase-inquiry::front.messages.submit_error')
            ], 500);
        }
    }

    /**
     * 埋め込みフォームからの送信処理
     */
    public function embedSend(DixlaseInquiryEmbedSendRequest $request)
    {
        $settings = DixlaseInquirySetting::getSettings();

        $this->applyFormLocale($settings);

        $validated = $request->validated();
        $redirectUrl = $request->input('redirect_url', url('/'));

        // 問い合わせデータを準備
        $inquiryData = $this->prepareInquiryData($validated, $settings);

        // DB保存
        $inquiry = $this->saveInquiry($inquiryData, $request, $settings);

        // メール送信（失敗してもDB保存は維持）
        try {
            Mail::to($settings->admin_email)->send(new DixlaseInquiryAdminNotification($inquiryData, $settings));

            if ($settings->auto_reply_enabled && !empty($validated['email'])) {
                Mail::to($validated['email'])->send(new DixlaseInquiryAutoReply($inquiryData, $settings));
            }

            return redirect($redirectUrl)->with('inquiry_success', true);

        } catch (\Exception $e) {
            \Log::error('Inquiry embed send failed: ' . $e->getMessage());
            return redirect($redirectUrl)
                ->withErrors(['message' => __('dixlase-inquiry::front.messages.submit_error')])
                ->withInput();
        }
    }
}
