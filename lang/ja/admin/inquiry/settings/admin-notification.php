<?php

/**
 * This file is part of Dixlase Inquiry.
 *
 * Copyright (C) 2026 exc-D inc.
 * https://exc-d.com
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 */

return [
    'heading' => '管理者通知設定',
    'description' => '管理者通知メールのメールアドレス、件名、本文を設定します。',

    'admin_email' => '送信先メールアドレス',
    'admin_email_help' => '問い合わせ内容が送信されるメールアドレスを入力してください。',
    'subject' => '管理者通知の件名',
    'body' => '管理者通知の本文',
    'body_help' => '使用可能な変数: {{name}}, {{email}}, {{subject}}, {{postal_code}}, {{address}}, {{phone}}, {{message}}',

    'mail_test_required' => '問い合わせフォーム機能を使用するには、<a href=":url" class="text-blue-600 hover:text-blue-800 underline">基本設定</a>でメールサーバー設定とメールテストをすべて完了してください。メールサーバーが設定されていない場合、問い合わせフォームは正常に動作しません。',

    'confirm_title' => '管理者通知設定の保存',
    'confirm_message' => '管理者通知設定を保存してもよろしいですか？',
    'settings_updated' => '管理者通知設定が更新されました。',
];
