@extends('layouts.app')
@section('title')
    MACHINE VIEW
@endsection
@section('content')
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-lg-3 col-sm-4 col-12">
                    <div class="mb-3">
                        <label class="form-label" for="name">Name</label>
                        <input type="text" class="form-control" disabled id="name" name="name"
                            value="{{ $machine->name }}" placeholder="Enter Name">
                    </div>
                </div>
                <div class="col-lg-3 col-sm-4 col-12">
                    <div class="mb-3">
                        <label class="form-label" for="code">Code</label>
                        <input type="text" class="form-control" disabled id="code" name="code"
                            value="{{ $machine->code }}" placeholder="Enter Code">
                    </div>
                </div>
                <div class="col-lg-3 col-sm-4 col-12">
                    <div class="mb-3">
                        <label class="form-label" for="tonnage">Tonnage</label>
                        <select class="form-select" disabled id="tonnage" name="tonnage">
                            <option value="" selected disabled>Select Tonnage</option>
                            @foreach ($tonnages as $tonnage)
                                <option value="{{ $tonnage->id }}" @selected($machine->tonnage_id == $tonnage->id)>
                                    {{ $tonnage->tonnage }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-4 col-12">
                    <div class="mb-3">
                        <label class="form-label" for="category">Category</label>
                        <select class="form-select" disabled id="category" name="category">
                            <option value="" selected disabled>Select Category</option>
                            <option value="Big" @selected($machine->category == 'Big')>Big</option>
                            <option value="Small" @selected($machine->category == 'Small')>Small</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <div class="d-flex gap-2 justify-content-start">
                <a type="button" class="btn btn-info" href="{{ route('machine.index') }}">
                    <i class="bi bi-arrow-left"></i> Back
                </a>
            </div>
        </div>
    </div>
@endsection