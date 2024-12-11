@extends('layouts.app')
@section('title')
    AREA RACK CREATE
@endsection
@section('content')
<style>
    .tooltip-arrow{
        display: none !important;
        width: 0px !important;
        height: 0px !important;

    }
    .tooltip-inner{
        display: none !important;
        width: 0px !important;
        height: 0px !important;
    }
</style>
    <div class="card">
        <form method="post" action="{{ route('area_rack.store') }}">
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
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label class="form-label" for="code">Code</label>
                            <input type="text" class="form-control" id="code" name="code"
                                value="{{ old('code') }}" placeholder="Enter Code">
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label class="form-label" for="area_level">Area Level</label>
                            <select class="form-select" id="area_level" name="area_level[]" multiple>
                                @foreach ($area_levels as $area_level)
                                    <option value="{{ $area_level->id }}" @if (old('area_level')) {{ in_array($area_level->id, old('area_level')) ? 'selected' : '' }} @endif>
                                        {{ $area_level->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <div class="d-flex gap-2 justify-content-between">
                    <a type="button" class="btn btn-info" href="{{ route('area_rack.index') }}">
                        <i class="bi bi-arrow-left"></i> Back
                    </a>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </div>
        </form>
    </div>
@endsection
