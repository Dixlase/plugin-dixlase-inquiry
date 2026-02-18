<?php

namespace Plugins\DixlaseInquiry\Tests\Unit;

use PHPUnit\Framework\TestCase;
use ReflectionMethod;

/**
 * prepareInquiryData / merge系メソッドのユニットテスト
 * コントローラーのprivateメソッドをリフレクションで直接テスト
 */
class PrepareInquiryDataTest extends TestCase
{
    /**
     * 日本式郵便番号の結合テスト（postal_code_1 + postal_code_2）
     */
    public function test_merge_postal_code_japanese(): void
    {
        $controller = $this->createController();
        $method = new ReflectionMethod($controller, 'mergePostalCode');
        $method->setAccessible(true);

        $result = $method->invoke($controller, [
            'postal_code_1' => '123',
            'postal_code_2' => '4567',
        ], false);

        $this->assertEquals('123-4567', $result);
    }

    /**
     * 欧米式郵便番号のパススルーテスト
     */
    public function test_merge_postal_code_western(): void
    {
        $controller = $this->createController();
        $method = new ReflectionMethod($controller, 'mergePostalCode');
        $method->setAccessible(true);

        $result = $method->invoke($controller, [
            'postal_code' => '90210',
        ], true);

        $this->assertEquals('90210', $result);
    }

    /**
     * 日本式郵便番号の片方だけの場合はnull（フォールバック）
     */
    public function test_merge_postal_code_japanese_partial(): void
    {
        $controller = $this->createController();
        $method = new ReflectionMethod($controller, 'mergePostalCode');
        $method->setAccessible(true);

        $result = $method->invoke($controller, [
            'postal_code_1' => '123',
        ], false);

        $this->assertNull($result);
    }

    /**
     * 日本式住所の結合テスト
     */
    public function test_merge_address_japanese(): void
    {
        $controller = $this->createController();
        $method = new ReflectionMethod($controller, 'mergeAddress');
        $method->setAccessible(true);

        $result = $method->invoke($controller, [
            'prefecture' => '東京都',
            'city' => '渋谷区',
            'address_line' => '神南1-2-3',
            'building' => 'ABCビル 5階',
        ], false);

        $this->assertEquals('東京都渋谷区神南1-2-3 ABCビル 5階', $result);
    }

    /**
     * 日本式住所で建物名なしの結合テスト
     */
    public function test_merge_address_japanese_without_building(): void
    {
        $controller = $this->createController();
        $method = new ReflectionMethod($controller, 'mergeAddress');
        $method->setAccessible(true);

        $result = $method->invoke($controller, [
            'prefecture' => '大阪府',
            'city' => '大阪市',
            'address_line' => '中央区1-1-1',
        ], false);

        $this->assertEquals('大阪府大阪市中央区1-1-1', $result);
    }

    /**
     * 欧米式住所の結合テスト
     */
    public function test_merge_address_western(): void
    {
        $controller = $this->createController();
        $method = new ReflectionMethod($controller, 'mergeAddress');
        $method->setAccessible(true);

        $result = $method->invoke($controller, [
            'street_address' => '123 Main Street',
            'building' => 'Apt 5',
            'city' => 'Los Angeles',
            'state' => 'California',
            'country' => 'United States',
        ], true);

        $this->assertEquals('123 Main Street, Apt 5, Los Angeles, California, United States', $result);
    }

    /**
     * 欧米式住所で一部フィールドがnullの場合
     */
    public function test_merge_address_western_partial(): void
    {
        $controller = $this->createController();
        $method = new ReflectionMethod($controller, 'mergeAddress');
        $method->setAccessible(true);

        $result = $method->invoke($controller, [
            'street_address' => '123 Main Street',
            'city' => 'New York',
        ], true);

        $this->assertEquals('123 Main Street, New York', $result);
    }

    /**
     * 日本式電話番号の結合テスト
     */
    public function test_merge_phone_japanese(): void
    {
        $controller = $this->createController();
        $method = new ReflectionMethod($controller, 'mergePhone');
        $method->setAccessible(true);

        $result = $method->invoke($controller, [
            'phone_1' => '090',
            'phone_2' => '1234',
            'phone_3' => '5678',
        ], false);

        $this->assertEquals('090-1234-5678', $result);
    }

    /**
     * 欧米式電話番号のパススルーテスト
     */
    public function test_merge_phone_western(): void
    {
        $controller = $this->createController();
        $method = new ReflectionMethod($controller, 'mergePhone');
        $method->setAccessible(true);

        $result = $method->invoke($controller, [
            'phone' => '+1-234-567-8900',
        ], true);

        $this->assertEquals('+1-234-567-8900', $result);
    }

    /**
     * 日本式電話番号の一部だけの場合はフォールバック
     */
    public function test_merge_phone_japanese_partial(): void
    {
        $controller = $this->createController();
        $method = new ReflectionMethod($controller, 'mergePhone');
        $method->setAccessible(true);

        $result = $method->invoke($controller, [
            'phone_1' => '090',
            'phone_2' => '1234',
        ], false);

        $this->assertNull($result);
    }

    /**
     * 日本式でカナ有効時のprepareInquiryDataでカナが結合される
     */
    public function test_prepare_inquiry_data_merges_kana_japanese(): void
    {
        $controller = $this->createController();
        $method = new ReflectionMethod($controller, 'prepareInquiryData');
        $method->setAccessible(true);

        $settings = (object) [
            'name_order_western' => false,
            'show_kana' => true,
            'show_postal_code' => false,
            'show_address' => false,
            'show_phone' => false,
            'show_gender' => false,
        ];

        $validated = [
            'last_name' => '山田',
            'first_name' => '太郎',
            'last_name_kana' => 'ヤマダ',
            'first_name_kana' => 'タロウ',
            'email' => 'test@example.com',
            'message' => 'テスト',
        ];

        $result = $method->invoke($controller, $validated, $settings);

        $this->assertEquals('ヤマダ タロウ', $result['name_kana']);
    }

    /**
     * 欧米式ではカナがnullになる
     */
    public function test_prepare_inquiry_data_kana_null_for_western(): void
    {
        $controller = $this->createController();
        $method = new ReflectionMethod($controller, 'prepareInquiryData');
        $method->setAccessible(true);

        $settings = (object) [
            'name_order_western' => true,
            'show_kana' => true,
            'show_postal_code' => false,
            'show_address' => false,
            'show_phone' => false,
            'show_gender' => false,
        ];

        $validated = [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'test@example.com',
            'message' => 'Test',
        ];

        $result = $method->invoke($controller, $validated, $settings);

        $this->assertNull($result['name_kana']);
    }

    /**
     * カナ無効時はname_kanaがnullになる
     */
    public function test_prepare_inquiry_data_kana_null_when_disabled(): void
    {
        $controller = $this->createController();
        $method = new ReflectionMethod($controller, 'prepareInquiryData');
        $method->setAccessible(true);

        $settings = (object) [
            'name_order_western' => false,
            'show_kana' => false,
            'show_postal_code' => false,
            'show_address' => false,
            'show_phone' => false,
            'show_gender' => false,
        ];

        $validated = [
            'last_name' => '山田',
            'first_name' => '太郎',
            'email' => 'test@example.com',
            'message' => 'テスト',
        ];

        $result = $method->invoke($controller, $validated, $settings);

        $this->assertNull($result['name_kana']);
    }

    /**
     * テスト用コントローラーインスタンスを作成
     */
    private function createController(): object
    {
        return new \Plugins\DixlaseInquiry\App\Http\Controllers\Front\DixlaseInquiryFrontController();
    }
}
