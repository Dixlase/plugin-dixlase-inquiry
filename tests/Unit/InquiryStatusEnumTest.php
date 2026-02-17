<?php

namespace Plugins\DixlaseInquiry\Tests\Unit;

use PHPUnit\Framework\TestCase;
use Plugins\DixlaseInquiry\App\Enums\InquiryStatus;

class InquiryStatusEnumTest extends TestCase
{
    /**
     * 全ステータスケースが定義されている
     */
    public function test_all_status_cases_exist(): void
    {
        $cases = InquiryStatus::cases();

        $this->assertCount(3, $cases);
        $this->assertContains(InquiryStatus::New, $cases);
        $this->assertContains(InquiryStatus::InProgress, $cases);
        $this->assertContains(InquiryStatus::Completed, $cases);
    }

    /**
     * ステータスの値が正しい
     */
    public function test_status_values(): void
    {
        $this->assertEquals('new', InquiryStatus::New->value);
        $this->assertEquals('in_progress', InquiryStatus::InProgress->value);
        $this->assertEquals('completed', InquiryStatus::Completed->value);
    }

    /**
     * CSSクラスが空でない
     */
    public function test_css_class_returns_non_empty_string(): void
    {
        foreach (InquiryStatus::cases() as $status) {
            $this->assertNotEmpty($status->cssClass(), "cssClass() for {$status->value} is empty");
        }
    }

    /**
     * 文字列からステータスを生成できる
     */
    public function test_from_string(): void
    {
        $this->assertEquals(InquiryStatus::New, InquiryStatus::from('new'));
        $this->assertEquals(InquiryStatus::InProgress, InquiryStatus::from('in_progress'));
        $this->assertEquals(InquiryStatus::Completed, InquiryStatus::from('completed'));
    }

    /**
     * 不正な値でエラーが発生する
     */
    public function test_invalid_value_throws_error(): void
    {
        $this->expectException(\ValueError::class);
        InquiryStatus::from('invalid');
    }
}
