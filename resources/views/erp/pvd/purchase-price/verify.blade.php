@extends('layouts.app')
@section('title')
    PURCHASE PRICE VERIFY
@endsection
@section('content')
    <div class="card">
        <form method="post" action="{{ route('purchase_price.verify_update', $purchaseprice->id) }}">
            @csrf
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label for="product_id" class="form-label">Part No.</label>
                            <select name="product_id" onchange="product_change()" id="product_id" class="form-select">
                                <option value="">Please Select</option>
                                @foreach ($products as $product)
                                    <option value="{{ $product->id }}" @selected($purchaseprice->product_id == $product->id)>{{ $product->part_no }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label for="part_name" class="form-label">Part Name</label>
                            <input type="text" readonly name="" id="part_name" class="form-control ">
                        </div>
                    </div>

                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label for="model" class="form-label">Model</label>
                            <input type="text" readonly name="" id="model" class="form-control ">
                        </div>
                    </div>

                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label for="variance" class="form-label">Variance</label>
                            <input type="text" readonly name="" id="variance" class="form-control ">
                        </div>
                    </div>

                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label for="type_of_product" class="form-label">Type of Product</label>
                            <input type="text" readonly name="" id="type_of_product" class="form-control ">
                        </div>
                    </div>

                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label for="category" class="form-label">Category</label>
                            <input type="text" readonly name="" id="category" class="form-control ">
                        </div>
                    </div>

                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label for="unit" class="form-label">Unit</label>
                            <input type="text" readonly name="" id="unit" class="form-control ">
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label for="price" class="form-label">Price/Unit(RM)</label>
                            <input type="number" name="price" value="{{ $purchaseprice->price }}" id="price"
                                class="form-control ">
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label for="effective_date" class="form-label">Effective Date:</label>
                            <input type="date" name="date" value="{{ $purchaseprice->date }}" id="effective_date"
                                class="form-control ">
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <div class="row">
                    <div class="d-flex gap-2 justify-content-between col-12">
                        <div class="col-md-6 d-flex gap-2 justify-content-start">
                            <a type="button" class="btn btn-info" href="{{ route('purchase_price.index') }}">
                                <i class="bi bi-arrow-left"></i> Back
                            </a>
                        </div>
                        <div class="col-md-6 d-flex gap-2 justify-content-end">
                            <button type="button" data-bs-toggle="modal" data-bs-target="#Modalverify" class="btn btn-success float-end" style="margin-right: 25px;">Verify</button>
                            <button type="button" data-bs-toggle="modal" data-bs-target="#Modaldecline" class="btn btn-warning float-end" style="margin-right: 25px;">Decline</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
     <!-- Modal verify -->
     <div class="modal modal-lg fade" id="Modalverify" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">
                    Verification (Verify)
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Username</th>
                                <th>Designation</th>
                                <th>Department</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                use App\Models\Department;
                                use App\Models\Designation;

                                $department = Department::find(Auth::user()->department_id);
                                $designation = Designation::find(Auth::user()->designation_id);
                            @endphp
                            <tr>
                                <td>{{date('d/m/y')}}</td>
                                <td>{{Auth::user()->user_name}}</td>
                                <td>{{$designation->name ?? ''}}</td>
                                <td>{{$department->name ?? ''}}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    Close
                </button>
                <form method="post" action="{{ route('purchase_price.verifying', $purchaseprice->id) }}">
                    @csrf
                    <input type="text" name="status" value="verified" hidden>
                    <input type="text" name="approved_by" value="{{Auth::user()->id}}" hidden>
                    <input type="text" name="department_id" value="{{Auth::user()->department_id}}" hidden>
                    <input type="text" name="designation_id" value="{{Auth::user()->designation_id}}" hidden>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal decline -->
    <div class="modal modal-lg fade" id="Modaldecline" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">
                    Verification (Decline)
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="post" action="{{ route('purchase_price.decline',$purchaseprice->id) }}">
                @csrf
                <div class="modal-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Username</th>
                                <th>Designation</th>
                                <th>Department</th>
                                <th>Reason</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $department = Department::find(Auth::user()->department_id);
                                $designation = Designation::find(Auth::user()->designation_id);
                            @endphp
                            <tr>
                                <td>{{date('d/m/y')}}</td>
                                <td>{{Auth::user()->user_name}}</td>
                                <td>{{$designation->name ?? ''}}</td>
                                <td>{{$department->name ?? ''}}</td>
                                <td><input type="text" name="reason" class="form-control"></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    Close
                </button>

                    <input type="text" name="status" value="declined" hidden>
                    <input type="text" name="approved_by" value="{{Auth::user()->id}}" hidden>
                    <input type="text" name="department_id" value="{{Auth::user()->department_id}}" hidden>
                    <input type="text" name="designation_id" value="{{Auth::user()->designation_id}}" hidden>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </form>
                </div>
            </div>
        </div>
        </div>
    <script>
        $(document).ready(function() {
            flatpickr("#effective_date", {
                dateFormat: "d-m-Y",
                defaultDate:@json(\Carbon\Carbon::parse($purchaseprice->date)->format('d-m-Y'))
            });
            product_change();
            $('.card-body input, select').prop('disabled', true);
        });

        $('#decline_button, #cancel_button').on('click', function() {
            $('#decline_cancel').val($(this).val());
            if ($('#decline_cancel').val() == 'decline') {
                $('#exampleModalCenterTitle').text('DECLINE REASON');
                $('#decline_cancel_button').text('DECLINE');
            } else {
                $('#exampleModalCenterTitle').text('CANCEL REASON');
                $('#decline_cancel_button').text('CANCEL');
            }
            $('#exampleModalCenter').modal('show');
        });

        var products = {!! json_encode($products) !!};

        function product_change() {
            var productId = $("#product_id").val();
            var product = products.find(p => p.id == productId);
            if (product) {
                $('#part_name').val(product.part_name);
                $('#model').val(product.model);
                $('#variance').val(product.variance);
                $('#type_of_product').val(product.type_of_products.type);
                $('#category').val(product.categories.name);
                $('#unit').val(product.units.name);
            } else {
                $('#part_name').val('');
                $('#model').val('');
                $('#variance').val('');
                $('#type_of_product').val('');
                $('#category').val('');
                $('#unit').val('');
            }
        };
    </script>
@endsection
