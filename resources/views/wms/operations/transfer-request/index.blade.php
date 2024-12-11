@extends('layouts.app')
@section('title')
    TRANSFER REQUEST
@endsection
@section('button')
    <a type="button" class="btn btn-info" href="{{ route('transfer_request.create') }}">
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
                <table  class="table datatable table-bordered m-0">
                    <thead>
                        <tr>
                            <th>Sr No</th>
                            <th>Ref No</th>
                            <th>MRF No.</th>
                            <th>Request Date</th>
                            <th>Shift</th>
                            <th>Machine</th>
                            <th>Request From</th>
                            <th>Request To</th>
                            <th>Received By</th>
                            <th>Received Date</th>
                            <th>Time</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <thead>
                        <tr>
                            <th></th>
                            <th>
                                <input type="text" class="all_column " placeholder="search Ref No.">
                            </th>
                            <th>
                                <input type="text" class="all_column " placeholder="search MRF No.">
                            </th>
                            <th>
                                <input type="text" class="all_column " placeholder="search Request Date.">
                            </th>
                            <th>
                                <input type="text" class="all_column " placeholder="search shift.">
                            </th>
                            <th>
                                <input type="text" class="all_column " placeholder="search machine">
                            </th>
                            <th>
                                <input type="text" class="all_column " placeholder="search Request From">
                            </th>
                            <th>
                                <input type="text" class="all_column " placeholder="search Request To">
                            </th>
                            <th>
                                <input type="text" class="all_column " placeholder="search Request By">
                            </th>
                            <th>
                                <input type="text" class="all_column " placeholder="search Received Date">
                            </th>
                            <th>
                                <input type="text" class="all_column " placeholder="search Time">
                            </th>
                            <th>
                                <input type="text" class="all_column " placeholder="search Status">
                            </th>

                            <th></th>
                        </tr>
                    </thead>
                   <tbody>
                        {{-- @foreach ($transfer_requests as $transfer_request)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $transfer_request->ref_no }}</td>
                                <td>{{ $transfer_request->mrf->ref_no ?? '' }}</td>
                                <td>{{ $transfer_request->request_date }}</td>
                                <td>{{ $transfer_request->shift }}</td>
                                <td>
                                    @foreach ($depts as $dept)
                                        @if ($dept->id == $transfer_request->request_from)
                                            {{ $dept->name }}
                                        @endif
                                    @endforeach
                                </td>
                                <td>
                                    @foreach ($machines as $machine)
                                        @if ($machine->id == $transfer_request->machine)
                                            {{ $machine->name }}
                                        @endif
                                    @endforeach
                                </td>
                                <td>
                                    @foreach ($depts as $dept)
                                        @if ($dept->id == $transfer_request->request_to)
                                            {{ $dept->name }}
                                        @endif
                                    @endforeach
                                </td>
                                <td>
                                    @foreach ($users as $user)
                                        @if ($user->id == $transfer_request->rcv_by)
                                            {{ $user->user_name }}
                                        @endif
                                    @endforeach
                                </td>
                                <td>{{ $transfer_request->rcv_date }}</td>
                                <td>{{ $transfer_request->rcv_time }}</td>
                                <td>
                                    @if ($transfer_request->status == 'Requested')
                                        <span class="badge border border-dark text-dark">Requested</span>
                                    @endif
                                    @if ($transfer_request->status == 'Issued')
                                        <span class="badge border border-warning text-warning">Issued</span>
                                    @endif
                                    @if ($transfer_request->status == 'Received')
                                        <span class="badge border border-primary text-primary">Received</span>
                                    @endif
                                </td>
                                <td>

                                    @if ($transfer_request->status == 'Requested')
                                        <a class="btn btn-info btn-sm" title="Edit" href="{{ route('transfer_request.edit', $transfer_request->id) }}"><i
                                            class="bi bi-pencil"></i></a>
                                        <a class="btn btn-warning btn-sm" title="Issue" href="{{ route('transfer_request.issue', $transfer_request->id) }}"><i
                                            class="bi bi-box-arrow-right"></i></a>
                                        <a class="btn btn-danger btn-sm" title="Delete" href="{{ route('transfer_request.destroy', $transfer_request->id) }}"><i
                                            class="bi bi-trash"></i></a>
                                    @endif
                                    @if ($transfer_request->status == 'Issued')
                                        <a class="btn btn-primary btn-sm" title="Recieve" href="{{ route('transfer_request.receive', $transfer_request->id) }}"><i
                                            class="bi bi-box-arrow-left"></i></a>
                                    @endif
                                    @if ($transfer_request->status == 'Received')
                                        <a class="btn btn-success btn-sm" title="View" href="{{ route('transfer_request.view', $transfer_request->id) }}"><i
                                            class="bi bi-eye"></i></a>
                                    @endif

                                </td>
                            </tr>
                        @endforeach --}}
                    </tbody> -
                </table>
            </div>
        </div>
    </div>

    <script>
        var data = "{{ route('transfer_request.data') }}";
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
                        data: 'mrf.ref_no',
                        name: 'mrf.ref_no',
                    },
                     {
                        data: 'request_date',
                        name: 'request_date',
                    },
                     {
                        data: 'shift',
                        name: 'shift',
                    },
                    {
                        data: 'machines.name',
                        name: 'machines.name',
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
                        data: 'user.user_name',
                        name: 'user.user_name',
                    },
                    {
                        data: 'rcv_date',
                        name: 'rcv_date',
                    },
                    {
                        data: 'rcv_time',
                        name: 'rcv_time',

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
                        data: 'mrf.ref_no',
                        name: 'mrf.ref_no',
                    },
                     {
                        data: 'request_date',
                        name: 'request_date',
                    },
                     {
                        data: 'shift',
                        name: 'shift',
                    },
                    {
                        data: 'machines.name',
                        name: 'machines.name',
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
                        data: 'user.user_name',
                        name: 'user.user_name',
                    },
                    {
                        data: 'rcv_date',
                        name: 'rcv_date',
                    },
                    {
                        data: 'rcv_time',
                        name: 'rcv_time',

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
