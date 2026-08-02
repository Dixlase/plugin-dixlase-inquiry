{{--
This file is part of Dixlase Inquiry.

Copyright (C) 2026 exc-D inc. and Dixlase contributors
https://exc-d.com

Dixlase Inquiry is dual-licensed. You may use this file under either:

  (a) the GNU General Public License version 3 or later, as published
      by the Free Software Foundation; or

  (b) a commercial license agreement obtained from exc-D inc.

Unless you have entered into a commercial license agreement, this
file is governed by the GPL terms below.

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program. If not, see <https://www.gnu.org/licenses/>.
--}}

{{-- お問い合わせセクション（見出し+説明文+フォームまたはリンクボタン） --}}
<section class="inquiry-section pt-28 pb-16 bg-gray-100 dark:bg-gray-800">
    <div class="container mx-auto px-4">
        <div class="max-w-2xl mx-auto">
            @php
                $heading = dls_inquiry_localized_setting('form_heading');
                $description = dls_inquiry_localized_setting('form_description');
            @endphp
            @if(!empty($heading))
                <h2 class="text-3xl font-bold text-center text-gray-900 dark:text-white mb-3">{{ $heading }}</h2>
            @endif
            @if(!empty($description))
                <p class="text-center text-gray-600 dark:text-gray-400 mb-8 max-w-lg mx-auto">{!! nl2br(e($description)) !!}</p>
            @endif
            @if($settings->use_single_page ?? true)
                {!! $formHtml !!}
            @else
                <div class="text-center">
                    <a href="{{ url('/' . (!empty($settings->inquiry_url_slug) ? $settings->inquiry_url_slug : 'inquiry')) }}"
                        class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-md transition-colors duration-200">
                        <i class="fas fa-paper-plane mr-2"></i>
                        {{ __('dixlase-inquiry::front.form.go_to_form') }}
                    </a>
                </div>
            @endif
        </div>
    </div>
</section>
