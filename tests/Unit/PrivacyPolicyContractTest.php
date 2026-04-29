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

use PHPUnit\Framework\TestCase;

class PrivacyPolicyContractTest extends TestCase
{
    /**
     * コアContractインターフェースが存在する
     */
    public function test_privacy_policy_provider_interface_exists(): void
    {
        $this->assertTrue(
            interface_exists(\App\Contracts\PluginIntegration\PrivacyPolicyProviderInterface::class)
        );
    }

    /**
     * インターフェースに必要なメソッドが定義されている
     */
    public function test_interface_has_required_methods(): void
    {
        $reflection = new \ReflectionClass(\App\Contracts\PluginIntegration\PrivacyPolicyProviderInterface::class);

        $this->assertTrue($reflection->hasMethod('getPrivacyPolicyUrl'));
        $this->assertTrue($reflection->hasMethod('isPrivacyPolicyEnabled'));
        $this->assertTrue($reflection->hasMethod('getPrivacyPolicyLabel'));
    }
}
