@extends('layouts.app')
@section('title')
    PRODUCTION OUTPUT TRACEABILITY QC
@endsection
@section('content')
    <div class="card">
        <form action="{{ route('production_output_traceability.qc_update', $production->id) }}" method="POST"
            enctype="multipart/form-data">
            @csrf
            <div class="card-body">
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
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label for="machine" class="form-label">
                                @if ($production->machine->name != 'Assembly')
                                    Machine
                                @else
                                    Line
                                @endif
                            </label>
                            <input type="text" readonly value="{{ $production->machine->name ?? '' }}" name="machine"
                                id="machine" class="form-control">
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label for="user" class="form-label">Operator</label>
                            <select disabled name="user[]" id="user" class="form-select" multiple>
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
                            <input type="number" readonly value="{{ $production->shift_length }}" name="shift_length"
                                id="shift_length" class="form-control">
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label for="purging_weight" class="form-label">Purging weight (kg)</label>
                            <input type="number" readonly value="{{ $production->purging_weight }}" name="purging_weight"
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
                                            <td><input type="text" readonly name="total_{{ $loop->index + 1 }}"
                                                    id="total_{{ $loop->index + 1 }}" class="form-control total_count"
                                                    value="{{ $count['machine_count'] }}"></td>
                                            <td><input type="text" readonly name="reject_{{ $loop->index + 1 }}"
                                                    id="reject_{{ $loop->index + 1 }}" class="form-control reject_count"
                                                    value="{{ $count['reject_count'] }}"></td>
                                            <td><input type="text" readonly name="good_{{ $loop->index + 1 }}"
                                                    id="good_{{ $loop->index + 1 }}" class="form-control good_count"
                                                    value="{{ $count['machine_count'] - $count['reject_count'] }}"></td>
                                            <td><input type="text" readonly name="remarks_{{ $loop->index + 1 }}"
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
                                    <tr>
                                        <td>Total (By QC)</td>
                                        <td><input type="text" readonly name="qc_total_total" id="qc_total_total"
                                                class="form-control" value="{{ $totalMachineCount }}"></td>
                                        <td><input type="text" readonly name="qc_total_reject" id="qc_total_reject"
                                                class="form-control" value="0"></td>
                                        <td><input type="text" readonly name="qc_total_good" id="qc_total_good"
                                                class="form-control" value="{{ $totalMachineCount }}"></td>
                                        <td><button type="button" class="btn btn-danger btn-sm openModal1"
                                                data-bs-toggle="modal" data-bs-target="#exampleModal1"><i
                                                    class="bi bi-plus-circle"></i></button></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>Grand Total</td>
                                        <td><input type="text" readonly name="" id="qc_total_total_total"
                                                class="form-control" value="{{ $totalMachineCount }}"></td>
                                        <td><input type="text" readonly name="" id="qc_total_reject_total"
                                                class="form-control" value="{{$production->qc_total_reject + $totalRejectCount}}"></td>
                                        <td><input type="text" readonly name="" id="qc_total_good_total"
                                                class="form-control" value="{{ $totalMachineCount - ($production->qc_total_reject + $totalRejectCount) }}"></td>
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
                        <input type="hidden" id="storedData" name="details">
                        <a type="button" class="btn btn-info" href="{{ route('production_output_traceability.index') }}">
                            <i class="bi bi-arrow-left"></i> Back
                        </a>
                        <button type="button" class="btn btn-primary" id="saveForm">Submit</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
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
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    {{-- QC REJECTION MODAL --}}
    <div class="modal fade" id="exampleModal1" tabindex="-1" aria-labelledby="exampleModal1Label" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModal1Label">REJECTION</h5>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12 d-flex justify-content-between">
                            <div>Rejected Quantity: <span class="rejected_qty_text1"></span></div>
                        </div>
                    </div>
                    <br>
                    <div class="table-responsive" id="popUp1">
                        <table class="table table-bordered m-0 w-100" id="rejectionTable1">
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
    <script>
        let timeTable;
        let productTable;
        let machineTable;
        let rejectionTable;
        let rejectionTable1;
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
            rejectionTable1 = $('#rejectionTable1').DataTable();
            sessionStorage.clear();
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

        // REJECTION WORK
        function addRow(button) {
            // Clone the row and get the data from it
            var row = button.parentNode.parentNode.cloneNode(true);
            var rowData = $(row).find('td').map(function() {
                return $(this).html();
            }).get();

            // Add the cloned data as a new row in DataTable
            rejectionTable1.row.add(rowData).draw(false);

            // Trigger any additional required events
            $('.qty1').trigger('keyup');
        }

        function removeRow(button) {
            // Check if there is more than one row
            if ($('#rejectionTable tr').length > 2) { // Including header row
                // Find the row index and remove it
                rejectionTable1.row($(button).closest('tr')).remove().draw(false);
            } else {
                $('#popUp1').prepend(`
                    <div class="alert border-warning alert-dismissible fade show text-warning" role="alert">
                    <b>Warning!</b> Can't remove Row!.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                `);
            }

            // Trigger any additional required events
            $('.qty1').trigger('keyup');
        }

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
                        `<input type="number" readonly class="form-control qty" value="${element.qty}">`,
                        `<select disabled class="form-control rejection_type">${optionsHtml}</select>`,
                        `<input type="text" readonly class="form-control comments" value="${element.comments}">`
                    ]).draw(false);
                });
            } else {
                let optionsHtml = '';
                rejections.forEach(rejection => {
                    optionsHtml +=
                        `<option value="${rejection.id}">${rejection.type}</option>`;
                });

                rejectionTable.row.add([
                    `<input readonly type="number" class="form-control qty" value="">`,
                    `<select disabled class="form-control rejection_type">${optionsHtml}</select>`,
                    `<input readonly type="text" class="form-control comments" value="">`
                ]).draw(false);
            }

            $('.time_text').text(hiddenId);
            $('.qty').trigger('keyup');
        });

        $(document).on('click', '.openModal1', function() {
            let storedData1 = sessionStorage.getItem(`modalData1`);

            // Clear existing rows in the table
            rejectionTable1.clear().draw();

            if (storedData1) {
                storedData1 = JSON.parse(storedData1);
                storedData1.forEach(element => {
                    let optionsHtml = '';
                    rejections.forEach(rejection => {
                        let selected = '';
                        if (element.rejection === rejection.id) {
                            selected = 'selected';
                        }
                        optionsHtml +=
                            `<option value="${rejection.id}" ${selected}>${rejection.type}</option>`;
                    });

                    rejectionTable1.row.add([
                        `<input type="number" class="form-control qty1" value="${element.qty}">`,
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

                rejectionTable1.row.add([
                    `<input type="number" class="form-control qty1" value="">`,
                    `<select class="form-control rejection_type">${optionsHtml}</select>`,
                    `<input type="text" class="form-control comments" value="">`,
                    `<button type="button" class="btn btn-success btn-sm me-2" onclick="addRow(this)"><i class="bi bi-plus"></i></button><button type="button" class="btn btn-danger btn-sm" onclick="removeRow(this)"><i class="bi bi-dash"></i></button>`
                ]).draw(false);
            }

            $('.qty1').trigger('keyup');
        });

        $('#saveModal').on('click', function() {
            $('#exampleModal1').modal('hide');
            let data = [];
            $('#rejectionTable1 tbody tr').each(function() {
                let rowData = {};
                rowData['rejection'] = $(this).find('.rejection_type').val();
                rowData['comments'] = $(this).find('.comments').val();
                rowData['qty'] = $(this).find('.qty1').val();
                rowData['time'] = 1;
                data.push(rowData);
            });
            sessionStorage.setItem(`modalData1`, JSON.stringify(data));
            let total_qty = $('.rejected_qty_text1').text();
            $('#qc_total_reject').val(total_qty).trigger('change');

            var totalReject = $('#total_reject').val();
            var totalQcReject = $('#qc_total_reject').val();
            var totalQc = $('#qc_total_total_total').val();
            
            var total = (+totalReject) + (+totalQcReject);
            var goodCount = (+totalQc) - (+total);
           $('#qc_total_reject_total').val(total);
           $('#qc_total_good_total').val(goodCount);
        });

        $(document).on('change', '#qc_total_reject', function() {
            let total_count = $('#qc_total_total').val();
            let reject_count = $(this).val();
            $('#qc_total_good').val(total_count - reject_count);
        });

        $(document).on('keyup change', '.qty', function() {
            let total = 0;
            $('#rejectionTable .qty').each(function() {
                total += +$(this).val();
            });
            $('.rejected_qty_text').text(total);
        });

        $(document).on('keyup change', '.qty1', function() {
            let total1 = 0;
            $('#rejectionTable1 .qty1').each(function() {
                total1 += +$(this).val();
            });
            $('.rejected_qty_text1').text(total1);
        });

        $('#saveForm').on('click', function() {
            let array = [];
            let storedData = sessionStorage.getItem(`modalData1`);
            array.push(JSON.parse(storedData));
            $('#storedData').val(JSON.stringify(array));
            $(this).closest('form').submit();
        });
    </script>
@endsection
