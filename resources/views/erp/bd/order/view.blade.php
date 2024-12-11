 @extends('layouts.app')
@section('title')
    ORDER VIEW
@endsection
@section('content')
    <style>
        #mainTable input {
            width: 100px;
        }
    </style>
    <div class="card">
        <div class="card-body">
            <div class="row">
                <h5>GENERAL INFORMATION</h5>
            </div>
            <br>
            <div class="row">
                <div class="col-lg-3 col-sm-4 col-12">
                    <div class="mb-3">
                        <label for="created_by" class="form-label">Created By</label>
                        <input type="text" readonly name="created_by" id="created_by" class="form-control"
                            value="{{ $order->user->user_name }}">
                    </div>
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-lg-3 col-sm-4 col-12">
                    <div class="mb-3">
                        <label for="date" class="form-label">Created Date</label>
                        <input type="text" readonly name="date" id="date" class="form-control"
                            value="{{ $order->date }}">
                    </div>
                </div>
                <div class="col-lg-3 col-sm-4 col-12">
                    <div class="mb-3">
                        <label for="order_no" class="form-label"> Customer Order No</label>
                        <input type="text" name="order_no" id="order_no" class="form-control"
                            value="{{ $order->order_no }}">
                    </div>
                </div>
                <div class="col-lg-3 col-sm-4 col-12">
                    <div class="mb-3">
                        <label for="po_no" class="form-label"> Customer PO No</label>
                        <input type="text" name="po_no" id="po_no" class="form-control"
                            value="{{ $order->po_no }}">
                    </div>
                </div>
                <div class="col-lg-3 col-sm-4 col-12">
                    <div class="mb-3">
                        <label for="order_month" class="form-label">Order Month</label>
                        <input type="month" name="order_month" id="order_month" class="form-control"
                            value="{{ $order->order_month }}">
                    </div>
                </div>
                <div class="col-lg-3 col-sm-4 col-12">
                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select name="status" id="status" class="form-select">
                            <option value="" selected disabled>Select Status</option>
                            <option value="in-progress" @selected($order->status == 'in-progress')>In Progress</option>
                            <option value="complete" @selected($order->status == 'complete')>Complete</option>
                        </select>
                    </div>
                </div>
                <div class="col-lg-4 col-sm-4 col-12">
                    <label for="inputGroupFileAddon03" class="form-label">Attachment</label>
                    <div class="input-group mb-3">
                        <a target="_blank" href="{{ asset('/order-attachments/') }}/{{ $order->attachment }}"
                            class="btn btn-outline-secondary" target="_blank" type="button" id="inputGroupFileAddon03">
                            <i class="bi bi-file-text"></i>{{ $order->attachment }}
                        </a>
                        <input type="file" class="form-control" id="inputGroupFile03"
                            aria-describedby="inputGroupFileAddon03" aria-label="Upload">
                    </div>
                </div>
            </div>
            <br>
            <div class="row">
                <h5>CUSTOMER DETAIL</h5>
            </div>
            <br>
            <div class="row">
                <div class="col-lg-3 col-sm-4 col-12">
                    <div class="mb-3">
                        <label for="customer_id" class="form-label">Customer Name</label>
                        <select name="customer_id" onchange="customer_change()" id="customer_id" class="form-select">
                            <option value="">Please Select</option>
                            @foreach ($customers as $customer)
                                <option value="{{ $customer->id }}" @selected($order->customer_id == $customer->id)>{{ $customer->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-4 col-12">
                    <div class="mb-3">
                        <label for="pic_name" class="form-label">PIC Name</label>
                        <input type="text" readonly name="" id="pic_name" class="form-control ">
                    </div>
                </div>
                <div class="col-lg-3 col-sm-4 col-12">
                    <div class="mb-3">
                        <label for="pic_email" class="form-label">PIC Email</label>
                        <input type="text" readonly name="" id="pic_email" class="form-control ">
                    </div>
                </div>
                <div class="col-lg-3 col-sm-4 col-12">
                    <div class="mb-3">
                        <label for="pic_phone" class="form-label">PIC Phone No. (Work/Mobile)</label>
                        <input type="text" readonly name="" id="pic_phone" class="form-control ">
                    </div>
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-12 d-flex justify-content-between">
                    <h5>PRODUCT DETAIL</h5>
                </div>
            </div>
            <br>
            <div class="row">
                <div class="table-responsive">
                    <table class="table table-bordered m-0" id="mainTable">
                        <thead>
                            <tr>
                                <th>Sr No</th>
                                <th>Part No</th>
                                <th>Part Name</th>
                                <th>Type of Product</th>
                                <th>Category</th>
                                <th>Variance</th>
                                <th>Model</th>
                                <th>Unit</th>
                                <th>Price(Unit)</th>
                                <th>SST %</th>
                                <th>SST Value</th>
                                <th>Firm 1 Month Qty</th>
                                <th>N+1 Forecast Month Qty</th>
                                <th>N+2 Forecast Month Qty</th>
                                <th>N+3 Forecast Month Qty</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($order_details as $order_detail)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $order_detail->products->part_no }}</td>
                                    <td>{{ $order_detail->products->part_name }}</td>
                                    <td>{{ $order_detail->products->type_of_products->type ?? '' }}</td>
                                    <td>{{ $order_detail->products->category }}</td>
                                    <td>{{ $order_detail->products->variance }}</td>
                                    <td>{{ $order_detail->products->model }}</td>
                                    <td>{{ $order_detail->products->units->name ?? '' }}</td>
                                    <td><input type="hidden" name="products[{{ $loop->iteration }}][product_id]"
                                            class="product_id" value="{{ $order_detail->product_id }}">
                                        <input type="number" readonly class="form-control price"
                                            name="products[{{ $loop->iteration }}][price]"
                                            value="{{ $order_detail->price }}">
                                    </td>
                                    <td><input type="number" readonly class="form-control sst_percentage"
                                            name="products[{{ $loop->iteration }}][sst_percentage]"
                                            value="{{ $order_detail->sst_percentage }}">
                                    </td>
                                    <td><input type="number" class="form-control sst_value"
                                            name="products[{{ $loop->iteration }}][sst_value]"
                                            value="{{ $order_detail->sst_value }}" readonly></td>
                                    <td><input type="number" class="form-control"
                                            name="products[{{ $loop->iteration }}][firm_qty]"
                                            value="{{ $order_detail->firm_qty }}" readonly></td>
                                    <td><input type="number" class="form-control"
                                            name="products[{{ $loop->iteration }}][n1_qty]"
                                            value="{{ $order_detail->n1_qty }}" readonly></td>
                                    <td><input type="number" class="form-control"
                                            name="products[{{ $loop->iteration }}][n2_qty]"
                                            value="{{ $order_detail->n2_qty }}" readonly></td>
                                    <td><input type="number" class="form-control"
                                            name="products[{{ $loop->iteration }}][n3_qty]"
                                            value="{{ $order_detail->n3_qty }}" readonly></td>
                                    <td><button type="button" class="btn btn-danger btn-sm remove-product"><i
                                                class="bi bi-trash"></i></button></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <div class="row">
                <div class="d-flex gap-2 justify-content-start col-12">
                    <a type="button" class="btn btn-info" href="{{ route('order.index') }}">
                        <i class="bi bi-arrow-left"></i> Back
                    </a>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            customer_change();
            $('.card-body input, select').prop('disabled', true);
            $('#mode-selector').removeAttr('disabled');
            $('#mainTable').DataTable();
        });

        var customers = {!! json_encode($customers) !!};

        function customer_change() {
            var customerId = $("#customer_id").val();
            var customer = customers.find(p => p.id == customerId);
            if (customer) {
                $('#pic_name').val(customer.pic_name);
                $('#pic_email').val(customer.pic_email);
                $('#pic_phone').val(`${customer.pic_phone_work}/${customer.pic_phone_mobile}`);
            } else {
                $('#pic_name').val('');
                $('#pic_email').val('');
                $('#pic_phone').val('');
            }
        };
    </script>
@endsection
