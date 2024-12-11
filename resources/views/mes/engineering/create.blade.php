@extends('layouts.app')
@section('title')
    BOM CREATE
@endsection
@section('content')
    <style>
        #mainTable input {
            width: 100px;
        }
    </style>
    <div class="card">
        <form method="post" action="{{ route('bom.store') }}" enctype="multipart/form-data" id="myForm">
            @csrf
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="col-lg-3 col-sm-4 col-12 float-end">
                            <div class="mb-3">
                                <label for="created_by" class="form-label">Created By</label>
                                <input type="text" readonly name="created_by" id="created_by" class="form-control"
                                    value="{{ Auth::user()->user_name }}">
                            </div>
                        </div>
                        <div class="col-lg-3 col-sm-4 col-12">
                            <h5>PRODUCT INFORMATION</h5>
                        </div>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            @php
                                $rev_no = 1;
                            @endphp
                            <label for="rev_no" class="form-label">Revision No.</label>
                            <input type="text" readonly name="rev_no" value="{{ $rev_no }}" id="rev_no"
                                class="form-control">
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label for="ref_no" class="form-label">Ref No.</label>
                            <input type="text" name="ref_no" id="ref_no" class="form-control">
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label for="product_id" class="form-label">Part No.</label>
                            <select name="product_id" onchange="product_change()" id="product_id" class="form-select">
                                <option value="" disabled selected>Please Select</option>
                                @foreach ($products as $product)
                                    <option value="{{ $product->id }}">{{ $product->part_no }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label for="part_name" class="form-label">Part Name:</label>
                            <input type="text" readonly id="part_name" class="form-control"
                                value="{{ old('part_name') }}">
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label for="customer_name" class="form-label">Customer Name:</label>
                            <input type="text" readonly id="customer_name" class="form-control"
                                value="{{ old('customer_name') }}">
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label for="customer_product_code" class="form-label">Customer Product Code:</label>
                            <input type="text" readonly id="customer_product_code" class="form-control"
                                value="{{ old('customer_product_code') }}">
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label for="model" class="form-label">Model:</label>
                            <input type="text" readonly name="model" id="model" class="form-control"
                                value="{{ old('model') }}">
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label for="variance" class="form-label">Variance:</label>
                            <input type="text" readonly name="variance" id="variance" class="form-control"
                                value="{{ old('variance') }}">
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label for="part_weight" class="form-label">Part Weight:</label>
                            <input type="text" readonly name="part_weight" id="part_weight" class="form-control"
                                value="{{ old('part_weight') }}">
                        </div>
                    </div>

                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label for="created_date" class="form-label">Created Date</label>
                            <input type="date" name="created_date" id="created_date" class="form-control"
                                value="{{ date('Y-m-d') }}">
                        </div>
                    </div>
                    <div class="col-lg-6 col-sm-4 col-12">
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" name="description" id="description" rows="5"></textarea>
                        </div>
                    </div>
                    <div class="col-lg-4 col-sm-4 col-12">
                        <div class="mb-3">
                            <label for="inputGroupFile02" class="form-label">Attachment 1:</label>
                            <input type="file" class="form-control" name="attachment1" id="inputGroupFile02">
                        </div>
                    </div>
                    <div class="col-lg-4 col-sm-4 col-12">
                        <div class="mb-3">
                            <label for="inputGroupFile02" class="form-label">Attachment 2:</label>
                            <input type="file" class="form-control" name="attachment2" id="inputGroupFile02">
                        </div>
                    </div>
                    <div class="col-lg-4 col-sm-4 col-12">
                        <div class="mb-3">
                            <label for="inputGroupFile02" class="form-label">Attachment 3:</label>
                            <input type="file" class="form-control" name="attachment3" id="inputGroupFile02">
                        </div>
                    </div>
                </div>
                <br>
                {{-- Material and Purchase Part --}}
                <div class="row">
                    <div class="col-12 d-flex justify-content-between">
                        <h5>Material and Purchase Part</h5>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                            data-bs-target="#materialandpruchasepart">Add Material</button>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="table-responsive">
                        <table class="table table-bordered m-0" id="material_pruchase_part_Table">
                            <thead>
                                <tr>
                                    <th>Sr No</th>
                                    <th>Part No</th>
                                    <th>Part Name</th>
                                    <th>Type of Product</th>
                                    <th>Unit</th>
                                    <th>Qty</th>
                                    <th>Remarks</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
                <br>
                {{-- Crushing Material --}}
                <div class="row">
                    <div class="col-12 d-flex justify-content-between">
                        <h5>Crushing Material</h5>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                            data-bs-target="#crushing_material_Modal">Add Material</button>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="table-responsive">
                        <table class="table table-bordered m-0" id="crushing_Table">
                            <thead>
                                <tr>
                                    <th>Sr No</th>
                                    <th>Part No</th>
                                    <th>Part Name</th>
                                    <th>Type of Product</th>
                                    <th>Unit</th>
                                    <th>Remarks</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
                <br>
                {{-- Sub Part --}}
                <div class="row">
                    <div class="col-12 d-flex justify-content-between">
                        <h5>Sub Part</h5>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                            data-bs-target="#subpart_Modal">Add Subpart</button>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="table-responsive">
                        <table class="table table-bordered m-0" id="subpart_Table">
                            <thead>
                                <tr>
                                    <th>Sr No</th>
                                    <th>Part No</th>
                                    <th>Part Name</th>
                                    <th>Type of Product</th>
                                    <th>Unit</th>
                                    <th>QTY</th>
                                    <th>Remarks</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
                <br>
                {{-- Process --}}
                <div class="row">
                    <div class="col-12 d-flex justify-content-between">
                        <h5>Process</h5>
                        <button type="button" class="btn btn-primary" id="addRow">+ Add Row</button>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="table-responsive">
                        <table class="table table-bordered m-0" id="process_Table">
                            <thead>
                                <tr>
                                    <th>Sr No</th>
                                    <th>Process</th>
                                    <th>Process Number</th>
                                    <th>Raw Part</th>
                                    <th>Sub Part</th>
                                    <th>Supplier</th>
                                    <th>Machine Tonnage</th>
                                    <th>Cavity</th>
                                    <th>CT</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
                <br>

                <div class="card-footer">
                    <div class="row">
                        <div class="d-flex gap-2 justify-content-between col-12">
                            <a type="button" class="btn btn-info" href="{{ route('bom') }}">
                                <i class="bi bi-arrow-left"></i> Back
                            </a>
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </div>
                </div>
            </div>
            {{-- Material and Purchase MODAL --}}
            <div class="modal fade" id="materialandpruchasepart" tabindex="-1"
                aria-labelledby="materialandpruchasepartTitle" aria-modal="true" role="dialog">
                <div class="modal-dialog modal-dialog-centered modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="materialandpruchasepartTitle">
                                Material and Purchase Part
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="table-responsive">
                                <table class="table table-bordered m-0 w-100" id="material_pruchase_part_modalTable">
                                    <thead>
                                        <tr>
                                            <th>
                                                <input type="checkbox" id="selectAll" style="width: 22px; height: 22px;">
                                            </th>
                                            <th>Part No</th>
                                            <th>Part Name</th>
                                            <th>Unit</th>
                                            <th>Model</th>
                                            <th>Variance</th>
                                            <th>Type of Product</th>
                                            <th>Category</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($products as $product)
                                            <tr data-product-id="{{ $product->id }}">
                                                <td>
                                                    <input class="form-check-input product_id" type="checkbox"
                                                        id="inlineCheckbox1" value="{{ $product->id }}">
                                                </td>
                                                <td>
                                                    {{ $product->part_no }}
                                                </td>
                                                <td>
                                                    {{ $product->part_name }}
                                                </td>
                                                <td>
                                                    {{ $product->units->name ?? '' }}
                                                </td>
                                                <td>
                                                    {{ $product->model }}
                                                </td>
                                                <td>
                                                    {{ $product->variance }}
                                                </td>
                                                <td>
                                                    {{ $product->type_of_products->type ?? '' }}
                                                </td>
                                                <td>
                                                    {{ $product->categories->name ?? '' }}
                                                </td>
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
                            <button type="button" class="btn btn-primary" onclick="add_product1()">
                                ADD
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- CRUSHING MODAL --}}
            <div class="modal fade" id="crushing_material_Modal" tabindex="-1"
                aria-labelledby="crushing_material_ModalTitle" aria-modal="true" role="dialog">
                <div class="modal-dialog modal-dialog-centered modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="crushing_material_ModalTitle">
                                Crushing Material
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="table-responsive">
                                <table class="table table-bordered m-0 w-100" id="crushing_material_modalTable">
                                    <thead>
                                        <tr>
                                            <th>
                                                <input type="checkbox" id="selectAll1"
                                                    style="width: 22px; height: 22px;">
                                            </th>
                                            <th>Part No</th>
                                            <th>Part Name</th>
                                            <th>Unit</th>
                                            <th>Model</th>
                                            <th>Variance</th>
                                            <th>Type of Product</th>
                                            <th>Category</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($products as $product)
                                            <tr data-product-id="{{ $product->id }}">
                                                <td>
                                                    <input class="form-check-input product_id" type="checkbox"
                                                        id="inlineCheckbox1" value="{{ $product->id }}">
                                                </td>
                                                <td>
                                                    {{ $product->part_no }}
                                                </td>
                                                <td>
                                                    {{ $product->part_name }}
                                                </td>
                                                <td>
                                                    {{ $product->units->name ?? '' }}
                                                </td>
                                                <td>
                                                    {{ $product->model }}
                                                </td>
                                                <td>
                                                    {{ $product->variance }}
                                                </td>
                                                <td>
                                                    {{ $product->type_of_products->type ?? '' }}
                                                </td>
                                                <td>
                                                    {{ $product->categories->name ?? '' }}
                                                </td>
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

            {{-- SUBPART MODAL --}}
            <div class="modal fade" id="subpart_Modal" tabindex="-1" aria-labelledby="subpart_ModalTitle"
                aria-modal="true" role="dialog">
                <div class="modal-dialog modal-dialog-centered modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="subpart_ModalTitle">
                                Subpart Material
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="table-responsive">
                                <table class="table table-bordered m-0 w-100" id="subpart_modalTable">
                                    <thead>
                                        <tr>
                                            <th>
                                                <input type="checkbox" id="selectAll2"
                                                    style="width: 22px; height: 22px;">
                                            </th>
                                            <th>Part No</th>
                                            <th>Part Name</th>
                                            <th>Unit</th>
                                            <th>Model</th>
                                            <th>Variance</th>
                                            <th>Type of Product</th>
                                            <th>Category</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($products as $product)
                                            @if ($product->have_bom == 1)
                                                <tr data-product-id="{{ $product->id }}">
                                                    <td>
                                                        <input class="form-check-input product_id" type="checkbox"
                                                            id="inlineCheckbox1" value="{{ $product->id }}">
                                                    </td>
                                                    <td>
                                                        {{ $product->part_no }}
                                                    </td>
                                                    <td>
                                                        {{ $product->part_name }}
                                                    </td>
                                                    <td>
                                                        {{ $product->units->name ?? '' }}
                                                    </td>
                                                    <td>
                                                        {{ $product->model }}
                                                    </td>
                                                    <td>
                                                        {{ $product->variance }}
                                                    </td>
                                                    <td>
                                                        {{ $product->type_of_products->type ?? '' }}
                                                    </td>
                                                    <td>
                                                        {{ $product->categories->name ?? '' }}
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-secondry" data-bs-dismiss="modal">
                                CANCEL
                            </button>
                            <button type="button" class="btn btn-primary" onclick="add_product3()">
                                ADD
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
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
        var products = {!! json_encode($products) !!};
        let table;

        $(document).ready(function() {
            table = $('#process_Table').DataTable();
        });

        function product_change() {
            var productId = $("#product_id").val();
            var product = products.find(p => p.id == productId);
            if (product) {
                $('#part_name').val(product.part_name);
                $('#model').val(product.model);
                $('#variance').val(product.variance);
                $('#type_of_product').val(product.type_of_products ? product.type_of_products.type : '');
                $('#category').val(product.categories ? product.categories.name : '');
                $('#unit').val(product.units ? product.units.name : '');
                $('#customer_name').val(product.customers ? product.customers.name : '');
                $('#part_weight').val(product.part_weight);
                $('#customer_product_code').val(product.customer_product_code);

            } else {
                $('#part_name').val('');
                $('#model').val('');
                $('#variance').val('');
                $('#type_of_product').val('');
                $('#category').val('');
                $('#unit').val('');
                $('#customer_name').val('');
                $('#customer_product_code').val('');
                $('#part_weight').val('');
            }
        };

        // material_pruchase_part_script

        let material_pruchase_part_modalTable;
        let material_pruchase_part_Table;
        $(document).ready(function() {
            material_pruchase_part_modalTable = $('#material_pruchase_part_modalTable').DataTable({
                "columnDefs": [{
                    "targets": 0,
                    "orderable": false
                }]
            });
            material_pruchase_part_Table = $('#material_pruchase_part_Table').DataTable();


            // hide product selected on the select box
            // Event listeners for when the modals are shown
            $('#materialandpruchasepart').on('show.bs.modal', function(e) {
                // Get the selected product id from the select box
                var selectedProductId = $('#product_id').val();

                // Loop through each row in the modal table
                $('#material_pruchase_part_modalTable tbody tr').each(function() {
                    var row = $(this);
                    var productId = row.data('product-id');

                    // If the product id matches the selected product id, hide the row
                    if (productId == selectedProductId) {
                        row.hide();
                    } else {
                        row.show();
                    }
                });
            });
            $('#crushing_material_Modal').on('show.bs.modal', function(e) {
                // Get the selected product id from the select box
                var selectedProductId = $('#product_id').val();

                // Loop through each row in the modal table
                $('#crushing_material_modalTable tbody tr').each(function() {
                    var row = $(this);
                    var productId = row.data('product-id');

                    // If the product id matches the selected product id, hide the row
                    if (productId == selectedProductId) {
                        row.hide();
                    } else {
                        row.show();
                    }
                });
            });
            $('#subpart_Modal').on('show.bs.modal', function(e) {
                // Get the selected product id from the select box
                var selectedProductId = $('#product_id').val();

                // Loop through each row in the modal table
                $('#subpart_modalTable tbody tr').each(function() {
                    var row = $(this);
                    var productId = row.data('product-id');

                    // If the product id matches the selected product id, hide the row
                    if (productId == selectedProductId) {
                        row.hide();
                    } else {
                        row.show();
                    }
                });
            });
        });

        function add_product1() {
            material_pruchase_part_modalTable.$('input:checked').each(function() {
                var row = $(this).closest('tr');
                var rowData = material_pruchase_part_modalTable.row(row).data();
                var productId = $(this).val();

                // Add the row data to the main table
                material_pruchase_part_Table.row.add([
                    material_pruchase_part_Table.rows().count() + 1,
                    rowData[1],
                    rowData[2],
                    rowData[6],
                    rowData[3],
                    `<input type="number" name="material_pruchase_part[${material_pruchase_part_Table.rows().count() + 1}][qty]" class="qty form-control" >
                    <input type="hidden" name="material_pruchase_part[${material_pruchase_part_Table.rows().count() + 1}][product_id]" class="product_id" value="${productId}">
                    <input type="hidden" class="model" value="${rowData[4]}">
                    <input type="hidden" class="variance" value="${rowData[5]}">
                    <input type="hidden" class="category" value="${rowData[7]}">`,
                    `<button type="button" class="btn btn-sm add-remarks btn-danger">Add</button><input type="hidden" name="material_pruchase_part[${material_pruchase_part_Table.rows().count() + 1}][remarks]" class="remarks form-control">`,
                    `<button type="button" class="btn btn-danger btn-sm remove-product"><i class="bi bi-trash"></i></button>`

                ]).draw(false);

                // Remove the row from the modal table
                material_pruchase_part_modalTable.row(row).remove().draw();

                if (table.rows().count() > 0) {
                    table.rows().every(function() {
                        var data = this.data();

                        // Find the actual select element inside the cell
                        var cellElement = $(this.node()).find('.raw_part_ids');

                        // Get the new subpart products and append them to the select options
                        var Purchase_Part = getMaterialPurchaseProducts();
                        cellElement.empty(); // Clear existing options

                        // Loop through Purchase_Part and append each option
                        Purchase_Part.forEach(function(product) {
                            cellElement.append(
                                `<option value="${product.id}">${product.name}</option>`);
                        });

                        // Reinitialize select2 for the updated select
                        cellElement.select2();
                    });
                }
            });

            // Uncheck all checkboxes
            $('#material_pruchase_part_modalTable input:checked').prop('checked', false);

            // Add event listener to remove buttons


            // Hide the modal
            $('#materialandpruchasepart').modal('hide');
        }
        $('#material_pruchase_part_Table tbody').on('click', 'button.remove-product', function() {
            var row = $(this).closest('tr');
            var rowData = material_pruchase_part_Table.row(row).data();

            // Add the removed row back to the modal table
            material_pruchase_part_modalTable.row.add([
                `<input class="form-check-input product_id" type="checkbox" value="${$(row).find('.product_id').val()}">`,
                rowData[1],
                rowData[2],
                rowData[4],
                $(row).find('.model').val(),
                $(row).find('.variance').val(),
                rowData[3],
                $(row).find('.category').val()
            ]).draw(false);

            // Remove the row from the main table
            material_pruchase_part_Table.row(row).remove().draw();
            resetSerialNumbersM(material_pruchase_part_Table);
            if (table.rows().count() > 0) {
                table.rows().every(function() {
                    var data = this.data();

                    // Find the actual select element inside the cell
                    var cellElement = $(this.node()).find('.raw_part_ids');

                    // Get the new subpart products and append them to the select options
                    var Purchase_Part = getMaterialPurchaseProducts();
                    cellElement.empty(); // Clear existing options

                    // Loop through Purchase_Part and append each option
                    Purchase_Part.forEach(function(product) {
                        cellElement.append(
                        `<option value="${product.id}">${product.name}</option>`);
                    });

                    // Reinitialize select2 for the updated select
                    cellElement.select2();
                });
            }
        });

        function resetSerialNumbersM() {
            if ($('#material_pruchase_part_Table tbody tr:first').find('td:first').text() != 'No data available in table') {
                $('#material_pruchase_part_Table tbody tr').each(function(index) {
                    $(this).find('td:first').text(index + 1);
                });
            }
        }

        $('#selectAll').on('change', function() {
            var closestTable = $(this).closest('table');
            var checkboxes = closestTable.find('.product_id');
            checkboxes.prop('checked', this.checked);
        });
        // crushing_material_script

        let crushing_material_modalTable;
        let crushing_Table;
        $(document).ready(function() {
            crushing_material_modalTable = $('#crushing_material_modalTable').DataTable({

                "columnDefs": [{
                    "targets": 0,
                    "orderable": false
                }]
            });
            crushing_Table = $('#crushing_Table').DataTable();
        });

        function add_product2() {
            crushing_material_modalTable.$('input:checked').each(function() {
                var row = $(this).closest('tr');
                var rowData = crushing_material_modalTable.row(row).data();
                var productId = $(this).val();

                // Add the row data to the main table
                crushing_Table.row.add([
                    crushing_Table.rows().count() + 1,
                    rowData[1],
                    rowData[2],
                    rowData[6],
                    rowData[3],
                    `<input type="hidden" class="model" value="${rowData[4]}">
                    <input type="hidden" class="variance" value="${rowData[5]}">
                    <input type="hidden" class="category" value="${rowData[7]}">
                    <button type="button" class="btn btn-sm add-remarks btn-danger">Add</button><input type="hidden" name="crushing_material[${crushing_Table.rows().count() + 1}][product_id]" value="${productId}">
                    <input type="hidden" name="crushing_material[${crushing_Table.rows().count() + 1}][remarks]" class="remarks form-control">`,
                    `<button type="button" class="btn btn-danger btn-sm remove-product"><i class="bi bi-trash"></i></button>`

                ]).draw(false);

                // Remove the row from the modal table
                crushing_material_modalTable.row(row).remove().draw();
            });

            // Uncheck all checkboxes
            $('#crushing_material_modalTable input:checked').prop('checked', false);

            // Add event listener to remove buttons
            $('#crushing_Table tbody').on('click', 'button.remove-product', function() {
                var row = $(this).closest('tr');
                var rowData = crushing_Table.row(row).data();

                // Add the removed row back to the modal table
                crushing_material_modalTable.row.add([
                    `<input class="form-check-input product_id" type="checkbox" value="${$(row).find('.product_id').val()}">`,
                    rowData[1],
                    rowData[2],
                    rowData[4],
                    $(row).find('.model').val(),
                    $(row).find('.variance').val(),
                    rowData[3],
                    $(row).find('.category').val()
                ]).draw(false);

                // Remove the row from the main table
                crushing_Table.row(row).remove().draw();
                resetSerialNumbersC(crushing_Table);
            });

            // Hide the modal
            $('#crushing_material_Modal').modal('hide');
        }

        function resetSerialNumbersC() {
            if ($('#crushing_Table tbody tr:first').find('td:first').text() != 'No data available in table') {
                $('#crushing_Table tbody tr').each(function(index) {
                    $(this).find('td:first').text(index + 1);
                });
            }
        }

        $('#selectAll1').on('change', function() {
            var closestTable = $(this).closest('table');
            var checkboxes = closestTable.find('.product_id');
            checkboxes.prop('checked', this.checked);
        });

        // Sub_part_script

        let subpart_modalTable;
        let subpart_Table;
        $(document).ready(function() {
            subpart_modalTable = $('#subpart_modalTable').DataTable({
                "columnDefs": [{
                    "targets": 0,
                    "orderable": false
                }]
            });
            subpart_Table = $('#subpart_Table').DataTable();
        });

        function add_product3() {
            subpart_modalTable.$('input:checked').each(function() {
                var row = $(this).closest('tr');
                var rowData = subpart_modalTable.row(row).data();
                var productId = $(this).val();

                // Add the row data to the main table
                subpart_Table.row.add([
                    subpart_Table.rows().count() + 1,
                    rowData[1],
                    rowData[2],
                    rowData[6],
                    rowData[3],
                    `<input type="number" name="sub_part[${subpart_Table.rows().count() + 1}][qty]" class="qty form-control" >
                    <input type="hidden" name="sub_part[${subpart_Table.rows().count() + 1}][product_id]" class="product_id" value="${productId}">
                    <input type="hidden" class="model" value="${rowData[4]}">
                    <input type="hidden" class="variance" value="${rowData[5]}">
                    <input type="hidden" class="category" value="${rowData[7]}">`,
                    `<button type="button" class="btn btn-sm add-remarks btn-danger">Add</button><input type="hidden" name="sub_part[${subpart_Table.rows().count() + 1}][remarks]" class="remarks form-control">`,
                    `<button type="button" class="btn btn-danger btn-sm remove-product"><i class="bi bi-trash"></i></button>`

                ]).draw(false).node();

                // Remove the row from the modal table
                subpart_modalTable.row(row).remove().draw();

                if (table.rows().count() > 0) {
                    table.rows().every(function() {
                        var data = this.data();

                        // Find the actual select element inside the cell
                        var cellElement = $(this.node()).find('.sub_part_ids');

                        // Get the new subpart products and append them to the select options
                        var sub_products = getMaterialSubPart();
                        cellElement.empty(); // Clear existing options

                        // Loop through sub_products and append each option
                        sub_products.forEach(function(product) {
                            cellElement.append(
                                `<option value="${product.id}">${product.name}</option>`);
                        });

                        // Reinitialize select2 for the updated select
                        cellElement.select2();
                    });
                }


            });


            // Uncheck all checkboxes
            $('#subpart_modalTable input:checked').prop('checked', false);

            // Add event listener to remove buttons
            $('#subpart_Table tbody').on('click', 'button.remove-product', function() {
                var row = $(this).closest('tr');
                var rowData = subpart_Table.row(row).data();

                // Add the removed row back to the modal table
                subpart_modalTable.row.add([
                    `<input class="form-check-input product_id" type="checkbox" value="${$(row).find('.product_id').val()}">`,
                    rowData[1],
                    rowData[2],
                    rowData[4],
                    $(row).find('.model').val(),
                    $(row).find('.variance').val(),
                    rowData[3],
                    $(row).find('.category').val()
                ]).draw(false);

                // Remove the row from the main table
                subpart_Table.row(row).remove().draw();
                resetSerialNumbersS(subpart_Table);
                if (table.rows().count() > 0) {
                    table.rows().every(function() {
                        var data = this.data();

                        // Find the actual select element inside the cell
                        var cellElement = $(this.node()).find('.sub_part_ids');

                        // Get the new subpart products and append them to the select options
                        var sub_products = getMaterialSubPart();
                        cellElement.empty(); // Clear existing options

                        // Loop through sub_products and append each option
                        sub_products.forEach(function(product) {
                            cellElement.append(
                                `<option value="${product.id}">${product.name}</option>`);
                        });

                        // Reinitialize select2 for the updated select
                        cellElement.select2();
                    });
                }
            });

            // Hide the modal
            $('#subpart_Modal').modal('hide');

            function resetSerialNumbersS() {
                if ($('#subpart_Table tbody tr:first').find('td:first').text() != 'No data available in table') {
                    $('#subpart_Table tbody tr').each(function(index) {
                        $(this).find('td:first').text(index + 1);
                    });
                }
            }
        }

        $('#selectAll2').on('change', function() {
            var closestTable = $(this).closest('table');
            var checkboxes = closestTable.find('.product_id');
            checkboxes.prop('checked', this.checked);
        });


        // Add_row_script


        function getMaterialPurchaseProducts() {
            var products = [];
            material_pruchase_part_Table.rows().every(function() {
                var data = this.data();
                var tempDiv = $('<div>').html(data[5]);
                var productId = tempDiv.find('.product_id').val();
                products.push({
                    id: productId,
                    name: data[1] // Assuming data[1] contains the product name
                });
            });
            return products;
        }

        function getMaterialSubPart() {
            var products = [];
            subpart_Table.rows().every(function() {
                var data = this.data();
                var tempDiv = $('<div>').html(data[5]);
                var productId = tempDiv.find('.product_id').val();
                products.push({
                    id: productId,
                    name: data[1] // Assuming data[1] contains the product name
                });
            });
            return products;
        }
        $(document).ready(function() {
            $('#addRow').on('click', function() {
                var raw_products = getMaterialPurchaseProducts();
                var sub_products = getMaterialSubPart();
                var newRow = table.row.add([
                    table.rows().count() + 1,
                    `<select class="form-select" name="process[${table.rows().count() + 1}][process_id]">
                        @foreach ($processes as $process)
                            <option value="{{ $process->id }}">{{ $process->name }}</option>
                        @endforeach
                    </select>`,
                    `<input type="text" class="form-control" name="process[${table.rows().count() + 1}][process_no]">`,
                    `<select class="form-select raw_part_ids" name="process[${table.rows().count() + 1}][raw_part_ids][]" multiple>
                        ${raw_products.map(product => `<option value="${product.id}">${product.name}</option>`).join('')}
                    </select>`,
                    // Dynamically populate sub_part_ids with products from material_pruchase_part_Table
                    `<select class="form-select sub_part_ids" name="process[${table.rows().count() + 1}][sub_part_ids][]" multiple>
                        ${sub_products.map(product => `<option value="${product.id}">${product.name}</option>`).join('')}
                    </select>`,
                    `<select class="form-select" name="process[${table.rows().count() + 1}][supplier_id]">
                        @foreach ($suppliers as $supplier)
                            <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                        @endforeach
                    </select>`,
                    `<select class="form-select" name="process[${table.rows().count() + 1}][machine_tonnage_id]">
                        @foreach ($machine_tonnages as $machine_tonnage)
                            <option value="{{ $machine_tonnage->id }}">{{ $machine_tonnage->tonnage }}</option>
                        @endforeach
                    </select>`,
                    `<input type="text" class="form-control" name="process[${table.rows().count() + 1}][cavity]">`,
                    `<input type="text" class="form-control" name="process[${table.rows().count() + 1}][ct]">`,
                    '<button type="button" class="btn btn-sm btn-danger remove-row"><i class="bi bi-trash"></i></button>'
                ]).draw(false).node();
                $(newRow).find('.raw_part_ids').select2();
                $(newRow).find('.sub_part_ids').select2();
            });

            $('#process_Table tbody').on('click', '.remove-row', function() {
                table.row($(this).parents('tr')).remove().draw();
                resetSerialNumbersP();
            });

            function resetSerialNumbersP() {
                if ($('#process_Table tbody tr:first').find('td:first').text() != 'No data available in table') {
                    $('#process_Table tbody tr').each(function(index) {
                        $(this).find('td:first').text(index + 1);
                    });
                }
            }
        });

        // material pruchase part Remarks
        $('#material_pruchase_part_Table tbody').on('click', '.add-remarks', function() {
            selectedRow = material_pruchase_part_Table.row($(this).closest('tr'));
            $('#remarksText').val($(this).closest('tr').find('.remarks').val());
            $('#remarksModal').modal('show');
        });

        $('#saveRemarks').on('click', function() {
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

        // crushing Table Remarks
        $('#crushing_Table tbody').on('click', '.add-remarks', function() {
            selectedRow = crushing_Table.row($(this).closest('tr'));
            $('#remarksText').val($(this).closest('tr').find('.remarks').val());
            $('#remarksModal').modal('show');
        });

        $('#saveRemarks').on('click', function() {
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

        // subpart Table Remarks
        $('#subpart_Table tbody').on('click', '.add-remarks', function() {
            selectedRow = subpart_Table.row($(this).closest('tr'));
            $('#remarksText').val($(this).closest('tr').find('.remarks').val());
            $('#remarksModal').modal('show');
        });

        $('#saveRemarks').on('click', function() {
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

        $('#myForm').on('submit', function() {
            $('.card-body').find('.table').DataTable().page.len(-1).draw();
        });
    </script>
@endsection