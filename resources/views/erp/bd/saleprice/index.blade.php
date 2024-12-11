@extends('layouts.app')
@section('title')
    SALE PRICE LIST
@endsection
@section('button')
    <a type="button" class="btn btn-info" href="{{ route('sale_price.create') }}">
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
                            <th>SR No</th>
                            <th>Part No</th>
                            <th>Part Name</th>
                            <th>Model</th>
                            <th>Variance</th>
                            <th>Unit</th>
                            <th>Price/Unit(RM)</th>
                            <th>Effective Date</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <thead>
                        <tr>
                            <th></th>
                            <th><input type="text" id="search-part_no" placeholder="Search Part No"></th>
                            <th><input type="text" id="search-part_name" placeholder="Search Part Name"></th>
                            <th><input type="text" id="search-model" placeholder="Search Model"></th>
                            <th><input type="text" id="search-variance" placeholder="Search Variance"></th>
                            <th><input type="text" id="search-unit" placeholder="Search Unit"></th>
                            <th><input type="text" id="search-price" placeholder="Search Price/Unit(RM)"></th>
                            <th><input type="text" id="search-date" placeholder="Search Effective Date"></th>
                            <th><input type="text" id="search-status" placeholder="Search Status"></th>

                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- @foreach ($saleprices as $saleprice)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $saleprice->product->part_no ?? '' }}</td>
                                <td>{{ $saleprice->product->part_name ?? '' }}</td>
                                <td>{{ $saleprice->product->model ?? '' }}</td>
                                <td>{{ $saleprice->product->variance ?? '' }}</td>
                                <td>{{ $saleprice->product->units->name ?? '' }}</td>
                                <td>{{ $saleprice->price }}</td>
                                <td>{{ date('d/m/y', strtotime($saleprice->date)) }}</td>
                                <td>
                                    @if ($saleprice->status == 'submitted')
                                        <span class="badge border border-light text-light">Submitted</span>
                                    @elseif ($saleprice->status == 'verified')
                                        <span class="badge border border-info text-info">Verified</span>
                                    @elseif ($saleprice->status == 'declined')
                                        <span class="badge border border-warning text-warning">Declined</span>
                                    @elseif ($saleprice->status == 'cancelled')
                                        <span class="badge border border-danger text-danger">Cancelled</span>
                                    @endif
                                </td>
                                <td>
                                    <a class="btn btn-success btn-sm"
                                        href="{{ route('sale_price.view', $saleprice->id) }}"><i class="bi bi-eye"></i></a>
                                        @if ($saleprice->status != 'verified')
                                    <a class="btn btn-info btn-sm" href="{{ route('sale_price.edit', $saleprice->id) }}"><i
                                            class="bi bi-pencil"></i></a>
                                            @endif
                                    <a class="btn btn-danger btn-sm"
                                        href="{{ route('sale_price.destroy', $saleprice->id) }}"><i
                                            class="bi bi-trash"></i></a>
                                    @if ($saleprice->status != 'verified')
                                        @if ($saleprice->status != 'cancelled')
                                            <a class="btn btn-warning btn-sm"
                                                href="{{ route('sale_price.verify', $saleprice->id) }}"><i
                                                    class="bi bi-check-circle"></i></a>
                                        @endif
                                    @endif
                                </td>
                            </tr>
                        @endforeach --}}
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script>
        var data = "{{ route('sale_price.data') }}";
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
                        d.part_no = $('#search-part_no').val();
                        d.part_name = $('#search-part_name').val();
                        d.model = $('#search-model').val();
                        d.variance = $('#search-variance').val();
                        d.unit = $('#search-unit').val();
                        d.price = $('#search-price').val();
                        d.date = $('#search-date').val();
                        d.status = $('#search-status').val();
                    }
                }, // URL to fetch data
                columns: [{
                    data :'DT_RowIndex',
                         name: 'DT_RowIndex',
                         orderable: false,
                         searchable: false
                    }, {
                        data: 'product.part_no',
                        name: 'product.part_no',
                    }, {
                        data: 'product.part_name',
                        name: 'product.part_name',
                    },
                    {
                        data: 'product.model',
                        name: 'product.model',
                    },
                    {
                        data: 'product.variance',
                        name: 'product.variance',
                    },
                    {
                        data: 'product.units.name',
                        name: 'product.units.name',
                    },
                    {
                        data: 'price',
                        name: 'price',
                    },
                    {
                        data: 'date',
                        name: 'date',
                    },
                    {
                        data: 'status',
                        name: 'status',
                        render: function(data, type, row) {
                            switch (data) {
                                case 'submitted':
                                    return '<span class="badge border border-light text-light">Submitted</span>';
                                case 'verified':
                                    return '<span class="badge border border-info text-info">Verified</span>';
                                case 'declined':
                                    return '<span class="badge border border-warning text-warning">Declined</span>';
                                case 'cancelled':
                                    return '<span class="badge border border-danger text-danger">Cancelled</span>';
                                default:
                                    return data;
                            }
                        }
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
