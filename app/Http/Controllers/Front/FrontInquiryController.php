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

    public function form()
    {
        $settings = InquirySetting::getSettings();
        
        if (!$settings || empty($settings['admin_email'])) {
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

}