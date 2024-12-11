@extends('layouts.app')
@section('title')
    MATERIAL REQUISITION VIEW
@endsection
@section('content')
    <style>
        #productTable input {
            width: 130px;
        }
    </style>
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
                        <input type="text" readonly value="{{$tr->ref_no}}" name="ref_no" id="ref_no" class="form-control">
                    </div>
                </div>
                <div class="col-lg-3 col-sm-4 col-12">
                    <div class="mb-3">
                        <label for="request_date" class="form-label">Request Date</label>
                        <input type="date" readonly value="{{$tr->request_date}}" name="request_date" id="request_date" class="form-control">
                    </div>
                </div>
                <div class="col-lg-3 col-sm-4 col-12">
                    <div class="mb-3">
                        <label for="request_from" class="form-label">Request From</label>
                        <select name="request_from" disabled id="request_from" class="form-select">
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
                        <select name="request_to" disabled id="request_to" class="form-select">
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
                        <textarea readonly name="description" id="description" rows="4" cols="4" class="form-control">{{ $tr->description }}</textarea>
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
                        <select name="mrf_no" disabled id="mrf_no" class="form-select" >
                            <option value="" selected disabled>Please Select</option>
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
                        <input type="text" readonly value="{{$tr->shift}}" name="shift" id="shift" class="form-control">
                    </div>
                </div>
                <div class="col-lg-3 col-sm-4 col-12">
                    <div class="mb-3">
                        <label for="machine" class="form-label">Machine</label>
                        <input type="text" readonly value="@foreach ($machines as $machine) @if ($machine->id == $tr->machine){{ $machine->name }}@endif @endforeach" id="machine_name" class="form-control">
                        <input type="hidden" value="{{$tr->machine_id}}" name="machine_id" id="machine_id" class="form-control">
                    </div>
                </div>
            </div>
            <br>
            @if ($tr->rcv_date)
            <div class="row">
                <div class="col-12 d-flex justify-content-between">
                    <h5>RECEIVER</h5>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-3 col-sm-4 col-12">
                    <div class="mb-3">
                        <label for="rcv_by" class="form-label">Received By</label>
                        <input type="text" disabled id="rcv_by" value="{{ $user2->user_name }}" class="form-control">
                    </div>
                </div>
                <div class="col-lg-3 col-sm-4 col-12">
                    <div class="mb-3">
                        <label for="rcv_date" class="form-label">Receive Date</label>
                        <input type="date" disabled name="rcv_date" id="rcv_date"  value="{{ $tr->rcv_date }}" class="form-control">
                    </div>
                </div>
                <div class="col-lg-3 col-sm-4 col-12">
                    <div class="mb-3">
                        <label for="time" class="form-label">Time</label>
                        <input type="time" disabled name="rcv_time" value="{{ $tr->rcv_time }}" id="time" class="form-control" />
                    </div>
                </div>
                <div class="col-lg-3 col-sm-4 col-12">
                    <div class="mb-3">
                        <label for="rcv_remarks" class="form-label">Remarks</label>
                        <input type="text" disabled name="rcv_remarks" id="rcv_remarks" value="{{ $tr->rcv_remarks }}"  class="form-control">
                    </div>
                </div>
            </div>
            @endif
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
                                    <th>Request Remarks</th>
                                    <th>Receive Qty</th>
                                    <th>Receive Remarks</th>
                                    <th>Balance</th>
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
            <div class="d-flex gap-2 justify-content-start col-12">
                <a type="button" class="btn btn-info" href="{{ route('transfer_request.index') }}">
                    <i class="bi bi-arrow-left"></i> Back
                </a>
            </div>
        </div>
    </div>
    {{-- PRODUCTS MODAL --}}
    <div class="modal fade" id="product_modal" tabindex="-1" aria-labelledby="exampleModalCenterTitle" aria-modal="true" role="dialog">
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
                                            {{ $product->units->name  ?? '' }}
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
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">ALLOCATION</h5>
                    <input type="hidden" class="product_ids">
                    <input type="hidden" class="trd_ids">
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
                            <div>Receive: <span class="issue_qty_text"></span></div>
                        </div>
                    </div>
                    <br>
                    <div class="table-responsive" id="popUp">
                        <table class="table table-bordered m-0 w-100" id="allocationTable">
                            <thead>
                                <tr>
                                    <th>Location</th>
                                    <th>Lot No</th>
                                    <th>Receive Qty</th>
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
                </div>
            </div>
        </div>
    </div>
    <script>

        var trds = {!! json_encode($trds) !!};
        var tr_receive = {!! json_encode($tr_receive) !!};
        let productTable;
        let modalTable;
        let allocationTable;

        locations = @json($locations);

        $(document).ready(function() {
            flatpickr("#request_date", {
                dateFormat: "d-m-Y",
                defaultDate:@json(\Carbon\Carbon::parse($tr->request_date)->format('d-m-Y'))
            });
            allocationTable = $('#allocationTable').DataTable();
            sessionStorage.clear();
            productTable = $('#productTable').DataTable();
            modalTable = $('#modalTable').DataTable();
            $('#productTable tbody button.remove-product').each(function() {
                $(this).trigger('click');
            });
            var pro_order_no = $("#pro_order_no").val();
            modalTable.$('.modalTable_product_id').each(function() {
                if( trds.find(detail => detail.product_id == $(this).val())){
                    var row = $(this).closest('tr');
                    var rowData = modalTable.row(row).data();
                    var productId = $(this).val();
                    var qty = 0;
                    var rcv_qty = 0;

                    var issue_qty = 0;
                    var remarks = '';
                    var rcv_remarks = '';
                    var issue_remarks = '';
                    var tr_detail_id = '';
                    var matchedDetail = trds.find(detail => detail.product_id == productId);
                    if (matchedDetail) {
                        qty = matchedDetail.request_qty;
                        issue_qty = matchedDetail.issue_qty !== null ? matchedDetail.issue_qty : 0;
                        rcv_qty = matchedDetail.rcv_qty !== null ? matchedDetail.rcv_qty : 0;
                        remarks = matchedDetail.request_remarks;
                        rcv_remarks = matchedDetail.rcv_remarks ?? '';
                        tr_detail_id = matchedDetail.id;
                        issue_remarks = matchedDetail.issue_remarks;
                        var avl_qty;
                        var balance = qty - rcv_qty;
                        let data = [];
                        if (remarks == '' || remarks == null){
                            request_remarks_button = `<button type="button" class="btn btn-sm add-remarks btn-danger" disabled>No Remarks</button>`;
                        }else{
                            request_remarks_button = `<button type="button" class="btn btn-sm add-remarks btn-success">View</button>`;
                        }
                        if (rcv_remarks == '' || rcv_remarks == null){
                            rcv_remarks_button = `<button type="button" class="btn btn-sm add-remarks2 btn-danger" disabled>No Remarks</button>`;
                        }else{
                            rcv_remarks_button = `<button type="button" class="btn btn-sm add-remarks2 btn-success">View</button>`;
                        }
                        $.each(tr_receive, function(key, detail) {
                            if (detail.tr_detail_id == tr_detail_id) {
                                locations.forEach(location => {
                                    if (location.product_id == productId) {
                                        if (location.area_id == detail.area && location.rack_id == detail.rack && location.level_id == detail.level && location.lot_no == detail.lot_no) {
                                            avl_qty = location.used_qty;
                                        }
                                    }
                                });
                                let rowData = {};
                                rowData['location'] = detail.area + '->'+ detail.rack +'->'+detail.level;
                                rowData['area'] = detail.area;
                                rowData['rack'] = detail.rack;
                                rowData['level'] = detail.level;
                                rowData['lot_no'] = detail.lot_no;
                                rowData['qty'] = detail.qty;
                                rowData['avl_qty'] = avl_qty;
                                rowData['hiddenId'] = productId;
                                rowData['tr_detail_id'] = tr_detail_id;
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
                            rowData[1] + `<input type="hidden" name="product[${productTable.rows().count() + 1}][product_id]" class="product_id form-control" value="${productId}">`
                            +`<input type="hidden" name="product[${productTable.rows().count() + 1}][tr_detail_id]" class="tr_detail_id form-control" value="${tr_detail_id}">`,
                            rowData[2],
                            rowData[6],
                            rowData[4],
                            rowData[3],
                            `<input readonly name="product[${productTable.rows().count() + 1}][req_qty]" class="qty form-control" value="${qty}"> <input type="hidden" class="variance form-control" value="${rowData[5]}">`,
                            `${request_remarks_button}<input type="hidden" name="product[${productTable.rows().count() + 1}][remarks]" class="remarks form-control" value="${remarks}">`,
                            `<input name="product[${productTable.rows().count() + 1}][rcv_qty]" class="rcv_qty form-control" value='${rcv_qty}' readonly>`,
                            `${rcv_remarks_button}<input type="hidden" name="product[${productTable.rows().count() + 1}][rcv_remarks]" class="rcv_remarks form-control" value="${rcv_remarks}">`,
                            `<input class="balance form-control" value="${balance}" readonly>`
                        ]).draw(false);

                        // Remove the row from the modal table
                    }
                }
            });

        });
        function addRow(button) {
            // Clone the row and get the data from it
            var row = button.parentNode.parentNode.cloneNode(true);
            var rowData = $(row).find('td').map(function() {
                return $(this).html();
            }).get();

            // Add the cloned data as a new row in DataTable
            allocationTable.row.add(rowData).draw(false);

            // Trigger any additional required events
            $('.qty').trigger('keyup');
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
            $('#remarksText').prop('disabled','true');
            $('#remarksModal').modal('show');
        });
        $('#productTable tbody').on('click', '.add-remarks2', function() {
            selectedRow = productTable.row($(this).closest('tr'));
            $('#remarksText2').val($(this).closest('tr').find('.rcv_remarks').val());
            $('#remarksText2').prop('disabled', true); // Use prop with false
            $('#remarksModal2').modal('show');
        });



        $(document).on('click', '.openModal', function() {
            let hiddenId = $(this).closest('tr').find('.product_id').val();
            let tr_detail_id = $(this).closest('tr').find('.tr_detail_id').val();
            $('.product_ids').val(hiddenId);
            $('.tr_detail_ids').val(tr_detail_id);

            let storedData = sessionStorage.getItem(`modalData${hiddenId}`);
            var row = $(this).closest('tr');
            var rowData = productTable.row(row).data();

            // Clear existing rows in the table
            allocationTable.clear().draw();
            if (storedData) {
                storedData = JSON.parse(storedData);
                storedData.forEach(element => {
                    let locationOptionsHtml = '';



                    // Populate location options for the selected lot number
                    locations.forEach(location => {
                            let selected = (element.location === `${location.area_id}->${location.rack_id}->${location.level_id}`) ? 'selected' : '';
                            locationOptionsHtml += `<option data-area-id="${location.area_id}" data-rack-id="${location.rack_id}" data-level-id="${location.level_id}" data-qty="${location.used_qty}" value="${location.area_id}->${location.rack_id}->${location.level_id}" ${selected}>${location.area.name}->${location.rack.name}->${location.level.name}</option>`;
                    });

                    allocationTable.row.add([
                        `<select class="form-control location">${locationOptionsHtml}</select>`,
                        `<input type="number" class="form-control lot_no" value="${element.lot_no}">`,
                        `<input type="number" class="form-control qty" value="${element.qty}">`,
                        `<button type="button" class="btn btn-success btn-sm me-2" onclick="addRow(this)"><i class="bi bi-plus"></i></button><button type="button" class="btn btn-danger btn-sm" onclick="removeRow(this)"><i class="bi bi-dash"></i></button>`
                    ]).draw(false);
                });
            } else {
                var flag = false;
                let locationOptionsHtml = '';

                locations.forEach(location => {
                    locationOptionsHtml += `<option data-area-id="${location.area_id}" data-rack-id="${location.rack_id}" data-level-id="${location.level_id}" data-qty="${location.used_qty}" value="${location.area_id}->${location.rack_id}->${location.level_id}">${location.area.name}->${location.rack.name}->${location.level.name}</option>`;
                });
                allocationTable.row.add([
                        `<select class="form-control location"><option>select</option>${locationOptionsHtml}</select>`,
                        `<input type="number" class="form-control lot_no">`,
                        `<input type="number" class="form-control qty">`,
                        `<button type="button" class="btn btn-success btn-sm me-2" onclick="addRow(this)"><i class="bi bi-plus"></i></button><button type="button" class="btn btn-danger btn-sm" onclick="removeRow(this)"><i class="bi bi-dash"></i></button>`
                    ]).draw(false);

            }

            let part_no = $(this).closest('tr').find('td:eq(1)').text();
            let part_name = $(this).closest('tr').find('td:eq(2)').text();
            let request_qty_text = $(row).find('.qty').val();
            $('.part_no_text').text(part_no);
            $('.part_name_text').text(part_name);
            $('.request_qty_text').text(request_qty_text);
            $('.qty').trigger('keyup');

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
            let tr_detail_id = $('.tr_detail_ids').val();
            let data = [];
            $('#allocationTable tbody tr').each(function() {
                let rowData = {};
                rowData['location'] = $(this).find('.location').val();
                rowData['area'] = $(this).find('.location option:selected').attr('data-area-id');
                rowData['rack'] = $(this).find('.location option:selected').attr('data-rack-id');
                rowData['level'] = $(this).find('.location option:selected').attr('data-level-id');
                rowData['lot_no'] = $(this).find('.lot_no').val();
                rowData['qty'] = $(this).find('.qty').val();
                rowData['hiddenId'] = hiddenId;
                rowData['tr_detail_id'] = tr_detail_id;
                data.push(rowData);
            });
            sessionStorage.setItem(`modalData${hiddenId}`, JSON.stringify(data));
            $('#productTable tbody tr').each(function() {
                if ($(this).find('.product_id').val() == hiddenId) {
                    let total_qty = $('.issue_qty_text').text();
                    if(total_qty == null || total_qty == undefined || total_qty == 0){
                        $(this).find('.rcv_qty').val(0);
                    }else{
                        $(this).find('.rcv_qty').val(total_qty);
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
            $('.issue_qty_text').text(total);

        });

        $('#saveForm').on('click', function() {
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


    </script>
@endsection





