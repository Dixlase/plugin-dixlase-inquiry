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

@extends('layouts.admin')

@section('content')
<div class="mx-auto">

    {{-- メールサーバー設定の確認メッセージ --}}
    @if(!($mailConnectionTested && $mailSendTested && $mailReceiveTested))
        <div class="mb-6">
            {{--
                Direct the operator at the core mail-settings page (where the
                Test Connection / Test Send / Verify Reception buttons live)
                rather than the basic-settings overview. The earlier
                `admin.settings.base` route name had no trailing segment and
                therefore did not exist — the issue only surfaced on
                environments where at least one mail-test flag was still
                unticked, because this @if branch hides the link otherwise.
            --}}
            <x-ui-message
                type="warning"
                :message="__('dixlase-inquiry::admin/inquiry/settings/admin-notification.mail_test_required', ['url' => route('admin.settings.base.mail')])"
            />
        </div>
    @endif

    <form id="admin-notification-settings-form" action="{{ route('dixlase-inquiry::admin.inquiry.settings.admin-notification.update') }}" method="POST">
        @csrf

        <section>
            <fieldset>
                <legend>{{ __('dixlase-inquiry::admin/inquiry/settings/admin-notification.admin_email') }}</legend>
                <x-form-text
                    name="admin_email"
                    :value="old('admin_email', $settings->admin_email ?? '')"
                    :required="true"
                />
                <p class="text-sm text-gray-600 dark:text-gray-400">
                    {{ __('dixlase-inquiry::admin/inquiry/settings/admin-notification.admin_email_help') }}
                </p>
            </fieldset>

            <fieldset>
                <legend>{{ __('dixlase-inquiry::admin/inquiry/settings/admin-notification.subject') }}</legend>
                <x-form-text
                    name="subject"
                    :value="old('subject', $settings->subject ?? '')"
                />
            </fieldset>

            <fieldset>
                <legend>{{ __('dixlase-inquiry::admin/inquiry/settings/admin-notification.body') }}</legend>
                <x-form-textarea
                    name="body"
                    :value="old('body', $settings->body ?? '')"
                    :rows="8"
                />
                <p class="text-sm text-gray-600 dark:text-gray-400">
                    {{ __('dixlase-inquiry::admin/inquiry/settings/admin-notification.body_help') }}
                </p>
            </fieldset>
        </section>

    </form>
</div>
@endsection

@section('save')
    <x-admin.save-button
        id_confirmation="confirmAdminNotificationSettingsModal"
        :label="__('common.save')"
        :title="__('dixlase-inquiry::admin/inquiry/settings/admin-notification.confirm_title')"
        :message="__('dixlase-inquiry::admin/inquiry/settings/admin-notification.confirm_message')"
        :confirm_label="__('common.save')"
        :cancel_label="__('common.cancel')"
        form="admin-notification-settings-form"
    />
@endsection
