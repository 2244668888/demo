@extends('layouts.app')
@section('title')
    MATERIAL REQUISITION EDIT
@endsection
@section('content')
    <style>
        #productTable input {
            width: 130px;
        }
    </style>
    <form method="post" action="{{ route('material_requisition.update', $material_requisition->id) }}"
        enctype="multipart/form-data" id="myForm">
        @csrf
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <h5>MATERIAL REQUISITION DETAILS</h5>
                </div>
                <br>
                <div class="row">
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label for="pro_order_no1" class="form-label">Production Order No.</label>
                            <select name="pro_order_no1" disabled id="pro_order_no1" class="form-select"
                                onchange="change_po()">
                                <option value="" selected disabled>Please Select</option>
                                @foreach ($production_orders as $production_order)
                                    <option value="{{ $production_order->id }}" @selected($material_requisition->pro_order_no == $production_order->pro_order_no)>
                                        {{ $production_order->pro_order_no }}</option>
                                @endforeach
                            </select>
                            <input type="hidden" name="pro_order_no" value="{{ $material_requisition->pro_order_no }}"
                                id="pro_order_no">
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label for="ref_no" class="form-label">Ref No.</label>
                            <input type="text" readonly value="{{ $material_requisition->ref_no }}" name="ref_no"
                                id="ref_no" class="form-control">
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label for="request_date" class="form-label">Request Date</label>
                            <input type="date" name="request_date" id="request_date"
                                value="{{ $material_requisition->request_date }}" class="form-control">
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label for="plan_date" class="form-label">Plan Date</label>
                            <input type="date" readonly name="plan_date" id="plan_date"
                                value="{{ $material_requisition->plan_date }}" class="form-control">
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label for="request_from" class="form-label">Request From</label>
                            <select name="request_from" id="request_from" class="form-select">
                                <option value="" selected disabled>Please Select</option>
                                @foreach ($depts as $dept)
                                    <option value="{{ $dept->id }}" @selected($material_requisition->request_from == $dept->id)>
                                        {{ $dept->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label for="request_to" class="form-label">Transfer To</label>
                            <select name="transfer_to" id="request_to" class="form-select">
                                <option value="" selected disabled>Please Select</option>
                                @foreach ($depts as $dept)
                                    <option value="{{ $dept->id }}" @selected($material_requisition->request_to == $dept->id)>
                                        {{ $dept->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label for="shift" class="form-label">Shift</label>
                            <input type="hidden" name="shift" id="shift" value="{{ $material_requisition->shift }}"
                                class="form-control">
                            <select name="shift1" disabled id="shift1" class="form-select">
                                <option value="AM" @selected($material_requisition->shift == 'AM')>AM</option>
                                <option value="PM" @selected($material_requisition->shift == 'PM')>PM</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label for="machine1" class="form-label">Machine</label>
                            <input type="hidden" name="machine" id="machine"
                                value="{{ $material_requisition->machine }}" class="form-control">
                            <select name="machine1" disabled id="machine1" class="form-select">
                                <option value="" selected disabled></option>
                                @foreach ($machines as $machine)
                                    <option value="{{ $machine->id }}" @selected($material_requisition->machine == $machine->id)>
                                        {{ $machine->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-12 col-sm-12 col-12">
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea name="description" id="description" rows="1" class="form-control">{{ $material_requisition->description }}</textarea>
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
                                        <th>Unit</th>
                                        <th>Request Qty</th>
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
                        <a type="button" class="btn btn-info" href="{{ route('material_requisition.index') }}">
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
                                        Select
                                        <input type="checkbox" id="selectAll" style="width: 22px; height: 22px;">
                                    </th>
                                    <th>Part No</th>
                                    <th>Part Name</th>
                                    <th>Unit</th>
                                    <th>Model</th>
                                    <th>Variance</th>
                                    <th>Type of Product</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($products as $product)
                                    <tr>
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
        var production_orders = {!! json_encode($production_orders) !!};
        var materialRequisitionDetails = {!! json_encode($material_requisition_details) !!};
        let productTable;
        let modalTable;

        $(document).ready(function() {
            productTable = $('#productTable').DataTable();
            modalTable = $('#modalTable').DataTable({
                "columnDefs": [{
                    "targets": 0,
                    "orderable": false
                }]
            });
            $('#productTable tbody button.remove-product').each(function() {
                $(this).trigger('click');
            });
            var pro_order_no = $("#pro_order_no").val();
            modalTable.$('.product_id').each(function() {
                if (materialRequisitionDetails.find(detail => detail.product_id == $(this).val())) {
                    var row = $(this).closest('tr');
                    var rowData = modalTable.row(row).data();
                    var productId = $(this).val();
                    var qty = 0;
                    var remarks = '';
                    var remarks_button;
                    var matchedDetail = materialRequisitionDetails.find(detail => detail.product_id ==
                        productId);
                    if (matchedDetail) {
                        qty = matchedDetail.request_qty;
                        remarks = matchedDetail.remarks;
                    }
                    if (remarks == '' || remarks == null) {
                        remarks_button =
                            `<button type="button" class="btn btn-sm add-remarks btn-danger">Add</button>`;
                    } else {
                        remarks_button =
                            `<button type="button" class="btn btn-sm add-remarks btn-success">Edit</button>`;
                    }
                    // Add the row data to the main table if not already added
                    if ($('#productTable tbody input[value="' + productId + '"]').length == 0) {
                        productTable.row.add([
                            productTable.rows().count() + 1,
                            rowData[1] +
                            `<input type="hidden" name="product[${productTable.rows().count() + 1}][product_id]" class="product_id form-control" value="${productId}">`,
                            rowData[2],
                            rowData[6],
                            rowData[4],
                            rowData[3],
                            `<input name="product[${productTable.rows().count() + 1}][req_qty]" class="qty form-control" value="${qty}"> <input type="hidden" class="variance form-control" value="${rowData[5]}">`,
                            `${remarks_button} <input type="hidden" name="product[${productTable.rows().count() + 1}][remarks]" class="remarks form-control" value="${remarks}">`,
                            `<button type="button" class="btn btn-danger btn-sm remove-product"><i class="bi bi-trash"></i></button>`
                        ]).draw(false);

                        // Remove the row from the modal table
                        modalTable.row(row).remove().draw();
                    }
                }
            });

        });

        function add_product() {
            modalTable.$('input:checked').each(function() {
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
                        rowData[3],
                        `<input name="product[${productTable.rows().count() + 1}][req_qty]" class="qty form-control" > <input type="hidden" class="variance form-control" value="${rowData[5]}">`,
                        `<button type="button" class="btn btn-danger btn-sm add-remarks">Add</button> <input type="hidden" name="product[${productTable.rows().count() + 1}][remarks]" class="remarks form-control">`,
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

            let selectedRow;


        }

        $('#productTable tbody').on('click', '.add-remarks', function() {
            selectedRow = productTable.row($(this).closest('tr'));
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

        function resetSerialNumbersM() {
            if ($('#productTable tbody tr:first').find('td:first').text() != 'No data available in table') {
                $('#productTable tbody tr').each(function(index) {
                    $(this).find('td:first').text(index + 1);
                });
            }
        }

        document.getElementById('selectAll').addEventListener('change', function() {
            const checkboxes = document.querySelectorAll('.product_id');
            checkboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
        });

        $('#myForm').on('submit', function() {
            $('.card-body').find('.table').DataTable().page.len(-1).draw();
        });
    </script>
@endsection
