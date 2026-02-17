<?php

namespace Plugins\DixlaseInquiry\Tests\Unit;

use PHPUnit\Framework\TestCase;

class MigrationTest extends TestCase
{
    /**
     * 問い合わせテーブルのマイグレーションファイルが存在する
     */
    public function test_inquiries_migration_file_exists(): void
    {
        $this->assertFileExists(
            __DIR__ . '/../../database/migrations/0001_01_01_000002_create_inquiries_table.php'
        );
    }

    /**
     * マイグレーションが正しいテーブル名を使用している
     */
    public function test_migration_uses_correct_table_name(): void
    {
        $content = file_get_contents(
            __DIR__ . '/../../database/migrations/0001_01_01_000002_create_inquiries_table.php'
        );

        $this->assertStringContainsString('plg_dixlase_inquiries', $content);
    }

    /**
     * マイグレーションに必要なカラムが含まれる
     */
    public function test_migration_contains_required_columns(): void
    {
        $content = file_get_contents(
            __DIR__ . '/../../database/migrations/0001_01_01_000002_create_inquiries_table.php'
        );

        $requiredColumns = [
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
            'form_locale',
            'privacy_agreed_at',
            'submitted_at',
            'read_at',
        ];

        foreach ($requiredColumns as $column) {
            $this->assertStringContainsString("'{$column}'", $content, "Column '{$column}' not found in migration");
        }
    }
}
