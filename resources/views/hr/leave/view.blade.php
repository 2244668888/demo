@extends('layouts.app')
@section('title')
    LEAVE VIEW
@endsection
@section('content')

    <div class="card">
        <form method="post" action="{{ route('leave.update', $leave->id) }}">
            @csrf
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label class="form-label" for="name">User Name</label>
                            <input type="text" class="form-control" id="name" name="name"
                                value="{{ $leave->name }}" placeholder="Enter Name">
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label class="form-label" for="entitlement">Entitlement</label>
                            <select class="form-select" id="entitlement" name="entitlement">
                                <option value="Annual" @selected($leave->entitlement == "Annual")>Annual</option>
                                <option value="Carried Annual" @selected($leave->entitlement == "Carried Annual")>Carried Annual</option>
                                <option value="Time-off" @selected($leave->entitlement == "Time-off")>Time-off</option>
                                <option value="Medical" @selected($leave->entitlement == "Medical")>Medical</option>
                                <option value="Unpaid" @selected($leave->entitlement == "Unpaid")>Unpaid</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label class="form-label" for="user_name">Status</label>
                            <input type="text" class="form-control" value="{{ $leave->status }}" readonly>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label class="form-label" for="balance_day">Balance(Day)</label>
                            <input type="balance_day" class="form-control" id="balance_day" name="balance_day"
                                value="{{ $leave->balance_day }}" placeholder="Enter Balance(Day)">
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label class="form-label" for="from">From</label>
                            <input type="date" class="form-control" id="from" name="from_date"
                                value="{{ $leave->from_date }}" >
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label class="form-label" for="to">To</label>
                            <input type="date" class="form-control" id="to" name="to_date"
                                value="{{ $leave->to_date }}" >
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label class="form-label" for="day">Day</label>
                            <input type="text" class="form-control" id="Current_day" disabled name="day"
                                value="{{ $leave->day }}">
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label class="form-label" for="session">Session</label>
                            <select class="form-select" id="session" name="session">
                                <option value="Full Day" @selected($leave->session == "Full Day")>Full Day</option>
                                <option value="1st Half" @selected($leave->session == "1st Half")>AM</option>
                                <option value="2nd Half" @selected($leave->session == "2nd Half")>PM</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label class="form-label" for="from_time">From</label>
                            <input type="time" class="form-control" @if($leave->entitlement == 'Time-off') readonly @endif id="from_time" name="from_time"
                                value="{{ $leave->from_time }}" >
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label class="form-label" for="to_time">To</label>
                            <input type="time" class="form-control" @if($leave->entitlement == 'Time-off') readonly @endif id="to_time" name="to_time"
                                value="{{ $leave->to_time }}" >
                        </div>
                    </div>

                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label class="form-label" for="attachment">Attachment </label>
                            <div class="input-group mb-3">
                                <a target="_blank" href="{{ asset('/leave-attachments/') }}/{{ $leave->attachment }}" class="btn btn-outline-secondary" type="button" id="inputGroupFileAddon03">
                                    <i class="bi bi-file-text"></i>
                                </a>
                                <input type="file" class="form-control" id="attachment" aria-describedby="attachment" aria-label="attachment" name="attachment">
                            </div>

                        </div>
                    </div>

                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <div class="form-check mt-4" >
                                <input class="form-check-input" type="checkbox" value="0" id="emergency"
                                    name="emergency" @if($leave->emergency == 1) checked @endif>
                                <label class="form-check-label" for="flexCheckDefault">Emergency</label>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label class="form-label" for="reason">Reason</label>
                           <textarea name="reason" id="reason" cols="30" rows="3" class="form-control">{{ $leave->reason }}</textarea>
                        </div>
                    </div>
                </div>

            </div>
        </form>
    </div>
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-sm-12 my-5">
                    <h6>Leave Verfication History</h6>
                    <div class="table-responsive">
                        <table class="table table-bordered m-0" id="mainTable">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Date</th>
                                    <th>UserName</th>
                                    <th>Department</th>
                                    <th>Designation</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    use App\Models\Department;
                                    use App\Models\Designation;
                                    use App\Models\User;
                                @endphp
                                @foreach ($leave_verifications as $leave_verification)
                                    <tr>
                                        <td>
                                            {{ $loop->iteration }}
                                        </td>
                                        <td>
                                            {{ date('d-m-Y',strtotime($leave_verification->date)) }}
                                        </td>
                                        <td>
                                            @php
                                                $user = User::find($leave_verification->approved_by);
                                            @endphp
                                            {{ $user ? $user->user_name : '' }}
                                        </td>
                                        <td>
                                            @php
                                                $department = Department::find(
                                                    $leave_verification->department_id,
                                                );
                                            @endphp
                                            {{ $department ? $department->name : '' }}
                                        </td>

                                        <td>
                                            @php
                                                $department = Designation::find(
                                                    $leave_verification->designation_id,
                                                );
                                            @endphp
                                            {{ $department ? $department->name : '' }}
                                        </td>

                                        <td>
                                            @if ($leave_verification->status == 'Approved')
                                                <span class="badge bg-info">Approved</span></h1>
                                            @endif
                                            @if ($leave_verification->status == 'Declined')
                                                <span class="badge bg-danger">Not Approved</span></h1>
                                            @endif
                                            @if ($leave_verification->status == 'Cancelled')
                                                <span class="badge bg-danger">Cancelled</span></h1>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <div class="row">
                <div class="d-flex gap-2 justify-content-between col-12">
                    <a type="button" class="btn btn-info" href="{{ route('leave.index') }}">
                        <i class="bi bi-arrow-left"></i> Back
                    </a>
                </div>
            </div>
        </div>
    </div>
    <script>


$('.card-body input, select,textarea,checkbox').prop('disabled', true);

$(document).on('click','#submit' , function() {
            if ($('.alert').length === 0) {
                $('#Current_day').removeAttr('disabled');
            }
        })

        $(document).on('change', '#emergency', function() {
            if ($(this).is(':checked')) {
                $(this).val(1);
            } else {
                $(this).val(0);
            }
        });





    </script>

@endsection
