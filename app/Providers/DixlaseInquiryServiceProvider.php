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
use Illuminate\Support\Facades\Blade;
use App\Traits\PluginLoaderTrait;
use App\Helpers\PluginHelper;
use Plugins\DixlaseInquiry\App\Models\InquirySetting;
use Plugins\DixlaseInquiry\App\Shortcodes\InquiryFormShortcode;

class DixlaseInquiryServiceProvider extends ServiceProvider
{
    use PluginLoaderTrait;
    /**
     * Register services.
     */
    public function register(): void
    {
        // Load helper functions
        require_once __DIR__ . '/../Helpers/InquiryHelpers.php';
        
        // Merge admin navigation
        $this->mergeAdminNavigation('DixlaseInquiry', __DIR__ . '/../../config/admin.php');
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // .git/info/excludeへの追加はplugin:installコマンドで自動実行されます
        
        // 動的ルート登録（別ページモード用）
        // 注: 静的ルート（routes/web.php, routes/admin.php）はPluginServiceProviderが自動読み込み
        $this->registerDynamicRoutes();
        
        // ショートコード登録
        $this->registerShortcodes();
        
        // Bladeディレクティブ登録
        $this->registerBladeDirectives();
        
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
        // コアのPluginHelperを使用してショートコードを登録
        PluginHelper::registerShortcode('inquiry', InquiryFormShortcode::class);
    }
    
    /**
     * Bladeディレクティブ登録
     */
    protected function registerBladeDirectives(): void
    {
        // @inquiry ディレクティブを登録
        Blade::directive('inquiry', function ($expression) {
            return "<?php echo app(\\Plugins\\DixlaseInquiry\\App\\Shortcodes\\InquiryFormShortcode::class)->render(); ?>";
        });
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
        // .git/info/excludeからの削除はplugin:uninstallコマンドで自動実行されます
    }
}