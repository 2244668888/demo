@extends('layouts.app')
@section('title')
    DEPARTMENT VIEW
@endsection
@section('content')
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-lg-3 col-sm-4 col-12">
                    <div class="mb-3">
                        <label class="form-label" for="name">Name</label>
                        <input type="text" class="form-control" id="name" disabled name="name"
                            value="{{ $department->name }}" placeholder="Enter Name">
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <div class="d-flex gap-2 justify-content-start">
                <a type="button" class="btn btn-info" href="{{ route('department.index') }}">
                    <i class="bi bi-arrow-left"></i> Back
                </a>
            </div>
        </div>
    </div>
@endsection
