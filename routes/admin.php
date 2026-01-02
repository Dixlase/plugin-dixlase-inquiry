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
use Plugins\DixlaseInquiry\App\Http\Controllers\Admin\DixlaseInquiryAdminController;

/*
|--------------------------------------------------------------------------
| プラグイン管理画面ルート（自動読み込み）
|--------------------------------------------------------------------------
|
| このファイルはプラグインが有効化されている場合、PluginServiceProviderによって
| 自動的に読み込まれます。以下のミドルウェアが自動適用されます：
|
| - admin.ip: IPアドレスフィルタリング
| - auth:member: 管理メンバー認証
| - verified: メール認証済みチェック
| - log.admin.activity: 管理画面操作ログ
|
| ルートプレフィックス: /admin（動的に取得）
| ルート名プレフィックス: admin.
|
*/

// 問い合わせ管理
Route::prefix('inquiry')
    ->name('dixlase-inquiry::admin.inquiry.')
    ->group(function () {
        Route::get('/', [DixlaseInquiryAdminController::class, 'index'])->name('index');
        Route::get('/settings', [DixlaseInquiryAdminController::class, 'settings'])->name('settings');
        Route::post('/settings', [DixlaseInquiryAdminController::class, 'updateSettings'])->name('settings.update');
        Route::get('/{id}', [DixlaseInquiryAdminController::class, 'show'])->name('show');
        Route::delete('/{id}', [DixlaseInquiryAdminController::class, 'destroy'])->name('destroy');
    });