@extends('layouts.app')
@section('title')
    MATERIAL PLANNING
@endsection
@section('button')
    <button class="btn btn-info" onclick="window.history.back()">
        <i class="bi bi-arrow-left"></i> Back
    </button>
@endsection
@section('content')
    <style>
        #productTable input {
            width: 130px;
        }
    </style>
    <form method="post" action="{{ route('material_planning.store') }}" enctype="multipart/form-data" id="myForm">
        @csrf
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <h5>MATERIAL PLANNING FORM DETAILS</h5>
                </div>
                <br>
                <div class="row">
                    <div class="col-lg-4 col-sm-6 col-12">
                        <div class="mb-3">
                            <label for="pro_order_no1" class="form-label">Production Order No.</label>
                            <select name="pro_order_no1" id="pro_order_no1" class="form-select" onchange="change_po()">
                                <option value="" selected disabled>Please Select</option>
                                @foreach ($production_orders as $production_order)
                                    <option value="{{ $production_order->id }}" @selected($id == $production_order->id)>
                                        {{ $production_order->pro_order_no }}</option>
                                @endforeach
                            </select>
                            <input type="hidden" name="pro_order_no" id="pro_order_no">
                        </div>
                    </div>
                    <div class="col-lg-4 col-sm-6 col-12">
                        <div class="mb-3">
                            <label for="ref_no" class="form-label">Ref No.</label>
                            <input type="text" readonly value="{{ $mrf_no_no }}" name="ref_no" id="ref_no"
                                class="form-control">
                        </div>
                    </div>
                    <div class="col-lg-4 col-sm-6 col-12">
                        <div class="mb-3">
                            <label for="request_date" class="form-label">Request Date</label>
                            <input type="date" name="request_date" id="request_date" value="{{ date('Y-m-d') }}"
                                class="form-control">
                        </div>
                    </div>
                    <div class="col-lg-4 col-sm-6 col-12">
                        <div class="mb-3">
                            <label for="shift" class="form-label">Shift</label>
                            <input type="hidden" name="shift" id="shift" class="form-control">
                            <select name="shift1" disabled id="shift1" class="form-select">
                                <option value="AM">AM</option>
                                <option value="PM">PM</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-4 col-sm-6 col-12">
                        <label for="request_from" class="form-label col-4">Department</label>
                        <select name="request_from" id="request_from" class="form-select" style="width: 100% !important;">
                            <option value="" selected disabled>Please Select</option>
                            @foreach ($depts as $dept)
                                <option value="{{ $dept->id }}" @selected(old('request_from') == $dept->id)>
                                    {{ $dept->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-4 col-sm-6 col-12">
                        @php
                            $string = $production_order->pro_order_no;
                            $parts = explode(' ', $string);
                            $firstPart = explode('/', $parts[0]);
                            $secondPart = explode('/', $parts[1]);
                        @endphp
                        <label for="process" class="form-label col-4">Process</label>
                        <input type="text" readonly name="process" id="process" rows="1"
                            value={{ $secondPart[1] }} class="form-control rounded-4" placeholder="auto based on order no">
                    </div>

                    <div class="col-lg-4 col-sm-6 col-12">
                        <div class="mb-3">
                            <label for="plan_date" class="form-label">Plan Date</label>
                            <input type="date" readonly name="plan_date" id="plan_date" class="form-control">
                        </div>
                    </div>
                    <div class="col-lg-4 col-sm-6 col-12">
                        <label for="total_planned_qty" class="form-label col-4">Total Planned Qty</label>
                        <input type="text" readonly name="total_planned_qty" id="total_planned_qty"
                            class="form-control rounded-4">
                    </div>

                    <div class="col-lg-4 col-sm-6 col-12">
                        <div class="mb-3">
                            <label for="machine" class="form-label col-4">Machine</label>
                            <input type="text" readonly name="machine" id="machine" rows="1"
                                class="form-control rounded-4" placeholder="auto based on order no">
                        </div>
                    </div>

                    <div class="col-lg-12 col-sm-12 col-12">
                        <div class="mb-3">
                            <label for="remarks" class="form-label">Description</label>
                            <textarea name="remarks" id="remarks" rows="1" cols="12" class="form-control">{{ old('remarks') }}</textarea>
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
                                        <th>No.</th>
                                        <th>Part No.</th>
                                        <th>Part Name</th>
                                        <th>UOM</th>
                                        <th>Required Qty(BOM)</th>
                                        <th>Current Inventory Qty</th>
                                        <th>Request Qty</th>
                                        <th>Difference</th>
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
                    <div class="d-flex gap-2 justify-content-end col-12">
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
    <script>
        $(document).on('keyup', '.request_qty', function() {
            var request_qty = $(this).val(); // `this` now refers to the input field (request_qty)
            var required_qty = $(this).closest('tr').find('.required_bom_qty').val(); // Get the required_qty value
            var result = parseFloat(required_qty) - parseFloat(request_qty); // Calculate the difference
            $(this).closest('tr').find('.difference').val(result); // Set the result in the difference field
        });
    </script>
    <script>
        var production_orders = {!! json_encode($production_orders) !!};
        let productTable;

        $(document).ready(function() {
            productTable = $('#productTable').DataTable();
            change_po();
            var totalPlanQty = sessionStorage.getItem('total_plan_qty');
            if (totalPlanQty) {
                $('#total_planned_qty').val(totalPlanQty);
            }
        });

        function change_po() {
            var production_orderId = $("#pro_order_no1").val();
            var production_order = production_orders.find(p => p.id == production_orderId);

            if (production_order) {
                $('#plan_date').val(production_order.planned_date);
                $('#shift1').val(production_order.shift);
                $('#shift1').trigger('change');
                $('#machine').val(production_order.machines?.name);
            } else {
                $('#plan_date').val('');
            }

            var product_ids = [production_order.product_id];
            $.ajax({
                url: `{{ route('daily-production-planning.get_subparts') }}`,
                method: 'POST',
                data: {
                    ids: product_ids,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    response[0].purchaseParts.forEach(function(subPart) {
                        processSubParts(subPart);
                    });
                },
                error: function(xhr) {
                    console.log(xhr.responseText);
                }
            });
        }

        function processSubParts(subParts) {
            return Get_subPart_inventory_qty(subParts.product_id).then(inv_qty => {
                let currentData = [
                    `${productTable.rows().count() + 1}<input type="hidden" value="${subParts.product_id}" class="product_id" name="products[${productTable.rows().count() + 1}][product_id]">`,
                    subParts.product?.part_no,
                    subParts.product?.part_name,
                    subParts?.product?.units?.name,
                    `<input type="text" readonly value="${$('#total_planned_qty').val() * subParts.qty}" class="form-control required_bom_qty" name="products[${productTable.rows().count() + 1}][required_qty]">`,
                    `<input type="text" readonly value="${inv_qty}" class="form-control inventory_qty" name="products[${productTable.rows().count() + 1}][inventory_qty]">`,
                    `<input type="number" step="0.001" value="0" class="form-control request_qty" name="products[${productTable.rows().count() + 1}][request_qty]">`,
                    `<input type="text" readonly class="form-control difference" value="${$('#total_planned_qty').val() * subParts.qty}" name="products[${productTable.rows().count() + 1}][difference]">`,
                ];
                productTable.row.add(currentData).draw();
            }).catch(error => {
                console.error('Error fetching inventory quantity:', error);
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

        $('#myForm').on('submit', function() {
            $('.card-body').find('.table').DataTable().page.len(-1).draw();
        });
    </script>
@endsection
