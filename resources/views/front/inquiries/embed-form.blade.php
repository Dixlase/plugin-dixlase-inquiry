{{--
This file is part of DixlaseInquiry.

Copyright (C) 2025 exc-D inc.
https://exc-d.com

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

{{-- 埋め込み用問い合わせフォーム（ショートコード用） --}}
<div class="dixlase-inquiry-embed" id="inquiry-form">
    @if(session('inquiry_success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ __('dixlase-inquiry::front.form.success_message') }}
        </div>
    @else
        @if($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('inquiry.embed.send') }}" method="POST" class="space-y-4">
            @csrf
            <input type="hidden" name="redirect_url" value="{{ url()->current() }}#inquiry-form">

            {{-- 題名 --}}
            @if($settings->show_subject ?? false)
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    {{ __('dixlase-inquiry::front.form.subject') }}
                    @if($settings->subject_required ?? false)<span class="text-red-500">*</span>@endif
                </label>
                <input type="text" name="subject" value="{{ old('subject') }}"
                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                    placeholder="{{ __('dixlase-inquiry::front.form.subject_placeholder') }}"
                    @if($settings->subject_required ?? false) required @endif>
            </div>
            @endif

            {{-- 名前 --}}
            @if($settings->name_order_western ?? false)
            {{-- 欧米式（名・姓） --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        {{ __('dixlase-inquiry::front.form.first_name') }}<span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="first_name" value="{{ old('first_name') }}"
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                        placeholder="{{ __('dixlase-inquiry::front.form.first_name_placeholder') }}" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        {{ __('dixlase-inquiry::front.form.last_name') }}<span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="last_name" value="{{ old('last_name') }}"
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                        placeholder="{{ __('dixlase-inquiry::front.form.last_name_placeholder') }}" required>
                </div>
            </div>
            @else
            {{-- 日本式（姓・名） --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        {{ __('dixlase-inquiry::front.form.last_name') }}<span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="last_name" value="{{ old('last_name') }}"
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                        placeholder="{{ __('dixlase-inquiry::front.form.last_name_placeholder') }}" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        {{ __('dixlase-inquiry::front.form.first_name') }}<span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="first_name" value="{{ old('first_name') }}"
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                        placeholder="{{ __('dixlase-inquiry::front.form.first_name_placeholder') }}" required>
                </div>
            </div>
            @endif

            {{-- メールアドレス --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    {{ __('dixlase-inquiry::front.form.email') }}<span class="text-red-500">*</span>
                </label>
                <input type="email" name="email" value="{{ old('email') }}"
                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                    placeholder="{{ __('dixlase-inquiry::front.form.email_placeholder') }}" required>
            </div>

            {{-- 電話番号 --}}
            @if($settings->show_phone ?? true)
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    {{ __('dixlase-inquiry::front.form.phone') }}
                    @if($settings->phone_required ?? false)<span class="text-red-500">*</span>@endif
                </label>
                <input type="tel" name="phone" value="{{ old('phone') }}"
                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                    placeholder="{{ __('dixlase-inquiry::front.form.phone_placeholder') }}"
                    @if($settings->phone_required ?? false) required @endif>
            </div>
            @endif

            {{-- 問い合わせ内容 --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    {{ __('dixlase-inquiry::front.form.message') }}<span class="text-red-500">*</span>
                </label>
                <textarea name="message" rows="5"
                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                    placeholder="{{ __('dixlase-inquiry::front.form.message_placeholder') }}" required>{{ old('message') }}</textarea>
            </div>

            {{-- 送信ボタン --}}
            <div>
                <button type="submit"
                    class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-md transition-colors duration-200">
                    <i class="fas fa-paper-plane mr-2"></i>
                    {{ __('dixlase-inquiry::front.form.submit') }}
                </button>
            </div>
        </form>
    @endif
</div>
