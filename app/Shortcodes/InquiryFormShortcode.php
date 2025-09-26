<?php

namespace Plugins\DixlaseInquiry\App\Shortcodes;

class InquiryFormShortcode
{
    public function render($atts, $content = null)
    {
        $settings = \DB::table('inquiry_settings')->first();
        $atts = shortcode_atts(['title' => 'お問い合わせ'], $atts);
        
        return view('inquiry::form', [
            'title' => $atts['title'],
            'settings' => $settings
        ])->render();
    }
}