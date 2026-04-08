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

namespace Plugins\DixlaseInquiry\App\Http\Controllers\Admin;

use App\Helpers\CaptchaHelper;
use App\Models\BaseSetting;
use App\Traits\AdminInterfaceTrait;
use App\Traits\AdminLoggedInTrait;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;
use Plugins\DixlaseInquiry\App\Enums\InquiryStatus;
use Plugins\DixlaseInquiry\App\Http\Requests\Admin\DixlaseInquiryAdminNotificationRequest;
use Plugins\DixlaseInquiry\App\Http\Requests\Admin\DixlaseInquiryAutoReplyRequest;
use Plugins\DixlaseInquiry\App\Http\Requests\Admin\DixlaseInquiryCompletionRequest;
use Plugins\DixlaseInquiry\App\Http\Requests\Admin\DixlaseInquiryFormBasicRequest;
use Plugins\DixlaseInquiry\App\Mail\DixlaseInquiryAdminNotification;
use Plugins\DixlaseInquiry\App\Mail\DixlaseInquiryAutoReply;
use Plugins\DixlaseInquiry\App\Models\DixlaseInquiry;
use Plugins\DixlaseInquiry\App\Models\DixlaseInquirySetting;
use Plugins\DixlaseInquiry\App\Traits\InquiryFormDataTrait;

class DixlaseInquiryAdminController extends Controller
{
    use AdminInterfaceTrait;
    use AdminLoggedInTrait;
    use InquiryFormDataTrait;

    public function __construct()
    {
        $this->initialize();
        $this->initializeAfterLogin();
    }

