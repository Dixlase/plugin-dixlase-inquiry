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

namespace Plugins\DixlaseInquiry\App\Http\Requests\Admin;

/**
 * The form-basic settings as the preview flow may supply them.
 *
 * The preview exists so an administrator can see the public form the
 * settings on that page produce, before saving them. It therefore accepts
 * the fields that page saves, validated the way saving them is validated:
 * the same rules, the same messages, one place to change them.
 */
class DixlaseInquiryFormBasicPreviewRequest extends DixlaseInquiryFormBasicRequest
{
    /**
     * Normalize the booleans the preview button sends.
     *
     * The button builds its request from the page's Alpine state rather
     * than from form controls, so a boolean arrives as the string "true"
     * or "false". The parent casts with `(bool)`, which is right for the
     * save path's "1" / "0" / "on" / absent, but `(bool) "false"` is
     * true -- so reusing it here would turn every switched-off toggle on
     * in the preview. FILTER_VALIDATE_BOOLEAN reads both spellings, and
     * an absent field is false, as on the save path.
     */
    protected function prepareForValidation(): void
    {
        $data = [];

        foreach (parent::BOOLEAN_FIELDS as $field) {
            $data[$field] = $this->has($field)
                ? filter_var($this->input($field), FILTER_VALIDATE_BOOLEAN)
                : false;
        }

        $this->merge($data);
    }
}
