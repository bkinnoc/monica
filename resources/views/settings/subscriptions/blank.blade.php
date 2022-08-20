@extends('layouts.skeleton')

@section('content')
    <div>

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
                                {{ trans('app.breadcrumb_settings_subscriptions') }}
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="main-content">
            <div class="{{ Auth::user()->getFluidLayout() }}">
                @include('settings.subscriptions.plan-select')
            </div>
        </div>
    </div>
@endsection
