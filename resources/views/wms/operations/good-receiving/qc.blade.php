@extends('layouts.app')
@section('title')
    GOOD RECEIVING QC
@endsection
@section('content')
    <style>
        #productTable input {
            width: 130px;
        }
    </style>
    <form method="post" action="{{ route('good_receiving.qc_update', $good_receiving->id) }}" enctype="multipart/form-data"
        id="myForm">
        @csrf
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <h5>GOOD RECEIVING DETAILS</h5>
                </div>
                <br>
                <div class="row">
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mt-4 d-flex">
                            <label class="form-label me-2" for="po_pr">PO No</label>
                            <div class="d-flex align-items-center">
                                <div class="form-check form-switch">
                                    <input disabled class="form-check-input" type="checkbox" id="po_pr" name="po_pr"
                                        @checked($good_receiving->po_pr) value="1">
                                </div>
                            </div>
                            <label class="form-check-label" for="po_pr">GRD No</label>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-4 col-12 purchase_order @if ($good_receiving->po_pr) d-none @endif">
                        <div class="mb-3">
                            <label for="po_id" class="form-label">Ref No.</label>
                            <select name="po_id" disabled id="po_id" class="form-select">
                                <option selected>
                                    {{ $good_receiving->purchase_order->ref_no ?? '' }}</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-4 col-12 purchase_return @if (!$good_receiving->po_pr) d-none @endif">
                        <div class="mb-3">
                            <label for="pr_id" class="form-label">Ref No.</label>
                            <select name="pr_id" disabled id="pr_id" class="form-select">
                                <option value="" selected disabled>Please Select</option>
                                <option selected>
                                    {{ $good_receiving->purchase_return->grd_no ?? '' }}</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-4 col-12 @if (!$good_receiving->po_pr) d-none @endif">
                        <div class="mb-3">
                            <label for="supplier" class="form-label">Supplier</label>
                            <input type="text" readonly name="supplier" id="supplier" class="form-control"
                                @if ($good_receiving->po_pr) value="{{ $good_receiving->purchase_return->grd_no ?? '' }}"
                        @else
                            value="{{ $good_receiving->purchase_order->ref_no ?? '' }}" @endif>
                        </div>
                    </div>
                    <div
                        class="col-lg-3 purchase_order_supplier col-sm-4 col-12 @if ($good_receiving->po_pr) d-none @endif">
                        <div class="mb-3">
                            <label for="order_supplier" class="form-label">Supplier</label>
                            <select name="order_supplier" id="order_supplier" class="form-select">
                                <option value="" selected disabled>Please Select</option>
                                @foreach ($suppliers as $supplier)
                                    <option value="{{ $supplier->id }}" @selected($good_receiving->supplier_id == $supplier->id)>
                                        {{ $supplier->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label for="date" class="form-label">Received Date</label>
                            <input type="date" readonly name="date" id="date" class="form-control"
                                value="{{ $good_receiving->date }}">
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label for="received_by" class="form-label">Received By</label>
                            <input type="text" readonly name="received_by" id="received_by" class="form-control"
                                value="{{ $good_receiving->user->user_name }}">
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label for="ref_no" class="form-label">DO No</label>
                            <input type="text" readonly name="ref_no" id="ref_no" class="form-control"
                                value="{{ $good_receiving->ref_no }}">
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-4 col-12">
                        <label for="inputGroupFileAddon03" class="form-label">Attachment</label>
                        <div class="input-group mb-3">
                            <a target="_blank" href="{{ asset('/order-attachments/') }}/{{ $good_receiving->attachment }}"
                                class="btn btn-outline-secondary" type="button" id="inputGroupFileAddon03">
                                <i class="bi bi-file-text"></i>
                            </a>
                            <input disabled type="file" class="form-control" id="inputGroupFile03"
                                aria-describedby="inputGroupFileAddon03" aria-label="Upload">
                        </div>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-12 d-flex justify-content-between">
                        <h5>PRODUCT/MATERIAL DETAILS</h5>
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
                                        <th>Unit</th>
                                        <th>Incoming Qty</th>
                                        <th>Received Qty</th>
                                        <th>Rejected Qty</th>
                                        <th>Rejected Reason</th>
                                        <th>Status</th>
                                        <th>Remarks</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($good_receiving_products as $good_receiving_product)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>
                                                <input type="date" class="form-control"
                                                    value="{{ $good_receiving_product->date }}"
                                                    name="products[{{ $loop->iteration }}][date]">
                                            </td>
                                            <td><input type="hidden" class="status"
                                                    name="products[{{ $loop->iteration }}][status]"
                                                    value="{{ $good_receiving_product->status }}"><input type="hidden"
                                                    class="product_id" value="{{ $good_receiving_product->product_id }}"
                                                    name="products[{{ $loop->iteration }}][product_id]">{{ $good_receiving_product->product->part_no }}
                                            </td>
                                            <td>{{ $good_receiving_product->product->part_name }}</td>
                                            <td>{{ $good_receiving_product->product->units->name ?? '' }}</td>
                                            <td><input type="number" readonly class="form-control qty"
                                                    value="{{ $good_receiving_product->incoming_qty }}"
                                                    name="products[{{ $loop->iteration }}][qty]">
                                            </td>
                                            <td><input type="number" readonly class="form-control received_qty"
                                                    value="{{ $good_receiving_product->received_qty }}"
                                                    name="products[{{ $loop->iteration }}][received_qty]"></td>
                                            <td><input type="number" readonly class="form-control rejected_qty"
                                                    value="{{ $good_receiving_product->rejected_qty }}"
                                                    name="products[{{ $loop->iteration }}][rejected_qty]">
                                            </td>
                                            <td><button type="button" class="btn btn-danger btn-sm openModal"
                                                    data-bs-toggle="modal" data-bs-target="#exampleModal"><i
                                                        class="bi bi-plus-circle"></i></button></td>
                                            <td>
                                                @if ($good_receiving_product->status == 1)
                                                    <span class="text-success">Accept</span>
                                                @elseif($good_receiving_product->status == 0)
                                                    <span class="text-danger">On Hold</span>
                                                @endif
                                            </td>
                                            <td>
                                                <textarea readonly class="form-control remarks" rows="1" name="products[{{ $loop->iteration }}][remarks]">{{ $good_receiving_product->remarks }}</textarea>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <div class="row">
                    <div class="d-flex gap-2 justify-content-between col-12">
                        <input type="hidden" id="storedData" name="details">
                        <a type="button" class="btn btn-info" href="{{ route('good_receiving.index') }}">
                            <i class="bi bi-arrow-left"></i> Back
                        </a>
                        <button type="button" class="btn btn-primary" id="saveForm">Submit</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
    {{-- REJECTION MODAL --}}
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">REJECTION</h5>
                    <input type="hidden" class="product_ids">
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12 d-flex justify-content-between">
                            <div>Part No: <span class="part_no_text"></span></div>
                            <div>Receiving Quantity: <span class="receiving_qty_text"></span></div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 d-flex justify-content-between">
                            <div>Part Name: <span class="part_name_text"></span></div>
                            <div>Rejected Quantity: <span class="rejected_qty_text"></span></div>
                        </div>
                    </div>
                    <br>
                    <div class="table-responsive" id="popUp">
                        <table class="table table-bordered m-0 w-100" id="rejectionTable">
                            <thead>
                                <tr>
                                    <th>Rejected Qty</th>
                                    <th>Rejection Type</th>
                                    <th>Comments</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary saveModal3" id="saveModal3">Release</button>
                    <button type="button" class="btn btn-primary" id="saveModal2">On Hold</button>
                    <button type="button" class="btn btn-primary" id="saveModal">Accept</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <script>
        var rejectionTable;
        let rejections = [];
        $(document).ready(function() {
            flatpickr("#date", {
                dateFormat: "d-m-Y",
                defaultDate: @json(\Carbon\Carbon::parse($good_receiving->date)->format('d-m-Y'))
            });
            $('#productTable').DataTable();
            rejectionTable = $('#rejectionTable').DataTable();
            sessionStorage.clear();
            var good_receiving_qcs = @json($good_receiving_qcs);
            good_receiving_qcs.forEach(element => {
                let data = sessionStorage.getItem(`modalData${element.product_id}`);
                if (!data) {
                    data = [];
                } else {
                    data = JSON.parse(data);
                }
                let rowData = {};
                rowData['rejection'] = `${element.tr_id}`;
                rowData['comments'] = element.comment ?? '';
                rowData['qty'] = element.qty;
                rowData['hiddenId'] = element.product_id;
                data.push(rowData);
                sessionStorage.setItem(`modalData${element.product_id}`, JSON.stringify(data));
            });
            rejections = @json($type_of_rejections);
        });

        // REJECTION WORK
        function addRow(button) {
            // Clone the row and get the data from it
            var row = button.parentNode.parentNode.cloneNode(true);
            var rowData = $(row).find('td').map(function() {
                return $(this).html();
            }).get();

            // Add the cloned data as a new row in DataTable
            rejectionTable.row.add(rowData).draw(false);

            // Trigger any additional required events
            $('.qty').trigger('keyup');
        }

        function removeRow(button) {
            // Check if there is more than one row
            if ($('#rejectionTable tr').length > 2) { // Including header row
                // Find the row index and remove it
                rejectionTable.row($(button).closest('tr')).remove().draw(false);
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
            let hiddenId = $(this).closest('tr').find('.product_id').val();
            $('.product_ids').val(hiddenId);
            let storedData = sessionStorage.getItem(`modalData${hiddenId}`);

            // Clear existing rows in the table
            rejectionTable.clear().draw();

            if (storedData) {
                storedData = JSON.parse(storedData);
                storedData.forEach(element => {
                    let optionsHtml = '';
                    rejections.forEach(rejection => {
                        let selected = '';
                        if (element.rejection === rejection.id) {
                            selected = 'selected';
                        }
                        optionsHtml +=
                            `<option value="${rejection.id}" ${selected}>${rejection.type}</option>`;
                    });

                    rejectionTable.row.add([
                        `<input type="number" class="form-control qty" value="${element.qty}">`,
                        `<select class="form-control rejection_type">${optionsHtml}</select>`,
                        `<input type="text" class="form-control comments" value="${element.comments}">`,
                        `<button type="button" class="btn btn-success btn-sm me-2" onclick="addRow(this)"><i class="bi bi-plus"></i></button><button type="button" class="btn btn-danger btn-sm" onclick="removeRow(this)"><i class="bi bi-dash"></i></button>`
                    ]).draw(false);
                });
            } else {
                let optionsHtml = '';
                rejections.forEach(rejection => {
                    optionsHtml +=
                        `<option value="${rejection.id}">${rejection.type}</option>`;
                });

                rejectionTable.row.add([
                    `<input type="number" class="form-control qty" value="">`,
                    `<select class="form-control rejection_type">${optionsHtml}</select>`,
                    `<input type="text" class="form-control comments" value="">`,
                    `<button type="button" class="btn btn-success btn-sm me-2" onclick="addRow(this)"><i class="bi bi-plus"></i></button><button type="button" class="btn btn-danger btn-sm" onclick="removeRow(this)"><i class="bi bi-dash"></i></button>`
                ]).draw(false);
            }

            let onHold = $(this).closest('tr').find('td:eq(9)').text();
            if (onHold == 'On Hold') {
                $('#saveModal2').hide();
                $('#saveModal3').show();
            } else {
                $('#saveModal2').show();
                $('#saveModal3').hide();
            }

            if ($(this).closest('tr').hasClass('Release')) {
                $('#rejectionTable').find('input, button, select').prop('disabled', true);
                $('.modal-footer').find('button').prop('disabled', true);
            } else {
                $('#rejectionTable').find('input, button, select').prop('disabled', false);
                $('.modal-footer').find('button').prop('disabled', false);
            }
            let part_no = $(this).closest('tr').find('td:eq(2)').text();
            let part_name = $(this).closest('tr').find('td:eq(3)').text();
            let receiving_qty = $(this).closest('tr').find('.received_qty').val();
            $('.part_no_text').text(part_no);
            $('.part_name_text').text(part_name);
            $('.receiving_qty_text').text(receiving_qty);
            $('.qty').trigger('keyup');
        });

        $('#saveModal').on('click', function() {
            var alertNeeded = false;

            var receiving_qty = parseFloat($('.receiving_qty_text').text());
            var rejected_qty = parseFloat($('.rejected_qty_text').text());
            if (rejected_qty > receiving_qty) {
                alertNeeded = true;
            }

            if (alertNeeded) {
                $('#popUp').prepend(`
                    <div class="alert border-warning alert-dismissible fade show text-warning" role="alert">
                    <b>Warning!</b> Rejected quantity must be greater than or equals to Qeceiving quantity!.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                `);
            } else {
                $('#exampleModal').modal('hide');
                let hiddenId = $('.product_ids').val();
                let data = [];
                $('#rejectionTable tbody tr').each(function() {
                    let rowData = {};
                    rowData['rejection'] = $(this).find('.rejection').val();
                    rowData['comments'] = $(this).find('.comments').val();
                    rowData['qty'] = $(this).find('.qty').val();
                    rowData['hiddenId'] = hiddenId;
                    data.push(rowData);
                });
                sessionStorage.setItem(`modalData${hiddenId}`, JSON.stringify(data));
                $('#productTable tbody tr').each(function() {
                    if ($(this).find('.product_id').val() == hiddenId) {
                        let total_qty = $('.rejected_qty_text').text();
                        $(this).find('.rejected_qty').val(total_qty);
                        $(this).find('.status').val(1);
                        $(this).find('td:eq(9)').css('color', 'green');
                        $(this).find('td:eq(9)').text('Accept');
                        $(this).removeClass('Release');
                    }
                });
            }
        });

        $('#saveModal2, #saveModal3').on('click', function() {
            var alertNeeded = false;
            let release = false;
            if ($(this).hasClass('saveModal3')) {
                release = true;
            }
            var receiving_qty = parseFloat($('.receiving_qty_text').text());
            var rejected_qty = parseFloat($('.rejected_qty_text').text());
            if (rejected_qty > receiving_qty) {
                alertNeeded = true;
            }

            if (alertNeeded) {
                $('#popUp').prepend(`
                    <div class="alert border-warning alert-dismissible fade show text-warning" role="alert">
                    <b>Warning!</b> Rejected quantity must be greater than or equals to Qeceiving quantity!.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                `);
            } else {
                $('#exampleModal').modal('hide');
                let hiddenId = $('.product_ids').val();
                let data = [];
                $('#rejectionTable tbody tr').each(function() {
                    let rowData = {};
                    rowData['rejection'] = $(this).find('.rejection').val();
                    rowData['comments'] = $(this).find('.comments').val();
                    rowData['qty'] = $(this).find('.qty').val();
                    rowData['hiddenId'] = hiddenId;
                    data.push(rowData);
                });
                sessionStorage.setItem(`modalData${hiddenId}`, JSON.stringify(data));
                $('#productTable tbody tr').each(function() {
                    if ($(this).find('.product_id').val() == hiddenId) {
                        let total_qty = $('.rejected_qty_text').text();
                        $(this).find('.rejected_qty').val(total_qty);
                        if (release) {
                            $(this).addClass('Release');
                            $(this).find('td:eq(9)').css('color', 'green');
                            $(this).find('td:eq(9)').text('Accept');
                            $(this).find('.status').val(1);
                        } else {
                            $(this).removeClass('Release');
                            $(this).find('td:eq(9)').css('color', 'red');
                            $(this).find('td:eq(9)').text('On Hold');
                            $(this).find('.status').val(0);
                        }
                    }
                });
            }
        });

        $(document).on('keyup change', '.qty', function() {
            let total = 0;
            $('#rejectionTable .qty').each(function() {
                total += +$(this).val();
            });
            $('.rejected_qty_text').text(total);
        });

        $('#saveForm').on('click', function() {
            $('.card-body').find('.table').DataTable().page.len(-1).draw();
            let array = [];
            $('.product_id').each(function() {
                let storedData = sessionStorage.getItem(`modalData${$(this).val()}`);
                if (storedData == null) {
                    storedData = `{"hiddenId":"${$(this).val()}"}`;
                }
                array.push(JSON.parse(storedData));
            });
            $('#storedData').val(JSON.stringify(array));
            $(this).closest('form').submit();
        });
    </script>
@endsection
