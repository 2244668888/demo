@extends('layouts.app')
@section('title')
    PURCHASE REQUISITION EDIT
@endsection
@section('content')
    <div class="card">
        <form method="post" action="{{ route('purchase_requisition.update', $purchase_requisitions->id) }}"
            enctype="multipart/form-data" id="myForm">
            @csrf
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label for="requested_by" class="form-label">Requested By</label>
                            <input type="text" readonly name="requested_by" id="requested_by" class="form-control"
                                value="{{ Auth::user()->user_name }}">
                        </div>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label for="" class="form-label">PR No.</label>
                            <input type="text" readonly name="pr_no" value="{{ $purchase_requisitions->pr_no }}"
                                readonly id="part_name" class="form-control">
                        </div>
                    </div>

                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label for="" class="form-label">Department</label>
                            <input type="text" readonly value="{{ $department ? $department->name : '' }}"
                                class="form-control">
                            <input type="text" hidden name="department_id"
                                value="{{ $department ? $department->id : '' }}" class="form-control">
                        </div>
                    </div>

                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label for="" class="form-label">Status</label>
                            <select name="status" class="form-select">
                                <option {{ $purchase_requisitions->status == 'Priority' ? 'selected' : '' }}
                                    value="Priority">Priority</option>
                                <option {{ $purchase_requisitions->status == 'Urgent' ? 'selected' : '' }} value="Urgent">
                                    Urgent</option>
                                <option {{ $purchase_requisitions->status == 'Not Urgent' ? 'selected' : '' }}
                                    value="Not Urgent">Not Urgent</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label for="" class="form-label">Date :</label>
                            <input type="date" value="{{ date('Y-m-d', strtotime($purchase_requisitions->date)) }}"
                                name="date" class="form-control">
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label for="" class="form-label">Require Date :</label>
                            <input type="date" name="require_date"
                                value="{{ date('Y-m-d', strtotime($purchase_requisitions->require_date)) }}"
                                class="form-control">
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label for="" class="form-label">Category:</label>
                            <select name="category" id="categorySelect" onchange="handleCategoryChange()"
                                class="form-select">
                                <option {{ $purchase_requisitions->category == 'Printing & Stationary' ? 'selected' : '' }}
                                    value="Printing & Stationary">Printing & Stationary</option>
                                <option {{ $purchase_requisitions->category == 'Direct Item' ? 'selected' : '' }}
                                    value="Direct Item">Direct Item</option>
                                <option {{ $purchase_requisitions->category == 'Urgent' ? 'selected' : '' }}
                                    value="Urgent">Indirect Item</option>
                                <option {{ $purchase_requisitions->category == 'Asset' ? 'selected' : '' }} value="Asset">
                                    Asset</option>
                                <option {{ $purchase_requisitions->category == 'Others' ? 'selected' : '' }}
                                    value="Others">Others (please Specify)</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-lg-3 col-sm-4 col-12 @if (!$purchase_requisitions->category_other) d-none @endif" id="other_div">
                        <div class="mb-3">
                            <label for="" class="form-label">Others:</label>
                            <input type="text" value="{{ $purchase_requisitions->category_other }}"
                                class="form-control">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="text-center">
                            <button type="button" class="btn btn-primary float-end" data-bs-toggle="modal"
                                data-bs-target="#exampleModalCenter" id="additem">ADD PRODUCTS</button>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-sm-12 my-5">
                                <div class="table-responsive">
                                    <table class="table table-bordered m-0" id="mainTable">
                                        <thead>
                                            <tr>
                                                <th>No.</th>
                                                <th>Part No.</th>
                                                <th>Part Name</th>
                                                <th>Price (RM)</th>
                                                <th>Quantity</th>
                                                <th>Total (RM)</th>
                                                <th>Purpose</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($purchase_requisition_details as $purchase_requisition_detail)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <input type="hidden" class="unit"
                                                        value="{{ $purchase_requisition_detail->product->unit }}">
                                                    <input type="hidden" class="modal"
                                                        value="{{ $purchase_requisition_detail->product->modal }}">
                                                    <input type="hidden" class="varient"
                                                        value="{{ $purchase_requisition_detail->product->varient }}">
                                                    <input type="hidden" class="type"
                                                        value="{{ $purchase_requisition_detail->product->type }}">
                                                    <input type="hidden" class="category"
                                                        value="{{ $purchase_requisition_detail->product->category }}">
                                                    <td>{{ $purchase_requisition_detail->product->part_no }}</td>
                                                    <td>{{ $purchase_requisition_detail->product->part_name }}</td>
                                                    <td>
                                                        <input type="hidden"
                                                            name="products[{{ $loop->iteration }}][product_id]"
                                                            class="product_id"
                                                            value="{{ $purchase_requisition_detail->product_id }}">
                                                        <input type="number" readonly class="form-control price"
                                                            name="products[{{ $loop->iteration }}][price]"
                                                            value="{{ $purchase_requisition_detail->price }}">
                                                    </td>
                                                    <td>
                                                        <input type="number" class="form-control qty"
                                                            name="products[{{ $loop->iteration }}][qty]"
                                                            value="{{ $purchase_requisition_detail->qty }}">
                                                    </td>
                                                    <td>
                                                        <input type="number" class="form-control total" readonly
                                                            name="products[{{ $loop->iteration }}][total]"
                                                            value="{{ $purchase_requisition_detail->total }}">
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control"
                                                            name="products[{{ $loop->iteration }}][purpose]"
                                                            value="{{ $purchase_requisition_detail->purpose }}">
                                                    </td>
                                                    <td><button type="button"
                                                            class="btn btn-danger btn-sm remove-product"><i
                                                                class="bi bi-trash"></i></button></td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label for="" class="form-label">Grand Total (RM)</label>
                            <input type="text" id="grandtotal" value="{{ $purchase_requisitions->total }}"
                                name="total" class="form-control">
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-4 col-12">
                        <label for="inputGroupFileAddon03" class="form-label">Attachment</label>
                        <div class="input-group mb-3">
                            <a href="{{ asset('/pr-attachments/') }}/{{ $purchase_requisitions->attachment }}"
                                class="btn btn-outline-secondary" type="button" id="inputGroupFileAddon03" target="_blank">
                                <i class="bi bi-file-text"></i>
                            </a>
                            <input type="file" name="attachment" class="form-control" id="inputGroupFile03"
                                aria-describedby="inputGroupFileAddon03" aria-label="Upload">
                            </div>
                            {{ substr($purchase_requisitions->attachment,14) }}
                    </div>
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label for="" class="form-label">Remarks</label>
                            <textarea class="form-control" name="remarks" rows="1">{{ $purchase_requisitions->remarks }}</textarea>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <div class="row">
                    <div class="d-flex gap-2 justify-content-between col-12">
                        <a type="button" class="btn btn-info" href="{{ route('purchase_requisition.index') }}">
                            <i class="bi bi-arrow-left"></i> Back
                        </a>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </div>
            </div>
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
                                <table id="modalTable" class="table table-bordered m-0">
                                    <thead>
                                        <tr>
                                            <th>
                                                <input type="checkbox" id="selectAll" style="width: 22px; height: 22px;">
                                            </th>
                                            <th>Part No.</th>
                                            <th>Part Name</th>
                                            <th>Unit</th>
                                            <th>Model</th>
                                            <th>Variant</th>
                                            <th>Price (RM)</th>
                                            <th>Type of Product</th>
                                            <th>Category (OEM/REM)</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($products as $product)
                                            <tr>
                                                @php
                                                    $purchase_price = $purchase_prices->where('product_id', $product->id)
                                                                                    ->orderBy('created_at', 'desc')
                                                                                    ->first();
                                                @endphp
                                                <input type="hidden" class="product_id" value="{{ $product->id }}">
                                                <input type="hidden" class="price"
                                                    value="{{ $purchase_price ? $purchase_price->price : 0 }}">
                                                <td><input type="checkbox" class="form-check-input"></td>
                                                <td>
                                                    {{ $product->part_no }}
                                                </td>
                                                <td>{{ $product->part_name }}</td>
                                                <td>{{ $product->units->name ?? '' }}</td>
                                                <td>{{ $product->model }}</td>
                                                <td>{{ $product->variance }}</td>

                                                <td>{{ $purchase_price ? $purchase_price->price : 0 }}</td>
                                                <td>{{ $product->type_of_products->type ?? '' }}</td>
                                                <td>{{ $product->category }}</td>
                                            </tr>
                                        @endforeach
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
        function handleCategoryChange() {
            var selectedValue = $("#categorySelect").val();

            var otherDiv = $('#other_div');
            var categoryOtherInput = $('#category_other');

            if (selectedValue === "Others") {
                otherDiv.removeClass('d-none');
                categoryOtherInput.prop('disabled', false);
            } else {
                otherDiv.addClass('d-none');
                categoryOtherInput.prop('disabled', true);
            }
        };

        let modalTable;
        let mainTable;
        let firstAttempt = true;

        $(document).ready(function() {
            modalTable = $('#modalTable').DataTable({
                "columnDefs": [
                    {
                        "targets": 0,
                        "orderable": false
                    }
                ]
            });
            mainTable = $('#mainTable').DataTable();
            total = 0;
            grand_total = 0;
        });

        $(document).on('input', '.qty', function() {
            var qty = parseFloat($(this).val());
            if (isNaN(qty)) {
                qty = 0;
            }
            var price = parseFloat($(this).closest('tr').find('.price').val());
            if (isNaN(price)) {
                price = 0;
            }
            var total = price * qty;
            $(this).closest('tr').find('.total').val(total);

            // Recalculate the grand total
            recalculateGrandTotal();
        });

        $('#mainTable tbody').off('click', 'button.remove-product').on('click', 'button.remove-product', function() {
            var row = $(this).closest('tr');
            var rowData = mainTable.row(row).data();

            // Add the removed row back to the modal table
            modalTable.row.add([
                `<input type="hidden" value="${$(row).find('.product_id').val()}" class="product_id">
            <input type="hidden" value="${$(row).find('.price').val()}" class="price">
            <input class="form-check-input product_id" type="checkbox">`,
                rowData[1],
                rowData[2],
                $(row).find('.unit').val(),
                $(row).find('.modal').val(),
                $(row).find('.varient').val(),
                $(row).find('.price').val(),
                $(row).find('.type').val(),
                $(row).find('.category').val(),
            ]).draw(false);

            // Remove the row from the main table
            mainTable.row(row).remove().draw();

            // Recalculate the grand total
            recalculateGrandTotal();
            resetSerialNumbers(mainTable);
        });
        $('#additem').click(function() {
            if (firstAttempt) {
                let mainTableProductIds = new Set();
                $("#mainTable tbody").find(".product_id").each(function() {
                    mainTableProductIds.add($(this).val());
                });
                $("#modalTable tbody").find(".product_id").each(function() {
                    if (mainTableProductIds.has($(this).val())) {
                        $(this).closest('tr').remove();
                    }
                });
                firstAttempt = false;
            }
        });

        function add_product() {
            $('#mainTable').DataTable();
            $('#modalTable').DataTable();

            modalTable.$('input:checked').each(function() {
                var row = $(this).closest('tr');
                var rowData = modalTable.row(row).data();
                var product_id = $(row).find('.product_id').val();
                var price = $(row).find('.price').val();

                // Add the row data to the main table
                mainTable.row.add([
                    mainTable.rows().count() + 1,
                    rowData[1],
                    rowData[2],
                    `<input type="hidden" name="products[${mainTable.rows().count() + 1}][product_id]" class="product_id" value="${product_id}">
            <input type="hidden" class="unit" value="${rowData[3]}">
            <input type="hidden" class="modal" value="${rowData[4]}">
            <input type="hidden" class="varient" value="${rowData[5]}">
            <input type="hidden" class="type" value="${rowData[7]}">
            <input type="hidden" class="category" value="${rowData[8]}">
            <input type="number" readonly class="form-control price" name="products[${mainTable.rows().count() + 1}][price]" value="${price}">`,
                    `<input type="number" class="form-control qty" name="products[${mainTable.rows().count() + 1}][qty]">`,
                    `<input type="number" class="form-control total" readonly name="products[${mainTable.rows().count() + 1}][total]">`,
                    `<input type="text" class="form-control" name="products[${mainTable.rows().count() + 1}][purpose]">`,
                    `<button type="button" class="btn btn-danger btn-sm remove-product"><i class="bi bi-trash"></i></button>`
                ]).draw(false);

                // Remove the row from the modal table
                modalTable.row(row).remove().draw();

                // Recalculate the grand total
                recalculateGrandTotal();
            });

            // Attach keyup event listener to the quantity inputs
            mainTable.$('.qty').off('keyup').on('keyup', function() {
                var qty = parseFloat($(this).val());
                if (isNaN(qty)) {
                    qty = 0;
                }
                var price = parseFloat($(this).closest('tr').find('.price').val());
                if (isNaN(price)) {
                    price = 0;
                }
                var total = price * qty;
                $(this).closest('tr').find('.total').val(total);

                // Recalculate the grand total
                recalculateGrandTotal();
            });

            // Uncheck all checkboxes
            $('#modalTable input:checked').prop('checked', false);

            // Add event listener to remove buttons
            $('#mainTable tbody').off('click', 'button.remove-product').on('click', 'button.remove-product', function() {
                var row = $(this).closest('tr');
                var rowData = mainTable.row(row).data();

                // Add the removed row back to the modal table
                modalTable.row.add([
                    `<input type="hidden" value="${$(row).find('.product_id').val()}" class="product_id">
            <input type="hidden" value="${$(row).find('.price').val()}" class="price">
            <input class="form-check-input product_id" type="checkbox">`,
                    rowData[1].replace(/<input[^>]*>/, ''),
                    rowData[2].replace(/<input[^>]*>/, ''),
                    $(row).find('.unit').val(),
                    $(row).find('.modal').val(),
                    $(row).find('.varient').val(),
                    $(row).find('.price').val(),
                    $(row).find('.type').val(),
                    $(row).find('.category').val(),
                ]).draw(false);

                // Remove the row from the main table
                mainTable.row(row).remove().draw();

                // Recalculate the grand total
                recalculateGrandTotal();
                resetSerialNumbers(mainTable);
            });

            // Hide the modal
            $('#exampleModalCenter').modal('hide');
        }

        function recalculateGrandTotal() {
            var grand_total = 0;
            mainTable.$('.total').each(function() {
                var total_value = parseFloat($(this).val());
                if (!isNaN(total_value)) {
                    grand_total += total_value;
                }
            });
            $('#grandtotal').val(grand_total);
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

        $('#myForm').on('submit', function(){
            $('.card-body').find('.table').DataTable().page.len(-1).draw();
        });
    </script>
@endsection
