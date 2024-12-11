@extends('layouts.app')
@section('title')
    ORDER EDIT
@endsection
@section('content')
    <style>
        #mainTable input {
            width: 100px;
        }
    </style>
    <div class="card">
        <form method="post" action="{{ route('order.update', $order->id) }}" enctype="multipart/form-data" id="myForm">
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
                                value="{{ $order->date }}">
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label for="order_no" class="form-label">Customer Order No</label>
                            <input type="text" name="order_no" id="order_no" class="form-control"
                                value="{{ $order->order_no }}">
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label for="po_no" class="form-label">Customer PO No</label>
                            <input type="text" name="po_no" id="po_no" class="form-control"
                                value="{{ $order->po_no }}">
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label for="order_month" class="form-label">Order Month</label>
                            <input type="month" name="order_month" id="order_month" class="form-control"
                                value="{{ $order->order_month }}">
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select name="status" id="status" class="form-select">
                                <option value="">Select Status</option>
                                <option value="in-progress" @selected($order->status == 'in-progress')>In Progress</option>
                                <option value="complete" @selected($order->status == 'complete')>Complete</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-4 col-12">
                        <label for="inputGroupFileAddon03" class="form-label">Attachment</label>
                            <a href="{{ asset('/order-attachments/') }}/{{ $order->attachment }}"
                                class="btn btn-outline-secondary" target="_blank" type="button" id="inputGroupFileAddon03">
                                <i class="bi bi-file-text"></i>{{ $order->attachment }}
                            </a>
                        <div class="input-group mb-3">
                            <input type="file" class="form-control" id="inputGroupFile03"
                                aria-describedby="inputGroupFileAddon03" aria-label="Upload" name="attachment">
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
                                    <option value="{{ $customer->id }}" @selected($order->customer_id == $customer->id)>{{ $customer->name }}
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
                            data-bs-target="#exampleModalCenter" id="additem">ADD PRODUCTS</button>
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
                                @foreach ($order_details as $order_detail)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $order_detail->products->part_no }}</td>
                                        <td>{{ $order_detail->products->part_name }}</td>
                                        <td>{{ $order_detail->products->type_of_products->type ?? '' }}</td>
                                        <td>{{ $order_detail->products->categories->name ?? '' }}</td>
                                        <td>{{ $order_detail->products->variance }}</td>
                                        <td>{{ $order_detail->products->model }}</td>
                                        <td>{{ $order_detail->products->units->name ?? '' }}</td>
                                        <td><input type="hidden" name="products[{{ $loop->iteration }}][product_id]"
                                                class="product_id" value="{{ $order_detail->product_id }}">
                                            <input type="number" readonly class="form-control price"
                                                name="products[{{ $loop->iteration }}][price]"
                                                value="{{ $order_detail->price }}">
                                        </td>
                                        <td><input type="number" readonly class="form-control sst_percentage"
                                                name="products[{{ $loop->iteration }}][sst_percentage]"
                                                value="{{ $order_detail->sst_percentage }}">
                                        </td>
                                        <td><input type="number" class="form-control sst_value"
                                                name="products[{{ $loop->iteration }}][sst_value]"
                                                value="{{ $order_detail->sst_value }}" readonly></td>
                                        <td><input type="number" class="form-control"
                                                name="products[{{ $loop->iteration }}][firm_qty]"
                                                value="{{ $order_detail->firm_qty }}"></td>
                                        <td><input type="number" class="form-control"
                                                name="products[{{ $loop->iteration }}][n1_qty]"
                                                value="{{ $order_detail->n1_qty }}"></td>
                                        <td><input type="number" class="form-control"
                                                name="products[{{ $loop->iteration }}][n2_qty]"
                                                value="{{ $order_detail->n2_qty }}"></td>
                                        <td><input type="number" class="form-control"
                                                name="products[{{ $loop->iteration }}][n3_qty]"
                                                value="{{ $order_detail->n3_qty }}"></td>
                                        <td><button type="button" class="btn btn-danger btn-sm remove-product"><i
                                                    class="bi bi-trash"></i></button></td>
                                    </tr>
                                @endforeach
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
                            <button type="submit" class="btn btn-primary">Update</button>
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
                                                    {{ $product->categories->name ?? ''}}
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
        let firstAttempt = true;
        $(document).ready(function() {
            customer_change();
            let is_pp = @json($is_purchase_planning);
            if(is_pp == 1){
                $('.card-body input, select').prop('disabled', true);
                $('#status').prop('disabled', false);
            }
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

            add_product();
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

        $('#additem,.remove-product').click(function() {
            if (firstAttempt) {
                let mainTableProductIds = new Set();
                $("#mainTable tbody").find(".product_id").each(function() {
                    mainTableProductIds.add($(this).val());
                });
                $("#modalTable tbody").find(".product_id").each(function() {
                    if (mainTableProductIds.has($(this).val())) {
                        modalTable.row($(this).closest('tr')).remove().draw(false);
                    }
                });
                firstAttempt = false;
            }
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
                    rowData[1],
                    rowData[2],
                    rowData[6],
                    rowData[7],
                    rowData[5],
                    rowData[4],
                    rowData[3],
                    `<input type="hidden" name="products[${mainTable.rows().count() + 1}][product_id]" class="product_id" value="${productId}">
                    <input type="number" readonly class="form-control price" name="products[${mainTable.rows().count() + 1}][price]" value="${price}">`, // Price (Unit)
                    `<input type="number"  class="form-control sst_percentage" name="products[${mainTable.rows().count() + 1}][sst_percentage]" value="${sstPercentage}">`, // SST %
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
            $('#mainTable tbody').on('click', 'button.remove-product', function() {
                var row = $(this).closest('tr');
                var rowData = mainTable.row(row).data();

                // Add the removed row back to the modal table
                modalTable.row.add([
                    `<input type="hidden" value="${$(row).find('.sst_percentage').val()}" class="sst_percentage">
                    <input type="hidden" value="${$(row).find('.price').val()}" class="price">
                    <input class="form-check-input product_id" type="checkbox" value="${$(row).find('.product_id').val()}">`,
                    rowData[1],
                    rowData[2],
                    rowData[7],
                    rowData[6],
                    rowData[5],
                    rowData[3],
                    rowData[4]
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

        $('#myForm').on('submit', function(){
            $('.card-body').find('.table').DataTable().page.len(-1).draw();
        });
    </script>
@endsection
