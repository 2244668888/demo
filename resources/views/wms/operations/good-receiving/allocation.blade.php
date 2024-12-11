@extends('layouts.app')
@section('title')
    GOOD RECEIVING ALLOCATION
@endsection
@section('content')
    <style>
        #productTable input {
            width: 130px;
        }
    </style>
    <form method="post" action="{{ route('good_receiving.allocation_update', $good_receiving->id) }}"
        enctype="multipart/form-data">
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
                                        <th>Accepted Qty</th>
                                        <th>Remarks</th>
                                        <th>Allocation Remarks</th>
                                        <th>Allocate</th>
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
                                            <td><input type="hidden" class="product_id"
                                                    value="{{ $good_receiving_product->product_id }}"
                                                    name="products[{{ $loop->iteration }}][product_id]">{{ $good_receiving_product->product->part_no }}
                                            </td>
                                            <td>{{ $good_receiving_product->product->part_name }}</td>
                                            <td>{{ $good_receiving_product->product->units->name ?? '' }}</td>
                                            <td><input type="number" readonly class="form-control accepted_qty"
                                                    value="{{ $good_receiving_product->accepted_qty }}"
                                                    name="products[{{ $loop->iteration }}][accepted_qty]">

                                            </td>
                                            <td>
                                                <input type="hidden" class="remarks"
                                                    value="{{ $good_receiving_product->remarks }}"
                                                    name="products[{{ $loop->iteration }}][remarks]">
                                                <button type="button"
                                                    class="btn btn-primary btn-sm add-remarks">View</button>
                                            </td>
                                            <td>
                                                <input type="hidden" class="allocation_remarks"
                                                    name="products[{{ $loop->iteration }}][allocation_remarks]"
                                                    value="{{ $good_receiving_product->allocation_remarks }}">
                                                @if ($good_receiving_product->allocation_remarks == '' || $good_receiving_product->allocation_remarks == null)
                                                    <button type="button"
                                                        class="btn btn-sm allocation-remarks btn-danger">Add</button>
                                                @else
                                                    <button type="button"
                                                        class="btn btn-sm allocation-remarks btn-success">Edit</button>
                                                @endif
                                            </td>
                                            <td><button type="button" class="btn btn-success btn-sm openModal"
                                                    data-bs-toggle="modal" data-bs-target="#exampleModal"><i
                                                        class="bi bi-plus-circle"></i></button></td>
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
    {{-- LOCATIONS MODAL --}}
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">ALLOCATION</h5>
                    <input type="hidden" class="product_ids">
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12 d-flex justify-content-between">
                            <div>Part No: <span class="part_no_text"></span></div>
                            <div>Accepted Quantity: <span class="accepted_qty_text"></span></div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 d-flex justify-content-between">
                            <div>Part Name: <span class="part_name_text"></span></div>
                            <div>Allocate Quantity: <span class="allocate_qty_text"></span></div>
                        </div>
                    </div>
                    <br>
                    <div class="table-responsive" id="popUp">
                        <table class="table table-bordered m-0 w-100" id="allocationTable">
                            <thead>
                                <tr>
                                    <th>Location</th>
                                    <th>Lot No</th>
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
    {{-- REMARKS MODAL --}}
    <div class="modal fade" id="remarksModal" tabindex="-1" aria-labelledby="remarksModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <input type="hidden" class="product_idss">
                    <h5 class="modal-title" id="remarksModalLabel">Remarks</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <textarea id="remarksText" disabled class="form-control" rows="4"></textarea>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    {{-- ALLOCATION REMARKS MODAL --}}
    <div class="modal fade" id="allocationRemarksModal" tabindex="-1" aria-labelledby="allocationRemarksModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <input type="hidden" class="product_idss">
                    <h5 class="modal-title" id="allocationRemarksModalLabel">Allocation Remarks</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <textarea id="allocationRemarksText" class="form-control" rows="4"></textarea>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="saveRemarks">Save</button>
                </div>
            </div>
        </div>
    </div>
    <script>
        var allocationTable;
        var productTable;
        let locations = [];
        $(document).ready(function() {
            flatpickr("#date", {
                dateFormat: "d-m-Y",
                defaultDate: @json(\Carbon\Carbon::parse($good_receiving->date)->format('d-m-Y'))
            });
            productTable = $('#productTable').DataTable();
            allocationTable = $('#allocationTable').DataTable();
            sessionStorage.clear();
            locations = @json($locations);
        });

        // location WORK
        function addRow(button) {
            // Clone the row and get the data from it
            var row = button.parentNode.parentNode.cloneNode(true);
            var rowData = $(row).find('td').map(function() {
                return $(this).html();
            }).get();

            // Add the cloned data as a new row in DataTable
            allocationTable.row.add(rowData).draw(false);

            // Trigger any additional required events
            $('.qty').trigger('keyup');
        }

        function removeRow(button) {
            // Check if there is more than one row
            if ($('#allocationTable tr').length > 2) { // Including header row
                // Find the row index and remove it
                allocationTable.row($(button).closest('tr')).remove().draw(false);
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

        $('#productTable tbody').on('click', '.add-remarks', function() {
            $('.product_idss').val($(this).closest('tr').find('.product_id').val());
            $('#remarksText').val($(this).closest('tr').find('.remarks').val());
            $('#remarksModal').modal('show');
        });

        $(document).on('click', '.allocation-remarks', function() {
            selectedRow = productTable.row($(this).closest('tr'));
            $('#allocationRemarksText').val($(this).closest('tr').find('.allocation_remarks').val());
            $('#allocationRemarksModal').modal('show');
        });

        $(document).on('click', '#saveRemarks', function() {
            let remarks = $('#allocationRemarksText').val();
            let button = selectedRow.node().querySelector('.allocation-remarks');
            if (remarks.trim() !== '') {
                button.classList.remove('btn-danger');
                button.classList.add('btn-success');
                button.textContent = 'Edit';
            } else {
                button.classList.remove('btn-success');
                button.classList.add('btn-danger');
                button.textContent = 'Add';
            }
            selectedRow.node().querySelector('.allocation_remarks').value = remarks;
            $('#allocationRemarksModal').modal('hide');
        });

        $(document).on('click', '.openModal', function() {
            let hiddenId = $(this).closest('tr').find('.product_id').val();
            $('.product_ids').val(hiddenId);
            let storedData = sessionStorage.getItem(`modalData${hiddenId}`);

            // Clear existing rows in the table
            allocationTable.clear().draw();

            if (storedData) {
                storedData = JSON.parse(storedData);
                storedData.forEach(element => {
                    let optionsHtml = '';
                    locations.forEach(location => {
                        let selected = '';
                        if (element.location ===
                            `${location.area_id}->${location.rack_id}->${location.level_id}`) {
                            selected = 'selected';
                        }
                        optionsHtml +=
                            `<option data-area-id="${location.area_id}" data-rack-id="${location.rack_id}" data-level-id="${location.level_id}" value="${location.area_id}->${location.rack_id}->${location.level_id}" ${selected}>${location.area.name}->${location.rack.name}->${location.level.name}</option>`;
                    });

                    allocationTable.row.add([
                        `<select class="form-control location">${optionsHtml}</select>`,
                        `<input type="text" class="form-control lot_no" value="${element.lot_no}">`,
                        `<input type="number" class="form-control qty" value="${element.qty}">`,
                        `<button type="button" class="btn btn-success btn-sm me-2" onclick="addRow(this)"><i class="bi bi-plus"></i></button><button type="button" class="btn btn-danger btn-sm" onclick="removeRow(this)"><i class="bi bi-dash"></i></button>`
                    ]).draw(false);
                });
            } else {
                let optionsHtml = '';
                locations.forEach(location => {
                    optionsHtml +=
                        `<option data-area-id="${location.area_id}" data-rack-id="${location.rack_id}" data-level-id="${location.level_id}" value="${location.area_id}->${location.rack_id}->${location.level_id}">${location.area.name}->${location.rack.name}->${location.level.name}</option>`;
                });

                allocationTable.row.add([
                    `<select class="form-control location">${optionsHtml}</select>`,
                    `<input type="text" class="form-control lot_no">`,
                    `<input type="number" class="form-control qty">`,
                    `<button type="button" class="btn btn-success btn-sm me-2" onclick="addRow(this)"><i class="bi bi-plus"></i></button><button type="button" class="btn btn-danger btn-sm" onclick="removeRow(this)"><i class="bi bi-dash"></i></button>`
                ]).draw(false);
            }

            let part_no = $(this).closest('tr').find('td:eq(1)').text();
            let part_name = $(this).closest('tr').find('td:eq(2)').text();
            let accepted_qty = $(this).closest('tr').find('.accepted_qty').val();
            $('.part_no_text').text(part_no);
            $('.part_name_text').text(part_name);
            $('.accepted_qty_text').text(accepted_qty);
            $('.qty').trigger('keyup');
        });

        $('#saveModal').on('click', function() {
            var alertNeeded = false;

            var accepted_qty = parseFloat($('.accepted_qty_text').text());
            var allocate_qty = parseFloat($('.allocate_qty_text').text());
            if (allocate_qty != accepted_qty) {
                alertNeeded = true;
            }

            if (alertNeeded) {
                $('#popUp').prepend(`
                    <div class="alert border-warning alert-dismissible fade show text-warning" role="alert">
                    <b>Warning!</b> Allocate quantity must be equals to Accepted quantity!.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                `);
            } else {
                $('#exampleModal').modal('hide');
                let hiddenId = $('.product_ids').val();
                let data = [];
                $('#allocationTable tbody tr').each(function() {
                    let rowData = {};
                    rowData['location'] = $(this).find('.location').val();
                    rowData['area'] = $(this).find('.location option:selected').attr('data-area-id');
                    rowData['rack'] = $(this).find('.location option:selected').attr('data-rack-id');
                    rowData['level'] = $(this).find('.location option:selected').attr('data-level-id');
                    rowData['lot_no'] = $(this).find('.lot_no').val();
                    rowData['qty'] = $(this).find('.qty').val();
                    rowData['hiddenId'] = hiddenId;
                    data.push(rowData);
                });
                sessionStorage.setItem(`modalData${hiddenId}`, JSON.stringify(data));
            }
        });

        $(document).on('keyup change', '.qty', function() {
            let total = 0;
            $('#allocationTable .qty').each(function() {
                total += +$(this).val();
            });
            $('.allocate_qty_text').text(total);
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
