@extends('layouts.app')
@section('title')
    PROCESS LIST
@endsection
@section('button')
    <a type="button" class="btn btn-info" href="{{ route('process.create') }}">
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
                            <th>Description</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <thead>
                        <tr>
                            <th></th>
                            <th><input type="text" id="search-name" placeholder="Search Name"></th>
                            <th><input type="text" id="search-code" placeholder="Search Code"></th>
                            <th><input type="text" id="search-description" placeholder="Search Description"></th>

                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- @foreach ($processs as $process)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $process->name }}</td>
                                <td>{{ $process->code }}</td>
                                <td>
                                    <div class="description">
                                        <p class="short-desc">{{ Str::limit($process->description, 100) }}</p>
                                        <p class="full-desc" style="display:none;">{{ $process->description }}</p>
                                        <a href="#" class="read-more">Read More</a>
                                    </div>
                                </td>
                                <td>
                                    <a class="btn btn-info btn-sm" href="{{ route('process.edit', $process->id) }}"><i
                                            class="bi bi-pencil"></i>
                                    </a>
                                    <a class="btn btn-success btn-sm" href="{{ route('process.view', $process->id) }}"><i
                                            class="bi bi-eye"></i>
                                    </a>
                                    <a class="btn btn-danger btn-icon btn-sm"
                                        href="{{ route('process.destroy', $process->id) }}"><i class="bi bi-trash"></i>
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
        var data = "{{ route('process.data') }}";
        $(document).ready(function() {
            let bool = true;
           var table =  $('.datatable').DataTable({
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
                        d.description = $('#search-description').val();
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
                    }, {
                        data: 'description',
                        name: 'description',
                        render: function(data, type, row) {
                            if (data!= null) {

                                    return '<div class="description">' +
                                        '<p class="short-desc">' + data.substring(0, 100) + '</p>' +
                                        '<p class="full-desc" style="display:none;">' + data + '</p>' +
                                        '<a href="#" class="read-more">Read More</a>' +
                                        '</div>';
                                    }else{
                                        return '';
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
