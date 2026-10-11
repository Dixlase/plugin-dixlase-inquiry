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

namespace Plugins\DixlaseInquiry\Tests\Unit;

use PHPUnit\Framework\TestCase;

class TranslationFileTest extends TestCase
{
    /** @var string */
    private string $langPath;

    protected function setUp(): void
    {
        parent::setUp();
        $this->langPath = __DIR__ . '/../../lang';
    }

    /**
     * admin-notification翻訳にdefault_subjectとdefault_bodyが含まれる
     */
    public function test_admin_notification_has_default_texts(): void
    {
        foreach (['en', 'ja'] as $locale) {
            $translations = require "{$this->langPath}/{$locale}/admin/inquiry/settings/admin-notification.php";
            $this->assertArrayHasKey('default_subject', $translations, "Missing default_subject in {$locale}");
            $this->assertArrayHasKey('default_body', $translations, "Missing default_body in {$locale}");
            $this->assertNotEmpty($translations['default_subject']);
            $this->assertNotEmpty($translations['default_body']);
        }
    }

    /**
     * auto-reply翻訳にdefault_subjectとdefault_bodyが含まれる
     */
    public function test_auto_reply_has_default_texts(): void
    {
        foreach (['en', 'ja'] as $locale) {
            $translations = require "{$this->langPath}/{$locale}/admin/inquiry/settings/auto-reply.php";
            $this->assertArrayHasKey('default_subject', $translations, "Missing default_subject in {$locale}");
            $this->assertArrayHasKey('default_body', $translations, "Missing default_body in {$locale}");
            $this->assertNotEmpty($translations['default_subject']);
            $this->assertNotEmpty($translations['default_body']);
        }
    }

    /**
     * index翻訳に埋め込み方法セクションのキーが含まれる
     */
    public function test_index_has_embedding_method_keys(): void
    {
        $embeddingKeys = [
            'embedding_methods',
            'usage_instruction_title',
            'usage_instruction_text',
            'blade_directive',
            'blade_directive_help',
            'shortcode',
            'shortcode_help',
        ];

        foreach (['en', 'ja'] as $locale) {
            $translations = require "{$this->langPath}/{$locale}/admin/inquiry/settings/index.php";
            foreach ($embeddingKeys as $key) {
                $this->assertArrayHasKey($key, $translations, "Missing {$key} in {$locale}/index.php");
            }
        }
    }

    /**
     * index翻訳に警告メッセージのキーが含まれる
     */
    public function test_index_has_warning_message_keys(): void
    {
        foreach (['en', 'ja'] as $locale) {
            $translations = require "{$this->langPath}/{$locale}/admin/inquiry/settings/index.php";
            $this->assertArrayHasKey('warning_admin_email', $translations, "Missing warning_admin_email in {$locale}");
            $this->assertArrayHasKey('warning_auto_reply_email', $translations, "Missing warning_auto_reply_email in {$locale}");
        }
    }

    /**
     * index翻訳からform_display関連のnavキーが削除されている
     */
    public function test_index_does_not_have_form_display_nav(): void
    {
        foreach (['en', 'ja'] as $locale) {
            $translations = require "{$this->langPath}/{$locale}/admin/inquiry/settings/index.php";
            $this->assertArrayNotHasKey('form_display', $translations['nav'] ?? []);
        }
    }

    /**
     * form-basic翻訳にform-displayから統合されたキーが含まれる
     */
    public function test_form_basic_has_merged_display_keys(): void
    {
        $displayKeys = [
            'form_type',
            'single_page',
            'separate_pages',
            'inquiry_url',
            'inquiry_url_slug_help',
            'lang',
            'lang_help',
        ];

        foreach (['en', 'ja'] as $locale) {
            $translations = require "{$this->langPath}/{$locale}/admin/inquiry/settings/form-basic.php";
            foreach ($displayKeys as $key) {
                $this->assertArrayHasKey($key, $translations, "Missing {$key} in {$locale}/form-basic.php");
            }
        }
    }

