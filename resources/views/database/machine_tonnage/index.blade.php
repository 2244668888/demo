@extends('layouts.app')
@section('title')
    MACHINE TONNAGE LIST
@endsection
@section('button')
    <a type="button" class="btn btn-info" href="{{ route('machine_tonage.create') }}">
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
                            <th>Tonnage</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <thead>
                        <tr>
                            <th></th>
                                <th><input type="text" id="search-tonnage" placeholder="Search Tonnage"></th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- @foreach ($machine_tonnages as $machine_tonnage)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $machine_tonnage->tonnage }}</td>
                                <td>
                                    <a class="btn btn-info btn-sm"
                                        href="{{ route('machine_tonage.edit', $machine_tonnage->id) }}"><i
                                            class="bi bi-pencil"></i>
                                    </a>
                                    <a class="btn btn-success btn-sm"
                                        href="{{ route('machine_tonage.view', $machine_tonnage->id) }}"><i
                                            class="bi bi-eye"></i>
                                    </a>
                                    <a class="btn btn-danger btn-icon btn-sm"
                                        href="{{ route('machine_tonage.destroy', $machine_tonnage->id) }}"><i
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
        var data = "{{ route('machine_tonage.data') }}";
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
                        d.tonnage = $('#search-tonnage').val();
                    }
                }, // URL to fetch data
                columns: [{
                         data :'DT_RowIndex',
                         name: 'DT_RowIndex',
                         orderable: false,
                         searchable: false
                    }, {
                        data: 'tonnage',
                        name: 'tonnage',
                    }, {
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
