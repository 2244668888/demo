@extends('layouts.app')
@section('title')
    DAILY PRODUCTION PLANNING
@endsection
@section('content')
    <style>
        #mainTable input {
            width: 100px;
        }
        .custom-table-class{
            width: -webkit-fill-available !important;
        }
        .custom-select{
            width: auto !important;
        }
    </style>
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-lg-12">
                    <div class="col-lg-3 col-sm-4 col-12">
                        <h5>DAILY PRODUCTION PLANNING DETAILS</h5>
                    </div>
                </div>
            </div>
            <div class="row mb-5">
                <div class="col-lg-3 col-sm-4 col-12">
                    <div class="mb-3">
                        <label for="rev_no" class="form-label">Order No.</label>
                        <select disabled class="form-select">
                            <option selected>{{ $fetch_orders->order_no }}</option>
                        </select>
                    </div>
                </div>

                <div class="col-lg-3 col-sm-4 col-12">
                    <div class="mb-3">
                        <label for="customer" class="form-label">Customer</label>
                        <input type="text" readonly id="customer_name" class="form-control" value="{{ $fetch_orders->customers->name }}">
                        <input type="hidden" id="dpp_id" value="{{$dpp->id}}" class="form-control">
                    </div>
                </div>
                <div class="col-lg-3 col-sm-4 col-12">
                    <div class="mb-3">
                        <label for="product_id" class="form-label">Order Month</label>
                        <input type="month" readonly id="order_month" class="form-control" value="{{$fetch_orders->order_month}}">
                    </div>
                </div>
                <div class="col-lg-3 col-sm-4 col-12 display-none">
                    <div class="mb-3">
                        <label for="planning_date" class="form-label">Planning Date</label>
                        <input type="date" readonly value="{{$dpp->planning_date}}" name="planning_date" id="planning_date" class="form-control" >
                    </div>
                </div>
                <div class="col-lg-3 col-sm-4 col-12">
                    <div class="mb-3">
                        <label for="created_date" class="form-label">Created Date</label>
                        <input type="date" readonly name="created_date" id="created_date" class="form-control" value="{{$dpp->created_date}}">
                    </div>
                </div>
                <div class="col-lg-3 col-sm-4 col-12">
                    <div class="mb-3">
                        <label for="created_by" class="form-label">Created By</label>
                        <input type="text" readonly id="created_by" class="form-control" value="{{ $user->user_name }}">
                    </div>
                </div>
                <div class="col-lg-3 col-sm-4 col-12">
                    <div class="mb-3">
                        <label for="ref_no" class="form-label">Planning Ref No.</label>
                        <input type="text" readonly name="ref_no" id="ref_no" value="{{ $dpp->ref_no }}" class="form-control" value="">
                    </div>
                </div>
                <div class="col-lg-3 col-sm-4 col-12 display-none">
                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select name="status" id="status" class="form-select">
                            <option value="">Please Select</option>
                            <option value="In Progress"  @if ($dpp->status == 'In Progress') selected @endif>In Progress</option>
                            <option value="Completed"  @if ($dpp->status == 'Completed') selected @endif>Completed</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="row mb-5">
                <div class="col-md-12">
                    <h5>PRODUCT LIST</h5>
                    <div class="table-responsive mb-5">
                        <table class="table table-bordered m-0" id="p_table">
                            <thead>
                                <tr>
                                    <th>Sr No.</th>
                                    <th>Part No.</th>
                                    <th>Part Name</th>
                                    <th>Type of Product</th>
                                    <th>Model</th>
                                    <th>Variance</th>
                                    <th>Unit</th>
                                    <th>DI QTY</th>
                                    <th>Current Inventory QTY</th>
                                    <th>Estimated Total Required</th>
                                    <th>Estimated Planning QTY</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($dppps as $dppp)
                                @php
                                    $parent_product[$loop->iteration]['product_id'] = $dppp->product_id;
                                    $parent_product[$loop->iteration]['di_qty'] = $dppp->total_required_qty;
                                @endphp
                                    <tr>
                                        <td>
                                            {{ $loop->iteration }}
                                            <input type="hidden" value="{{$dppp->product_id}}" class="main_product_id">
                                        </td>
                                        <td>{{ $dppp->products->part_no }}</td>
                                        <td>{{ $dppp->products->part_name }}</td>
                                        <td>{{ $dppp->products->type_of_products->type }}</td>
                                        <td>{{ $dppp->products->model }}</td>
                                        <td>{{ $dppp->products->variance }}</td>
                                        <td>{{ $dppp->products->units->name }}</td>
                                        <td>{{ $dppp->di_qty }}</td>
                                        <td>{{ $dppp->inventory_qty }}</td>
                                        <td>{{ $dppp->total_required_qty }}</td>
                                        <td>
                                            {{ $dppp->est_plan_qty }}
                                            <input type="hidden" class="form-control est_plan_qty" value="{{ $dppp->est_plan_qty }}">
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="row mb-5">
                <div class="col-lg-12 col-sm-12 col-12" id="child_partdiv">
                    <h5>CHILD PART LIST</h5>
                    <div class="table-responsive mb-5">
                        <table class="table table-bordered m-0" id="sp_table">
                            <thead>
                                <tr>
                                    <th>Sr No.</th>
                                    <th>Parent Part No.</th>
                                    <th>Part No.</th>
                                    <th>Part Name</th>
                                    <th>Model</th>
                                    <th>Variance</th>
                                    <th>Type of Product</th>
                                    <th>Unit</th>
                                    <th>Unit QTY</th>
                                    <th>Total Required QTY</th>
                                    <th>Current Inventory QTY</th>
                                    <th>Estimated Planning QTY</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($dpcps as $dpcp)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $dpcp->parent_products->part_no }}</td>
                                        <td>
                                            {{ $dpcp->products->part_no }}
                                            <input type="hidden" value="{{ $dpcp->products->id }}" class="child_part_id">
                                        </td>
                                        <td>{{ $dpcp->products->part_name }}</td>
                                        <td>{{ $dpcp->products->model }}</td>
                                        <td>{{ $dpcp->products->variance }}</td>
                                        <td>{{ $dpcp->products->type_of_product }}</td>
                                        <td>{{ $dpcp->products->units->name }}</td>
                                        <td>{{ $dpcp->subpart_qty }}</td>
                                        <td>{{ $dpcp->total_required_qty }}</td>
                                        <td>{{ $dpcp->inventory_qty }}</td>
                                        <td>
                                            {{ $dpcp->est_plan_qty }}
                                            <input type="hidden"  class="form-control est_plan_qty" value="{{ $dpcp->est_plan_qty }}">
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
            <div class="row mb-5">
                <div class="col-lg-12 col-sm-12 col-12 display-show">
                    <div class="table-responsive mb-5">
                        <h5 class="display-show">PRODUCTION SCHEDULING</h5>
                        <table class="table table-bordered m-0" id="scheduling_table">
                            <thead>
                                <tr>
                                    <th>Sr No.</th>
                                    <th>Part No.</th>
                                    <th>Part Name</th>
                                    <th>Model</th>
                                    <th>Variance</th>
                                    <th>Type of Product</th>
                                    <th>Process</th>
                                    <th>Process No.</th>
                                    <th>Material and Purchase Part</th>
                                    <th>Child Part</th>
                                    <th>CT (s)</th>
                                    <th>Total Planned QTY</th>
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
            <div class="d-flex gap-2 justify-content-start">
                <a type="button" class="btn btn-info" href="{{ route('daily-production-planning') }}">
                    <i class="bi bi-arrow-left"></i> Back
                </a>
            </div>
        </div>
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">PLANNING</h5>
                        <input type="hidden" class="product_ids">
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-6 d-flex" style="flex-direction: column">
                                <div>Planning Ref No. <span class="ref_no_text"></span></div>
                                <div>Part No.: <span class="part_no_text"></span></div>
                                <div>Part Name: <span class="part_name_text"></span></div>
                            </div>
                            <div class="col-6 d-flex" style="flex-direction: column">
                                <div style="display: flex;justify-content: flex-end;">Process: <span class="process_text"></span></div>
                                <div style="display: flex;justify-content: flex-end;">Total Plan Qty: <span class="total_plan_qty_text"></span></div>
                                <div style="display: flex;justify-content: flex-end;">Plan Qty: <span class="plan_qty_text"></span></div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="alert border-danger alert-dismissible fade show text-danger d-none" role="alert" id="error_div2">

                            </div>
                            <div class="alert border-success alert-dismissible fade show text-success d-none" role="alert" id="success_div2">

                            </div>
                        </div>
                        <br>
                        <div class="table-responsive" id="popUp">
                            <table class="table table-bordered m-0" id="planningTable">
                                <thead>
                                    <tr>
                                        <th>Production Order No.</th>
                                        <th>Planned Date</th>
                                        <th>Operator Name</th>
                                        <th>Shift</th>
                                        <th>Spec Break</th>
                                        <th>Planned Qty</th>
                                        <th>Machine</th>
                                        <th>Machine Tonnage</th>
                                        <th>Cavity</th>
                                        <th>Proceed Request Material</th>
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
                        <button type="button" class="btn btn-primary save_planning" onclick="save_planning()">Save</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        var sp_table;
        var p_table;
        var scheduling_table;
        var all_products = {!! json_encode($products) !!};

        $(document).ready(function() {
            sp_table = $('#sp_table').DataTable();
            p_table = $('#p_table').DataTable();
            scheduling_table = $('#scheduling_table').DataTable();
            $('#sp_table').addClass('custom-table-class');
            $('#scheduling_table').addClass('custom-table-class');

            planningTable = $('#planningTable').DataTable();
            sessionStorage.clear();
            scheduling()

        });

        function scheduling(){
            var parent_product = {!! json_encode($parent_product) !!};
            // Extract product IDs from parent_product object
            var product_ids = Object.keys(parent_product).map(function(key) {
                return parent_product[key].product_id;
            });
            // Send the product IDs to the controller
            $.ajax({
                url: `{{ route('daily-production-planning.get_bom_process') }}`, // Replace with your actual route
                method: 'POST',
                data: {
                    product_ids: product_ids,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                response.forEach((bomData, index) => {
                    const parentId = product_ids[index];
                    let parentQty = 0;
                    let matchFound = false; // Flag to track if a match is found for parentId

                    // Find the corresponding row in p_table with the matching parentId
                    const $parentRow = $("#p_table tbody").find(".main_product_id[value='" + parentId + "']").closest('tr');
                    if ($parentRow.length > 0) {
                        const main_est_plan_qty = parseFloat($parentRow.find(".est_plan_qty").val());

                        // Iterate through child parts to find a match
                        $("#sp_table tbody").find(".child_part_id").each(function() {
                            const child_part_id = $(this).val();
                            if (child_part_id == parentId) {
                                const child_est_plan_qty = parseFloat($(this).closest('tr').find(".est_plan_qty").val());
                                const new_qty = child_est_plan_qty + main_est_plan_qty;
                                $(this).closest('tr').find(".est_plan_qty").val(new_qty); // Update the child quantity
                                parentQty = new_qty; // Update the new_est_qty
                                matchFound = true; // Set match found to true
                            }
                        });

                        if (!matchFound) {
                            parentQty = main_est_plan_qty; // Set parentQty to main_est_plan_qty if no match is found
                        }
                    }

                    // Call processBomData with the updated parentQty
                    processBomData([bomData], parentId, parentQty, scheduling_table);
                });
            },
            error: function(xhr, status, error) {
                console.error('Error sending product IDs:', error);
            }
            });
        }

        function processBomData(bomData, parentProductId, parentQty, schedulingTable,level = 0) {
            bomData.forEach((bom, index) => {
                const partNo = bom.bom.products.part_no;
                const product_id = bom.bom.products.id;
                const partName = bom.bom.products.part_name;
                const model = bom.bom.products.model;
                const variance = bom.bom.products.variance;
                const typeOfProduct = bom.bom.products.type_of_products.type;

                // Process the main BOM processes
                bom.processes.forEach((process, processIndex) => {
                    const processNo = process.process_no;
                    const ct = process.ct;
                    const json_decodedsubparts = JSON.parse(process.sub_part_ids);
                    const subPartNames = json_decodedsubparts.map(subPartId => {
                        const product = all_products.find(product => product.id == subPartId);
                        return product ? product.part_name : '';
                    }).filter(name => name !== '').join(', ');

                    // Process raw_part_ids to get names
                    const json_decodedchildPart = JSON.parse(process.raw_part_ids);
                    const childPartNames = json_decodedchildPart.map(childPartId => {
                        const product = all_products.find(product => product.id == childPartId);
                        return product ? product.part_name : '';
                    }).filter(name => name !== '').join(', ');

                    let currentData = [
                        schedulingTable.rows().count() + 1,
                        partNo + `<input type="hidden" class="product_id" value="${product_id}">`,
                        partName,
                        model,
                        variance,
                        typeOfProduct,
                        process.process.name,
                        processNo,
                        subPartNames,
                        childPartNames,
                        ct + `<input type="hidden" class="ct" value="${ct}">`,
                        parentQty,
                        `<button class="btn btn-primary openModal" data-bs-toggle="modal" data-bs-target="#exampleModal" type="button" >Planning</button>`, // Customize action button or data
                    ];

                    schedulingTable.row.add(currentData).draw();

                    // Process subparts if any
                    if (bom.subParts && bom.subParts.length > 0) {
                        bom.subParts.forEach(subPart => {
                            const subPartProduct = subPart.subPart.product;
                            const subPartQty = subPart.subPart.qty * parentQty; // Adjust quantity based on parent quantity
                        });
                    }
                });

            });
        }
        function addRow(button) {
            // Clone the row and get the data from it
                var row = $(button).closest('tr').clone(true);

            // Get the product_ID from the closest .product_id input field
            let product_ID = $(button).closest('tr').find('.product_id').val();

            // Get the current number of rows in the table to create a new reference number
            var rowCounter = $('#planningTable tr').length;

            // Construct new row data
            var rowData = [
                `${$('#ref_no').val()}/${$('.process_text').text()}/${rowCounter}
                    <input type="hidden" class="form-control pro_order_no" value="${$('#ref_no').val()}/${$('.process_text').text()}/${rowCounter}">
                    <input type="hidden" class="form-control product_id" value="${product_ID}">`,
                `<input type="date" class="form-control planned_date" required>`,
                `<select class="form-select custom-select op_name w-100" multiple>
                    @foreach ($users as $user)
                        <option value="{{ $user->id }}">{{ $user->user_name }}</option>
                    @endforeach
                </select>`,
                `<select class="form-select custom-select shift" required>
                    <option value="AM">AM</option>
                    <option value="PM">PM</option>
                </select>`,
                `<select class="form-select custom-select spec_break" required>
                    <option value="Normal Hour">Normal Hour</option>
                    <option value="OT">OT</option>
                </select>`,
                `<input type="number" class="form-control qty" required>`,
                `<select class="form-select machine custom-select" required>
                    @foreach ($machines as $machine)
                        <option value="{{ $machine->id }}">{{ $machine->name }}</option>
                    @endforeach
                </select>`,
                `<select class="form-select tonnage custom-select" required>
                    @foreach ($machine_tonnages as $machine_tonnage)
                        <option value="{{ $machine_tonnage->id }}">{{ $machine_tonnage->tonnage }}</option>
                    @endforeach
                </select>`,
                `<input type="text" class="form-control cavity" name="cavity" required>`,
                `<a href="" class="btn btn-primary d-none proceed">Proceed</a>`,
                `<div style="display:flex">
                    <button type="button" class="btn btn-success btn-sm me-2" onclick="addRow(this)"><i class="bi bi-plus"></i></button>
                    <button type="button" class="btn btn-danger btn-sm" onclick="removeRow(this)"><i class="bi bi-dash"></i></button>
                </div>`
            ];

            // Add the cloned data as a new row in DataTable
            $('#planningTable').DataTable().row.add(rowData).draw(false).node();
            $(dat).find('.op_name').select2({
                dropdownParent: $("#exampleModal")
            });

            // Trigger any additional required events
            $('.qty').trigger('keyup');
        }

            function removeRow(button) {
                // Check if there is more than one row
                if ($('#planningTable tr').length > 2) { // Including header row
                    // Find the row index and remove it
                    planningTable.row($(button).closest('tr')).remove().draw(false);
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
            let product_ID = $(this).closest('tr').find('.product_id').val();

            let dppds = @json($dppds);
            let data = [];
            let dppds_flag = false;

            $.each(dppds, function(index, dppd) {
                if(dppd.product_id == product_ID) {
                    let rowData = {};
                    rowData['planned_date'] = dppd.planned_date;
                    rowData['pro_order_no'] = dppd.pro_order_no;

                    // Decode op_name JSON string
                    rowData['op_name'] = JSON.parse(dppd.op_name);

                    rowData['shift'] = dppd.shift;
                    rowData['spec_break'] = dppd.spec_break;
                    rowData['qty'] = dppd.planned_qty;
                    rowData['machine'] = dppd.machine;
                    rowData['tonnage'] = dppd.tonnage;
                    rowData['cavity'] = dppd.cavity;
                    rowData['disabled'] = true;
                    data.push(rowData);
                    dppds_flag = true;
                }
            });


            if(dppds_flag == true) {
                sessionStorage.setItem(`modalData${product_ID}`, JSON.stringify(data));
            }

            let error_div2 = $('#error_div2');
            if (error_div2.hasClass('d-none')) {
                // Do something if error_div2 has the class 'd-none'
            } else {
                error_div2.addClass('d-none');
            }
            let success_div2 = $('#success_div2');
            if (success_div2.hasClass('d-none')) {
                // Do something if success_div2 has the class 'd-none'
            } else {
                success_div2.addClass('d-none');
            }
            let part_no = $(this).closest('tr').find('td:eq(1)').text();
            let part_name = $(this).closest('tr').find('td:eq(2)').text();
            let process = $(this).closest('tr').find('td:eq(6)').text();
            let total_plan_qty = $(this).closest('tr').find('td:eq(11)').text();
            let ref_no = $('#ref_no').val();
            $('.product_ids').val(product_ID);
            let storedData = sessionStorage.getItem(`modalData${product_ID}`);
            // Reset row counter
            rowCounter = 1;
            // Clear existing rows in the table
            planningTable.clear().draw();
            if (storedData) {
                storedData = JSON.parse(storedData);

                storedData.forEach(element => {
                    var newRow2 = planningTable.row.add([
                        `${element.pro_order_no}` + `<input type="hidden" class="form-control pro_order_no" value="${element.pro_order_no}">
                        <input type="hidden" class="form-control product_id" value="${product_ID}">`,
                        `<input type="date" class="form-control planned_date" value='${element.planned_date}' required>`,
                        `<select class="form-select custom-select op_name w-100" multiple>
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}">{{ $user->user_name }}</option>
                            @endforeach
                        </select>`,
                        `<select class="form-select custom-select shift" required>
                            <option value="AM">AM</option>
                            <option value="PM">PM</option>
                        </select>`,
                        `<select class="form-select custom-select spec_break" required>
                            <option value="Normal Hour">Normal Hour</option>
                            <option value="OT">OT</option>
                        </select>`,
                        `<input type="number" class="form-control qty" value='${element.qty}' required>`,
                        `<select class="form-select machine custom-select" required>
                                <option value="">Select Machine</option>
                        </select>`,
                        `<select class="form-select tonnage custom-select" required>
                            <option value="" selected >Select Machine Tonnage</option>
                            @foreach ($machine_tonnages as $machine_tonnage)
                                <option value="{{ $machine_tonnage->id }}">{{ $machine_tonnage->tonnage }}</option>
                            @endforeach
                        </select>`,
                        `<input type="text" class="form-control cavity" name="cavity" value='${element.cavity}' required>`,
                        `<a href="" class="btn btn-primary proceed">Proceed</a>`,
                        `<div style="display:flex">
                            <button type="button" class="btn btn-success btn-sm me-2 d-none" onclick="addRow(this)"><i class="bi bi-plus"></i></button>
                            <button type="button" class="btn btn-danger btn-sm d-none" onclick="removeRow(this)"><i class="bi bi-dash"></i></button>
                        </div>`
                    ]).draw(false).node();
                    $(newRow2).find('.op_name').select2({
                            dropdownParent: $("#exampleModal")
                        });
                    $('.select2-container').addClass('w-100');

                    const selectedOpNames = element.op_name;
                    $(newRow2).find('.op_name').val(selectedOpNames).trigger('change');
                    $(newRow2).find('.shift option').each(function() {
                        if ($(this).val() == element.shift) {
                            $(this).prop('selected', true);
                        }
                    });
                    $(newRow2).find('.spec_break option').each(function() {
                        if ($(this).val() == element.spec_break) {
                            $(this).prop('selected', true);
                        }
                    });
                    $(newRow2).find('.machine option').each(function() {
                        if ($(this).val() == element.machine) {
                            $(this).prop('selected', true);
                        }
                    });
                    $(newRow2).find('.tonnage option').each(function() {
                        if ($(this).val() == element.tonnage) {
                            $(this).prop('selected', true);
                        }
                    });
                    $('.ref_no_text').text(ref_no);
                    $('.part_no_text').text(part_no);
                    $('.part_name_text').text(part_no);
                    $('.process_text').text(process);
                    $('.total_plan_qty_text').text(total_plan_qty);
                    $('.qty').trigger('keyup');



                    $(newRow2).find('.planned_date').prop('disabled',true);
                    $(newRow2).find('.op_name').prop('disabled',true);
                    $(newRow2).find('.shift').prop('disabled',true);
                    $(newRow2).find('.spec_break').prop('disabled',true);
                    $(newRow2).find('.qty').prop('disabled',true);
                    $(newRow2).find('.machine').prop('disabled',true);
                    $(newRow2).find('.tonnage').prop('disabled',true);
                    $(newRow2).find('.cavity').prop('disabled',true);
                    $('.save_planning').prop('disabled',true);
                });
            } else {
                var newRow = planningTable.row.add([
                    `${ref_no}/${process}/${rowCounter}` + `<input type="hidden" class="form-control pro_order_no" value="${ref_no}/${process}/${rowCounter}">
                    <input type="hidden" class="form-control product_id" value="${product_ID}">`,
                    `<input type="date" class="form-control planned_date" required>`,
                    `<select class="form-select custom-select op_name w-100" multiple>
                        @foreach ($users as $user)
                            <option value="{{ $user->id }}">{{ $user->user_name }}</option>
                        @endforeach
                    </select>`,
                    `<select class="form-select custom-select shift" required>
                        <option value="AM">AM</option>
                        <option value="PM">PM</option>
                    </select>`,
                    `<select class="form-select custom-select spec_break" required>
                        <option value="Normal Hour">Normal Hour</option>
                        <option value="OT">OT</option>
                    </select>`,
                    `<input type="number" class="form-control qty" required>`,
                    `<select class="form-select machine custom-select" required>
                            <option value="">Select Machine</option>
                    </select>`,
                    `<select class="form-select tonnage custom-select" required>
                    <option value="" selected >Select Machine Tonnage</option>
                    @foreach ($machine_tonnages as $machine_tonnage)
                        <option value="{{ $machine_tonnage->id }}">{{ $machine_tonnage->tonnage }}</option>
                    @endforeach
                    </select>`,
                    `<input type="text" class="form-control cavity" name="cavity" required>`,
                    `<a href="" class="btn btn-primary d-none proceed">Proceed</a>`,
                    `<div style="display:flex">
                        <button type="button" class="btn btn-success btn-sm me-2" onclick="addRow(this)"><i class="bi bi-plus"></i></button>
                        <button type="button" class="btn btn-danger btn-sm" onclick="removeRow(this)"><i class="bi bi-dash"></i></button>
                    </div>`
                ]).draw(false).node();
                $(newRow).find('.op_name').select2({
                            dropdownParent: $("#exampleModal")
                        });
                $('.select2-container').addClass('w-100');
                // Increment the row counter
                rowCounter++;


                $('.planned_date').prop('disabled',false);
                $('.op_name').prop('disabled',false);
                $('.shift').prop('disabled',false);
                $('.spec_break').prop('disabled',false);
                $('.qty').prop('disabled',false);
                $('.machine').prop('disabled',false);
                $('.tonnage').prop('disabled',false);
                $('.cavity').prop('disabled',false);
                $('.save_planning').prop('disabled',false);
            }



            $('.ref_no_text').text(ref_no);
            $('.part_no_text').text(part_no);
            $('.part_name_text').text(part_no);
            $('.process_text').text(process);
            $('.total_plan_qty_text').text(total_plan_qty);
            $('.qty').trigger('keyup');



        });
        $(document).on('keyup change', '.qty', function() {
            let total = 0;
            $('#planningTable .qty').each(function() {
                total += +$(this).val();
            });
            $('.plan_qty_text').text(total);
        });

        function order_change(){
            var order_id = $("#order_id").val();
            var order = orders.find(o => o.id == order_id);
            if (order) {
                $('#customer_name').val(order.customers.name);
                $('#customer_id').val(order.customers.id);
                $('#order_month').val(order.order_month);

            } else {
                $('#customer').val('');
                $('#order_month').val('');
            }
        }
        function save_planning(){
            let scheduling = [];
            let formData = {};
            $('#planningTable tbody tr').each(function() {
                let planned_date = $(this).find('.planned_date').val();
                let product_id = $(this).find('.product_id').val();
                let pro_order_no = $(this).find('.pro_order_no').val();
                let op_name = $(this).find('.op_name').val();
                let shift = $(this).find('.shift').val();
                let spec_break = $(this).find('.spec_break').val();
                let planned_qty = $(this).find('.qty').val();
                let machine = $(this).find('.machine').val();
                let tonnage = $(this).find('.tonnage').val();
                let cavity = $(this).find('.cavity').val();
                let dpp_id = $('#dpp_id').val();
                let ct = 0
                $('#scheduling_table tbody tr').each(function() {
                    let main_product_id = $(this).find('.product_id').val();
                    if (main_product_id == product_id) {
                        ct = $(this).find('.ct').val();
                    }
                });

                scheduling.push({
                    dpp_id: dpp_id,
                    planned_date: planned_date,
                    product_id: product_id,
                    pro_order_no: pro_order_no,
                    op_name: op_name,
                    shift: shift,
                    spec_break: spec_break,
                    planned_qty: planned_qty,
                    machine: machine,
                    tonnage: tonnage,
                    cavity: cavity,
                    ct:ct
                });
                formData.scheduling = scheduling;
            });
            $.ajax({
                url: `{{ route('daily-production-planning.planning.store') }}`,  // Change to your actual URL
                method: 'POST',
                data: formData,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}' // Include CSRF token for security
                },
                success: function(response) {
                    if (response == 'success') {
                        $('#success_div2').html('Data Saved Successfully').removeClass('d-none');
                        $('.proceed').removeClass('d-none');
                        $('.planned_date').prop('disabled', true);
                        $('.op_name').prop('disabled', true);
                        $('.shift').prop('disabled', true);
                        $('.spec_break').prop('disabled', true);
                        $('.qty').prop('disabled', true);
                        $('.machine').prop('disabled', true);
                        $('.tonnage').prop('disabled', true);
                        $('.cavity').prop('disabled', true);
                        $('.save_planning').prop('disabled', true);
                        let product_ID = $('.product_ids').val();

                        let data = [];
                        $('#planningTable tbody tr').each(function() {
                        let rowData = {};
                        rowData['planned_date'] = $(this).find('.planned_date').val();
                        rowData['pro_order_no'] = $(this).find('.pro_order_no').val();

                        // Handle multi-select field 'op_name'
                        let opNames = [];
                        $(this).find('.op_name option:selected').each(function() {
                            opNames.push($(this).val());
                        });
                        rowData['op_name'] = opNames;

                        rowData['shift'] = $(this).find('.shift option:selected').val();
                        rowData['spec_break'] = $(this).find('.spec_break option:selected').val();
                        rowData['qty'] = $(this).find('.qty').val();
                        rowData['machine'] = $(this).find('.machine option:selected').val();
                        rowData['tonnage'] = $(this).find('.tonnage option:selected').val();
                        rowData['cavity'] = $(this).find('.cavity').val();
                        rowData['disabled'] = true;
                        data.push(rowData);
                    });

                    sessionStorage.setItem(`modalData${product_ID}`, JSON.stringify(data));
                    }

                },
                error: function(xhr) {
                    let errorMessage = 'An error occurred while saving data';

                    if (xhr.status === 422) {
                        const errors = xhr.responseJSON.errors;
                        errorMessage = '<ul>';
                        for (let field in errors) {
                            if (errors.hasOwnProperty(field)) {
                                errors[field].forEach(error => {
                                    // Extract only the field name from the error message
                                    const fieldName = error.split('.')[2]; // Get the first word
                                    console.log();
                                    errorMessage += `<li>${fieldName} field is required.</li>`;
                                });
                            }
                        }
                        errorMessage += '</ul>';
                    }

                    $('#error_div2').html(errorMessage).removeClass('d-none');
                }
            });
        }

    </script>
@endsection
