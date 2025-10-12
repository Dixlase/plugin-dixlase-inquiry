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

namespace Plugins\DixlaseInquiry\App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use App\Traits\PluginLoaderTrait;
use App\Helpers\PluginGitignoreHelper;
use Plugins\DixlaseInquiry\App\Models\InquirySetting;

class DixlaseInquiryServiceProvider extends ServiceProvider
{
    use PluginLoaderTrait;
    /**
     * Register services.
     */
    public function register(): void
    {
        // Merge admin navigation
        $this->mergeAdminNavigation('DixlaseInquiry', __DIR__ . '/../../config/admin.php');
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // プラグインを.gitignore除外リストに自動追加
        PluginGitignoreHelper::addPlugin('DixlaseInquiry');
        
        // Load routes (PluginServiceProviderの自動読み込みを無効化したため、手動で読み込み)
        $this->loadRoutesFrom(__DIR__ . '/../../routes/web.php');
        
        // 動的ルート登録（別ページモード用）
        $this->registerDynamicRoutes();
        
        // ショートコード登録
        $this->registerShortcodes();
        
        // Load views
        $this->loadViewsFrom(__DIR__ . '/../../resources/views', 'dixlase-inquiry');
        
        // Load translations
        $this->loadTranslationsFrom(__DIR__ . '/../../lang', 'dixlase-inquiry');
        
        // Load migrations
        $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');
        
        // Publish assets
        $this->publishes([
            __DIR__ . '/../../resources/assets' => public_path('vendor/inquiry'),
        ], 'inquiry-assets');
    }
    
    /**
     * ショートコード登録
     */
    protected function registerShortcodes(): void
    {
        if ($this->app->bound('shortcode')) {
            $shortcode = $this->app['shortcode'];
            $shortcode->add('inquiry', \Plugins\DixlaseInquiry\App\Shortcodes\InquiryFormShortcode::class);
        }
    }
    
    /**
     * 動的ルート登録
     */
    protected function registerDynamicRoutes(): void
    {
        try {
            $settings = InquirySetting::getSettings();
            
            // 別ページモードの場合のみルート登録
            if (!$settings->use_single_page) {
                $slug = $settings->inquiry_url_slug ?? 'inquiry';
                
                Route::middleware(['web', 'front.ip'])
                    ->group(function () use ($slug) {
                        Route::get($slug, [\Plugins\DixlaseInquiry\App\Http\Controllers\Front\FrontInquiryController::class, 'index'])
                            ->name('inquiry.index');
                        Route::post($slug . '/confirm', [\Plugins\DixlaseInquiry\App\Http\Controllers\Front\FrontInquiryController::class, 'confirm'])
                            ->name('inquiry.confirm');
                        Route::post($slug . '/send', [\Plugins\DixlaseInquiry\App\Http\Controllers\Front\FrontInquiryController::class, 'send'])
                            ->name('inquiry.send');
                    });
            }
        } catch (\Exception $e) {
            // データベースがまだ存在しない場合などのエラーを無視
            \Log::debug('Failed to register dynamic inquiry routes: ' . $e->getMessage());
        }
    }
    
    /**
     * プラグインアンインストール時の処理
     */
    public function uninstall(): void
    {
        // プラグインを.gitignore除外リストから削除
        PluginGitignoreHelper::removePlugin('DixlaseInquiry');
    }
}