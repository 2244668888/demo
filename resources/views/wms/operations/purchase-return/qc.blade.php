@extends('layouts.app')
@section('title')
    PURCHASE RETURN QC
@endsection
@section('content')
    <div class="card">
        <form method="post" action="{{ route('purchase_return.qc_update', $purchase_return->id) }}"
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
                                        <td><input type="number" class="form-control rejected_qty"
                                                name="products[{{ $loop->iteration }}][rejected_qty]" value="0">
                                        </td>
                                        <td>
                                            <textarea class="form-control rejected_remarks" rows="1"
                                                name="products[{{ $loop->iteration }}][rejected_remarks]"></textarea>
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
                            <button type="submit" class="btn btn-primary">Submit</button>
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
                            <br>
                            <div class="table-responsive" id="popUp">
                                <table class="table table-bordered m-0 w-100" id="allocationTable">
                                    <thead>
                                        <tr>
                                            <th>Location</th>
                                            <th>Lot No</th>
                                            <th>Available Qty</th>
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
            $('.table').find('.rejected_qty, .rejected_remarks, .product_id').prop('disabled', false);
            $('input[type="hidden"]').prop('disabled', false);
            mainTable = $('#mainTable').DataTable();
            allocationTable = $('#allocationTable').DataTable();
            sessionStorage.clear();
            var purchase_return_locations = @json($purchase_return_locations);
            purchase_return_locations.forEach(element => {
                let data = sessionStorage.getItem(`modalData${element.product_id}`);
                if (!data) {
                    data = [];
                } else {
                    data = JSON.parse(data);
                }
                let rowData = {};
                rowData['location'] = `${element.area_id}->${element.shelf_id}->${element.level_id}`;
                rowData['area'] = element.area_id;
                rowData['shelf'] = element.shelf_id;
                rowData['level'] = element.level_id;
                rowData['qty'] = element.qty;
                rowData['lot_no'] = element.lot_no;
                rowData['available_qty'] = element.available_qty;
                rowData['hiddenId'] = element.product_id;
                data.push(rowData);
                sessionStorage.setItem(`modalData${element.product_id}`, JSON.stringify(data));
            });
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
                        `<input type="number" class="form-control lot_no" readonly value="${element.lot_no}">`,
                        `<input type="number" class="form-control available_qty" readonly value="${element.available_qty}">`,
                        `<input type="number" readonly class="form-control qty" value="${element.qty}">`
                    ]).draw(false);
                });
            } else {
                let optionsHtml = '';
                locations.forEach((location, index) => {
                    optionsHtml +=
                        `<option ${(index == 0) ? 'selected' : ''} data-area-id="${location.area_id}" data-rack-id="${location.rack_id}" data-level-id="${location.level_id}" value="${location.area_id}->${location.rack_id}->${location.level_id}">${location.area.name}->${location.rack.name}->${location.level.name}</option>`;
                });

                allocationTable.row.add([
                    `<select disabled class="form-control location">${optionsHtml}</select>`,
                    `<input type="number" class="form-control lot_no" readonly>`,
                    `<input type="number" class="form-control available_qty" readonly>`,
                    `<input type="number" readonly class="form-control qty">`
                ]).draw(false);
            }

            let part_no = $(this).closest('tr').find('td:eq(1)').text();
            let part_name = $(this).closest('tr').find('td:eq(2)').text();
            let unit = $(this).closest('tr').find('td:eq(3)').text();
            $('.part_no_text').text(part_no);
            $('.part_name_text').text(part_name);
            $('.unit_text').text(unit);
            $('.location').trigger('change');
            $('.qty').trigger('keyup');
        });

        $(document).on('keyup change', '.qty', function() {
            let total = 0;
            $('#allocationTable .qty').each(function() {
                total += +$(this).val();
            });
            $('.total_qty_text').text(total);
        });

        $(document).on('keyup change', '.rejected_qty', function() {
            let returned_qty = $(this).closest('tr').find('.returned_qty').val();
            let rejected_qty = $(this).val();
            if (parseFloat(rejected_qty) > parseFloat(returned_qty)) {
                $(this).val(returned_qty);
            }
        });

        $(document).on('change', '.location, .lot_no', function() {
            var value = $(this).closest('tr').find('.location').val();
            var $this = $(this).closest('tr').find('.available_qty');
            var lot_no = $(this).closest('tr').find('.lot_no').val();
            var location = $(this).closest('tr').find('.location');
            var area_id = location.find('option:selected').attr('data-area-id');
            var rack_id = location.find('option:selected').attr('data-rack-id');
            var level_id = location.find('option:selected').attr('data-level-id');
            var product_id = $('.product_ids').val();

            var $currentRow = $(this).closest('tr');
            var dropdownValueSelected = false;

            // Check if dropdown value is selected in any row
            $('.location').not(location).each(function() {
                if ($(this).val() === value) {
                    dropdownValueSelected = true;
                    return false; // Break out of the loop if a match is found
                }
            });

            if (!dropdownValueSelected) {
                $.ajax({
                    url: '{{ route('get.available_qty') }}',
                    method: 'GET',
                    data: {
                        area_id: area_id,
                        rack_id: rack_id,
                        level_id: level_id,
                        product_id: product_id,
                        lot_no: lot_no
                    },
                    success: function(response) {
                        $this.val(+$this.val() + +response.used_qty);
                    }
                });
            } else {
                // Dropdown value is selected in another row, set available qty to 0
                $this.val('0');
            }
        });
    </script>
@endsection
