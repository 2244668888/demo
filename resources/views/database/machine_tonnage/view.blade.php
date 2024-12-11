@extends('layouts.app')
@section('title')
    MACHINE TONNAGE VIEW
@endsection
@section('content')
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-lg-3 col-sm-4 col-12">
                    <div class="mb-3">
                        <label class="form-label" for="tonnage">Tonnage</label>
                        <input type="text" disabled class="form-control" id="tonnage" name="tonnage"
                            value="{{ $machine_tonnage->tonnage }}" placeholder="Enter Tonnage">
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <div class="d-flex gap-2 justify-content-start">
                <a type="button" class="btn btn-info" href="{{ route('machine_tonage.index') }}">
                    <i class="bi bi-arrow-left"></i> Back
                </a>
            </div>
        </div>
    </div>
@endsection
