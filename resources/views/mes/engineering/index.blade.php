@extends('layouts.app')
@section('title')
    BOM LIST
@endsection
@section('button')
    <a type="button" class="btn btn-info" href="{{ route('bom.create') }}">
        <i class="bi bi-plus-square"></i> Add
    </a>
@endsection
@section('content')
<style>
    .table thead tr input {
           background: transparent;
           color: white;

       }
</style>
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered m-0 datatable">
                    <thead>
                        <tr>
                            <th>Sr No</th>
                            <th>Ref No.</th>
                            <th>Part No.</th>
                            <th>Part Name</th>
                            <th>Customer Name</th>
                            <th>Customer Product Code</th>
                            <th>Type Of Prodcut</th>
                            <th>Unit</th>
                            <th>Model</th>
                            <th>Variance</th>
                            <th>Part Weight (g)</th>
                            <th>Date</th>
                            <th>Description</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <thead>
                        <tr>
                            <th></th>
                            <th><input type="text" id="search-ref_no" placeholder="Search Ref No."></th>
                            <th><input type="text" id="search-part_no" placeholder="Search Part No"></th>
                            <th><input type="text" id="search-part_name" placeholder="Search Part Name"></th>
                            <th><input type="text" id="search-customers" placeholder="Search Customer Name"></th>
                            <th><input type="text" id="search-customer_product_code" placeholder="Search Customer Product Code"></th>
                            <th><input type="text" id="search-type_of_products" placeholder="Search Type Of Prodcut"></th>
                            <th><input type="text" id="search-units" placeholder="Search Unit"></th>
                            <th><input type="text" id="search-model" placeholder="Search Model"></th>
                            <th><input type="text" id="search-variance" placeholder="Search Variance"></th>
                            <th><input type="text" id="search-part_weight" placeholder="Search Part Weight (g)"></th>
                            <th><input type="text" id="search-created_date" placeholder="Search Date"></th>
                            <th></th>
                            <th><input type="text" id="search-status" placeholder="Search Status"></th>
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
        var data = "{{ route('bom.data') }}";
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
                        // Include server-side pagination parameters
                        d.ref_no = $('#search-ref_no').val();
                        d.part_no = $('#search-part_no').val();
                        d.part_name = $('#search-part_name').val();
                        d.customers = $('#search-customers').val();
                        d.customer_product_code = $('#search-customer_product_code').val();
                        d.type_of_products = $('#search-type_of_products').val();
                        d.units = $('#search-units').val();
                        d.model = $('#search-model').val();
                        d.variance = $('#search-variance').val();
                        d.part_weight = $('#search-part_weight').val();
                        d.created_date = $('#search-created_date').val();
                        d.status = $('#search-status').val();
                    }
                }, // URL to fetch data
                columns: [{
                    data :'DT_RowIndex',
                         name: 'DT_RowIndex',
                         orderable: false,
                         searchable: false
                    },{
                        data: 'ref_no',
                        name: 'ref_no',
                    },{
                        data: 'products.part_no',
                        name: 'products.part_no',
                    },{
                        data: 'products.part_name',
                        name: 'products.part_name',
                    },{
                        data: 'products.customers.name',
                        name: 'products.customers.name',
                    },{
                        data: 'products.customer_product_code',
                        name: 'products.customer_product_code',
                    },{
                        data: 'products.type_of_products.type',
                        name: 'products.type_of_products.type',
                    },{
                        data: 'products.units.name',
                        name: 'products.units.name',
                    },{
                        data: 'products.model',
                        name: 'products.model',
                    },{
                        data: 'products.variance',
                        name: 'products.variance',
                    },{
                        data: 'products.part_weight',
                        name: 'products.part_weight',
                    },{
                        data: 'created_date',
                        name: 'created_date',
                    },{
                        data: 'description',
                        name: 'description',
                        render: function(data, type, row) {
                            if (data != null) {

                            return '<div class="description">' +
                                '<p class="short-desc">' + data.substring(0, 25) + '</p>' +
                                '<p class="full-desc" style="display:none;">' + data + '</p>' +
                                '<a href="#" class="read-more">Read More</a>' +
                                '</div>';
                            }else{
                                return '';
                            }
                        }
                    },{
                        data: 'status',
                        name: 'status',
                    },{
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

            $(document).on('keyup','#dt-search-0', function() {
                console.log($(this).val())
            table.search($(this).val()).draw();
             });

        // $('.datatable thead tr').clone(true).appendTo('.datatable thead');
            $('.datatable thead tr:eq(1) th').each(function (i) {
                $('input', this).on('keyup change', function () {
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
