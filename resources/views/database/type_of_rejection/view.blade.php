@extends('layouts.app')
@section('title')
    TYPE OF REJECTION VIEW
@endsection
@section('content')
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-lg-3 col-sm-4 col-12">
                    <div class="mb-3">
                        <label class="form-label" for="type">Type</label>
                        <input type="text" class="form-control" id="type" disabled name="type"
                            value="{{ $type_of_rejection->type }}" placeholder="Enter Type">
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <div class="row">
                <div class="d-flex gap-2 justify-content-start col-12">
                    <a type="button" class="btn btn-info" href="{{ route('type_of_rejection.index') }}">
                        <i class="bi bi-arrow-left"></i> Back
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
