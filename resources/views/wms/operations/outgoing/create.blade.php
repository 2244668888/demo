@extends('layouts.app')
@section('title')
    OUTGOING CREATE
@endsection
@section('content')
    <form method="post" action="{{ route('outgoing.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label for="ref_no" class="form-label">DO No</label>
                            <input type="text" readonly name="ref_no" id="ref_no" class="form-control"
                                value="{{ $ref_no }}">
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label for="date" class="form-label">Date</label>
                            <input type="date" readonly name="date" id="date" class="form-control"
                                >
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
                                value="{{ old('acc_no') }}">
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label for="payment_term" class="form-label">Payment Term</label>
                            <input type="text" name="payment_term" id="payment_term" class="form-control"
                                value="{{ old('payment_term') }}">
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label for="mode" class="form-label">Mode of Despatch</label>
                            <input type="text" name="mode" id="mode" class="form-control"
                                value="{{ old('mode') }}">
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
                                <option value="1" @selected(old('category') == 1)>Sales Return</option>
                                <option value="2" @selected(old('category') == 2)>Purchase Return</option>
                                <option value="3" @selected(old('category') == 3)>Order</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-4 col-12 sales_return {{ old('category') == 1 ? '' : 'd-none' }}"
                        onchange="get_sr()">
                        <div class="mb-3">
                            <label for="sr_id" class="form-label">Ref No</label>
                            <select name="sr_id" id="sr_id" class="form-select">
                                <option value="" selected disabled>Please Select</option>
                                @foreach ($sales_returns as $sales_return)
                                    <option value="{{ $sales_return->id }}" @selected(old('sr_id') == $sales_return->id)>
                                        {{ $sales_return->ref_no }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-4 col-12 purchase_return {{ old('category') == 2 ? '' : 'd-none' }}"
                        onchange="get_pr()">
                        <div class="mb-3">
                            <label for="pr_id" class="form-label">Ref No</label>
                            <select name="pr_id" id="pr_id" class="form-select">
                                <option value="" selected disabled>Please Select</option>
                                @foreach ($purchase_returns as $purchase_return)
                                    <option value="{{ $purchase_return->id }}" @selected(old('pr_id') == $purchase_return->id)>
                                        {{ $purchase_return->grd_no }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-4 col-12 order {{ old('category') == 3 ? '' : 'd-none' }}"
                        onchange="get_order()">
                        <div class="mb-3">
                            <label for="order_id" class="form-label">Ref No</label>
                            <select name="order_id" id="order_id" class="form-select">
                                <option value="" selected disabled>Please Select</option>
                                @foreach ($orders as $order)
                                    <option value="{{ $order->id }}" @selected(old('order_id') == $order->id)>
                                        {{ $order->order_no }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-4 col-12 customer @if (old('category') == 2) d-none @endif">
                        <div class="mb-3">
                            <label for="customer" class="form-label">Customer</label>
                            <input type="text" readonly name="customer" id="customer" class="form-control">
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-4 col-12 supplier @if (old('category') != 2) d-none @endif">
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
                        <button type="button" class="btn btn-primary" id="add_item" data-bs-toggle="modal"
                            data-bs-target="#exampleModalCenter">ADD PRODUCTS</button>
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
                                        <th>Quantity</th>
                                        <th>Remarks</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <div class="row">
                    <div class="d-flex gap-2 justify-content-between col-12">
                        <input type="hidden" id="storedData" name="details">
                        <a type="button" class="btn btn-info" href="{{ route('outgoing.index') }}">
                            <i class="bi bi-arrow-left"></i> Back
                        </a>
                        <button type="button" class="btn btn-primary" id="saveForm">Submit</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
    {{-- PRODUCTS MODAL --}}
    <div class="modal fade" id="exampleModalCenter" tabindex="-1" aria-labelledby="exampleModalCenterTitle"
        aria-modal="true" role="dialog">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalCenterTitle">
                        PRODUCTS
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table table-bordered m-0 w-100" id="modalTable">
                            <thead>
                                <tr>
                                    <th>
                                        
                                        <input type="checkbox" id="selectAll" style="width: 22px; height: 22px;">
                                    </th>
                                    <th>Part No</th>
                                    <th>Part Name</th>
                                    <th>Unit</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondry" data-bs-dismiss="modal">
                        CANCEL
                    </button>
                    <button type="button" class="btn btn-primary" onclick="add_product()">
                        ADD
                    </button>
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
                            <div>Transfer Quantity: <span class="total_qty_text"></span></div>
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
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="saveModal">Add</button>
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
                    <button type="button" class="btn btn-primary" id="saveRemarks">Save</button>
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
                defaultDate: new Date(),
            });
            modalTable = $('#modalTable').DataTable({
                "columnDefs": [{
                    "targets": 0,
                    "orderable": false
                }]
            });
            productTable = $('#productTable').DataTable();
            allocationTable = $('#allocationTable').DataTable();
            sessionStorage.clear();
            locations = @json($locations);
            $('#category').on('change', function() {
                let value = $(this).val();
                if (value == 1) {
                    $('.sales_return').removeClass('d-none');
                    $('.purchase_return').addClass('d-none');
                    $('.order').addClass('d-none');
                    $('.supplier').addClass('d-none');
                    $('.customer').removeClass('d-none');
                    $('.sales_return').find('.select2-container').addClass('w-100');
                } else if (value == 2) {
                    $('.purchase_return').removeClass('d-none');
                    $('.sales_return').addClass('d-none');
                    $('.order').addClass('d-none');
                    $('.supplier').removeClass('d-none');
                    $('.customer').addClass('d-none');
                    $('.purchase_return').find('.select2-container').addClass('w-100');
                } else if (value == 3) {
                    $('.order').removeClass('d-none');
                    $('.purchase_return').addClass('d-none');
                    $('.sales_return').addClass('d-none');
                    $('.supplier').addClass('d-none');
                    $('.customer').removeClass('d-none');
                    $('.order').find('.select2-container').addClass('w-100');
                } else {
                    $('.order').addClass('d-none');
                    $('.purchase_return').addClass('d-none');
                    $('.sales_return').addClass('d-none');
                    $('.supplier').addClass('d-none');
                    $('.customer').addClass('d-none');
                }
                $('#sr_id').val('');
                $('#pr_id').val('');
                $('#order_id').val('');
                $('#supplier').val('');
                $('#customer').val('');
                $('#address').val('');
                productTable.clear().draw();
                modalTable.clear().draw();
                sessionStorage.clear();
            });

            orders = {!! json_encode($orders) !!};
            sales_returns = {!! json_encode($sales_returns) !!};
            purchase_returns = {!! json_encode($purchase_returns) !!};
        });

        function get_sr() {
            var srId = $("#sr_id").val();
            var sr = sales_returns.find(p => p.id == srId);
            if (sr) {
                $('#supplier').val('');
                $('#customer').val(sr.customer.name);
                $('#address').val(sr.customer.address);
                $('#payment_term').val(sr.customer.payment_term);
                populateProductTable(sr.sales_return_detail);
            } else {
                productTable.clear().draw();
            }
            sessionStorage.clear();
        };

        function get_pr() {
            var prId = $("#pr_id").val();
            var pr = purchase_returns.find(p => p.id == prId);
            if (pr) {
                $('#customer').val('');
                $('#supplier').val(pr.supplier.name);
                $('#address').val(pr.supplier.address);
                populateProductTable(pr.purchase_return_detail);
            } else {
                productTable.clear().draw();
            }
            sessionStorage.clear();
        };

        function get_order() {
            var orderId = $("#order_id").val();
            var order = orders.find(p => p.id == orderId);
            if (order) {
                $('#supplier').val('');
                $('#customer').val(order.customers.name);
                $('#address').val(order.customers.address);
                $('#payment_term').val(order.customers.payment_term);
                populateProductTable(order.order_detail);
            } else {
                productTable.clear().draw();
            }
            sessionStorage.clear();
        };

        function populateProductTable(data) {
            productTable.clear();
            data.forEach((product, index) => {
                let products;
                if ($('#category').val() == 3) {
                    products = product.products;
                } else if ($('#category').val() == 2) {
                    products = product.product;
                } else if ($('#category').val() == 1) {
                    products = product.product;
                }

                let newRowData = [
                    productTable.rows().count() + 1,
                    `<input type="hidden" class="product_id" name="products[${index}][product_id]" value="${products.id}">${products.part_no}`,
                    products.part_name,
                    products.units ? products.units.name : ''
                ];

                // Add the remaining columns
                newRowData.push(
                    `<input type="number" readonly class="form-control qty" name="products[${index}][qty]" value="0">`,
                    `<button type="button" class="btn btn-danger btn-sm add-remarks">Add</button>
                    <input type="hidden" class="remarks" name="products[${index}][remarks]" value="">`,
                    '<button type="button" class="btn btn-success btn-sm openModal" data-bs-toggle="modal" data-bs-target="#exampleModal"><i class="bi bi-plus-circle"></i></button><button type="button" class="btn btn-danger btn-sm remove-product ms-2"><i class="bi bi-trash"></i></button>'
                );

                let newRow = productTable.row.add(newRowData).draw();
            });
        }

        function add_product() {
            modalTable.$('input:checked').each(function() {
                var row = $(this).closest('tr');
                var product_id = row.find('.product_id').val();
                var part_no = row.find('td:eq(1)').text();
                var part_name = row.find('td:eq(2)').text();
                var unit = row.find('td:eq(3)').text();

                // Add the row data to the main table
                productTable.row.add([
                    productTable.rows().count() + 1,
                    `<input type="hidden" class="product_id" name="products[${productTable.rows().count() + 1}][product_id]" value="${product_id}">${part_no}`,
                    part_name,
                    unit,
                    `<input type="number" readonly class="form-control qty" name="products[${productTable.rows().count() + 1}][qty]" value="0">`,
                    `<button type="button" class="btn btn-danger btn-sm add-remarks">Add</button>
                    <input type="hidden" class="remarks" name="products[${productTable.rows().count() + 1}][remarks]" value="">`,
                    '<button type="button" class="btn btn-success btn-sm openModal" data-bs-toggle="modal" data-bs-target="#exampleModal"><i class="bi bi-plus-circle"></i></button><button type="button" class="btn btn-danger btn-sm remove-product ms-2"><i class="bi bi-trash"></i></button>'
                ]).draw(false);

                // Remove the row from the modal table
                modalTable.row(row).remove().draw();
            });

            // Uncheck all checkboxes
            $('#modalTable input:checked').prop('checked', false);

            // Hide the modal
            $('#exampleModalCenter').modal('hide');
        }

        $('#productTable tbody').on('click', 'button.remove-product', function() {
            var row = $(this).closest('tr');
            var part_no = row.find('td:eq(1)').text();
            var part_name = row.find('td:eq(2)').text();
            var unit = row.find('td:eq(3)').text();

            // Add the removed row back to the modal table
            modalTable.row.add([
                `<input class="form-check-input product_id" type="checkbox" value="${$(row).find('.product_id').val()}">`,
                part_no,
                part_name,
                unit
            ]).draw();

            // Remove the row from the main table
            productTable.row(row).remove().draw();
            resetSerialNumbers(productTable);
        });

        function resetSerialNumbers() {
            if ($('#productTable tbody tr:first').find('td:first').text() != 'No data available in table') {
                $('#productTable tbody tr').each(function(index) {
                    $(this).find('td:first').text(index + 1);
                });
            }
        }

        $(document).on('click', '.add-remarks', function() {
            selectedRow = productTable.row($(this).closest('tr'));
            $('#remarksText').val($(this).closest('tr').find('.remarks').val());
            $('#remarksModal').modal('show');
        });

        $(document).on('click', '#saveRemarks', function() {
            let remarks = $('#remarksText').val();
            let button = selectedRow.node().querySelector('.add-remarks');
            if (remarks.trim() !== '') {
                button.classList.remove('btn-danger');
                button.classList.add('btn-success');
                button.textContent = 'Edit';
            } else {
                button.classList.remove('btn-success');
                button.classList.add('btn-danger');
                button.textContent = 'Add';
            }
            selectedRow.node().querySelector('.remarks').value = remarks;
            $('#remarksModal').modal('hide');
        });

        // ALLOCATION WORK
        function addRow(button) {
            // Clone the row and get the data from it
            let total = 0;
            $('#allocationTable .qty').each(function() {
                total += +$(this).val();

            });
            var req_qty = $('.request_qty_text').text();
            if (total >= req_qty) {
                $('#popUp').prepend(`
                    <div class="alert border-warning alert-dismissible fade show text-warning" role="alert">
                    <b>Warning!</b> Can't add more rows !.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                `);
            } else {
                var row = button.parentNode.parentNode.cloneNode(true);
                var rowData = $(row).find('td').map(function() {
                    return $(this).html();
                }).get();

                // Add the cloned data as a new row in DataTable
                allocationTable.row.add(rowData).draw(false);

                // Trigger any additional required events
                $('.qty').trigger('keyup');
            }

        }

        function removeRow(button) {
            // Check if there is more than one row
            if ($('#allocationTable tr').length > 2) { // Including header row
                // Find the row index and remove it
                allocationTable.row($(button).closest('tr')).remove().draw(false);
            } else {
                $('#popUp').prepend(`
                    <div class="alert border-warning alert-dismissible fade show text-warning" role="alert">
                    <b>Warning!</b> Can't remove Row!.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                `);
            }

            // Trigger any additional required events
            $('.qty').trigger('keyup');
        }

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
                        `<select class="form-control lot_no">${lotOptionsHtml}</select>`,
                        `<select class="form-control location">${locationOptionsHtml}</select>`,
                        `<input type="number" class="form-control available_qty" readonly value="${element.available_qty}">`,
                        `<input type="number" class="form-control qty" value="${element.qty}">`,
                        `<button type="button" class="btn btn-success btn-sm me-2" onclick="addRow(this)"><i class="bi bi-plus"></i></button><button type="button" class="btn btn-danger btn-sm" onclick="removeRow(this)"><i class="bi bi-dash"></i></button>`
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
                        `<select class="form-control lot_no"><option>select</option>${lotOptionsHtml}</select>`,
                        `<select class="form-control location"><option>select</option>${locationOptionsHtml}</select>`,
                        `<input type="number" readonly class="form-control available_qty">`,
                        `<input type="number" class="form-control qty">`,
                        `<button type="button" class="btn btn-success btn-sm me-2" onclick="addRow(this)"><i class="bi bi-plus"></i></button><button type="button" class="btn btn-danger btn-sm" onclick="removeRow(this)"><i class="bi bi-dash"></i></button>`
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

        $('#saveModal').on('click', function() {
            var alertNeeded = false;

            $('#allocationTable tr').each(function() {
                var availableQty = parseFloat($(this).find('.available_qty').val());
                var qty = parseFloat($(this).find('.qty').val());

                if (availableQty < qty) {
                    alertNeeded = true;
                    return false;
                }
            });

            if (alertNeeded) {
                $('#popUp').prepend(`
                    <div class="alert border-warning alert-dismissible fade show text-warning" role="alert">
                    <b>Warning!</b> total quantity and actual qty must be equal!.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                `);
            } else {
                $('#exampleModal').modal('hide');
                let hiddenId = $('.product_ids').val();
                let data = [];
                $('#allocationTable tbody tr').each(function() {
                    let rowData = {};
                    rowData['location'] = $(this).find('.location').val();
                    rowData['area'] = $(this).find('.location option:selected').attr('data-area-id');
                    rowData['rack'] = $(this).find('.location option:selected').attr('data-rack-id');
                    rowData['level'] = $(this).find('.location option:selected').attr('data-level-id');
                    rowData['available_qty'] = $(this).find('.available_qty').val();
                    rowData['lot_no'] = $(this).find('.lot_no').val();
                    rowData['qty'] = $(this).find('.qty').val();
                    rowData['hiddenId'] = hiddenId;
                    data.push(rowData);
                });
                sessionStorage.setItem(`modalData${hiddenId}`, JSON.stringify(data));
                $('#productTable tbody tr').each(function() {
                    if ($(this).find('.product_id').val() == hiddenId) {
                        let total_qty = $('.total_qty_text').text();
                        $(this).find('.qty').val(total_qty);
                    }
                });
            }
        });

        $(document).on('keyup change', '.qty', function() {
            let total = 0;
            $('#allocationTable .qty').each(function() {
                total += +$(this).val();
            });
            $('.total_qty_text').text(total);
        });

        $('#saveForm').on('click', function() {
            $('.table').DataTable().page.len(-1).draw();
            let array = [];
            $('.product_id').each(function() {
                let storedData = sessionStorage.getItem(`modalData${$(this).val()}`);
                if (storedData == null) {
                    storedData = `{"hiddenId":"${$(this).val()}"}`;
                }
                array.push(JSON.parse(storedData));
            });
            $('#storedData').val(JSON.stringify(array));
            $(this).closest('form').submit();
        });

        document.getElementById('selectAll').addEventListener('change', function() {
            const checkboxes = document.querySelectorAll('.product_id');
            checkboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
        })
    </script>
@endsection