    /**
     * 削除されたform-preview翻訳ファイルが存在しない
     */
    public function test_form_preview_translation_files_deleted(): void
    {
        foreach (['en', 'ja'] as $locale) {
            $this->assertFileDoesNotExist(
                "{$this->langPath}/{$locale}/admin/inquiry/settings/form-preview.php"
            );
        }
    }

    /**
     * 削除されたform-display翻訳ファイルが存在しない
     */
    public function test_form_display_translation_files_deleted(): void
    {
        foreach (['en', 'ja'] as $locale) {
            $this->assertFileDoesNotExist(
                "{$this->langPath}/{$locale}/admin/inquiry/settings/form-display.php"
            );
        }
    }

    /**
     * ナビゲーション翻訳からform_previewとform_displayが削除されている
     */
    public function test_navigation_does_not_have_removed_keys(): void
    {
        foreach (['en', 'ja'] as $locale) {
            $translations = require "{$this->langPath}/{$locale}/admin/navigation.php";
            $this->assertArrayNotHasKey('form_preview', $translations['settings_nav'] ?? []);
            $this->assertArrayNotHasKey('form_display', $translations['settings_nav'] ?? []);
        }
    }

    /**
     * プライバシー設定の翻訳ファイルが en/ja 両方に存在し、主要キーを含む
     */
    public function test_privacy_translation_files_exist(): void
    {
        $requiredKeys = [
            'heading',
            'intro',
            'store_section',
            'store_label',
            'retention_section',
            'retention_mode_label',
            'retention_option_indefinite',
            'retention_option_days',
            'retention_option_custom',
            'settings_updated',
        ];

        foreach (['en', 'ja'] as $locale) {
            $file = "{$this->langPath}/{$locale}/admin/inquiry/settings/privacy.php";
            $this->assertFileExists($file, "Missing privacy translation: {$locale}");

            $translations = require $file;
            foreach ($requiredKeys as $key) {
                $this->assertArrayHasKey($key, $translations, "Missing {$key} in {$locale}/settings/privacy.php");
            }
        }
    }

    /**
     * ナビゲーション翻訳に privacy エントリが含まれる
     */
    public function test_navigation_has_privacy_key(): void
    {
        foreach (['en', 'ja'] as $locale) {
            $translations = require "{$this->langPath}/{$locale}/admin/navigation.php";
            $this->assertArrayHasKey(
                'privacy',
                $translations['settings_nav'] ?? [],
                "Missing settings_nav.privacy in {$locale}/navigation.php",
            );
        }
    }

    /**
     * 詳細画面の翻訳に retention 関連キーが含まれる
     */
    public function test_show_has_retention_keys(): void
    {
        $retentionKeys = [
            'retention_title',
            'retention_indefinite',
            'retention_days_remaining',
            'retention_option_indefinite',
            'retention_option_days',
            'retention_option_custom',
            'retention_update',
            'expires_at_updated',
        ];

        foreach (['en', 'ja'] as $locale) {
            $translations = require "{$this->langPath}/{$locale}/admin/inquiry/show.php";
            foreach ($retentionKeys as $key) {
                $this->assertArrayHasKey($key, $translations, "Missing {$key} in {$locale}/show.php");
            }
        }
    }

    /**
     * フロント翻訳がjaとenの両方で正しい構造を持つ
     */
    public function test_front_translations_have_correct_structure(): void
    {
        $requiredFormKeys = ['name', 'email', 'message', 'submit', 'first_name', 'last_name'];

        foreach (['en', 'ja'] as $locale) {
            $translations = require "{$this->langPath}/{$locale}/front.php";
            $this->assertArrayHasKey('form', $translations, "Missing 'form' key in {$locale}/front.php");
            foreach ($requiredFormKeys as $key) {
                $this->assertArrayHasKey($key, $translations['form'], "Missing form.{$key} in {$locale}/front.php");
            }
        }
    }
}
