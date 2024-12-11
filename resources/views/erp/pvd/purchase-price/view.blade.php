@extends('layouts.app')
@section('title')
    PURCHASE PRICE VIEW
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
        <div class="card-body">
            <div class="row">
                <div class="col-lg-3 col-sm-4 col-12">
                    <div class="mb-3">
                        <label for="product_id" class="form-label">Part No.</label>
                        <select name="product_id" onchange="product_change()" id="product_id" class="form-select">
                            <option value="">Please Select</option>
                            @foreach ($products as $product)
                                <option value="{{ $product->id }}" @selected($purchaseprices->product_id == $product->id)>{{ $product->part_no }}
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
                        <input type="number" name="price" value="{{ $purchaseprices->price }}" id="price"
                            class="form-control ">
                    </div>
                </div>
                <div class="col-lg-3 col-sm-4 col-12">
                    <div class="mb-3">
                        <label for="effective_date" class="form-label">Effective Date:</label>
                        <input type="date" name="date" value="{{ $purchaseprices->date }}" id="effective_date"
                            class="form-control ">
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-12 my-5">
                    <h6>Purchase Price Verfication History</h6>
                    <div class="table-responsive">
                        <table class="table table-bordered m-0" id="mainTable">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Date</th>
                                    <th>Approve</th>
                                    <th>Department</th>
                                    <th>Designation</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    use App\Models\Department;
                                    use App\Models\Designation;
                                    use App\Models\User;
                                @endphp
                                @if (isset($purchase_prices_statuses))

                                @foreach ($purchase_prices_statuses as $purchase_prices_status)
                                    <tr>
                                        <td>
                                            {{ $loop->iteration }}
                                        </td>
                                        <td>
                                            {{ date('d-m-Y',strtotime($purchase_prices_status->date)) }}
                                        </td>
                                        <td>
                                            @php
                                                $user = User::find($purchase_prices_status->approved_by);
                                            @endphp
                                            {{ $user ? $user->user_name : '' }}
                                        </td>
                                        <td>
                                            @php
                                                $department = Department::find(
                                                    $purchase_prices_status->department_id,
                                                );
                                            @endphp
                                            {{ $department ? $department->name : '' }}
                                        </td>
                                        <td>
                                            @php
                                                $designation = Designation::find(
                                                    $purchase_prices_status->designation_id,
                                                );
                                            @endphp
                                            {{ $designation ? $designation->name : '' }}
                                        </td>

                                        <td>
                                            @if ($purchase_prices_status->status == 'Requested')
                                                <span class="badge bg-dark">Requested</span></h1>
                                            @endif
                                            @if ($purchase_prices_status->status == 'verified')
                                                <span class="badge bg-info">Verified</span></h1>
                                            @endif
                                            @if ($purchase_prices_status->status == 'Approved')
                                                <span class="badge bg-success">Approved</span></h1>
                                            @endif
                                            @if ($purchase_prices_status->status == 'declined')
                                                <span class="badge bg-warning">Declined</span></h1>
                                            @endif
                                            @if ($purchase_prices_status->status == 'Cancelled')
                                                <span class="badge bg-danger">Cancelled</span></h1>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                                @endif

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
        <div class="card-footer">
            <div class="row">
                <div class="d-flex gap-2 justify-content-between col-12">
                    <a type="button" class="btn btn-info" href="{{ route('purchase_price.index') }}">
                        <i class="bi bi-arrow-left"></i> Back
                    </a>
                </div>
            </div>
        </div>
    </div>
    {{-- <div class="card">

    </div> --}}
    <script>
        $(document).ready(function() {
            flatpickr("#effective_date", {
                dateFormat: "d-m-Y",
                defaultDate:@json(\Carbon\Carbon::parse($purchaseprices->date)->format('d-m-Y'))
            });
            product_change();
            $('.card-body input, select').prop('disabled', true);
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