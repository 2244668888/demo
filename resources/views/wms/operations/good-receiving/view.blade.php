@extends('layouts.app')
@section('title')
    GOOD RECEIVING VIEW
@endsection
@section('content')
    <style>
        #productTable input {
            width: 130px;
        }
    </style>
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
                                    <th>Accepted Qty</th>
                                    <th>Remarks</th>
                                    <th>Allocation Remarks</th>
                                    <th>Allocation</th>
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
                                        <td><button type="button" class="btn btn-danger btn-sm openModal1"
                                                data-bs-toggle="modal" data-bs-target="#exampleModal1"><i
                                                    class="bi bi-plus-circle"></i></button></td>
                                        <td>
                                            <input type="hidden" class="status"
                                                value="{{ $good_receiving_product->status }}"
                                                name="products[{{ $loop->iteration }}][status]">
                                            @if ($good_receiving_product->status == 1)
                                                <span class="text-success">Accept</span>
                                            @elseif($good_receiving_product->status == 0)
                                                <span class="text-danger">On Hold</span>
                                            @endif
                                        </td>
                                        <td><input type="number" readonly class="form-control accepted_qty"
                                                value="{{ $good_receiving_product->accepted_qty }}"
                                                name="products[{{ $loop->iteration }}][accepted_qty]">
                                            <input type="hidden" class="remarks"
                                                value="{{ $good_receiving_product->remarks }}"
                                                name="products[{{ $loop->iteration }}][remarks]">
                                            <input type="hidden" class="allocation_remarks"
                                                value="{{ $good_receiving_product->allocation_remarks }}"
                                                name="products[{{ $loop->iteration }}][allocation_remarks]">
                                        </td>
                                        <td>
                                            <button type="button"
                                                class="btn btn-primary btn-sm add-remarks">View</button>
                                        </td>
                                        <td>
                                            <button type="button"
                                                class="btn btn-primary btn-sm allocation-remarks">View</button>
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
        <div class="d-flex gap-2 justify-content-between col-12">
            <input type="hidden" id="storedData" name="details">
            <a type="button" class="btn btn-info" href="{{ route('good_receiving.index') }}">
                <i class="bi bi-arrow-left"></i> Back
            </a>
        </div>
    </div>
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
    {{-- REJECTION MODAL --}}
    <div class="modal fade" id="exampleModal1" tabindex="-1" aria-labelledby="exampleModal1Label" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModal1Label">REJECTION</h5>
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
                    <textarea disabled id="remarksText" class="form-control" rows="4"></textarea>
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
                    <textarea disabled id="allocationRemarksText" class="form-control" rows="4"></textarea>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <script>
        var allocationTable;
        var rejectionTable;
        var productTable;
        let locations = [];
        let rejections = [];
        $(document).ready(function() {
            flatpickr("#date", {
                dateFormat: "d-m-Y",
                defaultDate:@json(\Carbon\Carbon::parse($good_receiving->date)->format('d-m-Y'))
            });
            productTable = $('#productTable').DataTable();
            allocationTable = $('#allocationTable').DataTable();
            rejectionTable = $('#rejectionTable').DataTable();
            sessionStorage.clear();
            var good_receiving_locations = @json($good_receiving_locations);
            good_receiving_locations.forEach(element => {
                let data = sessionStorage.getItem(`modalData${element.product_id}`);
                if (!data) {
                    data = [];
                } else {
                    data = JSON.parse(data);
                }
                let rowData = {};
                rowData['location'] = `${element.area_id}->${element.rack_id}->${element.level_id}`;
                rowData['area'] = element.area_id;
                rowData['rack'] = element.rack_id;
                rowData['level'] = element.level_id;
                rowData['lot_no'] = element.lot_no;
                rowData['qty'] = element.qty;
                rowData['hiddenId'] = element.product_id;
                data.push(rowData);
                sessionStorage.setItem(`modalData${element.product_id}`, JSON.stringify(data));
            });
            var good_receiving_qcs = @json($good_receiving_qcs);
            good_receiving_qcs.forEach(element => {
                let data = sessionStorage.getItem(`modalData1${element.product_id}`);
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
                sessionStorage.setItem(`modalData1${element.product_id}`, JSON.stringify(data));
            });
            locations = @json($locations);
            rejections = @json($type_of_rejections);
        });

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
                        `<select disabled class="form-control location">${optionsHtml}</select>`,
                        `<input type="text" class="form-control lot_no" readonly value="${element.lot_no}">`,
                        `<input type="number" class="form-control qty" readonly value="${element.qty}">`,
                    ]).draw(false);
                });
            } else {
                let optionsHtml = '';
                locations.forEach(location => {
                    optionsHtml +=
                        `<option data-area-id="${location.area_id}" data-rack-id="${location.rack_id}" data-level-id="${location.level_id}" value="${location.area_id}->${location.rack_id}->${location.level_id}">${location.area.name}->${location.rack.name}->${location.level.name}</option>`;
                });

                allocationTable.row.add([
                    `<select disabled class="form-control location">${optionsHtml}</select>`,
                    `<input type="text" class="form-control lot_no" readonly>`,
                    `<input type="number" class="form-control qty" readonly>`
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

        $(document).on('keyup change', '.qty', function() {
            let total = 0;
            $('#allocationTable .qty').each(function() {
                total += +$(this).val();
            });
            $('.allocate_qty_text').text(total);
        });

        $(document).on('click', '.openModal1', function() {
            let hiddenId = $(this).closest('tr').find('.product_id').val();
            $('.product_ids').val(hiddenId);
            let storedData = sessionStorage.getItem(`modalData1${hiddenId}`);

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
                        `<input type="number" readonly class="form-control qty1" value="${element.qty}">`,
                        `<select disabled class="form-control rejection_type">${optionsHtml}</select>`,
                        `<input type="text" readonly class="form-control comments" value="${element.comments}">`
                    ]).draw(false);
                });
            } else {
                let optionsHtml = '';
                rejections.forEach(rejection => {
                    optionsHtml +=
                        `<option value="${rejection.id}">${rejection.type}</option>`;
                });

                rejectionTable.row.add([
                    `<input type="number" readonly class="form-control qty1" value="">`,
                    `<select disabled class="form-control rejection_type">${optionsHtml}</select>`,
                    `<input type="text" readonly class="form-control comments" value="">`
                ]).draw(false);
            }

            let part_no = $(this).closest('tr').find('td:eq(1)').text();
            let part_name = $(this).closest('tr').find('td:eq(2)').text();
            let receiving_qty = $(this).closest('tr').find('.received_qty').val();
            $('.part_no_text').text(part_no);
            $('.part_name_text').text(part_name);
            $('.receiving_qty_text').text(receiving_qty);
            $('.qty1').trigger('keyup');
        });

        $(document).on('keyup change', '.qty1', function() {
            let total = 0;
            $('#rejectionTable .qty1').each(function() {
                total += +$(this).val();
            });
            $('.rejected_qty_text').text(total);
        });
    </script>
@endsection
