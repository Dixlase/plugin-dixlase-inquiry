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
use App\Traits\PluginLoaderTrait;
use App\Helpers\PluginGitignoreHelper;

class DixlaseInquiryServiceProvider extends ServiceProvider
{
    use PluginLoaderTrait;
    /**
     */
    public function register(): void
    {
        // Register shortcode
        $this->app->extend('shortcode', function ($shortcodeManager, $app) {
            $shortcodeManager->add('inquiry_form', InquiryFormShortcode::class);
            return $shortcodeManager;
        });
        
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
     * プラグインアンインストール時の処理
     */
    public function uninstall(): void
    {
        // プラグインを.gitignore除外リストから削除
        PluginGitignoreHelper::removePlugin('DixlaseInquiry');
    }
}