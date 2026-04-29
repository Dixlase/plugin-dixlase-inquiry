<?php

/**
 * This file is part of Dixlase Inquiry.
 *
 * Copyright (C) 2026 exc-D inc.
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

namespace Plugins\DixlaseInquiry\App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Plugins\DixlaseInquiry\App\Enums\InquiryStatus;
use Plugins\DixlaseInquiry\Database\Factories\DixlaseInquiryFactory;

/**
 * 問い合わせモデル
 *
 * @property int $id
 * @property InquiryStatus $status
 * @property string $name
 * @property string|null $name_kana
 * @property string $email
 * @property string|null $subject
 * @property string|null $phone
 * @property string|null $postal_code
 * @property string|null $address
 * @property string|null $gender
 * @property string $message
 * @property string|null $ip_address
 * @property string|null $user_agent
 * @property string $lang 言語コード
 * @property \Illuminate\Support\Carbon|null $privacy_agreed_at
 * @property \Illuminate\Support\Carbon $submitted_at
 * @property \Illuminate\Support\Carbon|null $read_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
class DixlaseInquiry extends Model
{
    use HasFactory;

    protected $table = 'plg_dixlase_inquiries';

    protected $fillable = [
        'status',
        'name',
        'name_kana',
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

    /**
     * キャスト定義
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'status' => InquiryStatus::class,
            'submitted_at' => 'datetime',
            'read_at' => 'datetime',
            'privacy_agreed_at' => 'datetime',
        ];
    }

    /**
     * ファクトリの取得
     */
    protected static function newFactory(): DixlaseInquiryFactory
    {
        return DixlaseInquiryFactory::new();
    }

    /**
     * 未対応の問い合わせを取得
     */
    public function scopeNew(Builder $query): Builder
    {
        return $query->where('status', InquiryStatus::New);
    }

    /**
     * 既読（対応中）の問い合わせを取得
     */
    public function scopeInProgress(Builder $query): Builder
    {
        return $query->where('status', InquiryStatus::InProgress);
    }

    /**
     * 完了した問い合わせを取得
     */
    public function scopeCompleted(Builder $query): Builder
    {
        return $query->where('status', InquiryStatus::Completed);
    }

    /**
     * 未読の問い合わせを取得
     */
    public function scopeUnread(Builder $query): Builder
    {
        return $query->whereNull('read_at');
    }

    /**
     * 指定言語の問い合わせを取得
     */
    public function scopeForLang(Builder $query, string $lang): Builder
    {
        return $query->where('lang', $lang);
    }

    /**
     * 既読としてマーク
     */
    public function markAsRead(): void
    {
        if ($this->read_at === null) {
            $this->update(['read_at' => now()]);
        }
    }
}
