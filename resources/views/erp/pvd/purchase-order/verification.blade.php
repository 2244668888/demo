@extends('layouts.app')
@section('title')
    PURCHASE ORDER @if ($action == 'check')
        CHECK
    @elseif($action == 'verify')
        VERIFY
    @endif
@endsection
@section('content')
    <style>
        #productTable input {
            width: 130px;
        }
    </style>
    <div class="card">
        <form method="post"
            @if ($action == 'check') action="{{ route('purchase_order.check', $purchase_order->id) }}" @elseif($action == 'verify') action="{{ route('purchase_order.verify', $purchase_order->id) }}" @endif
            enctype="multipart/form-data">
            @csrf
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
                                <option value="{{ $purchase_order->supplier_id }}" selected>
                                    {{ $purchase_order->supplier->name ?? '' }}</option>
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
                                <option value="{{ $purchase_order->department_id }}" selected>
                                    {{ $purchase_order->department->name ?? '' }}</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label for="important_note" class="form-label">Important Note</label>
                            <textarea name="important_note" readonly id="important_note" class="form-control" rows="1">{{ $purchase_order->important_note }}</textarea>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select name="status" id="status" class="form-select">
                                <option value="" selected disabled>Please Select</option>
                                <option value="in-progress">In Progress</option>
                                <option value="completed" selected>Completed</option>
                            </select>
                        </div>
                    </div>
                </div>
                <br>
                <div class="row">
                    <h5>PRODUCT PURCHASE DETAILS</h5>
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
                                        <th>Total</th>
                                        <th>W/O Discount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($purchase_order_details as $index => $purchase_order_detail)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>
                                                <input type="hidden" name="products[{{ $index }}][product_id]"
                                                    value="{{ $purchase_order_detail->product_id }}"><input
                                                    type="date" class="form-control required_date"
                                                    name="products[{{ $index }}][required_date]"
                                                    value="{{ $purchase_order_detail->required_date }}">
                                            </td>
                                            <td>{{ $purchase_order_detail->product->part_no }}</td>
                                            <td>{{ $purchase_order_detail->product->part_name }}</td>
                                            <td>{{ $purchase_order_detail->product->type_of_product }}</td>
                                            <td>
                                                <input type="number" readonly class="form-control price"
                                                    name="products[{{ $index }}][price]"
                                                    value="{{ $purchase_order_detail->price }}">
                                            </td>
                                            <td>
                                                <input type="number" readonly class="form-control qty"
                                                    name="products[{{ $index }}][qty]"
                                                    value="{{ $purchase_order_detail->qty }}">
                                            </td>
                                            <td>
                                                <input type="number" readonly class="form-control disc"
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
                            <label for="net_total" class="form-label">Net Total</label>
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
                                value="{{ $purchase_order->user->user_name }}">
                        </div>
                    </div>
                </div>
                @if ($purchase_order_histories->isNotEmpty())
                    <br>
                    <div class="row">
                        <h5>Check and Verification</h5>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-12">
                            <div class="table-responsive">
                                <table class="table table-bordered m-0" id="productTable">
                                    <thead>
                                        <tr>
                                            <th>Sr No</th>
                                            <th>Date</th>
                                            <th>Status By</th>
                                            <th>Department</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($purchase_order_histories as $purchase_order_history)
                                        @php
                                            if ($purchase_order_history->status == 'checked') {
                                                $class = 'warning';
                                            }
                                            if ($purchase_order_history->status == 'verified') {
                                                $class = 'success';
                                            }
                                            if ($purchase_order_history->status == 'declined') {
                                                $class = 'danger';
                                            }
                                        @endphp
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $purchase_order_history->date }}</td>
                                                <td>{{ $purchase_order_history->user->user_name }}</td>
                                                <td>{{ $purchase_order_history->user->department->name ?? '' }}</td>
                                                <td><span class="badge border border-{{$class}} text-{{$class}}">{{ $purchase_order_history->status }}</span></td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
            <div class="card-footer">
                <div class="row">
                    <div class="d-flex gap-2 justify-content-between col-12">
                        <div class="d-flex gap-2 justify-content-start col-md-6">
                            <a type="button" class="btn btn-info" href="{{ route('purchase_order.index') }}">
                                <i class="bi bi-arrow-left"></i> Back
                            </a>
                        </div>
                        <div class="d-flex gap-2 justify-content-end col-md-6">
                            @if ($action == 'check')
                                <button type="submit" class="btn btn-primary">Check</button>
                            @elseif($action == 'verify')
                                <button type="submit" class="btn btn-primary">Verify</button>
                                <button type="button" id="decline_button" value="decline"
                                    class="btn btn-danger">Decline</button>
                                <button type="button" id="canceil_button" value="cancel"
                                    class="btn btn-warning">Cancel</button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
    {{-- DECLINE / CANCEL MODAL --}}
    <div class="modal fade show" id="exampleModalCenter" tabindex="-1" aria-labelledby="exampleModalCenterTitle"
        aria-modal="true" role="dialog" style="display: none;">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalCenterTitle">
                        DECLINE REASON
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('purchase_order.decline_cancel', $purchase_order->id) }}" method="POST"
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
    <script>
        $(document).ready(function() {
            $('.card').find('input, select, textarea').prop('disabled', true);
            $('input[type="hidden"]').prop('disabled', false);
            $('#productTable').DataTable();

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
        });
    </script>
@endsection
