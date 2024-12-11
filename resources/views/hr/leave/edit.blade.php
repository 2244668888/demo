@extends('layouts.app')
@section('title')
    LEAVE EDIT
@endsection
@section('content')

    <div class="card">
        <form method="post" action="{{ route('leave.update', $leave->id) }}" enctype="multipart/form-data">
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
                                                   @php
                                $moreUser = \App\Models\MoreUser::where('user_id', Auth::user()->id)->first();

                        @endphp
                            <label class="form-label" for="entitlement">Entitlement</label>
                            <select class="form-select" id="entitlement" name="entitlement">
                                
                                <option value="-2" selected disabled>Select an Entitlement</option>
                                <option value="Annual" @selected( $leave->entitlement == "Annual") data-balance="{{ isset($moreUser) ? $moreUser->annual_leave_balance_day : 0 }}">Annual</option>
                                <option value="Carried Annual" @selected( $leave->entitlement == "Carried Annual") data-balance="{{ isset($moreUser) ? $moreUser->carried_leave_balance_day : 0 }}">Carried Annual</option>
                                <option value="Time-off" @selected( $leave->entitlement == "Time-off") data-balance="0">Time-off</option>
                                <option value="Medical" @selected( $leave->entitlement == "Medical") data-balance="{{ isset($moreUser) ? $moreUser->medical_leave_balance_day : 0 }}">Medical</option>
                                <option value="Unpaid"  @selected( $leave->entitlement == "Unpaid")data-balance="{{ isset($moreUser) ? $moreUser->unpaid_leave_balance_day : 0 }}">Unpaid</option>
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
                                value="{{ $leave->balance_day }}" placeholder="Enter Balance(Day)" >
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
                                <input type="text" class="form-control" id="Current_day" disabled name="dayFront"
                                value="{{ $leave->day }}">
                                <input type="hidden" name="day" value="{{ $leave->day }}" id="day">
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
                            <input type="time" class="form-control" @if($leave->entitlement != "Time-off") readonly @endif  id="from_time" name="from_time"
                                value="{{ $leave->from_time }}" >
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label class="form-label" for="to_time">To</label>
                            <input type="time" class="form-control" @if($leave->entitlement != "Time-off") readonly @endif  id="to_time" name="to_time"
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
            <div class="card-footer">
                <div class="d-flex gap-2 justify-content-between">
                    <a type="button" class="btn btn-info" href="{{ route('leave.index') }}">
                        <i class="bi bi-arrow-left"></i> Back
                    </a>
                    <button type="submit" class="btn btn-primary" id="submit">Submit</button>
                </div>
            </div>
        </form>
    </div>
    <script>

$('#entitlement').on('change',function(){
    var value = $(this).val()
    var Parameter = $('#entitlement option:selected').data('balance');

    if (value == "Time-off") {
        $('#from_time').removeAttr('readonly');
        $('#to_time').removeAttr('readonly');
        $('#Current_day').val('0');
    $('#balance_day').val(Parameter);
    }else{
        $('#from_time').attr('readonly', 'readonly');
        $('#to_time').attr('readonly', 'readonly');
    $('#balance_day').val(Parameter);
    }
});
var prevValue = null;  // To track the previous value

$('#session').on('change', function() {
    calculateDays();
    var value = $(this).val();
    var date = $('#Current_day').val();
    
    if ( (value === "1st Half" || value === "2nd Half")) {
        prevValue = value;  // Update prevValue to the current selection
        var newdate = date / 2;
        $('#Current_day').val(newdate);
        $('#day').val(newdate);
    } else {
        // If the value hasn't changed, no action is taken
    }
});
         function calculateDays() {
                const fromDate = new Date($('#from').val());
                const toDate = new Date($('#to').val());

                if (fromDate && toDate && toDate >= fromDate) {
                    const differenceInTime = toDate.getTime() - fromDate.getTime();
                    const differenceInDays = Math.ceil(differenceInTime / (1000 * 3600 * 24)) + 1; 
                    $('#Current_day').val(differenceInDays);
                    $('#day').val(differenceInDays); 
                } else {
                    $('#Current_day').val(0);
                    $('#day').val(0);
                }
            }
            $('#from, #to').on('change', calculateDays);
        $(document).on('change', '#emergency', function() {
            if ($(this).is(':checked')) {
                $(this).val(1);
            } else {
                $(this).val(0);
            }
        });

         $(document).on('change','#entitlement',function(){
        var value = $('#entitlement option:selected').data('balance');
        $('#balance_day').val(value)
    })
    </script>

@endsection
