@extends('layouts.app')
@section('title')
    MATERIAL REQUISITION ISSUE
@endsection
@section('content')
    <style>
        #productTable input {
            width: 130px;
        }
    </style>
    <form method="post" action="{{ route('material_requisition.issued', $material_requisition->id) }}"
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
                            <input type="date" readonly name="request_date" id="request_date"
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
                            <select name="request_from" disabled id="request_from" class="form-select">
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
                            <select name="request_to" disabled id="request_to" class="form-select">
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
                            <textarea name="description" readonly id="description" rows="1" class="form-control">{{ $material_requisition->description }}</textarea>
                        </div>
                    </div>

                </div>
                <br>
                <div class="row">
                    <div class="col-12 d-flex justify-content-between">
                        <h5>ISSUER</h5>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label for="issue_by" class="form-label">Issue By</label>
                            <input type="text" readonly id="issue_by" value="{{ Auth::user()->user_name }}"
                                class="form-control">
                            <input type="hidden" name="issue_by" value="{{ Auth::user()->id }}" class="form-control">
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label for="issue_date" class="form-label">Issue Date</label>
                            <input type="date" name="issue_date" id="issue_date" value="{{ date('Y-m-d') }}"
                                class="form-control">
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label for="time" class="form-label">Issue Time</label>
                            <input type="time" name="issue_time"
                                value="{{ \Carbon\Carbon::now()->setTimezone('Asia/Kuala_Lumpur')->format('H:i') }}"
                                id="time" class="form-control" />
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label for="issue_remarks" class="form-label">Remarks</label>
                            <textarea type="text" rows="1" name="issue_remarks" id="issue_remarks"
                                value="{{ $material_requisition->issue_remarks }}" class="form-control"></textarea>
                        </div>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-12 d-flex justify-content-between">
                        <h5>PRODUCT/MATERIAL DETAILS</h5>
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
                                        <th>Remark Request</th>
                                        <th>Issue Qty</th>
                                        <th>Remark Issue</th>
                                        <th>Balance Qty</th>
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
                <div class="d-flex gap-2 justify-content-between col-12">
                    <input type="hidden" id="storedData" name="details">
                    <a type="button" class="btn btn-info" href="{{ route('material_requisition.index') }}">
                        <i class="bi bi-arrow-left"></i> Back
                    </a>
                    <button type="button" class="btn btn-primary" id="saveForm">Submit</button>
                </div>
            </div>
        </div>
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
                                                <input class="form-check-input modalTable_product_id" type="checkbox"
                                                    id="inlineCheckbox1" value="{{ $product->id }}">
                                            </td>
                                            <td>
                                                {{ $product->part_no }}
                                            </td>
                                            <td>
                                                {{ $product->part_name }}
                                            </td>
                                            <td>
                                                {{ $product->units->name }}
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
        {{-- LOCATIONS MODAL --}}
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">ALLOCATION</h5>
                        <input type="hidden" class="product_ids">
                        <input type="hidden" class="mrf_detail_ids">
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-12 d-flex justify-content-between">
                                <div>Part No: <span class="part_no_text"></span></div>
                                <div>Request Quantity: <span class="request_qty_text"></span></div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 d-flex justify-content-between">
                                <div>Part Name: <span class="part_name_text"></span></div>
                                <div>Issue: <span class="issue_qty_text"></span></div>
                            </div>
                        </div>
                        <br>
                        <div class="table-responsive" id="popUp">
                            <table class="table table-bordered m-0 w-100" id="allocationTable">
                                <thead>
                                    <tr>
                                        <th>Lot No</th>
                                        <th>Location</th>
                                        <th>Unit</th>
                                        <th>Available Qty</th>
                                        <th>Issue Qty</th>
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
    </form>
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
    {{-- REMARKS MODAL 2 --}}
    <div class="modal fade" id="remarksModal2" tabindex="-1" aria-labelledby="remarksModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="remarksModalLabel">Add Remarks</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <textarea id="remarksText2" class="form-control" rows="4" placeholder="Enter your remarks here"></textarea>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="saveRemarks2">Save</button>
                </div>
            </div>
        </div>
    </div>
    <script>
        var production_orders = {!! json_encode($production_orders) !!};
        var materialRequisitionDetails = {!! json_encode($material_requisition_details) !!};
        var material_requisition_product_details = {!! json_encode($material_requisition_product_details) !!};
        let productTable;
        let modalTable;
        let allocationTable;

        locations = @json($locations);

        $(document).ready(function() {
            allocationTable = $('#allocationTable').DataTable();
            sessionStorage.clear();
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
            modalTable.$('.modalTable_product_id').each(function() {
                if (materialRequisitionDetails.find(detail => detail.product_id == $(this).val())) {
                    var row = $(this).closest('tr');
                    var rowData = modalTable.row(row).data();
                    var productId = $(this).val();
                    var qty = 0;
                    var issue_qty = 0;
                    var remarks = '';
                    var request_remarks_button;
                    var issue_remarks = '';
                    var issue_remarks_button;
                    var mrf_detail_id = '';
                    var matchedDetail = materialRequisitionDetails.find(detail => detail.product_id ==
                        productId);
                    if (matchedDetail) {
                        qty = matchedDetail.request_qty;
                        issue_qty = matchedDetail.issue_qty !== null ? matchedDetail.issue_qty : 0;
                        remarks = matchedDetail.remarks;
                        mrf_detail_id = matchedDetail.id;
                        issue_remarks = matchedDetail.issue_remarks;

                        if (remarks == '' || remarks == null) {
                            request_remarks_button =
                                `<button type="button" class="btn btn-sm add-remarks btn-danger" disabled>No Remarks</button>`;
                        } else {
                            request_remarks_button =
                                `<button type="button" class="btn btn-sm add-remarks btn-success">View</button>`;
                        }
                        if (issue_remarks == '' || issue_remarks == null) {
                            issue_remarks_button =
                                `<button type="button" class="btn btn-sm add-remarks2 btn-danger">Add</button>`;
                        } else {
                            issue_remarks_button =
                                `<button type="button" class="btn btn-sm add-remarks2 btn-success">Edit</button>`;
                        }
                        var avl_qty;
                        var balance = qty - issue_qty;
                        let data = [];
                        $.each(material_requisition_product_details, function(key, detail) {
                            if (detail.mrf_detail_id == mrf_detail_id) {
                                locations.forEach(location => {
                                    if (location.product_id == productId) {
                                        if (location.area_id == detail.area && location
                                            .rack_id == detail.rack && location.level_id ==
                                            detail.level && location.lot_no == detail.lot_no
                                            ) {
                                            avl_qty = location.used_qty;
                                        }
                                    }
                                });
                                let rowData = {};
                                rowData['location'] = detail.area + '->' + detail.rack + '->' +
                                    detail.level;
                                rowData['area'] = detail.area;
                                rowData['rack'] = detail.rack;
                                rowData['level'] = detail.level;
                                rowData['lot_no'] = detail.lot_no;
                                rowData['qty'] = detail.qty;
                                rowData['avl_qty'] = avl_qty;
                                rowData['hiddenId'] = productId;
                                rowData['mrf_detail_id'] = mrf_detail_id;
                                // Check if rowData is not empty
                                if (rowData['location'] && rowData['lot_no'] && rowData['qty']) {
                                    data.push(rowData);
                                }
                            }
                        });

                        if (data.length > 0) {
                            sessionStorage.setItem(`modalData${productId}`, JSON.stringify(data));
                        }
                    }
                    // Add the row data to the main table if not already added
                    if ($('#productTable tbody input[value="' + productId + '"]').length == 0) {
                        productTable.row.add([
                            productTable.rows().count() + 1,
                            rowData[1] +
                            `<input type="hidden" name="product[${productTable.rows().count() + 1}][product_id]" class="product_id form-control" value="${productId}">` +
                            `<input type="hidden" name="product[${productTable.rows().count() + 1}][mrf_detail_id]" class="mrf_detail_id form-control" value="${mrf_detail_id}">`,
                            rowData[2],
                            rowData[6],
                            rowData[4],
                            rowData[3],
                            `<input readonly name="product[${productTable.rows().count() + 1}][req_qty]" class="qty form-control" value="${qty}"> <input type="hidden" class="variance form-control" value="${rowData[5]}">`,
                            `${request_remarks_button} <input type="hidden" name="product[${productTable.rows().count() + 1}][remarks]" rows="1" class="remarks form-control" value="${remarks}">`,
                            `<input name="product[${productTable.rows().count() + 1}][issue_qty]" class="issue_qty form-control" value="${issue_qty}" readonly>`,
                            `${issue_remarks_button} <input type="hidden" name="product[${productTable.rows().count() + 1}][issue_remarks]" rows="1" class="issue_remarks form-control" value="${issue_remarks}">`,
                            `<input class="balance form-control" value="${balance}">`,
                            `<div class="d-flex"><button type="button" class="btn btn-success btn-sm openModal me-1" data-bs-toggle="modal" data-bs-target="#exampleModal"><i class="bi bi-plus-circle"></i></button>
                            <a href="" target="_blank" class="btn btn-primary btn-sm print" data-mrf-detail-id="${mrf_detail_id}"><i class="bi bi-printer"></i></a></div>`
                        ]).draw(false);

                        // Then, attach an event listener to dynamically update the href
                        $('#productTable tbody').on('click', '.print', function(e) {
                            e.preventDefault();
                            let mrf_detail_id_print = $(this).data('mrf-detail-id');
                            let url =
                                "{{ route('material_requisition.issue.print', ':mrf_detail_id') }}";
                            url = url.replace(':mrf_detail_id', mrf_detail_id_print);
                            window.location.href = url;
                        });

                        // Remove the row from the modal table
                        let selectedRow;

                    }
                }
            });

        });

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

        $('#productTable tbody').on('click', '.add-remarks', function() {
            selectedRow = productTable.row($(this).closest('tr'));
            $('#remarksText').val($(this).closest('tr').find('.remarks').val());
            $('#remarksText').prop('disabled', 'true');
            $('#remarksModal').modal('show');
        });
        $('#productTable tbody').on('click', '.add-remarks2', function() {
            selectedRow = productTable.row($(this).closest('tr'));
            $('#remarksText2').val($(this).closest('tr').find('.issue_remarks').val());
            $('#remarksText2').prop('disabled', false); // Use prop with false
            $('#remarksModal2').modal('show');
        });

        $('#saveRemarks').on('click', function() {
            let remarks = $('#remarksText').val();
            let button = selectedRow.node().querySelector('.add-remarks');
            selectedRow.node().querySelector('.remarks').value = remarks;
            $('#remarksModal').modal('hide');
        });

        $('#saveRemarks2').on('click', function() {
            let remarks = $('#remarksText2').val();
            let button = selectedRow.node().querySelector('.add-remarks2');
            if (remarks.trim() !== '') {
                button.classList.remove('btn-danger');
                button.classList.add('btn-success');
                button.textContent = 'Edit';
            } else {
                button.classList.remove('btn-success');
                button.classList.add('btn-danger');
                button.textContent = 'Add';
            }
            selectedRow.node().querySelector('.issue_remarks').value = remarks;
            $('#remarksModal2').modal('hide');
        });



        $(document).on('click', '.openModal', function() {
            let hiddenId = $(this).closest('tr').find('.product_id').val();
            let mrf_detail_id = $(this).closest('tr').find('.mrf_detail_id').val();
            $('.product_ids').val(hiddenId);
            $('.mrf_detail_ids').val(mrf_detail_id);

            let storedData = sessionStorage.getItem(`modalData${hiddenId}`);
            var row = $(this).closest('tr');
            var rowData = productTable.row(row).data();

            // Clear existing rows in the table
            allocationTable.clear().draw();
            let part_no = $(this).closest('tr').find('td:eq(1)').text();
            let part_name = $(this).closest('tr').find('td:eq(2)').text();
            let request_qty_text = $(row).find('.qty').val();
            $('.part_no_text').text(part_no);
            $('.part_name_text').text(part_name);
            $('.request_qty_text').text(request_qty_text);
            $('.qty').trigger('keyup');

            if (storedData) {
                storedData = JSON.parse(storedData);
                storedData.forEach(element => {
                    let lotOptionsHtml = '';
                    let locationOptionsHtml = '';

                    // Populate lot number options
                    locations.forEach(location => {
                        if (location.product_id == hiddenId) {
                            let selectedLot = (element.lot_no === location.lot_no) ? 'selected' :
                            '';
                            lotOptionsHtml +=
                                `<option value="${location.lot_no}" ${selectedLot}>${location.lot_no}</option>`;
                        }
                    });

                    // Populate location options for the selected lot number
                    locations.forEach(location => {
                        if (location.product_id == hiddenId && location.lot_no === element.lot_no) {
                            let selected = (element.location ===
                                `${location.area_id}->${location.rack_id}->${location.level_id}`
                                ) ? 'selected' : '';
                            locationOptionsHtml +=
                                `<option data-area-id="${location.area_id}" data-rack-id="${location.rack_id}" data-level-id="${location.level_id}" data-qty="${location.used_qty}" value="${location.area_id}->${location.rack_id}->${location.level_id}" ${selected}>${location.area.name}->${location.rack.name}->${location.level.name}</option>`;
                        }
                    });

                    allocationTable.row.add([
                        `<select class="form-select lot_no">${lotOptionsHtml}</select>`,
                        `<select class="form-select location">${locationOptionsHtml}</select>`,
                        rowData[5],
                        `<input type="number" readonly class="form-control avl_qty" value="${element.avl_qty}">`,
                        `<input type="number" class="form-control qty" value="${element.qty}">`,
                        `<button type="button" class="btn btn-success btn-sm me-2" onclick="addRow(this)"><i class="bi bi-plus"></i></button><button type="button" class="btn btn-danger btn-sm" onclick="removeRow(this)"><i class="bi bi-dash"></i></button>`
                    ]).draw(false);
                });
            } else {
                var flag = false;
                let lotOptionsHtml = '';
                let locationOptionsHtml = '';

                // Populate lot number options
                locations.forEach(location => {
                    if (location.product_id == hiddenId) {
                        flag = true;
                        lotOptionsHtml += `<option value="${location.lot_no}">${location.lot_no}</option>`;
                    }
                });

                // Populate location options for the first lot number initially
                let selectedLotNo = locations[0].lot_no; // Assuming first lot number initially
                locations.forEach(location => {
                    if (location.product_id == hiddenId && location.lot_no === selectedLotNo) {
                        locationOptionsHtml +=
                            `<option data-area-id="${location.area_id}" data-rack-id="${location.rack_id}" data-level-id="${location.level_id}" data-qty="${location.used_qty}" value="${location.area_id}->${location.rack_id}->${location.level_id}">${location.area.name}->${location.rack.name}->${location.level.name}</option>`;
                    }
                });

                if (flag) {
                    $('.qt_alert').addClass('d-none');
                    $('#saveModal').removeClass('d-none');
                    allocationTable.row.add([
                        `<select class="form-select lot_no"><option>select</option>${lotOptionsHtml}</select>`,
                        `<select class="form-select location"><option>select</option>${locationOptionsHtml}</select>`,
                        rowData[5],
                        `<input type="number" readonly class="form-control avl_qty">`,
                        `<input type="number" class="form-control qty">`,
                        `<button type="button" class="btn btn-success btn-sm me-2" onclick="addRow(this)"><i class="bi bi-plus"></i></button><button type="button" class="btn btn-danger btn-sm" onclick="removeRow(this)"><i class="bi bi-dash"></i></button>`
                    ]).draw(false);
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



            // Attach onchange event handlers to the selects
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
                const qtyInput = $(locationSelect).closest('tr').find('.avl_qty');
                qtyInput.val(qty);
            }
        });






        $('#saveModal').on('click', function() {
            $('#exampleModal').modal('hide');
            let hiddenId = $('.product_ids').val();
            let mrf_detail_id = $('.mrf_detail_ids').val();
            let data = [];
            $('#allocationTable tbody tr').each(function() {
                let rowData = {};
                rowData['location'] = $(this).find('.location').val();
                rowData['area'] = $(this).find('.location option:selected').attr('data-area-id');
                rowData['rack'] = $(this).find('.location option:selected').attr('data-rack-id');
                rowData['level'] = $(this).find('.location option:selected').attr('data-level-id');
                rowData['lot_no'] = $(this).find('.lot_no').val();
                rowData['qty'] = $(this).find('.qty').val();
                rowData['avl_qty'] = $(this).find('.avl_qty').val();
                rowData['hiddenId'] = hiddenId;
                rowData['mrf_detail_id'] = mrf_detail_id;
                data.push(rowData);
            });
            sessionStorage.setItem(`modalData${hiddenId}`, JSON.stringify(data));
            $('#productTable tbody tr').each(function() {
                if ($(this).find('.product_id').val() == hiddenId) {
                    let total_qty = $('.issue_qty_text').text();
                    if (total_qty == null || total_qty == undefined || total_qty == 0) {
                        $(this).find('.issue_qty').val(0);
                    } else {
                        $(this).find('.issue_qty').val(total_qty);
                        var qty = $(this).find('.qty').val();
                        var balance = qty - total_qty;
                        $(this).find('.balance').val(balance);
                    }

                }
            });
        });

        $(document).on('keyup change', '.qty', function() {
            let total = 0;
            $('#allocationTable .qty').each(function() {
                total += +$(this).val();
            });
            var request_qty_text = $('.request_qty_text').text();
            if (total > request_qty_text) {
                newqty = request_qty_text - total;
                $(this).val(newqty);
            }
            if ($(this).val() <= 0) {
                $(this).val(0);
            }
            $('.issue_qty_text').text(total);

        });

        $('#saveForm').on('click', function() {
            $('.card-body').find('.table').DataTable().page.len(-1).draw();
            let array = [];
            $('.product_id').each(function() {
                let storedData = sessionStorage.getItem(`modalData${$(this).val()}`);
                if (storedData == null) {

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
        });
    </script>
@endsection
