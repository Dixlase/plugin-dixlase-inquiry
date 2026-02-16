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
    'heading' => '自動返信設定',
    'description' => 'お問い合わせフォーム送信後にユーザーへ送信される自動返信メールを設定します。',

    'enabled' => '自動返信を有効にする',
    'from_email' => '自動返信の送信元メールアドレス',
    'from_email_help' => '空の場合は、システムのデフォルト送信元アドレスが使用されます。',
    'subject' => '自動返信の件名',
    'body' => '自動返信の本文',
    'body_help' => '使用可能な変数: {{name}}, {{email}}, {{subject}}, {{postal_code}}, {{address}}, {{phone}}, {{message}}',

    'confirm_title' => '自動返信設定の保存',
    'confirm_message' => '自動返信設定を保存してもよろしいですか？',
    'settings_updated' => '自動返信設定が更新されました。',
];
