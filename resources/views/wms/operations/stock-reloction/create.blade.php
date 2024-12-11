@extends('layouts.app')
@section('title')
    STOCK RELOCATION CREATE
@endsection
@section('content')
    <style>
        #mainTable input {
            width: 100px;
        }
    </style>
    <div class="card">
        <form method="post" action="{{ route('stock_relocation.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label for="ref_no" class="form-label">Ref No</label>
                            <input type="text" readonly name="ref_no" id="ref_no" class="form-control"
                                value="{{ $ref_no }}">
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label for="date" class="form-label">Created Date</label>
                            <input type="text" readonly name="date" id="date" class="form-control"
                                value="{{ date('d-m-Y') }}">
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea name="description" id="description" rows="1" class="form-control">{{ old('description') }}</textarea>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label for="transfer_by" class="form-label">Transfer By</label>
                            <input type="text" readonly name="transfer_by" id="transfer_by" class="form-control"
                                value="{{ Auth::user()->user_name }}">
                        </div>
                    </div>
                </div>
                <br>
                <div class="row">
                    <h5>LOCATION TRANSFER</h5>
                </div>
                <br>
                <div class="row">
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label for="previous_location" class="form-label">Previous Location</label>
                            <select name="previous_location" id="previous_location" class="form-select">
                                <option value="" selected disabled>Please Select</option>
                                @foreach ($locations as $location)
                                    <option data-area-id="{{ $location->area->id }}"
                                        data-rack-id="{{ $location->rack->id }}" data-level-id="{{ $location->level->id }}"
                                        value="{{ $location->area->name }}->{{ $location->rack->name }}->{{ $location->level->name }}"
                                        @selected(old('previous_location') == $location->area->name . '->' . $location->rack->name . '->' . $location->level->name)>
                                        {{ $location->area->name }}->{{ $location->rack->name }}->{{ $location->level->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label for="new_location" class="form-label">New Location</label>
                            <select name="new_location" id="new_location" class="form-select">
                                <option value="" selected disabled>Please Select</option>
                                @foreach ($locations as $location)
                                    <option data-area-id="{{ $location->area->id }}"
                                        data-rack-id="{{ $location->rack->id }}"
                                        data-level-id="{{ $location->level->id }}"
                                        value="{{ $location->area->name }}->{{ $location->rack->name }}->{{ $location->level->name }}"
                                        @selected(old('new_location') == $location->area->name . '->' . $location->rack->name . '->' . $location->level->name)>
                                        {{ $location->area->name }}->{{ $location->rack->name }}->{{ $location->level->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-12 d-flex justify-content-end">
                        <button type="button" class="btn btn-primary" id="additem" disabled data-bs-toggle="modal"
                            data-bs-target="#exampleModalCenter">ADD PRODUCTS</button>
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
                                    <th>Available Qty</th>
                                    <th>Quantity</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="row">
                        <input type="hidden" name="previous_area_id" id="previous_area_id">
                        <input type="hidden" name="previous_rack_id" id="previous_rack_id">
                        <input type="hidden" name="previous_level_id" id="previous_level_id">
                        <input type="hidden" name="new_area_id" id="new_area_id">
                        <input type="hidden" name="new_rack_id" id="new_rack_id">
                        <input type="hidden" name="new_level_id" id="new_level_id">
                        <div class="d-flex gap-2 justify-content-between col-12">
                            <a type="button" class="btn btn-info" href="{{ route('stock_relocation.index') }}">
                                <i class="bi bi-arrow-left"></i> Back
                            </a>
                            <button type="button" class="btn btn-primary submit">Submit</button>
                        </div>
                    </div>
                </div>
            </div>
            {{-- PRODUCTS MODAL --}}
            <div class="modal fade" id="exampleModalCenter" tabindex="-1" aria-labelledby="exampleModalCenterTitle"
                aria-modal="true" role="dialog">
                <div class="modal-dialog modal-dialog-centered modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalCenterTitle">
                                PRODUCTS
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="table-responsive">
                                <table class="table table-bordered m-0 w-100" id="modalTable">
                                    <thead>
                                        <tr>
                                            <th>Select
                                                <input type="checkbox" id="selectAll" style="width: 22px; height: 22px;">
                                            </th>
                                            <th>Part No</th>
                                            <th>Part Name</th>
                                            <th>Unit</th>
                                            <th>Available Qty</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-secondry" data-bs-dismiss="modal">
                                CANCEL
                            </button>
                            <button type="button" class="btn btn-primary" onclick="add_product()">
                                ADD
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <script>
        let modalTable;
        let mainTable;
        $(document).ready(function() {
            modalTable = $('#modalTable').DataTable({
                "columnDefs": [{
                    "targets": 0,
                    "orderable": false
                }]
            });
            mainTable = $('#mainTable').DataTable();
        });

        $('#previous_location,#new_location').on('change', function() {
            mainTable.clear().draw();
            $previous = $('#previous_location').val();
            $new = $('#new_location').val();
            if ($previous == null || $new == null) {
                $('#additem').attr('disabled', 'disabled');
            } else if ($previous == $new) {
                $('#additem').attr('disabled', 'disabled');
            } else {
                $('#additem').removeAttr('disabled');
            }
        });

        $('#additem').click(function() {
            let area_id = $('#previous_location option:selected').attr('data-area-id');
            let rack_id = $('#previous_location option:selected').attr('data-rack-id');
            let level_id = $('#previous_location option:selected').attr('data-level-id');
            $.ajax({
                url: '{{ route('stock_relocation.products') }}',
                method: 'GET',
                data: {
                    area_id: area_id,
                    rack_id: rack_id,
                    level_id: level_id
                },
                success: function(response) {
                    modalTable.clear();
                    Object.values(response).forEach(element => {
                        modalTable.row.add([
                            `<input class="form-check-input product_id" type="checkbox"
                                                id="inlineCheckbox1" value="${element.product_id}"><input type="hidden" value="${element.product_id}" class="product_id">`,
                            element.part_no,
                            element.part_name,
                            element.units ? element.units.name : '',
                            element.used_qty
                        ]);
                    });
                    modalTable.draw();
                    let mainTableProductIds = new Set();
                    $("#mainTable tbody").find(".product_id").each(function() {
                        mainTableProductIds.add($(this).val());
                    });
                    $("#modalTable tbody").find(".product_id").each(function() {
                        if (mainTableProductIds.has($(this).val())) {
                            let modalRow = $(this).closest('tr');
                            modalTable.row(modalRow).remove();
                        }
                    });
                    modalTable.draw();
                }
            });
        });

        function add_product() {
            modalTable.$('input:checked').each(function() {
                var row = $(this).closest('tr');
                var rowData = modalTable.row(row).data();
                var product_id = $(row).find('.product_id').val();

                // Add the row data to the main table
                mainTable.row.add([
                    mainTable.rows().count() + 1,
                    rowData[1],
                    rowData[2],
                    rowData[3],
                    rowData[4],
                    `<input type="hidden" class="form-control available_qty" name="products[${mainTable.rows().count() + 1}][available_qty]" value="${rowData[4]}"><input type="hidden" name="products[${mainTable.rows().count() + 1}][product_id]" class="product_id" value="${product_id}"><input type="number" class="form-control qty" name="products[${mainTable.rows().count() + 1}][qty]" value="${rowData[4]}">`,
                    `<button type="button" class="btn btn-danger btn-sm remove-product"><i class="bi bi-trash"></i></button>`
                ]).draw(false);

                // Remove the row from the modal table
                modalTable.row(row).remove().draw();
            });

            // Uncheck all checkboxes
            $('#modalTable input:checked').prop('checked', false);

            // Add event listener to remove buttons
            $('#mainTable tbody').on('click', 'button.remove-product', function() {
                var row = $(this).closest('tr');
                var rowData = mainTable.row(row).data();
                let available_qty = $(row).find('.available_qty').val();

                // Add the removed row back to the modal table
                modalTable.row.add([
                    `<input class="form-check-input" type="checkbox"><input type="hidden" value="${$(row).find('.product_id').val()}" class="product_id">`,
                    rowData[1],
                    rowData[2],
                    rowData[3],
                    available_qty
                ]).draw(false);

                // Remove the row from the main table
                mainTable.row(row).remove().draw();
                resetSerialNumbers(mainTable);
            });

            // Hide the modal
            $('#exampleModalCenter').modal('hide');
        }

        function resetSerialNumbers() {
            if ($('#mainTable tbody tr:first').find('td:first').text() != 'No data available in table') {
                $('#mainTable tbody tr').each(function(index) {
                    $(this).find('td:first').text(index + 1);
                });
            }
        }

        document.getElementById('selectAll').addEventListener('change', function() {
            const checkboxes = document.querySelectorAll('.product_id');
            checkboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
        });
        $(document).on('keyup change', '.qty', function() {
            if (parseFloat($(this).val()) > parseFloat($(this).closest('tr').find('.available_qty').val())) {
                $(this).val($(this).closest('tr').find('.available_qty').val());
            }
        });

        $('.submit').on('click', function() {
            $('.card-body').find('.table').DataTable().page.len(-1).draw();

            let previous_area_id = $('#previous_location option:selected').attr('data-area-id');
            let previous_rack_id = $('#previous_location option:selected').attr('data-rack-id');
            let previous_level_id = $('#previous_location option:selected').attr('data-level-id');

            let new_area_id = $('#new_location option:selected').attr('data-area-id');
            let new_rack_id = $('#new_location option:selected').attr('data-rack-id');
            let new_level_id = $('#new_location option:selected').attr('data-level-id');

            $('#previous_area_id').val(previous_area_id);
            $('#previous_rack_id').val(previous_rack_id);
            $('#previous_level_id').val(previous_level_id);

            $('#new_area_id').val(new_area_id);
            $('#new_rack_id').val(new_rack_id);
            $('#new_level_id').val(new_level_id);

            $(this).closest('form').submit();
        });
    </script>
@endsection
