@include('ra.auth.partials.email.header')

<div class="title">
    Seems you forgot your password
</div>
<div class="normal-text" style="margin-bottom: 20px;">
    Click on the button below and reset it.
</div>
<div class="text-center">
    <a href="{{ config('path.front_url') }}/reset-password/{{ $user->id }}/{{ $user->reset_code }}" class="btn btn-pink">Reset Password</a>
</div>
<div class="normal-text" style="margin-top: 20px;">
    If you can click the button, copy paste this link into the browser:<br/>
    <b>{{ config('path.front_url') }}/reset-password/{{ $user->id }}/{{ $user->reset_code }}</b>
</div>
@include('ra.auth.partials.email.footer')
