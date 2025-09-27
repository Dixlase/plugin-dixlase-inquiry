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

@extends('admin::partials.layout')

@section('title', __('dixlase-inquiry::admin.pages.index.title'))

@section('content')
    <div class="container mx-auto px-4 py-6">
        <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h1 class="text-xl font-semibold text-gray-900 dark:text-white">{{ __('dixlase-inquiry::admin.pages.index.heading') }}</h1>
            </div>
            
            <div class="p-6">
                <div class="text-center py-8">
                    <i class="fas fa-envelope text-4xl text-gray-400 mb-4"></i>
                    <p class="text-gray-600 dark:text-gray-400">{{ __('dixlase-inquiry::admin.pages.index.no_inquiries') }}</p>
                    <p class="text-sm text-gray-500 dark:text-gray-500 mt-2">
                        <a href="{{ route('admin.dixlase-inquiry::admin.inquiries.settings') }}" class="text-blue-600 hover:text-blue-800">{{ __('dixlase-inquiry::admin.nav.inquiries.settings') }}</a>から{{ __('dixlase-inquiry::admin.pages.index.setup_message') }}
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection
