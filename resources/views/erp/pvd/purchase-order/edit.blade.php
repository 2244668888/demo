@extends('layouts.app')
@section('title')
    PURCHASE ORDER EDIT
@endsection
@section('content')
    <style>
        #productTable input {
            width: 130px;
        }
    </style>
    <form method="post" action="{{ route('purchase_order.update', $purchase_order->id) }}" enctype="multipart/form-data" id="myForm">
        @csrf
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <h5>PURCHASE ORDER DETAILS</h5>
                </div>
                <br>
                <div class="row">
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label for="ref_no" class="form-label">PO No</label>
                            <input type="text" readonly name="ref_no" id="ref_no" class="form-control"
                                value="{{ $purchase_order->ref_no }}">
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label for="supplier_id" class="form-label">Supplier</label>
                            <select name="supplier_id" id="supplier_id" class="form-select">
                                <option value="" selected disabled>Please Select</option>
                                @foreach ($suppliers as $supplier)
                                    <option value="{{ $supplier->id }}" @selected($purchase_order->supplier_id == $supplier->id)>{{ $supplier->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label for="quotation_ref_no" class="form-label">Quotation Ref. No. (optional)</label>
                            <input type="text" name="quotation_ref_no" id="quotation_ref_no"
                                value="{{ $purchase_order->quotation_ref_no }}" class="form-control">
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label for="date" class="form-label">PO Date</label>
                            <input type="date" name="date" id="date" class="form-control"
                                value="{{ $purchase_order->date }}">
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-4 col-12">
                        <label for="inputGroupFileAddon03" class="form-label">Attachment</label>
                        <div class="input-group mb-3">
                            <a target="_blank" href="{{ asset('/order-attachments/') }}/{{ $purchase_order->attachment }}"
                                class="btn btn-outline-secondary" type="button" id="inputGroupFileAddon03">
                                <i class="bi bi-file-text"></i>
                            </a>
                            <input type="file" class="form-control" id="inputGroupFile03"
                                aria-describedby="inputGroupFileAddon03" aria-label="Upload">
                        </div>
                    </div>
                </div>
                <br>
                <div class="row">
                    @if ($purchase_order->pp_id != '' || $purchase_order->pr_id != '')
                        <div class="col-lg-3 col-sm-4 col-12">
                            <div class="mt-4 d-flex">
                                <label class="form-label me-2" for="pp_pr">PP No</label>
                                <div class="d-flex align-items-center">
                                    <div class="form-check form-switch">
                                        <input disabled class="form-check-input" type="checkbox" id="pp_pr" name="pp_pr"
                                            @checked($purchase_order->pp_pr == 1) value="1">
                                    </div>
                                </div>
                                <label class="form-check-label" for="pp_pr">PR No</label>
                            </div>
                        </div>
                        <div class="col-lg-3 col-sm-4 col-12 purchase_planning @if ($purchase_order->pp_pr == 1) d-none @endif">
                            <div class="mb-3">
                                <label for="pp_id" class="form-label">Ref No.</label>
                                <select name="pp_id" id="pp_id" class="form-select" onchange="get_pp()" disabled>
                                    <option value="{{ $purchase_order->pp_id }}" selected>{{ $purchase_order->purchase_planning->ref_no ?? '' }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-3 col-sm-4 col-12 purchase_requisition @if (!$purchase_order->pp_pr == 1) d-none @endif">
                            <div class="mb-3">
                                <label for="pr_id" class="form-label">Ref No.</label>
                                <select name="pr_id" id="pr_id" class="form-select" onchange="get_pr()" disabled>
                                    <option value="{{ $purchase_order->pr_id }}" selected>{{ $purchase_order->purchase_requisition->pr_no ?? '' }}</option>
                                </select>
                            </div>
                        </div>
                        @else
                        <div class="col-lg-3 col-sm-4 col-12">
                            <div class="mb-3">
                                <label for="pr_id" class="form-label">Ref No.</label>
                                <input type="text" disabled value="Manual" class="form-control">
                            </div>
                        </div>
                    @endif
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label for="payment_term" class="form-label">Payment Term</label>
                            <input type="text" name="payment_term" value="{{ $purchase_order->payment_term }}"
                                id="payment_term" class="form-control">
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label for="department_id" class="form-label">Department</label>
                            <select name="department_id" id="department_id" class="form-select">
                                <option value="" selected disabled>Please Select</option>
                                @foreach ($departments as $department)
                                    <option value="{{ $department->id }}" @selected($purchase_order->department_id == $department->id)>
                                        {{ $department->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label for="important_note" class="form-label">Important Note</label>
                            <textarea name="important_note" readonly id="important_note" class="form-control" rows="1">{{ $inportant_note->po_note }}</textarea>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select name="status" id="status" class="form-select">
                                <option value="" selected disabled>Please Select</option>
                                <option value="in-progress" @selected($purchase_order->status == 'in-progress')>In Progress</option>
                                <option value="completed" @selected($purchase_order->status == 'completed')>Completed</option>
                            </select>
                        </div>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-12 d-flex justify-content-between">
                        <h5>PRODUCT PURCHASE DETAILS</h5>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                            data-bs-target="#exampleModalCenter">ADD PRODUCTS</button>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label for="required_date" class="form-label">Required Date</label>
                            <input type="date" name="required_date" value="{{ $purchase_order->required_date }}"
                                id="required_date" class="form-control">
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mt-4 d-flex">
                            <div class="d-flex align-items-center">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="bulk_wo_discount"
                                        name="bulk_wo_discount" @checked($purchase_order->bulk_wo_discount) value="1">
                                </div>
                            </div>
                            <label class="form-check-label" for="bulk_wo_discount">Bulk W/O Discount</label>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mt-4 d-flex">
                            <div class="d-flex align-items-center">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="bulk_required_date"
                                        name="bulk_required_date" @checked($purchase_order->bulk_required_date) value="1">
                                </div>
                            </div>
                            <label class="form-check-label" for="bulk_required_date">Bulk Required Date</label>
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
                                        <th>Sr No</th>
                                        <th>Required Date</th>
                                        <th>Part No.</th>
                                        <th>Part Name</th>
                                        <th>Type Of Product</th>
                                        <th>Price (RM)</th>
                                        <th>Qty</th>
                                        <th>Disc</th>
                                        <th>Disc %</th>
                                        <th>Sale Tax %</th>
                                        <th>Sale Tax</th>
                                        <th>Total (RM)</th>
                                        <th>W/O Discount</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        if ($purchase_order->pp_id != '' || $purchase_order->pr_id != ''){
                                            $readonly = 'readonly';
                                        }else{
                                            $readonly = '';
                                        }
                                    @endphp
                                    @foreach ($purchase_order_details as $index => $purchase_order_detail)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>
                                                <input type="hidden" class="product_id"
                                                    name="products[{{ $index }}][product_id]" value="{{$purchase_order_detail->product_id}}"><input
                                                    type="date" class="form-control required_date"
                                                    name="products[{{ $index }}][required_date]"
                                                    value="{{ $purchase_order_detail->required_date }}">
                                            </td>
                                            <td>{{ $purchase_order_detail->product->part_no }}</td>
                                            <td>{{ $purchase_order_detail->product->part_name }}</td>
                                            <td>{{ $purchase_order_detail->product->type_of_product }}</td>
                                            <td>
                                                <input type="number" {{$readonly}} class="form-control price"
                                                    name="products[{{ $index }}][price]"
                                                    value="{{ $purchase_order_detail->price }}">
                                            </td>
                                            <td>
                                                <input type="number" {{$readonly}} class="form-control qty"
                                                    name="products[{{ $index }}][qty]"
                                                    value="{{ $purchase_order_detail->qty }}">
                                            </td>
                                            <td>
                                                <input type="number" class="form-control disc"
                                                    name="products[{{ $index }}][disc]"
                                                    value="{{ $purchase_order_detail->disc }}">
                                            </td>
                                            <td>
                                                <input type="number" class="form-control disc_percent"
                                                    name="products[{{ $index }}][disc_percent]"
                                                    value="{{ $purchase_order_detail->disc_percent }}">
                                            </td>
                                            <td>
                                                <input type="number" class="form-control sale_tax_percent"
                                                    name="products[{{ $index }}][sale_tax_percent]"
                                                    value="{{ $purchase_order_detail->sale_tax_percent }}">
                                            </td>
                                            <td>
                                                <input type="number" readonly class="form-control sale_tax"
                                                    name="products[{{ $index }}][sale_tax]"
                                                    value="{{ $purchase_order_detail->sale_tax }}">
                                            </td>
                                            <td>
                                                <input type="number" readonly class="form-control total"
                                                    name="products[{{ $index }}][total]"
                                                    value="{{ $purchase_order_detail->total }}">
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="form-check form-switch">
                                                        <input class="form-check-input" type="checkbox" id="wo_discount"
                                                            name="products[{{ $index }}][wo_discount]"
                                                            value="1"
                                                            {{ $purchase_order_detail->wo_discount == 1 ? 'checked' : '' }}>
                                                    </div>
                                                </div>
                                            </td>
                                            <td><button type="button" class="btn btn-danger btn-sm remove-product"><i class="bi bi-trash"></i></button></td>
                                        </tr>
                                    @endforeach
                            </table>
                        </div>
                    </div>
                </div>
                <br>
                <div class="row">
                    <h5>TOTAL SECTION</h5>
                </div>
                <br>
                <div class="row">
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label for="total_qty" class="form-label">Total Quantity</label>
                            <input type="text" readonly id="total_qty" name="total_qty"
                                value="{{ $purchase_order->total_qty }}" class="form-control">
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label for="total_discount" class="form-label">Total Discount</label>
                            <input type="text" readonly id="total_discount" name="total_discount"
                                value="{{ $purchase_order->total_discount }}" class="form-control">
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label for="total_sale_tax" class="form-label">Total Sale Tax</label>
                            <input type="text" readonly id="total_sale_tax" name="total_sale_tax"
                                value="{{ $purchase_order->total_sale_tax }}" class="form-control">
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label for="net_total" class="form-label">Net Total (RM)</label>
                            <input type="text" readonly id="net_total" name="net_total"
                                value="{{ $purchase_order->net_total }}" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label for="issued_by" class="form-label">Issued By</label>
                            <input type="text" readonly name="issued_by" id="issued_by" class="form-control"
                                value="{{ Auth::user()->user_name }}">
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <div class="row">
                    <div class="d-flex gap-2 justify-content-between col-12">
                        <a type="button" class="btn btn-info" href="{{ route('purchase_order.index') }}">
                            <i class="bi bi-arrow-left"></i> Back
                        </a>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <div class="modal fade" id="exampleModalCenter" tabindex="-1" aria-labelledby="exampleModalCenterTitle"
        aria-modal="true" role="dialog">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalCenterTitle">
                        PRODUCTS
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                        aria-label="Close"></button>
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
                                    <th>Qty</th>
                                    <th>Price</th>
                                    <th>Model</th>
                                    <th>Variance</th>
                                    <th>Type of Product</th>
                                    <th>Category</th>
                                    <th>Supplier</th>
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
    <script>
        let sale_tax;
        var planning;
        var requisition;
        let productTable;
        let purchase_price;
        let modalTable;
        var purchase_planning_ViewUrlTemplate = '{{ route('purchase_planning.view', ':purchase_planningId') }}';
        var purchase_requisition_ViewUrlTemplate = '{{ route('purchase_requisition.view', ':purchase_requisitionId') }}';

        $(document).ready(function() {

           
            productTable = $('#productTable').DataTable();
            modalTable = $('#modalTable').DataTable();
            $('#pp_pr').on('change', function() {
                if ($(this).is(':checked')) {
                    $('.purchase_requisition').removeClass('d-none');
                    $('.purchase_requisition').find('.select2-container').addClass('w-100');
                    $('.purchase_planning').addClass('d-none');
                    $('#productTable').DataTable().clear().draw();
                } else {
                    $('.purchase_planning').removeClass('d-none');
                    $('.purchase_requisition').addClass('d-none');
                    $('#productTable').DataTable().clear().draw();
                }
            });

            $('#bulk_required_date, #required_date').on('change', function() {
                updateRequiredDate();
            });
            updateRequiredDate();

            $('#productTable').on('change', '#wo_discount', function() {
                updateRowTotal($(this).closest('tr'));
                calculateTotals();
            });

            $('#bulk_wo_discount').on('change', function() {
                var isChecked = $(this).is(':checked');
                $('#productTable tbody tr').each(function() {
                    $(this).find('#wo_discount').prop('checked', isChecked);
                    updateRowTotal($(this));
                });
                calculateTotals();
            });

            $('#productTable').on('input', '.disc_percent, .sale_tax_percent', function() {
                updateRowTotal($(this).closest('tr'));
                calculateTotals();
            });

            $('#productTable').on('input', '.qty, .price', function() {
                updateRowTotal($(this).closest('tr'));
                calculateTotals();
            });

            $('#productTable').on('input', '.disc', function() {
                updateRowTotalDisc($(this).closest('tr'));
                calculateTotals();
            });

            $('#productTable').on('change', '#wo_discount', function() {
                let isChecked = false;
                $('#productTable tbody tr').each(function() {
                    if($(this).find('#wo_discount').is(':checked')){
                        isChecked = true;
                        return;
                    }
                });
                $('#bulk_wo_discount').prop('checked', isChecked);
            });

            planning = {!! json_encode($purchase_plannings) !!};
            requisition = {!! json_encode($purchase_requisitions) !!};
            purchase_price = {!! json_encode($purchase_price) !!};
            sale_tax = {!! json_encode($sale_tax) !!};
            products_all = {!! json_encode($products_all) !!};



            if ($('#pp_pr').is(':checked')) {
                get_pr();
            }else{
                get_pp()
            }
        });

        function get_pp() {
            // productTable.clear().draw();
            var ppId = $("#pp_id").val();
            if(ppId == 'manual'){
                populateProductTable_all(products_all);
                $('#pp_view').attr('href', '#');
            }else{
                var pp = planning.find(p => p.id == ppId);
                if (pp) {
                    var purchase_planning_ViewUrl = purchase_planning_ViewUrlTemplate.replace(':purchase_planningId', ppId);
                    $('#pp_view').attr('href', purchase_planning_ViewUrl);
                    populateProductTable(pp.purchase_planning_suppliers);
                } else {
                    $('#pp_view').attr('href', '#');
                    // productTable.clear().draw();
                }
            }
        };

        function get_pr() {
            // productTable.clear().draw();
            var prId = $("#pr_id").val();
            if(prId == 'manual'){
                populateProductTable_all(products_all);
                $('#pp_view').attr('href', '#');
            }else{
                var pr = requisition.find(p => p.id == prId);
                if (pr) {
                    var purchase_requisition_ViewUrl = purchase_requisition_ViewUrlTemplate.replace(':purchase_requisitionId', prId);
                    $('#pr_view').attr('href', purchase_requisition_ViewUrl);
                    populateProductTablePr(pr.requisition_detail);
                } else {
                    $('#pr_view').attr('href', '#');
                    // productTable.clear().draw();
                }
            }
        };

        function updateRequiredDate() {
            if($('#bulk_required_date').is(':checked')){
                $('#productTable tbody tr').each(function() {
                    $(this).find('.required_date').val($('#required_date').val());
                });
            }
        }

        function populateProductTable_all(data) {
            productTable.clear();
            modalTable.clear();
            data.forEach((product, index) => {
                let lastRecord = null;
                if (Array.isArray(purchase_price) && purchase_price.length > 0) {
                    for (let i = 0; i < purchase_price.length; i++) {
                        if (purchase_price[i].product_id == product.id) {
                            if (lastRecord != null) {
                                if (new Date(purchase_price[i].date) > new Date(lastRecord.date)) {
                                    lastRecord = purchase_price[i];
                                }
                            } else {
                                lastRecord = purchase_price[i];
                            }
                        }
                    }
                }
                let price = lastRecord ? lastRecord.price : '-';
                let newRow = modalTable.row.add([
                    `<input class="form-check-input product_id" type="checkbox" id="inlineCheckbox1" value="${product.id}">`,
                    product?.part_no +`<input type="hidden" class="form-control PO_type" value="manual">`,
                    product?.part_name,
                    product?.units?.name ? product.units.name : '',
                    '-',
                    price,
                    product?.model,
                    product?.variance,
                    product?.type_of_products?.type ? product.type_of_products.type : '',
                    product?.category,
                    '-',
                ]).draw(false);
            });
        }

        function populateProductTable(data) {
            // productTable.clear();
            modalTable.clear();
            data.forEach((product, index) => {
                productTable.rows().every(function() {
                let rowData = this.data();
                let existingProductId = $('<div>').html(rowData[1]).find('input[class="product_id"]').val();
                if (existingProductId == product.product_id) {
                    productExists = true;
                    return false; // Exit loop
                }else{
                    productExists =  false;
                }
                return true;
            });

                let lastRecord = null;
                if (Array.isArray(purchase_price) && purchase_price.length > 0) {
                    for (let i = 0; i < purchase_price.length; i++) {
                        if (purchase_price[i].product_id == product.product_id) {
                            if (lastRecord != null) {
                                if (new Date(purchase_price[i].date) > new Date(lastRecord.date)) {
                                    lastRecord = purchase_price[i];
                                }
                            } else {
                                lastRecord = purchase_price[i];
                            }
                        }
                    }
                }
                let price = lastRecord ? lastRecord.price : 0;
                let newRow = modalTable.row.add([
                    `<input class="form-check-input product_id" type="checkbox" id="inlineCheckbox1" value="${product.product_id}">`,
                    product?.product.part_no ?? '',
                    product?.product.part_name ?? '',
                    product?.product.units?.name ? product.product.units.name : '',
                    product?.qty,
                    price,
                    product?.product.model,
                    product?.product.variance,
                    product?.product.type_of_products?.type ? product.product.type_of_products.type : '',
                    product?.product.categories.name ?? '',
                    product?.supplier?.name ? product.supplier.name : '-',
                ]).draw(false);
            });
        }
        $(document).on('click', '.remove-product', function() {
            var row = $(this).closest('tr');
            var rowData = productTable.row(row).data();
            var product_id = $(row).find('.product_id').val();
            var unit = $(row).find('.unit').val();
            var qty = $(row).find('.qty').val();
            var price = $(row).find('.price').val();
            var model = $(row).find('.model').val();
            var variance = $(row).find('.variance').val();
            var category = $(row).find('.category').val();
            var supplier = $(row).find('.supplier').val();
            // Add the removed row back to the modal table
            modalTable.row.add([
                `<input class="form-check-input product_id" type="checkbox" id="inlineCheckbox1" value="${product_id}">`,
                    rowData[2],
                    rowData[3],
                    unit,
                    qty,
                    price,
                    model,
                    variance,
                    rowData[4],
                    category,
                    supplier
            ]).draw(false);

            // Remove the row from the main table
            productTable.row(row).remove().draw();
            resetSerialNumbers(productTable);
        });


        function add_product() {
            modalTable.$('input:checked').each(function() {
                var row = $(this).closest('tr');
                var rowData = modalTable.row(row).data();
                var productId = $(this).val();
                var price = $(row).find('.price').val();
                var PO_type = $(row).find('.PO_type').val();
                var sstPercentage = $(row).find('.sst_percentage').val();
                var sstValue = (price * sstPercentage) / 100;

                // Add the row data to the main table
                if(PO_type == 'manual'){
                    let newRow = productTable.row.add([
                        productTable.rows().count() + 1,
                        `<input type="hidden" class="product_id" name="products[${productTable.rows().count() + 1}][product_id]" value="${productId}"><input type="date" class="form-control required_date" name="products[${productTable.rows().count() + 1}][required_date]">`,
                        `${rowData[1]}`,
                        `${rowData[2]}`,
                        `${rowData[8]}`,
                        `<input type="hidden" class="form-control unit" value="${rowData[3]}">
                        <input type="hidden" class="form-control model" value="${rowData[6]}">
                        <input type="hidden" class="form-control variance" value="${rowData[7]}">
                        <input type="hidden" class="form-control category" value="${rowData[9]}">
                        <input type="hidden" class="form-control supplier" value="${rowData[10]}">
                        <input type="number" class="form-control price" name="products[${productTable.rows().count() + 1}][price]" value="${rowData[5]}">`,
                        `<input type="number" class="form-control qty" name="products[${productTable.rows().count() + 1}][qty]" value="${rowData[4]}">`,
                        `<input type="number" class="form-control disc" name="products[${productTable.rows().count() + 1}][disc]">`,
                        `<input type="number" class="form-control disc_percent" name="products[${productTable.rows().count() + 1}][disc_percent]">`,
                        `<input type="number" class="form-control sale_tax_percent" name="products[${productTable.rows().count() + 1}][sale_tax_percent]" value="${sale_tax.sst_percentage ?? 0}">`,
                        `<input type="number" readonly class="form-control sale_tax" name="products[${productTable.rows().count() + 1}][sale_tax]">`,
                        `<input type="number" readonly class="form-control total" name="products[${productTable.rows().count() + 1}][total]">`,
                        `<div class="d-flex align-items-center"><div class="form-check form-switch"><input class="form-check-input" type="checkbox" id="wo_discount" name="products[${productTable.rows().count() + 1}][wo_discount]" value="1"></div></div>`,
                        `<button type="button" class="btn btn-danger btn-sm remove-product"><i class="bi bi-trash"></i></button>`
                    ]).draw(false);
                }else{
                    let newRow = productTable.row.add([
                        productTable.rows().count() + 1,
                        `<input type="hidden" class="product_id" name="products[${productTable.rows().count() + 1}][product_id]" value="${productId}"><input type="date" class="form-control required_date" name="products[${productTable.rows().count() + 1}][required_date]">`,
                        `${rowData[1]}`,
                        `${rowData[2]}`,
                        `${rowData[8]}`,
                        `<input type="hidden" class="form-control unit" value="${rowData[3]}">
                        <input type="hidden" class="form-control model" value="${rowData[6]}">
                        <input type="hidden" class="form-control variance" value="${rowData[7]}">
                        <input type="hidden" class="form-control category" value="${rowData[9]}">
                        <input type="hidden" class="form-control supplier" value="${rowData[10]}">
                        <input type="number" readonly class="form-control price" name="products[${productTable.rows().count() + 1}][price]" value="${rowData[5]}">`,
                        `<input type="number" readonly class="form-control qty" name="products[${productTable.rows().count() + 1}][qty]" value="${rowData[4]}">`,
                        `<input type="number" class="form-control disc" name="products[${productTable.rows().count() + 1}][disc]">`,
                        `<input type="number" class="form-control disc_percent" name="products[${productTable.rows().count() + 1}][disc_percent]">`,
                        `<input type="number" class="form-control sale_tax_percent" name="products[${productTable.rows().count() + 1}][sale_tax_percent]" value="${sale_tax.sst_percentage ?? 0}">`,
                        `<input type="number" readonly class="form-control sale_tax" name="products[${productTable.rows().count() + 1}][sale_tax]">`,
                        `<input type="number" readonly class="form-control total" name="products[${productTable.rows().count() + 1}][total]">`,
                        `<div class="d-flex align-items-center"><div class="form-check form-switch"><input class="form-check-input" type="checkbox" id="wo_discount" name="products[${productTable.rows().count() + 1}][wo_discount]" value="1"></div></div>`,
                        `<button type="button" class="btn btn-danger btn-sm remove-product"><i class="bi bi-trash"></i></button>`
                    ]).draw(false);
                }


            $('#bulk_wo_discount').trigger('change');
            $('#bulk_required_date').trigger('change');

                // Remove the row from the modal table
                modalTable.row(row).remove().draw();
            });

            // Uncheck all checkboxes
            $('#modalTable input:checked').prop('checked', false);

            // Add event listener to remove buttons





            // Hide the modal
            $('#exampleModalCenter').modal('hide');
        }

        function resetSerialNumbers() {
            if ($('#mainTable tbody tr:first').find('td:first').text() != 'No data available in table') {
                $('#mainTable tbody tr').each(function(index) {
                    $(this).find('td:first').text(index + 1);
                });
            }
        }

        function populateProductTablePr(data) {
            // productTable.clear();
            modalTable.clear();
            let productExists = false;
           

    // If the product does not exist, add it to the modal table
    
            data.forEach((product, index) => {
                productTable.rows().every(function() {
                let rowData = this.data();
                let existingProductId = $('<div>').html(rowData[1]).find('input[class="product_id"]').val();
                if (existingProductId == product.product_id) {
                    productExists = true;
                    return false; // Exit loop
                }else{
                    productExists =  false;
                }
                return true;
            });
            if (!productExists) {
                
                let newRow = modalTable.row.add([
                    `<input class="form-check-input product_id" type="checkbox" id="inlineCheckbox1" value="${product.product_id}">`,
                    product?.product.part_no ?? '',
                    product?.product.part_name ?? '',
                    product?.product.units.name ? product.product.units.name : '',
                    product?.qty ? product.qty : '',
                    product?.price ? product.price : 0,
                    product?.product.model ? product.product.model : '',
                    product?.product.variance,
                    product?.product.type_of_products.type ? product.product.type_of_products.type : '',
                    product?.product.category ?? '',
                    '-'
                ]).draw(false);
                }
            });
            $('#bulk_wo_discount').trigger('change');
            $('#bulk_required_date').trigger('change');
        }

        function updateRowTotalDisc(row) {
            let price = parseFloat(row.find('.price').val()) || 0;
            let qty = parseFloat(row.find('.qty').val()) || 0;
            let discPerc = parseFloat(row.find('.disc_percent').val()) || 0;
            let saleTaxPerc = parseFloat(row.find('.sale_tax_percent').val()) || 0;
            let discAmount_text = parseFloat(row.find('.disc').val()) || 0;
            let woDiscount = row.find('#wo_discount').is(':checked');

            let flat_discount = (100 * discAmount_text) / (price * qty);
            row.find('.disc_percent').val(flat_discount)


            // Calculate subtotal
            let subTotal = price * qty - (woDiscount ? 0 : discAmount_text);
            let saleTax = (saleTaxPerc / 100) * subTotal;
            let total = subTotal + saleTax;

            row.find('.sale_tax').val(saleTax.toFixed(2));
            row.find('.total').val(total.toFixed(2));
        }

        function updateRowTotal(row) {
            let price = parseFloat(row.find('.price').val()) || 0;
            let qty = parseFloat(row.find('.qty').val()) || 0;
            let discPerc = parseFloat(row.find('.disc_percent').val()) || 0;
            let saleTaxPerc = parseFloat(row.find('.sale_tax_percent').val()) || 0;
            let discAmount_text = parseFloat(row.find('.disc').val()) || 0;
            let woDiscount = row.find('#wo_discount').is(':checked');

            // Calculate discount amount
            let discAmount = (discPerc / 100) * price * qty;
            row.find('.disc').val(discAmount); // Set the discount value in the input


            // Calculate subtotal
            let subTotal = price * qty - (woDiscount ? 0 : discAmount);
            let saleTax = (saleTaxPerc / 100) * subTotal;
            let total = subTotal + saleTax;

            row.find('.sale_tax').val(saleTax.toFixed(2));
            row.find('.total').val(total.toFixed(2));
        }

        function calculateTotals() {
            let totalQuantity = 0;
            let totalDiscount = 0;
            let totalSaleTax = 0;
            let netTotal = 0;

            $('#productTable tbody tr').each(function() {
                let qty = parseFloat($(this).find('.qty').val()) || 0;
                let disc = parseFloat($(this).find('.disc').val()) || 0;
                let discPerc = parseFloat($(this).find('.disc_percent').val()) || 0;
                let price = parseFloat($(this).find('.price').val()) || 0;
                let saleTax = parseFloat($(this).find('.sale_tax').val()) || 0;
                let total = parseFloat($(this).find('.total').val()) || 0;
                let woDiscount = $(this).find('#wo_discount').is(':checked');

                totalQuantity += qty;
                if (!woDiscount) {
                    totalDiscount += disc;
                }
                totalSaleTax += saleTax;
                netTotal += total;
            });

            $('#total_qty').val(totalQuantity);
            $('#total_discount').val(totalDiscount.toFixed(2));
            $('#total_sale_tax').val(totalSaleTax.toFixed(2));
            $('#net_total').val(netTotal.toFixed(2));
        }

        $('#myForm').on('submit', function(){
            $('.card-body').find('.table').DataTable().page.len(-1).draw();
        });
    </script>
    <!-- <script>
        // let productTable;
        $(document).ready(function() {

            

            modalTable = $('#modalTable').DataTable();
            $('#pp_pr').on('change', function() {
                if ($(this).is(':checked')) {
                    $('.purchase_requisition').removeClass('d-none');
                    $('.purchase_requisition').find('.select2-container').addClass('w-100');
                    $('.purchase_planning').addClass('d-none');
                    $('#productTable').DataTable().clear().draw();
                } else {
                    $('.purchase_planning').removeClass('d-none');
                    $('.purchase_requisition').addClass('d-none');
                    $('#productTable').DataTable().clear().draw();
                }
            });


            productTable = $('#productTable').DataTable();

            $('#bulk_required_date, #required_date').on('change', function() {
                updateRequiredDate();
            });

            updateRequiredDate();

            $('#productTable').on('change', '#wo_discount', function() {
                updateRowTotal($(this).closest('tr'));
                calculateTotals();
            });

            $('#bulk_wo_discount').on('change', function() {
                var isChecked = $(this).is(':checked');
                $('#productTable tbody tr').each(function() {
                    $(this).find('#wo_discount').prop('checked', isChecked);
                    updateRowTotal($(this));
                });
                calculateTotals();
            });

            $('#productTable').on('input', '.disc_percent, .sale_tax_percent', function() {
                updateRowTotal($(this).closest('tr'));
                calculateTotals();
            });

            $('#productTable').on('input', '.disc', function() {
                updateRowTotalDisc($(this).closest('tr'));
                calculateTotals();
            });

            $('#productTable').on('change', '#wo_discount', function() {
                let isChecked = false;
                $('#productTable tbody tr').each(function() {
                    if ($(this).find('#wo_discount').is(':checked')) {
                        isChecked = true;
                        return;
                    }
                });
                $('#bulk_wo_discount').prop('checked', isChecked);
            });
        });

        function updateRequiredDate() {
            if ($('#bulk_required_date').is(':checked')) {
                $('#productTable tbody tr').each(function() {
                    $(this).find('.required_date').val($('#required_date').val());
                });
            }
        }

        function updateRowTotal(row) {
            let price = parseFloat(row.find('.price').val()) || 0;
            let qty = parseFloat(row.find('.qty').val()) || 0;
            let discPerc = parseFloat(row.find('.disc_percent').val()) || 0;
            let saleTaxPerc = parseFloat(row.find('.sale_tax_percent').val()) || 0;
            let woDiscount = row.find('#wo_discount').is(':checked');

            // Calculate discount amount
            let discAmount = (discPerc / 100) * price * qty;
            row.find('.disc').val(discAmount.toFixed(2)); // Set the discount value in the input

            // Calculate subtotal
            let subTotal = price * qty - (woDiscount ? 0 : discAmount);
            let saleTax = (saleTaxPerc / 100) * subTotal;
            let total = subTotal + saleTax;

            row.find('.sale_tax').val(saleTax.toFixed(2));
            row.find('.total').val(total.toFixed(2));
        }

        function updateRowTotalDisc(row) {
            let price = parseFloat(row.find('.price').val()) || 0;
            let qty = parseFloat(row.find('.qty').val()) || 0;
            let discPerc = parseFloat(row.find('.disc_percent').val()) || 0;
            let saleTaxPerc = parseFloat(row.find('.sale_tax_percent').val()) || 0;
            let discAmount_text = parseFloat(row.find('.disc').val()) || 0;
            let woDiscount = row.find('#wo_discount').is(':checked');

            let flat_discount = (100 * discAmount_text) / (price * qty);
            row.find('.disc_percent').val(flat_discount)


            // Calculate subtotal
            let subTotal = price * qty - (woDiscount ? 0 : discAmount_text);
            let saleTax = (saleTaxPerc / 100) * subTotal;
            let total = subTotal + saleTax;

            row.find('.sale_tax').val(saleTax.toFixed(2));
            row.find('.total').val(total.toFixed(2));
        }

        function calculateTotals() {
            let totalQuantity = 0;
            let totalDiscount = 0;
            let totalSaleTax = 0;
            let netTotal = 0;

            $('#productTable tbody tr').each(function() {
                let qty = parseFloat($(this).find('.qty').val()) || 0;
                let disc = parseFloat($(this).find('.disc').val()) || 0;
                let discPerc = parseFloat($(this).find('.disc_percent').val()) || 0;
                let price = parseFloat($(this).find('.price').val()) || 0;
                let saleTax = parseFloat($(this).find('.sale_tax').val()) || 0;
                let total = parseFloat($(this).find('.total').val()) || 0;
                let woDiscount = $(this).find('#wo_discount').is(':checked');

                totalQuantity += qty;
                if (!woDiscount) {
                    totalDiscount += disc;
                }
                totalSaleTax += saleTax;
                netTotal += total;
            });

            $('#total_qty').val(totalQuantity);
            $('#total_discount').val(totalDiscount.toFixed(2));
            $('#total_sale_tax').val(totalSaleTax.toFixed(2));
            $('#net_total').val(netTotal.toFixed(2));
        }
    </script> -->
@endsection
