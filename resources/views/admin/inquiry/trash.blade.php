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
--}}

@extends('layouts.admin')

@section('content')
<div class="max-w-7xl mx-auto">

    {{-- 戻るリンクとゴミ箱操作ヘッダー --}}
    <div class="mb-4 flex items-center justify-between gap-3">
        <a href="{{ route('dixlase-inquiry::admin.inquiry.index') }}"
            class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-600 transition-colors">
            <i class="fas fa-arrow-left mr-2"></i>
            {{ __('dixlase-inquiry::admin/inquiry/trash.back_to_index') }}
        </a>

        {{-- ゴミ箱を空にするボタン（ADMIN のみ、かつ 1 件以上ある場合） --}}
        {{-- トリガーとモーダルは同じ条件で囲む必要がある(片方だけ描画すると
             ui-modal が console.warn を出す)。inquiries->total() のガードも
             ここで共有し、モーダル側も同じ条件を反復する。 --}}
        @if($canEmptyTrash && $inquiries->total() > 0)
            <button type="button"
                @click="openModal('emptyTrashModal')"
                class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-red-600 hover:bg-red-700 border border-transparent rounded-md transition-colors">
                <i class="fas fa-trash-alt mr-2"></i>
                {{ __('dixlase-inquiry::admin/inquiry/trash.empty_trash') }}
            </button>
        @endif
    </div>

    {{-- 保持期間の告知 --}}
    <div class="mb-6 rounded-lg border border-amber-200 dark:border-amber-800 bg-amber-50 dark:bg-amber-900/20 p-4">
        <div class="flex gap-3">
            <div class="flex-shrink-0 pt-0.5">
                <i class="fas fa-info-circle text-amber-600 dark:text-amber-400"></i>
            </div>
            <div class="text-sm text-amber-900 dark:text-amber-200">
                <p>{{ __('dixlase-inquiry::admin/inquiry/trash.retention_notice') }}</p>
                <p class="mt-2 text-amber-800 dark:text-amber-300">{{ __('dixlase-inquiry::admin/inquiry/trash.gdpr_hint') }}</p>
            </div>
        </div>
    </div>

    {{-- 検索セクション --}}
    <section class="mb-6">
        <form action="{{ route('dixlase-inquiry::admin.inquiry.trash.index') }}" method="GET"
            class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-4">
            <fieldset>
                <legend class="sr-only">{{ __('common.search') }}</legend>
                <div class="flex flex-wrap items-end gap-4">
                    <div class="flex-1 min-w-[200px]">
                        <x-form-label for="search" :text="__('common.filters.search_keyword')" />
                        <x-form-text
                            id="search"
                            name="search"
                            :value="$search"
                        />
                    </div>
                    <div class="flex gap-2">
                        <x-form-button
                            type="submit"
                            variant="primary"
                            :label="__('common.search')"
                            icon="fas fa-search"
                        />
                        <a href="{{ route('dixlase-inquiry::admin.inquiry.trash.index') }}"
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
            route="dixlase-inquiry::admin.inquiry.trash.index"
            :routeParams="array_filter([
                'search' => request('search'),
                'per_page' => request('per_page'),
            ])"
        />

        @if($inquiries->count() > 0)
            <div class="responsive-table !border-0 !dark:border-0">
                <table class="border rounded-sm">
                    <caption class="sr-only">{{ __('dixlase-inquiry::admin/inquiry/trash.table.caption') }}</caption>
                    <thead>
                        <tr>
                            <th>{{ __('dixlase-inquiry::admin/inquiry/trash.table.id') }}</th>
                            <th>{{ __('dixlase-inquiry::admin/inquiry/trash.table.name') }}</th>
                            <th>{{ __('dixlase-inquiry::admin/inquiry/trash.table.email') }}</th>
                            <th>{{ __('dixlase-inquiry::admin/inquiry/trash.table.subject') }}</th>
                            <th>{{ __('dixlase-inquiry::admin/inquiry/trash.table.deleted_at') }}</th>
                            <th>{{ __('dixlase-inquiry::admin/inquiry/trash.table.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($inquiries as $inquiry)
                            <tr>
                                <td data-label="{{ __('dixlase-inquiry::admin/inquiry/trash.table.id') }}">
                                    {{ $inquiry->id }}
                                </td>
                                <td data-label="{{ __('dixlase-inquiry::admin/inquiry/trash.table.name') }}">
                                    {{ $inquiry->name }}
                                </td>
                                <td data-label="{{ __('dixlase-inquiry::admin/inquiry/trash.table.email') }}">
                                    {{ $inquiry->email }}
                                </td>
                                <td data-label="{{ __('dixlase-inquiry::admin/inquiry/trash.table.subject') }}">
                                    {{ $inquiry->subject ?? __('dixlase-inquiry::admin/inquiry/show.not_set') }}
                                </td>
                                <td data-label="{{ __('dixlase-inquiry::admin/inquiry/trash.table.deleted_at') }}">
                                    {{ $inquiry->deleted_at?->format('Y-m-d H:i') }}
                                </td>
                                <td data-label="{{ __('dixlase-inquiry::admin/inquiry/trash.table.actions') }}">
                                    <div class="flex items-center gap-1">
                                        {{-- 復元（EDITOR 以上） --}}
                                        <form id="restoreForm-{{ $inquiry->id }}" action="{{ route('dixlase-inquiry::admin.inquiry.trash.restore', $inquiry->id) }}" method="POST" class="inline">
                                            @csrf
                                        </form>
                                        <button type="button"
                                            @click="openModal('restoreModal-{{ $inquiry->id }}')"
                                            class="p-2 text-gray-500 hover:text-blue-600 dark:text-gray-400 dark:hover:text-blue-400 transition-colors"
                                            title="{{ __('dixlase-inquiry::admin/inquiry/trash.restore') }}">
                                            <i class="fas fa-undo"></i>
                                        </button>

                                        {{-- 完全削除（ADMIN のみ）— トリガーとモーダルを同じ条件で囲む --}}
                                        @if($canForceDestroyInquiries)
                                            <form id="forceDestroyForm-{{ $inquiry->id }}" action="{{ route('dixlase-inquiry::admin.inquiry.trash.force-destroy', $inquiry->id) }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                            <button type="button"
                                                @click="openModal('forceDestroyModal-{{ $inquiry->id }}')"
                                                class="p-2 text-gray-500 hover:text-red-600 dark:text-gray-400 dark:hover:text-red-400 transition-colors"
                                                title="{{ __('dixlase-inquiry::admin/inquiry/trash.force_destroy') }}">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        @endif
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
                    {{ __('dixlase-inquiry::admin/inquiry/trash.no_trashed_inquiries') }}
                </p>
            </div>
        @endif

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
            route="dixlase-inquiry::admin.inquiry.trash.index"
            :routeParams="array_filter([
                'search' => request('search'),
                'per_page' => request('per_page'),
            ])"
        />
    </section>
</div>

{{-- 復元 / 完全削除モーダル（各行）--}}
@push('modals')
    @foreach($inquiries as $inquiry)
        {{-- 復元モーダル。form=... で先の hidden form を submit する --}}
        <x-ui-modal id="restoreModal-{{ $inquiry->id }}"
            :title="__('dixlase-inquiry::admin/inquiry/trash.restore')"
            :message="__('dixlase-inquiry::admin/inquiry/trash.restore') . ': ' . $inquiry->name"
            icon-type="info"
            :form="'restoreForm-' . $inquiry->id"
            confirm-color="blue"
        />

        {{-- 完全削除モーダル。トリガーと同じ条件で囲む(重要) --}}
        @if($canForceDestroyInquiries)
            <x-ui-modal id="forceDestroyModal-{{ $inquiry->id }}"
                :title="__('dixlase-inquiry::admin/inquiry/trash.confirm_force_destroy_title')"
                :message="__('dixlase-inquiry::admin/inquiry/trash.confirm_force_destroy')"
                icon-type="danger"
                :form="'forceDestroyForm-' . $inquiry->id"
                confirm-color="red"
            />
        @endif
    @endforeach

    {{-- ゴミ箱を空にする確認モーダル。トリガーと同じ条件で囲む --}}
    @if($canEmptyTrash && $inquiries->total() > 0)
        <form id="emptyTrashForm" action="{{ route('dixlase-inquiry::admin.inquiry.trash.empty') }}" method="POST">
            @csrf
        </form>
        <x-ui-modal id="emptyTrashModal"
            :title="__('dixlase-inquiry::admin/inquiry/trash.confirm_empty_title')"
            :message="__('dixlase-inquiry::admin/inquiry/trash.confirm_empty')"
            icon-type="danger"
            form="emptyTrashForm"
            confirm-color="red"
        />
    @endif
@endpush
@endsection
