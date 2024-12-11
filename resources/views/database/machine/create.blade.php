@extends('layouts.app')
@section('title')
    MACHINE CREATE
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
        <form method="post" action="{{ route('machine.store') }}">
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
                            <label class="form-label" for="tonnage">Tonnage</label>
                            <select class="form-select" id="tonnage" name="tonnage">
                                <option value="" selected disabled>Select Tonnage</option>
                                @foreach ($tonnages as $tonnage)
                                    <option value="{{ $tonnage->id }}" @selected(old('tonnage') == $tonnage->id)>
                                        {{ $tonnage->tonnage }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label class="form-label" for="category">Category</label>
                            <select class="form-select" id="category" name="category">
                                <option value="" selected disabled>Select Category</option>
                                <option value="Big" @selected(old('category') == 'Big')>Big</option>
                                <option value="Small" @selected(old('category') == 'Small')>Small</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <div class="d-flex gap-2 justify-content-between">
                    <a type="button" class="btn btn-info" href="{{ route('machine.index') }}">
                        <i class="bi bi-arrow-left"></i> Back
                    </a>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </div>
        </form>
    </div>
@endsection
