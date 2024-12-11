@extends('layouts.app')
@section('title')
    Category VIEW
@endsection
@section('button')
    <a type="button" class="btn btn-info" href="{{ route('category.index') }}">
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
                        <input type="text" class="form-control" id="name" disabled name="name"
                            value="{{ $category->name }}" placeholder="Enter Name">
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
