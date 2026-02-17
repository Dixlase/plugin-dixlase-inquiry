<?php

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
