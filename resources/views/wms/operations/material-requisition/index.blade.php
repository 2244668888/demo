@extends('layouts.app')
@section('title')
    MATERIAL REQUISITION
@endsection
@section('button')
    <a type="button" class="btn btn-info" href="{{ route('material_requisition.create') }}">
        <i class="bi bi-plus-square"></i> Add
    </a>
@endsection
@section('content')
<style>
    .all_column {
        background: transparent;
        color: white;

    }
</style>
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table  class="table table-bordered m-0 datatable">
                    <thead>
                        <tr>
                            <th>Sr No</th>
                            <th>MRF No.</th>
                            <th>Production Order No.</th>
                            <th>Request From</th>
                            <th>Request To</th>
                            <th>Request Date</th>
                            <th>Plan Date</th>
                            <th>Machine</th>
                            <th>Shift</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <thead>
                        <tr>
                            <th></th>
                            <th>
                                <input type="text" class="all_column " placeholder="search MRF No.">
                            </th>
                            <th>
                                <input type="text" class="all_column " placeholder="search Production Planning Ref No.">
                            </th>
                            <th>
                                <input type="text" class="all_column " placeholder="search Request From">
                            </th>
                            <th>
                                <input type="text" class="all_column " placeholder="search Request To">
                            </th>
                            <th>
                                <input type="text" class="all_column " placeholder="search Request Date">
                            </th>
                            <th>
                                <input type="text" class="all_column " placeholder="search Plan Date">
                            </th>
                            <th>
                                <input type="text" class="all_column " placeholder="search Machine">
                            </th>
                            <th>
                                <input type="text" class="all_column " placeholder="search Shift">
                            </th>
                            <th>
                                <input type="text" class="all_column " placeholder="search Status">
                            </th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- @foreach ($material_requisitions as $material_requisition)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $material_requisition->ref_no }}</td>
                                <td>{{ $material_requisition->pro_order_no }}</td>
                                <td>
                                    @foreach ($depts as $dept)
                                        @if ($dept->id == $material_requisition->request_from)
                                            {{ $dept->name }}
                                        @endif
                                    @endforeach

                                </td>
                                <td>
                                    @foreach ($depts as $dept)
                                        @if ($dept->id == $material_requisition->request_to)
                                            {{ $dept->name }}
                                        @endif
                                    @endforeach
                                </td>
                                <td>{{ $material_requisition->request_date }}</td>
                                <td>{{ $material_requisition->plan_date }}</td>
                                <td>
                                    @foreach ($machines as $machine)
                                        @if ($machine->id == $material_requisition->machine)
                                            {{ $machine->name }}
                                        @endif
                                    @endforeach
                                </td>
                                <td>{{ $material_requisition->shift }}</td>
                                <td>
                                    @if ($material_requisition->status == 'Requested')
                                        <span class="badge border border-dark text-dark">Requested</span>
                                    @endif
                                    @if ($material_requisition->status == 'Issued')
                                        <span class="badge border border-warning text-warning">Issued</span>
                                    @endif
                                    @if ($material_requisition->status == 'Received')
                                        <span class="badge border border-primary text-primary">Received</span>
                                    @endif
                                </td>
                                <td>
                                    <a class="btn btn-success btn-sm" title="View" href="{{ route('material_requisition.view', $material_requisition->id) }}"><i
                                        class="bi bi-eye"></i></a>
                                    @if ($material_requisition->status == 'Requested')
                                        <a class="btn btn-info btn-sm" title="Edit" href="{{ route('material_requisition.edit', $material_requisition->id) }}"><i
                                            class="bi bi-pencil"></i></a>
                                        <a class="btn btn-warning btn-sm" title="Issue" href="{{ route('material_requisition.issue', $material_requisition->id) }}"><i
                                            class="bi bi-box-arrow-right"></i></a>
                                        <a class="btn btn-danger btn-sm" title="Delete" href="{{ route('material_requisition.destroy', $material_requisition->id) }}"><i
                                            class="bi bi-trash"></i></a>
                                    @endif
                                    @if ($material_requisition->status == 'Issued')
                                        <a class="btn btn-primary btn-sm" title="Recieve" href="{{ route('material_requisition.receive', $material_requisition->id) }}"><i
                                            class="bi bi-box-arrow-left"></i></a>
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
        var data = "{{ route('material_requisition.data') }}";
        $(document).ready(function() {
            let bool = true;
            $('.datatable').DataTable({
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
                        d.draw = d.draw || 1; // Add 'draw' parameter with a default value
                        d.start = d.start || 0; // Add 'start' parameter with a default value
                        d.length = d.length || 10; // Add 'length' parameter with a default value
                        if (bool) {
                            d.order = [null, null];
                        } else {
                            d.order = d.order || [null,
                                null
                            ]; // Add sorting information with a default value
                        }
                    }
                }, // URL to fetch data
                columns: [{
                        data: 'sr_no',
                        name: 'sr_no',
                        orderable: false
                    }, {
                        data: 'ref_no',
                        name: 'ref_no',
                    }, {
                        data: 'pro_order_no',
                        name: 'pro_order_no',
                    },
                    {
                        data: 'department_from.name',
                        name: 'department_from.name',
                    },
                    {
                        data: 'department_to.name',
                        name: 'department_to.name',
                    },

                    {
                        data: 'request_date',
                        name: 'request_date',
                    },
                    {
                        data: 'plan_date',
                        name: 'plan_date',
                    },
                    {
                        data: 'machines.name',
                        name: 'machines.name',

                    },
                    {
                        data: 'shift',
                        name: 'shift',

                    },
                    {
                        data: 'status',
                        name: 'status',

                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false
                    },
                ],
                paging: true
                // Other DataTables options go here
            });
            bool = false;
        });

        function AjaxCall(columnsData) {

            $('.datatable').DataTable().destroy();

            $('.datatable').DataTable({
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
                        d.draw = d.draw || 1; // Add 'draw' parameter with a default value
                        d.start = d.start || 0; // Add 'start' parameter with a default value
                        d.length = d.length || 10; // Add 'length' parameter with a default value
                        d.order = d.order || [null, null]; // Add sorting information with a default value
                        d.columnsData = columnsData;

                    }
                }, // URL to fetch data
                columns: [{
                        data: 'sr_no',
                        name: 'sr_no',
                        orderable: false
                    }, {
                        data: 'ref_no',
                        name: 'ref_no',
                    }, {
                        data: 'pro_order_no',
                        name: 'pro_order_no',
                    },
                    {
                        data: 'department_from.name',
                        name: 'department_from.nam',
                    },
                    {
                        data: 'department_to.name',
                        name: 'department_to.name',
                    },

                    {
                        data: 'request_date',
                        name: 'request_date',
                    },
                    {
                        data: 'plan_date',
                        name: 'plan_date',
                    },
                    {
                        data: 'machines.name',
                        name: 'machines.name',

                    },
                    {
                        data: 'shift',
                        name: 'shift',

                    },
                    {
                        data: 'status',
                        name: 'status',

                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false
                    },
                ],
                paging: true
                // Other DataTables options go here
            });

        }

        var typingTimer;
        var doneTypingInterval = 1000; // Adjust the time interval as needed (in milliseconds)

        $('.datatable .all_column').on('keyup', function() {
            clearTimeout(typingTimer);
            typingTimer = setTimeout(() => {
                var columnIndex = $(this).closest('th').index();

                // Collect all column indices and values in an array
                var columnsData = $('.datatable .all_column').map(function() {
                    var index = $(this).closest('th').index();
                    var value = $(this).val();
                    return {
                        index: index,
                        value: value
                    };
                }).get();
                AjaxCall(columnsData);

                // Focus on the input in the same column after making the Ajax call
                $(this).closest('tr').find('th').eq(columnIndex).find('input').focus();
            }, doneTypingInterval);
        });
    </script>
@endsection
