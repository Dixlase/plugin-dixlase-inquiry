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

namespace Plugins\DixlaseInquiry\App\Services;

use App\Contracts\PluginIntegration\DashboardNotificationProviderInterface;
use App\DTO\PluginIntegration\DashboardNotificationDTO;
use App\Models\CaptchaEnabledForm;

/**
 * Dashboard notification provider for the Inquiry plugin.
 *
 * Shows a recommendation notification when CAPTCHA is not enabled
 * for the inquiry contact form.
 */
class DixlaseInquiryDashboardProvider implements DashboardNotificationProviderInterface
{
    /** CAPTCHA form key for the inquiry contact form */
    private const CAPTCHA_FORM_KEY = 'dixlase-inquiry.inquiry_contact';

    /**
     * {@inheritDoc}
     */
    public function getPluginSlug(): string
    {
        return 'dixlase-inquiry';
    }

    /**
     * {@inheritDoc}
     */
    public function isCapabilityAvailable(): bool
    {
        return true;
    }

    /**
     * {@inheritDoc}
     */
    public function getNotifications(): array
    {
        $notifications = [];

        try {
            if (! CaptchaEnabledForm::isFormEnabled(self::CAPTCHA_FORM_KEY)) {
                $notifications[] = new DashboardNotificationDTO(
                    key: 'inquiry_captcha_off',
                    level: 'recommendation',
                    message: __('dixlase-inquiry::dashboard.captcha_not_enabled'),
                    icon: 'fas fa-robot',
                    pluginName: __('dixlase-inquiry::admin.plugin.name'),
                    url: route('admin.settings.security.captcha'),
                    actionLabel: __('dixlase-inquiry::dashboard.configure_captcha'),
                );
            }
        } catch (\Exception $e) {
            // DB not available or table missing — skip silently
        }

        return $notifications;
    }
}
