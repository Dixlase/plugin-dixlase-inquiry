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

        $privacyUrl = $this->resolvePrivacyPolicyUrl($settings);

        return view('dixlase-inquiry::front.inquiries.form', [
            'settings' => $settings,
            'privacyUrl' => $privacyUrl,
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

        // バリデーション処理
        // TODO: バリデーション実装

        return view('dixlase-inquiry::front.inquiries.confirm', [
            'settings' => $settings,
            'data' => $request->all(),
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
     */
    private function prepareInquiryData(array $validated, $settings): array
    {
        // 名前を結合
        $fullName = ($settings->name_order_western ?? false)
            ? trim(($validated['first_name'] ?? '') . ' ' . ($validated['last_name'] ?? ''))
            : trim(($validated['last_name'] ?? '') . ' ' . ($validated['first_name'] ?? ''));

        return [
            'name' => $fullName,
            'email' => $validated['email'] ?? '',
            'subject' => $validated['subject'] ?? null,
            'phone' => $validated['phone'] ?? null,
            'postal_code' => $validated['postal_code'] ?? null,
            'address' => $validated['address'] ?? null,
            'gender' => isset($validated['gender']) ? $this->getGenderLabel($validated['gender']) : null,
            'gender_value' => $validated['gender'] ?? null,
            'message' => $validated['message'] ?? '',
        ];
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
     * 問い合わせをDBに保存
     */
    private function saveInquiry(array $inquiryData, Request $request, $settings): DixlaseInquiry
    {
        return DixlaseInquiry::create([
            'status' => InquiryStatus::New,
            'name' => $inquiryData['name'],
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

        $privacyUrl = $this->resolvePrivacyPolicyUrl($settings);

        return view('dixlase-inquiry::front.inquiries.form', compact('settings', 'privacyUrl'));
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
