@extends('layouts.app')
@section('title')
    GOOD RECEIVING EDIT
@endsection
@section('content')
    <style>
        #productTable input {
            width: 130px;
        }
    </style>
    <form method="post" action="{{ route('good_receiving.update', $good_receiving->id) }}" enctype="multipart/form-data" id="myForm">
        @csrf
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <h5>GOOD RECEIVING DETAILS</h5>
                </div>
                <br>
                <div class="row">
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mt-4 d-flex">
                            <label class="form-label me-2" for="po_pr">PO No</label>
                            <div class="d-flex align-items-center">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="po_pr" name="po_pr"
                                        @checked($good_receiving->po_pr) value="1">
                                </div>
                            </div>
                            <label class="form-check-label" for="po_pr">GRD No</label>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-4 col-12 purchase_order @if ($good_receiving->po_pr) d-none @endif">
                        <div class="mb-3">
                            <label for="po_id" class="form-label">Ref No.</label>
                            <select name="po_id" id="po_id" class="form-select" onchange="get_po()">
                                <option value="" selected disabled>Please Select</option>
                                @foreach ($purchase_orders as $purchase_order)
                                    <option value="{{ $purchase_order->id }}" @selected($good_receiving->po_id == $purchase_order->id)>
                                        {{ $purchase_order->ref_no }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-4 col-12 purchase_return @if (!$good_receiving->po_pr) d-none @endif">
                        <div class="mb-3">
                            <label for="pr_id" class="form-label">Ref No.</label>
                            <select name="pr_id" id="pr_id" class="form-select" onchange="get_pr()">
                                <option value="" selected disabled>Please Select</option>
                                @foreach ($purchase_returns as $purchase_return)
                                    <option value="{{ $purchase_return->id }}" @selected($good_receiving->pr_id == $purchase_return->id)>
                                        {{ $purchase_return->grd_no }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div
                        class="col-lg-3 purchase_return_supplier col-sm-4 col-12 @if (!$good_receiving->po_pr) d-none @endif">
                        <div class="mb-3">
                            <label for="supplier" class="form-label">Supplier</label>
                            <input type="text" readonly name="supplier" id="supplier" class="form-control">
                        </div>
                    </div>
                    <div
                        class="col-lg-3 purchase_order_supplier col-sm-4 col-12 @if ($good_receiving->po_pr) d-none @endif">
                        <div class="mb-3">
                            <label for="order_supplier" class="form-label">Supplier</label>
                            <select name="order_supplier" id="order_supplier" class="form-select">
                                <option value="" selected disabled>Please Select</option>
                                @foreach ($suppliers as $supplier)
                                    <option value="{{ $supplier->id }}" @selected($good_receiving->supplier_id == $supplier->id)>
                                        {{ $supplier->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label for="date" class="form-label">Received Date</label>
                            <input type="date" name="date" id="date" class="form-control"
                                value="{{ $good_receiving->date }}">
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label for="received_by" class="form-label">Received By</label>
                            <input type="text" readonly name="received_by" id="received_by" class="form-control"
                                value="{{ Auth::user()->user_name }}">
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label for="ref_no" class="form-label">DO No</label>
                            <input type="text" name="ref_no" id="ref_no" class="form-control"
                                value="{{ $good_receiving->ref_no }}">
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-4 col-12">
                        <label for="inputGroupFileAddon03" class="form-label">Attachment</label>
                        <div class="input-group mb-3">
                            <a target="_blank" href="{{ asset('/order-attachments/') }}/{{ $good_receiving->attachment }}"
                                class="btn btn-outline-secondary" type="button" id="inputGroupFileAddon03">
                                <i class="bi bi-file-text"></i>
                            </a>
                            <input type="file" class="form-control" id="inputGroupFile03"
                                aria-describedby="inputGroupFileAddon03" aria-label="Upload">
                        </div>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-12 d-flex justify-content-between">
                        <h5>PRODUCT/MATERIAL DETAILS</h5>
                        <div>
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                data-bs-target="#exampleModalCenter">ADD PRODUCT (PO)</button>
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                data-bs-target="#exampleModal2">ADD PRODUCTS</button>
                        </div>
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
                                        <th>Required Date</th>
                                        <th>Part No.</th>
                                        <th>Part Name</th>
                                        <th>Unit</th>
                                        <th>Incoming Qty</th>
                                        <th>Received Qty</th>
                                        <th>Remarks</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($good_receiving_products as $good_receiving_product)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>
                                                <input type="date" class="form-control"
                                                    value="{{ $good_receiving_product->date }}"
                                                    name="products[{{ $loop->iteration }}][date]">
                                            </td>
                                            <td><input type="hidden" class="product_id"
                                                    value="{{ $good_receiving_product->product_id }}"
                                                    name="products[{{ $loop->iteration }}][product_id]">{{ $good_receiving_product->product->part_no }}
                                            </td>
                                            <td>{{ $good_receiving_product->product->part_name }}</td>
                                            <td>{{ $good_receiving_product->product->units->name ?? '' }}</td>
                                            <td><input type="number" readonly class="form-control qty"
                                                    value="{{ $good_receiving_product->incoming_qty }}"
                                                    name="products[{{ $loop->iteration }}][qty]">
                                            </td>
                                            <td><input type="number" class="form-control received_qty"
                                                    value="{{ $good_receiving_product->received_qty }}"
                                                    name="products[{{ $loop->iteration }}][received_qty]"></td>
                                            <td>
                                                <textarea class="form-control remarks" rows="1" name="products[{{ $loop->iteration }}][remarks]">{{ $good_receiving_product->remarks }}</textarea>
                                            </td>
                                            <td><button type="button" class="btn btn-danger btn-sm remove-product"><i
                                                        class="bi bi-trash"></i></button></td>
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
                    <div class="d-flex gap-2 justify-content-between col-12">
                        <a type="button" class="btn btn-info" href="{{ route('good_receiving.index') }}">
                            <i class="bi bi-arrow-left"></i> Back
                        </a>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
    {{-- PRODUCTS PO MODAL --}}
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
                                    <th>Incoming Qty</th>
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
    {{-- PRODUCTS MODAL --}}
    <div class="modal fade" id="exampleModal2" tabindex="-1" aria-labelledby="exampleModal2Title" aria-modal="true"
        role="dialog">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModal2Title">
                        PRODUCTS
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table table-bordered m-0 w-100" id="modalTable2">
                            <thead>
                                <tr>
                                    <th>
                                        
                                        <input type="checkbox" id="selectAll1" style="width: 22px; height: 22px;">
                                    </th>
                                    <th>Part No</th>
                                    <th>Part Name</th>
                                    <th>Unit</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($products as $product)
                                    <tr>
                                        <td><input type="checkbox" class="form-check-input product_id"
                                                value="{{ $product->id }}"></td>
                                        <td>
                                            {{ $product->part_no }}
                                        </td>
                                        <td>{{ $product->part_name }}</td>
                                        <td>{{ $product->units->name ?? '' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondry" data-bs-dismiss="modal">
                        CANCEL
                    </button>
                    <button type="button" class="btn btn-primary" onclick="add_product2()">
                        ADD
                    </button>
                </div>
            </div>
        </div>
    </div>
    <script>
        var modalTable;
        let productTable;
        let purchase_orders;
        let purchase_returns;
        let firstAttempt = true;
        $(document).ready(function() {
            $(document).on('click','#productButton',function(){
                $('#modalTable2 .product_id ,#modalTable2 #selectAll1').prop('checked',false);
            });
            $(document).on('click','#poButton',function(){
                $('#modalTable .product_id ,#modalTable #selectAll').prop('checked',false);
            });
            flatpickr("#date", {
                dateFormat: "d-m-Y",
                defaultDate:@json(\Carbon\Carbon::parse($good_receiving->date)->format('d-m-Y'))
            });
            modalTable = $('#modalTable').DataTable({
                "columnDefs": [
                    {
                        "targets": 0,
                        "orderable": false
                    }
                ]
            });
            productTable = $('#productTable').DataTable();

            purchase_orders = {!! json_encode($purchase_orders) !!};
            purchase_returns = {!! json_encode($purchase_returns) !!};

            if ($("#po_id").val() != null) {
                get_po();
            } else if ($("#pr_id").val() != null) {
                get_pr();
            }
            $('#order_supplier').on('change', function() {
                if ($('#po_id').val() == null || $('#po_id').val() == '') {
                    console.log($('#po_id').val())
                    $('#po_id').html('');
                    let selectedSupplierId = $(this).val();

                    let filteredPurchaseOrders = purchase_orders.filter(function(order) {
                        return order.supplier.id == selectedSupplierId;
                    });

                    $('#po_id').append($('<option>', {
                        value: '', // Supplier ID
                        text: 'Please Select' // Supplier Name
                    }));
                    filteredPurchaseOrders.forEach(function(order) {
                        $('#po_id').append($('<option>', {
                            value: order.id, // Supplier ID
                            text: order.ref_no // Supplier Name
                        }));
                    });
                }
            });

            $('#po_pr').on('change', function() {
                $('#pr_id').val('').trigger('change');
                $('#pr_id').val('').trigger('change');
                $('#po_id').html('');
                $('#order_supplier').html('');
                $('#order_supplier').append($('<option>', {
                    value: '', // Supplier ID
                    text: 'Please Select' // Supplier Name
                }));
                @json($suppliers).forEach(supplier => {
                    $('#order_supplier').append($('<option>', {
                        value: supplier.id, // Supplier ID
                        text: supplier.name // Supplier Name
                    }));
                });
                $('#po_id').append($('<option>', {
                    value: '', // Supplier ID
                    text: 'Please Select' // Supplier Name
                }));
                @json($purchase_orders).forEach(order => {
                    $('#po_id').append($('<option>', {
                        value: order.id, // Supplier ID
                        text: order.ref_no // Supplier Name
                    }));
                });
                if ($(this).is(':checked')) {
                    $('.purchase_return').removeClass('d-none');
                    $('.purchase_return_supplier').removeClass('d-none');
                    $('.purchase_return').find('.select2-container').addClass('w-100');
                    $('.purchase_order').addClass('d-none');
                    $('.purchase_order_supplier').addClass('d-none');
                } else {
                    $('.purchase_order').removeClass('d-none');
                    $('.purchase_order_supplier').removeClass('d-none');
                    $('.purchase_return').addClass('d-none');
                    $('.purchase_return_supplier').addClass('d-none');
                }
                modalTable.clear().draw();
                productTable.clear().draw();
            });
        });

        function get_po() {
            var poId = $("#po_id").val();
            var po = purchase_orders.find(p => p.id == poId);
            if (po) {
                if ($('#order_supplier').val() == null || $('#order_supplier').val() == '') {
                    console.log($('#order_supplier').val())
                    $('#order_supplier').html('');
                    // Assuming purchase_orders is an array of objects and each purchase order has a supplier relation
                    let uniqueSuppliers = [];

                    // Loop through purchase_orders to find unique suppliers
                    purchase_orders.forEach(p => {
                        // Check if the supplier ID is already in the uniqueSuppliers array
                        if (!uniqueSuppliers.find(s => s.id === p.supplier.id)) {
                            uniqueSuppliers.push({
                                id: p.supplier.id,
                                name: p.supplier.name
                            }); // Add supplier object if it's unique
                        }
                    });

                    // Now append the unique suppliers to the dropdown
                    let supplierDropdown = $('#order_supplier'); // Assuming #supplierDropdown is the ID of the dropdown
                    supplierDropdown.append($('<option>', {
                        value: '', // Supplier ID
                        text: 'Please Select' // Supplier Name
                    }));
                    uniqueSuppliers.forEach(supplier => {
                        supplierDropdown.append($('<option>', {
                            value: supplier.id, // Supplier ID
                            text: supplier.name // Supplier Name
                        }));
                    });
                }
                populateProductTable(po.purchase_order_detail);
            } else {
                productTable.clear().draw();
            }
        };

        function get_pr() {
            var prId = $("#pr_id").val();
            var pr = purchase_returns.find(p => p.id == prId);
            if (pr) {
                let supplier_name = pr.supplier ? pr.supplier.name : '';
                $('#supplier').val(supplier_name);
                if (!firstAttempt) {
                    populateProductTable(pr.purchase_return_detail);
                    firstAttempt = false;
                }
            } else {
                productTable.clear().draw();
            }
        };

        function populateProductTable(data) {
            productTable.clear();
            data.forEach((product, index) => {
                let qty = 0;
                let date = `<input type="date" class="form-control" name="products[${index}][date]">`;
                if ($('#po_pr').is(':checked')) {
                    date = '';
                    qty = product.return_qty;
                } else {
                    qty = product.qty;
                }
                let newRow = productTable.row.add([
                    productTable.rows().count() + 1,
                    date,
                    `<input type="hidden" name="products[${index}][product_id]" value="${product.product_id}">${product.product.part_no}`,
                    product.product.part_name,
                    product.product.units ? product.product.units.name : '',
                    `<input type="number" readonly class="form-control qty" name="products[${index}][qty]" value="${qty}">`,
                    `<input type="number" class="form-control received_qty" name="products[${index}][received_qty]" value="0">`,
                    `<textarea class="form-control remarks" rows="1" name="products[${index}][remarks]"></textarea>`,
                    `<button type="button" class="btn btn-danger btn-sm remove-product"><i class="bi bi-trash"></i></button>`
                ]).draw(false);
            });
        }

        function add_product() {
            modalTable.$('input:checked').each(function() {
                var row = $(this).closest('tr');
                var product_id = row.find('.product_id').val();
                var part_no = row.find('td:eq(1)').text();
                var part_name = row.find('td:eq(2)').text();
                var unit = row.find('td:eq(3)').text();
                var qty = row.find('td:eq(4)').text();

                let date = `<input type="date" class="form-control" name="products[${productTable.rows().count() + 1}][date]">`;
                if ($('#po_pr').is(':checked')) {
                    date = '';
                }

                // Add the row data to the main table
                productTable.row.add([
                    productTable.rows().count() + 1,
                    date,
                    `<input type="hidden" class="product_id" name="products[${productTable.rows().count() + 1}][product_id]" value="${product_id}">${part_no}`,
                    part_name,
                    unit,
                    `<input type="number" readonly class="form-control qty" name="products[${productTable.rows().count() + 1}][qty]" value="${qty}">`,
                    `<input type="number" class="form-control received_qty" name="products[${productTable.rows().count() + 1}][received_qty]" value="0">`,
                    `<textarea class="form-control remarks" rows="1" name="products[${productTable.rows().count() + 1}][remarks]"></textarea>`,
                    `<button type="button" class="btn btn-danger btn-sm remove-product"><i class="bi bi-trash"></i></button>`
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
            var incoming_qty = row.find('.qty').val();

            // Add the removed row back to the modal table
            modalTable.row.add([
                `<input class="form-check-input product_id" type="checkbox" value="${$(row).find('.product_id').val()}">`,
                part_no,
                part_name,
                unit,
                incoming_qty
            ]).draw(false);

            // Remove the row from the main table
            productTable.row(row).remove().draw();
            resetSerialNumbers(productTable);
        });

        function add_product2() {
            modalTable2.$('input:checked').each(function() {
                var row = $(this).closest('tr');
                var product_id = row.find('.product_id').val();
                var part_no = row.find('td:eq(1)').text();
                var part_name = row.find('td:eq(2)').text();
                var unit = row.find('td:eq(3)').text();

                let date = `<input type="date" class="form-control" name="products[${productTable.rows().count() + 1}][date]">`;
                if ($('#po_pr').is(':checked')) {
                    date = '';
                }

                // Add the row data to the main table
                productTable.row.add([
                    productTable.rows().count() + 1,
                    date,
                    `<input type="hidden" class="product_id" name="products[${productTable.rows().count() + 1}][product_id]" value="${product_id}">${part_no}`,
                    part_name,
                    unit,
                    `<input type="number" class="form-control qty" name="products[${productTable.rows().count() + 1}][qty]" value="0">`,
                    `<input type="number" class="form-control received_qty" name="products[${productTable.rows().count() + 1}][received_qty]" value="0">`,
                    `<textarea class="form-control remarks" rows="1" name="products[${productTable.rows().count() + 1}][remarks]"></textarea>`,
                    `<button type="button" class="btn btn-danger btn-sm remove-product2"><i class="bi bi-trash"></i></button>`
                ]).draw(false);

                // Remove the row from the modal table
                modalTable2.row(row).remove().draw();
            });

            // Uncheck all checkboxes
            $('#modalTable2 input:checked').prop('checked', false);

            // Hide the modal
            $('#exampleModal2').modal('hide');
        }

        $('#productTable tbody').on('click', 'button.remove-product2', function() {
            var row = $(this).closest('tr');
            var part_no = row.find('td:eq(1)').text();
            var part_name = row.find('td:eq(2)').text();
            var unit = row.find('td:eq(3)').text();

            // Add the removed row back to the modal table
            modalTable2.row.add([
                `<input class="form-check-input product_id" type="checkbox" value="${$(row).find('.product_id').val()}">`,
                part_no,
                part_name,
                unit
            ]).draw(false);

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

        $('#selectAll').on('change', function() {
            var closestTable = $(this).closest('table');
            var checkboxes = closestTable.find('.product_id');
            checkboxes.prop('checked', this.checked);
        });

        $(document).on('input', '.received_qty', function() {
            var incoming_qty = $(this).closest('tr').find('.qty').val();
            var qty = $(this).val();
            if (parseFloat(qty) > parseFloat(incoming_qty)) {
                $(this).val(incoming_qty);
            }
        });

        $('#myForm').on('submit', function(){
            $('.card-body').find('.table').DataTable().page.len(-1).draw();
        });
    </script>
@endsection
