@extends('layouts.app')
@section('title')
    INVOICE CREATE
@endsection
@section('content')
    <style>
        #mainTable input {
            width: 100px;
        }
    </style>
    <div class="card">
        
        <form method="post" action="{{ route('invoice.store') }}" enctype="multipart/form-data" id="myForm">
            @csrf
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label for="outgoing_id" class="form-label">DO No</label>
                            <select name="outgoing_id[]" multiple onchange="outgoing_change()" id="outgoing_id" class="form-select">

                                @foreach ($outgoings as $outgoing)
                                    <option value="{{ $outgoing->id }}" @selected(old('outgoing_id') == $outgoing->id)>{{ $outgoing->ref_no }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label for="customer" class="form-label">Customer</label>
                            <input type="text" readonly name="customer" value="{{ old('customer') }}" id="customer" class="form-control">
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label for="address" class="form-label">Address</label>
                            <textarea readonly name="address" id="address" rows="1" class="form-control">{{ old('address') }}</textarea>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label for="attn" class="form-label">Attn</label>
                            <input type="text" readonly name="attn" value="{{ old('attn') }}" id="attn" class="form-control">
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label for="tel" class="form-label">Tel</label>
                            <input type="text" readonly name="tel" value="{{ old('tel') }}" id="tel" class="form-control">
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label for="fax" class="form-label">Fax</label>
                            <input type="text" readonly name="fax" value="{{ old('fax') }}" id="fax" class="form-control">
                        </div>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label for="invoice_no" class="form-label">Invoice No</label>
                            <input type="text" name="invoice_no" id="invoice_no"  class="form-control"
                                value="{{ old('invoice_no') }}">
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label for="date" class="form-label">Created Date</label>
                            <input readonly type="text" readonly name="date" id="date" class="form-control"
                                value="{{ date('d-m-Y') }}" >
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label for="created_by" class="form-label">Created By</label>
                            <input type="text" readonly name="created_by" id="created_by" class="form-control"
                                value="{{ Auth::user()->user_name }}" >
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label for="acc_no" class="form-label">A/C No</label>
                            <input type="text" name="acc_no" id="acc_no" class="form-control"
                                value="{{ old('acc_no') }}">
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label for="term" class="form-label">Term</label>
                            <input type="text" name="term" id="term" class="form-control"
                                value="{{ old('term') }}">
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label for="payment_voucher_no" class="form-label">Payment Voucher No</label>
                            <input type="text" name="payment_voucher_no" id="payment_voucher_no" class="form-control"
                                value="{{ old('payment_voucher_no') }}">
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label for="issue_date" class="form-label">Issued Date</label>
                            <input type="text" readonly name="issue_date" id="issue_date" class="form-control"
                                value="{{ date('d-m-Y') }}">
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label for="term" class="form-label">Issued By</label>
                            <input type="text" readonly name="issued_by" id="issued_by" class="form-control"
                                value="{{ Auth::user()->user_name }}">
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
                                @if(old('products'))
                                @php
                                    $key = 0;
                                @endphp
                                @foreach (old('products') as $key => $invoice_detail)

                                <tr>
                                    <td>{{  $key += 1 }}</td>
                                    <td><input type="hidden" class="product_id"
                                            name="products[{{ $key }}][product_id]"
                                            value="{{ $invoice_detail['product_id'] }}"><input type="hidden" value="{{ $invoice_detail['part_no'] }}" name="products[{{ $key }}][part_no]">{{ $invoice_detail['part_no'] }}
                                    </td>
                                    <td><input type="hidden" value="{{ $invoice_detail['part_name'] }}" name="products[{{ $key }}][part_name]">{{ $invoice_detail['part_name'] ?? '' }}</td>
                                    <td><input type="hidden" value="{{ $invoice_detail['unit_name'] }}" name="products[{{ $key }}][unit_name]">{{ $invoice_detail['unit_name'] ?? '' }}</td>
                                    <td><input type="number" readonly class="form-control qty"
                                            name="products[{{ $key }}][qty]"
                                            value="{{ $invoice_detail['qty'] }}"></td>
                                    <td><input type="number" readonly class="form-control price"
                                            name="products[{{ $key }}][price]"
                                            value="{{ $invoice_detail['price'] }}"></td>
                                    <td><input type="number" class="form-control disc"
                                            name="products[{{ $key }}][disc]"
                                            value="{{ $invoice_detail['disc'] }}"></td>
                                    <td><input type="number" readonly class="form-control excl_sst"
                                            name="products[{{ $key }}][excl_sst]"
                                            value="{{ $invoice_detail['excl_sst'] }}"></td>
                                    <td><input type="number" class="form-control sst"
                                            name="products[{{ $key }}][sst]"
                                            value="{{ $invoice_detail['sst'] }}"></td>
                                    <td><input type="number" readonly class="form-control incl_sst"
                                            name="products[{{ $key }}][incl_sst]"
                                            value="{{ $invoice_detail['incl_sst'] }}"></td>
                                </tr>
                            @endforeach
                            @endif


                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="row">
                        <div class="d-flex gap-2 justify-content-between col-12">
                            <a type="button" class="btn btn-info" href="{{ route('invoice.index') }}">
                                <i class="bi bi-arrow-left"></i> Back
                            </a>
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <script>
        let sale_price;
        let sale_tax;
        let mainTable;
        $(document).ready(function() {
    //         if($.fn.DataTable.isDataTable('#mainTable')) {
    //     // If it is, destroy the existing DataTable instance
    //     $('#mainTable').DataTable().destroy();
    // }

    // Reinitialize the DataTable
    mainTable = $('#mainTable').DataTable({
        // Your DataTable initialization options here
    });

    outgoing_change();

            $('#mainTable').on('input', '.disc, .sst', function() {
                updateRowTotal($(this).closest('tr'));
            });
        });

        var outgoings = {!! json_encode($outgoings) !!};
        sale_price = {!! json_encode($sale_price) !!};
        sale_tax = {!! json_encode($sale_tax) !!};

        function outgoing_change() {
            var selectedDOs = $("#outgoing_id").val();
            if (!selectedDOs) {
                resetCustomerDetails();
                return;
            }

            let customers = [];
            let products = {};

            selectedDOs.forEach(outgoingId => {
                var outgoing = outgoings.find(p => p.id == outgoingId);
                if (outgoing) {
                    let customerDetails = getCustomerDetails(outgoing);
                    if (!customers.includes(JSON.stringify(customerDetails))) {
                        customers.push(JSON.stringify(customerDetails));
                    }

                  
                    outgoing.outgoing_detail.forEach(item => {
                        if (!products[item.product.id]) {
                            products[item.product.id] = { ...item, qty: parseFloat(item.qty) || 0 };
                        } else {
                            products[item.product.id].qty += parseFloat(item.qty) || 0; 
                        }
                    });
                }
            });

           
            if (customers.length > 1) {
                alert("The selected DO numbers belong to different customers. Please select DOs with the same customer.");
                resetCustomerDetails();
                return;
            }

            
            let customer = JSON.parse(customers[0]);
            $('#customer').val(customer.name);
            $('#address').val(customer.address);
            $('#attn').val(customer.attn);
            $('#tel').val(customer.tel);
            $('#fax').val(customer.fax);

           
            populateProductTable(Object.values(products));
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

        function resetCustomerDetails() {
            $('#customer').val('');
            $('#address').val('');
            $('#attn').val('');
            $('#tel').val('');
            $('#fax').val('');
        }

        function populateProductTable(data) {
            mainTable.clear();
            data.forEach((product, index) => {
                let price = getLastPrice(product.product.id);
                mainTable.row.add([
                    mainTable.rows().count() + 1,
                    `<input type="hidden" class="product_id" name="products[${index}][product_id]" value="${product.product.id}">
                    <input type="hidden" value="${product.product.part_no}" name="products[${index}][part_no]">${product.product.part_no}`,
                    `<input type="hidden" value="${product.product.part_name}" name="products[${index}][part_name]">${product.product.part_name}`,
                    `<input type="hidden" value="${product.product.units.name}" name="products[${index}][unit_name]">${product.product.units?.name ?? ''}`,
                    `<input type="number" readonly class="form-control qty" name="products[${index}][qty]" value="${product.qty}">`,
                    `<input type="number" readonly class="form-control price" name="products[${index}][price]" value="${price}">`,
                    `<input type="number" class="form-control disc" name="products[${index}][disc]" value="">`,
                    `<input type="number" readonly class="form-control excl_sst" name="products[${index}][excl_sst]" value="">`,
                    `<input type="number" class="form-control sst" name="products[${index}][sst]" value="${sale_tax.sst_percentage ?? 0}">`,
                    `<input type="number" readonly class="form-control incl_sst" name="products[${index}][incl_sst]" value="">`,
                ]).draw(false);
                let newRow = $('#mainTable tr:last');
                updateRowTotal(newRow);
            });
        }

        function getLastPrice(productId) {
            let lastRecord = null;
            if (Array.isArray(sale_price) && sale_price.length > 0) {
                sale_price.forEach(record => {
                    if (record.product_id == productId) {
                        if (!lastRecord || new Date(record.created_at) > new Date(lastRecord.created_at)) {
                            lastRecord = record;
                        }
                    }
                });
            }
            return lastRecord ? lastRecord.price : 0;
        }

        function updateRowTotal(row) {
            let price = parseFloat(row.find('.price').val()) || 0;
            let qty = parseFloat(row.find('.qty').val()) || 0;
            let disc = parseFloat(row.find('.disc').val()) || 0;
            let SST = parseFloat(row.find('.sst').val()) || 0;

            let withDisc = (price * qty) - disc;
            let saleTax = (SST / 100) * withDisc;

            let excl_sst = withDisc;
            let incl_sst = withDisc + saleTax;

            row.find('.excl_sst').val(excl_sst.toFixed(2));
            row.find('.incl_sst').val(incl_sst.toFixed(2));
        }

        $('#myForm').on('submit', function(){
            $('.card-body').find('.table').DataTable().page.len(-1).draw();
        });
    </script>
@endsection
