@extends('marketing.skeleton')

@section('content')

    <body class="marketing register">
        <div class="container">
            <div class="row">
                <div class="col-12 col-md-6 offset-md-3 offset-md-3-right">
                    <div class="tc">
                        <ul class="horizontal f6 relative mb3 light-silver">
                            <li class="mr2">
                                <span class="relative" style="top: 3px;">
                                    <svg width="15" height="15" viewBox="0 0 15 15" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M7.23334 13.8167C7.21667 13.8167 7.18334 13.8167 7.16667 13.8C6.05001 13.5 6.10001 12.45 6.13334 11.5833C6.15001 11.1833 6.16667 10.8 6.08334 10.5167C6.00001 10.2667 5.83334 10.1167 5.65001 9.94999C5.46667 9.78332 5.26667 9.59999 5.15001 9.33332C5.00001 9.01665 4.98334 8.68332 4.98334 8.34999C4.98334 8.14999 4.98334 7.96665 4.93334 7.79999C4.86667 7.51665 4.73334 7.44999 4.46667 7.33332C4.35001 7.28332 4.21667 7.21665 4.06667 7.13332C3.88334 7.01665 3.71667 6.88332 3.56667 6.74999C3.46667 6.66665 3.36667 6.56665 3.25001 6.49999C3.06667 6.36665 2.95001 6.33332 2.73334 6.28332C2.68334 6.26665 2.63334 6.26665 2.58334 6.24999C1.93334 6.08332 1.78334 5.53332 1.65001 5.09999C1.50001 4.59999 1.35001 3.93332 1.40001 3.23332C1.41667 3.09999 1.53334 2.98332 1.66667 2.99999C1.80001 3.01665 1.91667 3.13332 1.90001 3.26665C1.86667 3.76665 1.93334 4.28332 2.13334 4.96665C2.26667 5.44999 2.36667 5.66665 2.70001 5.76665C2.75001 5.78332 2.80001 5.78332 2.83334 5.79999C3.06667 5.86665 3.26667 5.91665 3.53334 6.09999C3.65001 6.18332 3.76667 6.28332 3.86667 6.38332C4.01667 6.51665 4.15001 6.63332 4.30001 6.71665C4.40001 6.78332 4.51667 6.83332 4.63334 6.88332C4.93334 7.01665 5.28334 7.16665 5.40001 7.71665C5.45001 7.93332 5.45001 8.14999 5.45001 8.36665C5.45001 8.64999 5.46667 8.91665 5.56667 9.13332C5.65001 9.31665 5.80001 9.44999 5.95001 9.58332C6.16667 9.78332 6.41667 9.99999 6.53334 10.3833C6.65001 10.75 6.63334 11.2 6.61667 11.6333C6.58334 12.3167 6.60001 12.8167 6.86667 13.1C6.81667 12.7833 6.80001 12.4333 6.98334 12.2C7.16667 11.9667 7.43334 11.85 7.70001 11.75C7.86667 11.6833 8.01667 11.6167 8.11667 11.5333C8.38334 11.3333 8.43334 11.15 8.50001 10.7833L8.51667 10.7333C8.60001 10.4 8.85001 10.25 9.05001 10.1333C9.13334 10.0833 9.21667 10.0333 9.28334 9.98332C9.56667 9.73332 9.98334 9.14999 9.98334 8.73332C9.98334 8.59999 9.95001 8.49999 9.86667 8.41665C9.73334 8.29999 9.55001 8.21665 9.35001 8.13332C9.23334 8.08332 9.11667 8.03332 9.00001 7.96665C8.76667 7.83332 8.61667 7.64999 8.48334 7.49999C8.40001 7.39999 8.31667 7.29999 8.21667 7.21665C8.06667 7.09999 7.85001 7.08332 7.61667 7.04999C7.48334 7.03332 7.36667 7.01665 7.23334 6.99999C7.01667 6.94999 6.80001 6.86665 6.61667 6.79999C6.43334 6.73332 6.26667 6.66665 6.10001 6.61665C5.73334 6.51665 5.38334 6.59999 4.98334 6.68332C4.50001 6.78332 4.18334 6.81665 4.01667 6.16665C3.98334 6.04999 3.86667 5.99999 3.63334 5.93332C3.33334 5.84999 2.76667 5.68332 2.96667 4.91665C3.06667 4.49999 3.40001 4.16665 3.83334 4.04999C4.15001 3.94999 4.46667 3.98332 4.75001 4.09999C4.88334 3.59999 5.20001 3.18332 5.48334 2.81665C5.96667 2.19999 6.33334 2.13332 7.06667 2.14999H7.10001C7.11667 2.14999 7.15001 2.14999 7.18334 2.14999C7.15001 2.11665 7.10001 2.08332 7.06667 2.06665C6.95001 1.98332 6.83334 1.91665 6.76667 1.79999C6.66667 1.64999 6.58334 1.46665 6.51667 1.29999C6.36667 0.949987 6.25001 0.716654 6.05001 0.683321C5.91667 0.666654 5.81667 0.533321 5.83334 0.383321C5.85001 0.233321 5.98334 0.149987 6.13334 0.166654C6.63334 0.249987 6.83334 0.716654 6.98334 1.08332C7.05001 1.23332 7.11667 1.38332 7.18334 1.48332C7.21667 1.51665 7.30001 1.58332 7.36667 1.63332C7.51667 1.73332 7.70001 1.86665 7.75001 2.04999C7.81667 2.24999 7.75001 2.38332 7.70001 2.44999C7.55001 2.64999 7.28334 2.64999 7.10001 2.63332H7.06667C6.45001 2.61665 6.25001 2.64999 5.88334 3.09999C5.56667 3.49999 5.21667 3.98332 5.18334 4.49999C5.18334 4.59999 5.11667 4.68332 5.01667 4.71665C4.93334 4.74999 4.81667 4.73332 4.75001 4.66665C4.55001 4.48332 4.25001 4.41665 3.96667 4.49999C3.70001 4.58332 3.51667 4.76665 3.45001 5.01665C3.38334 5.31665 3.43334 5.33332 3.76667 5.41665C4.03334 5.49999 4.40001 5.59999 4.51667 6.01665C4.55001 6.14999 4.56667 6.19999 4.58334 6.21665C4.63334 6.21665 4.78334 6.18332 4.88334 6.16665C5.31667 6.08332 5.73334 5.98332 6.21667 6.11665C6.41667 6.16665 6.60001 6.23332 6.78334 6.31665C6.96667 6.38332 7.15001 6.46665 7.33334 6.49999C7.43334 6.51665 7.53334 6.53332 7.65001 6.54999C7.93334 6.58332 8.25001 6.61665 8.51667 6.83332C8.65001 6.94999 8.76667 7.06665 8.85001 7.18332C8.96667 7.31665 9.06667 7.44999 9.21667 7.53332C9.31667 7.58332 9.41667 7.63332 9.51667 7.68332C9.75001 7.78332 9.98334 7.88332 10.1667 8.06665C10.35 8.24999 10.45 8.48332 10.45 8.76665C10.4333 9.39999 9.90001 10.1 9.56667 10.3833C9.46667 10.4667 9.35001 10.5333 9.26667 10.6C9.10001 10.7 9.00001 10.7667 8.96667 10.8833L8.95001 10.9333C8.86667 11.3333 8.78334 11.65 8.36667 11.9667C8.21667 12.0833 8.01667 12.1667 7.83334 12.25C7.63334 12.3333 7.41667 12.4333 7.31667 12.55C7.21667 12.6667 7.30001 13.0667 7.33334 13.3C7.35001 13.4 7.36667 13.5 7.38334 13.5833C7.40001 13.6667 7.36667 13.75 7.30001 13.8C7.35001 13.7833 7.28334 13.8167 7.23334 13.8167Z"
                                            fill="black" />
                                        <path
                                            d="M7.5 15C3.36667 15 0 11.6333 0 7.5C0 3.36667 3.36667 0 7.5 0C11.6333 0 15 3.36667 15 7.5C15 11.6333 11.6333 15 7.5 15ZM7.5 0.516667C3.65 0.516667 0.516667 3.65 0.516667 7.5C0.516667 11.35 3.65 14.4833 7.5 14.4833C11.35 14.4833 14.4833 11.35 14.4833 7.5C14.4833 3.65 11.35 0.516667 7.5 0.516667Z"
                                            fill="black" />
                                        <path
                                            d="M13.3167 8.28333C13.1167 8.28333 12.9167 8.21666 12.75 8.09999C12.15 7.68333 11.9833 6.71666 11.95 6.53333C11.9 6.18333 11.9167 5.79999 11.9333 5.41666C11.95 4.76666 11.9833 4.09999 11.6667 3.59999C11.6 3.48333 11.4667 3.38333 11.35 3.26666C11.15 3.09999 10.9333 2.91666 10.9667 2.64999C10.9833 2.49999 11.1 2.38333 11.2333 2.28333C11.0833 2.19999 10.9167 2.14999 10.75 2.09999C10.2833 1.96666 9.71667 1.79999 9.75 0.899994C9.75 0.766661 9.86667 0.649994 10.0167 0.649994C10.15 0.649994 10.2667 0.766661 10.2667 0.916661C10.25 1.41666 10.45 1.46666 10.9 1.59999C11.2167 1.69999 11.6 1.79999 11.8833 2.14999C11.9333 2.21666 11.95 2.29999 11.9333 2.36666C11.9167 2.43333 11.8667 2.51666 11.7833 2.54999C11.6167 2.61666 11.5333 2.68333 11.4833 2.71666C11.5333 2.76666 11.6 2.83333 11.6667 2.88333C11.8167 2.99999 11.9833 3.14999 12.1 3.33333C12.4833 3.96666 12.4667 4.71666 12.4333 5.44999C12.4167 5.81666 12.4 6.16666 12.45 6.46666C12.5167 6.91666 12.7167 7.48333 13.0333 7.68333C13.1667 7.76666 13.3 7.79999 13.45 7.74999C13.5667 7.71666 13.6833 7.58333 13.7833 7.44999C13.9333 7.26666 14.0833 7.04999 14.35 7.06666C14.6167 7.06666 14.8167 7.31666 14.9333 7.48333C15.0167 7.59999 14.9833 7.74999 14.8667 7.83333C14.75 7.91666 14.6 7.88333 14.5167 7.76666C14.4167 7.63333 14.3667 7.58333 14.3333 7.56666C14.2833 7.59999 14.2167 7.68333 14.1667 7.74999C14.0167 7.93333 13.8333 8.16666 13.5333 8.23333C13.4833 8.26666 13.4 8.28333 13.3167 8.28333Z"
                                            fill="black" />
                                    </svg>
                                </span>
                                {{ trans('auth.change_language_title') }}
                            </li>
                            @foreach ($locales as $locale)
                                <li>
                                    @if (App::isLocale($locale['lang']))
                                        {{ $locale['lang'] }}
                                    @else
                                        <a href="{{ route('register') }}?lang={{ $locale['lang'] }}"
                                            title="{{ trans('auth.change_language', ['lang' => $locale['name-orig']], $locale['lang']) }}">
                                            {{ $locale['lang'] }}
                                        </a>
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    <div class="signup-box">
                        <div class="dt w-100">
                            <div class="dtc tc">
                                <img src="img/monica.svg" width="97" height="88" alt="">
                            </div>
                        </div>
                        @if ($first)
                            <h1>{{ trans('auth.register_title_welcome') }}</h1>
                            <h2>{{ trans('auth.register_create_account') }}</h2>
                        @else
                            <h2>{{ trans('auth.register_title_create') }}</h2>
                            <h3>{!! trans('auth.register_login', ['url' => 'login']) !!}</h3>
                        @endif

                        @include('partials.errors')
                        @if (session('confirmation-success'))
                            <div class="alert alert-success">
                                {{ session('confirmation-success') }}
                            </div>
                        @endif

                        <form action="register" method="post">
                            @csrf

                            <div class="form-group">
                                <label for="email">{{ trans('auth.register_email') }}</label>
                                <input type="email" class="form-control" id="email" name="email"
                                    placeholder="{{ trans('auth.register_email_example') }}"
                                    value="{{ old('email') }}" required autocomplete="email" autofocus>
                            </div>

                            <div class="row">
                                <div class="col-12 col-sm-6">
                                    <div class="form-group">
                                        <label for="first_name">{{ trans('auth.register_firstname') }}</label>
                                        <input type="text" class="form-control" id="first_name" name="first_name"
                                            placeholder="{{ trans('auth.register_firstname_example') }}"
                                            value="{{ old('first_name') }}" required autocomplete="given-name">
                                    </div>
                                </div>
                                <div class="col-12 col-sm-6">
                                    <div class="form-group">
                                        <label for="last_name">{{ trans('auth.register_lastname') }}</label>
                                        <input type="text" class="form-control" id="last_name" name="last_name"
                                            placeholder="{{ trans('auth.register_lastname_example') }}"
                                            value="{{ old('last_name') }}" required autocomplete="family-name">
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="dob">{{ trans('auth.register_dob') }}</label>
                                <input type="date" class="form-control" id="dob" name="dob"
                                    placeholder="{{ trans('auth.register_dob_example') }}" required autocomplete="dob">
                            </div>

                            <div class="form-group">
                                <label for="password">{{ trans('auth.register_password') }}</label>
                                <input type="password" class="form-control" id="password" name="password"
                                    placeholder="{{ trans('auth.register_password_example') }}" required
                                    autocomplete="password">
                            </div>

                            <div class="form-group">
                                <label
                                    for="password_confirmation">{{ trans('auth.register_password_confirmation') }}</label>
                                <input type="password" class="form-control" id="password_confirmation"
                                    name="password_confirmation" required autocomplete="password">
                            </div>

                            <div class="form-group">
                                <h2>Select a Charity to Donate to</h2>
                                <p class="info">
                                    {!! trans('auth.register_charity_disclaimer', ['percentage' => nova_get_setting('charitable_percentage', 30)]) !!}
                                </p>
                                <div class="form-group">
                                    <label
                                        for="charity_preference">{{ trans('auth.register_charity_preference') }}</label>
                                    <select name="charity_preference" id="charity_preference" class="form-control"
                                        placeholder="{{ trans('auth.register_charity_preference_example') }}">
                                        <option value=''>Default: Automatically select for me!</option>
                                        @foreach (\App\Models\Charity::pluck('id', 'name') as $charity => $id)
                                            <option value="{{ $id }}">{{ $charity }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <!-- Policy acceptance check -->
                            <div class="form-check">
                                <label class="form-check-label">
                                    <input class="form-check-input" id="policy" name="policy" type="checkbox" value="policy"
                                        required>
                                    {!! trans('auth.register_policy', ['url' => 'https://monicahq.com/privacy', 'urlterm' => 'https://monicahq.com/terms', 'hreflang' => 'en']) !!}
                                </label>
                            </div>

                            <div class="form-group actions">
                                <input type="hidden" name="lang" value="{{ App::getLocale() }}" />
                                <button type="submit"
                                    class="btn btn-primary">{{ trans('auth.register_action') }}</button>
                            </div>

                        </form>
                    </div>

                </div>
            </div>
        </div>
    </body>
@endsection
