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
use Plugins\DixlaseInquiry\Shortcodes\InquiryFormShortcode;

class DixlaseInquiryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // `admin.nav` の設定をマージ
        $this->mergeAdminNavConfig(__DIR__ . '/../../config/admin.php');
        

        // Register shortcode
        $this->app->extend('shortcode', function ($shortcodeManager, $app) {
            $shortcodeManager->add('inquiry_form', InquiryFormShortcode::class);
            return $shortcodeManager;
        });

    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Load routes
        $this->loadRoutesFrom(__DIR__ . '/../../routes/web.php');
        
        // Load views
        $this->loadViewsFrom(__DIR__ . '/../../resources/views', 'inquiry');
        
        // Load migrations
        $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');
        
        // Publish assets
        $this->publishes([
            __DIR__ . '/../../resources/assets' => public_path('vendor/inquiry'),
        ], 'inquiry-assets');
    }
}