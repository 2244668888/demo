@extends('layouts.app')
@section('title')
PRODUCT LIST
@endsection
@section('button')
<a type="button" class="btn btn-info" href="{{ route('product.create') }}">
    <i class="bi bi-plus-square"></i> Add
</a>
@endsection
@section('content')
<style>
    .table thead tr input {
        background: transparent;
        color: rgb(117, 117, 117);

    }
</style>
<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered m-0 datatable">
                <thead>
                    <tr>
                        <th>Sr #</th>
                        <th>Part No</th>
                        <th>Part Name</th>
                        <th>Type of Product</th>
                        <th>Model</th>
                        <th>Variance</th>
                        <th>Category</th>
                        <th>Customer Name</th>
                        <th>Supplier Name</th>
                        <th>Customer Product Code</th>
                        <th>Supplier Product Code</th>
                        <th>Part Weight</th>
                        <th>Description</th>
                        <th>Unit</th>
                        <th>MOQ</th>
                        <th>Standard Packing</th>
                        <th>Amortization Qty</th>
                        <th>Delivered Qty</th>
                        <th>Balanced Amortization to Complete</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <thead>

                    <tr>
                        <th></th>
                        <th><input type="text" id="search-part_no" placeholder="Search Part No"></th>
                        <th><input type="text" id="search-part_name" placeholder="Search Part Name"></th>
                        <th><input type="text" id="search-type_of_product" placeholder="Search Type of Product"></th>
                        <th><input type="text" id="search-model" placeholder="Search Model"></th>
                        <th><input type="text" id="search-variance" placeholder="Search Variance"></th>
                        <th><input type="text" id="search-category" placeholder="Search Category"></th>
                        <th><input type="text" id="search-customer_name" placeholder="Search Customer Name"></th>
                        <th><input type="text" id="search-supplier_name" placeholder="Search Supplier Name"></th>
                        <th><input type="text" id="search-customer_product_code" placeholder="Search Customer Product Code"></th>
                        <th><input type="text" id="search-supplier_product_code" placeholder="Search Supplier Product Code"></th>
                        <th><input type="text" id="search-part_weight" placeholder="Search Part Weight"></th>
                        <th><input type="text" id="search-description" placeholder="Search Description"></th>
                        <th><input type="text" id="search-unit" placeholder="Search Unit"></th>
                        <th><input type="text" id="search-moq" placeholder="Search MOQ"></th>
                        <th><input type="text" id="search-standard_packaging" placeholder="Search Standard Packaging"></th>
                        <th><input type="text" id="search-amortization_qty" placeholder="Search Amortization Qty"></th>
                        <th><input type="text" id="search-delivered_qty" placeholder="Search Delivered Qty"></th>
                        <th><input type="text" id="search-balance_amortization" placeholder="Search Balanced Amortization to Complete"></th>
                        <th></th>
                    </tr>
                </thead>

                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    var data = "{{ route('product.data') }}";
    $(document).ready(function() {
        let bool = true;
        var table = $('.datatable').DataTable({
            perPageSelect: [5, 10, 15, ["All", -1]],
            processing: true,
            serverSide: true,
            language: {
                processing: 'Processing' // Custom processing text
            },
            ajax: {
                url: data, // URL for your server-side data endpoint
                type: 'GET',
                data: function(d) {
                    d.part_no = $('#search-part_no').val();
                    d.part_name = $('#search-part_name').val();
                    d.model = $('#search-model').val();
                    d.variance = $('#search-variance').val();
                    d.category = $('#search-category').val();
                    d.customer_product_code = $('#search-customer_product_code').val();
                    d.supplier_product_code = $('#search-supplier_product_code').val();
                    d.part_weight = $('#search-part_weight').val();
                    d.type_of_product = $('#search-type_of_product').val();
                    d.supplier_name = $('#search-supplier_name').val();
                    d.customer_name = $('#search-customer_name').val();
                    d.description = $('#search-description').val();
                    d.unit = $('#search-unit').val();
                    d.moq = $('#search-moq').val();
                    d.standard_packaging = $('#search-standard_packaging').val();
                    d.amortization_qty = $('#search-amortization_qty').val();
                    d.delivered_qty = $('#search-delivered_qty').val();
                    d.balance_amortization = $('#search-balance_amortization').val();
                }
            }, // URL to fetch data
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    orderable: false,
                    searchable: false
                }, {
                    data: 'part_no',
                    name: 'part_no',
                },
                {
                    data: 'part_name',
                    name: 'part_name',
                },
                {
                    data: 'type_of_product',
                    name: 'type_of_product',
                }, {
                    data: 'model',
                    name: 'model',
                }, {
                    data: 'variance',
                    name: 'variance',
                },
                {
                    data: 'category',
                    name: 'category',
                },
                {
                    data: 'customer_name',
                    name: 'customer_name',
                },
                {
                    data: 'supplier_name',
                    name: 'supplier_name',
                },
                {
                    data: 'customer_product_code',
                    name: 'customer_product_code',
                },
                {
                    data: 'supplier_product_code',
                    name: 'supplier_product_code',
                },
                {
                    data: 'part_weight',
                    name: 'part_weight',
                },
                {
                    data: 'description',
                    name: 'description',
                    render: function(data, type, row) {
                        if (data != null) {

                            return '<div class="description">' +
                                '<p class="short-desc">' + data.substring(0, 100) + '</p>' +
                                '<p class="full-desc" style="display:none;">' + data + '</p>' +
                                '<a href="#" class="read-more">Read More</a>' +
                                '</div>';
                        } else {
                            return '';
                        }
                    }

                },
                {
                    data: 'unit',
                    name: 'unit',
                },
                {
                    data: 'moq',
                    name: 'moq',
                },
                {
                    data: 'standard_packaging',
                    name: 'standard_packaging',
                },
                {
                    data: 'amortization_qty',
                    name: 'amortization_qty',
                },
                {
                    data: 'delivered_qty',
                    name: 'delivered_qty',
                },
                {
                    data: 'balance_amortization',
                    name: 'balance_amortization',
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                },
            ],
            paging: true
            // Other DataTables options go here
        });
        bool = false;

        $(document).on('keyup', '#dt-search-0', function() {
            console.log($(this).val())
            table.search($(this).val()).draw();
        });

        // $('.datatable thead tr').clone(true).appendTo('.datatable thead');
        $('.datatable thead tr:eq(1) th').each(function(i) {
            $('input', this).on('keyup change', function() {
                if (table.column(i).search() !== this.value) {
                    table
                        .column(i)
                        .search(this.value)
                        .draw();
                }
            });
        });

    });
</script>
@endsection