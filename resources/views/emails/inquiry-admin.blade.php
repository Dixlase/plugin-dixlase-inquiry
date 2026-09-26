{{--
This file is part of Dixlase Inquiry.

Copyright (C) 2026 exc-D inc. and Dixlase contributors
https://exc-d.com

Dixlase Inquiry is dual-licensed. You may use this file under either:

  (a) the GNU General Public License version 3 or later, as published
      by the Free Software Foundation; or

  (b) a commercial license agreement obtained from exc-D inc.

Unless you have entered into a commercial license agreement, this
file is governed by the GPL terms below.

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program. If not, see <https://www.gnu.org/licenses/>.
--}}

{{-- Visitor-supplied values go through MailMarkdown::escape() so Markdown
     in them (links, images, emphasis) renders as plain text. --}}
<x-mail::message>
# {{ __('dixlase-inquiry::front.mail.admin.title') }}

{{ __('dixlase-inquiry::front.mail.admin.intro') }}

---

**{{ __('dixlase-inquiry::front.form.name') }}**  
{{ \Plugins\DixlaseInquiry\App\Support\MailMarkdown::escape($inquiryData['name'] ?? '') }}

**{{ __('dixlase-inquiry::front.form.email') }}**  
{{ \Plugins\DixlaseInquiry\App\Support\MailMarkdown::escape($inquiryData['email'] ?? '') }}

@if(!empty($inquiryData['subject']))
**{{ __('dixlase-inquiry::front.form.subject') }}**  
{{ \Plugins\DixlaseInquiry\App\Support\MailMarkdown::escape($inquiryData['subject'] ?? '') }}

@endif
@if(!empty($inquiryData['phone']))
**{{ __('dixlase-inquiry::front.form.phone') }}**  
{{ \Plugins\DixlaseInquiry\App\Support\MailMarkdown::escape($inquiryData['phone'] ?? '') }}

@endif
@if(!empty($inquiryData['postal_code']))
**{{ __('dixlase-inquiry::front.form.postal_code') }}**  
{{ \Plugins\DixlaseInquiry\App\Support\MailMarkdown::escape($inquiryData['postal_code'] ?? '') }}

@endif
@if(!empty($inquiryData['address']))
**{{ __('dixlase-inquiry::front.form.address') }}**  
{{ \Plugins\DixlaseInquiry\App\Support\MailMarkdown::escape($inquiryData['address'] ?? '') }}

@endif
@if(!empty($inquiryData['gender']))
**{{ __('dixlase-inquiry::front.form.gender') }}**  
{{ \Plugins\DixlaseInquiry\App\Support\MailMarkdown::escape($inquiryData['gender'] ?? '') }}

@endif
---

**{{ __('dixlase-inquiry::front.form.message') }}**

{{ \Plugins\DixlaseInquiry\App\Support\MailMarkdown::escape($inquiryData['message'] ?? '') }}

---

{{ __('dixlase-inquiry::front.mail.regards') }}  
{{ config('app.name') }}
</x-mail::message>
