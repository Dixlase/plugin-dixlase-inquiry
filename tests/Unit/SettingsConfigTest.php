<?php

namespace Plugins\DixlaseInquiry\Tests\Unit;

use PHPUnit\Framework\TestCase;

class SettingsConfigTest extends TestCase
{
    /**
     * form_localeがデフォルト設定に含まれる
     */
    public function test_default_settings_include_form_locale(): void
    {
        $defaults = \Plugins\DixlaseInquiry\App\Models\DixlaseInquirySetting::getDefaultSettings();

        $this->assertObjectHasProperty('form_locale', $defaults);
        $this->assertEquals('ja', $defaults->form_locale);
    }

    /**
     * デフォルト設定にuse_recaptchaが含まれない
     */
    public function test_default_settings_do_not_include_use_recaptcha(): void
    {
        $defaults = \Plugins\DixlaseInquiry\App\Models\DixlaseInquirySetting::getDefaultSettings();

        $this->assertObjectNotHasProperty('use_recaptcha', $defaults);
    }

    /**
     * デフォルト設定に必要な全キーが存在する
     */
    public function test_default_settings_contain_all_required_keys(): void
    {
        $defaults = \Plugins\DixlaseInquiry\App\Models\DixlaseInquirySetting::getDefaultSettings();

        $requiredKeys = [
            'admin_email',
            'subject',
            'body',
            'show_phone',
            'phone_required',
            'show_address',
            'address_required',
            'show_subject',
            'subject_required',
            'show_postal_code',
            'postal_code_required',
            'show_gender',
            'gender_required',
            'auto_reply_enabled',
            'auto_reply_from_email',
            'auto_reply_subject',
            'auto_reply_body',
            'use_single_page',
            'show_confirmation_page',
            'inquiry_url_slug',
            'name_order_western',
            'form_locale',
        ];

        foreach ($requiredKeys as $key) {
            $this->assertObjectHasProperty($key, $defaults, "Missing key: {$key}");
        }
    }

    /**
     * config/inquiry.phpが正しい構造を持つ
     */
    public function test_inquiry_config_file_has_correct_structure(): void
    {
        $config = require __DIR__ . '/../../config/inquiry.php';

        $this->assertIsArray($config);
        $this->assertArrayHasKey('locales', $config);
        $this->assertIsArray($config['locales']);
        $this->assertContains('ja', $config['locales']);
        $this->assertContains('en', $config['locales']);
    }

    /**
     * シーダーが正しいモデルクラスを参照している
     */
    public function test_seeder_uses_correct_model_class(): void
    {
        $seederFile = file_get_contents(__DIR__ . '/../../database/seeders/InquirySettingsSeeder.php');

        $this->assertStringContainsString(
            'use Plugins\DixlaseInquiry\App\Models\DixlaseInquirySetting;',
            $seederFile
        );
        $this->assertStringNotContainsString(
            'use Plugins\DixlaseInquiry\App\Models\InquirySetting;',
            $seederFile
        );
    }

    /**
     * シーダーが個別の翻訳ファイルを参照している（admin.phpではなく）
     */
    public function test_seeder_references_individual_translation_files(): void
    {
        $seederFile = file_get_contents(__DIR__ . '/../../database/seeders/InquirySettingsSeeder.php');

        $this->assertStringContainsString('admin-notification.php', $seederFile);
        $this->assertStringContainsString('auto-reply.php', $seederFile);
        $this->assertStringNotContainsString("require \"{$seederFile}/admin.php\"", $seederFile);
    }

    /**
     * シーダーにform_localeが含まれる
     */
    public function test_seeder_includes_form_locale(): void
    {
        $seederFile = file_get_contents(__DIR__ . '/../../database/seeders/InquirySettingsSeeder.php');

        $this->assertStringContainsString("'form_locale'", $seederFile);
    }

    /**
     * シーダーにuse_recaptchaが含まれない
     */
    public function test_seeder_does_not_include_use_recaptcha(): void
    {
        $seederFile = file_get_contents(__DIR__ . '/../../database/seeders/InquirySettingsSeeder.php');

        $this->assertStringNotContainsString("'use_recaptcha'", $seederFile);
    }
}
