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

namespace Plugins\DixlaseInquiry\App\Http\Controllers\Front;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;

class FrontInquiryController extends Controller
{

    public function __construct()
    {

    }

    public function form()
    {
        $settings = \DB::table('inquiry_settings')->first();
        
        if (!$settings || empty($settings->admin_email)) {
            return view('inquiry::front.inquiries.error', [
                'message' => 'お問い合わせ機能は現在ご利用いただけません。'
            ]);
        }
        
        return view('inquiry::front.inquiries.form', compact('settings'));
    }
    
    public function submit(Request $request)
    {
        $settings = \DB::table('inquiry_settings')->first();
        
        if (!$settings || empty($settings->admin_email)) {
            return response()->json([
                'success' => false,
                'message' => 'お問い合わせ機能は現在ご利用いただけません。'
            ], 400);
        }
        
        $rules = [
            'name' => $settings->name_required ? 'required|string|max:255' : 'nullable|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => $settings->phone_required ? 'required|string|max:20' : 'nullable|string|max:20',
            'address' => $settings->address_required ? 'required|string|max:255' : 'nullable|string|max:255',
            'message' => 'required|string',
        ];
        
        if ($settings->use_recaptcha) {
            $rules['g-recaptcha-response'] = 'required|captcha';
        }
        
        $validated = $request->validate($rules);
        
        try {
            // Prepare email content
            $emailBody = $settings->body;
            foreach ($validated as $key => $value) {
                $emailBody = str_replace('{{' . $key . '}}', $value, $emailBody);
            }
            
            // Send email to admin
            \Mail::raw($emailBody, function($message) use ($settings, $validated) {
                $message->to($settings->admin_email)
                        ->subject('新しいお問い合わせがありました');
            });
            
            // Send auto-reply if enabled
            if (!empty($settings->subject) && !empty($settings->body)) {
                $replyBody = $settings->body;
                foreach ($validated as $key => $value) {
                    $replyBody = str_replace('{{' . $key . '}}', $value, $replyBody);
                }
                
                \Mail::raw($replyBody, function($message) use ($settings, $validated) {
                    $message->to($validated['email'])
                            ->subject($settings->subject);
                });
            }
            
            return response()->json([
                'success' => true,
                'message' => 'お問い合わせが送信されました。ありがとうございます。'
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Inquiry send failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'お問い合わせの送信中にエラーが発生しました。しばらくしてからもう一度お試しください。'
            ], 500);
        }
    }

}