@include('Common::partials.header')

<div class="title">
    Seems like you forgot your password
</div>
<div class="normal-text" style="margin-bottom: 20px;">
    Click on the button below and reset it.
</div>
<div class="text-center">
    <a href="{{ config('path.front_url') }}/reset-password/{{ $code->code }}" class="btn btn--primary">
        Reset Password
    </a>
</div>

@include('Common::partials.footer')
