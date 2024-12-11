@extends('layouts.app')
@section('title')
    QUOTATION LIST
@endsection
@section('button')
    <a type="button" class="btn btn-info" href="{{ route('quotation.create') }}">
        <i class="bi bi-plus-square"></i> Add
    </a>
@endsection
@section('content')
<style>
    .table thead tr input {
            background: transparent;
            color: white;

        }
    /* Customize the tooltip */
    .tooltip-inner {
        background-color: #5bc0de !important; /* Tooltip background color */
        color: #fff !important; /* Tooltip text color */
        font-size: 14px !important; /* Tooltip font size */
        border-radius: 5px !important;  /* Tooltip border radius */
        padding: 10px !important; /* Tooltip padding */
    }

    /* Customize the arrow of the tooltip */
    .tooltip.bs-tooltip-top .arrow::before {
        border-top-color: #5bc0de !important;  /* Arrow color for top tooltip */
    }

    .tooltip.bs-tooltip-bottom .arrow::before {
        border-bottom-color: #5bc0de !important; /* Arrow color for bottom tooltip */
    }

    .tooltip.bs-tooltip-left .arrow::before {
        border-left-color: #5bc0de !important; /* Arrow color for left tooltip */
    }

    .tooltip.bs-tooltip-right .arrow::before {
        border-right-color: #5bc0de !important; /* Arrow color for right tooltip */
    }
</style>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table  class="table table-bordered m-0 datatable" >
                    <thead>
                        <tr>
                            <th>Sr No</th>
                            <th>Ref No</th>
                            <th>Customer Name</th>
                            <th>Created Date</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <thead>
                        <tr>
                            <th></th>
                            <th><input type="text" id="search-ref_no" placeholder="Search Ref No"></th>
                            <th><input type="text" id="search-customer" placeholder="Search Customer Name"></th>
                            <th><input type="text" id="search-date" placeholder="Search Created Date"></th>
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
        var data = "{{ route('quotation.data') }}";
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
                        d.ref_no = $('#search-ref_no').val();
                        d.customer = $('#search-customer').val();
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
                        data: 'ref_no',
                        name: 'ref_no',
                    },  {
                        data: 'customers.name' ?? '',
                        name: 'customers.name' ?? '',
                    }, {
                        data: 'date',
                        name: 'date',
                    },
                    {
                        data: 'status',
                        name: 'status',
                        render: function(data, type, row) {
                            if (data === 'submitted') {
                                return '<span class="badge border border-light text-light">Submitted</span>';
                            }else if (data === 'verified') {
                                return '<span class="badge border border-info text-info">Verified</span>';
                            }else if (data === 'declined') {
                                return '<span class="badge border border-warning text-warning">Declined</span>';
                            }else if (data == 'cancelled'){
                                return '<span class="badge border border-danger text-danger">Cancelled</span>';
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

            $('[data-toggle="tooltip"]').tooltip();

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
