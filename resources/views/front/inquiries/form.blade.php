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

@extends('themes::layouts.app')

@section('title', __('dixlase-inquiry::front.form.title'))

@section('content')
    <div class="dixlase-inquiry">
        <div class="container mx-auto pt-32 pb-16 px-4 sm:px-6 lg:px-8">
            <article class="bg-white dark:bg-gray-800 shadow-lg rounded-lg overflow-hidden max-w-2xl mx-auto">
                @if(url()->previous() !== url()->current())
                <div class="px-8 pt-6">
                    <a href="{{ url()->previous() }}"
                        class="inline-flex items-center text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white transition-colors">
                        <i class="fas fa-chevron-left mr-2"></i>
                        {{ __('dixlase-inquiry::front.buttons.back') }}
                    </a>
                </div>
                @endif

                {{-- ページヘッダー（見出し+説明文） --}}
                <header class="px-8 pt-10 pb-8 mb-12 text-center">
                    @php
                        $heading = dls_inquiry_localized_setting('form_heading');
                        $description = dls_inquiry_localized_setting('form_description');
                    @endphp
                    @if(!empty($heading))
                        <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-5">{{ $heading }}</h1>
                    @endif
                    @if(!empty($description))
                        <p class="text-gray-600 dark:text-gray-400 max-w-lg mx-auto">{!! nl2br(e($description)) !!}</p>
                    @endif
                </header>

                {{-- フォームコンテンツ --}}
                <div class="px-8 pt-8 pb-10">
                    <form action="{{ $settings->show_confirmation_page ? route('inquiry.confirm') : route('inquiry.send') }}" method="POST">
                        @csrf
                        {{-- Validation errors (same block as the embed form). The
                             fields below only flag privacy consent inline, so a
                             rejected CAPTCHA token or a missing field would
                             otherwise bring the visitor back here with no reason. --}}
                        @if($errors->any())
                            <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 text-red-700 dark:text-red-300 px-4 py-3 rounded-lg mb-6" role="alert">
                                <ul class="list-disc list-inside space-y-1">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        @include('dixlase-inquiry::front.inquiries.partials.form-fields')
                    </form>
                </div>
            </article>
        </div>
    </div>
@endsection
