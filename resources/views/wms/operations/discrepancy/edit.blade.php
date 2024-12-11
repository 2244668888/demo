@extends('layouts.app')
@section('title')
    DISCREPANCY
@endsection
@section('content')
    <style>
        #productTable input {
            width: 130px;
        }
    </style>
    <form method="post" action="{{ route('discrepancy.update', $discrepancy->id) }}" enctype="multipart/form-data"
        id="myForm">
        @csrf
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <h5>DISCREPANCY DETAILS</h5>
                </div>
                <br>
                <div class="row">
                    <div class="col-lg-4 col-sm-4 col-12">
                        <div class="mb-3">
                            <label for="ref_no" class="form-label">Discrepancy Ref No.</label>
                            <input type="text" readonly value="{{ $discrepancy->ref_no }}" class="form-control">
                        </div>
                    </div>
                    <div class="col-lg-4 col-sm-4 col-12">
                        <div class="mb-3">
                            <label for="ref_no" class="form-label">Date</label>
                            <input type="text" readonly value="{{ $discrepancy->date }}" class="form-control">
                        </div>
                    </div>
                    <div class="col-lg-4 col-sm-4 col-12">
                        <div class="mb-3">
                            <label for="request_date" class="form-label">MRF No./TR No. <a href="#" target="_blank"
                                    id="mr_tr_view" type="button">
                                    <i class="bi bi-eye"></i></a></label>
                            <input type="text" readonly
                                value="@if ($check == 'tr') {{ $discrepancy->tr->ref_no }}@elseif ($check == 'mrf'){{ $discrepancy->mrf->ref_no }} @endif"
                                class="form-control">
                        </div>
                    </div>
                    <div class="col-lg-4 col-sm-4 col-12">
                        <div class="mb-3">
                            <label for="plan_date" class="form-label">Issuer</label>
                            <input type="text" readonly
                                value="@if ($check == 'tr') @php $issue_by = \App\Models\User::find($discrepancy->tr->issue_by);echo $issue_by->user_name ?? '';@endphp @elseif ($check == 'mrf') @php
                                if(!empty($discrepancy->mrf->issue_by))
                                {
                                    $issue_by = \App\Models\User::find($discrepancy->mrf->issue_by);
                                    echo $issue_by->user_name ?? '';
                                }@endphp @endif"
                                class="form-control">
                        </div>
                    </div>
                    <div class="col-lg-4 col-sm-4 col-12">
                        <div class="mb-3">
                            <label for="plan_date" class="form-label">Receiver</label>
                            <input type="text" readonly
                                value="@if ($check == 'tr') @php $rcv_by = \App\Models\User::find($discrepancy->tr->rcv_by);echo $rcv_by->user_name ?? '';
                        @endphp @elseif ($check == 'mrf')@php
                            if(!empty($discrepancy->mrf->rcv_by)){
                                $rcv_by = \App\Models\User::find($discrepancy->mrf->rcv_by);
                                echo $rcv_by->user_name ?? '';
                            }@endphp @endif"
                                class="form-control">
                        </div>
                    </div>
                    <div class="col-lg-4 col-sm-4 col-12">
                        <div class="mb-3">
                            <label for="plan_date" class="form-label">Shift</label>
                            <input type="text" readonly
                                value="@if ($discrepancy->tr) {{ $discrepancy->tr->shift }}@elseif ($discrepancy->mrf){{ $discrepancy->mrf->shift }} @endif"
                                class="form-control">
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
                                        <th>Part No.</th>
                                        <th>Part Name</th>
                                        <th>Type</th>
                                        <th>Model</th>
                                        <th>Variance</th>
                                        <th>Unit</th>
                                        <th>Discrepancy Qty</th>
                                        <th>Remarks</th>
                                        <th>Action</th>
                                        <th>Location</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <div class="d-flex gap-2 justify-content-between col-12">
                    <input type="hidden" id="storedData" name="details">
                    <a type="button" class="btn btn-info" href="{{ route('discrepancy.index') }}">
                        <i class="bi bi-arrow-left"></i> Back
                    </a>
                    <button type="button" class="btn btn-primary" id="saveForm">Submit</button>
                </div>
            </div>
        </div>

        {{-- LOCATIONS MODAL --}}
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">ALLOCATION</h5>
                        <input type="hidden" class="product_ids">
                        <input type="hidden" class="mrf_detail_ids">
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-12 d-flex justify-content-between">
                                <div>Part No: <span class="part_no_text"></span></div>
                                <div>Discrepancy Quantity: <span class="request_qty_text"></span></div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 d-flex justify-content-between">
                                <div>Part Name: <span class="part_name_text"></span></div>
                                <div>Receive: <span class="issue_qty_text"></span></div>
                            </div>
                        </div>
                        <br>
                        <div class="table-responsive" id="popUp">
                            <table class="table table-bordered m-0 w-100" id="allocationTable">
                                <thead>
                                    <tr>
                                        <th>Location</th>
                                        <th>Lot No</th>
                                        <th>Unit</th>
                                        <th>Receive Qty</th>
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
    {{-- REMARKS MODAL --}}
    <div class="modal fade" id="remarksModal" tabindex="-1" aria-labelledby="remarksModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="remarksModalLabel">Add Remarks</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <textarea id="remarksText" class="form-control" rows="4" placeholder="Enter your remarks here"></textarea>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" id="saveRemarks">Save</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <script>
        locations = @json($locations);
        discrepancy = @json($discrepancy);
        check = @json($check);

        mrf_tr_id = discrepancy.mrf_tr_id;
        var mr_tr_ViewUrlTemplate;
        if (check == 'tr') {
            mr_tr_ViewUrlTemplate = '{{ route('transfer_request.view', ':mr_trId') }}';
        } else {
            mr_tr_ViewUrlTemplate = '{{ route('material_requisition.view', ':mr_trId') }}';
        }
        let modalTable;
        let allocationTable;
        let productTable;
        console.log(locations);

        $(document).ready(function() {
            var mr_tr_ViewUrl = mr_tr_ViewUrlTemplate.replace(':mr_trId', mrf_tr_id);
            $('#mr_tr_view').attr('href', mr_tr_ViewUrl);
            allocationTable = $('#allocationTable').DataTable();
            sessionStorage.clear();
            modalTable = $('#modalTable').DataTable();
            productTable = $('#productTable').DataTable();
            productTable.row.add([
                productTable.rows().count() + 1,
                `<input type="hidden" name="product_id" class="product_id form-control" value="${discrepancy.products.id}">` +
                discrepancy.products.part_no,
                discrepancy.products.part_name,
                discrepancy.products.type_of_products.type,
                discrepancy.products.model,
                discrepancy.products.variance,
                discrepancy.products.units.name,
                discrepancy.issue_qty - discrepancy.rcv_qty,
                `<button type="button" class="btn btn-sm add-remarks btn-danger">Add</button> <input type="hidden" name="remarks" class="remarks form-control" >`,
                `<select class="form-control action" name="action"><option value="Issuer">Add to issuer</option><option value="Reciever">Add to receiver</option><option value="Deduct">Deduct from Issuer</option></select>`,
                `<button type="button" class="btn btn-success btn-sm openModal" data-bs-toggle="modal" data-bs-target="#exampleModal"><i class="bi bi-plus-circle"></i></button>`
            ]).draw(false).node();
            $('.action').select2();
            // Event listener for change event on action dropdown
            $('#productTable tbody').on('select2:select', '.action', function(e) {
                var selectedValue = $(this).val();
                var locationButton = $(this).closest('tr').find(
                '.openModal'); // Find the location button in the same row

                if (selectedValue === 'Reciever') {
                    locationButton.addClass('d-none'); // Hide location button
                } else {
                    locationButton.removeClass('d-none'); // Show location button
                }
            });
        });

        $('.action')

        function addRow(button) {
            // Clone the row and get the data from it
            let total = 0;
            $('#allocationTable .qty').each(function() {
                total += +$(this).val();

            });
            var req_qty = $('.request_qty_text').text();
            if (total >= req_qty) {
                $('#popUp').prepend(`
                    <div class="alert border-warning alert-dismissible fade show text-warning" role="alert">
                    <b>Warning!</b> Can't add more rows !.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                `);
            } else {
                var row = button.parentNode.parentNode.cloneNode(true);
                var rowData = $(row).find('td').map(function() {
                    return $(this).html();
                }).get();

                // Add the cloned data as a new row in DataTable
                allocationTable.row.add(rowData).draw(false);

                // Trigger any additional required events
                $('.qty').trigger('keyup');
            }

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
            var row = $(this).closest('tr');
            var rowData = productTable.row(row).data();
            let unit = $(row).find('td:eq(6)').text();

            // Clear existing rows in the table
            allocationTable.clear().draw();
            if (storedData) {
                storedData = JSON.parse(storedData);
                storedData.forEach(element => {
                    let locationOptionsHtml = '';
                    let lotOptionsHtml = '';

                    locations.forEach(location => {
                        if (location.product_id == hiddenId) {
                            let selectedLot = (element.lot_no === location.lot_no) ? 'selected' :
                            '';
                            lotOptionsHtml +=
                                `<option value="${location.lot_no}" ${selectedLot}>${location.lot_no}</option>`;
                        }
                    });

                    // Populate location options for the selected lot number
                    locations.forEach(location => {
                        if (location.product_id == hiddenId && location.lot_no === element.lot_no) {
                            let selected = (element.location ===
                                `${location.area_id}->${location.rack_id}->${location.level_id}`
                                ) ? 'selected' : '';
                            locationOptionsHtml +=
                                `<option data-area-id="${location.area_id}" data-rack-id="${location.rack_id}" data-level-id="${location.level_id}" data-qty="${location.used_qty}" value="${location.area_id}->${location.rack_id}->${location.level_id}" ${selected}>${location.area.name}->${location.rack.name}->${location.level.name}</option>`;
                        }
                    });

                    allocationTable.row.add([
                        `<select class="form-select lot_no">${lotOptionsHtml}</select>`,
                        `<select class="form-select location">${locationOptionsHtml}</select>`,
                        `${unit}`,
                        `<input type="number" class="form-control qty" value="${element.qty}">`,
                        `<button type="button" class="btn btn-success btn-sm me-2" onclick="addRow(this)"><i class="bi bi-plus"></i></button><button type="button" class="btn btn-danger btn-sm" onclick="removeRow(this)"><i class="bi bi-dash"></i></button>`
                    ]).draw(false);
                });
            } else {
                var flag = false;
                let locationOptionsHtml = '';
                let lotOptionsHtml = '';
                // Populate lot number options
                locations.forEach(location => {
                    lotOptionsHtml += `<option value="${location.lot_no}">${location.lot_no}</option>`;
                });
                let selectedLotNo = locations[0].lot_no; // Assuming first lot number initially
                locations.forEach(location => {
                    if (location.lot_no === selectedLotNo) {
                        locationOptionsHtml +=
                            `<option data-area-id="${location.area_id}" data-rack-id="${location.rack_id}" data-level-id="${location.level_id}" data-qty="${location.used_qty}" value="${location.area_id}->${location.rack_id}->${location.level_id}">${location.area.name}->${location.rack.name}->${location.level.name}</option>`;
                    }
                });
                allocationTable.row.add([
                    `<select class="form-select lot_no"><option>select</option>${lotOptionsHtml}</select>`,
                    `<select class="form-select location"><option>select</option>${locationOptionsHtml}</select>`,
                    `${unit}`,
                    `<input type="number" class="form-control qty">`,
                    `<button type="button" class="btn btn-success btn-sm me-2" onclick="addRow(this)"><i class="bi bi-plus"></i></button><button type="button" class="btn btn-danger btn-sm" onclick="removeRow(this)"><i class="bi bi-dash"></i></button>`
                ]).draw(false);

            }

            let part_no = $(this).closest('tr').find('td:eq(1)').text();
            let part_name = $(this).closest('tr').find('td:eq(2)').text();
            let request_qty_text = $(row).find('td:eq(7)').text();
            $('.part_no_text').text(part_no);
            $('.part_name_text').text(part_name);
            $('.request_qty_text').text(request_qty_text);
            $('.qty').trigger('keyup');

            // Function to fetch and display quantity based on selected location
            function updateQuantity(locationSelect) {
                const selectedLocation = $(locationSelect).find('option:selected');
                const qty = selectedLocation.attr('data-qty');
                const qtyInput = $(locationSelect).closest('tr').find('.avl_qty');
                qtyInput.val(qty);
            }
        });

        $('#productTable tbody').on('click', '.add-remarks', function() {
            selectedRow = productTable.row($(this).closest('tr'));
            $('#remarksText').val($(this).closest('tr').find('.remarks').val());
            $('#remarksText').prop('disabled', false); // Use prop with false
            $('#remarksModal').modal('show');
        });

        $('#saveRemarks').on('click', function() {
            let remarks = $('#remarksText').val();
            let button = selectedRow.node().querySelector('.add-remarks');
            if (remarks.trim() !== '') {
                button.classList.remove('btn-danger');
                button.classList.add('btn-success');
                button.textContent = 'Edit';
            } else {
                button.classList.remove('btn-success');
                button.classList.add('btn-danger');
                button.textContent = 'Add';
            }
            selectedRow.node().querySelector('.remarks').value = remarks;
            $('#remarksModal').modal('hide');
        });




        $('#saveModal').on('click', function() {
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
        });

        $(document).on('keyup change', '.qty', function() {
            let total = 0;
            $('#allocationTable .qty').each(function() {
                total += +$(this).val();
            });
            var request_qty_text = $('.request_qty_text').text();
            if (total > request_qty_text) {
                newqty = total - request_qty_text;
                $(this).val(newqty);
                total = 0;
                $('#allocationTable .qty').each(function() {
                    total += +$(this).val();
                });
            }
            if ($(this).val() <= 0) {
                $(this).val(0);
                total = 0;
                $('#allocationTable .qty').each(function() {
                    total += +$(this).val();
                });

            }
            $('.issue_qty_text').text(total);
        });

        $('#saveForm').on('click', function() {
            $('.card-body').find('.table').DataTable().page.len(-1).draw();
            let array = [];
            $('.product_id').each(function() {
                let storedData = sessionStorage.getItem(`modalData${$(this).val()}`);
                if (storedData == null) {

                }
                array.push(JSON.parse(storedData));
            });
            $('#storedData').val(JSON.stringify(array));
            $(this).closest('form').submit();
        });
    </script>
@endsection
