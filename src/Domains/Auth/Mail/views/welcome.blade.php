@include('Common::partials.header')

<div class="title">
    Welcome to {{ config('app.name') }}
</div>

@if ( $code )
    <div class="normal-text" style="margin-bottom: 20px;">
        Click on the button and activate your account!
    </div>
    <div class="text-center">
        <a href="{{ config('path.front_url') }}/confirm/{{ $code->code }}" class="btn btn--primary">
            Activate
        </a>
    </div>
@endif

@include('Common::partials.footer')
