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

namespace Plugins\DixlaseInquiry\Tests\Unit;

use PHPUnit\Framework\TestCase;

class RouteConfigTest extends TestCase
{
    /**
     * ルートファイルにform-previewルートが含まれない
     */
    public function test_routes_do_not_contain_form_preview(): void
    {
        $routeFile = file_get_contents(__DIR__ . '/../../routes/admin.php');

        $this->assertStringNotContainsString("'form-preview'", $routeFile);
        $this->assertStringNotContainsString('settingsFormPreview', $routeFile);
    }

    /**
     * ルートファイルにform-displayルートが含まれない
     */
    public function test_routes_do_not_contain_form_display(): void
    {
        $routeFile = file_get_contents(__DIR__ . '/../../routes/admin.php');

        $this->assertStringNotContainsString("'form-display'", $routeFile);
        $this->assertStringNotContainsString('settingsFormDisplay', $routeFile);
        $this->assertStringNotContainsString('updateFormDisplay', $routeFile);
    }

    /**
     * ルートファイルにform-basicルートが含まれる
     */
    public function test_routes_contain_form_basic(): void
    {
        $routeFile = file_get_contents(__DIR__ . '/../../routes/admin.php');

        $this->assertStringContainsString("'form-basic'", $routeFile);
        $this->assertStringContainsString('settingsFormBasic', $routeFile);
        $this->assertStringContainsString('updateFormBasic', $routeFile);
    }

    /**
     * ナビゲーション設定にform-previewとform-displayが含まれない
     */
    public function test_navigation_does_not_contain_removed_entries(): void
    {
        $navFile = file_get_contents(__DIR__ . '/../../config/admin/navigation.php');

        $this->assertStringNotContainsString("'form-preview'", $navFile);
        $this->assertStringNotContainsString("'form-display'", $navFile);
    }

    /**
     * ナビゲーション設定にform-basicが含まれる
     */
    public function test_navigation_contains_form_basic(): void
    {
        $navFile = file_get_contents(__DIR__ . '/../../config/admin/navigation.php');

        $this->assertStringContainsString("'form-basic'", $navFile);
    }

    /**
     * コントローラーにFormDisplayRequestのimportが含まれない
     */
    public function test_controller_does_not_import_form_display_request(): void
    {
        $controllerFile = file_get_contents(
            __DIR__ . '/../../app/Http/Controllers/Admin/DixlaseInquiryAdminController.php'
        );

        $this->assertStringNotContainsString('DixlaseInquiryFormDisplayRequest', $controllerFile);
        $this->assertStringNotContainsString('settingsFormPreview', $controllerFile);
        $this->assertStringNotContainsString('settingsFormDisplay', $controllerFile);
        $this->assertStringNotContainsString('updateFormDisplay', $controllerFile);
    }

    /**
     * FormDisplayRequestファイルが削除されている
     */
    public function test_form_display_request_file_deleted(): void
    {
        $this->assertFileDoesNotExist(
            __DIR__ . '/../../app/Http/Requests/Admin/DixlaseInquiryFormDisplayRequest.php'
        );
    }

    /**
     * form-previewビューファイルが削除されている
     */
    public function test_form_preview_view_deleted(): void
    {
        $this->assertFileDoesNotExist(
            __DIR__ . '/../../resources/views/admin/inquiry/settings/form-preview.blade.php'
        );
    }

    /**
     * form-displayビューファイルが削除されている
     */
    public function test_form_display_view_deleted(): void
    {
        $this->assertFileDoesNotExist(
            __DIR__ . '/../../resources/views/admin/inquiry/settings/form-display.blade.php'
        );
    }

    /**
     * form-previewパーシャルが存在する（統合先として残る）
     */
    public function test_form_preview_partial_exists(): void
    {
        $this->assertFileExists(
            __DIR__ . '/../../resources/views/admin/inquiry/partials/form-preview.blade.php'
        );
    }

    /**
     * ルートファイルにstatus.updateルートが含まれる
     */
    public function test_routes_contain_status_update(): void
    {
        $routeFile = file_get_contents(__DIR__ . '/../../routes/admin.php');

        $this->assertStringContainsString('status.update', $routeFile);
        $this->assertStringContainsString('updateStatus', $routeFile);
    }

    /**
     * ServiceProviderにRateLimiter登録が含まれる
     */
    public function test_service_provider_registers_rate_limiter(): void
    {
        $providerFile = file_get_contents(
            __DIR__ . '/../../app/Providers/DixlaseInquiryServiceProvider.php'
        );

        $this->assertStringContainsString('RateLimiter::for', $providerFile);
        $this->assertStringContainsString('inquiry-submit', $providerFile);
    }

    /**
     * 埋め込みルートにthrottleミドルウェアが適用されている
     */
    public function test_embed_route_has_throttle_middleware(): void
    {
        $webRouteFile = file_get_contents(__DIR__ . '/../../routes/web.php');

        $this->assertStringContainsString('throttle:inquiry-submit', $webRouteFile);
    }

    /**
     * ServiceProviderにconfig登録が含まれる
     */
    public function test_service_provider_registers_config(): void
    {
        $providerFile = file_get_contents(
            __DIR__ . '/../../app/Providers/DixlaseInquiryServiceProvider.php'
        );

        $this->assertStringContainsString("mergeConfigFrom", $providerFile);
        $this->assertStringContainsString("'dixlase-inquiry'", $providerFile);
    }
}
