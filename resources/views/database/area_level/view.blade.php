@extends('layouts.app')
@section('title')
    AREA LEVEL VIEW
@endsection
@section('button')
    <a type="button" class="btn btn-info" href="{{ route('area_level.index') }}">
        <i class="bi bi-arrow-left"></i> Back
    </a>
@endsection
@section('content')
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-lg-3 col-sm-4 col-12">
                    <div class="mb-3">
                        <label class="form-label" for="name">Name</label>
                        <input type="text" disabled class="form-control" id="name" name="name"
                            value="{{ $area_level->name }}" placeholder="Enter Name">
                    </div>
                </div>
                <div class="col-lg-3 col-sm-4 col-12">
                    <div class="mb-3">
                        <label class="form-label" for="code">Code</label>
                        <input type="text" disabled class="form-control" id="code" name="code"
                            value="{{ $area_level->code }}" placeholder="Enter Code">
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
