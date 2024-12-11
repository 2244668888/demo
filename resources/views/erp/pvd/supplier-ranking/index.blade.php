@extends('layouts.app')
@section('title')
    SUPPLIER RANKING LIST
@endsection
@section('button')
    <a type="button" class="btn btn-info" href="{{ route('supplier_ranking.create') }}">
        <i class="bi bi-plus-square"></i> Add
    </a>
@endsection
@section('content')
<style>
    .all_column{
        background: transparent;
    color: white;

    }
</style>
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered m-0 datatable" >
                    <thead>
                        <tr>
                            <th>NO</th>
                            <th>Supplier Name</th>
                            <th>Ranking Date</th>
                            <th>Customer Ranking</th>
                            <th>Created By</th>
                            <th>Created Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <thead>
                        <tr>
                            <th></th>
                            <th>
                                <input type="text" id="search-supplier" placeholder="search Supplier Name">
                            </th>

                            <th>
                                <input type="text" id="search-ranking_date" placeholder="search Ranking Date">
                            </th>
                            <th>
                                <input type="text" id="search-ranking" placeholder="search Customer Ranking">
                            </th>
                            <th>
                                <input type="text" id="search-created_by" placeholder="search Created By">
                            </th>
                            <th>
                                <input type="text" id="search-date" placeholder="search Created Date">
                            </th>




                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- @foreach ($supplierrankings as $supplierranking)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $supplierranking->supplier->name ?? '' }}</td>
                                <td>{{ date('d/m/y', strtotime($supplierranking->ranking_date)) }}</td>
                                <td>
                                    @if ($supplierranking->ranking == 'A')
                                        <span class="badge bg-success">A</span>
                                    @endif
                                    @if ($supplierranking->ranking == 'B')
                                        <span class="badge bg-warning">B</span>
                                    @endif
                                    @if ($supplierranking->ranking == 'C')
                                        <span class="badge bg-danger">C</span>
                                    @endif
                                </td>
                                <td>{{ $supplierranking->user->user_name ?? '' }}</td>
                                <td>{{ date('d/m/y', strtotime($supplierranking->date)) }}</td>
                                <td>
                                    <a class="btn btn-info btn-sm"
                                        href="{{ route('supplier_ranking.edit', $supplierranking->id) }}"><i
                                            class="bi bi-pencil"></i></a>
                                    <a class="btn btn-success btn-sm"
                                        href="{{ route('supplier_ranking.view', $supplierranking->id) }}"><i
                                            class="bi bi-eye"></i></a>
                                    <a class="btn btn-danger btn-sm"
                                        href="{{ route('supplier_ranking.destroy', $supplierranking->id) }}"><i
                                            class="bi bi-trash"></i></a>
                                </td>
                            </tr>
                        @endforeach -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        var data = "{{ route('supplier_ranking.data') }}";
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
                    d.supplier = $('#search-supplier').val();
                    d.ranking_date = $('#search-ranking_date').val();
                    d.ranking = $('#search-ranking').val();
                    d.created_by = $('#search-created_by').val();
                    d.date = $('#search-date').val();
                   
                }
            }, // URL to fetch data
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    orderable: false,
                    searchable: false
                }, {
                    data: 'supplier.name',
                    name: 'supplier.name',
                },
                {
                    data: 'ranking_date',
                    name: 'ranking_date',
                },
                {
                    data: 'ranking',
                    name: 'ranking',
                }, {
                    data: 'user.user_name',
                    name: 'user.user_name',
                }, {
                    data: 'date',
                    name: 'date',
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
