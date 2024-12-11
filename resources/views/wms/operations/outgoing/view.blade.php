@extends('layouts.app')
@section('title')
    OUTGOING VIEW
@endsection
@section('content')
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-lg-3 col-sm-4 col-12">
                    <div class="mb-3">
                        <label for="ref_no" class="form-label">DO No</label>
                        <input type="text" readonly name="ref_no" id="ref_no" class="form-control"
                            value="{{ $outgoing->ref_no }}">
                    </div>
                </div>
                <div class="col-lg-3 col-sm-4 col-12">
                    <div class="mb-3">
                        <label for="date" class="form-label">Date</label>
                        <input type="date" readonly name="date" id="date" class="form-control"
                            value="{{ $outgoing->date }}">
                    </div>
                </div>
                <div class="col-lg-3 col-sm-4 col-12">
                    <div class="mb-3">
                        <label for="created_by" class="form-label">Created By</label>
                        <input type="text" readonly name="created_by" id="created_by" class="form-control"
                            value="{{ Auth::user()->user_name }}">
                    </div>
                </div>
                <div class="col-lg-3 col-sm-4 col-12">
                    <div class="mb-3">
                        <label for="acc_no" class="form-label">A/C No</label>
                        <input type="text" name="acc_no" id="acc_no" class="form-control"
                            value="{{ $outgoing->acc_no }}">
                    </div>
                </div>
                <div class="col-lg-3 col-sm-4 col-12">
                    <div class="mb-3">
                        <label for="payment_term" class="form-label">Payment Term</label>
                        <input type="text" name="payment_term" id="payment_term" class="form-control"
                            value="{{ $outgoing->payment_term }}">
                    </div>
                </div>
                <div class="col-lg-3 col-sm-4 col-12">
                    <div class="mb-3">
                        <label for="mode" class="form-label">Mode of Despatch</label>
                        <input type="text" name="mode" id="mode" class="form-control"
                            value="{{ $outgoing->mode }}">
                    </div>
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-lg-3 col-sm-4 col-12">
                    <div class="mb-3">
                        <label for="category" class="form-label">Category</label>
                        <select name="category" id="category" class="form-select">
                            <option value="" selected disabled>Please Select</option>
                            <option value="1" @selected($outgoing->category == 1)>Sales Return</option>
                            <option value="2" @selected($outgoing->category == 2)>Purchase Return</option>
                            <option value="3" @selected($outgoing->category == 3)>Order</option>
                        </select>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-4 col-12 sales_return @if ($outgoing->category != 1) d-none @endif"
                    onchange="get_sr()">
                    <div class="mb-3">
                        <label for="sr_id" class="form-label">Ref No</label>
                        <select name="sr_id" id="sr_id" class="form-select">
                            <option value="" selected disabled>Please Select</option>
                            @foreach ($sales_returns as $sales_return)
                                <option value="{{ $sales_return->id }}" @selected($outgoing->sr_id == $sales_return->id)>
                                    {{ $sales_return->ref_no }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-4 col-12 purchase_return @if ($outgoing->category != 2) d-none @endif"
                    onchange="get_pr()">
                    <div class="mb-3">
                        <label for="pr_id" class="form-label">Ref No</label>
                        <select name="pr_id" id="pr_id" class="form-select">
                            <option value="" selected disabled>Please Select</option>
                            @foreach ($purchase_returns as $purchase_return)
                                <option value="{{ $purchase_return->id }}" @selected($outgoing->pr_id == $purchase_return->id)>
                                    {{ $purchase_return->grd_no }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-4 col-12 order @if ($outgoing->category != 3) d-none @endif"
                    onchange="get_order()">
                    <div class="mb-3">
                        <label for="order_id" class="form-label">Ref No</label>
                        <select name="order_id" id="order_id" class="form-select">
                            <option value="" selected disabled>Please Select</option>
                            @foreach ($orders as $order)
                                <option value="{{ $order->id }}" @selected($outgoing->order_id == $order->id)>
                                    {{ $order->order_no }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-4 col-12 customer @if ($outgoing->category == 2) d-none @endif">
                    <div class="mb-3">
                        <label for="customer" class="form-label">Customer</label>
                        <input type="text" readonly name="customer" id="customer" class="form-control">
                    </div>
                </div>
                <div class="col-lg-3 col-sm-4 col-12 supplier @if ($outgoing->category != 2) d-none @endif">
                    <div class="mb-3">
                        <label for="supplier" class="form-label">Supplier</label>
                        <input type="text" readonly name="supplier" id="supplier" class="form-control">
                    </div>
                </div>
                <div class="col-lg-3 col-sm-4 col-12">
                    <div class="mb-3">
                        <label for="address" class="form-label">Address</label>
                        <textarea readonly name="address" id="address" class="form-control" rows="1"></textarea>
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
                <div class="col-12">
                    <div class="table-responsive">
                        <table class="table table-bordered m-0" id="productTable">
                            <thead>
                                <tr>
                                    <th>Sr No</th>
                                    <th>Part No</th>
                                    <th>Part Name</th>
                                    <th>Unit</th>
                                    <th>Return Qty</th>
                                    <th>Remarks</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($outgoing_details as $outgoing_detail)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td><input type="hidden" class="product_id"
                                                name="products[{{ $loop->iteration }}][product_id]"
                                                value="{{ $outgoing_detail->product_id }}">{{ $outgoing_detail->product->part_no }}
                                        </td>
                                        <td>{{ $outgoing_detail->product->part_name }}</td>
                                        <td>{{ $outgoing_detail->product->units->name ?? '' }}</td>
                                        <td><input type="number" readonly class="form-control return_qty"
                                                name="products[{{ $loop->iteration }}][return_qty]"
                                                value="{{ $outgoing_detail->qty }}"></td>
                                        <td>
                                            <input type="hidden" class="remarks"
                                                name="products[{{ $loop->iteration }}][remarks]"
                                                value="{{ $outgoing_detail->remarks }}">
                                            @if ($outgoing_detail->remarks == '' || $outgoing_detail->remarks == null)
                                                <button type="button"
                                                    class="btn btn-sm add-remarks btn-danger">Add</button>
                                            @else
                                                <button type="button"
                                                    class="btn btn-sm add-remarks btn-success">Edit</button>
                                            @endif
                                        </td>
                                        <td><button type="button" class="btn btn-success btn-sm openModal"
                                                data-bs-toggle="modal" data-bs-target="#exampleModal"><i
                                                    class="bi bi-plus-circle"></i></button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <div class="row">
                <div class="d-flex gap-2 justify-content-start col-12">
                    <a type="button" class="btn btn-info" href="{{ route('outgoing.index') }}">
                        <i class="bi bi-arrow-left"></i> Back
                    </a>
                </div>
            </div>
        </div>
    </div>
    {{-- LOCATIONS MODAL --}}
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">ALLOCATION</h5>
                    <input type="hidden" class="product_ids">
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12 d-flex justify-content-between">
                            <div>Part No: <span class="part_no_text"></span></div>
                            <div>Total Quantity: <span class="total_qty_text"></span></div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 d-flex justify-content-between">
                            <div>Unit: <span class="unit_text"></span></div>
                            <div>Part Name: <span class="part_name_text"></span></div>
                        </div>
                    </div>
                    <br>
                    <div class="table-responsive" id="popUp">
                        <table class="table table-bordered m-0 w-100" id="allocationTable">
                            <thead>
                                <tr>
                                    <th>Lot No</th>
                                    <th>Location</th>
                                    <th>Available Qty</th>
                                    <th>Quantity</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    {{-- REMARKS MODAL --}}
    <div class="modal fade" id="remarksModal" tabindex="-1" aria-labelledby="remarksModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="remarksModalLabel">Add Remarks</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <textarea id="remarksText" class="form-control" rows="4" placeholder="Enter your remarks here"></textarea>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <script>
        let orders;
        let modalTable;
        let productTable;
        let sales_returns;
        let allocationTable;
        let purchase_returns;
        $(document).ready(function() {
            flatpickr("#date", {
                dateFormat: "d-m-Y",
                defaultDate:@json(\Carbon\Carbon::parse($outgoing->date)->format('d-m-Y'))
            });
            $('.card-body').find('input, select, textarea').prop('disabled', true);
            modalTable = $('#modalTable').DataTable();
            productTable = $('#productTable').DataTable();
            allocationTable = $('#allocationTable').DataTable();
            sessionStorage.clear();
            var outgoing_locations = @json($outgoing_locations);
            outgoing_locations.forEach(element => {
                let data = sessionStorage.getItem(`modalData${element.product_id}`);
                if (!data) {
                    data = [];
                } else {
                    data = JSON.parse(data);
                }
                let rowData = {};
                rowData['location'] = `${element.area_id}->${element.shelf_id}->${element.level_id}`;
                rowData['area'] = element.area_id;
                rowData['shelf'] = element.shelf_id;
                rowData['level'] = element.level_id;
                rowData['qty'] = element.qty;
                rowData['available_qty'] = element.available_qty;
                rowData['lot_no'] = element.lot_no;
                rowData['hiddenId'] = element.product_id;
                data.push(rowData);
                sessionStorage.setItem(`modalData${element.product_id}`, JSON.stringify(data));
            });
            locations = @json($locations);

            orders = {!! json_encode($orders) !!};
            sales_returns = {!! json_encode($sales_returns) !!};
            purchase_returns = {!! json_encode($purchase_returns) !!};

            if ($('#sr_id').val() != null) {
                get_sr();
            } else if ($('#pr_id').val() != null) {
                get_pr();
            } else if ($('#order_id').val() != null) {
                get_order();
            }
        });

        function get_sr() {
            var srId = $("#sr_id").val();
            var sr = sales_returns.find(p => p.id == srId);
            if (sr) {
                $('#supplier').val('');
                $('#customer').val(sr.customer.name);
                $('#address').val(sr.customer.address);
            } else {
                productTable.clear().draw();
            }
        };

        function get_pr() {
            var prId = $("#pr_id").val();
            var pr = purchase_returns.find(p => p.id == prId);
            if (pr) {
                $('#customer').val('');
                $('#supplier').val(pr.supplier.name);
                $('#address').val(pr.supplier.address);
            } else {
                productTable.clear().draw();
            }
        };

        function get_order() {
            var orderId = $("#order_id").val();
            var order = orders.find(p => p.id == orderId);
            if (order) {
                $('#supplier').val('');
                $('#customer').val(order.customers.name);
                $('#address').val(order.customers.address);
            } else {
                productTable.clear().draw();
            }
        };

        // ALLOCATION WORK
        $(document).on('click', '.add-remarks', function() {
            selectedRow = productTable.row($(this).closest('tr'));
            $('#remarksText').val($(this).closest('tr').find('.remarks').val());
            $('#remarksModal').modal('show');
        });

        // ALLOCATION WORK
        $(document).on('click', '.openModal', function() {
            let hiddenId = $(this).closest('tr').find('.product_id').val();
            $('.product_ids').val(hiddenId);
            let storedData = sessionStorage.getItem(`modalData${hiddenId}`);

            // Clear existing rows in the table
            allocationTable.clear().draw();

            if (storedData) {
                storedData = JSON.parse(storedData);
                storedData.forEach(element => {
                    let lotOptionsHtml = '';
                    let locationOptionsHtml = '';
                    locations.forEach(location => {
                        if (location.product_id == hiddenId) {
                            let selectedLot = (element.lot_no === location.lot_no) ? 'selected' :
                                '';
                            lotOptionsHtml +=
                                `<option value="${location.lot_no}" ${selectedLot}>${location.lot_no}</option>`;
                        }

                        if (location.product_id == hiddenId && location.lot_no === element.lot_no) {
                            let selected = (element.location ===
                                `${location.area_id}->${location.rack_id}->${location.level_id}`
                            ) ? 'selected' : '';
                            locationOptionsHtml +=
                                `<option data-area-id="${location.area_id}" data-rack-id="${location.rack_id}" data-level-id="${location.level_id}" data-qty="${location.used_qty}" value="${location.area_id}->${location.rack_id}->${location.level_id}" ${selected}>${location.area.name}->${location.rack.name}->${location.level.name}</option>`;
                        }
                    });

                    allocationTable.row.add([
                        `<select class="form-control lot_no" disabled>${lotOptionsHtml}</select>`,
                        `<select class="form-control location" disabled>${locationOptionsHtml}</select>`,
                        `<input type="number" class="form-control available_qty"  disabled value="${element.available_qty}">`,
                        `<input type="number" class="form-control qty" disabled value="${element.qty}">`
                    ]).draw(false);
                });
            } else {
                var flag = false;
                let lotOptionsHtml = '';
                let locationOptionsHtml = '';
                let selectedLotNo = locations[0].lot_no;
                locations.forEach((location, index) => {
                    if (location.product_id == hiddenId) {
                        flag = true;
                        lotOptionsHtml += `<option value="${location.lot_no}">${location.lot_no}</option>`;
                    }
                    if (location.product_id == hiddenId && location.lot_no === selectedLotNo) {
                        locationOptionsHtml +=
                            `<option data-area-id="${location.area_id}" data-rack-id="${location.rack_id}" data-level-id="${location.level_id}" data-qty="${location.used_qty}" value="${location.area_id}->${location.rack_id}->${location.level_id}">${location.area.name}->${location.rack.name}->${location.level.name}</option>`;
                    }
                });

                if (flag) {
                    $('.qt_alert').addClass('d-none');
                    $('#saveModal').removeClass('d-none');
                    allocationTable.row.add([
                        `<select class="form-control lot_no" disabled><option>select</option>${lotOptionsHtml}</select>`,
                        `<select class="form-control location" disabled><option>select</option>${locationOptionsHtml}</select>`,
                        `<input type="number" class="form-control available_qty" disabled>`,
                        `<input type="number" class="form-control qty" disabled>`
                    ]).draw();
                } else {
                    $('#popUp').prepend(`
                        <div class="alert border-warning alert-dismissible fade show text-warning qt_alert" role="alert">
                        <b>Warning!</b> Not enough Quantity to issue
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    `);
                    $('#saveModal').addClass('d-none');
                }
            }

            $(document).off('change', '.lot_no').on('change', '.lot_no', function() {
                updateLocations(this);
            });

            $(document).off('change', '.location').on('change', '.location', function() {
                updateQuantity(this);
            });

            // Function to update location options based on selected lot number
            function updateLocations(lotSelect) {
                const selectedLotNo = lotSelect.value;
                const locationSelect = $(lotSelect).closest('tr').find('.location');
                let locationOptionsHtml = '';

                locations.forEach(location => {
                    if (location.product_id == hiddenId && location.lot_no === selectedLotNo) {
                        locationOptionsHtml +=
                            `<option data-area-id="${location.area_id}" data-rack-id="${location.rack_id}" data-level-id="${location.level_id}" data-qty="${location.used_qty}" value="${location.area_id}->${location.rack_id}->${location.level_id}">${location.area.name}->${location.rack.name}->${location.level.name}</option>`;
                    }
                });

                locationSelect.html(locationOptionsHtml);
                locationSelect.trigger('change');
            }

            // Function to fetch and display quantity based on selected location
            function updateQuantity(locationSelect) {
                const selectedLocation = $(locationSelect).find('option:selected');
                const qty = selectedLocation.attr('data-qty');
                const qtyInput = $(locationSelect).closest('tr').find('.available_qty');
                qtyInput.val(qty);
            }

            let part_no = $(this).closest('tr').find('td:eq(1)').text();
            let part_name = $(this).closest('tr').find('td:eq(2)').text();
            let unit = $(this).closest('tr').find('td:eq(3)').text();
            let qty = $(this).closest('tr').find('.qty').val();
            $('.part_no_text').text(part_no);
            $('.part_name_text').text(part_name);
            $('.unit_text').text(unit);
            $('.qty').trigger('keyup');
        });

        $(document).on('keyup change', '.qty', function() {
            let total = 0;
            $('#allocationTable .qty').each(function() {
                total += +$(this).val();
            });
            $('.total_qty_text').text(total);
        });
    </script>
@endsection
