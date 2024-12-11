@extends('layouts.app')
@section('title')
    PURCHASE REQUISITION CREATE
@endsection
@section('content')
    <div class="card">
        <form method="post" action="{{ route('purchase_requisition.store') }}" enctype="multipart/form-data" id="myForm">
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
                            <input type="text" readonly name="pr_no" value="{{ $pr_no }}" readonly
                                id="part_name" class="form-control">
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
                                <option value="">Please Select</option>
                                <option value="Priority">Priority</option>
                                <option value="Urgent">Urgent</option>
                                <option value="Not Urgent">Not Urgent</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label for="" class="form-label">Date :</label>
                            <input type="date" value="{{ date('Y-m-d') }}" name="date" class="form-control">
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label for="" class="form-label">Require Date :</label>
                            <input type="date" name="require_date" class="form-control">
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label for="" class="form-label">Category:</label>
                            <select name="category" id="categorySelect" onchange="handleCategoryChange()"
                                class="form-select">
                                <option value="Printing & Stationary" title="Printing & Stationary">Printing & Stationary</option>
                                <option value="Direct Item" title="Direct Item">Direct Item</option>
                                <option value="Urgent">Indirect Item</option>
                                <option value="Asset">Asset</option>
                                <option value="Others">Others (please Specify)</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-lg-3 col-sm-4 col-12 d-none" id="other_div">
                        <div class="mb-3">
                            <label for="" class="form-label">Others:</label>
                            <input type="text" id="category_other" disabled name="category_other" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="text-center">
                            <button type="button" class="btn btn-primary float-end" data-bs-toggle="modal"
                                data-bs-target="#exampleModalCenter">ADD PRODUCTS</button>
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


                                            @if(old('products'))

                                @foreach (old('products') as $key => $purchase_detail)

                                <tr>
                                    <td>{{  $key }}</td>
                                    <td><input type="hidden" class="product_id"
                                            name="products[{{ $key }}][product_id]"
                                            value="{{ $purchase_detail['product_id'] }}"><input type="hidden" value="{{ $purchase_detail['part_no'] }}" name="products[{{ $key }}][part_no]">{{ $purchase_detail['part_no'] }}
                                    </td>
                                    <td><input type="hidden" value="{{ $purchase_detail['part_name'] }}" name="products[{{ $key }}][part_name]">{{ $purchase_detail['part_name'] ?? '' }}
                                    <input type="hidden" value="{{ $purchase_detail['unit'] }}" name="products[{{ $key }}][unit]" class="unit">
                                    <input type="hidden" value="{{ $purchase_detail['modal'] }}" name="products[{{ $key }}][modal]" class="modal">
                                    <input type="hidden" value="{{ $purchase_detail['varient'] }}" name="products[{{ $key }}][varient]" class="varient">
                                    <input type="hidden" value="{{ $purchase_detail['type'] }}" name="products[{{ $key }}][type]" class="type"></td>
                                    <input type="hidden" value="{{ $purchase_detail['category'] }}" name="products[{{ $key }}][category]" class="category">
                                </td>
                                    <td><input type="number" readonly class="form-control price" readonly
                                            name="products[{{ $key }}][price]"
                                            value="{{ $purchase_detail['price'] }}"></td>
                                            <td><input type="number" class="form-control qty"
                                                name="products[{{ $key }}][qty]"
                                                value="0"></td>
                                            <td><input type="number" readonly class="form-control total" readonly
                                                    name="products[{{ $key }}][total]"
                                                    value="{{ $purchase_detail['total'] }}" readonly></td>
                                    <td><input type="text" class="form-control purpose"
                                            name="products[{{ $key }}][purpose]"
                                            ></td>
                                    <td><button type="button" class="btn btn-danger btn-sm remove-product"><i class="bi bi-trash"></i></button></td>
                                </tr>
                            @endforeach
                            @endif

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label for="" class="form-label">Grand Total (RM)</label>
                            <input type="text" id="grandtotal" readonly name="total" class="form-control">
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label for="" class="form-label">Attachment</label>
                            <input type="file" name="attachment" class="form-control">
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label for="" class="form-label">Remarks</label>
                            <textarea class="form-control" name="remarks" id="supplier_address" rows="1"></textarea>
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
                        <button type="submit" class="btn btn-primary">Submit</button>
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
                                            <th>Variance</th>
                                            <th>Price (RM)</th>
                                            <th>Type of Product</th>
                                            <th>Category</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($products as $product)
                                            <tr>
                                                @php
                                                    $purchase_price = $purchase_prices->where('product_id', $product->id)
                                                                                    ->sortByDesc('created_at')
                                                                                    ->first();
                                                @endphp
                                            
                                            
                                                <input type="hidden" class="product_id" value="{{ $product->id }}">
                                                <input type="hidden" class="price"
                                                    value="{{ $purchase_price ? $purchase_price->price : 0 }}">
                                                <td><input class="form-check-input product_id" type="checkbox"
                                                    id="inlineCheckbox1" value="{{ $product->id }}"></td>
                                                <td>
                                                    {{ $product->part_no }}
                                                </td>
                                                <td>{{ $product->part_name }}</td>
                                                <td>{{ $product->units->name ?? '' }}</td>
                                                <td>{{ $product->model }}</td>
                                                <td>{{ $product->variance }}</td>

                                                <td>{{ $purchase_price ? $purchase_price->price : 0 }}</td>
                                                <td>{{ $product->type_of_products->type ?? '' }}</td>
                                                <td>{{ $product->categories->name ?? '' }}</td>
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

        $(document).ready(function() {
            $(document).on('mouseenter', '.select2-selection__rendered', function () {
            $('.select2-selection__rendered').removeAttr('data-bs-original-title');
        });
            modalTable = $('#modalTable').DataTable({
                "columnDefs": [{
                    "targets": 0,
                    "orderable": false
                }]
            });
            mainTable = $('#mainTable').DataTable();
            total = 0;
            grand_total = 0;


            $('#modalTable tr').each(function() {
                var modalProductId = $(this).find('.product_id').val();
                var existsInMainTable = $('#mainTable tr').filter(function() {
                    if(modalProductId == undefined){
                        return
                    }
                    return $(this).find('.product_id').val() == modalProductId;
                }).length > 0;
                if (existsInMainTable) {
                    modalTable.row($(this)).remove().draw();
                }
            });


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
                   `${rowData[1]}<input type="hidden" name="products[${mainTable.rows().count() + 1}][part_no]" class="part_no" value="${rowData[1]}"> <input type="hidden" name="products[${mainTable.rows().count() + 1}][category]" class="category" value="${rowData[8] || ''}"> <input type="hidden" name="products[${mainTable.rows().count() + 1}][product_id]" class="product_id" value="${product_id}">`,
                    `${rowData[2]}<input type="hidden" name="products[${mainTable.rows().count() + 1}][part_name]" class="part_name" value="${rowData[2]}"> <input type="hidden" name="products[${mainTable.rows().count() + 1}][type]" class="type" value="${rowData[7]}">
                    <input type="hidden" name="products[${mainTable.rows().count() + 1}][unit]" class="unit" value="${rowData[3]}"> <input type="hidden" name="products[${mainTable.rows().count() + 1}][modal]" class="modal" value="${rowData[4]}"> <input type="hidden" name="products[${mainTable.rows().count() + 1}][varient]" class="varient" value="${rowData[5]}">`,
                    `<input type="number" readonly class="form-control price" name="products[${mainTable.rows().count() + 1}][price]" value="${price}">`,
                    `<input type="number" class="form-control qty" name="products[${mainTable.rows().count() + 1}][qty]" value="0">`,
                    `<input type="number" class="form-control total" readonly name="products[${mainTable.rows().count() + 1}][total]">`,
                    `<input type="text" class="form-control" name="products[${mainTable.rows().count() + 1}][purpose]">`,
                    `<button type="button" class="btn btn-danger btn-sm remove-product"><i class="bi bi-trash"></i></button>`
                ]).draw(false);

                // Remove the row from the modal table
                modalTable.row(row).remove().draw();
                $('.qty').trigger('input');
                // Recalculate the grand total
                recalculateGrandTotal();
            });

            // Uncheck all checkboxes
            $('#modalTable input:checked').prop('checked', false);

            // Add event listener to remove buttons


            // Hide the modal
            $('#exampleModalCenter').modal('hide');
        }

        $(document).on('click', '.remove-product', function() {
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
        $(document).ready(function() {
            console.log("test");

            // "Select All" checkbox functionality
            document.getElementById('selectAll').addEventListener('change', function() {
                const checkboxes = document.querySelectorAll('.product_id');
                checkboxes.forEach(checkbox => {
                    checkbox.checked = this.checked;
                });
            });
        });

        $('#myForm').on('submit', function(){
            $('.card-body').find('.table').DataTable().page.len(-1).draw();
        });
    </script>
@endsection
