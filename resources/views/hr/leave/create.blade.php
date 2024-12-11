@extends('layouts.app')
@section('title')
    LEAVE CREATE
@endsection
@section('content')
@php
use Illuminate\Support\Facades\Auth;
@endphp
    <div class="card">
        <form method="post" action="{{ route('leave.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label class="form-label" for="name">User Name</label>
                            <input type="text" class="form-control" id="name" name="name"
                                value="{{ old('name', Auth::user()->user_name) }}" placeholder="Enter Name" readonly>
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
                                <option value="Annual" data-balance="{{ isset($moreUser) ? $moreUser->annual_leave_balance_day : 0 }}">Annual</option>
                                <option value="Carried Annual" data-balance="{{ isset($moreUser) ? $moreUser->carried_leave_balance_day : 0 }}">Carried Annual</option>
                                <option value="Time-off" data-balance="0">Time-off</option>
                                <option value="Medical" data-balance="{{ isset($moreUser) ? $moreUser->medical_leave_balance_day : 0 }}">Medical</option>
                                <option value="Unpaid" data-balance="{{ isset($moreUser) ? $moreUser->unpaid_leave_balance_day : 0 }}">Unpaid</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label class="form-label" for="user_name">Status</label>
                            <input type="text" class="form-control" value="Request" readonly>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label class="form-label" for="balance_day">Balance(Day)</label>
                            <input type="balance_day" class="form-control" id="balance_day" name="balance_day"
                                   value="{{ old('balance_day') }}" placeholder="Enter Balance(Day)" >
                        </div>
                    </div>                    
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label class="form-label" for="from">From (Date)</label>
                            <input type="date" class="form-control" id="from" name="from_date"
                                value="{{ old('from_date') }}" >
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label class="form-label" for="to">To (Date)</label>
                            <input type="date" class="form-control" id="to" name="to_date"
                                value="{{ old('to_date') }}" >
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label class="form-label" for="day">Day</label>
                            <input type="text" class="form-control" id="Current_day" disabled name="dayFront"
                                value="{{ old('dayFront') }}">
                                <input type="hidden" name="day" value="{{ old('day') }}" id="day">
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label class="form-label" for="session">Session</label>
                            <select class="form-select" id="session" name="session">
                                <option value="Full Day">Full Day</option>
                                <option value="1st Half">AM</option>
                                <option value="2nd Half">PM</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label class="form-label" for="from_time">From (Time)</label>
                            <input type="time" class="form-control" readonly  id="from_time" name="from_time"
                                value="{{ old('from_time') }}" >
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label class="form-label" for="to_time">To (Time)</label>
                            <input type="time" class="form-control" readonly id="to_time" name="to_time"
                                value="{{ old('to_time') }}" >
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label class="form-label" for="Attachment">Attachment</label>
                            <input  type="file" class="form-control" name="attachment" id="Attachment" value="{{ old('attachment') }}">
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <div class="form-check mt-4" >
                                <input class="form-check-input" type="checkbox"  id="emergency"
                                    name="emergency" @checked(old("emergency"))>
                                <label class="form-check-label" for="flexCheckDefault">Emergency</label>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label class="form-label" for="reason">Reason</label>
                           <textarea name="reason" id="reason" cols="30" rows="3" class="form-control">{{ old('reason') }}</textarea>
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
$(document).ready(function() {


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



    if ($('#emergency').is(':checked')) {
        $(this).removeAttr('value')
                $(this).val(1);
            } else {
                $(this).val(0);
            }




            function calculateDays() {
                const fromDate = new Date($('#from').val());
                const toDate = new Date($('#to').val());
                if ($('#entitlement').val() == 'Time-off') {
                    $('#Current_day').val('0');
                    return;
                }
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

            });


        $(document).on('change', '#emergency', function() {
            if ($(this).is(':checked')) {
                $(this).val(1);
            } else {
                $(this).val(0);
            }
        });





    </script>

@endsection
