@extends('layouts.app')
@section('title')
    PURCHASE RETURN RECEIVE
@endsection
@section('content')
    <div class="card">
        <form method="post" action="{{ route('purchase_return.receive_update', $purchase_return->id) }}"
            enctype="multipart/form-data">
            @csrf
            <div class="card-body">
                <div class="row">
                    <h5>GOOD RETURN DETAILS</h5>
                </div>
                <br>
                <div class="row">
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label for="grd_no" class="form-label">GRD No</label>
                            <input type="text" readonly name="grd_no" id="grd_no" class="form-control"
                                value="{{ $purchase_return->grd_no }}">
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label for="po_id" class="form-label">PO No</label>
                            <select name="po_id" disabled id="po_id" class="form-select">
                                <option selected>{{ $purchase_return->purchase_order->ref_no ?? '' }}</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label for="date" class="form-label">Created Date</label>
                            <input type="date" name="date" id="date" class="form-control"
                                value="{{ $purchase_return->date }}">
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label for="created_by" class="form-label">Created By</label>
                            <input type="text" readonly name="created_by" id="created_by" class="form-control"
                                value="{{ $purchase_return->user->user_name }}">
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label for="supplier_id" class="form-label">Supplier</label>
                            <select name="supplier_id" onchange="supplier_change()" id="supplier_id" class="form-select">
                                <option value="" selected disabled>Please Select</option>
                                @foreach ($suppliers as $supplier)
                                    <option value="{{ $supplier->id }}" @selected($purchase_return->supplier_id == $supplier->id)>{{ $supplier->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label for="address" class="form-label">Address</label>
                            <input type="text" readonly name="" id="address" class="form-control ">
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label for="attn" class="form-label">Attn</label>
                            <input type="text" readonly name="" id="attn" class="form-control ">
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label for="phone" class="form-label">Phone</label>
                            <input type="text" readonly name="" id="phone" class="form-control ">
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label for="for_office" class="form-label">For Office Use</label>
                            <select name="for_office" id="for_office" class="form-select">
                                <option value="" selected disabled>Please Select</option>
                                <option value="Return For Credit" @selected($purchase_return->for_office == 'Return For Credit')>Return For Credit</option>
                                <option value="Return For Replacement" @selected($purchase_return->for_office == 'Return For Replacement')>Return For Replacement
                                </option>
                                <option value="Good Loan Return" @selected($purchase_return->for_office == 'Good Loan Return')>Good Loan Return</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-4 col-12">
                        <label for="inputGroupFileAddon03" class="form-label">Attachment</label>
                        <div class="input-group mb-3">
                            <a target="_blank" href="{{ asset('/order-attachments/') }}/{{ $purchase_return->attachment }}"
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
                    <div class="col-12">
                        <h5>PRODUCT/MATERIAL DETAILS</h5>
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
                                    <th>Returned Qty</th>
                                    <th>Returned Reason</th>
                                    <th>Rejected Qty</th>
                                    <th>Rejected Remarks</th>
                                    <th>To Receive</th>
                                    <th>Receive Qty</th>
                                    <th>Receive Remarks</th>
                                    <th>Allocation</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($purchase_return_products as $purchase_return_product)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $purchase_return_product->product->part_no }}</td>
                                        <td>{{ $purchase_return_product->product->part_name }}</td>
                                        <td>{{ $purchase_return_product->product->units->name ?? '' }}</td>
                                        <td><input type="hidden" name="products[{{ $loop->iteration }}][product_id]"
                                                class="product_id" value="2">
                                            <input type="number" readonly class="form-control qty"
                                                name="products[{{ $loop->iteration }}][qty]"
                                                value="{{ $purchase_return_product->qty }}">
                                        </td>
                                        <td><input type="number" readonly class="form-control returned_qty"
                                                name="products[{{ $loop->iteration }}][returned_qty]"
                                                value="{{ $purchase_return_product->return_qty }}"></td>
                                        <td>
                                            <textarea readonly class="form-control reason" rows="1" name="products[{{ $loop->iteration }}][reason]">{{ $purchase_return_product->reason }}</textarea>
                                        </td>
                                        <td><input type="number" readonly class="form-control rejected_qty"
                                                name="products[{{ $loop->iteration }}][rejected_qty]"
                                                value="{{ $purchase_return_product->reject_qty }}">
                                        </td>
                                        <td>
                                            <textarea readonly class="form-control rejected_remarks" rows="1"
                                                name="products[{{ $loop->iteration }}][rejected_remarks]">{{ $purchase_return_product->reject_remarks }}</textarea>
                                        </td>
                                        <td><input type="number" readonly class="form-control to_receive"
                                                name="products[{{ $loop->iteration }}][to_receive]"
                                                value="{{ $purchase_return_product->return_qty - $purchase_return_product->reject_qty }}">
                                        </td>
                                        <td><input type="number" readonly class="form-control receive_qty"
                                                name="products[{{ $loop->iteration }}][receive_qty]" value="0">
                                        </td>
                                        <td>
                                            <textarea class="form-control receive_remarks" rows="1"
                                                name="products[{{ $loop->iteration }}][receive_remarks]"></textarea>
                                        </td>
                                        <td><button type="button" class="btn btn-success btn-sm openModal"
                                                data-bs-toggle="modal" data-bs-target="#exampleModal"><i
                                                    class="bi bi-plus-circle"></i></button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label for="remarks" class="form-label">Remarks</label>
                            <textarea name="remarks" id="remarks" rows="3" class="form-control">{{ $purchase_return->remarks }}</textarea>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="row">
                        <div class="d-flex gap-2 justify-content-between col-12">
                            <a type="button" class="btn btn-info" href="{{ route('purchase_return.index') }}">
                                <i class="bi bi-arrow-left"></i> Back
                            </a>
                            <input type="hidden" id="storedData" name="details">
                            <button type="button" class="btn btn-primary" id="saveForm">Submit</button>
                        </div>
                    </div>
                </div>
            </div>
            {{-- LOCATIONS MODAL --}}
            <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel"
                aria-hidden="true">
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
                                    <div>Unit: <span class="unit_text"></span></div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 d-flex justify-content-between">
                                    <div>Part Name: <span class="part_name_text"></span></div>
                                    <div>Total Quantity: <span class="total_qty_text"></span></div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 d-flex justify-content-between">
                                    <div><span class="total_qtyt"></span></div>
                                    <div>To Receive: <span class="to_receive_text"></span></div>
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
        </form>
    </div>
    <script>
        let mainTable;
        let allocationTable;
        let locations = [];
        $(document).ready(function() {
            flatpickr("#date", {
                dateFormat: "d-m-Y",
                defaultDate:@json(\Carbon\Carbon::parse($purchase_return->date)->format('d-m-Y'))
            });
            supplier_change();
            $('.card').find('input, select, textarea').prop('disabled', true);
            $('.table').find('.receive_qty, .receive_remarks, .to_receive').prop('disabled', false);
            $('input[type="hidden"]').prop('disabled', false);
            mainTable = $('#mainTable').DataTable();
            allocationTable = $('#allocationTable').DataTable();
            sessionStorage.clear();
            locations = @json($locations);
        });

        var suppliers = {!! json_encode($suppliers) !!};

        function supplier_change() {
            var supplierId = $("#supplier_id").val();
            var supplier = suppliers.find(p => p.id == supplierId);
            if (supplier) {
                $('#address').val(supplier.address);
                $('#attn').val(supplier.contact_person_name);
                $('#phone').val(supplier.contact);
            } else {
                $('#address').val('');
                $('#attn').val('');
                $('#phone').val('');
            }
        };

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
                        `<input type="number" class="form-control lot_no" value="${element.lot_no}">`,
                        `<input type="number" class="form-control qty" value="${element.qty}">`,
                        `<button type="button" class="btn btn-success btn-sm me-2" onclick="addRow(this)"><i class="bi bi-plus"></i></button><button type="button" class="btn btn-danger btn-sm" onclick="removeRow(this)"><i class="bi bi-dash"></i></button>`
                    ]).draw(false);
                });
            } else {
                let optionsHtml = '';
                locations.forEach((location, index) => {
                    optionsHtml +=
                        `<option ${(index == 0) ? 'selected' : ''} data-area-id="${location.area_id}" data-rack-id="${location.rack_id}" data-level-id="${location.level_id}" value="${location.area_id}->${location.rack_id}->${location.level_id}">${location.area.name}->${location.rack.name}->${location.level.name}</option>`;
                });

                allocationTable.row.add([
                    `<select class="form-control location">${optionsHtml}</select>`,
                    `<input type="number" class="form-control lot_no">`,
                    `<input type="number" class="form-control qty">`,
                    `<button type="button" class="btn btn-success btn-sm me-2" onclick="addRow(this)"><i class="bi bi-plus"></i></button><button type="button" class="btn btn-danger btn-sm" onclick="removeRow(this)"><i class="bi bi-dash"></i></button>`
                ]).draw(false);
            }

            let part_no = $(this).closest('tr').find('td:eq(1)').text();
            let part_name = $(this).closest('tr').find('td:eq(2)').text();
            let unit = $(this).closest('tr').find('td:eq(3)').text();
            let to_receive = $(this).closest('tr').find('.to_receive').val();
            $('.part_no_text').text(part_no);
            $('.part_name_text').text(part_name);
            $('.unit_text').text(unit);
            $('.to_receive_text').text(to_receive);
            $('.location').trigger('change');
            $('.qty').trigger('keyup');
        });

        $('#saveModal').on('click', function() {
            var alertNeeded = false;

            var total_qty = parseFloat($('.total_qty_text').text());
            var to_receive = parseFloat($('.to_receive_text').text());
            if (to_receive < total_qty) {
                alertNeeded = true;
            }

            if (alertNeeded) {
                $('#popUp').prepend(`
                    <div class="alert border-warning alert-dismissible fade show text-warning" role="alert">
                    <b>Warning!</b> total quantity must be greater or equals to receive quantity!.
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
                $('#mainTable tbody tr').each(function() {
                    if ($(this).find('.product_id').val() == hiddenId) {
                        let total_qty = $('.total_qty_text').text();
                        $(this).find('.receive_qty').val(total_qty);
                    }
                });
            }
        });

        $(document).on('keyup change', '.qty', function() {
            let total = 0;
            $('#allocationTable .qty').each(function() {
                total += +$(this).val();
            });
            $('.total_qty_text').text(total);
        });

        $('#saveForm').on('click', function() {
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
