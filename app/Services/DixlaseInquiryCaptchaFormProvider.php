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

namespace Plugins\DixlaseInquiry\App\Services;

use App\Contracts\PluginIntegration\CaptchaFormProviderInterface;
use App\DTO\PluginIntegration\CaptchaFormDTO;

/**
 * CAPTCHA form provider for the Inquiry plugin.
 *
 * Declares the inquiry contact form as a CAPTCHA-eligible target.
 * The core CaptchaService aggregates this via the "captcha" capability.
 */
class DixlaseInquiryCaptchaFormProvider implements CaptchaFormProviderInterface
{
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
    public function getCaptchaForms(): array
    {
        return [
            new CaptchaFormDTO(
                key: 'inquiry_contact',
                name: 'dixlase-inquiry::captcha.forms.inquiry_contact',
                route: 'inquiry.send',
                category: 'contact',
                defaultEnabled: true,
                priority: 200,
            ),
        ];
    }
}
