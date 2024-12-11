@extends('layouts.app')
@section('title')
    ORDER CREATE
@endsection
@section('content')
    <style>
        #mainTable input {
            width: 100px;
        }
    </style>
    <div class="card">
        <form method="post" action="{{ route('order.store') }}" enctype="multipart/form-data" id="myForm">
            @csrf
            <div class="card-body">
                <div class="row">
                    <h5>GENERAL INFORMATION</h5>
                </div>
                <br>
                <div class="row">
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label for="created_by" class="form-label">Created By</label>
                            <input type="text" readonly name="created_by" id="created_by" class="form-control"
                                value="{{ Auth::user()->user_name }}">
                        </div>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label for="date" class="form-label">Created Date</label>
                            <input type="text" readonly name="date" id="date" class="form-control"
                                value="{{ date('d-m-Y') }}">
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label for="order_no" class="form-label">Customer Order No</label>
                            <input type="text" name="order_no" id="order_no" class="form-control"
                                value="{{ old('order_no') }}">
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label for="po_no" class="form-label">Customer PO No</label>
                            <input type="text" name="po_no" id="po_no" class="form-control"
                                value="{{ old('po_no') }}">
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label for="order_month" class="form-label">Order Month</label>
                            <input type="month" name="order_month" id="order_month" class="form-control"
                                value="{{ old('order_month') }}">
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select name="status" id="status" class="form-select" style="width: 100% !important;">
                                <option value="" selected>Select Status</option>
                                <option value="in-progress" @selected(old('status') == 'in-progress')>In Progress</option>
                                <option value="complete" @selected(old('status') == 'complete')>Complete</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label for="inputGroupFile02" class="form-label">Attachment</label>
                            <input type="file" class="form-control" name="attachment" id="inputGroupFile02">
                        </div>
                    </div>
                </div>
                <br>
                <div class="row">
                    <h5>CUSTOMER DETAIL</h5>
                </div>
                <br>
                <div class="row">
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label for="customer_id" class="form-label">Customer Name</label>
                            <select name="customer_id" onchange="customer_change()" id="customer_id" class="form-select">
                                <option value="" selected disabled>Please Select</option>
                                @foreach ($customers as $customer)
                                    <option value="{{ $customer->id }}" @selected(old('customer_id') == $customer->id)>{{ $customer->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label for="pic_name" class="form-label">PIC Name</label>
                            <input type="text" readonly name="" id="pic_name" class="form-control ">
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label for="pic_email" class="form-label">PIC Email</label>
                            <input type="text" readonly name="" id="pic_email" class="form-control ">
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label for="pic_phone" class="form-label">PIC Phone No. (Work/Mobile)</label>
                            <input type="text" readonly name="" id="pic_phone" class="form-control ">
                        </div>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-12 d-flex justify-content-between">
                        <h5>PRODUCT DETAIL</h5>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal"
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
                                    <th>Type of Product</th>
                                    <th>Category</th>
                                    <th>Variance</th>
                                    <th>Model</th>
                                    <th>Unit</th>
                                    <th>Price(Unit)</th>
                                    <th>SST %</th>
                                    <th>SST Value</th>
                                    <th>Firm 1 Month Qty</th>
                                    <th>N+1 Forecast Month Qty</th>
                                    <th>N+2 Forecast Month Qty</th>
                                    <th>N+3 Forecast Month Qty</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(old('products'))

                                @foreach (old('products') as $key => $order_detail)

                                <tr>
                                    <td>{{  $key }}</td>
                                    <td><input type="hidden" class="product_id"
                                            name="products[{{ $key }}][product_id]"
                                            value="{{ $order_detail['product_id'] }}"><input type="hidden" value="{{ $order_detail['part_no'] }}" name="products[{{ $key }}][part_no]">{{ $order_detail['part_no'] }}
                                    </td>
                                    <td><input type="hidden" value="{{ $order_detail['part_name'] }}" name="products[{{ $key }}][part_name]">{{ $order_detail['part_name'] ?? '' }}</td>
                                    <td><input type="hidden" value="{{ $order_detail['type_of_product'] }}" name="products[{{ $key }}][type_of_product]">{{ $order_detail['type_of_product'] ?? '' }}</td>
                                    <td><input type="hidden" value="{{ $order_detail['category'] }}" name="products[{{ $key }}][category]">{{ $order_detail['category'] ?? '' }}</td>
                                    <td><input type="hidden" value="{{ $order_detail['variance'] }}" name="products[{{ $key }}][variance]">{{ $order_detail['variance'] ?? '' }}</td>
                                    <td><input type="hidden" value="{{ $order_detail['model'] }}" name="products[{{ $key }}][model]">{{ $order_detail['model'] ?? '' }}</td>
                                    <td><input type="hidden" value="{{ $order_detail['unit'] }}" name="products[{{ $key }}][unit]">{{ $order_detail['unit'] ?? '' }}</td>
                                    <td><input type="number" readonly class="form-control price"
                                            name="products[{{ $key }}][price]"
                                            value="{{ $order_detail['price'] }}"></td>
                                    <td><input type="number" readonly class="form-control sst_percentage"
                                            name="products[{{ $key }}][sst_percentage]"
                                            value="{{ $order_detail['sst_percentage'] }}"></td>
                                    <td><input type="number" readonly class="form-control sst_value"
                                            name="products[{{ $key }}][sst_value]"
                                            value="{{ $order_detail['sst_value'] }}"></td>
                                    <td><input type="number"  class="form-control firm_qty"
                                            name="products[{{ $key }}][firm_qty]"
                                            value="{{ $order_detail['firm_qty'] }}"></td>
                                    <td><input type="number" class="form-control n1_qty"
                                            name="products[{{ $key }}][n1_qty]"
                                            value="{{ $order_detail['n1_qty']  }}"></td>
                                    <td><input type="number"  class="form-control n2_qty"
                                            name="products[{{ $key }}][n2_qty]"
                                            value="{{ $order_detail['n2_qty']  }}"></td>
                                    <td><input type="number"  class="form-control n3_qty"
                                            name="products[{{ $key }}][n3_qty]"
                                            value="{{ $order_detail['n3_qty']  }}"></td>
                                    <td><button type="button" class="btn btn-danger btn-sm remove-product"><i class="bi bi-trash"></i></button></td>
                                </tr>
                            @endforeach
                            @endif
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="row">
                        <div class="d-flex gap-2 justify-content-between col-12">
                            <a type="button" class="btn btn-info" href="{{ route('order.index') }}">
                                <i class="bi bi-arrow-left"></i> Back
                            </a>
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </div>
                </div>
            </div>
            {{-- PRODUCTS MODAL --}}
            <div class="modal fade" id="exampleModalCenter" tabindex="-1" aria-labelledby="exampleModalCenterTitle"
                aria-modal="true" role="dialog">
                <div class="modal-dialog modal-dialog-centered modal-xl">
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
                                            <th>
                                                <input type="checkbox" id="selectAll" style="width: 22px; height: 22px;">
                                            </th>
                                            <th>Part No</th>
                                            <th>Part Name</th>
                                            <th>Unit</th>
                                            <th>Model</th>
                                            <th>Variance</th>
                                            <th>Type of Product</th>
                                            <th>Category</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($products as $product)
                                            <tr>
                                                @php
                                                    $sale_price = App\Models\SalePrice::where(
                                                        'product_id',
                                                        $product->id,
                                                    )
                                                        ->orderBy('created_at', 'DESC')
                                                        ->first();
                                                @endphp
                                                <td>
                                                    <input type="hidden"
                                                        value="{{ $sst_percentage->sst_percentage ?? 0 }}"
                                                        class="sst_percentage">
                                                    <input type="hidden" value="{{ $sale_price->price ?? 0 }}"
                                                        class="price">
                                                    <input class="form-check-input product_id" type="checkbox"
                                                        id="inlineCheckbox1" value="{{ $product->id }}">
                                                </td>
                                                <td>
                                                    {{ $product->part_no }}
                                                </td>
                                                <td>
                                                    {{ $product->part_name }}
                                                </td>
                                                <td>
                                                    {{ $product->units->name ?? '' }}
                                                </td>
                                                <td>
                                                    {{ $product->model }}
                                                </td>
                                                <td>
                                                    {{ $product->variance }}
                                                </td>
                                                <td>
                                                    {{ $product->type_of_products->type ?? '' }}
                                                </td>
                                                <td>
                                                    {{ $product->categories->name ?? '' }}
                                                </td>
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
        let modalTable;
        let mainTable;
        $(document).ready(function() {

            customer_change();
            modalTable = $('#modalTable').DataTable({
                "columnDefs": [
                    {
                        "targets": 0,
                        "orderable": false
                    }
                ]
            });
            mainTable = $('#mainTable').DataTable();
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

        var customers = {!! json_encode($customers) !!};

        function customer_change() {
            var customerId = $("#customer_id").val();
            var customer = customers.find(p => p.id == customerId);
            if (customer) {
                $('#pic_name').val(customer.pic_name);
                $('#pic_email').val(customer.pic_email);
                $('#pic_phone').val(`${customer.pic_phone_work}/${customer.pic_phone_mobile}`);
            } else {
                $('#pic_name').val('');
                $('#pic_email').val('');
                $('#pic_phone').val('');
            }
        };



        $(document).on('click', '.remove-product', function() {
                var row = $(this).closest('tr');
                var rowData = mainTable.row(row).data();

                // Add the removed row back to the modal table
                modalTable.row.add([
                    `<input type="hidden" value="${$(row).find('.sst_percentage').val()}" class="sst_percentage">
                    <input type="hidden" value="${$(row).find('.price').val()}" class="price">
                    <input class="form-check-input product_id" type="checkbox" value="${$(row).find('.product_id').val()}">`,
                    rowData[1].replace(/<input[^>]*>/, ''),
                    rowData[2].replace(/<input[^>]*>/, ''),
                    rowData[7].replace(/<input[^>]*>/, ''),
                    rowData[6].replace(/<input[^>]*>/, ''),
                    rowData[5].replace(/<input[^>]*>/, ''),
                    rowData[3].replace(/<input[^>]*>/, ''),
                    rowData[4].replace(/<input[^>]*>/, '')
                ]).draw(false);

                // Remove the row from the main table
                mainTable.row(row).remove().draw();
                resetSerialNumbers(mainTable);
            });


        function add_product() {
            modalTable.$('input:checked').each(function() {
                var row = $(this).closest('tr');
                var rowData = modalTable.row(row).data();
                var productId = $(this).val();
                var price = $(row).find('.price').val();
                var sstPercentage = $(row).find('.sst_percentage').val();
                var sstValue = (price * sstPercentage) / 100;

                // Add the row data to the main table
                mainTable.row.add([
                    mainTable.rows().count() + 1,
                    `${rowData[1]}<input type="hidden" name="products[${mainTable.rows().count() + 1}][part_no]" class="part_no" value="${rowData[1]}">`,
                    `${rowData[2]}<input type="hidden" name="products[${mainTable.rows().count() + 1}][part_name]" class="part_name" value="${rowData[2]}">`,
                    `${rowData[6]}<input type="hidden" name="products[${mainTable.rows().count() + 1}][type_of_product]" class="type_of_product" value="${rowData[6]}">`,
                    `${rowData[7]}<input type="hidden" name="products[${mainTable.rows().count() + 1}][category]" class="category" value="${rowData[7]}">`,
                    `${rowData[5]}<input type="hidden" name="products[${mainTable.rows().count() + 1}][variance]" class="variance" value="${rowData[5]}">`,
                    `${rowData[4]}<input type="hidden" name="products[${mainTable.rows().count() + 1}][model]" class="model" value="${rowData[4]}">`,
                    `${rowData[3]}<input type="hidden" name="products[${mainTable.rows().count() + 1}][unit]" class="unit" value="${rowData[3] || ''}">`,
                    `<input type="hidden" name="products[${mainTable.rows().count() + 1}][product_id]" class="product_id" value="${productId}">
                    <input type="number" readonly class="form-control price" name="products[${mainTable.rows().count() + 1}][price]" value="${price}">`, // Price (Unit)
                    `<input type="number" class="form-control sst_percentage" name="products[${mainTable.rows().count() + 1}][sst_percentage]" value="${sstPercentage}">`, // SST %
                    `<input type="number" class="form-control sst_value" name="products[${mainTable.rows().count() + 1}][sst_value]" value="${sstValue.toFixed(2)}" readonly>`, // SST Value
                    `<input type="number" class="form-control" name="products[${mainTable.rows().count() + 1}][firm_qty]">`, // Firm 1 Month Qty
                    `<input type="number" class="form-control" name="products[${mainTable.rows().count() + 1}][n1_qty]">`, // N+1 Forecast Month Qty
                    `<input type="number" class="form-control" name="products[${mainTable.rows().count() + 1}][n2_qty]">`, // N+2 Forecast Month Qty
                    `<input type="number" class="form-control" name="products[${mainTable.rows().count() + 1}][n3_qty]">`, // N+3 Forecast Month Qty
                    `<button type="button" class="btn btn-danger btn-sm remove-product"><i class="bi bi-trash"></i></button>`
                ]).draw(false);

                // Remove the row from the modal table
                modalTable.row(row).remove().draw();
            });

            // Uncheck all checkboxes
            $('#modalTable input:checked').prop('checked', false);

            // Add event listener to remove buttons





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

        $('#myForm').on('submit', function(){
            $('.card-body').find('.table').DataTable().page.len(-1).draw();
        });
    </script>
@endsection
