@if (session()->has('custom_success'))
    <div class="alert border-success alert-dismissible fade show text-success" role="alert">
        <b>Well done!</b> {!! session()->get('custom_success') !!}.
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif
