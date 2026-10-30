<?php

/**
 * This file is part of DixlaseInquiry.
 *
 * Copyright (C) 2026 exc-D inc. and Dixlase contributors
 * https://exc-d.com
 *
 * DixlaseInquiry is dual-licensed. You may use this file under either:
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

namespace Plugins\DixlaseInquiry\Tests\Feature\Admin;

use Illuminate\Support\Facades\Validator;
use Plugins\DixlaseInquiry\App\Http\Requests\Admin\DixlaseInquiryFormBasicRequest;
use Tests\TestCase;

/**
 * The submission-limit fields accept a cleared value.
 *
 * Core restores Laravel's ConvertEmptyStringsToNull in its global
 * middleware stack (dixlase-core#642), so a field the operator cleared
 * reaches the request as null instead of ''. Laravel skips non-implicit
 * rules for '' but not for null, so `integer` without `nullable` refused
 * a cleared field even with the limit switched off, and the basic
 * settings could not be saved at all (#82).
 *
 * Both shapes are asserted, because a site runs one core or the other:
 * '' on a core before that change, null on one after it.
 */
class FormBasicThrottleFieldsTest extends TestCase
{
    /**
     * The fields the screen always submits, so each case below differs
     * only in the two values under test.
     *
     * @return array<string, mixed>
     */
    private function baseData(): array
    {
        return [
            'lang' => 'auto',
            'name_order_western' => 'auto',
        ];
    }

    /**
     * @param  array<string, mixed>  $data
     */
    private function validate(array $data): \Illuminate\Validation\Validator
    {
        return Validator::make($data, (new DixlaseInquiryFormBasicRequest)->rules());
    }

    public function test_cleared_fields_pass_when_the_limit_is_off_and_they_arrive_as_null(): void
    {
        $validator = $this->validate($this->baseData() + [
            'throttle_enabled' => false,
            'throttle_max_attempts' => null,
            'throttle_decay_minutes' => null,
        ]);

        $this->assertFalse(
            $validator->fails(),
            'Cleared submission-limit fields were refused: '.$validator->errors()->toJson()
        );
    }

    public function test_cleared_fields_pass_when_the_limit_is_off_and_they_arrive_as_empty_strings(): void
    {
        $validator = $this->validate($this->baseData() + [
            'throttle_enabled' => false,
            'throttle_max_attempts' => '',
            'throttle_decay_minutes' => '',
        ]);

        $this->assertFalse(
            $validator->fails(),
            'Cleared submission-limit fields were refused: '.$validator->errors()->toJson()
        );
    }

    public function test_the_fields_are_still_required_while_the_limit_is_on(): void
    {
        $validator = $this->validate($this->baseData() + [
            'throttle_enabled' => true,
            'throttle_max_attempts' => null,
            'throttle_decay_minutes' => null,
        ]);

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('throttle_max_attempts', $validator->errors()->toArray());
        $this->assertArrayHasKey('throttle_decay_minutes', $validator->errors()->toArray());
    }

    public function test_a_value_outside_the_range_is_still_refused_while_the_limit_is_on(): void
    {
        $validator = $this->validate($this->baseData() + [
            'throttle_enabled' => true,
            'throttle_max_attempts' => 0,
            'throttle_decay_minutes' => 2000,
        ]);

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('throttle_max_attempts', $validator->errors()->toArray());
        $this->assertArrayHasKey('throttle_decay_minutes', $validator->errors()->toArray());
    }
}
