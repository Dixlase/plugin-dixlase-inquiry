{{--
This file is part of Dixlase Inquiry.

Copyright (C) 2026 exc-D inc.
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

@extends('layouts.admin')

@section('content')
<div class="mx-auto">
    <form id="completion-settings-form" action="{{ route('dixlase-inquiry::admin.inquiry.settings.completion.update') }}" method="POST">
        @csrf

        <section>
            <fieldset>
                <legend>{{ __('dixlase-inquiry::admin/inquiry/settings/completion.title_text') }}</legend>
                <x-form-text
                    name="completion_title"
                    :value="old('completion_title', $settings->completion_title ?? '')"
                />
                <p class="text-sm text-gray-600 dark:text-gray-400">
                    {{ __('dixlase-inquiry::admin/inquiry/settings/completion.title_help') }}
                </p>
            </fieldset>

            <fieldset>
                <legend>{{ __('dixlase-inquiry::admin/inquiry/settings/completion.message') }}</legend>
                <x-form-textarea
                    name="completion_message"
                    :value="old('completion_message', $settings->completion_message ?? '')"
                    :rows="4"
                />
                <p class="text-sm text-gray-600 dark:text-gray-400">
                    {{ __('dixlase-inquiry::admin/inquiry/settings/completion.message_help') }}
                </p>
            </fieldset>
        </section>

    </form>
</div>
@endsection

@section('save')
    <x-admin.save-button
        id_confirmation="confirmCompletionSettingsModal"
        :label="__('common.save')"
        :title="__('dixlase-inquiry::admin/inquiry/settings/completion.confirm_title')"
        :message="__('dixlase-inquiry::admin/inquiry/settings/completion.confirm_message')"
        :confirm_label="__('common.save')"
        :cancel_label="__('common.cancel')"
        form="completion-settings-form"
    />
@endsection
