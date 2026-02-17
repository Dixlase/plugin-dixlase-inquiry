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

@extends('layouts.admin')

@section('content')
<div class="max-w-7xl mx-auto">

    {{-- ヘッダー --}}
    <div class="flex justify-between items-center mb-6">
        <a href="{{ route('dixlase-inquiry::admin.inquiry.index') }}"
           class="inline-flex items-center text-sm text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-200">
            <i class="fas fa-arrow-left mr-2"></i>
            {{ __('dixlase-inquiry::admin/inquiry/show.back_to_list') }}
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- メインコンテンツ（左2カラム） --}}
        <div class="lg:col-span-2 space-y-6">

            {{-- 問い合わせ情報 --}}
            <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                    {{ __('dixlase-inquiry::admin/inquiry/show.inquiry_info') }}
                </h2>

                <dl class="space-y-4">
                    <div class="border-b border-gray-200 dark:border-gray-700 pb-3">
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('dixlase-inquiry::admin/inquiry/show.name') }}</dt>
                        <dd class="mt-1 text-gray-900 dark:text-white">{{ $inquiry->name }}</dd>
                    </div>

                    <div class="border-b border-gray-200 dark:border-gray-700 pb-3">
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('dixlase-inquiry::admin/inquiry/show.email') }}</dt>
                        <dd class="mt-1 text-gray-900 dark:text-white">
                            <a href="mailto:{{ $inquiry->email }}" class="text-blue-600 hover:underline dark:text-blue-400">
                                {{ $inquiry->email }}
                            </a>
                        </dd>
                    </div>

                    @if($inquiry->subject)
                    <div class="border-b border-gray-200 dark:border-gray-700 pb-3">
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('dixlase-inquiry::admin/inquiry/show.subject') }}</dt>
                        <dd class="mt-1 text-gray-900 dark:text-white">{{ $inquiry->subject }}</dd>
                    </div>
                    @endif

                    @if($inquiry->phone)
                    <div class="border-b border-gray-200 dark:border-gray-700 pb-3">
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('dixlase-inquiry::admin/inquiry/show.phone') }}</dt>
                        <dd class="mt-1 text-gray-900 dark:text-white">{{ $inquiry->phone }}</dd>
                    </div>
                    @endif

                    @if($inquiry->postal_code)
                    <div class="border-b border-gray-200 dark:border-gray-700 pb-3">
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('dixlase-inquiry::admin/inquiry/show.postal_code') }}</dt>
                        <dd class="mt-1 text-gray-900 dark:text-white">{{ $inquiry->postal_code }}</dd>
                    </div>
                    @endif

                    @if($inquiry->address)
                    <div class="border-b border-gray-200 dark:border-gray-700 pb-3">
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('dixlase-inquiry::admin/inquiry/show.address') }}</dt>
                        <dd class="mt-1 text-gray-900 dark:text-white">{{ $inquiry->address }}</dd>
                    </div>
                    @endif

                    @if($inquiry->gender)
                    <div class="border-b border-gray-200 dark:border-gray-700 pb-3">
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('dixlase-inquiry::admin/inquiry/show.gender') }}</dt>
                        <dd class="mt-1 text-gray-900 dark:text-white">{{ $inquiry->gender }}</dd>
                    </div>
                    @endif

                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('dixlase-inquiry::admin/inquiry/show.message') }}</dt>
                        <dd class="mt-1 text-gray-900 dark:text-white whitespace-pre-wrap bg-gray-50 dark:bg-gray-900 rounded-lg p-4">{{ $inquiry->message }}</dd>
                    </div>
                </dl>
            </div>

        </div>

        {{-- サイドバー（右1カラム） --}}
        <div class="space-y-6">

            {{-- ステータス変更 --}}
            <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6">
                <h3 class="text-sm font-semibold text-gray-900 dark:text-white mb-3">
                    {{ __('dixlase-inquiry::admin/inquiry/show.change_status') }}
                </h3>

                <form action="{{ route('dixlase-inquiry::admin.inquiry.status.update', $inquiry->id) }}" method="POST">
                    @csrf
                    @method('PATCH')

                    <div class="flex gap-2">
                        <x-form-select
                            name="status"
                            :options="$statusLabels"
                            :value="$inquiry->status->value"
                        />
                        <x-form-button
                            type="submit"
                            variant="primary"
                            size="sm"
                            icon="fas fa-check"
                        />
                    </div>
                </form>
            </div>

            {{-- メタ情報 --}}
            <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6">
                <h3 class="text-sm font-semibold text-gray-900 dark:text-white mb-3">
                    {{ __('dixlase-inquiry::admin/inquiry/show.meta_info') }}
                </h3>

                <dl class="space-y-3 text-sm">
                    <div>
                        <dt class="text-gray-500 dark:text-gray-400">{{ __('dixlase-inquiry::admin/inquiry/show.status') }}</dt>
                        <dd class="mt-1">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $inquiry->status->cssClass() }}">
                                {{ $inquiry->status->label() }}
                            </span>
                        </dd>
                    </div>

                    <div>
                        <dt class="text-gray-500 dark:text-gray-400">{{ __('dixlase-inquiry::admin/inquiry/show.submitted_at') }}</dt>
                        <dd class="mt-1 text-gray-900 dark:text-white">{{ $inquiry->submitted_at->format('Y-m-d H:i:s') }}</dd>
                    </div>

                    <div>
                        <dt class="text-gray-500 dark:text-gray-400">{{ __('dixlase-inquiry::admin/inquiry/show.read_at') }}</dt>
                        <dd class="mt-1 text-gray-900 dark:text-white">
                            {{ $inquiry->read_at ? $inquiry->read_at->format('Y-m-d H:i:s') : __('dixlase-inquiry::admin/inquiry/show.not_set') }}
                        </dd>
                    </div>

                    <div>
                        <dt class="text-gray-500 dark:text-gray-400">{{ __('dixlase-inquiry::admin/inquiry/show.privacy_agreed') }}</dt>
                        <dd class="mt-1 text-gray-900 dark:text-white">
                            @if($inquiry->privacy_agreed_at)
                                {{ __('dixlase-inquiry::admin/inquiry/show.privacy_agreed_yes', ['date' => $inquiry->privacy_agreed_at->format('Y-m-d H:i')]) }}
                            @else
                                {{ __('dixlase-inquiry::admin/inquiry/show.privacy_agreed_no') }}
                            @endif
                        </dd>
                    </div>

                    <div>
                        <dt class="text-gray-500 dark:text-gray-400">{{ __('dixlase-inquiry::admin/inquiry/show.form_locale') }}</dt>
                        <dd class="mt-1 text-gray-900 dark:text-white">{{ $inquiry->form_locale }}</dd>
                    </div>

                    <div>
                        <dt class="text-gray-500 dark:text-gray-400">{{ __('dixlase-inquiry::admin/inquiry/show.ip_address') }}</dt>
                        <dd class="mt-1 text-gray-900 dark:text-white">{{ $inquiry->ip_address ?? __('dixlase-inquiry::admin/inquiry/show.not_set') }}</dd>
                    </div>

                    <div>
                        <dt class="text-gray-500 dark:text-gray-400">{{ __('dixlase-inquiry::admin/inquiry/show.user_agent') }}</dt>
                        <dd class="mt-1 text-gray-900 dark:text-white text-xs break-all">{{ $inquiry->user_agent ?? __('dixlase-inquiry::admin/inquiry/show.not_set') }}</dd>
                    </div>
                </dl>
            </div>

            {{-- 削除ボタン --}}
            <div class="bg-white dark:bg-gray-800 rounded-lg border border-red-200 dark:border-red-800 p-6" x-data="{ showDeleteConfirm: false }">
                <x-form-button
                    type="button"
                    variant="danger"
                    :label="__('dixlase-inquiry::admin/inquiry/show.delete')"
                    icon="fas fa-trash"
                    @click="showDeleteConfirm = true"
                />

                <div x-show="showDeleteConfirm" x-cloak class="mt-4 p-3 bg-red-50 dark:bg-red-900/20 rounded-lg">
                    <p class="text-sm text-red-700 dark:text-red-400 mb-3">
                        {{ __('dixlase-inquiry::admin/inquiry/show.delete_confirm_message') }}
                    </p>
                    <form action="{{ route('dixlase-inquiry::admin.inquiry.destroy', $inquiry->id) }}" method="POST" class="flex gap-2">
                        @csrf
                        @method('DELETE')
                        <x-form-button
                            type="submit"
                            variant="danger"
                            size="sm"
                            :label="__('dixlase-inquiry::admin/inquiry/show.delete')"
                        />
                        <x-form-button
                            type="button"
                            variant="secondary"
                            size="sm"
                            :label="__('common.cancel')"
                            @click="showDeleteConfirm = false"
                        />
                    </form>
                </div>
            </div>

        </div>
    </div>

</div>
@endsection
