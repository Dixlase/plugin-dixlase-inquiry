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

namespace Plugins\DixlaseInquiry\App\Providers;

use App\Contracts\RouteSlugProvider;
use App\DTO\RouteSlug\RegisteredSlug;
use App\Helpers\PluginHelper;
use App\Traits\PluginLoaderTrait;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Plugins\DixlaseInquiry\App\Models\DixlaseInquirySetting;
use Plugins\DixlaseInquiry\App\Services\DixlaseInquiryDashboardProvider;
use Plugins\DixlaseInquiry\App\Shortcodes\DixlaseInquiryFormShortcode;

class DixlaseInquiryServiceProvider extends ServiceProvider implements RouteSlugProvider
{
    use PluginLoaderTrait;

    /**
     * Register services.
     */
    public function register(): void
    {
        // Load helper functions
        require_once __DIR__.'/../Helpers/DixlaseInquiryHelpers.php';

        // プラグイン設定ファイルの登録
        $this->mergeConfigFrom(__DIR__.'/../../config/inquiry.php', 'dixlase-inquiry');
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // .git/info/excludeへの追加はplugin:installコマンドで自動実行されます

        // ルートスラッグプロバイダーの登録
        $this->registerRouteSlugProvider();

        // レートリミッター登録
        $this->registerRateLimiter();

        // 動的ルート登録（別ページモード用）
        // 注: 静的ルート（routes/web.php, routes/admin.php）はPluginServiceProviderが自動読み込み
        $this->registerDynamicRoutes();

        // ショートコード登録
        $this->registerShortcodes();

        // Bladeディレクティブ登録
        $this->registerBladeDirectives();

        // Dashboard notification provider registration
        $this->app->singleton(DixlaseInquiryDashboardProvider::class);
        $this->app->tag([DixlaseInquiryDashboardProvider::class], 'plugin.capabilities');

        // Preview provider registration
        $this->app->singleton(\Plugins\DixlaseInquiry\App\Services\DixlaseInquiryPreviewProvider::class);
        $this->app->tag([\Plugins\DixlaseInquiry\App\Services\DixlaseInquiryPreviewProvider::class], 'plugin.capabilities');

        // Load views
        $this->loadViewsFrom(__DIR__.'/../../resources/views', 'dixlase-inquiry');

        // Load translations
        $this->loadTranslationsFrom(__DIR__.'/../../lang', 'dixlase-inquiry');

        // Load migrations
        $this->loadMigrationsFrom(__DIR__.'/../../database/migrations');

        // Publish assets
        $this->publishes([
            __DIR__.'/../../resources/assets' => public_path('vendor/inquiry'),
        ], 'inquiry-assets');
    }

    /**
     * レートリミッター登録
     */
    protected function registerRateLimiter(): void
    {
        RateLimiter::for('inquiry-submit', function (Request $request) {
            try {
                $settings = DixlaseInquirySetting::getSettings();

                if (! ($settings->throttle_enabled ?? true)) {
                    return Limit::none();
                }

                $maxAttempts = (int) ($settings->throttle_max_attempts ?? 3);
                $decayMinutes = (int) ($settings->throttle_decay_minutes ?? 5);

                return Limit::perMinutes($decayMinutes, $maxAttempts)->by($request->ip());
            } catch (\Exception $e) {
                // DB未接続時はデフォルト制限
                return Limit::perMinutes(5, 3)->by($request->ip());
            }
        });
    }

    /**
     * ショートコード登録
     */
    protected function registerShortcodes(): void
    {
        // コアのPluginHelperを使用してショートコードを登録
        PluginHelper::registerShortcode('inquiry', DixlaseInquiryFormShortcode::class);
    }

    /**
     * Bladeディレクティブ登録
     */
    protected function registerBladeDirectives(): void
    {
        // @inquiry ディレクティブを登録
        Blade::directive('inquiry', function ($expression) {
            return '<?php echo app(\\Plugins\\DixlaseInquiry\\App\\Shortcodes\\DixlaseInquiryFormShortcode::class)->render(); ?>';
        });
    }

    /**
     * 動的ルート登録
     */
    protected function registerDynamicRoutes(): void
    {
        try {
            $settings = DixlaseInquirySetting::getSettings();

            // 別ページモードの場合のみルート登録
            if (! $settings->use_single_page) {
                $slug = $settings->inquiry_url_slug ?? 'inquiry';

                Route::middleware(['web', 'front.ip'])
                    ->group(function () use ($slug) {
                        Route::get($slug, [\Plugins\DixlaseInquiry\App\Http\Controllers\Front\DixlaseInquiryFrontController::class, 'index'])
                            ->name('inquiry.index');
                        Route::post($slug.'/confirm', [\Plugins\DixlaseInquiry\App\Http\Controllers\Front\DixlaseInquiryFrontController::class, 'confirm'])
                            ->name('inquiry.confirm');
                        Route::post($slug.'/send', [\Plugins\DixlaseInquiry\App\Http\Controllers\Front\DixlaseInquiryFrontController::class, 'send'])
                            ->name('inquiry.send')
                            ->middleware('throttle:inquiry-submit');
                    });
            }
        } catch (\Exception $e) {
            // データベースがまだ存在しない場合などのエラーを無視
        }
    }

    /**
     * ルートスラッグプロバイダーを登録
     */
    protected function registerRouteSlugProvider(): void
    {
        if (app()->bound(\App\Services\RouteSlugRegistry::class)) {
            app(\App\Services\RouteSlugRegistry::class)
                ->registerProvider('dixlase-inquiry', $this);
        }
    }

    /**
     * プラグインが管理するルートスラッグを返す
     *
     * @return array<RegisteredSlug>
     */
    public function getRouteSlugs(): array
    {
        try {
            $settings = DixlaseInquirySetting::getSettings();
            $slug = $settings->inquiry_url_slug ?? 'inquiry';
        } catch (\Exception $e) {
            $slug = 'inquiry';
        }

        return [
            new RegisteredSlug(
                slug: $slug,
                owner: 'dixlase-inquiry:directory',
                label: 'dixlase-inquiry::route-slug.owners.directory',
            ),
        ];
    }

    /**
     * プラグインアンインストール時の処理
     */
    public function uninstall(): void
    {
        // .git/info/excludeからの削除はplugin:uninstallコマンドで自動実行されます
    }
}
