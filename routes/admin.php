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
use Plugins\DixlaseInquiry\App\Http\Controllers\Admin\AdminInquiryController;

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

// 問い合わせ管理
Route::prefix('inquiries')
    ->name('dixlase-inquiry::admin.inquiries.')
    ->middleware(['admin.ip']) // IPアドレスフィルタを適用
    ->group(function () {
        // 認証チェックを各ルートで実行
        Route::middleware(['auth:member'])->group(function () {
            Route::get('/', [AdminInquiryController::class, 'index'])->name('index');
            Route::get('/settings', [AdminInquiryController::class, 'settings'])->name('settings');
            Route::post('/settings', [AdminInquiryController::class, 'updateSettings'])->name('settings.update');
            Route::get('/{id}', [AdminInquiryController::class, 'show'])->name('show');
            Route::delete('/{id}', [AdminInquiryController::class, 'destroy'])->name('destroy');
        });
    });