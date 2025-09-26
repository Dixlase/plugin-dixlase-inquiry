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


<form action="{{ route('inquiry.submit') }}" method="POST">
    @csrf
    <div class="form-group">
        <label>お名前 <span class="required">*</span></label>
        <input type="text" name="name" class="form-control" required>
    </div>
    
    <div class="form-group">
        <label>メールアドレス <span class="required">*</span></label>
        <input type="email" name="email" class="form-control" required>
    </div>

    @if($settings->show_phone)
    <div class="form-group">
        <label>電話番号 @if($settings->phone_required)<span class="required">*</span>@endif</label>
        <input type="tel" name="phone" class="form-control" 
            @if($settings->phone_required) required @endif>
    </div>
    @endif

    @if($settings->show_address)
    <div class="form-group">
        <label>住所 @if($settings->address_required)<span class="required">*</span>@endif</label>
        <input type="text" name="address" class="form-control"
            @if($settings->address_required) required @endif>
    </div>
    @endif

    <div class="form-group">
        <label>本文 <span class="required">*</span></label>
        <textarea name="message" rows="5" class="form-control" required></textarea>
    </div>

    <button type="submit" class="btn btn-primary">送信する</button>
</form>