    /**
     * 問い合わせ一覧
     */
    public function index(Request $request): View
    {
        $search = $request->input('search');
        $statusFilter = $request->input('status', '');

        $perPage = (int) $request->input('per_page', 25);
        $allowedPerPage = [10, 25, 50, 100];
        if (! in_array($perPage, $allowedPerPage)) {
            $perPage = 25;
        }

        $inquiries = DixlaseInquiry::query()
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', '%'.$search.'%')
                        ->orWhere('email', 'like', '%'.$search.'%')
                        ->orWhere('subject', 'like', '%'.$search.'%')
                        ->orWhere('message', 'like', '%'.$search.'%');
                });
            })
            ->when($statusFilter, function ($query, $statusFilter) {
                $query->where('status', $statusFilter);
            })
            ->orderBy('submitted_at', 'desc')
            ->paginate($perPage)
            ->withQueryString();

        $unreadCount = DixlaseInquiry::unread()->count();
        $statuses = InquiryStatus::cases();
        $statusLabels = [];
        foreach ($statuses as $status) {
            $statusLabels[$status->value] = $status->label();
        }

        return view('dixlase-inquiry::admin.inquiry.index', array_merge($this->viewParams, [
            'inquiries' => $inquiries,
            'search' => $search,
            'statusFilter' => $statusFilter,
            'unreadCount' => $unreadCount,
            'statuses' => $statuses,
            'statusLabels' => $statusLabels,
        ]));
    }

    /**
     * 問い合わせ詳細
     */
    public function show(int $id): View
    {
        $inquiry = DixlaseInquiry::findOrFail($id);

        // 自動既読マーク
        $inquiry->markAsRead();

        // ステータスが「新規」の場合は「対応中」に変更
        if ($inquiry->status === InquiryStatus::New) {
            $inquiry->update(['status' => InquiryStatus::InProgress]);
        }

        $statuses = InquiryStatus::cases();
        $statusLabels = [];
        foreach ($statuses as $status) {
            $statusLabels[$status->value] = $status->label();
        }

        return view('dixlase-inquiry::admin.inquiry.show', array_merge($this->viewParams, [
            'inquiry' => $inquiry,
            'statuses' => $statuses,
            'statusLabels' => $statusLabels,
        ]));
    }

    /**
     * 問い合わせ削除
     */
    public function destroy(int $id): RedirectResponse
    {
        $inquiry = DixlaseInquiry::findOrFail($id);
        $inquiry->delete();

        return redirect()->route('dixlase-inquiry::admin.inquiry.index')
            ->with('success', __('dixlase-inquiry::admin/inquiry/index.deleted'));
    }

    /**
     * ステータス変更
     */
    public function updateStatus(Request $request, int $id): RedirectResponse
    {
        $inquiry = DixlaseInquiry::findOrFail($id);

        $request->validate([
            'status' => ['required', 'string', 'in:'.implode(',', array_column(InquiryStatus::cases(), 'value'))],
        ]);

        $inquiry->update(['status' => $request->input('status')]);

        return redirect()->route('dixlase-inquiry::admin.inquiry.show', $id)
            ->with('success', __('dixlase-inquiry::admin/inquiry/show.status_updated'));
    }

    /**
     * 問い合わせ受付状態のトグル（AJAX）
     */
    public function toggleAccepting(Request $request): \Illuminate\Http\JsonResponse
    {
        $current = filter_var(DixlaseInquirySetting::get('accepting_inquiries', true), FILTER_VALIDATE_BOOLEAN);
        $newValue = ! $current;
        DixlaseInquirySetting::set('accepting_inquiries', $newValue ? '1' : '0');

        return response()->json([
            'success' => true,
            'accepting' => $newValue,
        ]);
    }

    /**
     * 設定 - 概要ページ
     */
    public function settingsIndex(): View
    {
        $settings = DixlaseInquirySetting::getSettings();

        $captchaEnabled = CaptchaHelper::shouldShowCaptcha(self::CAPTCHA_FORM_KEY);

        return view('dixlase-inquiry::admin.inquiry.settings.index', array_merge($this->viewParams, [
            'settings' => $settings,
            'captchaEnabled' => $captchaEnabled,
        ]));
    }

    /**
     * 設定 - フォーム設定（基本+表示+言語統合）
     */
    public function settingsFormBasic(): View
    {
        $settings = DixlaseInquirySetting::getSettings();

        // 全ロケールのフロント翻訳をJSに渡す
        $locales = config('dixlase-inquiry.locales', ['ja', 'en']);
        $formTranslations = [];
        foreach ($locales as $locale) {
            $formTranslations[$locale] = array_merge(
                trans('dixlase-inquiry::front.form', [], $locale),
                ['confirm_button' => trans('dixlase-inquiry::front.buttons.confirm', [], $locale)],
            );
        }

        // Determine admin mode (Simple=0, Advanced=1)
        $isSimpleMode = (int) BaseSetting::getValue('admin_mode', 0) === 0;

        return view('dixlase-inquiry::admin.inquiry.settings.form-basic', array_merge($this->viewParams, [
            'settings' => $settings,
            'locales' => $locales,
            'formTranslations' => $formTranslations,
            'isSimpleMode' => $isSimpleMode,
        ]));
    }

    /**
     * 設定 - フォーム設定の更新
     */
    public function updateFormBasic(DixlaseInquiryFormBasicRequest $request): RedirectResponse
    {
        DixlaseInquirySetting::updateSettings($request->validated());

        return redirect()->route('dixlase-inquiry::admin.inquiry.settings.form-basic')
            ->with('success', __('dixlase-inquiry::admin/inquiry/settings/form-basic.settings_updated'));
    }

    /**
     * 設定 - 完了ページ設定
     */
    public function settingsCompletion(): View
    {
        $settings = DixlaseInquirySetting::getSettings();

        return view('dixlase-inquiry::admin.inquiry.settings.completion', array_merge($this->viewParams, [
            'settings' => $settings,
        ]));
    }

    /**
     * 設定 - 完了ページ設定の更新
     */
    public function updateCompletion(DixlaseInquiryCompletionRequest $request): RedirectResponse
    {
        DixlaseInquirySetting::updateSettings($request->validated());

        return redirect()->route('dixlase-inquiry::admin.inquiry.settings.completion')
            ->with('success', __('dixlase-inquiry::admin/inquiry/settings/completion.settings_updated'));
    }

    /**
     * 設定 - 管理者通知設定
     */
    public function settingsAdminNotification(): View
    {
        $settings = DixlaseInquirySetting::getSettings();

        // メールテスト状態を取得
        $sessionTestResults = session('mail_test_results', []);
        $mailConnectionTested = (bool) ($sessionTestResults['mail_connection_tested'] ?? BaseSetting::getValue('mail_connection_tested', false));
        $mailSendTested = (bool) ($sessionTestResults['mail_send_tested'] ?? BaseSetting::getValue('mail_send_tested', false));
        $mailReceiveTested = (bool) ($sessionTestResults['mail_receive_tested'] ?? BaseSetting::getValue('mail_receive_tested', false));

        return view('dixlase-inquiry::admin.inquiry.settings.admin-notification', array_merge($this->viewParams, [
            'settings' => $settings,
            'mailConnectionTested' => $mailConnectionTested,
            'mailSendTested' => $mailSendTested,
            'mailReceiveTested' => $mailReceiveTested,
        ]));
    }

    /**
     * 設定 - 管理者通知設定の更新
     */
    public function updateAdminNotification(DixlaseInquiryAdminNotificationRequest $request): RedirectResponse
    {
        DixlaseInquirySetting::updateSettings($request->validated());

        return redirect()->route('dixlase-inquiry::admin.inquiry.settings.admin-notification')
            ->with('success', __('dixlase-inquiry::admin/inquiry/settings/admin-notification.settings_updated'));
    }

    /**
     * 設定 - 自動返信設定
     */
    public function settingsAutoReply(): View
    {
        $settings = DixlaseInquirySetting::getSettings();

        return view('dixlase-inquiry::admin.inquiry.settings.auto-reply', array_merge($this->viewParams, [
            'settings' => $settings,
        ]));
    }

    /**
     * 設定 - 自動返信設定の更新
     */
    public function updateAutoReply(DixlaseInquiryAutoReplyRequest $request): RedirectResponse
    {
        DixlaseInquirySetting::updateSettings($request->validated());

        return redirect()->route('dixlase-inquiry::admin.inquiry.settings.auto-reply')
            ->with('success', __('dixlase-inquiry::admin/inquiry/settings/auto-reply.settings_updated'));
    }

    /**
     * プレビュー設定をセッションに保存してリダイレクト
     */
    public function storePreviewSettings(Request $request): RedirectResponse
    {
        $settingsData = $request->except(['_token']);
        // 真偽値フィールドを正規化
        $boolFields = [
            'use_single_page', 'name_order_western', 'show_subject', 'subject_required',
            'show_address', 'postal_code_required', 'address_required',
            'show_phone', 'phone_required', 'show_gender', 'gender_required',
            'show_gender_other', 'show_gender_prefer_not_to_say',
            'email_confirm_paste_disabled',
            'show_kana', 'require_kana', 'privacy_consent_enabled', 'throttle_enabled',
            'show_confirmation_page',
        ];
        foreach ($boolFields as $field) {
            if (isset($settingsData[$field])) {
                $settingsData[$field] = filter_var($settingsData[$field], FILTER_VALIDATE_BOOLEAN);
            }
        }

        session(['inquiry_preview_settings' => $settingsData]);

        return redirect()->route('dixlase-inquiry::admin.inquiry.settings.form-basic.preview');
    }

    /**
     * プレビュー用設定を取得
     * セッションのフォーム設定をDB保存済み設定にマージして返す
     */
    private function getPreviewSettings(): object
    {
        $dbSettings = DixlaseInquirySetting::getSettings();
        $previewData = session('inquiry_preview_settings');

        if (! $previewData) {
            return $dbSettings;
        }

        // DB設定をベースにプレビュー設定で上書き
        $merged = (array) $dbSettings;
        foreach ($previewData as $key => $value) {
            $merged[$key] = $value;
        }

        return (object) $merged;
    }

    /**
     * プレビューページ表示
     */
    public function showPreview(): View
    {
        $settings = $this->getPreviewSettings();

        $this->applyFormLocale($settings);

        $privacyUrl = $this->resolvePrivacyPolicyUrl($settings);

        $formAction = route('dixlase-inquiry::admin.inquiry.settings.form-basic.preview.confirm');
        $sendAction = route('dixlase-inquiry::admin.inquiry.settings.form-basic.preview.send');

        $captchaEnabled = CaptchaHelper::shouldShowCaptcha(self::CAPTCHA_FORM_KEY);
        $captchaWidget = $captchaEnabled ? CaptchaHelper::renderWidget(self::CAPTCHA_FORM_KEY) : null;

        return view('dixlase-inquiry::front.inquiries.preview', [
            'settings' => $settings,
            'privacyUrl' => $privacyUrl,
            'genderOptions' => $this->getGenderOptions($settings),
            'prefectures' => $this->getPrefectures(),
            'formAction' => $settings->show_confirmation_page ?? true
                ? $formAction
                : $sendAction,
            'isPreview' => true,
            'captchaEnabled' => $captchaEnabled,
            'captchaWidget' => $captchaWidget,
        ]);
    }

    /**
     * プレビュー確認画面
     */
    public function previewConfirm(Request $request): View
    {
        $settings = $this->getPreviewSettings();

        $this->applyFormLocale($settings);

        return view('dixlase-inquiry::front.inquiries.confirm', [
            'settings' => $settings,
            'data' => $request->all(),
            'genderOptions' => $this->getGenderOptions($settings),
            'isPreview' => true,
        ]);
    }

    /**
     * プレビュー送信処理
     */
    public function previewSend(Request $request): View
    {
        $settings = $this->getPreviewSettings();

        $this->applyFormLocale($settings);

        $validated = $request->except(['_token', '_preview_save_to_db', '_preview_send_email']);
        $inquiryData = $this->prepareInquiryData($validated, $settings);

        $inquiry = null;
        $previewSaveToDb = $request->input('_preview_save_to_db') === '1';
        $previewSendEmail = $request->input('_preview_send_email') === '1';

        // トグルに応じてDB保存
        if ($previewSaveToDb) {
            $inquiry = $this->saveInquiry($inquiryData, $request, $settings);
        }

        // トグルに応じてメール送信
        if ($previewSendEmail && ! empty($settings->admin_email)) {
            try {
                Mail::to($settings->admin_email)->send(new DixlaseInquiryAdminNotification($inquiryData, $settings));

                if (($settings->auto_reply_enabled ?? false) && ! empty($validated['email'])) {
                    Mail::to($validated['email'])->send(new DixlaseInquiryAutoReply($inquiryData, $settings));
                }
            } catch (\Exception $e) {
                \Log::error('Inquiry preview mail send failed: '.$e->getMessage());
            }
        }

        return view('dixlase-inquiry::front.inquiries.complete', [
            'settings' => $settings,
            'inquiry' => $inquiry,
            'isPreview' => true,
            'previewSavedToDb' => $previewSaveToDb,
            'previewSentEmail' => $previewSendEmail,
        ]);
    }
}
