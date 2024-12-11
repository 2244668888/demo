@extends('layouts.app')
@section('title')
    SUPPLIER EDIT
@endsection
@section('content')
    <div class="card">
        <form method="post" action="{{ route('supplier.update', $supplier->id) }}">
            @csrf
            <div class="card-body">
                <div class="row">
                    <h5>SUPPLIER DETAILS</h5>
                </div>
                <br>
                <div class="row">
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label class="form-label" for="name">Name</label>
                            <input type="text" class="form-control" id="name" name="name"
                                value="{{ $supplier->name }}" placeholder="Enter Name">
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label class="form-label" for="address">Address</label>
                            <textarea name="address" id="address" class="form-control" rows="1" placeholder="Enter Address">{{ $supplier->address }}</textarea>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label class="form-label" for="contact">Contact No</label>
                            <input type="number" class="form-control" id="contact" name="contact"
                                value="{{ $supplier->contact }}" placeholder="Enter Contact no">
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label class="form-label" for="group">Group</label>
                            <div class="m-0">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="group"
                                        id="group" value="Direct" @if(!$supplier->group) checked @endif @checked($supplier->group == 'Direct')>
                                    <label class="form-check-label" for="group">Direct</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="group"
                                        id="group1" value="InDirect" @checked($supplier->group == 'InDirect')>
                                    <label class="form-check-label" for="group1">InDirect</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <br>
                <div class="row">
                    <h5>CONTACT PERSON DETAILS</h5>
                </div>
                <br>
                <div class="row">
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label class="form-label" for="contact_person_name">Contact Person Name</label>
                            <input type="text" class="form-control" id="contact_person_name" name="contact_person_name"
                                value="{{ $supplier->contact_person_name }}" placeholder="Enter Name">
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label class="form-label" for="contact_person_telephone">Telephone</label>
                            <input type="number" class="form-control" id="contact_person_telephone" name="contact_person_telephone"
                                value="{{ $supplier->contact_person_telephone }}" placeholder="Enter Telephone">
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label class="form-label" for="contact_person_department">Department</label>
                            <input type="text" class="form-control" id="contact_person_department" name="contact_person_department"
                                value="{{ $supplier->contact_person_department }}" placeholder="Enter Department">
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label class="form-label" for="contact_person_mobile">Mobile Phone</label>
                            <input type="number" class="form-control" id="contact_person_mobile" name="contact_person_mobile"
                                value="{{ $supplier->contact_person_mobile }}" placeholder="Enter Mobile phone">
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label class="form-label" for="contact_person_fax">Fax</label>
                            <input type="text" class="form-control" id="contact_person_fax" name="contact_person_fax"
                                value="{{ $supplier->contact_person_fax }}" placeholder="Enter Fax">
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label class="form-label" for="contact_person_email">Email</label>
                            <input type="email" class="form-control" id="contact_person_email" name="contact_person_email"
                                value="{{ $supplier->contact_person_email }}" placeholder="Enter Email">
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <div class="d-flex gap-2 justify-content-between">
                    <a type="button" class="btn btn-info" href="{{ route('supplier.index') }}">
                        <i class="bi bi-arrow-left"></i> Back
                    </a>
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </div>
        </form>
    </div>
@endsection
