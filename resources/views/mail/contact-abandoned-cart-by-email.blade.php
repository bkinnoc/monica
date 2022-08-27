@component('mail::message')
{!! $body !!}

@component('mail::button', ['url' => $url])
    Claim your email address!
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
