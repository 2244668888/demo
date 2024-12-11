@extends('layouts.app')
@section('title')
    MACHINE TONNAGE EDIT
@endsection
@section('content')
    <div class="card">
        <form method="post" action="{{ route('machine_tonage.update', $machine_tonnage->id) }}">
            @csrf
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label class="form-label" for="tonnage">Tonnage</label>
                            <input type="text" class="form-control" id="tonnage" name="tonnage"
                                value="{{ $machine_tonnage->tonnage }}" placeholder="Enter Tonnage">
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <div class="d-flex gap-2 justify-content-between">
                    <a type="button" class="btn btn-info" href="{{ route('machine_tonage.index') }}">
                        <i class="bi bi-arrow-left"></i> Back
                    </a>
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </div>
        </form>
    </div>
@endsection
