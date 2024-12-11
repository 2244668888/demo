@extends('layouts.app')
@section('title')
    INVOICE PAYMENT VOUCHER APPROVE
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
                <div class="col-lg-3 col-sm-4 col-12">
                    <div class="mb-3">
                        @php
                            $item = json_decode($invoice->outgoing_id);
                        @endphp
                        <label for="outgoing_id" class="form-label">DO No</label>
                        <select class="form-select" id="outgoing_id" name="outgoing_id[]" onchange="outgoing_change()" multiple disabled>
                            @foreach ($outgoings as $outgoing)
                                <option value="{{ $outgoing->id }}"
                                    @if ($item) {{ in_array($outgoing->id, $item) ? 'selected' : '' }} @endif>
                                    {{ $outgoing->ref_no }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-4 col-12">
                    <div class="mb-3">
                        <label for="customer" class="form-label">Customer</label>
                        <input type="text" readonly name="customer" id="customer" class="form-control">
                    </div>
                </div>
                <div class="col-lg-3 col-sm-4 col-12">
                    <div class="mb-3">
                        <label for="address" class="form-label">Address</label>
                        <textarea readonly name="address" id="address" rows="1" class="form-control"></textarea>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-4 col-12">
                    <div class="mb-3">
                        <label for="attn" class="form-label">Attn</label>
                        <input type="text" readonly name="attn" id="attn" class="form-control">
                    </div>
                </div>
                <div class="col-lg-3 col-sm-4 col-12">
                    <div class="mb-3">
                        <label for="tel" class="form-label">Tel</label>
                        <input type="text" readonly name="tel" id="tel" class="form-control">
                    </div>
                </div>
                <div class="col-lg-3 col-sm-4 col-12">
                    <div class="mb-3">
                        <label for="fax" class="form-label">Fax</label>
                        <input type="text" readonly name="fax" id="fax" class="form-control">
                    </div>
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-lg-3 col-sm-4 col-12">
                    <div class="mb-3">
                        <label for="invoice_no" class="form-label">Invoice No</label>
                        <input type="text" name="invoice_no" id="invoice_no" class="form-control"
                            value="{{ $invoice->invoice_no }}">
                    </div>
                </div>
                <div class="col-lg-3 col-sm-4 col-12">
                    <div class="mb-3">
                        <label for="date" class="form-label">Created Date</label>
                        <input readonly type="text" readonly name="date" id="date" class="form-control"
                            value="{{ date('d-m-Y') }}">
                    </div>
                </div>
                <div class="col-lg-3 col-sm-4 col-12">
                    <div class="mb-3">
                        <label for="created_by" class="form-label">Created By</label>
                        <input type="text" readonly name="created_by" id="created_by" class="form-control"
                            value="{{ Auth::user()->user_name }}">
                    </div>
                </div>
                <div class="col-lg-3 col-sm-4 col-12">
                    <div class="mb-3">
                        <label for="acc_no" class="form-label">A/C No</label>
                        <input type="text" name="acc_no" id="acc_no" class="form-control"
                            value="{{ $invoice->acc_no }}">
                    </div>
                </div>
                <div class="col-lg-3 col-sm-4 col-12">
                    <div class="mb-3">
                        <label for="term" class="form-label">Term</label>
                        <input type="text" name="term" id="term" class="form-control"
                            value="{{ $invoice->term }}">
                    </div>
                </div>
                <div class="col-lg-3 col-sm-4 col-12">
                    <div class="mb-3">
                        <label for="payment_voucher_no" class="form-label">Payment Voucher No</label>
                        <input type="text" name="payment_voucher_no" id="payment_voucher_no" class="form-control"
                            value="{{ $invoice->payment_voucher_no }}">
                    </div>
                </div>
                <div class="col-lg-3 col-sm-4 col-12">
                    <div class="mb-3">
                        <label for="issue_date" class="form-label">Issued Date</label>
                        <input type="text" name="issue_date" id="issue_date" class="form-control"
                            value="{{ $invoice->issue_date }}">
                    </div>
                </div>
                <div class="col-lg-3 col-sm-4 col-12">
                    <div class="mb-3">
                        <label for="issued_by" class="form-label">Issued By</label>
                        <input type="text" name="issued_by" id="issued_by" class="form-control"
                            value="{{ $invoice->issued_by }}">
                    </div>
                </div>
            </div>
            <br>
            <div class="row">
                <div class="table-responsive">
                    <table class="table table-bordered m-0" id="mainTable">
                        <thead>
                            <tr>
                                <th>Sr No</th>
                                <th>Part No</th>
                                <th>Part Name</th>
                                <th>Unit</th>
                                <th>Quantity</th>
                                <th>Unit Price (RM)</th>
                                <th>Disc Amount</th>
                                <th>Total Excl. SST</th>
                                <th>SST%</th>
                                <th>Total Incl. SST</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($invoice_details as $invoice_detail)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td><input type="hidden" class="product_id"
                                            name="products[{{ $loop->iteration }}][product_id]"
                                            value="{{ $invoice_detail->product_id }}">{{ $invoice_detail->product->part_no }}
                                    </td>
                                    <td>{{ $invoice_detail->product->part_name }}</td>
                                    <td>{{ $invoice_detail->product->units->name ?? '' }}</td>
                                    <td><input type="number" readonly class="form-control qty"
                                            name="products[{{ $loop->iteration }}][qty]"
                                            value="{{ $invoice_detail->qty }}"></td>
                                    <td><input type="number" readonly class="form-control price"
                                            name="products[{{ $loop->iteration }}][price]"
                                            value="{{ $invoice_detail->price }}"></td>
                                    <td><input type="number" class="form-control disc"
                                            name="products[{{ $loop->iteration }}][disc]"
                                            value="{{ $invoice_detail->disc }}"></td>
                                    <td><input type="number" readonly class="form-control excl_sst"
                                            name="products[{{ $loop->iteration }}][excl_sst]"
                                            value="{{ $invoice_detail->excl_sst }}"></td>
                                    <td><input type="number" class="form-control sst"
                                            name="products[{{ $loop->iteration }}][sst]"
                                            value="{{ $invoice_detail->sst }}"></td>
                                    <td><input type="number" readonly class="form-control incl_sst"
                                            name="products[{{ $loop->iteration }}][incl_sst]"
                                            value="{{ $invoice_detail->incl_sst }}"></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <div class="row">
                <div class="d-flex gap-2 justify-content-between col-12">
                    <a type="button" class="btn btn-info" href="{{ route('invoice.index') }}">
                        <i class="bi bi-arrow-left"></i> Back
                    </a>
                    <div class="d-flex gap-2 justify-content-end col-md-6">
                        <button type="button" class="btn btn-primary" onclick="submitForm('Approved')">Approved</button>
                        <button type="button" class="btn btn-danger" onclick="submitForm('Not Approved')">Not Approved</button>
                    </div>
                </div>
            </div>
        </div>
        
    </div>
    <form id="statusForm" action="{{ route('invoice.paymentVouchercheck', $invoice->id) }}" method="POST" style="display: none;">
        @csrf
        <input type="hidden" name="status" id="statusInput">
    </form>
    <script>
        let mainTable;
        let firstAttempt = true;
        $(document).ready(function() {
            outgoing_change();
            $('.card').find('input, select, textarea').prop('disabled', true);
            mainTable = $('#mainTable').DataTable();
        });

        var outgoings = {!! json_encode($outgoings) !!};



        function outgoing_change() {
            var selectedDOs = $("#outgoing_id").val();
            if (!selectedDOs) {
                resetCustomerDetails();
                return;
            }

            let customers = [];

            selectedDOs.forEach(outgoingId => {
                var outgoing = outgoings.find(p => p.id == outgoingId);
                if (outgoing) {
                    let customerDetails = getCustomerDetails(outgoing);
                    if (!customers.includes(JSON.stringify(customerDetails))) {
                        customers.push(JSON.stringify(customerDetails));
                    }
                }
            });

            
            let customer = JSON.parse(customers[0]);
            $('#customer').val(customer.name);
            $('#address').val(customer.address);
            $('#attn').val(customer.attn);
            $('#tel').val(customer.tel);
            $('#fax').val(customer.fax);
        }

        function getCustomerDetails(outgoing) {
            if (outgoing.sr_id) {
                return {
                    name: outgoing.sales_return.customer.name,
                    address: outgoing.sales_return.customer.address,
                    attn: outgoing.sales_return.customer.pic_name,
                    tel: outgoing.sales_return.customer.phone,
                    fax: outgoing.sales_return.customer.pic_fax
                };
            } else if (outgoing.pr_id) {
                return {
                    name: outgoing.purchase_return.supplier.name,
                    address: outgoing.purchase_return.supplier.address,
                    attn: outgoing.purchase_return.supplier.contact_person_name,
                    tel: outgoing.purchase_return.supplier.contact_person_telephone,
                    fax: outgoing.purchase_return.supplier.contact_person_fax
                };
            } else if (outgoing.order_id) {
                return {
                    name: outgoing.order.customers.name,
                    address: outgoing.order.customers.address,
                    attn: outgoing.order.customers.pic_name,
                    tel: outgoing.order.customers.phone,
                    fax: outgoing.order.customers.pic_fax
                };
            }
            return {};
        }

        function submitForm(status) {
            document.getElementById('statusInput').value = status;
            document.getElementById('statusForm').submit();
        }
    </script>
@endsection
