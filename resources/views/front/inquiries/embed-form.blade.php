{{--
This file is part of Dixlase Inquiry.

Copyright (C) 2026 exc-D inc.
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
<div class="dixlase-inquiry-embed" id="inquiry-form" x-data="inquiryEmbedForm({{ ($settings->show_confirmation_page ?? true) ? 'true' : 'false' }}, {{ ($settings->name_order_western ?? false) ? 'true' : 'false' }})">
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

        {{-- 確認画面 --}}
        @if($settings->show_confirmation_page ?? true)
        <div x-show="showConfirmation" x-cloak class="space-y-4">
            <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4 mb-4">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">{{ __('dixlase-inquiry::front.confirmation.title') }}</h3>
                <p class="text-gray-600 dark:text-gray-400 text-sm">{{ __('dixlase-inquiry::front.confirmation.message') }}</p>
            </div>

            <dl class="space-y-3">
                <div class="border-b border-gray-200 dark:border-gray-700 pb-2">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('dixlase-inquiry::front.form.name') }}</dt>
                    <dd class="mt-1 text-gray-900 dark:text-white" x-text="fullName"></dd>
                </div>
                @if(!($settings->name_order_western ?? false) && ($settings->show_kana ?? false))
                <div x-show="formData.last_name_kana || formData.first_name_kana" class="border-b border-gray-200 dark:border-gray-700 pb-2">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('dixlase-inquiry::front.form.kana') }}</dt>
                    <dd class="mt-1 text-gray-900 dark:text-white" x-text="(formData.last_name_kana + ' ' + formData.first_name_kana).trim()"></dd>
                </div>
                @endif
                <div class="border-b border-gray-200 dark:border-gray-700 pb-2">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('dixlase-inquiry::front.form.email') }}</dt>
                    <dd class="mt-1 text-gray-900 dark:text-white" x-text="formData.email"></dd>
                </div>
                @if($settings->show_address ?? false)
                <div x-show="formData.postal_code" class="border-b border-gray-200 dark:border-gray-700 pb-2">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('dixlase-inquiry::front.form.postal_code') }}</dt>
                    <dd class="mt-1 text-gray-900 dark:text-white" x-text="formData.postal_code"></dd>
                </div>
                @endif
                @if($settings->show_address ?? false)
                <div x-show="formData.address" class="border-b border-gray-200 dark:border-gray-700 pb-2">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('dixlase-inquiry::front.form.address') }}</dt>
                    <dd class="mt-1 text-gray-900 dark:text-white" x-text="formData.address"></dd>
                </div>
                @endif
                @if($settings->show_phone ?? true)
                <div x-show="formData.phone" class="border-b border-gray-200 dark:border-gray-700 pb-2">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('dixlase-inquiry::front.form.phone') }}</dt>
                    <dd class="mt-1 text-gray-900 dark:text-white" x-text="formData.phone"></dd>
                </div>
                @endif
                @if($settings->show_gender ?? false)
                <div x-show="formData.gender" class="border-b border-gray-200 dark:border-gray-700 pb-2">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('dixlase-inquiry::front.form.gender') }}</dt>
                    <dd class="mt-1 text-gray-900 dark:text-white" x-text="formData.genderLabel"></dd>
                </div>
                @endif
                @if($settings->show_subject ?? false)
                <div x-show="formData.subject" class="border-b border-gray-200 dark:border-gray-700 pb-2">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('dixlase-inquiry::front.form.subject') }}</dt>
                    <dd class="mt-1 text-gray-900 dark:text-white" x-text="formData.subject"></dd>
                </div>
                @endif
                <div class="pb-2">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('dixlase-inquiry::front.form.message') }}</dt>
                    <dd class="mt-1 text-gray-900 dark:text-white whitespace-pre-wrap" x-text="formData.message"></dd>
                </div>
            </dl>

            <div class="flex gap-3 pt-4">
                <button type="button" @click="showConfirmation = false"
                    class="inline-flex items-center px-4 py-2 bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 font-medium rounded-md transition-colors duration-200">
                    <i class="fas fa-arrow-left mr-2"></i>
                    {{ __('dixlase-inquiry::front.buttons.back') }}
                </button>
                <button type="button" @click="submitForm()"
                    class="inline-flex items-center px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-md transition-colors duration-200">
                    <i class="fas fa-paper-plane mr-2"></i>
                    {{ __('dixlase-inquiry::front.buttons.send') }}
                </button>
            </div>
        </div>
        @endif

        <form x-ref="inquiryForm" action="{{ route('inquiry.embed.send') }}" method="POST" class="space-y-4"
            @if($settings->show_confirmation_page ?? true)
            x-show="!showConfirmation" @submit.prevent="showConfirm()"
            @endif
        >
            @csrf
            <input type="hidden" name="redirect_url" value="{{ url()->current() }}#inquiry-form">

            {{-- 1. 名前（2カラム） --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    {{ __('dixlase-inquiry::front.form.name') }}<span class="text-red-500">*</span>
                </label>
                @if($settings->name_order_western ?? false)
                {{-- 欧米式（名・姓） --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <input type="text" name="first_name" value="{{ old('first_name') }}"
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                            placeholder="{{ __('dixlase-inquiry::front.form.first_name_placeholder') }}" required>
                    </div>
                    <div>
                        <input type="text" name="last_name" value="{{ old('last_name') }}"
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                            placeholder="{{ __('dixlase-inquiry::front.form.last_name_placeholder') }}" required>
                    </div>
                </div>
                @else
                {{-- 日本式（姓・名） --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <input type="text" name="last_name" value="{{ old('last_name') }}"
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                            placeholder="{{ __('dixlase-inquiry::front.form.last_name_placeholder') }}" required>
                    </div>
                    <div>
                        <input type="text" name="first_name" value="{{ old('first_name') }}"
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                            placeholder="{{ __('dixlase-inquiry::front.form.first_name_placeholder') }}" required>
                    </div>
                </div>
                @endif
            </div>

            {{-- 1b. カタカナ（日本式+カナONの場合のみ） --}}
            @if(!($settings->name_order_western ?? false) && ($settings->show_kana ?? false))
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    {{ __('dixlase-inquiry::front.form.kana') }}
                    @if($settings->require_kana ?? false)<span class="text-red-500">*</span>@endif
                </label>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <input type="text" name="last_name_kana" value="{{ old('last_name_kana') }}"
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                            placeholder="{{ __('dixlase-inquiry::front.form.last_name_kana_placeholder') }}"
                            @if($settings->require_kana ?? false) required @endif>
                    </div>
                    <div>
                        <input type="text" name="first_name_kana" value="{{ old('first_name_kana') }}"
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                            placeholder="{{ __('dixlase-inquiry::front.form.first_name_kana_placeholder') }}"
                            @if($settings->require_kana ?? false) required @endif>
                    </div>
                </div>
            </div>
            @endif

            {{-- 2. メールアドレス --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    {{ __('dixlase-inquiry::front.form.email') }}<span class="text-red-500">*</span>
                </label>
                <input type="email" name="email" value="{{ old('email') }}"
                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                    placeholder="{{ __('dixlase-inquiry::front.form.email_placeholder') }}" required>
            </div>

            {{-- 3. 郵便番号 --}}
            @if($settings->show_address ?? false)
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    {{ __('dixlase-inquiry::front.form.postal_code') }}
                    @if($settings->postal_code_required ?? false)<span class="text-red-500">*</span>@endif
                </label>
                @if($settings->name_order_western ?? false)
                    {{-- 欧米式: 単一フィールド --}}
                    <input type="text" name="postal_code" value="{{ old('postal_code') }}"
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                        placeholder="{{ __('dixlase-inquiry::front.form.postal_code_placeholder') }}"
                        @if($settings->postal_code_required ?? false) required @endif>
                @else
                    {{-- 日本式: 2分割 --}}
                    <div class="flex items-center gap-2">
                        <input type="text" name="postal_code_1" value="{{ old('postal_code_1') }}" maxlength="3"
                            class="w-20 px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                            placeholder="{{ __('dixlase-inquiry::front.form.postal_code_1_placeholder') }}"
                            @if($settings->postal_code_required ?? false) required @endif>
                        <span class="text-gray-500 dark:text-gray-400">-</span>
                        <input type="text" name="postal_code_2" value="{{ old('postal_code_2') }}" maxlength="4"
                            class="w-24 px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                            placeholder="{{ __('dixlase-inquiry::front.form.postal_code_2_placeholder') }}"
                            @if($settings->postal_code_required ?? false) required @endif>
                    </div>
                @endif
            </div>
            @endif

            {{-- 4. 住所 --}}
            @if($settings->show_address ?? false)
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    {{ __('dixlase-inquiry::front.form.address') }}
                    @if($settings->address_required ?? false)<span class="text-red-500">*</span>@endif
                </label>
                @if($settings->name_order_western ?? false)
                    {{-- 欧米式住所 --}}
                    <div class="space-y-3">
                        <div>
                            <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">
                                {{ __('dixlase-inquiry::front.form.street_address') }}
                            </label>
                            <input type="text" name="street_address" value="{{ old('street_address') }}"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                                placeholder="{{ __('dixlase-inquiry::front.form.street_address_placeholder') }}"
                                @if($settings->address_required ?? false) required @endif>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">
                                {{ __('dixlase-inquiry::front.form.building') }}
                            </label>
                            <input type="text" name="building" value="{{ old('building') }}"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                                placeholder="{{ __('dixlase-inquiry::front.form.building_placeholder') }}">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">
                                {{ __('dixlase-inquiry::front.form.city') }}
                            </label>
                            <input type="text" name="city" value="{{ old('city') }}"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                                placeholder="{{ __('dixlase-inquiry::front.form.city_placeholder') }}"
                                @if($settings->address_required ?? false) required @endif>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">
                                {{ __('dixlase-inquiry::front.form.state') }}
                            </label>
                            <input type="text" name="state" value="{{ old('state') }}"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                                placeholder="{{ __('dixlase-inquiry::front.form.state_placeholder') }}">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">
                                {{ __('dixlase-inquiry::front.form.country') }}
                            </label>
                            <input type="text" name="country" value="{{ old('country') }}"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                                placeholder="{{ __('dixlase-inquiry::front.form.country_placeholder') }}">
                        </div>
                    </div>
                @else
                    {{-- 日本式住所 --}}
                    <div class="space-y-3">
                        <div>
                            <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">
                                {{ __('dixlase-inquiry::front.form.prefecture') }}
                            </label>
                            <select name="prefecture"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                                @if($settings->address_required ?? false) required @endif>
                                <option value="">{{ __('dixlase-inquiry::front.form.prefecture_placeholder') }}</option>
                                @foreach($prefectures as $key => $name)
                                    <option value="{{ $key }}" {{ old('prefecture') === $key ? 'selected' : '' }}>{{ $name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">
                                {{ __('dixlase-inquiry::front.form.city') }}
                            </label>
                            <input type="text" name="city" value="{{ old('city') }}"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                                placeholder="{{ __('dixlase-inquiry::front.form.city_placeholder') }}"
                                @if($settings->address_required ?? false) required @endif>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">
                                {{ __('dixlase-inquiry::front.form.address_line') }}
                            </label>
                            <input type="text" name="address_line" value="{{ old('address_line') }}"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                                placeholder="{{ __('dixlase-inquiry::front.form.address_line_placeholder') }}"
                                @if($settings->address_required ?? false) required @endif>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">
                                {{ __('dixlase-inquiry::front.form.building') }}
                            </label>
                            <input type="text" name="building" value="{{ old('building') }}"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                                placeholder="{{ __('dixlase-inquiry::front.form.building_placeholder') }}">
                        </div>
                    </div>
                @endif
            </div>
            @endif

            {{-- 5. 電話番号 --}}
            @if($settings->show_phone ?? true)
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    {{ __('dixlase-inquiry::front.form.phone') }}
                    @if($settings->phone_required ?? false)<span class="text-red-500">*</span>@endif
                </label>
                @if($settings->name_order_western ?? false)
                    {{-- 欧米式: 単一フィールド --}}
                    <input type="tel" name="phone" value="{{ old('phone') }}"
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                        placeholder="{{ __('dixlase-inquiry::front.form.phone_placeholder') }}"
                        @if($settings->phone_required ?? false) required @endif>
                @else
                    {{-- 日本式: 3分割 --}}
                    <div class="flex items-center gap-2">
                        <input type="tel" name="phone_1" value="{{ old('phone_1') }}" maxlength="5"
                            class="w-20 px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                            placeholder="{{ __('dixlase-inquiry::front.form.phone_1_placeholder') }}"
                            @if($settings->phone_required ?? false) required @endif>
                        <span class="text-gray-500 dark:text-gray-400">-</span>
                        <input type="tel" name="phone_2" value="{{ old('phone_2') }}" maxlength="4"
                            class="w-20 px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                            placeholder="{{ __('dixlase-inquiry::front.form.phone_2_placeholder') }}"
                            @if($settings->phone_required ?? false) required @endif>
                        <span class="text-gray-500 dark:text-gray-400">-</span>
                        <input type="tel" name="phone_3" value="{{ old('phone_3') }}" maxlength="4"
                            class="w-20 px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                            placeholder="{{ __('dixlase-inquiry::front.form.phone_3_placeholder') }}"
                            @if($settings->phone_required ?? false) required @endif>
                    </div>
                @endif
            </div>
            @endif

            {{-- 6. 性別（ラジオカード） --}}
            @if($settings->show_gender ?? false)
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    {{ __('dixlase-inquiry::front.form.gender') }}
                    @if($settings->gender_required ?? false)<span class="text-red-500">*</span>@endif
                </label>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-3" x-data="{ selectedGender: '{{ old('gender', '') }}' }">
                    @foreach($genderOptions as $option)
                    <label class="flex items-center p-3 border rounded-lg cursor-pointer transition-all duration-150"
                           :class="selectedGender === '{{ $option['value'] }}' ? 'border-blue-600 bg-blue-50 dark:bg-blue-900/30 dark:border-blue-500 ring-2 ring-blue-600 dark:ring-blue-500' : 'border-gray-300 dark:border-gray-600 hover:border-gray-400'"
                           @click="selectedGender = '{{ $option['value'] }}'">
                        <input type="radio" name="gender" value="{{ $option['value'] }}" x-model="selectedGender" class="sr-only"
                            @if($settings->gender_required ?? false) required @endif>
                        <i class="{{ $option['icon'] }} mr-2"></i>
                        <span class="text-sm">{{ $option['label'] }}</span>
                    </label>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- 7. 題名 --}}
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

            {{-- 8. 問い合わせ内容 --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    {{ __('dixlase-inquiry::front.form.message') }}<span class="text-red-500">*</span>
                </label>
                <textarea name="message" rows="6"
                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                    placeholder="{{ __('dixlase-inquiry::front.form.message_placeholder') }}" required>{{ old('message') }}</textarea>
            </div>

            {{-- 9. プライバシー同意 --}}
            @if($settings->privacy_consent_enabled ?? false)
            <div>
                <div class="flex items-center space-x-3 my-3">
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="hidden" name="privacy_agreed" value="0">
                        <input type="checkbox" name="privacy_agreed" value="1" required
                               {{ old('privacy_agreed') ? 'checked' : '' }}
                               class="sr-only peer">
                        <div class="w-11 h-6 rounded-full bg-gray-300 dark:bg-gray-600 peer-checked:bg-blue-600 dark:peer-checked:bg-blue-500 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-offset-2 peer-focus:ring-blue-500 transition-colors"></div>
                        <div class="absolute left-1 top-1 w-4 h-4 bg-white border border-gray-300 rounded-full transition-all peer-checked:translate-x-full peer-checked:border-white"></div>
                    </label>
                    <span class="text-sm text-gray-700 dark:text-gray-300">
                        @if(!empty($settings->privacy_consent_text))
                            @if(!empty($privacyUrl))
                                <a href="{{ $privacyUrl }}" target="_blank" class="text-blue-600 hover:underline dark:text-blue-400">{{ $settings->privacy_consent_text }}</a>
                            @else
                                {{ $settings->privacy_consent_text }}
                            @endif
                        @else
                            @if(!empty($privacyUrl))
                                {!! __('dixlase-inquiry::front.form.privacy_consent', ['url' => $privacyUrl]) !!}
                            @else
                                {{ __('dixlase-inquiry::front.form.privacy_consent_default') }}
                            @endif
                        @endif
                    </span>
                </div>
            </div>
            @endif

            {{-- 10. 送信ボタン --}}
            <div>
                <button type="submit"
                    class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-md transition-colors duration-200">
                    @if($settings->show_confirmation_page ?? true)
                    <i class="fas fa-check mr-2"></i>
                    {{ __('dixlase-inquiry::front.buttons.confirm') }}
                    @else
                    <i class="fas fa-paper-plane mr-2"></i>
                    {{ __('dixlase-inquiry::front.form.submit') }}
                    @endif
                </button>
            </div>
        </form>
    @endif
</div>

{{-- プラグインアセットの読み込み（@once で重複防止） --}}
@once
@push('styles')
    {!! load_plugin_assets('DixlaseInquiry', ['css/style.scss']) !!}
@endpush
@push('scripts')
    {!! load_plugin_assets('DixlaseInquiry', ['js/app.js']) !!}
@endpush
@endonce
