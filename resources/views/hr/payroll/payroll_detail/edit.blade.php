@extends('layouts.app')
@section('title')
    PAYROLL DETAIL EDIT
@endsection
@section('content')
    <style>
        #mainTable input {
            width: 100px;
        }
        .table thead tr input {
            background: transparent;
            color: white;

        }
    </style>
    {{-- <link rel="stylesheet" href="{{ asset('assets/vendor/daterange/daterange.css') }}" /> --}}
    <div class="card">
        <form method="post" action="{{ route('payroll_detail.update', $payroll_detail->id) }}" enctype="multipart/form-data">
            @csrf
            <div class="card-body">
                <div class="row">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>NET SALARY</th>
                                    <th>GROSS SALARY</th>
                                    <th>TOTAL DEDUCTION</th>
                                    <th>COMPANY CONTRIBUTION</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>{{ $payroll_detail->net_salary }}</td>
                                    <td>{{ $payroll_detail->gross_salary }}</td>
                                    <td>{{ $payroll_detail->total_deduction }}</td>
                                    <td>{{ $payroll_detail->company_contribution }}</td>
                                </tr>
                            </tbody>

                        </table>
                    </div>
                </div>
                <div class="row">
                    <h5>GENERAL DETAILS</h5>
                </div>
                <br>
                <div class="row">
                    <div class="col-lg-4 col-sm-4 col-12">
                        <div class="mb-3">
                            <label for="created_by" class="form-label">Ref No</label>
                            <input type="text" readonly name="created_by" id="created_by" class="form-control"
                                value="{{ $payroll_detail->ref_no }}">
                        </div>
                    </div>
                    <div class="col-lg-4 col-sm-4 col-12">
                        <div class="mb-3">
                            <label for="order_no" class="form-label">Payroll Date</label>
                            {{-- <div class="input-group">
                                <span class="input-group-text">
                                    <i class="bi bi-calendar4"></i>
                                </span>
                                <input type="text" id="abc3" name="date" value="{{ $payroll_detail->date }}" class="form-control datepicker-current-date">
                            </div> --}}
                            <input type="date"  name="date" id="order_no" class="form-control"
                                value="{{  \Carbon\Carbon::parse($payroll_detail->date)->format('Y-m-d') }}">
                        </div>
                    </div>
                    <div class="col-lg-4 col-sm-4 col-12">
                        <div class="mb-3">
                            <label for="date" class="form-label">Payroll Month</label>
                            <input type="text" readonly name="date" id="date" class="form-control"
                                value="{{ $payroll->month }} ,{{ $payroll->year  }}">
                        </div>
                    </div>

                    <div class="col-lg-4 col-sm-4 col-12">
                        <div class="mb-3">
                            <label for="date" class="form-label">Emp ID</label>
                            <input type="text" readonly name="date" id="date" class="form-control"
                                value="{{ $payroll_detail->user->code ?? '' }}">
                        </div>
                    </div>

                    <div class="col-lg-4 col-sm-4 col-12">
                        <div class="mb-3">
                            <label for="date" class="form-label">Name</label>
                            <input type="text" readonly name="date" id="date" class="form-control"
                                value="{{ $payroll_detail->user->user_name ?? '' }}">
                                <input type="hidden" name="user_id" value="{{ $payroll_detail->user_id }}">
                        </div>
                    </div>

                    <div class="col-lg-4 col-sm-4 col-12">
                        <div class="mb-3">
                            {{-- @dd($payroll_detail) --}}
                            <label for="date" class="form-label">Created By</label>
                            <input type="text" readonly name="date" id="date" class="form-control"
                                value="{{ $payroll_detail->created_by_user->user_name ?? '' }}">
                        </div>
                    </div>

                    <div class="col-lg-4 col-sm-4 col-12">
                        <div class="mb-3">
                            <label for="date" class="form-label">Basic Salary</label>
                            <input type="text" readonly name="date" id="date" class="form-control"
                                value="{{ isset($payroll_detail->user->personalUser) ? $payroll_detail->user->personalUser->base_salary : '' }}">
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <h5 class="mb-2">Other Details</h5>

                        <div class="col-lg-4 col-sm-4 col-12">
                            <div class="mb-3">
                            <label for="date" class="form-label">Other Details</label>
                            <textarea name="remarks" id="" cols="30" rows="1" class="form-control">{{ $payroll_detail->remarks }}</textarea>
                        </div>
                    </div>

                    <div class="col-lg-4 col-sm-4 col-12">
                        <div class="mb-3">
                            <label for="date" class="form-label">Attachment</label>
                            <div class="d-flex">
                                <input type="file" name="attachment" id="date" class="form-control" @if ($payroll_detail->attachment != null || $payroll_detail->attachment != '') style="width:80%;" @endif >
                                @if ($payroll_detail->attachment != null || $payroll_detail->attachment != '')
                                                        <a target="_blank"
                                                            href="{{ asset('/payroll-detail-attachments/') }}/{{ $payroll_detail->attachment }}"
                                                            class="btn btn-outline-secondary mx-1" type="button"
                                                            id="inputGroupFileAddon03">
                                                            <i class="bi bi-file-text"></i>
                                                        </a>
                                                </div>
                                                <div>{{ substr($payroll_detail->attachment, 14) }}</div>
                                    @endif
                            </div>
                        </div>
                    </div>

                <br>
                <div class="row">
                    <h5 class="mb-2">Include in Pay Slip</h5>
                    <br>
                        <div class="col-md-6">
                            <div class="d-flex mb-2">
                                <h5>EPF</h5>
                                {{-- <div class="mt-4 d-flex"> --}}
                                <label class=" mx-2" for="hrdf">(
                                    NO</label>
                                {{-- <div class="d-flex align-items-center"> --}}
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox"
                                        id="hrdf" name="kwsp"
                                        @checked($payroll_detail->kwsp ?? 0) value="1">
                                </div>
                                {{-- </div> --}}
                                <label class="form-check-label" for="hrdf">YES
                                    )</label>
                                {{-- </div> --}}
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="d-flex mb-2">
                                <h5>EIS</h5>
                                {{-- <div class="mt-4 d-flex"> --}}
                                <label class=" mx-2" for="hrdf">(
                                    NO</label>
                                {{-- <div class="d-flex align-items-center"> --}}
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox"
                                        id="hrdf" name="eis"
                                        @checked($payroll_detail->eis ?? 0) value="1">
                                </div>
                                {{-- </div> --}}
                                <label class="form-check-label" for="hrdf">YES
                                    )</label>
                                {{-- </div> --}}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex mb-2">
                                <h5>SOCSO</h5>
                                {{-- <div class="mt-4 d-flex"> --}}
                                <label class=" mx-2" for="hrdf">(
                                    NO</label>
                                {{-- <div class="d-flex align-items-center"> --}}
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox"
                                        id="hrdf" name="socso"
                                        @checked($payroll_detail->socso ?? 0) value="1">
                                </div>
                                {{-- </div> --}}
                                <label class="form-check-label" for="hrdf">YES
                                    )</label>
                                {{-- </div> --}}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex mb-2">
                                <h5>HRDF</h5>
                                {{-- <div class="mt-4 d-flex"> --}}
                                <label class=" mx-2" for="hrdf">(
                                    NO</label>
                                {{-- <div class="d-flex align-items-center"> --}}
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox"
                                        id="hrdf" name="hrdf"
                                        @checked($payroll_detail->hrdf ?? 0) value="1">
                                </div>
                                {{-- </div> --}}
                                <label class="form-check-label" for="hrdf">YES
                                    )</label>
                                {{-- </div> --}}
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="d-flex mb-2">
                                <h5>Employee Voluntary Excess</h5>
                                {{-- <div class="mt-4 d-flex"> --}}
                                <label class=" mx-2" for="eve">(
                                    NO</label>
                                {{-- <div class="d-flex align-items-center"> --}}
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox"
                                        id="eve" name="eve_employee"
                                        @checked($payroll_detail->eve_employee ?? 0) value="1">
                                </div>
                                {{-- </div> --}}
                                <label class="form-check-label" for="eve">YES
                                    )</label>
                                {{-- </div> --}}
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="d-flex mb-2">
                                <h5>Employer Voluntary Excess</h5>
                                {{-- <div class="mt-4 d-flex"> --}}
                                <label class=" mx-2" for="hrdf">(
                                    NO</label>
                                {{-- <div class="d-flex align-items-center"> --}}
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox"
                                        id="hrdf" name="eve_employer"
                                        @checked($payroll_detail->eve_employer ?? 0) value="1">
                                </div>
                                {{-- </div> --}}
                                <label class="form-check-label" for="hrdf">YES
                                    )</label>
                                {{-- </div> --}}
                            </div>
                        </div>
                </div>
                <div class="row mt-3">
                                                            <div class="col-md-6">
                                                                <div class="d-flex mb-2">
                                                                    <h5>Employee Voluntary Excess & Over Time</h5>
                                                                    {{-- <div class="mt-4 d-flex"> --}}
                                                                    <label class="mx-2" for="eve_ot">(
                                                                        NO</label>
                                                                    {{-- <div class="d-flex align-items-center"> --}}
                                                                    <div class="form-check form-switch">
                                                                        <input class="form-check-input" type="checkbox"
                                                                            id="eve_ot" name="eve_ot"
                                                                            @checked($payroll_detail->eve_ot ?? 0) value="1">
                                                                    </div>
                                                                    {{-- </div> --}}
                                                                    <label class="form-check-label" for="eve_ot">YES
                                                                        )</label>
                                                                    {{-- </div> --}}
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="d-flex mb-2">
                                                                    <h5>Employee Voluntary Excess & Over Time & Allowance</h5>
                                                                    {{-- <div class="mt-4 d-flex"> --}}
                                                                    <label class="mx-2" for="eve_ot_allowance">(
                                                                        NO</label>
                                                                    {{-- <div class="d-flex align-items-center"> --}}
                                                                    <div class="form-check form-switch">
                                                                        <input class="form-check-input" type="checkbox"
                                                                            id="eve_ot_allowance" name="eve_ot_allowance"
                                                                            @checked($payroll_detail->eve_ot_allowance ?? 0) value="1">
                                                                    </div>
                                                                    {{-- </div> --}}
                                                                    <label class="form-check-label" for="eve_ot_allowance">YES
                                                                        )</label>
                                                                    {{-- </div> --}}
                                                                </div>
                                                            </div>          
                                                        </div>

                <br>

                <div class="row d-flex flex-row-reverse">

                    <div class="col-md-4 d-flex flex-row-reverse">
                        <button type="button" class="btn btn-primary" id="addRow">
                            <i class="bi bi-plus"></i> Add
                        </button>
                    </div>
                </div>
                <div class="row">
                    <h5>Additional Items</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered m-0 datatable" id="additional_item_table">
                            <thead>
                                <tr>
                                    <th>Sr No</th>
                                    <th>Particulars</th>
                                    <th>Value (RM)</th>
                                    <th>Include in Pay Slip</th>
                                    <th>Action</th>
                                </tr>
                            </thead>

                            <tbody>
                               {{-- @dd($payroll_detail_childs) --}}

                               @if (!empty($payroll_detail_childs) && $payroll_detail_childs->isNotEmpty())
                                @foreach ($payroll_detail_childs as $key => $payroll_detail_child)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td><input type="text" @if ($loop->iteration != 5)
                                        readonly
                                    @endif  name="item[{{ $loop->iteration }}][particular]" value="{{ $payroll_detail_child['particular'] }}" class="form-control"></td>
                                    <td><input type="number" name="item[{{ $loop->iteration }}][value]" value="{{ $payroll_detail_child['value'] }}" class="form-control"></td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                    <label class=" mx-2" for="hrdf">(
                                        NO</label>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox"
                                            id="hrdf" name="item[{{ $loop->iteration }}][checkbox]"
                                            @checked($payroll_detail_child['checkbox'] ?? 0) value="1">
                                    </div>
                                    <label class="form-check-label" for="hrdf">YES
                                        )</label>
                                        </div>
                                    </td>
                                    <td>@if ($loop->iteration == 5 ||$loop->iteration > 5)
                                    <button type="button" class="btn btn-sm btn-danger remove-row"><i class="bi bi-trash"></i></button>@endif</td>
                                </tr>

                                @endforeach
                                @else
                                <tr>
                                    <td>1</td>
                                    <td> <input type="text" readonly name="item[1][particular]" value="Allowance" class="form-control"></td>
                                    <td><input type="number" name="item[1][value]" class="form-control"></td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                    <label class=" mx-2" for="hrdf">(
                                        NO</label>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox"
                                            id="hrdf" name="item[1][checkbox]"
                                             value="1">
                                    </div>
                                    <label class="form-check-label" for="hrdf">YES
                                        )</label>
                                        </div>
                                    </td>
                                    <td></td>
                                </tr>

                                <tr>
                                    <td>2</td>
                                    <td> <input type="text" readonly name="item[2][particular]" value="Advance Salary" class="form-control"></td>
                                    <td><input type="number" name="item[2][value]" class="form-control"></td>
                                    <td>
                                       <div class="d-flex align-items-center">
                                    <label class=" mx-2" for="hrdf">(
                                        NO</label>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox"
                                            id="hrdf" name="item[2][checkbox]"
                                            value="1">
                                    </div>
                                    <label class="form-check-label" for="hrdf">YES
                                        )</label>
                                        </div>
                                    </td>
                                    <td></td>
                                </tr>

                                <tr>
                                    <td>3</td>
                                    <td> <input type="text" readonly name="item[3][particular]" value="Claim" class="form-control"></td>
                                    <td><input type="number" name="item[3][value]" class="form-control"></td>
                                    <td>
                                       <div class="d-flex align-items-center">
                                    <label class=" mx-2" for="hrdf">(
                                        NO</label>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox"
                                            id="hrdf" name="item[3][checkbox]"
                                             value="1">
                                    </div>
                                    <label class="form-check-label" for="hrdf">YES
                                        )</label>
                                        </div>
                                    </td>
                                    <td></td>
                                </tr>


                                <tr>
                                    <td>4</td>
                                    <td><input type="text" readonly name="item[4][particular]" value="Overtime" class="form-control"></td>
                                    <td><input type="number" name="item[4][value]" class="form-control"></td>
                                    <td>
                                       <div class="d-flex align-items-center">
                                    <label class=" mx-2" for="hrdf">(
                                        NO</label>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox"
                                            id="hrdf" name="item[4][checkbox]"
                                             value="1">
                                    </div>
                                    <label class="form-check-label" for="hrdf">YES
                                        )</label>
                                        </div>
                                    </td>
                                    <td></td>
                                </tr>


                               @endif

                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="card-footer">
                    <div class="row">
                        <div class="d-flex gap-2 justify-content-between col-12">
                            <a type="button" class="btn btn-info" href="{{ route('payroll.edit', ['id' => $payroll->id]) }}">
                                <i class="bi bi-arrow-left"></i> Back
                            </a>
                            <button type="submit" class="btn btn-primary">Update</button>
                        </div>
                    </div>
                </div>
            </div>

        </form>
    </div>
    <script>
          $(document).ready(function() {
            // Toggle switches Jquery
            if ($('#eve').is(':checked')) {
                $('#eve_ot_allowance, #eve_ot').prop('disabled', true);
            } else if ($('#eve_ot_allowance').is(':checked')) {
                $('#eve, #eve_ot').prop('disabled', true);
            } else if ($('#eve_ot').is(':checked')) {
                $('#eve, #eve_ot_allowance').prop('disabled', true);
            }


            $('#eve').on('change', function () {
            const isChecked = $(this).is(':checked');
            $('#eve_ot_allowance, #eve_ot').prop('disabled', isChecked);
            });
            $('#eve_ot_allowance').on('change', function () {
            const isChecked = $(this).is(':checked');
            $('#eve, #eve_ot').prop('disabled', isChecked);
            });
            $('#eve_ot').on('change', function () {
            const isChecked = $(this).is(':checked');
            $('#eve_ot_allowance, #eve').prop('disabled', isChecked);
            });


            var table = $('#additional_item_table').DataTable();
            $('#addRow').on('click', function() {
                var newRow = table.row.add([
                    table.rows().count() + 1,
                    `<input type="text" name="item[${table.rows().count() + 1}][particular]"  class="form-control">`,
                    `<input type="number" name="item[${table.rows().count() + 1}][value]" class="form-control">`,
                    `<div class="d-flex align-items-center">
                                    <label class=" mx-2" for="hrdf">(
                                        NO</label>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox"
                                            id="hrdf" name="item[${table.rows().count() + 1}][checkbox]"
                                            value="1">
                                    </div>
                                    <label class="form-check-label" for="hrdf">YES
                                        )</label>
                                                            </div>`,
                                        '<button type="button" class="btn btn-sm btn-danger remove-row"><i class="bi bi-trash"></i></button>'
                                    ]).draw(false).node();
            });

            $('#additional_item_table tbody').on('click', '.remove-row', function() {
                table.row($(this).parents('tr')).remove().draw();
                resetSerialNumbersP();
            });

            function resetSerialNumbersP() {
                if ($('#additional_item_table tbody tr:first').find('td:first').text() != 'No data available in table') {
                    $('#additional_item_table tbody tr').each(function(index) {
                        $(this).find('td:first').text(index + 1);
                    });
                }
            }
        });
    </script>
@endsection
