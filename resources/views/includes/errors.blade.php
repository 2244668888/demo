@if (isset($errors) && count($errors) > 0)
    <div class="alert border-danger alert-dismissible fade show text-danger" role="alert">
        <b>Oh No!</b>
        <ul>
            @foreach ($errors->all() as $error)
                <li>{!! $error !!}</li>
            @endforeach
        </ul>
    </div>
@endif

@if (session()->has('custom_errors'))
    <div class="alert border-danger alert-dismissible fade show text-danger" role="alert">
        <b>Oh No!</b> {!! session()->get('custom_errors') !!}.
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif
