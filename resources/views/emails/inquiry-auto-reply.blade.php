{{--
This file is part of DixlaseInquiry.

Copyright (C) 2025 exc-D inc.
https://exc-d.com

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

<x-mail::message>
# {{ __('dixlase-inquiry::front.mail.auto_reply.title') }}

{{ $inquiryData['name'] ?? '' }} {{ __('dixlase-inquiry::front.mail.auto_reply.greeting') }}

{{ __('dixlase-inquiry::front.mail.auto_reply.intro') }}

---

**{{ __('dixlase-inquiry::front.form.name') }}**  
{{ $inquiryData['name'] ?? '' }}

**{{ __('dixlase-inquiry::front.form.email') }}**  
{{ $inquiryData['email'] ?? '' }}

@if(!empty($inquiryData['subject']))
**{{ __('dixlase-inquiry::front.form.subject') }}**  
{{ $inquiryData['subject'] }}

@endif
---

**{{ __('dixlase-inquiry::front.form.message') }}**

{{ $inquiryData['message'] ?? '' }}

---

{{ __('dixlase-inquiry::front.mail.auto_reply.footer') }}

{{ __('mail.login_notification.regards') }}  
{{ config('app.name') }}
</x-mail::message>
