@extends('layouts.app')
@section('title')
    PROCESS VIEW
@endsection
@section('content')
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-lg-3 col-sm-4 col-12">
                    <div class="mb-3">
                        <label class="form-label" for="name">Name</label>
                        <input type="text" class="form-control" disabled id="name" name="name"
                            value="{{ $process->name }}" placeholder="Enter Name">
                    </div>
                </div>
                <div class="col-lg-3 col-sm-4 col-12">
                    <div class="mb-3">
                        <label class="form-label" for="code">Code</label>
                        <input type="text" class="form-control" disabled id="code" name="code"
                            value="{{ $process->code }}" placeholder="Enter Code">
                    </div>
                </div>
                <div class="col-lg-3 col-sm-4 col-12">
                    <div class="mb-3">
                        <label class="form-label" for="description">Description</label>
                        <textarea name="description" id="description" disabled rows="1" class="form-control">{{ $process->description }}</textarea>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <div class="d-flex gap-2 justify-content-start">
                <a type="button" class="btn btn-info" href="{{ route('process.index') }}">
                    <i class="bi bi-arrow-left"></i> Back
                </a>
            </div>
        </div>
    </div>
@endsection
