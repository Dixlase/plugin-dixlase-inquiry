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
use Plugins\DixlaseInquiry\App\Models\DixlaseInquiry;

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

        if (! $this->isCaptchaEnabledForInquiryForm()) {
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

        try {
            $unreadCount = DixlaseInquiry::unread()->count();
            if ($unreadCount > 0) {
                $notifications[] = new DashboardNotificationDTO(
                    key: 'inquiry_unread',
                    level: 'info',
                    message: __('dixlase-inquiry::dashboard.unread_inquiries', ['count' => $unreadCount]),
                    icon: 'fas fa-envelope',
                    pluginName: __('dixlase-inquiry::admin.plugin.name'),
                    url: route('dixlase-inquiry::admin.inquiry.index'),
                    actionLabel: __('dixlase-inquiry::dashboard.view_inquiries'),
                );
            }
        } catch (\Exception $e) {
            // DB not available or table missing — skip silently
        }

        return $notifications;
    }

    /**
     * Whether the captcha is currently enabled for the inquiry contact form.
     *
     * This is a **documented temporary direct access** to the core
     * `App\Models\CaptchaEnabledForm` model. The proper abstraction —
     * `CaptchaServiceInterface::isFormEnabled()` — is planned for Dixlase
     * v0.2 (see Core repo `.claude/plans/handoff-plugin-core-access-cleanup.md`,
     * "Outstanding core API gap"). Until that contract ships, this single
     * helper isolates the direct model access so the v0.2 retrofit is a
     * one-method change. The `captcha_enabled_forms` table is declared
     * under `plugin.json` `permissions.database.core_tables_read` and the
     * intent is captured in `_notes`.
     *
     * Returns true on read failure (e.g. table missing during install)
     * so the dashboard does not show a misleading "captcha not enabled"
     * warning before the captcha tables are migrated.
     */
    private function isCaptchaEnabledForInquiryForm(): bool
    {
        try {
            return CaptchaEnabledForm::isFormEnabled(self::CAPTCHA_FORM_KEY);
        } catch (\Throwable) {
            return true;
        }
    }
}
