@extends('layouts.app')
@section('title')
    DELIVERY INSTRUCTION LIST
@endsection
@section('button')
    <a type="button" class="btn btn-info" href="{{ route('delivery_instruction.create') }}">
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
                <table class="table table-bordered m-0 datatable">
                    <thead>
                        <tr>
                            <th>Sr No</th>
                            <th>Order No</th>
                            <th>Customer Name</th>
                            <th>Order Month</th>
                            <th>Created Date</th>
                            <th>Created By</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <thead>
                        <tr>
                            <th></th>
                            <th>
                                <input type="text" class="all_column " placeholder="search Order No">
                            </th>
                            <th>
                                <input type="text" class="all_column " placeholder="search Customer Name">
                            </th>
                            <th>
                                <input type="text" class="all_column " placeholder="search Order Month">
                            </th>
                            <th>
                                <input type="text" class="all_column " placeholder="search Created Date">
                            </th>
                            <th>
                                <input type="text" class="all_column " placeholder="search Created By">
                            </th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- @foreach ($delivery_instructions as $delivery_instruction)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $delivery_instruction->order->order_no }}</td>
                                <td>{{ $delivery_instruction->order->customers->name ?? '' }}</td>
                                <td>{{ $delivery_instruction->order->order_month }}</td>
                                <td>{{ $delivery_instruction->date }}</td>
                                <td>{{ $delivery_instruction->user->user_name }}</td>
                                <td>
                                    <a class="btn btn-success btn-sm"
                                        href="{{ route('delivery_instruction.view', $delivery_instruction->id) }}"><i
                                            class="bi bi-eye"></i></a>
                                    <a class="btn btn-info btn-sm"
                                        href="{{ route('delivery_instruction.edit', $delivery_instruction->id) }}"><i
                                            class="bi bi-pencil"></i></a>
                                    <a class="btn btn-danger btn-sm"
                                        href="{{ route('delivery_instruction.destroy', $delivery_instruction->id) }}"><i
                                            class="bi bi-trash"></i></a>
                                </td>
                            </tr>
                        @endforeach --}}
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        var data = "{{ route('delivery_instruction.data') }}";
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
                        data: 'order.order_no',
                        name: 'order.order_no',
                    }, {
                        data: 'customer',
                        name: 'customer',
                    },
                    {
                        data: 'order.order_month',
                        name: 'order.order_month',
                    },
                    {
                        data: 'date',
                        name: 'date',
                    },
                    {
                        data: 'user.user_name',
                        name: 'user.user_name',

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
                        data: 'order.order_no',
                        name: 'order.order_no',
                    }, {
                        data: 'customer',
                        name: 'customer',
                    },
                    {
                        data: 'order.order_month',
                        name: 'order.order_month',
                    },
                    {
                        data: 'date',
                        name: 'date',
                    },
                    {
                        data: 'user.user_name',
                        name: 'user.user_name',

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
