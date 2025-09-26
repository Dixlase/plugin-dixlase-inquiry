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


class AdminInquiryController extends Controller
{

    use AdminInterfaceTrait;
    use AdminLoggedInTrait;
    public function __construct()
    {
        $this->initialize();
        $this->initializeAfterLogin();
    }

    public function settings()
    {
        $settings = \DB::table('inquiry_settings')->first();
        
        if (!$settings) {
            // Initialize default settings if not exists
            $settings = (object)[
                'admin_email' => '',
                'subject' => 'お問い合わせありがとうございます',
                'body' => '以下の内容でお問い合わせを受け付けました。\n\nお名前: {{name}}\nメールアドレス: {{email}}\n電話番号: {{phone}}\n住所: {{address}}\n\nお問い合わせ内容:\n{{message}}',
                'use_recaptcha' => false,
                'show_phone' => true,
                'phone_required' => false,
                'show_address' => true,
                'address_required' => false,
            ];
        }
        
        return view('inquiry::admin.inquiries.settings', compact('settings'));
    }
    
    public function updateSettings(Request $request)
    {
        $validated = $request->validate([
            'admin_email' => 'required|email',
            'subject' => 'required|string|max:255',
            'body' => 'required|string',
            'use_recaptcha' => 'boolean',
            'show_phone' => 'boolean',
            'phone_required' => 'boolean',
            'show_address' => 'boolean',
            'address_required' => 'boolean',
        ]);
        
        // Update or create settings
        if (\DB::table('inquiry_settings')->exists()) {
            \DB::table('inquiry_settings')->update($validated);
        } else {
            \DB::table('inquiry_settings')->insert($validated);
        }
        
        return redirect()->back()->with('success', '設定を保存しました。');
    }

}