@extends('layouts.app')
@section('title')
    AREA RACK VIEW
@endsection
@section('content')
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-lg-3 col-sm-4 col-12">
                    <div class="mb-3">
                        <label class="form-label" for="name">Name</label>
                        <input type="text" class="form-control" disabled id="name" name="name"
                            value="{{ $area_rack->name }}" placeholder="Enter Name">
                    </div>
                </div>
                <div class="col-lg-3 col-sm-4 col-12">
                    <div class="mb-3">
                        <label class="form-label" for="code">Code</label>
                        <input type="text" class="form-control" disabled id="code" name="code"
                            value="{{ $area_rack->code }}" placeholder="Enter Code">
                    </div>
                </div>
                <div class="col-lg-3 col-sm-4 col-12">
                    <div class="mb-3">
                        @php
                            $item = json_decode($area_rack->level_id);
                        @endphp
                        <label class="form-label" for="area_level">Area Level</label>
                        <select class="form-select" id="area_level" disabled name="area_level[]" multiple>
                            @foreach ($area_levels as $area_level)
                                <option value="{{ $area_level->id }}"
                                    @if ($item) {{ in_array($area_level->id, $item) ? 'selected' : '' }} @endif>
                                    {{ $area_level->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <div class="d-flex gap-2 justify-content-start">
                <a type="button" class="btn btn-info" href="{{ route('area_rack.index') }}">
                    <i class="bi bi-arrow-left"></i> Back
                </a>
            </div>
        </div>
    </div>
@endsection
