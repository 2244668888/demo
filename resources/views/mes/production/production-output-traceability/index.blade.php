@extends('layouts.app')
@section('title')
    PRODUCTION OUTPUT TRACEABILITY LIST
@endsection
@section('button')
    <button class="btn btn-primary" id="showFilterBtn">
        <i class="bi bi-gear me-2"></i>Advance Search
    </button>
@endsection
@section('content')
    <link rel="stylesheet" href="{{ asset('assets/vendor/daterange/daterange.css') }}" />
    <div class="row gx-5 viewInput" style="display: none;">
        <!-- Date Range Section -->
        <div class="col-lg-4 col-md-4 col-12 mt-3">
            <div class="card mb-3">
                <div class="card-body">
                    <div class="form-group m-0">
                        <label class="form-label" for="abc12">Date Range</label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="bi bi-calendar4"></i>
                            </span>
                            <input type="text" id="abc12" class="form-control datepicker-range-iso-week-numbers">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Process Section -->
        <div class="col-lg-4 col-md-4 col-12 mt-3">
            <div class="card mb-3">
                <div class="card-body">
                    <div class="form-group m-0">
                        <label class="form-label" for="process">Process</label>
                        <select id="process" class="form-select rounded-4" style="width: 100% !important;">
                            <option value="" disabled selected>Select Process</option>
                            @foreach ($uniqueProcesses as $production)
                                <option value="{{ $production }}">{{ $production }}</option>
                            @endforeach
                            <!-- Add other process options if needed -->
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <!-- Production Order Section -->
        <div class="col-lg-4 col-md-4 col-12 mt-3">
            <div class="card mb-3">
                <div class="card-body">
                    <div class="form-group m-0">
                        <label class="form-label" for="order_no">Production Order No</label>
                        <select id="order_no" class="form-select rounded-4" style="width: 100% !important;">
                            <option value="" disabled selected>Please Select</option>
                            @foreach ($uniquePoNos as $production)
                                <option value="{{ $production }}">
                                    {{ $production }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <!-- Part No Section -->
        <div class="col-lg-4 col-md-4 col-12 mt-3">
            <div class="card mb-3">
                <div class="card-body">
                    <div class="form-group m-0">
                        <label for="part_no" class="form-label">Part No :</label>
                        <select name="part_no" id="part_no" class="form-select rounded-4" style="width: 100% !important;">
                            <option value="" disabled selected>Please Select</option>
                            @foreach ($products as $production)
                                <option value="{{ $production->part_no }}">
                                    {{ $production->part_no }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <!-- Buttons Section -->
        <div class="row gx-3 mb-3">
            <div class="col-12 d-flex justify-content-end">
                <button type="submit" class="btn btn-info me-2" id="generate">
                    <i class="bi bi-search"></i> Search
                </button>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table id="myTable1" class="table table-bordered m-0">
                    <thead>
                        <tr>
                            <th>Sr No</th>
                            <th>Production Date</th>
                            <th>Production Order No</th>
                            <th>Process</th>
                            <th>Part No</th>
                            <th>Part Name</th>
                            <th>Shift</th>
                            <th>Operator Name</th>
                            <th>Total Produced</th>
                            <th>Total Rejected Qty</th>
                            <th>Total Good Qty</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($productions as $production)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $production->daily_production->created_date ?? '' }}</td>
                                <td>{{ $production->po_no }}</td>
                                <td>{{ $production->process }}</td>
                                <td>{{ $production->product->part_no ?? '' }}</td>
                                <td>{{ $production->product->part_name ?? '' }}</td>
                                <td>{{ $production->shift }}</td>
                                @php
                                    $item = json_decode($production->operator);
                                @endphp
                                <td>
                                    @if ($item)
                                        @foreach ($item as $id)
                                            @php
                                                $user = App\Models\User::find($id);
                                            @endphp
                                            {{ $user->user_name ?? '' }}
                                            @if (!$loop->last)
                                                ,
                                            @endif
                                        @endforeach
                                    @endif
                                </td>
                                <td>{{ $production->total_produced }}</td>
                                <td>{{ $production->total_rejected_qty }}</td>
                                <td>{{ $production->total_good_qty }}</td>
                                <td>
                                    @if ($production->status == 'Not-initiated')
                                        <span class="badge border border-secondary text-secondary">Not-initiated</span>
                                    @elseif ($production->status == 'Start')
                                        <span class="badge border border-success text-success">Started</span>
                                    @elseif ($production->status == 'Pause')
                                        <span class="badge border border-warning text-warning">Paused</span>
                                    @elseif ($production->status == 'Stop')
                                        <span class="badge border border-danger text-danger">Stopped</span>
                                    @elseif ($production->status == 'Checked')
                                        <span class="badge border border-info text-info">Checked</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex">
                                        @if ($production->status != 'Stop' && $production->status != 'Checked')
                                            <a class="btn btn-info btn-sm me-2"
                                                href="{{ route('production_output_traceability.edit', $production->id) }}"><i
                                                    class="bi bi-pencil"></i></a>
                                        @elseif ($production->status != 'Checked')
                                            <a class="btn btn-warning btn-sm me-2"
                                                href="{{ route('production_output_traceability.qc_edit', $production->id) }}"><i
                                                    class="bi bi-check-circle"></i></a>
                                        @endif
                                        <a class="btn btn-success btn-sm"
                                            href="{{ route('production_output_traceability.view', $production->id) }}"><i
                                                class="bi bi-eye"></i></a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script src="{{ asset('assets/js/moment.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/daterange/daterange.js') }}"></script>
    <script>
        let table;
        $(document).ready(function() {
            table = $('#myTable1').DataTable();
        });
        $(".datepicker-range-iso-week-numbers").daterangepicker({
            drops: "up",
            opens: "left",
            showWeekNumbers: true,
            showISOWeekNumbers: true,
            startDate: moment().startOf("hour"),
            endDate: moment().startOf("hour").add(32, "hour"),
            locale: {
                format: "DD-MM-YYYY",
            },
        });

        function getDateRange() {
            var datepicker = $(".datepicker-range-iso-week-numbers").data('daterangepicker');
            return {
                start_date: datepicker.startDate.format('YYYY-MM-DD'),
                end_date: datepicker.endDate.format('YYYY-MM-DD')
            };
        }

        $('#showFilterBtn').click(function() {
            if ($('.viewInput').is(':hidden')) {
                $('.viewInput').show(); // Show the hidden filters
                $('#showFilterBtn').html(
                    '<i class="bi bi-gear"></i> Advance Search'
                ); // Change showFilterBtn viewInput to "Don't Show"
            } else {
                $('.viewInput').hide(); // Hide the filters
                $('#showFilterBtn').html(
                    '<i class="bi bi-gear"></i> Advance Search'
                ); // Change showFilterBtn viewInput back to "Advance Search"
            }
        });

        $('#generate').on('click', function() {
            let product = $('#part_no').val();
            let process = $('#process').val();
            let order_no = $('#order_no').val();
            let dates = getDateRange(); // Assume getDateRange() returns an object with start_date and end_date
            let start_date = dates.start_date;
            let end_date = dates.end_date;

            // Apply search filters only if the values are not null or empty
            if (order_no) {
                table.column(2).search(order_no);
            } else {
                table.column(2).search(''); // Clear filter if null
            }

            if (process) {
                table.column(3).search(process);
            } else {
                table.column(3).search(''); // Clear filter if null
            }

            if (product) {
                table.column(4).search(product);
            } else {
                table.column(4).search(''); // Clear filter if null
            }

            // Custom filtering for date range (index 1 is for date)
            $.fn.dataTable.ext.search.push(
                function(settings, data, dataIndex) {
                    let date = data[1]; // Assuming column 1 contains the date
                    let min = start_date ? new Date(start_date) : null;
                    let max = end_date ? new Date(end_date) : null;
                    let dateValue = new Date(date); // Parse the date from the table

                    if (
                        (!min || dateValue >= min) &&
                        (!max || dateValue <= max)
                    ) {
                        return true;
                    }
                    return false;
                }
            );

            // Redraw the DataTable with the new filters
            table.draw();
        });
    </script>
@endsection
