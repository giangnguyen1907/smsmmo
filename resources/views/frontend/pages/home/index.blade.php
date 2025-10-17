@extends('frontend.layouts.default')

@section('content')
<section class="content">
    <div class="box box-primary">
        <div class="alert bg-danger text-white">
            <div>{!! $translates[1] ?? ''!!}</div>
            <div></div>
        </div>
        <div class="alert bg-danger text-white">
            <div>{!! $translates[2] ?? ''!!}</div>
            <div></div>
        </div>
        <div class="alert bg-danger text-white">
            <div>{!! $translates[3] ?? ''!!}</div>
            <div></div>
        </div>
        <div class="alert bg-danger text-white">
            <div>{!! $translates[4] ?? ''!!}</div>
            <div></div>
        </div>
        <div class="alert bg-danger text-white">
            <div>{!! $translates[5] ?? ''!!}</div>
            <div></div>
        </div>
        <div class="alert bg-danger text-white">
            <div>{!! $translates[6] ?? ''!!}</div>
            <div></div>
        </div>
    </div>
</section>
{{-- 
<div id="popup" style="display: none;">
    {!! $translates[8] ?? ''!!}
    <div class="checkbox-container">
        <input type="checkbox" id="agreeCheckbox" onchange="toggleAgreeButton()">
        <label for="agreeCheckbox">Tôi đồng ý với các điều khoản trên.</label>
    </div>
    <button id="agreeBtn" class="agree-btn" disabled="" onclick="agreeToTerms()">Đồng Ý</button>
</div> --}}

@endsection