@include('Common::partials.header')

<div class="title">
    You have been invited to join <b>{{ $team->name }}</b> on {{ config('app.name') }}
</div>
<div class="normal-text" style="margin-bottom: 20px;">
    Click on the button to accept the invitation!
</div>
<div class="text-center">
    <a href="{{ config('path.front_url') }}/accept-invite/{{ $invite->code }}" class="btn btn--primary">
        Join {{ $team->name }}
    </a>
</div>

@include('Common::partials.footer')
