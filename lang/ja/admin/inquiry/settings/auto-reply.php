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

    'default_subject' => 'お問い合わせを受け付けました',
    'default_body' => "この度は、お問い合わせいただきありがとうございます。\n\n以下の内容で承りました。\n内容を確認の上、担当者よりご連絡させていただきます。\n\nお名前: {{name}}\nメールアドレス: {{email}}\n題名: {{subject}}\n\nお問い合わせ内容:\n{{message}}\n\n今後ともよろしくお願いいたします。",

    'confirm_title' => '自動返信設定の保存',
    'confirm_message' => '自動返信設定を保存してもよろしいですか？',
    'settings_updated' => '自動返信設定が更新されました。',
];
