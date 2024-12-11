@extends('layouts.app')
@section('title')
    SUPPLIER LIST
@endsection
@section('button')
    <a type="button" class="btn btn-info" href="{{ route('supplier.create') }}">
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
                            <th>Address</th>
                            <th>Contact No</th>
                            <th>Group</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <thead>
                        <tr>
                            <th></th>
                            <th><input type="text" id="search-name" placeholder="Search Name"></th>
                            <th><input type="text" id="search-address" placeholder="Search Address"></th>
                            <th><input type="text" id="search-contact" placeholder="Search Contact No"></th>
                            <th><input type="text" id="search-group" placeholder="Search Group"></th>

                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- @foreach ($suppliers as $supplier)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $supplier->name }}</td>
                                <td>{{ $supplier->address }}</td>
                                <td>{{ $supplier->contact }}</td>
                                <td>{{ $supplier->group }}</td>
                                <td>
                                    <a class="btn btn-info btn-sm"
                                        href="{{ route('supplier.edit', $supplier->id) }}"><i
                                            class="bi bi-pencil"></i>
                                    </a>
                                    <a class="btn btn-success btn-sm"
                                        href="{{ route('supplier.view', $supplier->id) }}"><i class="bi bi-eye"></i>
                                    </a>
                                    <a class="btn btn-danger btn-icon btn-sm"
                                        href="{{ route('supplier.destroy', $supplier->id) }}"><i
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
        var data = "{{ route('supplier.data') }}";
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
                        d.name = $('#search-name').val();
                        d.address = $('#search-address').val();
                        d.contact = $('#search-contact').val();
                        d.group = $('#search-group').val();

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
                        data: 'address',
                        name: 'address',
                        render: function(data, type, row) {
                            return '<div class="description">' +
                                '<p class="short-desc">' + data.substring(0, 100) + '</p>' +
                                '<p class="full-desc" style="display:none;">' + data + '</p>' +
                                '<a href="#" class="read-more">Read More</a>' +
                                '</div>';
                        }
                    }, {
                        data: 'contact',
                        name: 'contact',
                    }, {
                        data: 'group',
                        name: 'group',
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
