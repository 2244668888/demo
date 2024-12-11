@extends('layouts.app')
@section('title')
    PURCHASE PLANNING CREATE
@endsection
@section('content')
    <style>
        table thead th,
        table tbody td {
            width: 350px !important;

        }

        #supplierTable {
            table-layout: fixed;
            /* Ensures that the column widths are fixed */
            width: 100%;
            /* Makes sure the table takes full width */
        }

        #supplierTable th,
        #supplierTable td {
            word-wrap: break-word;
            /* If content overflows, break the word */
        }
    </style>
    <div class="card">
        <form method="post" action="{{ route('purchase_planning.store') }}" enctype="multipart/form-data" id="myForm">
            @csrf
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label for="created_by" class="form-label">Created By</label>
                            <input type="text" readonly name="created_by" id="created_by" class="form-control"
                                value="{{ Auth::user()->user_name }}">
                        </div>
                    </div>
                </div>
                <br>
                <div class="row">
                    <h5>PURCHASE PLANNING DETAILS</h5>
                </div>
                <br>
                <div class="row">
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label for="ref_no" class="form-label">PP Registration No</label>
                            <input type="text" name="ref_no" id="ref_no" readonly class="form-control"
                                value="{{ $ref_no }}">
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label for="order_id" class="form-label">Order No <a href="#" target="_blank"
                                    id="so_view" type="button">
                                    <i class="bi bi-eye"></i></a></label>
                            <select name="order_id" onchange="order_change()" id="order_id" class="form-select"
                                aria-label="Default select example">
                                <option value="" selected disabled>Please Select</option>
                                @foreach ($orders as $order)
                                    <option value="{{ $order->id }}" @selected(old('order_id') == $order->id)>{{ $order->order_no }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label for="date" class="form-label">Created Date</label>
                            <input type="date" name="date" id="date" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label for="order_date" class="form-label">Order Date</label>
                            <input type="text" id="order_date" class="form-control" readonly>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label for="order_month" class="form-label">Order Month</label>
                            <input type="text" id="order_month" class="form-control" readonly>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label for="attachment" class="form-label">Attachment</label><br>
                            <a href="#" id="attachment" target="_blank"><i
                                    class="bi bi-download text-success"></i></a>
                        </div>
                    </div>
                </div>
                <br>
                <div class="row">
                    <h5>CUSTOMER DETAILS</h5>
                </div>
                <br>
                <div class="row">
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label for="customer_name" class="form-label">Customer Name</label>
                            <input type="text" id="customer_name" class="form-control" readonly>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label for="pic_name" class="form-label">PIC Name</label>
                            <input type="text" id="pic_name" class="form-control" readonly>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label for="pic_email" class="form-label">PIC Email</label>
                            <input type="text" id="pic_email" class="form-control" readonly>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label for="pic_phone" class="form-label">PIC Phone No (Mobile/Work)</label>
                            <input type="text" id="pic_phone" class="form-control" readonly>
                        </div>
                    </div>
                </div>
                <br>
                <div class="row">
                    <h5>PRODUCT DETAILS</h5>
                </div>
                <br>
                <div class="row">
                    <div class="table-responsive">
                        <table class="table table-bordered m-0" id="productTable">
                            <thead>
                                <tr>
                                    <th>Sr No</th>
                                    <th>Part No</th>
                                    <th>Part Name</th>
                                    <th>Type of Product</th>
                                    <th>Product Qty</th>
                                    <th>Total Qty (1 Month Firm + 3 Month Forecast)</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
                <br>
                <div class="row">
                    <h5>PLANNING DETAILS</h5>
                </div>
                <br>
                <div class="row">
                    <div class="table-responsive">
                        <table class="table table-bordered m-0" id="planningTable">
                            <thead>
                                <tr>
                                    <th>Sr No</th>
                                    <th>Part No</th>
                                    <th>Part Name</th>
                                    <th>Type of Product</th>
                                    <th>Unit</th>
                                    <th>Total Qty (1 Firm + 3 Forecast)</th>
                                    <th>Inventory Qty</th>
                                    <th>Balance</th>
                                    <th>MOQ</th>
                                    <th>Qty Planning</th>
                                    <th>Supplier</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="row">
                        <div class="d-flex gap-2 justify-content-between col-12">
                            <input type="hidden" id="storedData" name="details">
                            <a type="button" class="btn btn-info" href="{{ route('purchase_planning.index') }}">
                                <i class="bi bi-arrow-left"></i> Back
                            </a>
                            <button type="button" class="btn btn-primary" id="saveForm">Submit</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
    {{-- SUPPLIER MODAL --}}
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalTitle" aria-modal="true"
        role="dialog">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <input type="hidden" id="product_ids">
                    <h5 class="modal-title" id="exampleModalTitle">
                        ADD SUPPLIER
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12 d-flex justify-content-between">
                            <div>Part No: <span class="part_no_text"></span></div>
                            <div>Qty Planning: <span class="qty_planning_text"></span></div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 d-flex justify-content-between">
                            <div>Part Name: <span class="part_name_text"></span></div>
                            <div>Qty Supplier: <span class="qty_supplier_text"></span></div>
                        </div>
                    </div>
                    <br>
                    <div class="table-responsive" id="popUp">
                        <table class="table table-bordered m-0 w-100" id="supplierTable">
                            <thead>
                                <tr>
                                    <th>Sr No</th>
                                    <th>Supplier Name</th>
                                    <th>Quantity</th>
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
    <script>
        var orderViewUrlTemplate = '{{ route('order.view', ':orderId') }}';
        let productTable;
        let planningTable;
        let supplierTable;
        let suppliers = [];
        let orderDetails;
        $(document).ready(function() {
            flatpickr("#date", {
                dateFormat: "d-m-Y",
                defaultDate: new Date()
            });
            productTable = $('#productTable').DataTable();
            planningTable = $('#planningTable').DataTable();
            supplierTable = $('#supplierTable').DataTable({
                autoWidth: false, // Disable automatic column width calculation
                columnDefs: [{
                        width: "50px",
                        targets: 0
                    }, // Sr No column
                    {
                        width: "200px",
                        targets: 1
                    }, // Supplier Name column
                    {
                        width: "150px",
                        targets: 2
                    }, // Quantity column
                    {
                        width: "50px",
                        targets: 3
                    } // Action column
                ]
            });
            order_change();
            sessionStorage.clear();
            suppliers = @json($suppliers);
        });

        var orders = {!! json_encode($orders) !!};

        function order_change() {
            var orderId = $("#order_id").val();
            var order = orders.find(p => p.id == orderId);
            if (order) {
                $('#pic_email').val(order.customers.pic_email);
                $('#customer_name').val(order.customers.name);
                $('#pic_name').val(order.customers.pic_name);
                $('#pic_phone').val(order.customers.phone);

                let orderMonth = order.order_month;
                let [year, month] = orderMonth.split('-');
                let formattedOrderMonth = `${month}-${year}`;
                $('#order_month').val(formattedOrderMonth);

                $('#order_date').val(order.date);

                orderDetails = order.order_detail;
                appendOrderDetails(order.order_detail);

                appendPlanningDetails(order.id);

                var orderViewUrl = orderViewUrlTemplate.replace(':orderId', orderId);
                $('#so_view').attr('href', orderViewUrl);
                if (order.attachment != null) {
                    $('#attachment').attr('href', `{{ asset('/order-attachments/') }}/${order.attachment}`);
                } else {
                    $('#attachment').attr('href', '#');
                }
            } else {
                $('#pic_email').val('');
                $('#customer_name').val('');
                $('#pic_name').val('');
                $('#pic_phone').val('');
                $('#order_month').val('');
                $('#order_date').val('');

                productTable.clear().draw();

                $('#so_view').attr('href', '#');
                $('#attachment').attr('href', '#');
            }
            sessionStorage.clear('suppliers');
        };

        function appendOrderDetails(orderDetails) {
            productTable.clear(); // Clear existing details
            orderDetails.forEach(detail => {
                var total_qty_firm3 = Number(detail.firm_qty) + Number(detail.n1_qty) + Number(detail.n2_qty) +
                    Number(detail.n3_qty);
                productTable.row.add([
                    productTable.rows().count() + 1,
                    detail.products.part_no,
                    detail.products.part_name,
                    detail.products.type_of_products.type ?? '',
                    `${detail.firm_qty}<input type="hidden" value="${detail.product_id}" name="products[${detail.product_id}][product_id]"><input type="hidden" value="${detail.firm_qty}" name="products[${detail.product_id}][product_qty]"><input type="hidden" value="${+detail.firm_qty + +detail.n3_qty}" name="products[${detail.product_id}][total_qty]">`,
                    total_qty_firm3
                ]).draw(false); // Append new details
            });
        }




        var subParts;
        // function appendPlanningDetails(orderId) {
        //     let rowIndex = 1;
        //     $.ajax({
        //         url: '{{ route('purchase_planning.bom.get') }}',
        //         method: 'GET',
        //         data: { id: orderId },
        //         success: function(response) {
        //             planningTable.clear(); // Clear existing details

        //             const productMap = new Map();

        //             // // Fetch purchase order and good receiving quantities
        //             // let purchaseOrderQuantities = response.purchaseOrderQuantities;
        //             // let goodReceivingQuantities = response.goodReceivingQuantities;

        //             response.forEach(function(item) {
        //                 if (item != null) {
        //                     const bom = item.bom;
        //                     const purchaseParts = item.purchaseParts;
        //                     subParts = item.subParts;

        //                     purchaseParts.forEach(function(purchasePart) {
        //                         const part = purchasePart;
        //                         const productId = part.product.id;

        //                         var order_qty = orderDetails.find(p => p.product_id == bom.products.id);
        //                         let total_qty = part.qty;
        //                         if (order_qty) {
        //                             total_qty = (order_qty.firm_qty * order_qty.n3_qty) * part.qty;
        //                         }

        //                         // // Calculate received, out, and outstanding quantities
        //                         // let received_qty = goodReceivingQuantities[productId] ? goodReceivingQuantities[productId].total_qty : 0;
        //                         // let out_qty = purchaseOrderQuantities[productId] ? purchaseOrderQuantities[productId].total_qty : 0;
        //                         // let outstanding_qty = out_qty - received_qty;

        //                         // Aggregate quantities for repeated products
        //                         if (productMap.has(productId)) {
        //                             const existingPart = productMap.get(productId);
        //                             existingPart.total_qty += total_qty;
        //                             existingPart.used_qty = parseFloat(part.used_qty);
        //                             existingPart.outstanding_qty = outstanding_qty; // Update outstanding quantity
        //                         } else {
        //                             productMap.set(productId, {
        //                                 product_id: productId,
        //                                 part_no: part.product.part_no,
        //                                 part_name: part.product.part_name,
        //                                 parent_part_name: bom.products.part_name ?? '',
        //                                 type_of_product: part.product?.type_of_products?.type ?? '',
        //                                 unit: part.product.units?.name ?? '',
        //                                 total_qty: total_qty,
        //                                 used_qty: parseFloat(part.used_qty),
        //                                 // outstanding_qty: outstanding_qty, // Store outstanding quantity
        //                                 moq: parseFloat(part.product.moq)
        //                             });
        //                         }
        //                     });
        //                 }
        //             });

        //             // Add aggregated data to the table
        //             productMap.forEach((part) => {
        //                 const balance = Math.max(part.moq - part.used_qty - part.outstanding_qty, 0);

        //                 planningTable.row.add([
        //                     rowIndex++, // Sr No
        //                     `${part.part_no}<input type="hidden" value="${part.product_id}" class="form-control product_id" name="planning[${rowIndex}][product_id]">`, // Part No
        //                     part.part_name,
        //                     part.parent_part_name,
        //                     part.type_of_product,
        //                     part.unit,
        //                     `${part.total_qty}<input type="hidden" value="${part.total_qty}" class="form-control total_qty" name="planning[${rowIndex}][total_qty]">`, // Total Qty
        //                     `${part.used_qty}`,
        //                     `<input type="number" readonly value="${balance}" class="form-control balance" name="planning[${rowIndex}][balance]">`, // Balance
        //                     `<input type="number" readonly value="${part.moq}" style="width:250px !important;" class="form-control moq" name="planning[${rowIndex}][moq]">`, // MOQ
        //                     `<input type="number" readonly value="0" class="form-control planning_qty" name="planning[${rowIndex}][planning_qty]">`, // Qty Planning
        //                     `<button type="button" class="btn btn-info openModal" data-bs-toggle="modal" data-bs-target="#exampleModal"><i class="bi bi-plus-square me-2"></i>Add</button>` // Supplier
        //                 ]).draw(false);
        //             });

        //             $.each(subParts, function(index, subPart) {
        //                 if (subPart.hasBom === true) {
        //                     try {
        //                         if (subPart.bomTree && subPart.bomTree.purchaseParts && subPart.bomTree.purchaseParts.length > 0) {
        //                                 item = subPart.bomTree?.bom;
        //                             $.each(subPart.bomTree.purchaseParts, function(pIndex, purchasePart) {
        //                                 console.log(purchasePart);

        //                                 const part = purchasePart;
        //                                 const productId = part.product.id;

        //                                 var order_qty = orderDetails.find(p => p.product_id == part.product.id);
        //                                 let total_qty = part.qty;
        //                                 if (order_qty) {
        //                                     total_qty = (order_qty.firm_qty * order_qty.n3_qty) * part.qty;
        //                                 }

        //                                 // Calculate received, out, and outstanding quantities
        //                                 // let received_qty = goodReceivingQuantities[productId] ? goodReceivingQuantities[productId].total_qty : 0;
        //                                 // let out_qty = purchaseOrderQuantities[productId] ? purchaseOrderQuantities[productId].total_qty : 0;
        //                                 // let outstanding_qty = out_qty - received_qty;

        //                                 if (productMap.has(productId)) {
        //                                     const existingPart = productMap.get(productId);

        //                                     $('#planningTable tbody tr').each(function() {
        //                                         const row = $(this);
        //                                         const partNo = row.find('td:eq(1)').text().trim();

        //                                         if (partNo === existingPart.part_no) {
        //                                             const currentTotalQty = parseFloat(row.find('td:eq(5)').text().trim()) || 0;
        //                                             const newTotalQty = currentTotalQty + existingPart.total_qty;
        //                                             row.find('td:eq(5)').text(newTotalQty);
        //                                             row.find('td:eq(5)').find('input.total_qty').val(newTotalQty);

        //                                             row.find('td:eq(6)').find('input.inventory_qty').val(existingPart.used_qty);

        //                                             return true;
        //                                         }
        //                                     });
        //                                 } else {
        //                                     productMap.set(productId, {
        //                                         product_id: productId,
        //                                         part_no: part.product.part_no,
        //                                         parent_part_name: item.products.part_name ?? '',
        //                                         part_name: part.product.part_name,
        //                                         type_of_product: part.product.type_of_products?.type ?? '',
        //                                         unit: part.product.units?.name ?? '',
        //                                         total_qty: total_qty,
        //                                         used_qty: parseFloat(part.used_qty),
        //                                         // outstanding_qty: outstanding_qty, // Store outstanding quantity
        //                                         moq: parseFloat(part.product.moq)
        //                                     });
        //                                 }
        //                                 productMap.forEach((part) => {
        //                                     const partNo = part.part_no;
        //                                     let rowExists = false;

        //                                     $('#planningTable tbody tr').each(function() {
        //                                         const existingPartNo = $(this).find('td:eq(1)').text().trim();
        //                                         if (existingPartNo === partNo) {
        //                                             rowExists = true;
        //                                             return false;
        //                                         }
        //                                     });

        //                                     if (!rowExists) {
        //                                         const balance = Math.max(part.moq - part.used_qty, 0);

        //                                         planningTable.row.add([
        //                                             rowIndex++,
        //                                             `${part.part_no}<input type="hidden" value="${part.product_id}" class="form-control product_id" name="planning[${rowIndex}][product_id]">`, // Part No
        //                                             part.part_name,
        //                                             part.parent_part_name,
        //                                             part.type_of_product,
        //                                             part.unit,
        //                                             `${part.total_qty}<input type="hidden" value="${part.total_qty}" class="form-control total_qty" name="planning[${rowIndex}][total_qty]">`, // Total Qty
        //                                             `${part.used_qty}`,
        //                                             `<input type="number" readonly value="${balance}" class="form-control balance" name="planning[${rowIndex}][balance]">`, // Balance
        //                                             `<input type="number" readonly value="${part.moq}" style="width:250px !important;" class="form-control moq" name="planning[${rowIndex}][moq]">`, // MOQ
        //                                             `<input type="number" readonly value="0" class="form-control planning_qty" name="planning[${rowIndex}][planning_qty]">`, // Qty Planning
        //                                             `<button type="button" class="btn btn-info openModal" data-bs-toggle="modal" data-bs-target="#exampleModal"><i class="bi bi-plus-square me-2"></i>Add</button>` // Supplier
        //                                         ]).draw(false);
        //                                     }
        //                                 });


        //                                     });



        //                         } else {
        //                             throw new Error('purchaseParts not found or is null');
        //                         }
        //                     } catch (error) {
        //                         console.log('Error: ' + error.message);
        //                     }
        //                 } else {
        //                     return true;
        //                 }
        //             });
        //         },
        //         error: function(xhr, status, error) {
        //             console.error('Error fetching planning details:', error);
        //         }
        //     });
        // }



        function appendPlanningDetails(orderId) {
            $.ajax({
                url: '{{ route('purchase_planning.bom.get') }}',
                method: 'GET',
                data: {
                    id: orderId
                },
                success: function(response) {
                    planningTable.rows().remove().draw(); // Clear existing details

                    const productMap = new Map();

                    response.forEach(function(item) {
                        if (item != null) {
                            const bom = item.bom;
                            const PurchaseParts = item.purchaseParts;

                            PurchaseParts.forEach(function(PurchasePart) {

                                const part = PurchasePart;
                                const productId = part.product.id;

                                var order_qty = orderDetails.find(p => p.product_id == bom
                                    .products.id);
                                var total_qty_firm3 = Number(order_qty.firm_qty) + Number(
                                    order_qty.n1_qty) + Number(order_qty.n2_qty) + Number(
                                    order_qty.n3_qty);

                                let total_qty = part.qty;
                                if (order_qty) {
                                    total_qty = total_qty_firm3 * part.qty;
                                }

                                // Aggregate quantities for repeated products
                                if (productMap.has(productId)) {
                                    const existingPart = productMap.get(productId);
                                    existingPart.total_qty += total_qty;
                                    existingPart.used_qty = parseFloat(part.used_qty ?? 0);
                                } else {
                                    productMap.set(productId, {
                                        product_id: productId,
                                        part_no: part.product.part_no,
                                        part_name: part.product.part_name,
                                        type_of_product: part.product.type_of_products
                                            .type ?? '',
                                        unit: part.product.units.name ?? '',
                                        total_qty: total_qty,
                                        used_qty: parseFloat(part.used_qty ?? 0),
                                        moq: parseFloat(part.product.moq)
                                    });
                                }
                            });
                        }
                    });

                    // Add aggregated data to the table
                    let rowIndex = 1;
                    productMap.forEach((part) => {
                        const balance = Math.max(part.total_qty - part.used_qty, 0);
                        console.log(part.total_qty);
                        console.log(part.used_qty);

                        planningTable.row.add([
                            rowIndex++, // Sr No
                            `${part.part_no}<input type="hidden" value="${part.product_id}" class="form-control product_id" name="planning[${rowIndex}][product_id]">`, // Part No
                            part.part_name, // Part Name
                            part.type_of_product, // Type of Product
                            part.unit, // Unit
                            `${part.total_qty}<input type="hidden" value="${part.total_qty}" class="form-control total_qty" name="planning[${rowIndex}][total_qty]">`, // Total Qty
                            `<input type="number" readonly value="${part.used_qty}" class="form-control inventory_qty" name="planning[${rowIndex}][inventory_qty]">`, // Inventory Qty
                            `<input type="number" readonly value="${balance}" class="form-control balance" name="planning[${rowIndex}][balance]">`, // Balance
                            `<input type="number" readonly value="${part.moq}" style="width:250px !important;" class="form-control moq" name="planning[${rowIndex}][moq]">`, // MOQ
                            `<input type="number" readonly value="0" class="form-control planning_qty" name="planning[${rowIndex}][planning_qty]">`, // Qty Planning
                            `<button type="button" class="btn btn-info openModal" data-bs-toggle="modal" data-bs-target="#exampleModal"><i class="bi bi-plus-square me-2"></i>Add</button>` // Supplier
                        ]).draw(false);
                    });

                    $.each(subParts, function(index, subPart) {
                        if (subPart.hasBom === true) {
                            try {
                                if (subPart.bomTree && subPart.bomTree.purchaseParts && subPart.bomTree
                                    .purchaseParts.length > 0) {
                                    item = subPart.bomTree?.bom;
                                    $.each(subPart.bomTree.purchaseParts, function(pIndex,
                                        purchasePart) {
                                        console.log(purchasePart);

                                        const part = purchasePart;
                                        const productId = part.product.id;

                                        var order_qty = orderDetails.find(p => p.product_id ==
                                            part.product.id);
                                        let total_qty = part.qty;
                                        if (order_qty) {
                                            total_qty = (order_qty.firm_qty * order_qty
                                                .n3_qty) * part.qty;
                                        }

                                        // Calculate received, out, and outstanding quantities
                                        // let received_qty = goodReceivingQuantities[productId] ? goodReceivingQuantities[productId].total_qty : 0;
                                        // let out_qty = purchaseOrderQuantities[productId] ? purchaseOrderQuantities[productId].total_qty : 0;
                                        // let outstanding_qty = out_qty - received_qty;

                                        if (productMap.has(productId)) {
                                            const existingPart = productMap.get(productId);

                                            $('#planningTable tbody tr').each(function() {
                                                const row = $(this);
                                                const partNo = row.find('td:eq(1)')
                                                    .text().trim();

                                                if (partNo === existingPart.part_no) {
                                                    const currentTotalQty = parseFloat(
                                                        row.find('td:eq(5)').text()
                                                        .trim()) || 0;
                                                    const newTotalQty =
                                                        currentTotalQty + existingPart
                                                        .total_qty;
                                                    row.find('td:eq(5)').text(
                                                        newTotalQty);
                                                    row.find('td:eq(5)').find(
                                                        'input.total_qty').val(
                                                        newTotalQty);

                                                    row.find('td:eq(6)').find(
                                                        'input.inventory_qty').val(
                                                        existingPart.used_qty);

                                                    return true;
                                                }
                                            });
                                        } else {
                                            productMap.set(productId, {
                                                product_id: productId,
                                                part_no: part.product.part_no,
                                                parent_part_name: item.products
                                                    .part_name ?? '',
                                                part_name: part.product.part_name,
                                                type_of_product: part.product
                                                    .type_of_products?.type ?? '',
                                                unit: part.product.units?.name ?? '',
                                                total_qty: total_qty,
                                                used_qty: parseFloat(part.used_qty),
                                                // outstanding_qty: outstanding_qty, // Store outstanding quantity
                                                moq: parseFloat(part.product.moq)
                                            });
                                        }
                                    });

                                    productMap.forEach((part) => {
                                        const partNo = part.part_no;
                                        let rowExists = false;

                                        $('#planningTable tbody tr').each(
                                            function() {
                                                const existingPartNo = $(this)
                                                    .find('td:eq(1)').text()
                                                    .trim();
                                                if (existingPartNo === partNo) {
                                                    rowExists = true;
                                                    return false;
                                                }
                                            });

                                        if (!rowExists) {
                                            const balance = Math.max(part.moq - part
                                                .used_qty, 0);

                                            planningTable.row.add([
                                                rowIndex++,
                                                `${part.part_no}<input type="hidden" value="${part.product_id}" class="form-control product_id" name="planning[${rowIndex}][product_id]">`, // Part No
                                                part.part_name,
                                                part.parent_part_name,
                                                part.type_of_product,
                                                part.unit,
                                                `${part.total_qty}<input type="hidden" value="${part.total_qty}" class="form-control total_qty" name="planning[${rowIndex}][total_qty]">`, // Total Qty
                                                `${part.used_qty}`,
                                                `<input type="number" readonly value="${balance}" class="form-control balance" name="planning[${rowIndex}][balance]">`, // Balance
                                                `<input type="number" readonly value="${part.moq}" style="width:250px !important;" class="form-control moq" name="planning[${rowIndex}][moq]">`, // MOQ
                                                `<input type="number" readonly value="0" class="form-control planning_qty" name="planning[${rowIndex}][planning_qty]">`, // Qty Planning
                                                `<button type="button" class="btn btn-info openModal" data-bs-toggle="modal" data-bs-target="#exampleModal"><i class="bi bi-plus-square me-2"></i>Add</button>` // Supplier
                                            ]).draw(false);
                                        }
                                    });
                                } else {
                                    throw new Error('purchaseParts not found or is null');
                                }
                            } catch (error) {
                                console.log('Error: ' + error.message);
                            }
                        } else {
                            return true;
                        }
                    });
                },
                error: function(xhr, status, error) {
                    console.error('Error fetching planning details:', error);
                }
            });
        }

        //SUPPLIER WORK
        function addRow(button) {
            // Clone the row and get the data from it
            var row = button.parentNode.parentNode.cloneNode(true);

            // Get all the selected supplier values in the current table
            var selectedSuppliers = [];
            document.querySelectorAll('.supplier').forEach(select => {
                selectedSuppliers.push(select.value);
            });

            let optionsHtml = '';
            let supplierAvailable = false; // Flag to check if any supplier is available

            // Loop through the suppliers and check if they're already selected
            suppliers.forEach(supplier => {
                if (!selectedSuppliers.includes(supplier.id.toString())) {
                    // If the supplier is not already selected, add to the options
                    optionsHtml += `<option value="${supplier.id}">${supplier.name}</option>`;
                    supplierAvailable = true; // Set the flag to true if any supplier is available
                }
            });

            // If no supplier is left to add, don't add the row
            if (!supplierAvailable) {
                $('#popUp').prepend(`
                    <div class="alert border-warning alert-dismissible fade show text-warning" role="alert">
                    <b>Warning!</b> Can't Add more Rows! No supplier left.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                `);
                return;
            }

            // Add the new row with available suppliers
            supplierTable.row.add([
                supplierTable.rows().count() + 1,
                `<select class="form-select supplier">${optionsHtml}</select>`,
                `<input type="number" class="form-control qty">`,
                `<button type="button" class="btn btn-success btn-sm me-2" onclick="addRow(this)"><i class="bi bi-plus"></i></button>
            <button type="button" class="btn btn-danger btn-sm" onclick="removeRow(this)"><i class="bi bi-dash"></i></button>`
            ]).draw(false);
            supplierTable.columns.adjust();

            resetSerialNumbers();

            // Trigger any additional required events
            $('.qty').trigger('keyup');
        }


        function removeRow(button) {
            // Check if there is more than one row
            if ($('#supplierTable tr').length > 2) { // Including header row
                // Find the row index and remove it
                supplierTable.row($(button).closest('tr')).remove().draw(false);
                resetSerialNumbers();
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

        function resetSerialNumbers() {
            if ($('#supplierTable tbody tr:first').find('td:first').text() != 'No data available in table') {
                $('#supplierTable tbody tr').each(function(index) {
                    $(this).find('td:first').text(index + 1);
                });
            }
        }

        $(document).on('click', '.openModal', function() {
            let hiddenId = $(this).closest('tr').find('.product_id').val();
            $('#product_ids').val(hiddenId);
            let storedData = sessionStorage.getItem(`supplierData${hiddenId}`);

            // Clear existing rows in the table
            supplierTable.clear().draw();

            if (storedData) {
                storedData = JSON.parse(storedData);
                storedData.forEach(element => {
                    let optionsHtml = '';
                    suppliers.forEach(supplier => {
                        let selected = '';
                        if (element.supplier === `${supplier.id}`) {
                            selected = 'selected';
                        }
                        optionsHtml +=
                            `<option value="${supplier.id}" ${selected}>${supplier.name}</option>`;
                    });

                    supplierTable.row.add([
                        supplierTable.rows().count() + 1,
                        `<select class="form-select supplier">${optionsHtml}</select>`,
                        `<input type="number" class="form-control qty" value="${element.qty}">`,
                        `<button type="button" class="btn btn-success btn-sm me-2" onclick="addRow(this)"><i class="bi bi-plus"></i></button><button type="button" class="btn btn-danger btn-sm" onclick="removeRow(this)"><i class="bi bi-dash"></i></button>`
                    ]).draw(false);
                    supplierTable.columns.adjust();
                });
            } else {
                let optionsHtml = '';
                suppliers.forEach(supplier => {
                    optionsHtml += `<option value="${supplier.id}">${supplier.name}</option>`;
                });

                supplierTable.row.add([
                    supplierTable.rows().count() + 1,
                    `<select class="form-select supplier">${optionsHtml}</select>`,
                    `<input type="number" class="form-control qty">`,
                    `<button type="button" class="btn btn-success btn-sm me-2" onclick="addRow(this)"><i class="bi bi-plus"></i></button><button type="button" class="btn btn-danger btn-sm" onclick="removeRow(this)"><i class="bi bi-dash"></i></button>`
                ]).draw(false);
                supplierTable.columns.adjust();
            }

            let part_no = $(this).closest('tr').find('td:eq(1)').text();
            let part_name = $(this).closest('tr').find('td:eq(2)').text();
            let planning_qty = $(this).closest('tr').find('.planning_qty').val();
            let balance_qty = $(this).closest('tr').find('.balance').val();
            $('.part_no_text').text(part_no);
            $('.part_name_text').text(part_name);
            $('.qty_planning_text').text(balance_qty);
            $('.qty').trigger('keyup');
        });

        $(document).on('keyup change', '.qty', function() {
            let total = 0;
            $('#supplierTable .qty').each(function() {
                total += +$(this).val();
            });
            $('.qty_supplier_text').text(total);
        });

        $('#saveModal').on('click', function() {
            $('#exampleModal').modal('hide');
            let hiddenId = $('#product_ids').val();
            let data = [];
            $('#supplierTable tbody tr').each(function() {
                let rowData = {};
                rowData['supplier'] = $(this).find('.supplier').val();
                rowData['qty'] = $(this).find('.qty').val();
                rowData['hiddenId'] = hiddenId;
                data.push(rowData);
            });
            sessionStorage.setItem(`supplierData${hiddenId}`, JSON.stringify(data));
            $('#planningTable tbody tr').each(function() {
                if ($(this).find('.product_id').val() == hiddenId) {
                    let total_qty = $('.qty_supplier_text').text();
                    $(this).find('.planning_qty').val(total_qty);
                }
            });
        });

        //END SUPPLIER WORK

        $('#saveForm').on('click', function() {
            $('.card-body').find('.table').DataTable().page.len(-1).draw();
            let array = [];
            $('#planningTable tbody tr').each(function() {
                let hiddenId = $(this).find('.product_id').val();
                let storedData = sessionStorage.getItem(`supplierData${hiddenId}`);
                if (storedData == null) {
                    storedData = `{"hiddenId":"${hiddenId}"}`;
                }
                array.push(JSON.parse(storedData));
            });
            $('#storedData').val(JSON.stringify(array));
            $(this).closest('form').submit();
        });
    </script>
@endsection
