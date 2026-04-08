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

    {{-- 未読バッジ --}}
    @if($unreadCount > 0)
        <div class="mb-4">
            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300">
                <i class="fas fa-envelope mr-1.5"></i>
                {{ __('dixlase-inquiry::admin/inquiry/index.unread_count', ['count' => $unreadCount]) }}
            </span>
        </div>
    @endif

    {{-- 検索セクション --}}
    <section class="mb-6">
        <h2 class="text-lg font-semibold mb-4">{{ __('dixlase-inquiry::admin/inquiry/index.search_title') }}</h2>

        <form action="{{ route('dixlase-inquiry::admin.inquiry.index') }}" method="GET"
              class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-4">
            <fieldset>
                <legend class="sr-only">{{ __('dixlase-inquiry::admin/inquiry/index.search_title') }}</legend>

                <div class="flex flex-wrap items-end gap-4">
                    {{-- キーワード検索 --}}
                    <div class="flex-1 min-w-[200px]">
                        <x-form-label for="search" :text="__('common.filters.search_keyword')" />
                        <x-form-text
                            id="search"
                            name="search"
                            :placeholder="__('dixlase-inquiry::admin/inquiry/index.search_placeholder')"
                            :value="$search"
                        />
                    </div>

                    {{-- ステータスフィルタ --}}
                    <div class="w-48">
                        <x-form-label for="status" :text="__('dixlase-inquiry::admin/inquiry/index.status_filter')" />
                        <x-form-select
                            id="status"
                            name="status"
                            :options="array_merge(
                                ['' => __('dixlase-inquiry::admin/inquiry/index.all_statuses')],
                                $statusLabels
                            )"
                            :value="$statusFilter"
                        />
                    </div>

                    {{-- ボタン --}}
                    <div class="flex gap-2">
                        <x-form-button
                            type="submit"
                            variant="primary"
                            :label="__('common.search')"
                            icon="fas fa-search"
                        />
                        <a href="{{ route('dixlase-inquiry::admin.inquiry.index') }}"
                           class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-600 flex items-center justify-center">
                            {{ __('common.filters.clear_button') }}
                        </a>
                    </div>
                </div>
            </fieldset>
        </form>
    </section>

    {{-- 一覧セクション --}}
    <section>
        {{-- ページネーションコントロール --}}
        <x-ui-pagination-controls
            :paginator="$inquiries"
            :perPageOptions="[10, 25, 50, 100]"
            :currentPerPage="request('per_page', 25)"
            totalLabel="components/ui-pagination.total_count"
            perPageLabel="components/ui-pagination.per_page_label"
        />

        {{-- ページネーション --}}
        <x-ui-pagination
            :pagination="[
                'current_page' => $inquiries->currentPage(),
                'last_page' => $inquiries->lastPage(),
                'prev_page' => $inquiries->currentPage() > 1 ? $inquiries->currentPage() - 1 : null,
                'next_page' => $inquiries->hasMorePages() ? $inquiries->currentPage() + 1 : null,
                'total' => $inquiries->total(),
                'per_page' => $inquiries->perPage(),
                'from' => $inquiries->firstItem(),
                'to' => $inquiries->lastItem(),
            ]"
            route="dixlase-inquiry::admin.inquiry.index"
            :routeParams="array_filter([
                'search' => request('search'),
                'status' => request('status'),
                'per_page' => request('per_page'),
            ])"
        />

        {{-- テーブル --}}
        @if($inquiries->count() > 0)
        <div class="responsive-table !border-0 !dark:border-0">
            <table class="border rounded-sm">
                <caption class="sr-only">{{ __('dixlase-inquiry::admin/inquiry/index.table.caption') }}</caption>
                <thead>
                    <tr>
                        <th>{{ __('dixlase-inquiry::admin/inquiry/index.table.id') }}</th>
                        <th>{{ __('dixlase-inquiry::admin/inquiry/index.table.status') }}</th>
                        <th>{{ __('dixlase-inquiry::admin/inquiry/index.table.name') }}</th>
                        <th>{{ __('dixlase-inquiry::admin/inquiry/index.table.email') }}</th>
                        <th>{{ __('dixlase-inquiry::admin/inquiry/index.table.subject') }}</th>
                        <th>{{ __('dixlase-inquiry::admin/inquiry/index.table.submitted_at') }}</th>
                        <th>{{ __('dixlase-inquiry::admin/inquiry/index.table.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($inquiries as $inquiry)
                        <tr class="{{ $inquiry->read_at === null ? 'font-semibold' : '' }}">
                            <td data-label="{{ __('dixlase-inquiry::admin/inquiry/index.table.id') }}">
                                {{ $inquiry->id }}
                            </td>
                            <td data-label="{{ __('dixlase-inquiry::admin/inquiry/index.table.status') }}">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $inquiry->status->cssClass() }}">
                                    {{ $inquiry->status->label() }}
                                </span>
                            </td>
                            <td data-label="{{ __('dixlase-inquiry::admin/inquiry/index.table.name') }}">
                                <a href="{{ route('dixlase-inquiry::admin.inquiry.show', $inquiry->id) }}" class="hover:underline text-blue-600 dark:text-blue-400">
                                    {{ $inquiry->name }}
                                    @if($inquiry->read_at === null)
                                        <span class="ml-1 inline-block w-2 h-2 bg-red-500 rounded-full"></span>
                                    @endif
                                </a>
                            </td>
                            <td data-label="{{ __('dixlase-inquiry::admin/inquiry/index.table.email') }}">
                                {{ $inquiry->email }}
                            </td>
                            <td data-label="{{ __('dixlase-inquiry::admin/inquiry/index.table.subject') }}">
                                {{ $inquiry->subject ?? __('dixlase-inquiry::admin/inquiry/show.not_set') }}
                            </td>
                            <td data-label="{{ __('dixlase-inquiry::admin/inquiry/index.table.submitted_at') }}">
                                {{ $inquiry->submitted_at->format('Y-m-d H:i') }}
                            </td>
                            <td data-label="{{ __('dixlase-inquiry::admin/inquiry/index.table.actions') }}">
                                <div class="flex items-center gap-1">
                                    <a href="{{ route('dixlase-inquiry::admin.inquiry.show', $inquiry->id) }}"
                                        class="p-2 text-gray-500 hover:text-blue-600 dark:text-gray-400 dark:hover:text-blue-400 transition-colors"
                                        title="{{ __('dixlase-inquiry::admin/inquiry/index.view') }}">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <form action="{{ route('dixlase-inquiry::admin.inquiry.destroy', $inquiry->id) }}" method="POST"
                                        @submit.prevent="if(confirm('{{ __('dixlase-inquiry::admin/inquiry/index.confirm_delete') }}')) $el.submit()">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="p-2 text-gray-500 hover:text-red-600 dark:text-gray-400 dark:hover:text-red-400 transition-colors"
                                            title="{{ __('dixlase-inquiry::admin/inquiry/index.delete') }}">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
            <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-8 text-center">
                <p class="text-gray-500 dark:text-gray-400">
                    {{ __('dixlase-inquiry::admin/inquiry/index.no_inquiries') }}
                </p>
            </div>
        @endif

        {{-- ページネーション（下部） --}}
        <x-ui-pagination
            :pagination="[
                'current_page' => $inquiries->currentPage(),
                'last_page' => $inquiries->lastPage(),
                'prev_page' => $inquiries->currentPage() > 1 ? $inquiries->currentPage() - 1 : null,
                'next_page' => $inquiries->hasMorePages() ? $inquiries->currentPage() + 1 : null,
                'total' => $inquiries->total(),
                'per_page' => $inquiries->perPage(),
                'from' => $inquiries->firstItem(),
                'to' => $inquiries->lastItem(),
            ]"
            route="dixlase-inquiry::admin.inquiry.index"
            :routeParams="array_filter([
                'search' => request('search'),
                'status' => request('status'),
                'per_page' => request('per_page'),
            ])"
        />
    </section>

</div>
@endsection
