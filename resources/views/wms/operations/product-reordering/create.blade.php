@extends('layouts.app')
@section('title')
    PRODUCT REORDERING CREATE
@endsection
@section('content')
    <div class="card">
        <form method="post" action="{{ route('product_reordering.store') }}" enctype="multipart/form-data" id="myForm">
            @csrf
            <div class="card-body">
                <div class="row">
                    <div class="col-12 d-flex justify-content-end">
                        <button type="button" class="btn btn-primary" id="additem" data-bs-toggle="modal"
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
                                    <th>Type of Product</th>
                                    <th>Model</th>
                                    <th>Variance</th>
                                    <th>Critical Min</th>
                                    <th>Minimum Qty</th>
                                    <th>Maximum Qty</th>
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
                        <div class="d-flex gap-2 justify-content-between col-12">
                            <a type="button" class="btn btn-info" href="{{ route('product_reordering.index') }}">
                                <i class="bi bi-arrow-left"></i> Back
                            </a>
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
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
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
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
                                    <th>Type of Product</th>
                                    <th>Model</th>
                                    <th>Variance</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($products as $product)
                                    <tr>
                                        <td><input class="form-check-input" type="checkbox"><input type="hidden"
                                                value="{{ $product->id }}" class="product_id"></td>
                                        <td>{{ $product->part_no }}</td>
                                        <td>{{ $product->part_name }}</td>
                                        <td>{{ $product->units->name ?? '' }}</td>
                                        <td>{{ $product->type_of_products->type ?? '' }}</td>
                                        <td>{{ $product->model }}</td>
                                        <td>{{ $product->variance }}</td>
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
    <script>
        let modalTable;
        let mainTable;
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
                    rowData[5],
                    rowData[6],
                    `<input type="number" class="form-control critical_min" name="products[${mainTable.rows().count() + 1}][critical_min]"><input type="hidden" name="products[${mainTable.rows().count() + 1}][product_id]" class="product_id" value="${product_id}">`,
                    `<input type="number" class="form-control min_qty" name="products[${mainTable.rows().count() + 1}][min_qty]">`,
                    `<input type="number" class="form-control max_qty" name="products[${mainTable.rows().count() + 1}][max_qty]">`,
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
                    `<input class="form-check-input" type="checkbox"><input type="hidden" value="${$(row).find('.product_id').val()}" class="product_id">`,
                    rowData[1],
                    rowData[2],
                    rowData[3],
                    rowData[4],
                    rowData[5],
                    rowData[6]
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
            let checkboxes = document.querySelectorAll('#modalTable tbody input[type="checkbox"]');
            checkboxes.forEach(function(checkbox) {
                checkbox.checked = document.getElementById('selectAll').checked;
            });
        });

        $('#myForm').on('submit', function(){
            $('.card-body').find('.table').DataTable().page.len(-1).draw();
        });
    </script>
@endsection
