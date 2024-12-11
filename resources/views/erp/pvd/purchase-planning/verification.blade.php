@extends('layouts.app')
@section('title')
    PURCHASE PLANNING @if ($action == 'check')
        CHECK
    @elseif($action == 'approve')
        APPROVE
    @elseif($action == 'verify_hod')
        VERIFY HOD
    @elseif($action == 'verify_acc')
        VERIFY ACC
    @endif
@section('content')
    <div class="card">
        <form method="post"
            @if ($action == 'check') action="{{ route('purchase_planning.check', $purchase_planning->id) }}" @elseif($action == 'approve') action="{{ route('purchase_planning.approve', $purchase_planning->id) }}" @elseif($action == 'verify_hod') action="{{ route('purchase_planning.verify_hod', $purchase_planning->id) }}" @elseif($action == 'verify_acc') action="{{ route('purchase_planning.verify_acc', $purchase_planning->id) }}" @endif
            enctype="multipart/form-data">
            @csrf
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label for="created_by" class="form-label">Created By</label>
                            <input type="text" readonly name="created_by" id="created_by" class="form-control"
                                value="{{ $purchase_planning->user->user_name ?? '' }}">
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
                                value="{{ $purchase_planning->ref_no }}">
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label for="order_id" class="form-label">Order No <a href="#" target="_blank"
                                    id="so_view" type="button">
                                    <i class="bi bi-eye"></i></a></label>
                            <select name="order_id" disabled onchange="order_change()" id="order_id" class="form-select">
                                <option value="" selected disabled>Please Select</option>
                                @foreach ($orders as $order)
                                    <option value="{{ $order->id }}" @selected($purchase_planning->order_id == $order->id)>{{ $order->order_no }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label for="date" class="form-label">Created Date</label>
                            <input type="date" name="date" id="date" class="form-control"
                                value="{{ $purchase_planning->date }}">
                        </div>
                    </div>
                </div>
                <br>
                <div class="row">
                    <h5>ORDER REGISTRATION DETAILS</h5>
                </div>
                <br>
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
                                @foreach ($purchase_planning_detail as $purchase_planning_detail)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $purchase_planning_detail->product->part_no }}<input type="hidden"
                                                value="{{ $purchase_planning_detail->product_id }}"
                                                class="form-control product_id"
                                                name="planning[{{ $loop->iteration }}][product_id]"></td>
                                        <td>{{ $purchase_planning_detail->product->part_name }}</td>
                                        <td>{{ $purchase_planning_detail->product->type_of_products->type ?? '' }}</td>
                                        <td>{{ $purchase_planning_detail->product->units->name ?? '' }}</td>
                                        <td>{{ $purchase_planning_detail->total_qty }}<input type="hidden"
                                                value="{{ $purchase_planning_detail->total_qty }}"
                                                class="form-control total_qty"
                                                name="planning[{{ $loop->iteration }}][total_qty]"></td>
                                        <td><input type="number" readonly
                                                value="{{ $purchase_planning_detail->inventory_qty }}"
                                                class="form-control inventory_qty"
                                                name="planning[{{ $loop->iteration }}][inventory_qty]"></td>
                                        <td><input type="number" readonly
                                                value="{{ $purchase_planning_detail->balance }}"
                                                class="form-control balance"
                                                name="planning[{{ $loop->iteration }}][balance]"></td>
                                        <td><input type="number" readonly value="{{ $purchase_planning_detail->moq }}"
                                                class="form-control moq" name="planning[{{ $loop->iteration }}][moq]">
                                        </td>
                                        <td><input type="number" readonly
                                                value="{{ $purchase_planning_detail->planning_qty }}"
                                                class="form-control planning_qty"
                                                name="planning[{{ $loop->iteration }}][planning_qty]"></td>
                                        <td><button type="button" class="btn btn-info openModal" data-bs-toggle="modal"
                                                data-bs-target="#exampleModal"><i
                                                    class="bi bi-plus-square me-2"></i>Add</button></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <br>




                <div class="row">
                    <h5>Checked</h5>
                </div>
                <br>
                <div class="row">
                    <div class="table-responsive">
                        <table class="table table-bordered m-0" id="planningTable">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Username</th>
                                    <th>Designation</th>
                                    <th>Department</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($purchase_planning_verifications as $purchase_planning_verification)
                                    @if ($purchase_planning_verification->status == 'checked')
                                        <tr>
                                            <td>{{ date('d-m-Y' ,strtotime($purchase_planning_verification->date)) }}</td>
                                            <td>{{ $purchase_planning_verification->user->user_name ?? '' }}</td>
                                            <td>{{ $purchase_planning_verification->designation->name ?? '' }}</td>
                                            <td>{{ $purchase_planning_verification->department->name ?? '' }}</td>
                                        </tr>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <br>
                <div class="row">
                    <h5>Verified (hod)</h5>
                </div>
                <br>
                <div class="row">
                    <div class="table-responsive">
                        <table class="table table-bordered m-0" id="planningTable">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Username</th>
                                    <th>Designation</th>
                                    <th>Department</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($purchase_planning_verifications as $purchase_planning_verification)
                                    @if($purchase_planning_verification->status == 'verified(hod)')
                                        <tr>
                                            <td>{{ date('d-m-Y' ,strtotime($purchase_planning_verification->date)) }}</td>
                                            <td>{{ $purchase_planning_verification->user->user_name ?? '' }}</td>
                                            <td>{{ $purchase_planning_verification->designation->name ?? '' }}</td>
                                            <td>{{ $purchase_planning_verification->department->name ?? '' }}</td>
                                        </tr>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <br>

                <div class="row">
                    <h5>Verified (acc)</h5>
                </div>
                <br>
                <div class="row">
                    <div class="table-responsive">
                        <table class="table table-bordered m-0" id="planningTable">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Username</th>
                                    <th>Designation</th>
                                    <th>Department</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($purchase_planning_verifications as $purchase_planning_verification)
                                @if($purchase_planning_verification->status == 'verified(acc)')
                                <tr>
                                    <td>{{ date('d-m-Y' ,strtotime($purchase_planning_verification->date)) }}</td>
                                    <td>{{ $purchase_planning_verification->user->user_name ?? '' }}</td>
                                    <td>{{ $purchase_planning_verification->designation->name ?? '' }}</td>
                                    <td>{{ $purchase_planning_verification->department->name ?? '' }}</td>
                                </tr>
                                @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <br>

                <div class="row">
                    <h5>Approved</h5>
                </div>
                <br>
                <div class="row">
                    <div class="table-responsive">
                        <table class="table table-bordered m-0" id="planningTable">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Username</th>
                                    <th>Designation</th>
                                    <th>Department</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($purchase_planning_verifications as $purchase_planning_verification)
                                    @if($purchase_planning_verification->status == 'approved')
                                        <tr>
                                            <td>{{ date('d-m-Y' ,strtotime($purchase_planning_verification->date)) }}</td>
                                            <td>{{ $purchase_planning_verification->user->user_name ?? '' }}</td>
                                            <td>{{ $purchase_planning_verification->designation->name ?? '' }}</td>
                                            <td>{{ $purchase_planning_verification->department->name ?? '' }}</td>
                                        </tr>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <br>
            </div>
            <div class="card-footer">
                <div class="row">
                    <div class="d-flex gap-2 justify-content-between col-12">
                        <div class="d-flex gap-2 justify-content-start col-md-6">
                            <a type="button" class="btn btn-info" href="{{ route('purchase_planning.index') }}">
                                <i class="bi bi-arrow-left"></i> Back
                            </a>
                        </div>
                        <div class="d-flex gap-2 justify-content-end col-md-6">
                            @if ($action == 'check')
                                <button type="submit" class="btn btn-primary">Check</button>
                            @elseif($action == 'approve')
                                <button type="submit" class="btn btn-primary">Approve</button>
                                <button type="button" id="decline_button" value="decline"
                                    class="btn btn-danger">Decline</button>
                                <button type="button" id="cancel_button" value="cancel"
                                    class="btn btn-warning">Cancel</button>
                            @elseif($action == 'verify_hod')
                                <button type="submit" class="btn btn-primary">Verify HOD</button>
                                <button type="button" id="decline_button" value="decline"
                                    class="btn btn-danger">Decline</button>
                                <button type="button" id="cancel_button" value="cancel"
                                    class="btn btn-warning">Cancel</button>
                            @elseif($action == 'verify_acc')
                                <button type="submit" class="btn btn-primary">Verify ACC</button>
                                <button type="button" id="decline_button" value="decline"
                                    class="btn btn-danger">Decline</button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
    {{-- DECLINE / CANCEL MODAL --}}
    <div class="modal fade" id="exampleModalCenter" tabindex="-1" aria-labelledby="exampleModalCenterTitle"
        aria-modal="true" role="dialog" style="display: none;">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalCenterTitle">
                        DECLINE REASON
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('purchase_planning.decline_cancel', $purchase_planning->id) }}" method="POST"
                    id="decline_cancel_form">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" name="decline_cancel" id="decline_cancel" value="decline">
                        <textarea name="decline_cancel_reason" id="decline_cancel_reason" rows="5" class="form-control"
                            placeholder="Enter Reason"></textarea>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-danger" id="decline_cancel_button">
                            DECLINE
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    {{-- SUPPLIER MODAL --}}
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalTitle" aria-modal="true"
        role="dialog">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <input type="hidden" id="product_ids">
                    <h5 class="modal-title" id="exampleModalTitle">
                        ADD SUPPLIERS
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
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
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
        $(document).ready(function() {
            flatpickr("#date", {
                dateFormat: "d-m-Y",
                defaultDate:@json(\Carbon\Carbon::parse($purchase_planning->date)->format('d-m-Y'))
            });
            sessionStorage.clear();
            var supplier_details = @json($supplier_details);
            supplier_details.forEach(element => {
                let data = sessionStorage.getItem(
                    `supplierData${element.product_id}`);
                if (!data) {
                    data = [];
                } else {
                    data = JSON.parse(data);
                }
                let rowData = {};
                rowData['supplier'] = `${element.supplier_id}`;
                rowData['qty'] = element.qty;
                rowData['hiddenId'] = element.product_id;
                data.push(rowData);
                sessionStorage.setItem(`supplierData${element.product_id}`,
                    JSON.stringify(data));
            });
            suppliers = @json($suppliers);
            $('.card').find('input, select').prop('disabled', true);
            $('input[type="hidden"]').prop('disabled', false);
            $('#verificationTable').DataTable();
            productTable = $('#productTable').DataTable();
            planningTable = $('#planningTable').DataTable();
            supplierTable = $('#supplierTable').DataTable();
            order_change();
        });

        $('#decline_button, #cancel_button').on('click', function() {
            $('#decline_cancel').val($(this).val());
            if ($('#decline_cancel').val() == 'decline') {
                $('#exampleModalCenterTitle').text('DECLINE REASON');
                $('#decline_cancel_button').text('DECLINE');
            } else {
                $('#exampleModalCenterTitle').text('CANCEL REASON');
                $('#decline_cancel_button').text('CANCEL');
            }
            $('#exampleModalCenter').modal('show');
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

                appendOrderDetails(order.order_detail);

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
        };

        function appendOrderDetails(orderDetails) {
            productTable.clear(); // Clear existing details
            orderDetails.forEach(detail => {
                productTable.row.add([
                    productTable.rows().count() + 1,
                    detail.products.part_no,
                    detail.products.part_name,
                    detail.products.type_of_products.type ?? '',
                    `${detail.firm_qty}<input type="hidden" value="${detail.product_id}" name="products[${detail.product_id}][product_id]"><input type="hidden" value="${detail.firm_qty}" name="products[${detail.product_id}][product_qty]"><input type="hidden" value="${+detail.firm_qty + +detail.n3_qty}" name="products[${detail.product_id}][total_qty]">`,
                    +detail.firm_qty + +detail.n3_qty
                ]).draw(false); // Append new details
            });
        }

        //SUPPLIER WORK
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
                        `<select class="form-control supplier" disabled>${optionsHtml}</select>`,
                        `<input type="number" disabled class="form-control qty" value="${element.qty}">`
                    ]).draw(false);
                });
            } else {
                let optionsHtml = '';
                suppliers.forEach(supplier => {
                    optionsHtml += `<option value="${supplier.id}">${supplier.name}</option>`;
                });

                supplierTable.row.add([
                    supplierTable.rows().count() + 1,
                    `<select class="form-control supplier" disabled>${optionsHtml}</select>`,
                    `<input type="number" disabled class="form-control qty">`
                ]).draw(false);
            }

            let part_no = $(this).closest('tr').find('td:eq(1)').text();
            let part_name = $(this).closest('tr').find('td:eq(2)').text();
            let planning_qty = $(this).closest('tr').find('.planning_qty').val();
            $('.part_no_text').text(part_no);
            $('.part_name_text').text(part_name);
            $('.qty_planning_text').text(planning_qty);
            $('.qty').trigger('keyup');
        });

        $(document).on('keyup change', '.qty', function() {
            let total = 0;
            $('#supplierTable .qty').each(function() {
                total += +$(this).val();
            });
            $('.qty_supplier_text').text(total);
        });

        //END SUPPLIER WORK
    </script>
@endsection
