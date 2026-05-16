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

use App\Traits\TranslatableTrait;
use Illuminate\Database\Eloquent\Model;

/**
 * Singleton aggregate that wraps the six translatable entries of the
 * key-value `plg_dixlase_inquiry_settings` table so that DixlaseMultilingual's
 * central translation manager can treat them as a single translatable entity.
 *
 * The aggregate's own row (`plg_dixlase_inquiry_settings_aggregate.id = 1`)
 * carries no business columns — it exists only to give the polymorphic
 * `plg_dixlase_multilingual_translations` table a stable
 * `(translatable_type, translatable_id)` pair to anchor against.
 *
 * Reading a translatable attribute (e.g. `$aggregate->form_heading` or
 * `$aggregate->getTranslation('form_heading')`) goes through Core's
 * {@see TranslatableTrait}, which asks DixlaseMultilingual's
 * `TranslationResolver` for the current-locale value and falls back to
 * {@see self::getOriginalValue()} — overridden here to read the
 * primary-locale value out of the existing inquiry settings table.
 *
 * On single-language sites (no DixlaseMultilingual installed) the trait
 * has no resolver to call and falls back directly to the primary value,
 * so the inquiry plugin keeps working unchanged.
 *
 * @property-read string|null $form_heading       Translated form heading.
 * @property-read string|null $form_description   Translated form description.
 * @property-read string|null $completion_title   Translated completion-page title.
 * @property-read string|null $completion_message Translated completion-page message.
 * @property-read string|null $auto_reply_subject Translated auto-reply email subject.
 * @property-read string|null $auto_reply_body    Translated auto-reply email body template.
 */
class DixlaseInquirySettingsAggregate extends Model
{
    use TranslatableTrait;

    protected $table = 'plg_dixlase_inquiry_settings_aggregate';

    /**
     * Allow `id` to be mass-assigned so the seeder's idempotent
     * `firstOrCreate(['id' => 1])` works. Safe because this model has
     * exactly one row (id=1) and no business columns to protect.
     *
     * @var list<string>
     */
    protected $guarded = [];

    /**
     * Fields surfaced to DixlaseMultilingual's central translation editor.
     * Must match the `multilingual_content.types[].fields[].name` list in
     * plugin.json and the lookup keys used by `dls_inquiry_localized_setting()`.
     *
     * Admin-notification subject/body are intentionally excluded — the
     * installer seeds them in the site's primary locale and they are
     * edited only once. See `.backlog/inquiry-translatable-settings-design.md`
     * (Core repo) §1 for the scope rationale.
     *
     * @var list<string>
     */
    protected array $translatable = [
        'form_heading',
        'form_description',
        'completion_title',
        'completion_message',
        'auto_reply_subject',
        'auto_reply_body',
    ];

    /**
     * The aggregate row stores no business columns of its own; the
     * primary-locale value for each translatable field lives in the
     * key-value `plg_dixlase_inquiry_settings` table. Override the
     * TranslatableTrait default (which reads `$this->attributes[$field]`)
     * to delegate to the settings model so the trait's fallback returns
     * the right primary value when no translation exists for the
     * requested locale.
     */
    protected function getOriginalValue(string $field): mixed
    {
        return DixlaseInquirySetting::get($field);
    }
}
