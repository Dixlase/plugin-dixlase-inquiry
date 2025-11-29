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
use Plugins\DixlaseInquiry\App\Http\Requests\FrontInquirySubmitRequest;
use Plugins\DixlaseInquiry\App\Models\InquirySetting;

class FrontInquiryController extends Controller
{
    public function __construct()
    {
    }

    /**
     * 問い合わせフォーム表示（別ページモード）
     */
    public function index()
    {
        $settings = InquirySetting::getSettings();
        
        // シングルページモードの場合は404
        if ($settings->use_single_page) {
            abort(404);
        }
        
        return view('dixlase-inquiry::front.inquiries.form', [
            'settings' => $settings,
        ]);
    }

    /**
     * 確認画面表示（別ページモード）
     */
    public function confirm(Request $request)
    {
        $settings = InquirySetting::getSettings();
        
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
        $settings = InquirySetting::getSettings();
        
        // シングルページモードの場合は404
        if ($settings->use_single_page) {
            abort(404);
        }
        
        // 送信処理
        // TODO: メール送信実装
        
        return view('dixlase-inquiry::front.inquiries.complete', [
            'settings' => $settings,
        ]);
    }

    /**
     * フォーム表示（シングルページモード用・旧メソッド）
     */
    public function form()
    {
        $settings = InquirySetting::getSettings();
        
        if (!$settings || empty($settings->admin_email)) {
            return view('dixlase-inquiry::front.inquiries.error', [
                'message' => __('dixlase-inquiry::front.messages.service_unavailable')
            ]);
        }
        
        return view('dixlase-inquiry::front.inquiries.form', compact('settings'));
    }
    
    public function submit(FrontInquirySubmitRequest $request)
    {
        $settings = InquirySetting::getSettings();
        
        if (!$settings || empty($settings['admin_email'])) {
            return response()->json([
                'success' => false,
                'message' => __('dixlase-inquiry::front.messages.service_unavailable')
            ], 400);
        }
        
        $validated = $request->validated();
        
        try {
            // Prepare email content
            $emailBody = $settings['body'];
            foreach ($validated as $key => $value) {
                $emailBody = str_replace('{{' . $key . '}}', $value, $emailBody);
            }
            
            // Send email to admin
            \Mail::raw($emailBody, function($message) use ($settings, $validated) {
                $message->to($settings['admin_email'])
                        ->subject(__('dixlase-inquiry::front.mail.new_inquiry_subject'));
            });
            
            // Send auto-reply if enabled
            if ($settings['auto_reply_enabled'] && !empty($settings['auto_reply_subject']) && !empty($settings['auto_reply_body'])) {
                $replyBody = $settings['auto_reply_body'];
                foreach ($validated as $key => $value) {
                    $replyBody = str_replace('{{' . $key . '}}', $value, $replyBody);
                }
                
                \Mail::raw($replyBody, function($message) use ($settings, $validated) {
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
    public function embedSend(Request $request)
    {
        $settings = InquirySetting::getSettings();
        
        // バリデーション
        $rules = [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'message' => 'required|string|max:5000',
        ];
        
        if ($settings->show_subject ?? false) {
            $rules['subject'] = ($settings->subject_required ?? false) ? 'required|string|max:255' : 'nullable|string|max:255';
        }
        if ($settings->show_phone ?? true) {
            $rules['phone'] = ($settings->phone_required ?? false) ? 'required|string|max:50' : 'nullable|string|max:50';
        }
        
        $validated = $request->validate($rules);
        $redirectUrl = $request->input('redirect_url', url('/'));
        
        try {
            // 名前を結合
            $fullName = ($settings->name_order_western ?? false)
                ? $validated['first_name'] . ' ' . $validated['last_name']
                : $validated['last_name'] . ' ' . $validated['first_name'];
            
            // メール本文を作成
            $emailBody = "【お問い合わせ】\n\n";
            if (!empty($validated['subject'])) {
                $emailBody .= "題名: {$validated['subject']}\n";
            }
            $emailBody .= "お名前: {$fullName}\n";
            $emailBody .= "メールアドレス: {$validated['email']}\n";
            if (!empty($validated['phone'])) {
                $emailBody .= "電話番号: {$validated['phone']}\n";
            }
            $emailBody .= "\n【お問い合わせ内容】\n{$validated['message']}\n";
            
            // 管理者にメール送信
            \Mail::raw($emailBody, function($message) use ($settings, $validated) {
                $message->to($settings->admin_email)
                        ->subject(__('dixlase-inquiry::front.mail.new_inquiry_subject'));
                if (!empty($validated['email'])) {
                    $message->replyTo($validated['email']);
                }
            });
            
            // 自動返信が有効な場合
            if ($settings->auto_reply_enabled && !empty($settings->auto_reply_subject) && !empty($settings->auto_reply_body)) {
                $replyBody = $settings->auto_reply_body;
                $replyBody = str_replace('{{name}}', $fullName, $replyBody);
                $replyBody = str_replace('{{email}}', $validated['email'], $replyBody);
                $replyBody = str_replace('{{message}}', $validated['message'], $replyBody);
                
                \Mail::raw($replyBody, function($message) use ($settings, $validated) {
                    $message->to($validated['email'])
                            ->subject($settings->auto_reply_subject);
                });
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