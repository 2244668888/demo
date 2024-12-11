@extends('layouts.app')
@section('title')
    LEAVE MANAGE
@endsection
@section('content')

    <div class="card">
        <form method="post" action="{{ route('leave.manage.store', $leave->id) }}">
            @csrf
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label class="form-label" for="name">User Name</label>
                            <input type="text" class="form-control" id="name" name="name"
                                value="{{ $leave->name }}" placeholder="Enter Name" disabled>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label class="form-label" for="entitlement">Entitlement</label>
                            <select class="form-select" id="entitlement" name="entitlement" disabled>
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
                            <select class="form-select" id="status" name="status" disabled>
                                <option value="Approved">Approved</option>
                                <option value="Not Approved">Not Approved</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label class="form-label" for="balance_day">Balance(Day)</label>
                            <input type="balance_day" class="form-control" id="balance_day" name="balance_day"
                                value="{{ $leave->balance_day }}" placeholder="Enter Balance(Day)" disabled>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label class="form-label" for="from">From</label>
                            <input type="date" class="form-control" id="from" name="from_date" disabled
                                value="{{ $leave->from_date }}" >
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label class="form-label" for="to">To</label>
                            <input type="date" class="form-control" id="to" name="to_date" disabled
                                value="{{ $leave->to_date }}" >
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label class="form-label" for="day">Day</label>
                            <input type="text" class="form-control" id="Current_day"  name="day" disabled
                                value="{{ $leave->day }}">
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label class="form-label" for="session">Session</label>
                            <select class="form-select" id="session" name="session" disabled>
                                <option value="Full Day" @selected($leave->session == "Full Day")>Full Day</option>
                                <option value="1st Half" @selected($leave->session == "1st Half")>AM</option>
                                <option value="2nd Half" @selected($leave->session == "2nd Half")>PM</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label class="form-label" for="from_time">From</label>
                            <input type="time" class="form-control" id="from_time" name="from_time"
                                value="{{ $leave->from_time }}"  disabled>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label class="form-label" for="to_time">To</label>
                            <input type="time" class="form-control" id="to_time" name="to_time"
                                value="{{ $leave->to_time }}"  disabled>
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
                           <textarea name="reason" id="reason" disabled cols="30" rows="3" class="form-control">{{ $leave->reason }}</textarea>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label class="form-label" for="pic_remarks">PIC</label>
                            <input type="text" class="form-control" id="pic_remarks" name="pic_remarks" placeholder="Enter Remarks">
                        </div>
                    </div>
                </div>

            </div>
            <div class="card-footer">
                <div class="d-flex gap-2 justify-content-between">
                    <a type="button" class="btn btn-info" href="{{ route('leave.index') }}">
                        <i class="bi bi-arrow-left"></i> Back
                    </a>
                </div>
                <div class="d-flex justify-content-end">
                    <button type="button" data-bs-toggle="modal" data-bs-target="#Modalverify" class="btn btn-success float-end" style="margin-right: 25px;">Approved</button>
                    <button type="button" data-bs-toggle="modal" data-bs-target="#Modaldecline" class="btn btn-warning float-end" style="margin-right: 25px;">Not Approved</button>
                </div>
            </div>
        </form>
    </div>

        <!-- Modal verify -->
        <div class="modal modal-lg fade" id="Modalverify" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">
                        Verification (Verify)
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Username</th>
                                    <th>Designation</th>
                                    <th>Department</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    use App\Models\Department;
                                    use App\Models\Designation;
    
                                    $department = Department::find(Auth::user()->department_id);
                                    $designation = Designation::find(Auth::user()->designation_id);
                                @endphp
                                <tr>
                                    <td>{{date('d/m/y')}}</td>
                                    <td>{{Auth::user()->user_name}}</td>
                                    <td>{{$designation->name ?? ''}}</td>
                                    <td>{{$department->name ?? ''}}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        Close
                    </button>
                    <form method="post" action="{{ route('leave.verify', $leave->id) }}">
                        @csrf
                        <input type="text" name="status" value="Approved" hidden>
                        <input type="text" name="approved_by" value="{{Auth::user()->id}}" hidden>
                        <input type="text" name="department_id" value="{{Auth::user()->department_id}}" hidden>
                        <input type="text" name="designation_id" value="{{Auth::user()->designation_id}}" hidden>
                        <input type="text" name="pic_remarks" hidden>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal decline -->
        <div class="modal modal-lg fade" id="Modaldecline" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">
                        Verification (Decline)
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form method="post" action="{{ route('leave.decline',$leave->id) }}">
                    @csrf
                    <div class="modal-body">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Username</th>
                                    <th>Designation</th>
                                    <th>Department</th>
                                    <th>Reason</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $department = Department::find(Auth::user()->department_id);
                                    $designation = Designation::find(Auth::user()->designation_id);
                                @endphp
                                <tr>
                                    <td>{{date('d/m/y')}}</td>
                                    <td>{{Auth::user()->user_name}}</td>
                                    <td>{{$designation->name ?? ''}}</td>
                                    <td>{{$department->name ?? ''}}</td>
                                    <td><input type="text" name="reason" class="form-control"></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        Close
                    </button>
    
                        <input type="text" name="status" value="Declined" hidden>
                        <input type="text" name="department_id" value="{{Auth::user()->department_id}}" hidden>
                        <input type="text" name="designation_id" value="{{Auth::user()->designation_id}}" hidden>
                        <input type="text" name="approved_by" value="{{Auth::user()->id}}" hidden>
                        <input type="text" name="pic_remarks" hidden>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </form>
                    </div>
                </div>
            </div>
        </div>
    <script>




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
