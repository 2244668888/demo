@extends('layouts.app')
@section('title')
    Category CREATE
@endsection
@section('button')
    <a type="button" class="btn btn-info" href="{{ route('category.index') }}">
        <i class="bi bi-arrow-left"></i> Back
    </a>
@endsection
@section('content')
    <div class="card">
        <form method="post" action="{{ route('category.store') }}">
            @csrf
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label class="form-label" for="name">Name</label>
                            <input type="text" class="form-control" id="name" name="name"
                                value="{{ old('name') }}" placeholder="Enter Name">
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <div class="d-flex gap-2 justify-content-end">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </div>
        </form>
    </div>
@endsection
