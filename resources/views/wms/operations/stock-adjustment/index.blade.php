@extends('layouts.app')
@section('title')
    STOCK ADJUSTMENT LIST
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
                <table class="table datatable table-bordered m-0">
                    <thead>
                        <tr>
                            <th>Sr No</th>
                            <th>Part No</th>
                            <th>Part Name</th>
                            <th>Available Qty</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <thead>
                        <tr>
                            <th></th>
                            <th>
                                <input type="text" class="all_column " placeholder="search Part No">
                            </th>

                            <th>
                                <input type="text" class="all_column " placeholder="search Part Name">
                            </th>
                            <th>
                                <input type="text" class="all_column " placeholder="search Available Qty">
                            </th>


                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- @foreach ($products as $product)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $product->part_no }}</td>
                                <td>{{ $product->part_name }}</td>
                                <td>{{ $product->locations->first()->total_used_qty ?? 0 }}</td>
                                <td>
                                    <a class="btn btn-success btn-sm" href="#"
                                        onclick="render_locations({{ $product->id }}, '{{ $product->part_no }}', '{{ $product->part_name }}')">
                                        <i class="bi bi-sliders"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach --}}
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    {{-- LOCATIONS MODAL --}}
    <div class="modal fade" id="exampleModalCenter" tabindex="-1" aria-labelledby="exampleModalCenterTitle"
        aria-modal="true" role="dialog">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalCenterTitle">
                        STOCK ADJUSTMENT
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="" method="POST" id="updateForm">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-12 d-flex justify-content-between">
                                <h5>Part No: <span class="part_no_text"></span></h5>
                                <h5>Part Name: <span class="part_name_text"></span></h5>
                            </div>
                        </div>
                        <br>
                        <div class="table-responsive">
                            <table class="table table-bordered m-0 w-100" id="modalTable">
                                <thead>
                                    <tr>
                                        <th>Department</th>
                                        <th>Area</th>
                                        <th>Rack</th>
                                        <th>Level</th>
                                        <th>Available Qty</th>
                                        <th>Add or Remove Qty</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                            CANCEL
                        </button>
                        <button type="button" class="btn btn-primary" id="updateButton">
                            UPDATE
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script>
          var data = "{{ route('stock_adjustment.data') }}";
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
                        data: 'part_no',
                        name: 'part_no',
                    }, {
                        data: 'part_name',
                        name: 'part_name',
                    },
                     {
                        data: 'location_quantity',
                        name: 'location_quantity',
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
                        data: 'part_no',
                        name: 'part_no',
                    }, {
                        data: 'part_name',
                        name: 'part_name',
                    },
                     {
                        data: 'location_quantity',
                        name: 'location_quantity',
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
        let modalTable;

        $(document).ready(function() {
            modalTable = $('#modalTable').DataTable();
        });

        function render_locations(productId, partNo, partName) {
            $('.part_no_text').text(partNo);
            $('.part_name_text').text(partName);
            $('#updateForm').attr('action', '{{ route('stock_adjustment.update', ':id') }}'.replace(':id', productId));
            let url = '{{ url('wms/operations/stock-adjustment/edit') }}/' + productId;

            $.ajax({
                url: url,
                method: 'GET',
                success: function(response) {
                    modalTable.clear().draw();
                    let rowsAdded = false;
                    response.forEach(function(location) {
                        modalTable.row.add([
                            location.area.department,
                            location.area.name,
                            location.rack.name,
                            location.level.name,
                            location.used_qty,
                            '<input type="number" name="quantities[' + location.id +
                            ']" class="form-control quantity-input" value="0">'
                        ]).draw();
                    });
                    rowsAdded = true;

                    if (!rowsAdded) {
                        $('#updateButton').prop('disabled', true);
                    } else {
                        $('#updateButton').prop('disabled', false);
                    }

                    $('#exampleModalCenter').modal('show');

                },
                error: function(xhr) {
                    console.log(xhr.responseText);
                }
            });
        }

        document.getElementById('updateButton').addEventListener('click', function(e) {
            let invalidInput = false;

            document.querySelectorAll('.quantity-input').forEach(function(input) {
                if (input.value == '' || input.value == null) {
                    invalidInput = true;
                    input.classList.add('is-invalid'); // Optional: add a class to highlight invalid input
                } else {
                    input.classList.remove('is-invalid'); // Optional: remove the class if input is valid
                }
            });

            if (!invalidInput) {
                $(this).closest('form').submit();
            }
        });
    </script>
@endsection
