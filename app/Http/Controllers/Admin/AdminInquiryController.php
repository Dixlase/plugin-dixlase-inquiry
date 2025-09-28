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
use Plugins\DixlaseInquiry\App\Models\InquirySetting;


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
        return view('dixlase-inquiry::admin.inquiries.index');
    }

    public function show($id)
    {
        // 問い合わせ詳細を表示
        return view('dixlase-inquiry::admin.inquiries.detail', compact('id'));
    }

    public function destroy($id)
    {
        // 問い合わせを削除
        return redirect()->route('admin.dixlase-inquiry::admin.inquiries.index')->with('success', '問い合わせを削除しました。');
    }

    public function settings()
    {
        $settings = InquirySetting::getSettings();
        $this->viewParams['settings'] = $settings;
        
        return view('dixlase-inquiry::admin.settings', $this->viewParams);
    }
    
    public function updateSettings(Request $request)
    {
        $validated = $request->validate([
            'admin_email' => 'required|email',
            'subject' => 'nullable|string|max:255',
            'body' => 'nullable|string',
            'completion_title' => 'nullable|string|max:255',
            'completion_message' => 'nullable|string',
            'use_recaptcha' => 'boolean',
            'show_phone' => 'boolean',
            'phone_required' => 'boolean',
            'show_address' => 'boolean',
            'address_required' => 'boolean',
            'show_subject' => 'boolean',
            'subject_required' => 'boolean',
            'show_postal_code' => 'boolean',
            'postal_code_required' => 'boolean',
            'auto_reply_enabled' => 'boolean',
            'auto_reply_from_email' => 'nullable|email',
            'auto_reply_subject' => 'nullable|string|max:255',
            'auto_reply_body' => 'nullable|string',
            'use_single_page' => 'boolean',
            'show_confirmation_page' => 'boolean',
            'name_order_western' => 'boolean',
        ]);
        
        // Convert checkbox values (unchecked checkboxes don't send data)
        $booleanFields = [
            'use_recaptcha', 'show_phone', 'phone_required', 'show_address', 'address_required',
            'show_subject', 'subject_required', 'show_postal_code', 'postal_code_required',
            'auto_reply_enabled', 'use_single_page', 'show_confirmation_page', 'name_order_western'
        ];
        
        foreach ($booleanFields as $field) {
            $validated[$field] = $validated[$field] ?? false;
        }
        
        InquirySetting::updateSettings($validated);
        
        return redirect()->back()->with('success', '設定を保存しました。');
    }

}