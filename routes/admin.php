<?php

/**
 * This file is part of DixlaseInquiry.
 *
 * Copyright (C) 2025 exc-D inc.
 * https://exc-d.com
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <https://www.gnu.org/licenses/>.
 */

use Illuminate\Support\Facades\Route;
use App\Models\SecuritySetting;

/*
|--------------------------------------------------------------------------
| 管理画面ルート
|--------------------------------------------------------------------------
|
| 管理画面用のルートを登録するファイルです。
| これらのルートは RouteServiceProvider によって読み込まれ、
| "web" と "auth" ミドルウェアグループに自動的に割り当てられます。
|
| セキュリティに関する注意:
| - auth:member ミドルウェアで認証を要求します
| - admin.ip ミドルウェアでIPアドレスフィルタリングを実施します
| - IPアドレスフィルタリングを実施しないとセキュリティリスクが高まります
|
*/

// 設定またはコンフィグから管理画面URLを取得
$adminUrl = SecuritySetting::get('admin_url', config('security.admin_url'));

// 管理画面用のルートグループ（セキュリティミドルウェア付き）
Route::prefix($adminUrl)
    ->name('admin.')
    ->middleware(['auth:member', 'admin.ip'])
    ->group(function () {
        // 管理画面のルートをここに定義します
    });