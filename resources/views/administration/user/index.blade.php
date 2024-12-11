@extends('layouts.app')
@section('title')
    STAFF REGISTRATION LIST
@endsection
@section('button')
    <a type="button" class="btn btn-info" href="{{ route('user.create') }}">
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
                            <th>Code</th>
                            <th>Full Name</th>
                            <th>User Name</th>
                            <th>Email Address</th>
                            <th>Department</th>
                            <th>Active</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <thead>
                        <tr>
                            <th></th>
                            <th><input type="text" id="search-code" placeholder="Search Code"></th>
                            <th><input type="text" id="search-full_name" placeholder="Search Full Name"></th>
                            <th><input type="text" id="search-user_name" placeholder="Search User Name"></th>
                            <th><input type="text" id="search-phone" placeholder="Search Email Address"></th>
                            <th><input type="text" id="search-department" placeholder="Search Department"></th>
                            <th><input type="text" id="search-active" placeholder="Search Active"></th>
                            <th></th>
                        </tr>
                    </thead>

                    <tbody>
                        {{-- @foreach ($users as $user)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $user->full_name }}</td>
                                <td>{{ $user->user_name }}</td>
                                <td>{{ $user->email }}</td>
                                <td><div class="form-check">

                                        @if ($user->is_active == 'yes')
                                        <i class="fs-2 bi bi-check2 " style="color: green"></i>
                                        @else
                                        <i class="fs-3 bi bi-x-lg " style="color: red"></i>
                                         @endif</td>
                                <td>
                                    <a class="btn btn-info btn-sm" href="{{ route('user.edit', $user->id) }}"><i
                                            class="bi bi-pencil"></i>
                                    </a>
                                    <a class="btn btn-success btn-sm" href="{{ route('user.view', $user->id) }}"><i
                                        class="bi bi-eye"></i>
                                </a>
                                    <a class="btn btn-danger btn-icon btn-sm"
                                        href="{{ route('user.destroy', $user->id) }}"><i class="bi bi-trash"></i>
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
        var data = "{{ route('user.data') }}";
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
                        d.code = $('#search-code').val();
                        d.full_name = $('#search-full_name').val();
                        d.user_name = $('#search-user_name').val();
                        d.email = $('#search-email').val();
                        d.department = $('#search-department').val();
                        d.active = $('#search-active').val();
                    }
                }, // URL to fetch data
                columns: [{
                    data :'DT_RowIndex',
                         name: 'DT_RowIndex',
                         orderable: false,
                         searchable: false
                    },
                    {
                        data: 'code',
                        name: 'code',
                    },
                    
                    {
                        data: 'full_name',
                        name: 'full_name',
                    },
                    {
                        data: 'user_name',
                        name: 'user_name',
                    },
                    {
                        data: 'email',
                        name: 'email',
                    },
                    {
                        data: 'department.name',
                        name: 'department.name',
                        defaultContent:'',
                    },
                    {
                        data: 'is_active',
                        name: 'is_active',
                        render: function(data, type, row) {
                            if (data === 'yes') {
                                return '<i class="fs-2 bi bi-check2" style="color: green"></i>';
                            } else {
                                return '<i class="fs-3 bi bi-x-lg" style="color: red"></i>';
                            }
                        },
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
