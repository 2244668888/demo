@extends('layouts.app')
@section('title')
    MONTHLY PRODUCTION PLANNING
@endsection
@section('button')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.0/xlsx.full.min.js"></script>
    <button type="button" class="btn btn-warning" id="export-btn">
        <i class="bi bi-download"></i> Export
    </button>
@endsection
@section('content')
    <style>
        #mainTable input {
            width: 100px;
        }
    </style>
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-lg-12">
                    <div class="col-lg-3 col-sm-4 col-12">
                        <h5>MONTHLY PRODUCTION PLANNING DETAILS</h5>
                    </div>
                </div>
            </div>
            <br>
            <form method="post" action="{{ route('ppc.monthly_production_planning.generate') }}"
                enctype="multipart/form-data" id="myForm">
                @csrf
                <div class="row mb-5">
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label for="rev_no" class="form-label">Order No.</label>
                            <select name="order_id" id="order_id" class="form-select" onchange="change_order_id()">
                                <option value="" disabled selected>Please Select</option>
                                @foreach ($orders as $order)
                                    <option value="{{ $order->id }}"
                                        @if (isset($fetched_orders)) @if ($fetched_orders[0]->id == $order->id)
                                        selected @endif
                                        @endif>{{ $order->order_no }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label for="ref_no" class="form-label">Select Customer</label>
                            <select name="customer_id" id="customer_id" class="form-select">
                                @php
                                    $customer_name = '';
                                    foreach ($customers as $customer) {
                                        if (isset($fetched_orders)) {
                                            if ($fetched_orders[0]->customer_id == $customer->id) {
                                                $customer_name = $customer->name;
                                            }
                                        }
                                    }
                                @endphp
                                <option
                                    value="@if (isset($fetched_orders)) {{ $fetched_orders[0]->customer_id }} @endif">
                                    {{ $customer_name }}</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label for="product_id" class="form-label">Select Order Month</label>
                            <input type="month" name="order_month" id="order_month" class="form-control"
                                value="{{ isset($fetched_orders[0]) ? \Carbon\Carbon::parse($fetched_orders[0]->order_month)->format('Y-m') : '' }}">
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label style="visibility: hidden" for="product_id" class="form-label">Select Order Month</label>
                            <br>
                            <button class="btn btn-primary" type="submit">Generate</button>
                        </div>
                    </div>
                </div>
            </form>
            @if (isset($fetched_orders))
                <div class="row">
                    <div class="col-md-12">
                        <div class="custom-tabs-container">
                            <ul class="nav nav-tabs" id="customTab" role="tablist">
                                @foreach ($fetched_orders[0]->order_detail as $index => $order_detail)
                                    <li class="nav-item" role="presentation">
                                        <a class="nav-link {{ $index == 0 ? 'active' : '' }}" id="tab-one"
                                            data-bs-toggle="tab" href="#{{ $order_detail->id }}" role="tab"
                                            aria-controls="{{ $order_detail->id }}"
                                            aria-selected="true">{{ $order_detail->products->part_no }}</a>
                                    </li>
                                @endforeach
                            </ul>
                            <div class="tab-content" id="customTabContent">
                                @foreach ($fetched_orders[0]->order_detail as $index => $order_detail)
                                    <div class="tab-pane fade {{ $index == 0 ? 'show active' : '' }}"
                                        id="{{ $order_detail->id }}" role="tabpanel">
                                        <div class="table-responsive mb-5">
                                            <table class="table table-bordered m-0 p_table">
                                                <thead>
                                                    <tr>
                                                        <th>Sr No.</th>
                                                        <th>Part No.</th>
                                                        <th>Part Name</th>
                                                        <th>Type of Product</th>
                                                        <th>Model</th>
                                                        <th>Variance</th>
                                                        <th>Current Inventory QTY</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>{{ $index + 1 }}</td>
                                                        <td>{{ $order_detail->products->part_no }}</td>
                                                        <td>{{ $order_detail->products->part_name }}</td>
                                                        <td>{{ $order_detail->products->type_of_products->type }}</td>
                                                        <td>{{ $order_detail->products->model }}</td>
                                                        <td>{{ $order_detail->products->variance }}</td>
                                                        <td>
                                                            @if ($inventory->isNotEmpty())
                                                                @foreach ($inventory as $inventory_product)
                                                                    @if ($inventory_product->product_id == $order_detail->product_id)
                                                                        @if ($inventory_product->qty != '' && $inventory_product->qty != null)
                                                                            {{ $inventory_product->qty }}
                                                                        @else
                                                                            0
                                                                        @endif
                                                                        @php
                                                                            $inventory_qty = $inventory_product->qty;
                                                                        @endphp
                                                                    @endif
                                                                @endforeach
                                                            @else
                                                                0
                                                            @endif

                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                        @foreach ($delievry_intructions as $delievry_intruction)
                                            <div class="table-responsive">
                                                <table class="table table-bordered m-0 d_table">
                                                    <thead>
                                                        <tr>
                                                            <th>DI Date</th>
                                                            <th>Estimated Inventory QTY</th>
                                                            <th>DI QTY</th>
                                                            <th>1D Buffer QTY</th>
                                                            <th>3D Buffer QTY</th>
                                                            <th>Total Required QTY</th>
                                                            <th>Balance to Plan</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($delievry_intruction->delivery_instruction_details as $delivery_instruction_detail)
                                                            @if ($delivery_instruction_detail->product_id == $order_detail->product_id)
                                                                @php
                                                                    $calendar_data = json_decode(
                                                                        $delivery_instruction_detail->calendar,
                                                                    );
                                                                    $first_DO_flag = 0;
                                                                @endphp

                                                                @foreach ($calendar_data as $index => $calendar)
                                                                    @if ($calendar->value != '')
                                                                        <tr>
                                                                            @php
                                                                                $di_date = date(
                                                                                    'd-m-Y',
                                                                                    strtotime(
                                                                                        $delievry_intruction->order
                                                                                            ->order_month .
                                                                                            '-' .
                                                                                            $calendar->day,
                                                                                    ),
                                                                                );

                                                                                // Find the next non-empty value
                                                                                $next_value = '0';
                                                                                for (
                                                                                    $i = $index + 1;
                                                                                    $i < count($calendar_data);
                                                                                    $i++
                                                                                ) {
                                                                                    if (
                                                                                        $calendar_data[$i]->value != ''
                                                                                    ) {
                                                                                        $next_value =
                                                                                            $calendar_data[$i]->value;
                                                                                        break;
                                                                                    }
                                                                                }

                                                                                // Calculate the sum of the next three non-empty values
                                                                                $next_three_days_qty = 0;
                                                                                $count = 0;
                                                                                for (
                                                                                    $i = $index + 1;
                                                                                    $i < count($calendar_data);
                                                                                    $i++
                                                                                ) {
                                                                                    if (
                                                                                        $calendar_data[$i]->value != ''
                                                                                    ) {
                                                                                        $next_three_days_qty +=
                                                                                            $calendar_data[$i]->value;
                                                                                        $count++;
                                                                                        if ($count == 3) {
                                                                                            break;
                                                                                        }
                                                                                    }
                                                                                }

                                                                                $total_required_qty =
                                                                                    $calendar->value +
                                                                                    $next_three_days_qty;
                                                                                $inventory_qty = isset($inventory_qty)
                                                                                    ? $inventory_qty
                                                                                    : 0;

                                                                                if ($first_DO_flag == 0) {
                                                                                    $remaining_inventory_qty = $inventory_qty;
                                                                                } else {
                                                                                    $remaining_inventory_qty = max(
                                                                                        0,
                                                                                        $inventory_qty -
                                                                                            $calendar->value,
                                                                                    );
                                                                                }

                                                                                $estimated_produced_qty =
                                                                                    $total_required_qty -
                                                                                    $remaining_inventory_qty;
                                                                            @endphp
                                                                            <td>{{ $di_date }}</td>
                                                                            <td>{{ $remaining_inventory_qty }}</td>
                                                                            <td>{{ $calendar->value }}</td>
                                                                            <td>{{ $next_value }}</td>
                                                                            <td>{{ $next_three_days_qty }}</td>
                                                                            <td>{{ $total_required_qty }}</td>
                                                                            <td>{{ $estimated_produced_qty }}</td>
                                                                            @php
                                                                                // Update the inventory_qty for the next iteration
                                                                                $inventory_qty = $remaining_inventory_qty;
                                                                            @endphp
                                                                        </tr>
                                                                        @php
                                                                            $first_DO_flag++;
                                                                        @endphp
                                                                    @endif
                                                                @endforeach
                                                            @endif
                                                        @endforeach


                                                    </tbody>
                                                </table>
                                            </div>
                                        @endforeach
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
    <script>
        $(document).ready(function() {
            $(document).on('mouseenter', '.select2-selection__rendered', function() {
                $('.select2-selection__rendered').removeAttr('data-bs-original-title');
            });
        });
        document.addEventListener("DOMContentLoaded", function() {
            document.getElementById("export-btn").addEventListener("click", exportToExcel);

            function exportToExcel() {
    const tables = document.querySelectorAll(".p_table, .d_table");
    var order_value = $('#order_id option:selected').text();
    var customer_value = $('#customer_id option:selected').text();
    var order_month = $('#order_month').val();

    const wb = XLSX.utils.book_new();

    tables.forEach((table, index) => {
        const ws = XLSX.utils.table_to_sheet(table);
        const sheetName = table.classList.contains("p_table") ? `Product_Sheet_${index + 1}` : `DI_Sheet_${index + 1}`;

        // Add custom headers
        const header = [
            ["Monthly Production Planning"],  // Main header
            ["Order", order_value],     // Order Value
            ["Customer", customer_value],  // Customer Value
            ["Order Month", order_month],    // Order Month
            []                               // Empty row to separate table data
        ];

        // Get the existing table data
        const existingData = XLSX.utils.sheet_to_json(ws, { header: 1 });

        // Combine headers with table data
        const combinedData = header.concat(existingData);

        // Convert combined data to a sheet
        const newWs = XLSX.utils.aoa_to_sheet(combinedData);

        // Style the first row (main header) only
        if (!newWs["A1"]) newWs["A1"] = { t: "s", v: "Monthly Production Planning" };

        const headerStyle = {
            font: {
                bold: true,
                sz: 16
            },
            alignment: {
                horizontal: "center",
                vertical: "center"
            }
        };

        if (!newWs["A1"].s) newWs["A1"].s = headerStyle;

        XLSX.utils.book_append_sheet(wb, newWs, sheetName);
    });

    XLSX.writeFile(wb, "Monthly_Production_Planning.xlsx");
}



        });
        var orders = {!! json_encode($orders) !!};
        var customers = {!! json_encode($customers) !!};

        function change_order_id() {
            var order_id = $("#order_id").val();
            var order = orders.find(p => p.id == order_id);
            var customer_id = order.customer_id;
            var customer = customers.find(c => c.id == customer_id);
            if (customer) {
                // Get the customer select element
                var customerSelect = $("#customer_id");
                // Clear previous options
                customerSelect.empty();
                // Append the customer as the selected option
                customerSelect.append(new Option(customer.name, customer.id));
            }
            var order_month = order.order_month
            $('#order_month').val(order_month);

        }

        $('#order_month').on('input', function() {

            var order_month = $(this).val();
            var order = orders.find(p => p.order_month == order_month);
            var orderSelect = $("#order_id");
            orderSelect.empty();
            orderSelect.append(new Option(order.order_no, order.id));
            change_order_id();
        })

        $('#myForm').on('submit', function() {
            $('.card-body').find('.table').DataTable().page.len(-1).draw();
        });
    </script>

@endsection
