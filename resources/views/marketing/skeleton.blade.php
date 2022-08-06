<!DOCTYPE html>
<html lang="{{ \App::getLocale() }}" dir="{{ htmldir() }}">

<head>
    <base href="{{ url('/') }}/" />
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="x-ua-compatible" content="IE=edge,chrome=1">
    <title>{{ trans('app.application_title') }}</title>

    <link rel="stylesheet" href="{{ asset(mix('css/app-' . htmldir() . '.css')) }}">
    <meta name="description" content="{{ trans('app.application_description') }}">
    <link rel="author" href="@djaiss">
    <meta property="og:title" content="{{ trans('app.application_og_title') }}" />
    <link rel="shortcut icon" href="img/favicon.png">

    <script id="laravel">
        window.Laravel = {!! \Safe\json_encode([
            'locale' => \App::getLocale(),
            'htmldir' => htmldir(),
            'env' => \App::environment(),
        ]) !!}
    </script>
</head>

@yield('content')

{{-- THE JS FILE OF THE APP --}}
@push('scripts')
    <script src="{{ asset(mix('js/manifest.js')) }}"></script>
    <script src="{{ asset(mix('js/vendor.js')) }}"></script>
@endpush

{{-- Load everywhere except on the Upgrade account page --}}
@if (Route::currentRouteName() != 'settings.subscriptions.upgrade' &&
    Route::currentRouteName() != 'settings.subscriptions.confirm')
    @push('scripts')
        <script src="{{ asset(mix('js/app.js')) }}"></script>
    @endpush
@endif

@stack('scripts')

</html>
