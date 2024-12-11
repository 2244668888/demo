@extends('layouts.app')
@section('title')
    CUSTOMER EDIT
@endsection
@section('content')
    <div class="card">
        <form method="post" action="{{ route('customer.update', $customer->id) }}">
            @csrf
            <div class="card-body">
                <div class="row">
                    <h5>CUSTOMER DETAILS</h5>
                </div>
                <br>
                <div class="row">
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label class="form-label" for="name">Name</label>
                            <input type="text" class="form-control" id="name" name="name"
                                value="{{ $customer->name }}" placeholder="Enter Name">
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label class="form-label" for="code">Code</label>
                            <input type="text" class="form-control" id="code" name="code"
                                value="{{ $customer->code }}" placeholder="Enter Code">
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label class="form-label" for="phone">Phone No</label>
                            <input type="number" class="form-control" id="phone" name="phone"
                                value="{{ $customer->phone }}" placeholder="Enter Phone">
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label class="form-label" for="address">Address</label>
                            <textarea name="address" id="address" class="form-control" rows="1" placeholder="Enter Address">{{ $customer->address }}</textarea>
                        </div>
                    </div>
                </div>
                <br>
                <div class="row">
                    <h5>PIC DETAILS</h5>
                </div>
                <br>
                <div class="row">
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label class="form-label" for="pic_name">PIC Name</label>
                            <input type="text" class="form-control" id="pic_name" name="pic_name"
                                value="{{ $customer->pic_name }}" placeholder="Enter PIC name">
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label class="form-label" for="pic_department">PIC Department</label>
                            <input type="text" class="form-control" id="pic_department" name="pic_department"
                                value="{{ $customer->pic_department }}" placeholder="Enter PIC department">
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label class="form-label" for="pic_phone_work">PIC Phone No (Work)</label>
                            <input type="number" class="form-control" id="pic_phone_work" name="pic_phone_work"
                                value="{{ $customer->pic_phone_work }}" placeholder="Enter PIC phone work">
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label class="form-label" for="pic_phone_mobile">PIC Phone No (Mobile)</label>
                            <input type="number" class="form-control" id="pic_phone_mobile" name="pic_phone_mobile"
                                value="{{ $customer->pic_phone_mobile }}" placeholder="Enter PIC phone mobile">
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label class="form-label" for="pic_fax">PIC Fax</label>
                            <input type="text" class="form-control" id="pic_fax" name="pic_fax"
                                value="{{ $customer->pic_fax }}" placeholder="Enter PIC fax">
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label class="form-label" for="pic_email">PIC Email</label>
                            <input type="email" class="form-control" id="pic_email" name="pic_email"
                                value="{{ $customer->pic_email }}" placeholder="Enter PIC email">
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label class="form-label" for="payment_term">Payment Term</label>
                            <input type="text" class="form-control" id="payment_term" name="payment_term"
                                value="{{ $customer->payment_term }}" placeholder="Enter Payment Term">
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <div class="d-flex gap-2 justify-content-between">
                    <a type="button" class="btn btn-info" href="{{ route('customer.index') }}">
                        <i class="bi bi-arrow-left"></i> Back
                    </a>
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </div>
        </form>
    </div>
@endsection
