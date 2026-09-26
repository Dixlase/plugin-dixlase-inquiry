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

namespace Plugins\DixlaseInquiry\App\Support;

/**
 * Neutralise Markdown in visitor-supplied text before it is placed in a
 * Markdown mail.
 *
 * The inquiry mails are Markdown templates. Blade's {{ }} escapes HTML, but
 * Markdown syntax passes straight through, so a visitor could write
 * `[Reset your password](https://evil.example)` in the message and have the
 * site's own mailer deliver a formatted link -- to the site's staff, and in
 * the auto-reply to any address the visitor typed in.
 *
 * The characters that build links, images, emphasis, code and headings are
 * backslash-escaped so the text renders literally. & < > " ' are left alone:
 * Blade's {{ }} turns them into entities afterwards, which Markdown already
 * treats as plain text (so `<https://...>` cannot become an autolink), and a
 * backslash in front of an entity would print the entity itself. Keeping the
 * set small also keeps the plain-text part of the mail readable.
 */
final class MailMarkdown
{
    public static function escape(mixed $value): string
    {
        $text = is_scalar($value) ? (string) $value : '';

        return (string) preg_replace('/([\\\\`*_{}\[\]()!#|~])/', '\\\\$1', $text);
    }
}
