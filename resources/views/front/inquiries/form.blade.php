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
        <!-- メインコンテンツ -->
        <div class="container mx-auto py-8 px-4 sm:px-6 lg:px-8">
            <article class="bg-white dark:bg-gray-800 shadow-lg rounded-lg overflow-hidden">
                <!-- ページヘッダー -->
                <header class="px-6 py-8 border-b border-gray-200 dark:border-gray-700">
                    <h1 class="text-4xl font-bold text-gray-900 dark:text-white mb-4">
                        {{ __('dixlase-inquiry::front.form.heading') }}
                    </h1>
                </header>

                <!-- フォームコンテンツ -->
                <div class="px-6 py-8">
                    <form action="{{ $settings->show_confirmation_page ? route('inquiry.confirm') : route('inquiry.send') }}" method="POST">
                        @csrf
                        @include('dixlase-inquiry::front.inquiries.partials.form-fields')
                    </form>
                </div>
            </article>
        </div>
    </div>
@endsection
