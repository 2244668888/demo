@extends('layouts.app')
@section('title')
    AREA CREATE
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
        <form method="post" action="{{ route('area.store') }}">
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
                            <label class="form-label" for="area_rack">Area Rack</label>
                            <select class="form-select" id="area_rack" name="area_rack[]" multiple>
                                @foreach ($area_racks as $area_rack)
                                    <option value="{{ $area_rack->id }}" @if (old('area_rack')) {{ in_array($area_rack->id, old('area_rack')) ? 'selected' : '' }} @endif>
                                        {{ $area_rack->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label class="form-label" for="department">Department</label>
                            <select class="form-select" id="department" name="department">
                                <option value="" selected disabled>Select Department</option>
                                <option value="Production" @selected(old('department') == 'Production')>Production</option>
                                <option value="Store" @selected(old('department') == 'Store')>Store</option>
                                <option value="Logistic" @selected(old('department') == 'Logistic')>Logistic</option>
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
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </div>
        </form>
    </div>
@endsection
