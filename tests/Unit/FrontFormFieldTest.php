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

/**
 * フロントフォームの分割フィールドと翻訳キーのテスト
 */
class FrontFormFieldTest extends TestCase
{
    /** @var string */
    private string $langPath;

    protected function setUp(): void
    {
        parent::setUp();
        $this->langPath = __DIR__ . '/../../lang';
    }

    /**
     * 日本式分割フィールド用の翻訳キーが両言語に存在する
     */
    public function test_split_field_translation_keys_exist(): void
    {
        $splitFieldKeys = [
            'form.postal_code_1_placeholder',
            'form.postal_code_2_placeholder',
            'form.phone_1_placeholder',
            'form.phone_2_placeholder',
            'form.phone_3_placeholder',
            'form.prefecture_placeholder',
            'form.building',
        ];

        foreach (['en', 'ja'] as $locale) {
            $translations = require "{$this->langPath}/{$locale}/front.php";
            foreach ($splitFieldKeys as $key) {
                $parts = explode('.', $key);
                $value = $translations;
                foreach ($parts as $part) {
                    $this->assertArrayHasKey($part, $value, "Missing {$key} in {$locale}/front.php");
                    $value = $value[$part];
                }
                $this->assertNotEmpty($value, "Empty value for {$key} in {$locale}/front.php");
            }
        }
    }

    /**
     * 日本式分割フィールドのバリデーションメッセージキーが両言語に存在する
     */
    public function test_split_field_validation_keys_exist(): void
    {
        $validationKeys = [
            'postal_code_1_required',
            'postal_code_1_digits',
            'postal_code_2_required',
            'postal_code_2_digits',
            'prefecture_required',
            'address_line_required',
            'phone_1_required',
            'phone_1_format',
            'phone_2_required',
            'phone_2_format',
            'phone_3_required',
            'phone_3_format',
        ];

        foreach (['en', 'ja'] as $locale) {
            $translations = require "{$this->langPath}/{$locale}/front.php";
            foreach ($validationKeys as $key) {
                $this->assertArrayHasKey($key, $translations['validation'], "Missing validation.{$key} in {$locale}/front.php");
                $this->assertNotEmpty($translations['validation'][$key], "Empty validation.{$key} in {$locale}/front.php");
            }
        }
    }

    /**
     * 日本語翻訳に都道府県配列が47件存在する
     */
    public function test_ja_prefectures_array_has_47_entries(): void
    {
        $translations = require "{$this->langPath}/ja/front.php";
        $this->assertArrayHasKey('prefectures', $translations, 'Missing prefectures key in ja/front.php');
        $this->assertIsArray($translations['prefectures']);
        $this->assertCount(47, $translations['prefectures'], 'Prefectures array should have exactly 47 entries');
    }

    /**
     * 性別オプションの翻訳キーが両言語に存在する
     */
    public function test_gender_translation_keys_exist(): void
    {
        $genderKeys = [
            'gender_male',
            'gender_female',
            'gender_other',
            'gender_prefer_not_to_say',
        ];

        foreach (['en', 'ja'] as $locale) {
            $translations = require "{$this->langPath}/{$locale}/front.php";
            foreach ($genderKeys as $key) {
                $this->assertArrayHasKey($key, $translations['form'], "Missing form.{$key} in {$locale}/front.php");
                $this->assertNotEmpty($translations['form'][$key], "Empty form.{$key} in {$locale}/front.php");
            }
        }
    }

    /**
     * 日本語と英語の翻訳ファイルが同じ構造キーを持つ
     */
    public function test_translation_files_have_consistent_structure(): void
    {
        $jaTranslations = require "{$this->langPath}/ja/front.php";
        $enTranslations = require "{$this->langPath}/en/front.php";

        // トップレベルキーの一致（prefecturesは日本語のみなので除外）
        $jaKeys = array_diff(array_keys($jaTranslations), ['prefectures']);
        $enKeys = array_keys($enTranslations);
        sort($jaKeys);
        sort($enKeys);
        $this->assertEquals(array_values($jaKeys), array_values($enKeys), 'Top-level keys should match between ja and en (excluding prefectures)');

        // form配下のキーの一致
        $jaFormKeys = array_keys($jaTranslations['form']);
        $enFormKeys = array_keys($enTranslations['form']);
        sort($jaFormKeys);
        sort($enFormKeys);
        $this->assertEquals($jaFormKeys, $enFormKeys, 'form.* keys should match between ja and en');

        // validation配下のキーの一致
        $jaValidationKeys = array_keys($jaTranslations['validation']);
        $enValidationKeys = array_keys($enTranslations['validation']);
        sort($jaValidationKeys);
        sort($enValidationKeys);
        $this->assertEquals($jaValidationKeys, $enValidationKeys, 'validation.* keys should match between ja and en');
    }

    /**
     * カタカナ（フリガナ）関連の翻訳キーが両言語に存在する
     */
    public function test_kana_translation_keys_exist(): void
    {
        $kanaFormKeys = [
            'kana',
            'last_name_kana',
            'first_name_kana',
            'last_name_kana_placeholder',
            'first_name_kana_placeholder',
        ];

        $kanaValidationKeys = [
            'last_name_kana_required',
            'first_name_kana_required',
            'last_name_kana_katakana',
            'first_name_kana_katakana',
        ];

        foreach (['en', 'ja'] as $locale) {
            $translations = require "{$this->langPath}/{$locale}/front.php";

            foreach ($kanaFormKeys as $key) {
                $this->assertArrayHasKey($key, $translations['form'], "Missing form.{$key} in {$locale}/front.php");
                $this->assertNotEmpty($translations['form'][$key], "Empty form.{$key} in {$locale}/front.php");
            }

            foreach ($kanaValidationKeys as $key) {
                $this->assertArrayHasKey($key, $translations['validation'], "Missing validation.{$key} in {$locale}/front.php");
                $this->assertNotEmpty($translations['validation'][$key], "Empty validation.{$key} in {$locale}/front.php");
            }
        }
    }

    /**
     * 住所関連の翻訳キーが両言語に存在する
     */
    public function test_address_field_translation_keys_exist(): void
    {
        $addressKeys = [
            'form.address',
            'form.street_address',
            'form.prefecture',
            'form.city',
            'form.address_line',
            'form.building',
            'form.state',
            'form.country',
        ];

        foreach (['en', 'ja'] as $locale) {
            $translations = require "{$this->langPath}/{$locale}/front.php";
            foreach ($addressKeys as $key) {
                $parts = explode('.', $key);
                $value = $translations;
                foreach ($parts as $part) {
                    $this->assertArrayHasKey($part, $value, "Missing {$key} in {$locale}/front.php");
                    $value = $value[$part];
                }
            }
        }
    }
}
