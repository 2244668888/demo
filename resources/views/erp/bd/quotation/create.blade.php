@extends('layouts.app')
@section('title')
    QUOTATION CREATE
@endsection
@section('content')
<style>
    .tooltip-arrow{
   display: none !important;
   width: 0px !important;
   height: 0px !important;

}
 .tooltip-inner{
   display: none !important;
   width: 0px !important;
   height: 0px !important;
}

</style>
    <div class="card">
        <form method="post" action="{{ route('quotation.store') }}" id="myForm">
            @csrf
            <div class="card-body">
                <div class="row">
                    <h5>CUSTOMER DETAILS</h5>
                </div>
                <br>
                <div class="row">
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label for="customer_id" class="form-label">Customer Name</label>
                            <select name="customer_id" onchange="customer_change()" id="customer_id" class="form-select">
                                <option value="">Please Select</option>
                                @foreach ($customers as $customer)
                                    <option value="{{ $customer->id }}" @selected(old('customer_id') == $customer->id)>
                                        {{ $customer->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label for="address" class="form-label">Customer Address</label>
                            <textarea id="address" rows="1" class="form-control" readonly></textarea>
                        </div>
                    </div>

                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label for="attn" class="form-label">Attn</label>
                            <input type="text" readonly id="attn" class="form-control ">
                        </div>
                    </div>

                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label for="pic_department" class="form-label">Department</label>
                            <input type="text" readonly id="pic_department" class="form-control">
                        </div>
                    </div>

                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label for="cc" class="form-label">CC</label>
                            <input type="text" name="cc" id="cc" class="form-control"value="{{ old('cc') }}">
                        </div>
                    </div>

                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label for="department" class="form-label">Department</label>
                            <input type="text" name="department" id="department" class="form-control" value="{{ old('department') }}">
                        </div>
                    </div>
                </div>
                <br>
                <div class="row">
                    <h5>QUOTATION DETAILS</h5>
                </div>
                <br>
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
                            <label for="date" class="form-label">Date</label>
                            <input type="date" name="date" id="date" class="form-control" value="{{ \Carbon\Carbon::now()->format('Y-m-d') }}">
                        </div>
                    </div>
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
                    <div class="col-12 d-flex justify-content-between">
                        <h5>PART LIST</h5>
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
                                    <th>Remarks</th>
                                    <th>Price (RM)</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(old('products'))

                                @foreach (old('products') as $key => $quotation_detail)

                                <tr>
                                    <td>{{  $key }}</td>
                                    <td><input type="hidden" class="product_id"
                                            name="products[{{ $key }}][product_id]"
                                            value="{{ $quotation_detail['product_id'] }}"><input type="hidden" value="{{ $quotation_detail['part_no'] }}" name="products[{{ $key }}][part_no]">{{ $quotation_detail['part_no'] }}
                                    </td>
                                    <td><input type="hidden" value="{{ $quotation_detail['part_name'] }}" name="products[{{ $key }}][part_name]">{{ $quotation_detail['part_name'] ?? '' }}
                                    <input type="hidden" value="{{ $quotation_detail['category'] }}" name="products[{{ $key }}][category]" class="category">
                                    <input type="hidden" value="{{ $quotation_detail['variance'] }}" name="products[{{ $key }}][variance]" class="variance">
                                    <input type="hidden" value="{{ $quotation_detail['model'] }}" name="products[{{ $key }}][model]" class="model">
                                    <input type="hidden" value="{{ $quotation_detail['unit'] }}" name="products[{{ $key }}][unit]" class="unit"></td>
                                    <input type="hidden" value="{{ $quotation_detail['remarks'] }}" name="products[{{ $key }}][remarks]" class="remarks">
                                    <input type="hidden" value="{{ $quotation_detail['type_of_product'] }}" name="products[{{ $key }}][type_of_product]" class="type_of_product">
                                </td>
                                    @if ($quotation_detail['remarks'] != '')
                                    <td><button type="button" class="btn btn-success btn-sm add-remarks">Edit</button></td>
                                    @else
                                    <td><button type="button" class="btn btn-danger btn-sm add-remarks">Add</button></td>
                                    @endif
                                    <td><input type="number" readonly class="form-control price"
                                            name="products[{{ $key }}][price]"
                                            value="{{ $quotation_detail['price'] }}"></td>
                                    <td><button type="button" class="btn btn-danger btn-sm remove-product"><i class="bi bi-trash"></i></button></td>
                                </tr>
                            @endforeach
                            @endif
                            </tbody>
                        </table>
                    </div>
                </div>
                <br>
                <div class="col-lg-12 col-sm-12 col-12">
                    <div class="mb-3">
                        <label for="term_conditions" class="form-label">TERM AND CONDITION</label>
                        <textarea placeholder="Enter Here" id="term_conditions" rows="4" class="form-control" name="term_conditions">{!! old('term_conditions') !!}</textarea>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="row">
                        <div class="d-flex gap-2 justify-content-between col-12">
                            <a type="button" class="btn btn-info" href="{{ route('quotation.index') }}">
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
        <div class="modal-dialog modal-dialog-centered modal-lg">
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
                                            $sale_price = App\Models\SalePrice::where('product_id', $product->id)
                                                ->where('status', 'verified')
                                                ->orderBy('created_at', 'DESC')
                                                ->first();
                                        @endphp
                                        <td>
                                            <input type="hidden" value="{{ $sst_percentage->sst_percentage ?? 0 }}"
                                                class="sst_percentage">
                                            <input type="hidden" value="{{ $sale_price->price ?? 0 }}" class="price">
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
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="saveRemarks">Save</button>
                </div>
            </div>
        </div>
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
            var customersId = $("#customer_id").val();
            var customer = customers.find(p => p.id == customersId);
            if (customer) {
                $('#address').val(customer.address);
                $('#attn').val(customer.pic_name);
                $('#pic_department').val(customer.pic_department);
            } else {
                $('#address').val('');
                $('#attn').val('');
                $('#pic_department').val('');
            }
        };

        $(document).on('click', '.add-remarks', function() {
                selectedRow = mainTable.row($(this).closest('tr'));
                $('#remarksText').val($(this).closest('tr').find('.remarks').val());
                $('#remarksModal').modal('show');
            });

            $(document).on('click', '.remove-product', function() {
                var row = $(this).closest('tr');
                var rowData = mainTable.row(row).data();
                var unit = $(this).closest('tr').find('.unit').val();
                var model = $(this).closest('tr').find('.model').val();
                var variance = $(this).closest('tr').find('.variance').val();
                var type_of_product = $(this).closest('tr').find('.type_of_product').val();
                var category = $(this).closest('tr').find('.category').val();

                // Add the removed row back to the modal table
                modalTable.row.add([
                    `<input type="hidden" value="${$(row).find('.price').val()}" class="price">
                    <input class="form-check-input product_id" type="checkbox" value="${$(row).find('.product_id').val()}">`,
                    rowData[1].replace(/<input[^>]*>/, ''),
                    rowData[2].replace(/<input[^>]*>/, ''),
                    unit,
                    model,
                    variance,
                    type_of_product,
                    category
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

                // Add the row data to the main table
                mainTable.row.add([
                    mainTable.rows().count() + 1,
                    `${rowData[1]}<input type="hidden" name="products[${mainTable.rows().count() + 1}][part_no]" class="part_no" value="${rowData[1]}">`,
                    `${rowData[2]}<input type="hidden" name="products[${mainTable.rows().count() + 1}][part_name]" class="part_name" value="${rowData[2]}">`,
                    `<button type="button" class="btn btn-danger btn-sm add-remarks">Add</button>`,
                    `<input type="hidden" name="products[${mainTable.rows().count() + 1}][product_id]" class="product_id" value="${productId}">
                    <input type="hidden" class="unit" value="${rowData[3]}" name="products[${mainTable.rows().count() + 1}][unit]">
                    <input type="hidden" class="model" value="${rowData[4]}" name="products[${mainTable.rows().count() + 1}][model]">
                    <input type="hidden" class="variance" value="${rowData[5]}" name="products[${mainTable.rows().count() + 1}][variance]">
                    <input type="hidden" class="type_of_product" value="${rowData[6]}" name="products[${mainTable.rows().count() + 1}][type_of_product]">
                    <input type="hidden" class="category" value="${rowData[7]}" name="products[${mainTable.rows().count() + 1}][category]">
                    <input type="hidden" class="remarks" name="products[${mainTable.rows().count() + 1}][remarks]" value="">
                    <input type="number" class="form-control price" name="products[${mainTable.rows().count() + 1}][price]" value="${price}">`,
                    `<button type="button" class="btn btn-danger btn-sm remove-product"><i class="bi bi-trash"></i></button>`
                ]).draw(false);

                // Remove the row from the modal table
                modalTable.row(row).remove().draw();
                resetSerialNumbers()
            });

            // Uncheck all checkboxes
            $('#modalTable input:checked').prop('checked', false);

            // Add event listener to remove buttons


            // Hide the modal
            $('#exampleModalCenter').modal('hide');

            let selectedRow;




        }

        $(document).on('click','#saveRemarks', function() {
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
