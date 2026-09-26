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

namespace Plugins\DixlaseInquiry\App\Http\Controllers\Front;

use App\Captcha\CaptchaDriver;
use App\Helpers\CaptchaHelper;
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

    public function __construct() {}

    /**
     * 問い合わせフォーム表示（別ページモード）
     */
    public function index()
    {
        $settings = DixlaseInquirySetting::getSettings();

        // シングルページモードまたは受付停止の場合は404
        if ($settings->use_single_page || ! ($settings->accepting_inquiries ?? true)) {
            abort(404);
        }

        $this->applyFormLocale($settings);

        $privacyUrl = $this->resolvePrivacyPolicyUrl($settings);

        $captchaEnabled = CaptchaHelper::shouldShowCaptcha(self::CAPTCHA_FORM_KEY);
        $captchaWidget = $captchaEnabled ? CaptchaHelper::renderWidget(self::CAPTCHA_FORM_KEY) : null;

        return view('dixlase-inquiry::front.inquiries.form', [
            'settings' => $settings,
            'privacyUrl' => $privacyUrl,
            'genderOptions' => $this->getGenderOptions($settings),
            'prefectures' => $this->getPrefectures(),
            'captchaEnabled' => $captchaEnabled,
            'captchaWidget' => $captchaWidget,
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
        if (! $settings->show_confirmation_page) {
            abort(404);
        }

        // Closed to inquiries: the form is gone (index() 404s), so every step after it is too.
        if (! $this->isAccepting($settings)) {
            abort(404);
        }

        $this->applyFormLocale($settings);

        // The CAPTCHA widget lives on the form page, so its response token
        // arrives here -- but send() is where it is verified, and these tokens
        // are single-use. Forwarding the field names lets the confirm form
        // re-emit the token untouched instead of consuming it on this hop.
        // Without this the confirmation step would silently strip the token and
        // every legitimate submission would fail CAPTCHA validation at send().
        $captchaFields = CaptchaHelper::shouldShowCaptcha(self::CAPTCHA_FORM_KEY)
            ? array_keys(app(CaptchaDriver::class)->rules())
            : [];

        return view('dixlase-inquiry::front.inquiries.confirm', [
            'settings' => $settings,
            'data' => $request->all(),
            'genderOptions' => $this->getGenderOptions($settings),
            'captchaFields' => $captchaFields,
        ]);
    }

    /**
     * 送信処理（別ページモード）
     */
    /**
     * Final submit for the separate-page flow.
     *
     * Type-hinted with DixlaseInquirySubmitRequest so validation and the
     * CAPTCHA rules actually run. This used to take a bare Request and call
     * $request->all(), which meant the only unauthenticated write endpoint in
     * the plugin accepted anything: no size limits, no type checks, and no
     * CAPTCHA at all. Combined with the auto-reply below -- which mails an
     * address taken straight from the submission -- that turned the site into
     * a relay an attacker could point at arbitrary recipients.
     */
    public function send(DixlaseInquirySubmitRequest $request)
    {
        $settings = DixlaseInquirySetting::getSettings();

        // シングルページモードの場合は404
        if ($settings->use_single_page) {
            abort(404);
        }

        // Closed to inquiries: refuse the submission itself, not only the form.
        // A client that skips the form could otherwise still store inquiries
        // and trigger the notification and auto-reply mails.
        if (! $this->isAccepting($settings)) {
            abort(404);
        }

        $this->applyFormLocale($settings);

        $validated = $request->validated();

        // 問い合わせデータを準備
        $inquiryData = $this->prepareInquiryData($validated, $settings);

        // DB保存
        $inquiry = $this->saveInquiry($inquiryData, $request, $settings);

        // メール送信（失敗してもDB保存は維持）
        try {
            Mail::to($settings->admin_email)->send(new DixlaseInquiryAdminNotification($inquiryData, $settings));

            if ($settings->auto_reply_enabled && ! empty($validated['email'])) {
                Mail::to($validated['email'])->send(new DixlaseInquiryAutoReply($inquiryData, $settings));
            }
        } catch (\Exception $e) {
            \Log::error('Inquiry mail send failed: '.$e->getMessage());
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

        if (! $settings || empty($settings->admin_email) || ! $this->isAccepting($settings)) {
            return view('dixlase-inquiry::front.inquiries.error', [
                'message' => __('dixlase-inquiry::front.messages.service_unavailable'),
            ]);
        }

        $this->applyFormLocale($settings);

        $privacyUrl = $this->resolvePrivacyPolicyUrl($settings);

        $captchaEnabled = CaptchaHelper::shouldShowCaptcha(self::CAPTCHA_FORM_KEY);
        $captchaWidget = $captchaEnabled ? CaptchaHelper::renderWidget(self::CAPTCHA_FORM_KEY) : null;

        return view('dixlase-inquiry::front.inquiries.form', [
            'settings' => $settings,
            'privacyUrl' => $privacyUrl,
            'genderOptions' => $this->getGenderOptions($settings),
            'prefectures' => $this->getPrefectures(),
            'captchaEnabled' => $captchaEnabled,
            'captchaWidget' => $captchaWidget,
        ]);
    }

    public function submit(DixlaseInquirySubmitRequest $request)
    {
        $settings = DixlaseInquirySetting::getSettings();

        if (! $settings || empty($settings['admin_email'])) {
            return response()->json([
                'success' => false,
                'message' => __('dixlase-inquiry::front.messages.service_unavailable'),
            ], 400);
        }

        // Closed to inquiries: refuse the submission (see send()).
        if (! $this->isAccepting($settings)) {
            return response()->json([
                'success' => false,
                'message' => __('dixlase-inquiry::front.messages.service_unavailable'),
            ], 403);
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
                $emailBody = str_replace('{{'.$key.'}}', $value, $emailBody);
            }

            \Mail::raw($emailBody, function ($message) use ($settings) {
                $message->to($settings['admin_email'])
                    ->subject(__('dixlase-inquiry::front.mail.new_inquiry_subject'));
            });

            // Resolve auto-reply text via the helper so DixlaseMultilingual's
            // current-locale translation wins over the primary-locale value
            // stored in plg_dixlase_inquiry_settings.
            $autoReplySubject = dls_inquiry_localized_setting('auto_reply_subject')
                ?? ($settings['auto_reply_subject'] ?? '');
            $autoReplyBody = dls_inquiry_localized_setting('auto_reply_body')
                ?? ($settings['auto_reply_body'] ?? '');

            if ($settings['auto_reply_enabled'] && ! empty($autoReplySubject) && ! empty($autoReplyBody)) {
                $replyBody = $autoReplyBody;
                foreach ($validated as $key => $value) {
                    $replyBody = str_replace('{{'.$key.'}}', $value, $replyBody);
                }

                \Mail::raw($replyBody, function ($message) use ($autoReplySubject, $validated) {
                    $message->to($validated['email'])
                        ->subject($autoReplySubject);
                });
            }

            return response()->json([
                'success' => true,
                'message' => __('dixlase-inquiry::front.messages.submit_success'),
            ]);
        } catch (\Exception $e) {
            \Log::error('Inquiry send failed: '.$e->getMessage());

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

        // 受付停止中は送信を拒否
        if (! $this->isAccepting($settings)) {
            abort(403);
        }

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

            if ($settings->auto_reply_enabled && ! empty($validated['email'])) {
                Mail::to($validated['email'])->send(new DixlaseInquiryAutoReply($inquiryData, $settings));
            }

            // AJAXリクエストの場合はJSONレスポンスを返す
            if ($request->ajax()) {
                return response()->json(['success' => true]);
            }

            return redirect($redirectUrl)->with('inquiry_success', true);
        } catch (\Exception $e) {
            \Log::error('Inquiry embed send failed: '.$e->getMessage());

            if ($request->ajax()) {
                return response()->json(['error' => __('dixlase-inquiry::front.messages.submit_error')], 500);
            }

            return redirect($redirectUrl)
                ->withErrors(['message' => __('dixlase-inquiry::front.messages.submit_error')])
                ->withInput();
        }
    }

    /**
     * Whether the form is currently open to inquiries.
     *
     * The setting is stored as a string ('0' / '1'), so it is read as a
     * boolean rather than by truthiness ('0' would otherwise count as open
     * in some paths and closed in others).
     */
    private function isAccepting($settings): bool
    {
        $value = is_array($settings) ? ($settings['accepting_inquiries'] ?? true) : ($settings->accepting_inquiries ?? true);

        return filter_var($value, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE) ?? true;
    }
}
