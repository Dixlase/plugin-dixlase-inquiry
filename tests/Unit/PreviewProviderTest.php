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

use App\DTO\PluginIntegration\PreviewDTO;
use App\DTO\PluginIntegration\PreviewFieldDTO;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Schema;
use Plugins\DixlaseInquiry\App\Models\DixlaseInquirySetting;
use Plugins\DixlaseInquiry\App\Services\DixlaseInquiryPreviewProvider;
use Plugins\DixlaseInquiry\App\Support\InquiryLocaleSupport;
use Tests\TestCase;

class PreviewProviderTest extends TestCase
{
    private DixlaseInquiryPreviewProvider $provider;

    protected function setUp(): void
    {
        parent::setUp();

        // テスト用に設定テーブルを作成
        if (! Schema::hasTable('plg_dixlase_inquiry_settings')) {
            Schema::create('plg_dixlase_inquiry_settings', function ($table) {
                $table->id();
                $table->string('name')->unique();
                $table->text('value')->nullable();
                $table->timestamps();
            });
        }

        // プラグイン翻訳ファイルをロード
        $langPath = dirname(__DIR__, 2).'/lang';
        Lang::addNamespace('dixlase-inquiry', $langPath);

        $this->provider = new DixlaseInquiryPreviewProvider();
    }

    protected function tearDown(): void
    {
        // Remove only the rows this test wrote; keep the table. It is
        // created by the plugin's own migration and shared across the
        // whole test run, so dropping it here would break sibling
        // suites — most visibly on a persistent MySQL test database.
        if (Schema::hasTable('plg_dixlase_inquiry_settings')) {
            \Illuminate\Support\Facades\DB::table('plg_dixlase_inquiry_settings')->delete();
        }

        parent::tearDown();
    }

    /**
     * lang設定が 'en' の場合、英語ラベルが使用される
     */
    public function test_labels_use_english_when_lang_is_en(): void
    {
        DixlaseInquirySetting::set('lang', 'en');

        $preview = $this->provider->getPreview('inquiry_form');

        $this->assertInstanceOf(PreviewDTO::class, $preview);

        $emailField = $this->findField($preview, 'email');
        $this->assertNotNull($emailField);
        $this->assertSame('Email Address', $emailField->label);
    }

    /**
     * lang設定が 'ja' の場合、日本語ラベルが使用される
     */
    public function test_labels_use_japanese_when_lang_is_ja(): void
    {
        DixlaseInquirySetting::set('lang', 'ja');

        $preview = $this->provider->getPreview('inquiry_form');

        $this->assertInstanceOf(PreviewDTO::class, $preview);

        $emailField = $this->findField($preview, 'email');
        $this->assertNotNull($emailField);
        $this->assertSame('メールアドレス', $emailField->label);
    }

    /**
     * lang設定が 'auto' の場合、プレビューのラベルは InquiryLocaleSupport が
     * 解決したロケールに追従する（多言語無効時はサイト既定ロケール）。
     */
    public function test_labels_follow_resolved_locale_when_lang_is_auto(): void
    {
        DixlaseInquirySetting::set('lang', 'auto');

        // The provider resolves 'auto' through InquiryLocaleSupport; the
        // labels must follow whatever locale that yields, not blindly
        // app()->getLocale(). The resolver's own contract — 'auto' uses
        // the site default when multilingual is off — is covered by
        // InquiryLocaleSupportTest.
        $resolvedLocale = InquiryLocaleSupport::resolveFormLocale('auto');

        $preview = $this->provider->getPreview('inquiry_form');

        $emailField = $this->findField($preview, 'email');
        $this->assertNotNull($emailField);
        $this->assertSame(
            __('dixlase-inquiry::front.form.email', [], $resolvedLocale),
            $emailField->label,
        );
    }

    /**
     * lang設定による翻訳がプレビュータイトルにも適用される
     */
    public function test_preview_title_respects_form_heading_setting(): void
    {
        DixlaseInquirySetting::set('form_heading', 'カスタム見出し');

        $preview = $this->provider->getPreview('inquiry_form');

        $this->assertSame('カスタム見出し', $preview->title);
    }

    /**
     * form_headingが空の場合、タイトルが空文字列になる
     */
    public function test_preview_title_empty_when_form_heading_not_set(): void
    {
        $preview = $this->provider->getPreview('inquiry_form');

        $this->assertSame('', $preview->title);
    }

    /**
     * privacy_consent_enabled が true の場合、checkbox フィールドが追加される
     */
    public function test_privacy_consent_checkbox_added_when_enabled(): void
    {
        DixlaseInquirySetting::set('privacy_consent_enabled', '1');
        DixlaseInquirySetting::set('privacy_policy_url', 'https://example.com/privacy');

        $preview = $this->provider->getPreview('inquiry_form');

        $checkbox = $this->findField($preview, 'privacy_agreed');
        $this->assertNotNull($checkbox, 'privacy_agreed フィールドが存在すること');
        $this->assertSame('checkbox', $checkbox->type);
        $this->assertTrue($checkbox->required);
        $this->assertSame('https://example.com/privacy', $checkbox->meta['privacy_policy_url']);
    }

    /**
     * privacy_consent_enabled が false の場合、checkbox フィールドが追加されない
     */
    public function test_privacy_consent_checkbox_not_added_when_disabled(): void
    {
        DixlaseInquirySetting::set('privacy_consent_enabled', '0');

        $preview = $this->provider->getPreview('inquiry_form');

        $checkbox = $this->findField($preview, 'privacy_agreed');
        $this->assertNull($checkbox, 'privacy_agreed フィールドが存在しないこと');
    }

    /**
     * カスタムテキストが設定されている場合、ラベルに使用される
     */
    public function test_privacy_consent_uses_custom_text_when_set(): void
    {
        DixlaseInquirySetting::set('privacy_consent_enabled', '1');
        DixlaseInquirySetting::set('privacy_consent_text', 'カスタム同意文');
        DixlaseInquirySetting::set('privacy_policy_url', 'https://example.com/privacy');

        $preview = $this->provider->getPreview('inquiry_form');

        $checkbox = $this->findField($preview, 'privacy_agreed');
        $this->assertSame('カスタム同意文', $checkbox->label);
    }

    /**
     * privacy_agreed フィールドが fields の末尾（message の後）に配置される
     */
    public function test_privacy_consent_is_last_field(): void
    {
        DixlaseInquirySetting::set('privacy_consent_enabled', '1');
        DixlaseInquirySetting::set('privacy_policy_url', 'https://example.com/privacy');

        $preview = $this->provider->getPreview('inquiry_form');

        $fields = $preview->fields;
        $lastField = end($fields);
        $this->assertSame('privacy_agreed', $lastField->name);
    }

    /**
     * PreviewFieldDTO からフィールドを名前で検索する
     */
    private function findField(PreviewDTO $preview, string $name): ?PreviewFieldDTO
    {
        foreach ($preview->fields as $field) {
            if ($field->name === $name) {
                return $field;
            }
        }

        return null;
    }
}
