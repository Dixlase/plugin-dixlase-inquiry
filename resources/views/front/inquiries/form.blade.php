{{--
This file is part of Dixlase Inquiry.

Copyright (C) 2026 exc-D inc.
Website: https://exc-d.com

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
        <div class="container mx-auto pt-24 pb-16 px-4 sm:px-6 lg:px-8">
            <article class="bg-white dark:bg-gray-800 shadow-lg rounded-lg overflow-hidden max-w-2xl mx-auto">
                {{-- ページヘッダー（見出し+説明文） --}}
                <header class="px-8 pt-10 pb-6 text-center">
                    @if(!empty($settings->form_heading))
                        <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-3">{{ $settings->form_heading }}</h1>
                    @endif
                    @if(!empty($settings->form_description))
                        <p class="text-gray-600 dark:text-gray-400 max-w-lg mx-auto">{!! nl2br(e($settings->form_description)) !!}</p>
                    @endif
                </header>

                {{-- フォームコンテンツ --}}
                <div class="px-8 pb-10">
                    <form action="{{ $settings->show_confirmation_page ? route('inquiry.confirm') : route('inquiry.send') }}" method="POST">
                        @csrf
                        @include('dixlase-inquiry::front.inquiries.partials.form-fields')
                    </form>
                </div>
            </article>
        </div>
    </div>
@endsection
