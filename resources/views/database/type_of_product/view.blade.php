@extends('layouts.app')
@section('title')
    TYPE OF PRODUCT VIEW
@endsection
@section('content')
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-lg-3 col-sm-4 col-12">
                    <div class="mb-3">
                        <label class="form-label" for="type">Type</label>
                        <input type="text" class="form-control" disabled id="type" disabled name="type"
                            value="{{ $type_of_product->type }}" placeholder="Enter Type">
                    </div>
                </div>
                <div class="col-lg-3 col-sm-4 col-12">
                    <div class="mb-3">
                        <label class="form-label" for="description">Description</label>
                        <textarea name="description" disabled id="description" rows="1" class="form-control">{{ $type_of_product->description }}</textarea>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <div class="d-flex gap-2 justify-content-end">
                <a type="button" class="btn btn-info" href="{{ route('type_of_product.index') }}">
                    <i class="bi bi-arrow-left"></i> Back
                </a>
            </div>
        </div>
    </div>
@endsection
