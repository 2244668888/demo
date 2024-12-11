@extends('layouts.app')
@section('title')
    PRODUCTION OUTPUT TRACEABILITY EDIT
@endsection
@section('content')
    <form action="{{ route('production_output_traceability.update', $production->id) }}" method="POST"
        enctype="multipart/form-data">
        @csrf
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <h5>PRODUCTION BUTTONS</h5>
                </div>
                <br>
                <div class="row">
                    <div class="col-lg-4 col-sm-4 col-12">
                        <div class="mt-4 d-flex">
                            <button id="play" type="button" class="btn btn-success w-100"><i
                                    class="bi bi-play-circle me-2"></i>START</button>
                        </div>
                    </div>
                    <div class="col-lg-4 col-sm-4 col-12">
                        <div class="mt-4 d-flex">
                            <button id="pause" type="button" 
                                class="btn btn-warning w-100"><i class="bi bi-pause-circle me-2"></i>PAUSE</button>
                        </div>
                    </div>
                    <div class="col-lg-4 col-sm-4 col-12">
                        <div class="mt-4 d-flex">
                            <button id="stop" type="button" class="btn btn-danger w-100"><i
                                    class="bi bi-stop-circle me-2"></i>STOP</button>
                        </div>
                    </div>
                </div>
                <div class="row mt-2">
                    <div id="msg" class="col-12 text-center"></div>
                </div>
                <br>
                <hr>
                <br>
                <div class="row">
                    <h5>PRODUCTION OUTPUT TRACEABILITY DETAILS</h5>
                </div>
                <br>
                <div class="row">
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label for="po_no" class="form-label">Production Order No</label>
                            <input type="text" readonly value="{{ $production->po_no }}" name="po_no" id="po_no"
                                class="form-control">
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label for="process" class="form-label">Process</label>
                            <input type="text" readonly value="{{ $production->process }}" name="process" id="process"
                                class="form-control">
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label for="po_date" class="form-label">Production Date</label>
                            <input type="text" readonly
                                value="{{ Carbon\Carbon::parse($production->planned_date)->format('d-m-Y') }}"
                                name="po_date" id="po_date" class="form-control">
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label for="part_no" class="form-label">Part No</label>
                            <input type="text" readonly value="{{ $production->product->part_no }}" name="part_no"
                                id="part_no" class="form-control">
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label for="part_name" class="form-label">Part Name</label>
                            <input type="text" readonly value="{{ $production->product->part_name }}" name="part_name"
                                id="part_name" class="form-control">
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label for="shift" class="form-label">Shift</label>
                            <input type="text" readonly value="{{ $production->shift }}" name="shift" id="shift"
                                class="form-control">
                        </div>
                    </div>
                </div>
                <div class="row">
                    @if ($production->machine->name == 'Assembly')
                        <div class="col-lg-3 col-sm-4 col-12">
                            <div class="mb-3">
                                <label for="machine" class="form-label">Line</label>
                                <input type="text" readonly value="{{ $production->machine->name ?? '' }}" name="machine"
                                    class="form-control">
                                <input type="hidden" value="{{ $production->machine_id }}" id="machine">
                            </div>
                        </div>
                    @else
                        <div class="col-lg-3 col-sm-4 col-12">
                            <div class="mb-3">
                                <label for="machine" class="form-label">Machine</label>
                                <select name="machine" id="machine" class="form-select">
                                    <option value="" selected disabled>Please Select</option>
                                    @foreach ($machines as $machine)
                                        @if ($machine->name != 'Assembly')
                                            <option value="{{ $machine->id }}" @selected($production->machine_id == $machine->id)>
                                                {{ $machine->name }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    @endif
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label for="leader" class="form-label">Leader Name</label>
                            <select name="leader[]" id="leader" class="form-select" multiple>
                                @php
                                    $item1 = json_decode($production->leader_name);
                                @endphp
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}"
                                        @if ($item1) {{ in_array($user->id, $item1) ? 'selected' : '' }} @endif>
                                        {{ $user->user_name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label for="user" class="form-label">Operator</label>
                            <select name="user[]" id="user" class="form-select" multiple>
                                @php
                                    $item = json_decode($production->operator);
                                @endphp
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}"
                                        @if ($item) {{ in_array($user->id, $item) ? 'selected' : '' }} @endif>
                                        {{ $user->user_name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label for="shift_length" class="form-label">Shift Length</label>
                            <input type="number" value="{{ $production->shift_length }}" name="shift_length"
                                id="shift_length" class="form-control">
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label for="purging_weight" class="form-label">Purging weight (kg)</label>
                            <input type="number" value="{{ $production->purging_weight }}" name="purging_weight"
                                id="purging_weight" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label for="planned_qty" class="form-label">Planned Qty</label>
                            <input type="text" readonly value="{{ $production->planned_qty }}" name="planned_qty"
                                id="planned_qty" class="form-control">
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label for="report_qty" class="form-label">Okay Qty</label>
                            <input type="text" readonly value="{{ $count }}" name="report_qty"
                                id="report_qty" class="form-control">
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label for="remaining_qty" class="form-label">Remaining Qty</label>
                            <input type="text" readonly value="{{ $production->planned_qty - $count }}"
                                name="remaining_qty" id="remaining_qty" class="form-control">
                        </div>
                    </div>
                </div>
                <br>
                <hr>
                <br>
                <div class="row">
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label for="planned_cycle_time" class="form-label">Planned Cycle Time(s)</label>
                            <input type="text" value="{{ $production->planned_cycle_time }}" readonly
                                name="planned_cycle_time" id="planned_cycle_time" class="form-control">
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label for="planned_qty_hr" class="form-label">Planned Qty/hr</label>
                            <input type="text"
                                value="{{ $production->planned_cycle_time > 0 ? 3600 / $production->planned_cycle_time : 0 }}"
                                readonly name="planned_qty_hr" id="planned_qty_hr" class="form-control">
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label for="actual_cycle_time" class="form-label">Actual Cycle Time(s)</label>
                            <input type="text" value="{{ $actual_time_hr > 0 ? $actual_time_hr / 3600 : 0 }}" readonly
                                name="actual_cycle_time" id="actual_cycle_time" class="form-control">
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label for="actual_qty_hr" class="form-label">Actual Qty/hr</label>
                            <input type="text" value="{{ $actual_time_hr }}" readonly name="actual_qty_hr"
                                id="actual_qty_hr" class="form-control">
                        </div>
                    </div>
                </div>
                <br>
                <div class="row">
                    <h5>RAW MATERIAL INFORMATION</h5>
                </div>
                <br>
                <div class="row">
                    <div class="col-12">
                        <div class="table-responsive">
                            <table class="table table-bordered m-0" id="productTable">
                                <thead>
                                    <tr>
                                        <th>Sr No</th>
                                        <th>Part No</th>
                                        <th>Part Name</th>
                                        <th>Type</th>
                                        <th>Variance</th>
                                        <th>Model</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if ($raw_materials)
                                        @foreach ($raw_materials as $raw_material)
                                            @php
                                                $product_ids = json_decode($raw_material->raw_part_ids);
                                            @endphp
                                            @if ($product_ids)
                                                @foreach ($product_ids as $product_id)
                                                    @php
                                                        $product = App\Models\Product::where('id', $product_id)
                                                            ->with('type_of_products')
                                                            ->first();
                                                    @endphp
                                                    <tr>
                                                        <td>{{ $loop->iteration }}</td>
                                                        <td>{{ $product->part_no }}</td>
                                                        <td>{{ $product->part_name }}</td>
                                                        <td>{{ $product->type_of_products->type ?? '' }}</td>
                                                        <td>{{ $product->variance }}</td>
                                                        <td>{{ $product->model }}</td>
                                                    </tr>
                                                @endforeach
                                            @endif
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <br>
                <div class="row">
                    <h5>SHIFT ({{ $production->shift }})</h5>
                </div>
                <br>
                <div class="row">
                    <div class="col-12">
                        <div class="table-responsive">
                            <table class="table table-bordered m-0" id="shiftTable">
                                <thead>
                                    <tr>
                                        <th>Time</th>
                                        <th>Total Qty</th>
                                        <th>Reject Qty</th>
                                        <th>Good Qty</th>
                                        <th>Remarks</th>
                                        <th>Rejection Type</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $totalMachineCount = 0;
                                        $totalRejectCount = 0;
                                    @endphp
                                    @foreach ($hourlyCounts as $time => $count)
                                        @php
                                            $totalMachineCount += $count['machine_count'];
                                            $totalRejectCount += $count['reject_count'];
                                        @endphp
                                        <tr>
                                            <td><input type="text" readonly name="hour_{{ $loop->index + 1 }}"
                                                    id="hour_{{ $loop->index + 1 }}" class="form-control time"
                                                    value="{{ $time }}"></td>
                                            @if ($production->machine->name == 'Assembly')
                                                <td><input type="number" name="total_{{ $loop->index + 1 }}"
                                                        id="total_{{ $loop->index + 1 }}"
                                                        class="form-control total_count"
                                                        value="{{ $count['machine_count'] }}"></td>
                                            @else
                                                <td><input type="text" readonly name="total_{{ $loop->index + 1 }}"
                                                        id="total_{{ $loop->index + 1 }}"
                                                        class="form-control total_count"
                                                        value="{{ $count['machine_count'] }}"></td>
                                            @endif

                                            <td><input type="text" readonly name="reject_{{ $loop->index + 1 }}"
                                                    id="reject_{{ $loop->index + 1 }}" class="form-control reject_count"
                                                    value="{{ $count['reject_count'] }}"></td>
                                            <td><input type="text" readonly name="good_{{ $loop->index + 1 }}"
                                                    id="good_{{ $loop->index + 1 }}" class="form-control good_count"
                                                    value="{{ $count['machine_count'] - $count['reject_count'] }}"></td>
                                            <td><input type="text" name="remarks_{{ $loop->index + 1 }}"
                                                    id="remarks_{{ $loop->index + 1 }}"
                                                    value="{{ $count['reject_remarks'] }}" class="form-control remarks">
                                            </td>
                                            <td><button type="button" class="btn btn-danger btn-sm openModal"
                                                    data-bs-toggle="modal" data-bs-target="#exampleModal"><i
                                                        class="bi bi-plus-circle"></i></button></td>
                                        </tr>
                                    @endforeach
                                    <tr>
                                        <td>Total</td>
                                        <td><input type="text" readonly name="total_total" id="total_total"
                                                class="form-control" value="{{ $totalMachineCount }}"></td>
                                        <td><input type="text" readonly name="total_reject" id="total_reject"
                                                class="form-control" value="{{ $totalRejectCount }}"></td>
                                        <td><input type="text" readonly name="total_good" id="total_good"
                                                class="form-control"
                                                value="{{ $totalMachineCount - $totalRejectCount }}"></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <br>
                <div class="row">
                    <h5>PRODUCTION TIME DETAILS</h5>
                </div>
                <br>
                <div class="row">
                    <div class="col-12">
                        <div class="table-responsive">
                            <table class="table table-bordered m-0" id="timeTable">
                                <thead>
                                    <tr>
                                        <th>Start DateTime</th>
                                        <th>End DateTime</th>
                                        <th>Total Time (min)</th>
                                        @if ($production->machine->name != 'Assembly')
                                            <th>Machine</th>
                                            <th>Machine Count</th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($details as $detail)
                                        <tr>
                                            <td>{{ $detail->start_time }}</td>
                                            <td>{{ $detail->end_time }}</td>
                                            <td>{{ $detail->duration }}</td>
                                            @if ($production->machine->name != 'Assembly')
                                                <td>{{ $detail->machine->name }}</td>
                                                <td>{{ $detail->machine_count }}</td>
                                            @endif
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                @if ($production->machine->name != 'Assembly')
                    <br>
                    <div class="row">
                        <h5>PRODUCTION MACHINE DETAILS</h5>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-12">
                            <div class="table-responsive">
                                <table class="table table-bordered m-0" id="machineTable">
                                    <thead>
                                        <tr>
                                            <th>Production Status</th>
                                            <th>Machine</th>
                                            <th>Start DateTime</th>
                                            <th>End DateTime</th>
                                            <th>Total Time (min)</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($sorted_details as $key => $sorted_detail)
                                            <tr>
                                                <td>
                                                    <span class="badge"
                                                        style="background: {{ $sorted_detail->color }};">{{ $sorted_detail->status }}</span>
                                                </td>
                                                <td>{{ $sorted_detail->mc_no }}</td>
                                                <td>{{ $sorted_detail->start_time }}</td>
                                                <td>{{ $sorted_detail->end_time }}</td>
                                                <td>
                                                    @php
                                                        $duration = null;
                                                        if ($sorted_detail->end_time) {
                                                            $start = \Carbon\Carbon::parse($sorted_detail->start_time);
                                                            $end = \Carbon\Carbon::parse($sorted_detail->end_time);
                                                            $duration = $start->diffInMinutes($end);
                                                        }
                                                    @endphp
                                                    @if ($duration)
                                                        {{ round($duration, 0) }}
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
            <div class="card-footer">
                <div class="row">
                    <div class="d-flex gap-2 justify-content-between col-12">
                        <a type="button" class="btn btn-info" href="{{ route('production_output_traceability.index') }}">
                            <i class="bi bi-arrow-left"></i> Back
                        </a>
                        <input type="hidden" id="storedData" name="details">
                        <button type="button" class="btn btn-primary" id="saveForm">Submit</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
    {{-- REJECTION MODAL --}}
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">REJECTION</h5>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12 d-flex justify-content-between">
                            <div>TIME: <span class="time_text"></span></div>
                            <div>Rejected Quantity: <span class="rejected_qty_text"></span></div>
                        </div>
                    </div>
                    <br>
                    <div class="table-responsive" id="popUp">
                        <table class="table table-bordered m-0 w-100" id="rejectionTable">
                            <thead>
                                <tr>
                                    <th>Rejected Qty</th>
                                    <th>Rejection Type</th>
                                    <th>Comments</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="saveModal">Done</button>
                </div>
            </div>
        </div>
    </div>
    {{-- PAUSE REMARKS MODAL --}}

    <div class="modal fade" id="exampleModal2" tabindex="-1" aria-labelledby="exampleModal1Label" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModal1Label">REMARKS</h5>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="pauseRemarks">Select Issue</label>
                        <select id="pauseRemarks" class="form-control" required>
                            <option value="">Select an issue</option>
                            <option value="Machine issue">Machine issue</option>
                            <option value="Mold issue">Mold issue</option>
                            <option value="Manpower issue">Manpower issue</option>
                            <option value="Material issue">Material issue</option>
                            <option value="Packaging issue">Packaging issue</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                    <div class="form-group mt-3 d-none" id="otherReasonContainer">
                        <label for="otherReason">Specify Other Issue</label>
                        <input type="text" id="otherReason" class="form-control" placeholder="Enter other issue">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-danger" onclick="pauseMesin()">Pause</button>
                </div>
            </div>
        </div>
    </div>



    {{-- STOP REMARKS MODAL --}}
    <div class="modal fade" id="exampleModal1" tabindex="-1" aria-labelledby="exampleModal1Label" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModal1Label">REMARKS</h5>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                    <label for="pauseRemarks">Select Issue</label>
                        <select name="" id="stopRemarks" class="form-control">
                            <option value="-1">Select A Reason</option>
                            <option value="Machine issue">Machine issue</option>
                            <option value="Mold issue">Mold issue</option>
                            <option value=" Manpower issue"> Manpower issue</option>
                            <option value="Material issue">Material issue</option>
                            <option value="Packaging issue">Packaging issue</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                        <div class="form-group mt-3 d-none" id="otherReasonContainerStop">
                        <label for="otherReasonStop">Specify Other Issue</label>
                        <input type="text" id="otherReasonStop" class="form-control" placeholder="Enter other issue">
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-danger" onclick="stopMesin()">Stop</button>
                </div>
            </div>
        </div>
    </div>
    <script>
        let timeTable;
        let productTable;
        let machineTable;
        let rejectionTable;
        let rejections = [];
        $(document).ready(function() {
            timeTable = $('#timeTable').DataTable({
                order: [
                    [0, 'desc']
                ],

                // Column definitions
                columnDefs: [{
                        orderable: false,
                        targets: '_all'
                    } // Disables ordering on the first column
                ]
            });
            productTable = $('#productTable').DataTable();
            machineTable = $('#machineTable').DataTable({
                order: [
                    [2, 'asc']
                ],

                // Column definitions
                columnDefs: [{
                        orderable: false,
                        targets: '_all'
                    } // Disables ordering on the first column
                ]
            });
            rejectionTable = $('#rejectionTable').DataTable();
            sessionStorage.clear();
            check_machines(@json($check_machines));
            var shift_rejection = @json($shift_rejection);
            shift_rejection.forEach(element => {
                let data = sessionStorage.getItem(`modalData${element.time}`);
                if (!data) {
                    data = [];
                } else {
                    data = JSON.parse(data);
                }
                let rowData = {};
                rowData['rejection'] = `${element.rt_id}`;
                rowData['comments'] = element.comments ?? '';
                rowData['qty'] = element.qty;
                rowData['time'] = element.time;
                data.push(rowData);
                sessionStorage.setItem(`modalData${element.time}`, JSON.stringify(data));
            });
            rejections = @json($type_of_rejections);
        });
        setTimeout(() => {
            MachineCount();
        }, 15000);

        function MachineCount(){
            $machine_no = $('#machine option:selected').text();

            $.ajax({
                url: "{{ route('production_output_traceability.machine_count')}}", // replace with your endpoint URL
                method: 'GET',
                data: {
                    mc_no: $machine_no,
                },
                success: function(response) {

                    console.log('Data sent successfully:', response);

                                const timeIntervals = [
                    { range: "8AM-9AM", start: "08:00:00", end: "09:00:00" },
                    { range: "9AM-10AM", start: "09:00:00", end: "10:00:00" },
                    { range: "10AM-11AM", start: "10:00:00", end: "11:00:00" },
                    { range: "11AM-12PM", start: "11:00:00", end: "12:00:00" },
                    { range: "12PM-1PM", start: "12:00:00", end: "13:00:00" },
                    { range: "1PM-2PM", start: "13:00:00", end: "14:00:00" },
                    { range: "2PM-3PM", start: "14:00:00", end: "15:00:00" },
                    { range: "3PM-4PM", start: "15:00:00", end: "16:00:00" },
                    { range: "4PM-5PM", start: "16:00:00", end: "17:00:00" },
                    { range: "5PM-6PM", start: "17:00:00", end: "18:00:00" },
                    { range: "6PM-7PM", start: "18:00:00", end: "19:00:00" },
                    { range: "7PM-8PM", start: "19:00:00", end: "20:00:00" },
                ];

                const totals = {};
                timeIntervals.forEach((interval) => {
                    totals[interval.range] = 0;
                });

                response.machineCount.forEach((entry) => {
                    const datetimeParts = entry.datetime.split(" "); 
                    const time = datetimeParts[1]; 
                    const period = datetimeParts[2]; 

                    const fullTime = convertTo24Hour(time, period);

                    timeIntervals.forEach((interval) => {
                        if (fullTime >= interval.start && fullTime < interval.end) {
                            if (totals[interval.range] == '0') {
                                totals[interval.range] = entry.count;
                            }else{
                                var totalprevCount = +totals[interval.range];
                                totals[interval.range] = (totalprevCount) + (+entry.count);
                            }
                        }
                    });
                });

                $("#shiftTable tr").each(function () {
                    const timeRange = $(this).find(".time").val();
                    if (totals[timeRange] !== undefined) {
                        if ($(this).find(".total_count").val() != '0') {
                             var prevValue = +$(this).find(".total_count").val();
                        $(this).find(".total_count").val(prevValue+(+totals[timeRange]));
                            
                        }else{
                            $(this).find(".total_count").val('')
                            $(this).find(".total_count").val(totals[timeRange]);
                        }
                    }
                });
}
});

 

            
        }

        function convertTo24Hour(time, period) {
        const [hours, minutes, seconds] = time.split(":");
        let hour = parseInt(hours);

        if (period === "PM" && hour !== 12) {
            hour += 12;
        } else if (period === "AM" && hour === 12) {
            hour = 0;
        }

        return `${hour.toString().padStart(2, "0")}:${minutes}:${seconds}`;
    }

        // REJECTION WORK
        function addRow(button) {
            // Clone the row and get the data from it
            var row = button.parentNode.parentNode.cloneNode(true);
            var rowData = $(row).find('td').map(function() {
                return $(this).html();
            }).get();

            // Add the cloned data as a new row in DataTable
            rejectionTable.row.add(rowData).draw(false);

            // Trigger any additional required events
            $('.qty').trigger('keyup');
        }

        function removeRow(button) {
            // Check if there is more than one row
            if ($('#rejectionTable tr').length > 2) { // Including header row
                // Find the row index and remove it
                rejectionTable.row($(button).closest('tr')).remove().draw(false);
            } else {
                $('#popUp').prepend(`
                    <div class="alert border-warning alert-dismissible fade show text-warning" role="alert">
                    <b>Warning!</b> Can't remove Row!.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                `);
            }

            // Trigger any additional required events
            $('.qty').trigger('keyup');
        }

        $('#stopRemarks').on('change', function() {
            var selectedValue = $(this).val();
            if (selectedValue === 'Other') {
                $('#otherReasonContainerStop').removeClass('d-none'); 
            } else {
                $('#otherReasonContainerStop').addClass('d-none'); 
            }
        });

        $(document).on('click', '.openModal', function() {
            let hiddenId = $(this).closest('tr').find('.time').val();
            let storedData = sessionStorage.getItem(`modalData${hiddenId}`);

            // Clear existing rows in the table
            rejectionTable.clear().draw();

            if (storedData) {
                storedData = JSON.parse(storedData);
                storedData.forEach(element => {
                    let optionsHtml = '';
                    rejections.forEach(rejection => {
                        let selected = '';
                        if (element.rejection === rejection.id) {
                            selected = 'selected';
                        }
                        optionsHtml +=
                            `<option value="${rejection.id}" ${selected}>${rejection.type}</option>`;
                    });

                    rejectionTable.row.add([
                        `<input type="number" class="form-control qty" value="${element.qty}">`,
                        `<select class="form-control rejection_type">${optionsHtml}</select>`,
                        `<input type="text" class="form-control comments" value="${element.comments}">`,
                        `<button type="button" class="btn btn-success btn-sm me-2" onclick="addRow(this)"><i class="bi bi-plus"></i></button><button type="button" class="btn btn-danger btn-sm" onclick="removeRow(this)"><i class="bi bi-dash"></i></button>`
                    ]).draw(false);
                });
            } else {
                let optionsHtml = '';
                rejections.forEach(rejection => {
                    optionsHtml +=
                        `<option value="${rejection.id}">${rejection.type}</option>`;
                });

                rejectionTable.row.add([
                    `<input type="number" class="form-control qty" value="">`,
                    `<select class="form-control rejection_type">${optionsHtml}</select>`,
                    `<input type="text" class="form-control comments" value="">`,
                    `<button type="button" class="btn btn-success btn-sm me-2" onclick="addRow(this)"><i class="bi bi-plus"></i></button><button type="button" class="btn btn-danger btn-sm" onclick="removeRow(this)"><i class="bi bi-dash"></i></button>`
                ]).draw(false);
            }

            $('.time_text').text(hiddenId);
            $('.qty').trigger('keyup');
        });

        $('#saveModal').on('click', function() {
            $('#exampleModal').modal('hide');
            let time = $('.time_text').text();
            let data = [];
            $('#rejectionTable tbody tr').each(function() {
                let rowData = {};
                rowData['rejection'] = $(this).find('.rejection_type').val();
                rowData['comments'] = $(this).find('.comments').val();
                rowData['qty'] = $(this).find('.qty').val();
                rowData['time'] = time;
                data.push(rowData);
            });
            sessionStorage.setItem(`modalData${time}`, JSON.stringify(data));
            $('#shiftTable tbody tr').each(function() {
                if ($(this).find('.time').val() == time) {
                    let total_qty = $('.rejected_qty_text').text();
                    $(this).closest('tr').find('.reject_count').val(total_qty).trigger('change');
                }
            });

            $.ajax({
                url: '{{ route('production_output_traceability.rejection') }}', // replace with your endpoint URL
                method: 'POST',
                data: {
                    id: @json($production->id),
                    rejection_data: data, // Sending the array as data
                    _token: $('meta[name="csrf-token"]').attr('content') // CSRF token for Laravel
                },
                success: function(response) {
                    console.log('Data sent successfully:', response);
                }
            });
        });

        $(document).on('keyup change', '.qty', function() {
            let total = 0;
            $('#rejectionTable .qty').each(function() {
                total += +$(this).val();
            });
            $('.rejected_qty_text').text(total);
        });

        $(document).on('change', '.reject_count, .total_count', function() {
            let total_count = $(this).closest('tr').find('.total_count').val();
            let reject_count = $(this).closest('tr').find('.reject_count').val();
            $(this).closest('tr').find('.good_count').val(total_count - reject_count).trigger('change');
            let total_reject = 0;
            $('#shiftTable .reject_count').each(function() {
                total_reject += +$(this).val();
            });
            $('#total_reject').val(total_reject);
        });

        $(document).on('change', '.good_count', function() {
            let total_good = 0;
            $('#shiftTable .good_count').each(function() {
                total_good += +$(this).val();
            });
            $('#total_good').val(total_good);
        });

        $(document).on('change', '.total_count', function() {
            let total_count = 0;
            $('#shiftTable .total_count').each(function() {
                total_count += +$(this).val();
            });
            $('#total_total').val(total_count);
        });

        $('#saveForm').on('click', function() {
            let array = [];
            $('.time').each(function() {
                let storedData = sessionStorage.getItem(`modalData${$(this).val()}`);
                if (storedData == null) {
                    storedData = `{"time":"${$(this).val()}"}`;
                }
                array.push(JSON.parse(storedData));
            });
            $('#storedData').val(JSON.stringify(array));
            $(this).closest('form').submit();
        });

        //MACHINE WORK
        $('#play').on('click', function() {
            let operator = $('#user').val();
            if (operator.length > 0) {
                machineStarter(1, @json($production->id));
            } else {
                alert("Can`t Start Without Operator(s)!");
            }
        });

        $('#pause').on('click', function() {
            $('#exampleModal2').modal('show');
        });

  $('#pauseRemarks').on('change', function() {
            var selectedValue = $(this).val();
            if (selectedValue === 'Other') {
                $('#otherReasonContainer').removeClass('d-none'); 
            } else {
                $('#otherReasonContainer').addClass('d-none'); 
        }
        });
        function pauseMesin() {
            var remarks = $('#pauseRemarks').val();
            if (remarks == 'Other') {
                var remarks = $('#otherReason').val();
            }
            if (!remarks || remarks.trim() === '') {
                alert("Can't Stop Without Remarks!");
            } else if (remarks.length > 50) {
                alert("Remarks must be 50 characters or less.");
            } else {
                $('#exampleModal2').modal('hide');
                machineStarter(2, @json($production->id));
            }
        }


        $('#stop').on('click', function() {
            $('#exampleModal1').modal('show');
        });

        function stopMesin() {
            var remarks = $('#stopRemarks').val();
            if (remarks == 'Other') {
                var remarks = $('#otherReasonStop').val();
            }
            if (!remarks || remarks.trim() === '') {
                alert("Can't Stop Without option selected!");
            } else if (remarks.length > 50) {
                alert("Remarks must be 50 characters or less.");
            } else {
                $('#exampleModal1').modal('hide');
                machineStarter(3, @json($production->id));
            }
        }

        function check_machines(check_machines) {
            if (check_machines != null) {
                if (check_machines.status == 1) {
                    $('#play').attr('disabled', 'disabled');
                    $('#pause').removeAttr('disabled');
                    $('#stop').removeAttr('disabled');
                    $('#play').removeClass('btn-success');
                    $('#pause').addClass('btn-warning');
                    $('#stop').addClass('btn-danger');
                } else if (check_machines.status == 2) {
                    $('#play').removeAttr('disabled');
                    $('#pause').attr('disabled', 'disabled');
                    $('#stop').attr('disabled', 'disabled');
                    $('#play').addClass('btn-success');
                    $('#pause').removeClass('btn-warning');
                    $('#stop').removeClass('btn-danger');
                } else if (check_machines.status == 3) {
                    $('#play').attr('disabled', 'disabled');
                    $('#pause').attr('disabled', 'disabled');
                    $('#stop').attr('disabled', 'disabled');
                    $('#play').removeClass('btn-success');
                    $('#pause').removeClass('btn-warning');
                    $('#stop').removeClass('btn-danger');
                }
            } else {
                $('#play').removeAttr('disabled');
                $('#pause').attr('disabled', 'disabled');
                $('#stop').attr('disabled', 'disabled');
                $('#play').addClass('btn-success');
                $('#pause').removeClass('btn-warning');
                $('#stop').removeClass('btn-danger');
            }
        }

        function machineStarter(status, production_id) {
            var machine = $("#machine").val();

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            var stop = $('#stopRemarks').val();
            if (stop == 'Other') {
                var stop = $('#otherReasonStop').val();
            }

            var remarks = $('#pauseRemarks').val();
            if (!stop && remarks != null && remarks == 'Other') {
                var remarks = $('#otherReason').val();
            }else{
                remarks = stop;
            }
            $.ajax({
                type: 'POST',
                url: '{{ route('production_output_traceability.starter') }}',
                data: {
                    "production_id": production_id,
                    "machine": machine,
                    "status": status,
                    "remarks":remarks,
                    "operator": $('#user').val(),
                },
                success: function(data) {
                    $("#msg").html(data.message);
                    check_machines(data.check_machine);
                    timeTable.clear().draw();
                    machineTable.clear().draw();
                    data.details.forEach(function(detail, index) {
                        var statusBadge;
                        if (detail.status == 1) {
                            statusBadge =
                                '<span class="badge border border-success text-success">Started</span>';
                        } else if (detail.status == 2) {
                            statusBadge =
                                '<span class="badge border border-warning text-warning">Paused</span>';
                        } else if (detail.status == 3) {
                            statusBadge =
                                '<span class="badge border border-danger text-danger">Stopped</span>';
                        } else {
                            statusBadge =
                                '<span class="badge border border-secondary text-secondary">Not-initiated</span>';
                        }

                        var start_time = detail.start_time;
                        var end_time = (detail.end_time != null) ? detail.end_time : '';
                        var duration = (detail.duration != null) ? detail.duration : '';
                        var machine = detail.machine.name;
                        var count = detail.machine_count;

                        if (machine == 'Assembly') {
                            timeTable.row.add([
                                start_time,
                                end_time,
                                duration
                            ]).draw();
                        } else {
                            timeTable.row.add([
                                start_time,
                                end_time,
                                duration,
                                machine,
                                count
                            ]).draw();

                            machineTable.row.add([
                                statusBadge,
                                machine,
                                start_time,
                                end_time,
                                duration
                            ]).draw();
                        }
                    });
                    if (status == 3) {
                        window.location.href = "{{ route('production_output_traceability.index') }}";
                    }
                }
            });
        }
    </script>
@endsection
