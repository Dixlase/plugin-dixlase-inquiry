<?php

namespace Plugins\DixlaseInquiry\Tests\Unit;

use App\Contracts\RouteSlugProvider;
use App\DTO\RouteSlug\RegisteredSlug;
use Plugins\DixlaseInquiry\App\Providers\DixlaseInquiryServiceProvider;
use Tests\TestCase;

/**
 * DixlaseInquiry RouteSlugProvider ユニットテスト
 */
class DixlaseInquiryRouteSlugProviderTest extends TestCase
{
    /**
     * ServiceProvider が RouteSlugProvider を実装していることを検証
     */
    public function test_service_provider_implements_route_slug_provider(): void
    {
        $this->assertTrue(
            is_subclass_of(DixlaseInquiryServiceProvider::class, RouteSlugProvider::class)
        );
    }

    /**
     * getRouteSlugs が RegisteredSlug 配列を返すことを検証
     */
    public function test_get_route_slugs_returns_registered_slug_array(): void
    {
        $provider = $this->app->make(DixlaseInquiryServiceProvider::class, ['app' => $this->app]);
        $slugs = $provider->getRouteSlugs();

        $this->assertIsArray($slugs);
        $this->assertNotEmpty($slugs);
        $this->assertContainsOnlyInstancesOf(RegisteredSlug::class, $slugs);
    }

    /**
     * getRouteSlugs のスラッグが正しい owner を持つことを検証
     */
    public function test_get_route_slugs_has_correct_owner(): void
    {
        $provider = $this->app->make(DixlaseInquiryServiceProvider::class, ['app' => $this->app]);
        $slugs = $provider->getRouteSlugs();

        $slug = $slugs[0];
        $this->assertSame('dixlase-inquiry:inquiry_url_slug', $slug->owner);
    }

    /**
     * getRouteSlugs のスラッグが正しい翻訳ラベルを持つことを検証
     */
    public function test_get_route_slugs_has_correct_label(): void
    {
        $provider = $this->app->make(DixlaseInquiryServiceProvider::class, ['app' => $this->app]);
        $slugs = $provider->getRouteSlugs();

        $slug = $slugs[0];
        $this->assertSame('dixlase-inquiry::route-slug.owners.inquiry_url_slug', $slug->label);
    }

    /**
     * getRouteSlugs がデフォルト値 'inquiry' を返すことを検証
     */
    public function test_get_route_slugs_returns_default_slug(): void
    {
        $provider = $this->app->make(DixlaseInquiryServiceProvider::class, ['app' => $this->app]);
        $slugs = $provider->getRouteSlugs();

        $slug = $slugs[0];
        $this->assertSame('inquiry', $slug->slug);
    }

    /**
     * getRouteSlugs のスラッグが isReserved=false であることを検証
     */
    public function test_get_route_slugs_is_not_reserved(): void
    {
        $provider = $this->app->make(DixlaseInquiryServiceProvider::class, ['app' => $this->app]);
        $slugs = $provider->getRouteSlugs();

        $slug = $slugs[0];
        $this->assertFalse($slug->isReserved);
    }
}
