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
<div class="dixlase-inquiry-embed" id="inquiry-form" x-data="inquiryEmbedForm()">
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
                @if($settings->show_subject ?? false)
                <div x-show="formData.subject" class="border-b border-gray-200 dark:border-gray-700 pb-2">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('dixlase-inquiry::front.form.subject') }}</dt>
                    <dd class="mt-1 text-gray-900 dark:text-white" x-text="formData.subject"></dd>
                </div>
                @endif
                <div class="border-b border-gray-200 dark:border-gray-700 pb-2">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('dixlase-inquiry::front.form.name') }}</dt>
                    <dd class="mt-1 text-gray-900 dark:text-white" x-text="fullName"></dd>
                </div>
                <div class="border-b border-gray-200 dark:border-gray-700 pb-2">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('dixlase-inquiry::front.form.email') }}</dt>
                    <dd class="mt-1 text-gray-900 dark:text-white" x-text="formData.email"></dd>
                </div>
                @if($settings->show_phone ?? true)
                <div x-show="formData.phone" class="border-b border-gray-200 dark:border-gray-700 pb-2">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('dixlase-inquiry::front.form.phone') }}</dt>
                    <dd class="mt-1 text-gray-900 dark:text-white" x-text="formData.phone"></dd>
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

@if($settings->show_confirmation_page ?? true)
<script>
function inquiryEmbedForm() {
    return {
        showConfirmation: false,
        formData: {
            subject: '',
            first_name: '',
            last_name: '',
            email: '',
            phone: '',
            message: ''
        },
        nameOrderWestern: {{ ($settings->name_order_western ?? false) ? 'true' : 'false' }},
        get fullName() {
            if (this.nameOrderWestern) {
                return (this.formData.first_name + ' ' + this.formData.last_name).trim();
            }
            return (this.formData.last_name + ' ' + this.formData.first_name).trim();
        },
        showConfirm() {
            const form = this.$refs.inquiryForm;
            if (!form.checkValidity()) {
                form.reportValidity();
                return;
            }
            // フォームデータを収集
            this.formData.subject = form.querySelector('[name="subject"]')?.value || '';
            this.formData.first_name = form.querySelector('[name="first_name"]')?.value || '';
            this.formData.last_name = form.querySelector('[name="last_name"]')?.value || '';
            this.formData.email = form.querySelector('[name="email"]')?.value || '';
            this.formData.phone = form.querySelector('[name="phone"]')?.value || '';
            this.formData.message = form.querySelector('[name="message"]')?.value || '';
            this.showConfirmation = true;
            // スクロールして確認画面を表示
            this.$el.scrollIntoView({ behavior: 'smooth', block: 'start' });
        },
        submitForm() {
            this.$refs.inquiryForm.submit();
        }
    }
}
</script>
<style>
[x-cloak] { display: none !important; }
</style>
@else
<script>
function inquiryEmbedForm() {
    return {};
}
</script>
@endif
