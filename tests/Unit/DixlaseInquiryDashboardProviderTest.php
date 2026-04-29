<?php

/**
 * This file is part of Dixlase Inquiry.
 *
 * Copyright (C) 2026 exc-D inc.
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

use App\Contracts\PluginIntegration\DashboardNotificationProviderInterface;
use App\DTO\PluginIntegration\DashboardNotificationDTO;
use App\Models\CaptchaEnabledForm;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Plugins\DixlaseInquiry\App\Services\DixlaseInquiryDashboardProvider;
use Tests\TestCase;

class DixlaseInquiryDashboardProviderTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Provider implements DashboardNotificationProviderInterface
     */
    public function test_implements_dashboard_notification_provider_interface(): void
    {
        $provider = new DixlaseInquiryDashboardProvider();

        $this->assertInstanceOf(DashboardNotificationProviderInterface::class, $provider);
    }

    /**
     * CAPTCHA disabled returns recommendation notification
     */
    public function test_returns_notification_when_captcha_not_enabled(): void
    {
        // Ensure no CAPTCHA form is enabled for inquiry
        CaptchaEnabledForm::where('form_key', 'dixlase-inquiry.inquiry_contact')->delete();

        $provider = new DixlaseInquiryDashboardProvider();
        $notifications = $provider->getNotifications();

        $this->assertCount(1, $notifications);
        $this->assertInstanceOf(DashboardNotificationDTO::class, $notifications[0]);
        $this->assertEquals('inquiry_captcha_off', $notifications[0]->key);
        $this->assertEquals('recommendation', $notifications[0]->level);
        $this->assertEquals('fas fa-robot', $notifications[0]->icon);
        $this->assertNotNull($notifications[0]->url);
    }

    /**
     * CAPTCHA enabled returns empty notifications
     */
    public function test_returns_empty_when_captcha_enabled(): void
    {
        CaptchaEnabledForm::enableForm('dixlase-inquiry.inquiry_contact');

        $provider = new DixlaseInquiryDashboardProvider();
        $notifications = $provider->getNotifications();

        $this->assertCount(0, $notifications);
    }

    /**
     * Notification has correct plugin name
     */
    public function test_notification_plugin_name_is_translated(): void
    {
        CaptchaEnabledForm::where('form_key', 'dixlase-inquiry.inquiry_contact')->delete();

        $provider = new DixlaseInquiryDashboardProvider();
        $notifications = $provider->getNotifications();

        $this->assertNotEmpty($notifications[0]->pluginName);
    }
}
