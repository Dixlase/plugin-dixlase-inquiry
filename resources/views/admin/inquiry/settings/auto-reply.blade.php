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
<div class="mx-auto">
    <form id="auto-reply-settings-form" action="{{ route('dixlase-inquiry::admin.inquiry.settings.auto-reply.update') }}" method="POST"
          x-data="{ autoReplyEnabled: '{{ old('auto_reply_enabled', $settings->auto_reply_enabled ?? true) ? '1' : '0' }}' }">
        @csrf

        <section>
            <fieldset>
                <div class="grid grid-cols-1 gap-6">
                    <x-form-toggle
                        name="auto_reply_enabled"
                        :label="__('dixlase-inquiry::admin/inquiry/settings/auto-reply.enabled')"
                        :checked="old('auto_reply_enabled', $settings->auto_reply_enabled ?? true)"
                        xModel="autoReplyEnabled"
                    />

                    <div x-show="autoReplyEnabled === '1'" x-cloak class="space-y-6">
                        <fieldset>
                            <legend>{{ __('dixlase-inquiry::admin/inquiry/settings/auto-reply.from_email') }}</legend>
                            <x-form-text
                                name="auto_reply_from_email"
                                :value="old('auto_reply_from_email', $settings->auto_reply_from_email ?? '')"
                            />
                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                {{ __('dixlase-inquiry::admin/inquiry/settings/auto-reply.from_email_help') }}
                            </p>
                        </fieldset>

                        <fieldset>
                            <legend>{{ __('dixlase-inquiry::admin/inquiry/settings/auto-reply.subject') }}</legend>
                            <x-form-text
                                name="auto_reply_subject"
                                :value="old('auto_reply_subject', $settings->auto_reply_subject ?? '')"
                            />
                        </fieldset>

                        <fieldset>
                            <legend>{{ __('dixlase-inquiry::admin/inquiry/settings/auto-reply.body') }}</legend>
                            <x-form-textarea
                                name="auto_reply_body"
                                :value="old('auto_reply_body', $settings->auto_reply_body ?? '')"
                                :rows="8"
                            />
                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                {{ __('dixlase-inquiry::admin/inquiry/settings/auto-reply.body_help') }}
                            </p>
                        </fieldset>
                    </div>
                </div>
            </fieldset>
        </section>

    </form>
</div>
@endsection

@section('save')
    <x-admin.save-button
        id_confirmation="confirmAutoReplySettingsModal"
        :label="__('common.save')"
        :title="__('dixlase-inquiry::admin/inquiry/settings/auto-reply.confirm_title')"
        :message="__('dixlase-inquiry::admin/inquiry/settings/auto-reply.confirm_message')"
        :confirm_label="__('common.save')"
        :cancel_label="__('common.cancel')"
        form="auto-reply-settings-form"
    />
@endsection
