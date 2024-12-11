@extends('layouts.app')

@section('title')
        ACCOUNT CATEGORIES
    @endsection
    @section('button')
        <a type="button" class="btn btn-info" href="{{ route('account_categories.create') }}">
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
                <table class="table table-bordered m-0 datatable" >
                    <thead>
                        <tr>
                            <th>Sr #</th>
                            <th>Name</th>
                            <th>Type</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <thead>
                        <tr>
                            <th></th>
                            <th><input type="text" id="search-name" placeholder="Search Name"></th>
                            <th><input type="text" id="search-type" placeholder="Search Type"></th>
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

    var data = "{{ route('account_categories.data') }}";
 
     $(document).ready(function () {
     let bool = true;
     var table = $('.datatable').DataTable({
         perPageSelect: [5, 10, 15, ["All", -1]],
         processing: true,
         serverSide: true,
         language: {
             processing: 'Processing' 
         },
         ajax: {
             url: data, 
             type: 'GET',
             data: function (d) {
                 d.name = $('#search-name').val();
                 d.type = $('#search-type').val();
             }
         },
         columns: [{
             data :'DT_RowIndex',
                          name: 'DT_RowIndex',
                          orderable: false,
                          searchable: false
             }, {
                 data: 'name',
                 name: 'name',
             },
             {
                 data: 'type',
                 name: 'type',
             },
             {
                 data: 'action',
                 name: 'action',
                 orderable: false
             },
         ],
         paging: true
     });
     bool = false;
     $(document).on('keyup','#dt-search-0', function() {
                 console.log($(this).val())
             table.search($(this).val()).draw();
              });
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
