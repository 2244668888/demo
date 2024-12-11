@extends('layouts.app')
@section('title')
    DAILY PRODUCTION PLANNING
@endsection
@section('content')
    <style>
        #mainTable input {
            width: 100px;
        }

        .custom-table-class {
            width: -webkit-fill-available !important;
        }

        .custom-select {
            width: auto !important;
        }
    </style>
    <div class="alert border-danger alert-dismissible fade show text-danger d-none" role="alert" id="error_div">

    </div>
    <div class="alert border-success alert-dismissible fade show text-success d-none" role="alert" id="success_div">

    </div>
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-lg-12">
                    <div class="col-lg-3 col-sm-4 col-12">
                        <h5 class="display-none">DAILY PRODUCTION PLANNING DETAILS</h5>
                        <h5 class="display-show d-none">PRODUCTION SCHEDULING</h5>
                    </div>
                </div>
            </div>
            <br>
            <form method="post" action="{{ route('daily-production-planning.generate') }}" enctype="multipart/form-data"
                id="myForm">
                @csrf
                <input type="hidden" class="hidden_planning_date"
                    value="{{ isset($planning_date) ? $planning_date : '' }}">
                <div class="row mb-5">
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label for="rev_no" class="form-label">Order No.</label>
                            <select name="order_id" onchange="order_change()" id="order_id" class="form-select">
                                <option value="" disabled selected>Please Select</option>
                                @foreach ($orders as $order)
                                    <option value="{{ $order->id }}"
                                        @if (isset($fetch_orders)) @if ($order->id == $fetch_orders->id)
                                        selected @endif
                                        @endif>{{ $order->order_no }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label for="customer" class="form-label">Customer</label>
                            <input type="text" readonly id="customer_name" class="form-control"
                                value="@if (isset($fetch_orders)) {{ $fetch_orders->customers->name }} @endif">
                            <input type="hidden" id="customer_id" class="form-control"
                                value="@if (isset($fetch_orders)) {{ $fetch_orders->customers->id }} @endif">
                            <input type="hidden" id="dpp_id" class="form-control">
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label for="product_id" class="form-label">Order Month</label>
                            <input type="month" readonly id="order_month" class="form-control"
                                value="@if (isset($fetch_orders)) {{ $fetch_orders->order_month }} @endif">
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-4 col-12 display-none">
                        <div class="mb-3">
                            <label for="planning_date" class="form-label">Planning Date</label>
                            <select name="planning_date" id="planning_date" class="form-select">
                                {{-- @if (isset($planning_date))
                                    <option value></option>{{$fetch_orders->order_month}}
                                @endif --}}
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label for="created_date" class="form-label">Created Date</label>
                            <input type="date" readonly name="created_date" id="created_date" class="form-control" @if (isset($fetch_orders))
                                value="{{ $created_date }}" @else value="{{ date('Y-m-d') }}" @endif>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label for="created_by" class="form-label">Created By</label>
                            <input type="text" readonly id="created_by" class="form-control"
                                value="{{ Auth::user()->user_name }}">
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label for="ref_no" class="form-label">Planning Ref No.</label>
                            <input type="text" readonly name="ref_no" id="ref_no"
                                value="@if (isset($fetch_orders)) {{ $ref_no }} @else {{ $dpp_ref_no }} @endif"
                                class="form-control" value="">
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-4 col-12 display-none">
                        <div class="mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select name="status" id="status" class="form-select">
                                <option value="">Please Select</option>
                                <option value="In Progress"
                                    @if (isset($fetch_orders)) @if ($status == 'In Progress') selected @endif
                                    @endif >In Progress</option>
                                <option value="Completed"
                                    @if (isset($fetch_orders)) @if ($status == 'Completed') selected @endif
                                    @endif>Completed</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-12 col-sm-12 col-12">
                        <div class="mb-3 d-flex gap-2 justify-content-end">
                            <label style="visibility: hidden" for="product_id" class="form-label">Select Order
                                Month</label>
                            <br>
                            <button class="btn btn-primary display-none" type="submit">Generate</button>
                        </div>
                    </div>
                </div>
            </form>
            @if (isset($fetch_orders))
                <div class="row display-none">
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
                                        <th>Balance To Plan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $product_ids = [];
                                        $estimated_planning_qty = 0;
                                    @endphp
                                    @foreach ($fetch_orders->order_detail as $order_detail)
                                        @php
                                            $product_ids[] = $order_detail->product_id;
                                        @endphp
                                        @foreach ($delievry_intructions[0]->delivery_instruction_details as $delivery_instruction_detail)
                                            @if ($delivery_instruction_detail->product_id == $order_detail->product_id)
                                                @php
                                                    $calendar_data = json_decode(
                                                        $delivery_instruction_detail->calendar,
                                                    );
                                                    $di_qty = 0;
                                                    $next_three_days_qty = 0;

                                                    // Calculate sum of first three days' values
for ($i = 0; $i < 3; $i++) {
    if (
        isset($calendar_data[$i]) &&
        $calendar_data[$i]->value != ''
                                                        ) {
                                                            $next_three_days_qty += $calendar_data[$i]->value;
                                                        }
                                                    }

                                                @endphp

                                                @foreach ($calendar_data as $index => $calendar)
                                                    @if ($calendar->value != '')
                                                        @php
                                                            $planning_day = date('d', strtotime($planning_date));
                                                            if ($calendar->day == $planning_day) {
                                                                $di_qty = $calendar->value;
                                                            }

                                                            $total_required_qty = $di_qty + $next_three_days_qty;
                                                        @endphp
                                                    @endif
                                                @endforeach
                                            @endif
                                        @endforeach
                                        <tr>
                                            <td>
                                                {{ $loop->iteration }}
                                                <input type="hidden" value="{{ $order_detail->product_id }}"
                                                    class="main_product_id">
                                            </td>
                                            <td>{{ $order_detail->products->part_no }}</td>
                                            <td>{{ $order_detail->products->part_name }}</td>
                                            <td>{{ $order_detail->products->type_of_products->type }}</td>
                                            <td>{{ $order_detail->products->model }}</td>
                                            <td>{{ $order_detail->products->variance }}</td>
                                            <td>{{ $order_detail->products->units->name ?? '' }}</td>
                                            @php
                                                $parent_product[$loop->iteration]['product_id'] =
                                                    $order_detail->product_id;
                                                $parent_product[$loop->iteration]['di_qty'] = $total_required_qty;
                                            @endphp
                                            <td>
                                                {{ $di_qty }}
                                                <input type="hidden" value="{{ $di_qty }}" class="di_qty">
                                            </td>
                                            <td>
                                                @if ($inventory->isNotEmpty())
                                                    @foreach ($inventory as $inventory_product)
                                                        @if ($inventory_product->product_id == $order_detail->product_id)
                                                            {{ $inventory_product->qty }}
                                                            <input type="hidden" value="{{ $inventory_product->qty }}"
                                                                class="inventory_product_qty">
                                                            @php
                                                                $inventory_qty = $inventory_product->qty;
                                                                $estimated_planning_qty =
                                                                    $total_required_qty - $inventory_qty;
                                                            @endphp
                                                        @endif
                                                    @endforeach
                                                @else
                                                    @php
                                                        $estimated_planning_qty = $total_required_qty;
                                                    @endphp
                                                    0
                                                    <input type="hidden" value="0" class="inventory_product_qty">
                                                @endif
                                            </td>

                                            <td>
                                                {{ $total_required_qty }}
                                                <input type="hidden" value="{{ $total_required_qty }}"
                                                    class="total_required_qty">
                                            </td>
                                            <td>
                                                <input type="number" name="est_plan_qty"
                                                    value="{{ $estimated_planning_qty }}"
                                                    class="form-control est_plan_qty">
                                            </td>
                                            {{-- @dd($order_detail->products->part_no) --}}


                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="row display-none">
                    <div class="col-lg-12 col-sm-12 col-12">
                        <div class="mb-3 text-end">
                            <button class="btn btn-primary" type="button" onclick="next()" id="next">next</button>
                        </div>
                    </div>
                    <div class="col-lg-12 col-sm-12 col-12 d-none" id="child_partdiv">
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
                                        <th>Balance to plan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-lg-12 col-sm-12 col-12">
                            <div class="mb-3 text-end">
                                <button class="btn btn-primary" type="button"
                                    onclick="save_wo_planning(true)">Save</button>
                                <button class="btn btn-primary" type="button" onclick="scheduling()">Next >
                                    Scheduling</button>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="row">
                    <div class="col-lg-12 col-sm-12 col-12 d-none display-show">
                        <div class="table-responsive mb-5">

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
            @endif
        </div>
        <div class="card-footer">
            <div class="d-flex gap-2 justify-content-start">
                <a type="button" class="btn btn-info" href="{{ route('daily-production-planning') }}">
                    <i class="bi bi-arrow-left"></i> Back
                </a>
            </div>
        </div>

        {{-- PLANNING MODAL --}}
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
                                <div style="display: flex;justify-content: flex-end;">Process: <span
                                        class="process_text"></span></div>
                                <div style="display: flex;justify-content: flex-end;">Total Plan Qty: <span
                                        class="total_plan_qty_text"></span></div>
                                <div style="display: flex;justify-content: flex-end;">Plan Qty: <span
                                        class="plan_qty_text"></span></div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="alert border-danger alert-dismissible fade show text-danger d-none" role="alert"
                                id="error_div2">

                            </div>
                            <div class="alert border-success alert-dismissible fade show text-success d-none"
                                role="alert" id="success_div2">

                            </div>
                        </div>
                        <br>
                        <div class="table-responsive" id="popUp">
                            <table class="table table-bordered m-0" id="planningTable">
                                <thead>
                                    <tr>
                                        <th>Production Order No.</th>
                                        <th>Planned Date</th>
                                        <th>Leader Name</th>
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
                        <button type="button" class="btn btn-primary save_planning"
                            onclick="save_planning()">Save</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        $('#myForm').on('submit', function() {
            $('.card-body').find('.table').DataTable().page.len(-1).draw();
        });

        var orders = {!! json_encode($orders) !!};
        var planningdateRoute = "{{ route('daily-production-planning.get_date') }}";
        var sp_table;
        var p_table;
        var scheduling_table;
        $(document).ready(function() {
            sp_table = $('#sp_table').DataTable();
            p_table = $('#p_table').DataTable();
            scheduling_table = $('#scheduling_table').DataTable();
            $('#sp_table').addClass('custom-table-class');
            $('#scheduling_table').addClass('custom-table-class');

            planningTable = $('#planningTable').DataTable();
            sessionStorage.clear();
        });

        function order_change() {
            var order_id = $("#order_id").val();
            var order = orders.find(o => o.id == order_id);
            if (order) {
                $('#customer_name').val(order.customers.name);
                $('#customer_id').val(order.customers.id);
                $('#order_month').val(order.order_month);
                $.ajax({
                    url: planningdateRoute, // URL to send the request
                    type: 'GET',
                    data: {
                        order_id: order_id
                    },
                    success: function(data) {
                        var select = $('#planning_date'); // Get the select element
                        select.empty(); // Clear any existing options

                        var addedDates = new Set(); // Create a Set to store unique dates

                        // Loop through the data and append to the select element
                        $.each(data, function(key, value) {
                            $.each(value.delivery_instruction_details, function(key, delivery_instruction_details) {
                                var calander = JSON.parse(delivery_instruction_details.calendar);
                                $.each(calander, function(key, calander_value) {
                                    var orderMonth = order.order_month;
                                    var splitDate = orderMonth.split("-");
                                    var formattedDate = splitDate[1] + "-" + splitDate[0]; // month-year
                                    var final_date = calander_value.day + '-' + formattedDate;

                                    var splitDate_final = final_date.split("-");

                                    // Reformat to YYYY-MM-DD
                                    var formattedDate = splitDate_final[2] + "-" + splitDate_final[1] + "-" + splitDate_final[0]; // year-month-day
                                    
                                    // Check if the date is already in the Set
                                    if (!addedDates.has(formattedDate)) {
                                        addedDates.add(formattedDate); // Add the date to the Set
                                        var $selectedAttr = formattedDate == $('.hidden_planning_date').val() ? 'selected' : '';
                                        select.append(
                                            `<option ${$selectedAttr} value="${formattedDate}">${final_date}</option>`
                                        );
                                    }
                                });
                            });
                        });
                    },
                    error: function(xhr, status, error) {
                        console.error("Error: " + error);
                    }
                });

            } else {
                $('#customer').val('');
                $('#order_month').val('');
            }
        }
        @if (isset($fetch_orders))
            order_change();
            var all_products = {!! json_encode($products) !!};

            function processSubParts(subParts, parentData, sp_table, parent_product, tableCheck) {
                // Access the correct object within parent_product using the product_id as key
               
                est_planning_qty = 0;
                total_rq_qty = 0;

                let promises = subParts.map(subPart => {
                    return Get_subPart_inventory_qty(subPart.subPart.product_id).then(inv_qty => {
                        parent_qty = 0;
                        const $parentRow1 = $("#p_table tbody").find(".main_product_id[value='" +
                            parentData.id + "']").closest('tr');

                        if ($parentRow1.length > 0) {
                            parent_qty1 = parseFloat($parentRow1.find(".est_plan_qty")
                                .val());

                            parent_qty = parent_qty1;
                        }
                        total_rq_qty = (subPart.subPart.qty * parent_qty);
                        est_planning_qty = total_rq_qty - inv_qty;

                        if (tableCheck) {
                            if (est_planning_qty <= 0) {} else {
                                let currentData1 = [
                                    sp_table.rows().count() + 1,
                                    parentData.part_no +
                                    `<input type="hidden" value="${subPart.subPart.product.id}" class="child_part_id">
                                    <input type="hidden" value="${parentData.id}" class="parent_part_id">`,
                                    subPart.subPart.product.part_no,
                                    subPart.subPart.product.part_name,
                                    subPart.subPart.product.model,
                                    subPart.subPart.product.variance,
                                    subPart.subPart.product.type_of_products.type,
                                    subPart.subPart.product.units.name,
                                    subPart.subPart.qty +
                                    `<input type="hidden" value="${subPart.subPart.qty}" class="subpart_qty">`,
                                    total_rq_qty +
                                    `<input type="hidden" value="${total_rq_qty}" class="total_required_qty">`,
                                    inv_qty +
                                    `<input type="hidden" value="${inv_qty}" class="inventory_qty">`,
                                    `${est_planning_qty}`,
                                    `<button class="btn btn-primary openModal" data-bs-toggle="modal" data-bs-target="#exampleModal" type="button" >Planning</button>`
                                ];
                                sp_table.row.add(currentData1).draw();
                            }
                        } else {
                            let currentData = [
                                sp_table.rows().count() + 1,
                                parentData.part_no +
                                `<input type="hidden" value="${subPart.subPart.product.id}" class="child_part_id">
                                <input type="hidden" value="${parentData.id}" class="parent_part_id">`,
                                subPart.subPart.product.part_no,
                                subPart.subPart.product.part_name,
                                subPart.subPart.product.model,
                                subPart.subPart.product.variance,
                                subPart.subPart.product.type_of_products.type,
                                subPart.subPart.product.units.name,
                                subPart.subPart.qty +
                                `<input type="hidden" value="${subPart.subPart.qty}" class="subpart_qty">`,
                                total_rq_qty +
                                `<input type="hidden" value="${total_rq_qty}" class="total_required_qty">`,
                                inv_qty +
                                `<input type="hidden" value="${inv_qty}" class="inventory_qty">`,
                                `<input type="number" class="form-control est_plan_qty" value="${est_planning_qty}">`,
                            ];
                            sp_table.row.add(currentData).draw();
                        }

                        if (subPart.hasBom) {
                            // Recursively process any sub-parts
                            if (subPart.bomTree.subParts && subPart.bomTree.subParts.length > 0) {
                                return processSubParts(subPart.bomTree.subParts, subPart.subPart.product,
                                    sp_table, parent_product, tableCheck);
                            }
                        }
                    }).catch(error => {
                        console.error('Error fetching inventory quantity:', error);
                    });
                });

                return Promise.all(promises);
            }

            var sub_parent_product = [];

            function next() {

                var ids = {!! json_encode($product_ids) !!};
                var parent_product = {!! json_encode($parent_product) !!};

                $('#child_partdiv').removeClass('d-none');
                $.ajax({
                    url: `{{ route('daily-production-planning.get_subparts') }}`,
                    method: 'POST',
                    data: {
                        ids: ids,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        sub_parent_product = response;
                        response.forEach(function(bom) {
                            if (bom != null) {
                                if (bom.subParts && bom.subParts.length > 0) {
                                    processSubParts(bom.subParts, bom.bom.products, sp_table,
                                        parent_product, false);
                                    $('#next').addClass('d-none');
                                }
                            }
                        });
                    },
                    error: function(xhr) {
                        console.log(xhr.responseText);
                    }
                });
            }

            function Get_subPart_inventory_qty(product_id) {
                return new Promise((resolve, reject) => {
                    $.ajax({
                        url: `{{ route('daily-production-planning.get_inventory_qty') }}`,
                        method: 'POST',
                        data: {
                            product_id: product_id,
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            if (Array.isArray(response) && response.length === 0) {
                                resolve(0);

                            } else {
                                resolve(response[0]['qty']);
                            }
                        },
                        error: function(xhr, status, error) {
                            reject(error);
                        }
                    });
                });
            }

            function processBomData(bomData, parentProductId, parentQty, schedulingTable, level = 0) {

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
                        if (parentQty <= 0) {} else {
                            let currentData = [
                                schedulingTable.rows().count() + 1,
                                partNo +
                                `<input type="hidden" class="product_id" value="${product_id}">`,
                                partName,
                                model,
                                variance,
                                typeOfProduct,
                                process.process?.name ?? '',
                                processNo,
                                subPartNames,
                                childPartNames,
                                ct + `<input type="hidden" class="ct" value="${ct}">`,
                                parentQty,
                                `<button class="btn btn-primary openModal" data-bs-toggle="modal" data-bs-target="#exampleModal" type="button" >Planning</button>`, // Customize action button or data
                            ];

                            schedulingTable.row.add(currentData).draw();
                        }
                        // Process subparts if any
                        if (bom.subParts && bom.subParts.length > 0) {
                            bom.subParts.forEach(subPart => {
                                const subPartProduct = subPart.subPart.product;
                                const subPartQty = subPart.subPart.qty *
                                    parentQty; // Adjust quantity based on parent quantity
                            });
                        }
                    });

                });
            }
            
            var globalBomData = [];
            function scheduling() {
                save_wo_planning(false);
                $('.display-none').addClass('d-none');
                $('.display-show').removeClass('d-none');
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
                        console.log(response);
                        globalBomData = response;
                        sub_parent_product.forEach(function(bom) {
                            if (bom != null) {
                                if (bom.subParts && bom.subParts.length > 0) {
                                    processSubParts(bom.subParts, bom.bom.products, scheduling_table,
                                        parent_product, true);
                                }
                            }
                        });
                        response.forEach((bomData, index) => {
                            const parentId = product_ids[index];
                            let parentQty = 0;
                            let matchFound = false; // Flag to track if a match is found for parentId

                            // Find the corresponding row in p_table with the matching parentId
                            const $parentRow = $("#p_table tbody").find(".main_product_id[value='" +
                                parentId + "']").closest('tr');
                            if ($parentRow.length > 0) {
                                const main_est_plan_qty = parseFloat($parentRow.find(".est_plan_qty")
                                    .val());

                                // Iterate through child parts to find a match
                                $("#sp_table tbody").find(".child_part_id").each(function() {
                                    const child_part_id = $(this).val();
                                    if (child_part_id == parentId) {
                                        const child_est_plan_qty = parseFloat($(this).closest(
                                            'tr').find(".est_plan_qty").val());
                                        const new_qty = child_est_plan_qty + main_est_plan_qty;
                                        $(this).closest('tr').find(".est_plan_qty").val(
                                            new_qty); // Update the child quantity
                                        parentQty = new_qty; // Update the new_est_qty
                                        matchFound = true; // Set match found to true
                                    }
                                });

                                if (!matchFound) {
                                    parentQty =
                                        main_est_plan_qty; // Set parentQty to main_est_plan_qty if no match is found
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
                dat = $('#planningTable').DataTable().row.add(rowData).draw(false).node();
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
                let product_ID = $(this).closest('tr').find('.child_part_id').val();
                $('.product_ids').val(product_ID);
                let storedData = sessionStorage.getItem(`modalData${product_ID}`);
                let process_text = process;
                // Function to get the highest row counter from saved data
                function getHighestRowCounter(data) {
                    if (!data || data.length === 0) return 0;
                    let highestCounter = 0;
                    data.forEach(item => {
                        let pro_order_no = item.pro_order_no;
                        let counter = parseInt(pro_order_no.split('/').pop());
                        if (counter > highestCounter) {
                            highestCounter = counter;
                        }
                    });
                    return highestCounter;
                }

                // Initialize rowCounter based on the highest saved value
                if (storedData) {
                    storedData = JSON.parse(storedData);
                    rowCounter = getHighestRowCounter(storedData) + 1;
                } else {
                    rowCounter = 1;
                }

                // Clear existing rows in the table
                planningTable.clear().draw();
                if (storedData) {
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
                        $(newRow2).find('.op_name option').each(function() {
                            if ($.inArray($(this).val(), selectedOpNames) !== -1) {
                                $(this).prop('selected', true);
                            }
                        });
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

                        $(newRow2).find('.planned_date').prop('disabled', true);
                        $(newRow2).find('.op_name').prop('disabled', true);
                        $(newRow2).find('.shift').prop('disabled', true);
                        $(newRow2).find('.spec_break').prop('disabled', true);
                        $(newRow2).find('.qty').prop('disabled', true);
                        $(newRow2).find('.machine').prop('disabled', true);
                        $(newRow2).find('.tonnage').prop('disabled', true);
                        $(newRow2).find('.cavity').prop('disabled', true);
                        $('.save_planning').prop('disabled', true);
                    });
                } else {
                    let allProcessNames = [];
                    globalBomData.filter(bom => bom !== null).forEach(bom => {
                        if (bom.processes && Array.isArray(bom.processes)) {
                            bom.processes.forEach(process => {
                                allProcessNames.push(process.process.name);
                            });
                        }
                    });
                    var machineTonnages = [];
                    if (allProcessNames.includes(process_text)) {
                        let processData = globalBomData.find(bom => 
                            bom.processes && bom.processes.some(process => process.process.name === process_text)
                        );
                        
                        if (processData) {
                            machineTonnages = processData.processes.map(process => ({
                                id: process.machine_tonnage.id,       // Use the actual ID from your data
                                tonnage: process.machine_tonnage.tonnage // Use the name/tonnage as the display text
                            }));
                        } else {
                            console.warn("No process data found for the selected process.");
                        }
                    } else {
                        console.warn("Selected process not found in any of the BOMs.");
                    }
                    var newRow = planningTable.row.add([
                        `${ref_no}/${process}/${rowCounter}` + `<input type="hidden" class="form-control pro_order_no" value="${ref_no}/${process}/${rowCounter}">
                        <input type="hidden" class="form-control product_id" value="${$(this).closest('tr').find('.product_id').val()}">`,
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
                                <option value="" selected>Select Machine Tonnage</option>
                            </select>`,
                        `<input type="text" class="form-control cavity" name="cavity" required>`,
                        `<a href="" class="btn btn-primary d-none proceed">Proceed</a>`,
                        `<div style="display:flex">
                            <button type="button" class="btn btn-success btn-sm me-2" onclick="addRow(this)"><i class="bi bi-plus"></i></button>
                            <button type="button" class="btn btn-danger btn-sm" onclick="removeRow(this)"><i class="bi bi-dash"></i></button>
                        </div>`
                    ]).draw(false).node();
                    let tonnageDropdown = $(newRow).find('.tonnage');
                    tonnageDropdown.empty();
                    tonnageDropdown.append('<option value="" selected>Select Machine Tonnage</option>');
                    machineTonnages.forEach(({ id, tonnage }) => {
                        tonnageDropdown.append(`<option value="${id}">${tonnage}</option>`);
                    });
                    $(newRow).find('.op_name').select2({
                        dropdownParent: $("#exampleModal")
                    });
                    $('.select2-container').addClass('w-100');
                    rowCounter++;

                    $('.planned_date').prop('disabled', false);
                    $('.op_name').prop('disabled', false);
                    $('.shift').prop('disabled', false);
                    $('.spec_break').prop('disabled', false);
                    $('.qty').prop('disabled', false);
                    $('.machine').prop('disabled', false);
                    $('.tonnage').prop('disabled', false);
                    $('.cavity').prop('disabled', false);
                    $('.save_planning').prop('disabled', false);
                }

                $('.ref_no_text').text(ref_no);
                $('.part_no_text').text(part_no);
                $('.part_name_text').text(part_no);
                $('.process_text').text(process);
                $('.total_plan_qty_text').text(total_plan_qty);
                sessionStorage.setItem('total_plan_qty', total_plan_qty);
            });

            $(document).on('keyup change', '.qty', function() {
                let total = 0;
                $('#planningTable .qty').each(function() {
                    total += +$(this).val();
                });
                $('.plan_qty_text').text(total);
            });

            function save_wo_planning(flag) {
                let formData = {
                    order_id: $('#order_id').val(),
                    ref_no: $('#ref_no').val(),
                    customer_id: $('#customer_id').val(),
                    planning_date: $('#planning_date').val(),
                    created_date: $('#created_date').val(),
                    created_by: $('#created_by').val(),
                    status: $('#status').val()
                };

                let products = [];
                let childParts = [];
                $('#p_table tbody tr').each(function() {
                    let product_id = $(this).find('.main_product_id').val();
                    let di_qty = $(this).find('.di_qty').val();
                    let inventory_qty = $(this).find('.inventory_product_qty').val();
                    let total_required_qty = $(this).find('.total_required_qty').val();
                    let est_plan_qty = $(this).find('.est_plan_qty').val();
                    if (product_id && di_qty && inventory_qty && total_required_qty && est_plan_qty) {
                        products.push({
                            product_id: product_id,
                            di_qty: di_qty,
                            inventory_qty: inventory_qty,
                            total_required_qty: total_required_qty,
                            est_plan_qty: est_plan_qty
                        });
                    }
                });
                $('#sp_table tbody tr').each(function() {
                    let product_id = $(this).find('.child_part_id').val();
                    let parent_part_id = $(this).find('.parent_part_id').val();
                    let subpart_qty = $(this).find('.subpart_qty').val();
                    let total_required_qty = $(this).find('.total_required_qty').val();
                    let inventory_qty = $(this).find('.inventory_qty').val();
                    let est_plan_qty = $(this).find('.est_plan_qty').val();
                    if (product_id && subpart_qty && total_required_qty && inventory_qty && est_plan_qty) {
                        childParts.push({
                            product_id: product_id,
                            parent_part_id: parent_part_id,
                            subpart_qty: subpart_qty,
                            inventory_qty: inventory_qty,
                            total_required_qty: total_required_qty,
                            est_plan_qty: est_plan_qty
                        });
                    }
                });

                // Add products array to formData
                formData.product_table = products;
                formData.childpart_table = childParts;
                // Validation

                let errors = [];
                if (!formData.order_id) {
                    errors.push('Order No Required');
                }
                if (!formData.ref_no) {
                    errors.push('Reference No Required');
                }
                if (!formData.customer_id) {
                    errors.push('Customer Required');
                }
                if (!formData.planning_date) {
                    errors.push('Planning Date Required');
                }
                if (!formData.created_date) {
                    errors.push('Created Date Required');
                }
                if (!formData.status) {
                    errors.push('Status Required');
                }

                // Show errors if any
                if (errors.length > 0) {
                    $('#error_div').html(errors.join('<br>')).removeClass('d-none');
                    return;
                }

                // AJAX request to save data
                $.ajax({
                    url: `{{ route('daily-production-planning.store') }}`, // Change to your actual URL
                    method: 'POST',
                    data: formData,
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}' // Include CSRF token for security
                    },
                    success: function(response) {
                        if (flag) {
                            window.location.href = `{{ route('daily-production-planning') }}`;
                        } else {
                            $('#dpp_id').val(response);
                            $('#success_div').html('Data Saved Successfully').removeClass('d-none');
                        }
                    },
                    error: function(xhr) {
                        $('#error_div2').html('An error occurred while saving data').removeClass('d-none');
                    }
                });
            }

            function save_planning() {
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
                    let ct = 0;
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
                        ct: ct
                    });
                });

                formData.scheduling = scheduling;

                $.ajax({
                    url: '{{ route('daily-production-planning.planning.store') }}', // Change to your actual URL
                    method: 'POST',
                    data: formData,
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}' // Include CSRF token for security
                    },
                    success: function(response) {
                        if (response.message == 'success') {
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

                            // Set href for each proceed button
                            let ids = response.ids;
                            $('.proceed').each(function(index) {
                                if (ids[index]) {
                                    $(this).attr('href', "{{ route('material_planning.create') }}/" +
                                        ids[index]); // Change 'your_url/' to your actual URL prefix
                                }
                            });

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
                                rowData['spec_break'] = $(this).find('.spec_break option:selected')
                                    .val();
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
                                        const fieldName = error.split('.')[2]; // Get the field name
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
        @endif

        $(document).ready(function () {
            $(document).on('change', '.tonnage', function () {
                const tonnageDropdown = $(this);
                const selectedTonnageId = tonnageDropdown.val();
                const machineDropdown = tonnageDropdown.closest('tr').find('.machine');

                machineDropdown.empty();
                machineDropdown.append('<option value="">Select Machine</option>');

                if (selectedTonnageId) {
                    $.ajax({
                        url: `{{ route('daily-production-planning.getmachinebytonnage', '') }}/${selectedTonnageId}`,
                        method: 'GET',
                        success: function (machines) {
                            machines.forEach(machine => {
                                machineDropdown.append(`<option value="${machine.id}">${machine.name}</option>`);
                            });
                        },
                        error: function () {
                            alert('Failed to fetch machines. Please try again.');
                        }
                    });
                }
            });
        });

    </script>
@endsection
