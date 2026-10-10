<?php

/**
 * This file is part of Dixlase Inquiry.
 *
 * Copyright (C) 2026 exc-D inc. and Dixlase contributors
 * https://exc-d.com
 *
 * Dixlase Inquiry is dual-licensed. You may use this file under either:
 *
 *   (a) the GNU General Public License version 3 or later, as published
 *       by the Free Software Foundation; or
 *
 *   (b) a commercial license agreement obtained from exc-D inc.
 *
 * Unless you have entered into a commercial license agreement, this
 * file is governed by the GPL terms below.
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
| ルート名: プラグイン側で完全に制御（例: dixlase-inquiry::admin.inquiry.index）
|
*/

// 問い合わせ管理
Route::prefix('inquiry')
    ->name('dixlase-inquiry::admin.inquiry.')
    ->group(function () {
        Route::get('/', [DixlaseInquiryAdminController::class, 'index'])->name('index');
        Route::get('/{id}', [DixlaseInquiryAdminController::class, 'show'])->name('show')->where('id', '[0-9]+');
        Route::delete('/{id}', [DixlaseInquiryAdminController::class, 'destroy'])->name('destroy')->where('id', '[0-9]+');
        Route::patch('/{id}/status', [DixlaseInquiryAdminController::class, 'updateStatus'])->name('status.update')->where('id', '[0-9]+');
        Route::patch('/{id}/expires-at', [DixlaseInquiryAdminController::class, 'updateExpiresAt'])->name('expires-at.update')->where('id', '[0-9]+');

        // 一括ステータス更新
        Route::patch('/bulk-status', [DixlaseInquiryAdminController::class, 'bulkUpdateStatus'])->name('bulk-status');

        // 受付状態のトグル（AJAX）
        Route::patch('/toggle-accepting', [DixlaseInquiryAdminController::class, 'toggleAccepting'])->name('toggle-accepting');

        // ゴミ箱（ソフトデリート済みの一覧・復元・完全削除）
        //
        // destroy() は SoftDeletes 対応でゴミ箱行き。ここは戻す/完全に消す
        // 経路と、ゴミ箱一覧そのもの。/{id} の numeric 制約のおかげで
        // /trash は先の show/destroy 経路にはぶつからない。
        //
        // 一覧ページはグループ内 `index` で trash.index を割り当てる。
        // config/admin/roles.php は parent+access_roles+children を使わない
        // (現状のコードベースに例が無く、コア #271 まで動かない場合がある)
        // ため、`trash.children.index` で単葉として権限を持たせる。
        Route::prefix('trash')->name('trash.')->group(function () {
            Route::get('/', [DixlaseInquiryAdminController::class, 'trash'])->name('index');
            Route::post('/empty', [DixlaseInquiryAdminController::class, 'emptyTrash'])->name('empty');
            Route::post('/{id}/restore', [DixlaseInquiryAdminController::class, 'restore'])->name('restore')->where('id', '[0-9]+');
            Route::delete('/{id}', [DixlaseInquiryAdminController::class, 'forceDestroy'])->name('force-destroy')->where('id', '[0-9]+');
        });

        // 設定サブメニュー
        Route::prefix('settings')->name('settings.')->group(function () {
            Route::get('/', [DixlaseInquiryAdminController::class, 'settingsIndex'])->name('index');
            Route::get('/form-basic', [DixlaseInquiryAdminController::class, 'settingsFormBasic'])->name('form-basic');
            Route::post('/form-basic', [DixlaseInquiryAdminController::class, 'updateFormBasic'])->name('form-basic.update');

            // フォームプレビュー
            Route::post('/form-basic/preview', [DixlaseInquiryAdminController::class, 'storePreviewSettings'])->name('form-basic.preview.store');
            Route::get('/form-basic/preview', [DixlaseInquiryAdminController::class, 'showPreview'])->name('form-basic.preview');
            Route::post('/form-basic/preview/confirm', [DixlaseInquiryAdminController::class, 'previewConfirm'])->name('form-basic.preview.confirm');
            Route::post('/form-basic/preview/send', [DixlaseInquiryAdminController::class, 'previewSend'])->name('form-basic.preview.send');
            Route::get('/completion', [DixlaseInquiryAdminController::class, 'settingsCompletion'])->name('completion');
            Route::post('/completion', [DixlaseInquiryAdminController::class, 'updateCompletion'])->name('completion.update');
            Route::get('/admin-notification', [DixlaseInquiryAdminController::class, 'settingsAdminNotification'])->name('admin-notification');
            Route::post('/admin-notification', [DixlaseInquiryAdminController::class, 'updateAdminNotification'])->name('admin-notification.update');
            Route::get('/auto-reply', [DixlaseInquiryAdminController::class, 'settingsAutoReply'])->name('auto-reply');
            Route::post('/auto-reply', [DixlaseInquiryAdminController::class, 'updateAutoReply'])->name('auto-reply.update');
            Route::get('/privacy', [DixlaseInquiryAdminController::class, 'settingsPrivacy'])->name('privacy');
            Route::post('/privacy', [DixlaseInquiryAdminController::class, 'updatePrivacy'])->name('privacy.update');
        });
    });
