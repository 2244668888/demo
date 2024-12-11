@extends('layouts.app')
@section('title')
    SALES RETURN VIEW
@endsection
@section('content')
    <div class="card">
        <div class="card-body">
            <div class="row">
                <h5>SALE RETURN DETAIL</h5>
            </div>
            <br>
            <div class="row">
                <div class="col-lg-3 col-sm-4 col-12">
                    <div class="mb-3">
                        <label for="ref_no" class="form-label">Ref No</label>
                        <input type="text" readonly name="ref_no" id="ref_no" class="form-control"
                            value="{{ $sales_return->ref_no }}">
                    </div>
                </div>
                <div class="col-lg-3 col-sm-4 col-12">
                    <div class="mb-3">
                        <label for="customer_id" class="form-label">Customer Name</label>
                        <select name="customer_id" onchange="customer_change()" id="customer_id" class="form-select">
                            <option value="" selected disabled>Please Select</option>
                            @foreach ($customers as $customer)
                                <option value="{{ $customer->id }}" @selected($sales_return->customer_id == $customer->id)>{{ $customer->name }}
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
                        <label for="date" class="form-label">Created Date</label>
                        <input type="date" name="date" id="date" class="form-control"
                            value="{{ $sales_return->date }}">
                    </div>
                </div>
                <div class="col-lg-3 col-sm-4 col-12">
                    <div class="mb-3">
                        <label for="created_by" class="form-label">Created By</label>
                        <input type="text" readonly name="created_by" id="created_by" class="form-control"
                            value="{{ $sales_return->user->user_name }}">
                    </div>
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-12 d-flex justify-content-between">
                    <h5>PRODUCT DETAIL</h5>
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
                                <th>Returned Qty</th>
                                <th>Reason</th>
                                <th>Allocation</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($sales_return_products as $sales_return_product)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $sales_return_product->product->part_no }}</td>
                                    <td>{{ $sales_return_product->product->part_name }}</td>
                                    <td>{{ $sales_return_product->product->units->name ?? '' }}</td>
                                    <td><input type="hidden" name="products[{{ $loop->iteration }}][product_id]"
                                            class="product_id" value="{{ $sales_return_product->product_id }}">
                                        <input type="number" readonly class="form-control returned_qty"
                                            name="products[{{ $loop->iteration }}][qty]"
                                            value="{{ $sales_return_product->qty ?? 0 }}">
                                    </td>
                                    <td>
                                        <textarea class="form-control reason" rows="1" name="products[{{ $loop->iteration }}][reason]">{{ $sales_return_product->reason }}</textarea>
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
        </div>
        <div class="card-footer">
            <div class="row">
                <div class="d-flex gap-2 justify-content-start col-12">
                    <a type="button" class="btn btn-info" href="{{ route('sales_return.index') }}">
                        <i class="bi bi-arrow-left"></i> Back
                    </a>
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
                                <div>Total Quantity: <span class="total_qty_text"></span></div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 d-flex justify-content-between">
                                <div>Unit: <span class="unit_text"></span></div>
                                <div>Part Name: <span class="part_name_text"></span></div>
                            </div>
                        </div>
                        <br>
                        <div class="table-responsive" id="popUp">
                            <table class="table table-bordered m-0 w-100" id="allocationTable">
                                <thead>
                                    <tr>
                                        <th>Location</th>
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
    </div>
    <script>
        let modalTable;
        let mainTable;
        let allocationTable;
        let locations = [];
        $(document).ready(function() {
            flatpickr("#date", {
                dateFormat: "d-m-Y",
                defaultDate:@json(\Carbon\Carbon::parse($sales_return->date)->format('d-m-Y'))
            });
            customer_change();
            $('.card-body input, select, textarea').prop('disabled', true);
            modalTable = $('#modalTable').DataTable();
            mainTable = $('#mainTable').DataTable();
            allocationTable = $('#allocationTable').DataTable();
            sessionStorage.clear();
            var sales_return_locations = @json($sales_return_locations);
            sales_return_locations.forEach(element => {
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
                rowData['qty'] = element.qty;
                rowData['hiddenId'] = element.product_id;
                data.push(rowData);
                sessionStorage.setItem(`modalData${element.product_id}`, JSON.stringify(data));
            });
            locations = @json($locations);
        });

        var customers = {!! json_encode($customers) !!};

        function customer_change() {
            var customerId = $("#customer_id").val();
            var customer = customers.find(p => p.id == customerId);
            if (customer) {
                $('#address').val(customer.address);
                $('#attn').val(customer.pic_name);
                $('#phone').val(customer.phone);
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
                        `<select class="form-control location" disabled>${optionsHtml}</select>`,
                        `<input type="number" class="form-control qty" readonly value="${element.qty}">`
                    ]).draw(false);
                });
            } else {
                let optionsHtml = '';
                locations.forEach(location => {
                    optionsHtml +=
                        `<option data-area-id="${location.area_id}" data-rack-id="${location.rack_id}" data-level-id="${location.level_id}" value="${location.area_id}->${location.rack_id}->${location.level_id}">${location.area.name}->${location.rack.name}->${location.level.name}</option>`;
                });

                allocationTable.row.add([
                    `<select class="form-control location" disabled>${optionsHtml}</select>`,
                    `<input type="number" class="form-control qty" readonly>`
                ]).draw(false);
            }

            let part_no = $(this).closest('tr').find('td:eq(1)').text();
            let part_name = $(this).closest('tr').find('td:eq(2)').text();
            let unit = $(this).closest('tr').find('td:eq(3)').text();
            $('.part_no_text').text(part_no);
            $('.part_name_text').text(part_name);
            $('.unit_text').text(unit);
            $('.qty').trigger('keyup');
        });

        $(document).on('keyup change', '.qty', function() {
            let total = 0;
            $('#allocationTable .qty').each(function() {
                total += +$(this).val();
            });
            $('.total_qty_text').text(total);
        });
    </script>
@endsection
