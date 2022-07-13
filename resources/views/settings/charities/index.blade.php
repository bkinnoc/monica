@extends('layouts.skeleton')

@section('content')
    <div class="settings">

        {{-- Breadcrumb --}}
        <div class="breadcrumb">
            <div class="{{ Auth::user()->getFluidLayout() }}">
                <div class="row">
                    <div class="col-12">
                        <ul class="horizontal">
                            <li>
                                <a href="{{ route('dashboard.index') }}">{{ trans('app.breadcrumb_dashboard') }}</a>
                            </li>
                            <li>
                                <a href="{{ route('settings.index') }}">{{ trans('app.breadcrumb_settings') }}</a>
                            </li>
                            <li>
                                {{ trans('app.breadcrumb_settings_charities') }}
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="{{ Auth::user()->getFluidLayout() }}">
            <div class="row">

                @include('settings._sidebar')

                <div class="col-12 col-sm-9">

                    <div class="mb3">
                        <h3 class="f3 fw5">{{ trans('settings.charities_tab_title') }}</h3>
                        <p>{{ trans('settings.charities_title') }}</p>
                    </div>

                    <div class="br3 ba b--gray-monica bg-white mb4">
                        <div class="pa3 bb b--gray-monica">
                            <charity-preference :percent="{{ nova_get_setting('charitable_percentage', 30) }}">
                            </charity-preference>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
