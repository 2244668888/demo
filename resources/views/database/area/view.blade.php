@extends('layouts.app')
@section('title')
    AREA VIEW
@endsection
@section('content')
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-lg-3 col-sm-4 col-12">
                    <div class="mb-3">
                        <label class="form-label" for="name">Name</label>
                        <input type="text" class="form-control" disabled id="name" name="name"
                            value="{{ $area->name }}" placeholder="Enter Name">
                    </div>
                </div>
                <div class="col-lg-3 col-sm-4 col-12">
                    <div class="mb-3">
                        <label class="form-label" for="code">Code</label>
                        <input type="text" class="form-control" disabled id="code" name="code"
                            value="{{ $area->code }}" placeholder="Enter Code">
                    </div>
                </div>
                <div class="col-lg-3 col-sm-4 col-12">
                    <div class="mb-3">
                        @php
                            $item = json_decode($area->rack_id);
                        @endphp
                        <label class="form-label" for="area_rack">Area Rack</label>
                        <select class="form-select" id="area_rack" disabled name="area_rack[]" multiple>
                            @foreach ($area_racks as $area_rack)
                                <option value="{{ $area_rack->id }}"
                                    @if ($item) {{ in_array($area_rack->id, $item) ? 'selected' : '' }} @endif>
                                    {{ $area_rack->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-4 col-12">
                    <div class="mb-3">
                        <label class="form-label" for="department">Department</label>
                        <select class="form-select" disabled id="department" name="department">
                            <option value="" selected disabled>Select Department</option>
                            <option value="Production" @selected($area->department == 'Production')>Production</option>
                            <option value="Store" @selected($area->department == 'Store')>Store</option>
                            <option value="Logistic" @selected($area->department == 'Logistic')>Logistic</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <div class="d-flex gap-2 justify-content-between">
                <a type="button" class="btn btn-info" href="{{ route('area.index') }}">
                    <i class="bi bi-arrow-left"></i> Back
                </a>
                <button type="submit" class="btn btn-primary">Update</button>
            </div>
        </div>
    </div>
@endsection
