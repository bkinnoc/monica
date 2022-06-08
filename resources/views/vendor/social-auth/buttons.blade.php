<div class="row wrap">
    <div class="col-12">
        <h3>OR</h3>
    </div>
    @foreach ($socialProviders as $provider)
        <div class="col col-12 mb-2">
            <a href="{{ route('social.auth', [$provider->slug]) }}"
                class="btn btn-lg btn-default btn-block {{ $provider->slug }} text-center"
                style="margin-bottom: 6px; width: 100%; text-align: center">
                <i clas="fa fa-google"></i> Login with {{ $provider->label }}
            </a>
        </div>
    @endforeach
</div>
