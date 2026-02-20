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

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Mail;
use Plugins\DixlaseInquiry\App\Http\Requests\DixlaseInquirySubmitRequest;
use Plugins\DixlaseInquiry\App\Http\Requests\Front\DixlaseInquiryEmbedSendRequest;
use Plugins\DixlaseInquiry\App\Mail\DixlaseInquiryAdminNotification;
use Plugins\DixlaseInquiry\App\Mail\DixlaseInquiryAutoReply;
use Plugins\DixlaseInquiry\App\Models\DixlaseInquirySetting;
use Plugins\DixlaseInquiry\App\Traits\InquiryFormDataTrait;

class DixlaseInquiryFrontController extends Controller
{
    use InquiryFormDataTrait;

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
     * フォーム表示（シングルページモード用・旧メソッド）
     */
    public function form()
    {
        $settings = DixlaseInquirySetting::getSettings();

        if (!$settings || empty($settings->admin_email)) {
            return view('dixlase-inquiry::front.inquiries.error', [
                'message' => __('dixlase-inquiry::front.messages.service_unavailable'),
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
                'message' => __('dixlase-inquiry::front.messages.service_unavailable'),
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
                'message' => __('dixlase-inquiry::front.messages.submit_success'),
            ]);

        } catch (\Exception $e) {
            \Log::error('Inquiry send failed: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => __('dixlase-inquiry::front.messages.submit_error'),
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
