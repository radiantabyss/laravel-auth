@include('ra.auth.partials.email.header')

<div class="title">
    Welcome to ShoutBoards
</div>
<div class="normal-text" style="margin-bottom: 20px;">
    Click on the button and activate your account!
</div>
<div class="text-center">
    <a href="{{ config('path.front_url') ?: config('app.url') }}/activate{{ $user->parent_id ? '-with-password' : '' }}/{{ $user->id }}/{{ $user->activation_code }}" class="btn btn-pink">Activate</a>
</div>
<div class="normal-text" style="margin-top: 20px;">
    If you can click the button, copy paste this link into the browser:<br/>
    <b>{{ config('path.front_url') ?: config('app.url') }}/activate{{ $user->parent_id ? '-with-password' : '' }}/{{ $user->id }}/{{ $user->activation_code }}</b>
</div>

@include('ra.auth.partials.email.footer')
