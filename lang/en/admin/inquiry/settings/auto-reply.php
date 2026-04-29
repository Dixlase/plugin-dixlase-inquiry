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

return [
    'heading' => 'Auto-Reply Settings',
    'description' => 'Configure auto-reply emails sent to users after they submit the inquiry form.',

    'enabled' => 'Enable Auto-Reply',
    'from_email' => 'Auto-Reply From Email Address',
    'from_email_help' => 'If empty, the system default sender address will be used.',
    'subject' => 'Auto-Reply Subject',
    'body' => 'Auto-Reply Message Body',
    'body_help' => 'Available variables: {{name}}, {{email}}, {{subject}}, {{postal_code}}, {{address}}, {{phone}}, {{message}}',

    'default_subject' => 'Thank you for your inquiry',
    'default_body' => "Thank you for contacting us.\n\nWe have received your inquiry with the following details.\nOur team will review your message and get back to you soon.\n\nName: {{name}}\nEmail: {{email}}\nSubject: {{subject}}\n\nMessage:\n{{message}}\n\nWe appreciate your interest and look forward to assisting you.",

    'confirm_title' => 'Save Auto-Reply Settings',
    'confirm_message' => 'Are you sure you want to save the auto-reply settings?',
    'settings_updated' => 'Auto-reply settings have been updated.',
];
