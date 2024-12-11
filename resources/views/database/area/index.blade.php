@extends('layouts.app')
@section('title')
    AREA LIST
@endsection
@section('button')
    <a type="button" class="btn btn-info" href="{{ route('area.create') }}">
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
                            <th>Sr #</th>
                            <th>Name</th>
                            <th>Code</th>
                            <th>Department</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <thead>
                        <tr>
                            <th></th>
                            <th><input type="text" id="search-name" placeholder="Search name"></th>
                            <th><input type="text" id="search-code" placeholder="Search code"></th>
                            <th><input type="text" id="search-department" placeholder="Search department"></th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- @foreach ($areas as $area)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $area->name }}</td>
                                <td>{{ $area->code }}</td>
                                <td>{{ $area->department }}</td>
                                <td>
                                    <a class="btn btn-info btn-sm"
                                        href="{{ route('area.edit', $area->id) }}"><i
                                            class="bi bi-pencil"></i>
                                    </a>
                                    <a class="btn btn-success btn-sm"
                                        href="{{ route('area.view', $area->id) }}"><i class="bi bi-eye"></i>
                                    </a>
                                    <a class="btn btn-danger btn-icon btn-sm"
                                        href="{{ route('area.destroy', $area->id) }}"><i
                                            class="bi bi-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach --}}
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        var data = "{{ route('area.data') }}";
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
                        d.name = $('#search-name').val();
                    d.code = $('#search-code').val();
                    d.department = $('#search-department').val();
                    }
                }, // URL to fetch data
                columns: [{
                    data :'DT_RowIndex',
                         name: 'DT_RowIndex',
                         orderable: false,
                         searchable: false
                    }, {
                        data: 'name',
                        name: 'name',
                    }, {
                        data: 'code',
                        name: 'code',
                    },
                    {
                        data: 'department',
                        name: 'department',
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
