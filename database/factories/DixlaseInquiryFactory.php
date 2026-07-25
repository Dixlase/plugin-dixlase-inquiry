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

namespace Plugins\DixlaseInquiry\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Plugins\DixlaseInquiry\App\Enums\InquiryStatus;
use Plugins\DixlaseInquiry\App\Models\DixlaseInquiry;

/**
 * 問い合わせファクトリ
 *
 * @extends Factory<DixlaseInquiry>
 */
class DixlaseInquiryFactory extends Factory
{
    protected $model = DixlaseInquiry::class;

    /**
     * デフォルトの定義
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'status' => InquiryStatus::New,
            'name' => fake()->name(),
            'name_kana' => null,
            'email' => fake()->safeEmail(),
            'subject' => fake()->sentence(),
            'phone' => fake()->phoneNumber(),
            'postal_code' => fake()->postcode(),
            'address' => fake()->address(),
            'gender' => fake()->randomElement(['male', 'female', 'non_binary', 'other', 'prefer_not_to_say']),
            'message' => fake()->paragraphs(3, true),
            'ip_address' => fake()->ipv4(),
            'user_agent' => fake()->userAgent(),
            'lang' => 'ja',
            'privacy_agreed_at' => now(),
            'submitted_at' => now(),
            'read_at' => null,
        ];
    }

    /**
     * 未対応ステータス
     */
    public function statusNew(): static
    {
        return $this->state(fn (array $attributes): array => [
            'status' => InquiryStatus::New,
            'read_at' => null,
        ]);
    }

    /**
     * 対応中ステータス
     */
    public function inProgress(): static
    {
        return $this->state(fn (array $attributes): array => [
            'status' => InquiryStatus::InProgress,
            'read_at' => now(),
        ]);
    }

    /**
     * 完了ステータス
     */
    public function completed(): static
    {
        return $this->state(fn (array $attributes): array => [
            'status' => InquiryStatus::Completed,
            'read_at' => now(),
        ]);
    }

    /**
     * 未読状態
     */
    public function unread(): static
    {
        return $this->state(fn (array $attributes): array => [
            'read_at' => null,
        ]);
    }

    /**
     * プライバシー同意なし
     */
    public function withoutPrivacyConsent(): static
    {
        return $this->state(fn (array $attributes): array => [
            'privacy_agreed_at' => null,
        ]);
    }
}
