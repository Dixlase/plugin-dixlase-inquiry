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

namespace Plugins\DixlaseInquiry\App\Http\Controllers\Admin;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use App\Traits\AdminInterfaceTrait;
use App\Traits\AdminLoggedInTrait;
use App\Models\BaseSetting;
use App\Models\SecuritySetting;
use Plugins\DixlaseInquiry\App\Models\InquirySetting;
use Plugins\DixlaseInquiry\App\Http\Requests\AdminInquirySettingsRequest;


class AdminInquiryController extends Controller
{

    use AdminInterfaceTrait;
    use AdminLoggedInTrait;
    
    public function __construct()
    {
        $this->initialize();
        $this->initializeAfterLogin();
    }

    public function index()
    {
        // 問い合わせ一覧を表示
        return view('dixlase-inquiry::admin.inquiry.index', $this->viewParams);
    }

    public function settings()
    {
        $settings = InquirySetting::getSettings();
        
        // メールテスト状態を取得（DB優先、セッションは一時的な状態のみ）
        $sessionTestResults = session('mail_test_results', []);
        
        $mailConnectionTested = (bool) ($sessionTestResults['mail_connection_tested'] ?? BaseSetting::getValue('mail_connection_tested', false));
        $mailSendTested = (bool) ($sessionTestResults['mail_send_tested'] ?? BaseSetting::getValue('mail_send_tested', false));
        $mailReceiveTested = (bool) ($sessionTestResults['mail_receive_tested'] ?? BaseSetting::getValue('mail_receive_tested', false));
        
        // CAPTCHA設定状況を確認
        $captchaEnabled = filter_var(SecuritySetting::get('captcha_enabled', false), FILTER_VALIDATE_BOOLEAN);
        $captchaDriver = SecuritySetting::get('captcha_driver', '');
        $captchaTestResult = session('captcha_test_result', SecuritySetting::get('captcha_test_result', false));
        $captchaAuthenticated = filter_var($captchaTestResult, FILTER_VALIDATE_BOOLEAN);
        
        $this->viewParams['settings'] = $settings;
        $this->viewParams['mailConnectionTested'] = $mailConnectionTested;
        $this->viewParams['mailSendTested'] = $mailSendTested;
        $this->viewParams['mailReceiveTested'] = $mailReceiveTested;
        $this->viewParams['captchaEnabled'] = $captchaEnabled;
        $this->viewParams['captchaDriver'] = $captchaDriver;
        $this->viewParams['captchaAuthenticated'] = $captchaAuthenticated;
        
        return view('dixlase-inquiry::admin.inquiry.settings', $this->viewParams);
    }
    
    public function updateSettings(AdminInquirySettingsRequest $request)
    {
        $validated = $request->validated();
        
        InquirySetting::updateSettings($validated);
        
        return redirect()->back()->with('success', __('dixlase-inquiry::admin.messages.settings_updated'));
    }

}