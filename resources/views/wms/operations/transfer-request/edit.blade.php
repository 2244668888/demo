@extends('layouts.app')
@section('title')
    TRANSFER REQUEST EDIT
@endsection
@section('content')
    <style>
        #productTable input {
            width: 130px;
        }
    </style>
    <form method="post" action="{{ route('transfer_request.update', $tr->id) }}" enctype="multipart/form-data" id="myForm">
        @csrf
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <h5>TRANSFER REQUEST DETAILS</h5>
                </div>
                <br>
                <div class="row">
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label for="ref_no" class="form-label">Tr No.</label>
                            <input type="text" readonly value="{{ $tr->ref_no }}" name="ref_no" id="ref_no"
                                class="form-control">
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label for="request_date" class="form-label">Request Date</label>
                            <input type="date" value="{{ $tr->request_date }}" name="request_date" id="request_date"
                                class="form-control">
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label for="request_from" class="form-label">Request From</label>
                            <select name="request_from" id="request_from" class="form-select">
                                <option value="" selected disabled>Please Select</option>
                                @foreach ($depts as $dept)
                                    <option value="{{ $dept->id }}" @selected($tr->request_from == $dept->id)>
                                        {{ $dept->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label for="request_to" class="form-label">Request To</label>
                            <select name="request_to" id="request_to" class="form-select">
                                <option value="" selected disabled>Please Select</option>
                                @foreach ($depts as $dept)
                                    <option value="{{ $dept->id }}" @selected($tr->request_to == $dept->id)>
                                        {{ $dept->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-12 col-sm-12 col-12">
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea name="description" id="description" rows="4" cols="4" class="form-control">{{ $tr->description }}</textarea>
                        </div>
                    </div>
                </div>
                <br>
                <div class="row">
                    <h5>MATERIAL REQUISITION DETAILS (If Any)</h5>
                </div>
                <br>
                <div class="row">
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label for="mrf_no" class="form-label">MRF No.</label>
                            <select name="mrf_no" id="mrf_no" class="form-select" onchange="change_mrf()">
                                <option value="" selected disabled>Please Select</option>
                                <option value="manual" @selected($tr->mrf_no == 'manual')>Manual</option>
                                @foreach ($material_requisitions as $material_requisition)
                                    <option value="{{ $material_requisition->id }}" @selected($tr->mrf_no == $material_requisition->id)>
                                        {{ $material_requisition->ref_no }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label for="shift" class="form-label">Shift</label>
                            <input type="text" readonly value="{{ $tr->shift }}" name="shift" id="shift"
                                class="form-control">
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label for="machine" class="form-label">Machine</label>
                            <input type="text" readonly
                                value="@foreach ($machines as $machine) @if ($machine->id == $tr->machine){{ $machine->name }}@endif @endforeach"
                                id="machine_name" class="form-control">
                            <input type="hidden" value="{{ $tr->machine_id }}" name="machine_id" id="machine_id"
                                class="form-control">
                        </div>
                    </div>
                </div>
                <br>

                <div class="row">
                    <div class="col-12 d-flex justify-content-between">
                        <h5>PRODUCT/MATERIAL DETAILS</h5>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                            data-bs-target="#product_modal">ADD PRODUCTS</button>
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
                                        <th>Part No.</th>
                                        <th>Part Name</th>
                                        <th>Type</th>
                                        <th>Model</th>
                                        <th>Variance</th>
                                        <th>Unit</th>
                                        <th>Transfer Qty</th>
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
                        <a type="button" class="btn btn-info" href="{{ route('transfer_request.index') }}">
                            <i class="bi bi-arrow-left"></i> Back
                        </a>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
    {{-- PRODUCTS MODAL --}}
    <div class="modal fade" id="product_modal" tabindex="-1" aria-labelledby="exampleModalCenterTitle"
        aria-modal="true" role="dialog">
        <div class="modal-dialog modal-dialog-centered modal-xl">
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
                                    <th>Select</th>
                                    <th>Part No</th>
                                    <th>Part Name</th>
                                    <th>Unit</th>
                                    <th>Model</th>
                                    <th>Variance</th>
                                    <th>Type of Product</th>
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
        var material_requisitions = {!! json_encode($material_requisitions) !!};
        var trds = {!! json_encode($trds) !!};
        let productTable;
        let modalTable;

        function change_mrf() {
            var mrf_id = $("#mrf_no").val();
            if (mrf_id == 'manual') {
                $('#shift').val('');
                $('#machine_id').val('');
                $('#machine_name').val('');
                showAllProducts();
            }
            var material_requisition = material_requisitions.find(p => p.id == mrf_id);
            if (material_requisition) {
                $("#shift").val(material_requisition.shift);
                $('#machine_id').val(material_requisition.machine);
                $('#machine_name').val(material_requisition.machines.name);
                // Fetch related products based on the selected MRF ID
                $.ajax({
                    url: '{{ route('get-products-by-mrf', ':mrf_id') }}'.replace(':mrf_id', mrf_id),
                    method: 'GET',
                    success: function(response) {
                        // Clear the DataTable using its API
                        modalTable.clear().draw();
                        console.log(response.products);

                        response.products.forEach(function(product) {
                            // Add a new row to the DataTable using row.add()
                            modalTable.row.add([
                                `<input class="form-check-input product_id" type="checkbox" value="${product.id}">`,
                                product.part_no,
                                product.part_name,
                                product.unit_name,
                                product.model,
                                product.variance,
                                product.type
                            ]).draw(
                            false); // Use .draw(false) to avoid re-drawing the entire table for each row
                        });

                        // Optional: Re-draw the table at the end
                        modalTable.columns.adjust().draw(); // Adjust column sizes and redraw the table

                        // Now that the DataTable is updated, process the rows
                        updateProductTableAfterMRFChange();
                    }
                });

            } else {
                $('#shift').val('');
                $('#machine_id').val('');
                $('#machine_name').val('');
            }
        }

        function updateProductTableAfterMRFChange() {
            // Iterate over each product_id checkbox in the modal table
            modalTable.$('.product_id').each(function() {
                if (trds.find(detail => detail.product_id == $(this).val())) {
                    var row = $(this).closest('tr');
                    var rowData = modalTable.row(row).data();
                    var productId = $(this).val();
                    var qty = 0;
                    var remarks = '';
                    var matchedDetail = trds.find(detail => detail.product_id == productId);

                    if (matchedDetail) {
                        qty = matchedDetail.request_qty;
                        remarks = matchedDetail.request_remarks;
                    }

                    var remarks_button = (remarks == '' || remarks == null) ?
                        `<button type="button" class="btn btn-sm add-remarks btn-danger">Add</button>` :
                        `<button type="button" class="btn btn-sm add-remarks btn-success">Edit</button>`;

                    // Add the row data to the main table if not already added
                    if ($('#productTable tbody input[value="' + productId + '"]').length == 0) {
                        productTable.row.add([
                            productTable.rows().count() + 1,
                            rowData[1] +
                            `<input type="hidden" name="product[${productTable.rows().count() + 1}][product_id]" class="product_id form-control" value="${productId}">`,
                            rowData[2],
                            rowData[6],
                            rowData[4],
                            rowData[5],
                            rowData[3],
                            `<input name="product[${productTable.rows().count() + 1}][req_qty]" class="qty form-control" value="${qty}"> <input type="hidden" class="variance form-control" value="${rowData[5]}">`,
                            `${remarks_button}<input type="hidden" name="product[${productTable.rows().count() + 1}][remarks]" class="remarks form-control" value="${remarks}">`,
                            `<button type="button" class="btn btn-danger btn-sm remove-product"><i class="bi bi-trash"></i></button>`
                        ]).draw(false);

                        // Remove the row from the modal table
                        modalTable.row(row).remove().draw();
                    }
                }
            });
        }
        // Function to show all products (when Manual is selected)
        function showAllProducts() {
            modalTable.clear().draw(); // Clear the existing rows in the DataTable

            @foreach ($products as $product)
                // Add a new row using DataTable's API
                modalTable.row.add([
                    `<input class="form-check-input product_id" type="checkbox" id="inlineCheckbox1" value="{{ $product->id }}">`,
                    `{{ $product->part_no }}`,
                    `{{ $product->part_name }}`,
                    `{{ $product->units->name ?? '' }}`,
                    `{{ $product->model }}`,
                    `{{ $product->variance }}`,
                    `{{ $product->type_of_products->type ?? '' }}`
                ]).draw(false); // Draw the row without fully re-rendering the table for performance
            @endforeach

            // Optionally adjust the table's column sizes if needed
            modalTable.columns.adjust().draw();
            updateProductTableAfterMRFChange();
        }
        $(document).ready(function() {
            flatpickr("#request_date", {
                dateFormat: "d-m-Y",
                defaultDate: @json(\Carbon\Carbon::parse($tr->request_date)->format('d-m-Y'))
            });
            $(document).on('mouseenter', '.select2-selection__rendered', function() {
                $('.select2-selection__rendered').removeAttr('data-bs-original-title');
            });
            productTable = $('#productTable').DataTable();
            modalTable = $('#modalTable').DataTable();
            $('#productTable tbody button.remove-product').each(function() {
                $(this).trigger('click');
            });
            // Call change_mrf to fetch MRF data and then process the modal table data
            change_mrf();

        });

        function add_product() {
            $('#modalTable input.product_id:checked').each(function() {
                var row = $(this).closest('tr');
                var rowData = modalTable.row(row).data();
                var productId = $(this).val();

                // Add the row data to the main table if not already added
                if ($('#productTable tbody input[value="' + productId + '"]').length == 0) {
                    productTable.row.add([
                        productTable.rows().count() + 1,
                        rowData[1] +
                        `<input type="hidden" name="product[${productTable.rows().count() + 1}][product_id]" class="product_id form-control" value="${productId}">`,
                        rowData[2],
                        rowData[6],
                        rowData[4],
                        rowData[5],
                        rowData[3],
                        `<input name="product[${productTable.rows().count() + 1}][req_qty]" class="qty form-control" > <input type="hidden" class="variance form-control" value="${rowData[5]}">`,
                        `<button type="button" class="btn btn-sm add-remarks btn-danger">Add</button><input type="hidden" name="product[${productTable.rows().count() + 1}][remarks]" class="remarks form-control">`,
                        `<button type="button" class="btn btn-danger btn-sm remove-product"><i class="bi bi-trash"></i></button>`
                    ]).draw(false);

                    // Remove the row from the modal table
                    modalTable.row(row).remove().draw();
                }
            });

            // Uncheck all checkboxes
            $('#modalTable input:checked').prop('checked', false);

            // Hide the modal
            $('#product_modal').modal('hide');
        }

        $('#productTable tbody').on('click', 'button.remove-product', function() {
            var row = $(this).closest('tr');
            var rowData = productTable.row(row).data();

            // Add the removed row back to the modal table
            modalTable.row.add([
                `<input class="form-check-input product_id" type="checkbox" value="${$(row).find('.product_id').val()}">`,
                rowData[1],
                rowData[2],
                rowData[5],
                rowData[4],
                $(row).find('.variance').val(),
                rowData[3],
                $(row).find('.category').val()
            ]).draw(false);

            // Remove the row from the main table
            productTable.row(row).remove().draw();
            resetSerialNumbersM(productTable);
        });

        $('#productTable tbody').on('click', '.add-remarks', function() {
            selectedRow = productTable.row($(this).closest('tr'));
            $('#remarksText').val($(this).closest('tr').find('.remarks').val());
            $('#remarksText').prop('disabled', false);
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

        function resetSerialNumbersM() {
            if ($('#productTable tbody tr:first').find('td:first').text() != 'No data available in table') {
                $('#productTable tbody tr').each(function(index) {
                    $(this).find('td:first').text(index + 1);
                });
            }
        }

        $('#myForm').on('submit', function() {
            $('.card-body').find('.table').DataTable().page.len(-1).draw();
        });
    </script>
@endsection
