<?php

/**
 * This file is part of Dixlase Inquiry.
 *
 * Copyright (C) 2026 exc-D inc.
 * https://exc-d.com
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

class InquiryTranslationTest extends TestCase
{
    /**
     * 一覧ページ翻訳ファイルが両言語で存在する
     */
    public function test_index_translation_files_exist(): void
    {
        $this->assertFileExists(__DIR__ . '/../../lang/en/admin/inquiry/index.php');
        $this->assertFileExists(__DIR__ . '/../../lang/ja/admin/inquiry/index.php');
    }

    /**
     * 詳細ページ翻訳ファイルが両言語で存在する
     */
    public function test_show_translation_files_exist(): void
    {
        $this->assertFileExists(__DIR__ . '/../../lang/en/admin/inquiry/show.php');
        $this->assertFileExists(__DIR__ . '/../../lang/ja/admin/inquiry/show.php');
    }

    /**
     * ステータス翻訳ファイルが両言語で存在する
     */
    public function test_status_translation_files_exist(): void
    {
        $this->assertFileExists(__DIR__ . '/../../lang/en/admin/inquiry/status.php');
        $this->assertFileExists(__DIR__ . '/../../lang/ja/admin/inquiry/status.php');
    }

    /**
     * 一覧翻訳にheadingとdescriptionが含まれる
     */
    public function test_index_translations_have_heading_and_description(): void
    {
        foreach (['en', 'ja'] as $locale) {
            $trans = require __DIR__ . "/../../lang/{$locale}/admin/inquiry/index.php";
            $this->assertArrayHasKey('heading', $trans, "{$locale}: heading missing");
            $this->assertArrayHasKey('description', $trans, "{$locale}: description missing");
        }
    }

    /**
     * 詳細翻訳にheadingとdescriptionが含まれる
     */
    public function test_show_translations_have_heading_and_description(): void
    {
        foreach (['en', 'ja'] as $locale) {
            $trans = require __DIR__ . "/../../lang/{$locale}/admin/inquiry/show.php";
            $this->assertArrayHasKey('heading', $trans, "{$locale}: heading missing");
            $this->assertArrayHasKey('description', $trans, "{$locale}: description missing");
        }
    }

    /**
     * ステータス翻訳に全ステータスが含まれる
     */
    public function test_status_translations_have_all_statuses(): void
    {
        foreach (['en', 'ja'] as $locale) {
            $trans = require __DIR__ . "/../../lang/{$locale}/admin/inquiry/status.php";
            $this->assertArrayHasKey('new', $trans, "{$locale}: new missing");
            $this->assertArrayHasKey('in_progress', $trans, "{$locale}: in_progress missing");
            $this->assertArrayHasKey('completed', $trans, "{$locale}: completed missing");
        }
    }

    /**
     * フォーム設定翻訳にプライバシー設定キーが含まれる
     */
    public function test_form_basic_translations_have_privacy_keys(): void
    {
        foreach (['en', 'ja'] as $locale) {
            $trans = require __DIR__ . "/../../lang/{$locale}/admin/inquiry/settings/form-basic.php";
            $this->assertArrayHasKey('section_privacy', $trans, "{$locale}: section_privacy missing");
            $this->assertArrayHasKey('privacy_consent_enabled', $trans, "{$locale}: privacy_consent_enabled missing");
            $this->assertArrayHasKey('privacy_policy_url', $trans, "{$locale}: privacy_policy_url missing");
        }
    }

    /**
     * フォーム設定翻訳に送信間隔制限キーが含まれる
     */
    public function test_form_basic_translations_have_throttle_keys(): void
    {
        foreach (['en', 'ja'] as $locale) {
            $trans = require __DIR__ . "/../../lang/{$locale}/admin/inquiry/settings/form-basic.php";
            $this->assertArrayHasKey('section_throttle', $trans, "{$locale}: section_throttle missing");
            $this->assertArrayHasKey('throttle_enabled', $trans, "{$locale}: throttle_enabled missing");
            $this->assertArrayHasKey('throttle_max_attempts', $trans, "{$locale}: throttle_max_attempts missing");
        }
    }

    /**
     * フロント翻訳にprivacy_consentキーが含まれる
     */
    public function test_front_translations_have_privacy_consent_key(): void
    {
        foreach (['en', 'ja'] as $locale) {
            $trans = require __DIR__ . "/../../lang/{$locale}/front.php";
            $this->assertArrayHasKey('privacy_consent', $trans['form'], "{$locale}: form.privacy_consent missing");
        }
    }

    /**
     * フロント翻訳にprivacy_agreed_requiredバリデーションキーが含まれる
     */
    public function test_front_translations_have_privacy_validation_key(): void
    {
        foreach (['en', 'ja'] as $locale) {
            $trans = require __DIR__ . "/../../lang/{$locale}/front.php";
            $this->assertArrayHasKey('privacy_agreed_required', $trans['validation'], "{$locale}: validation.privacy_agreed_required missing");
        }
    }

    /**
     * 英語と日本語の翻訳キーが一致する（一覧）
     */
    public function test_index_translation_keys_match(): void
    {
        $en = require __DIR__ . '/../../lang/en/admin/inquiry/index.php';
        $ja = require __DIR__ . '/../../lang/ja/admin/inquiry/index.php';

        $this->assertEquals(
            array_keys($this->flattenArray($en)),
            array_keys($this->flattenArray($ja)),
            'EN and JA index translation keys do not match'
        );
    }

    /**
     * 英語と日本語の翻訳キーが一致する（詳細）
     */
    public function test_show_translation_keys_match(): void
    {
        $en = require __DIR__ . '/../../lang/en/admin/inquiry/show.php';
        $ja = require __DIR__ . '/../../lang/ja/admin/inquiry/show.php';

        $this->assertEquals(
            array_keys($this->flattenArray($en)),
            array_keys($this->flattenArray($ja)),
            'EN and JA show translation keys do not match'
        );
    }

    /**
     * 配列をフラット化するヘルパー
     *
     * @param array<string, mixed> $array
     * @return array<string, mixed>
     */
    private function flattenArray(array $array, string $prefix = ''): array
    {
        $result = [];
        foreach ($array as $key => $value) {
            $newKey = $prefix ? "{$prefix}.{$key}" : $key;
            if (is_array($value)) {
                $result = array_merge($result, $this->flattenArray($value, $newKey));
            } else {
                $result[$newKey] = $value;
            }
        }
        return $result;
    }
}
