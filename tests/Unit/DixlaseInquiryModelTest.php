<?php

namespace Plugins\DixlaseInquiry\Tests\Unit;

use PHPUnit\Framework\TestCase;
use Plugins\DixlaseInquiry\App\Models\DixlaseInquiry;

class DixlaseInquiryModelTest extends TestCase
{
    /**
     * テーブル名が正しい
     */
    public function test_table_name(): void
    {
        $model = new DixlaseInquiry();
        $this->assertEquals('plg_dixlase_inquiries', $model->getTable());
    }

    /**
     * fillableが正しく設定されている
     */
    public function test_fillable_contains_all_fields(): void
    {
        $model = new DixlaseInquiry();
        $fillable = $model->getFillable();

        $expectedFields = [
            'status',
            'name',
            'email',
            'subject',
            'phone',
            'postal_code',
            'address',
            'gender',
            'message',
            'ip_address',
            'user_agent',
            'lang',
            'privacy_agreed_at',
            'submitted_at',
            'read_at',
        ];

        foreach ($expectedFields as $field) {
            $this->assertContains($field, $fillable, "Field '{$field}' not found in fillable");
        }
    }

    /**
     * castsにdatetimeフィールドが含まれる
     */
    public function test_casts_contain_datetime_fields(): void
    {
        $model = new DixlaseInquiry();
        $casts = $model->getCasts();

        $this->assertArrayHasKey('submitted_at', $casts);
        $this->assertArrayHasKey('read_at', $casts);
        $this->assertArrayHasKey('privacy_agreed_at', $casts);
        $this->assertArrayHasKey('status', $casts);
    }

    /**
     * ファクトリが存在する
     */
    public function test_factory_class_exists(): void
    {
        $this->assertTrue(
            class_exists(\Plugins\DixlaseInquiry\Database\Factories\DixlaseInquiryFactory::class)
        );
    }
}
