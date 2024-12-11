@extends('layouts.app')
@section('title')
    CALL FOR ASSISTANCE VIEW
@endsection
@section('content')
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-lg-3 col-sm-4 col-12">
                    <div class="mb-3">
                        <label for="attended_pic" class="form-label">Attended PIC</label>
                        <input type="text" readonly value="{{ $call->user->user_name ?? '' }}" id="attended_pic"
                            class="form-control">
                    </div>
                </div>
                <div class="col-lg-3 col-sm-4 col-12">
                    <div class="mb-3">
                        <label for="calling_datetime" class="form-label">Calling DateTime</label>
                        <input type="text" readonly
                            value="{{ Carbon\Carbon::parse($call->datetime)->format('d-m-Y H:i:s') }}" class="form-control"
                            id="calling_datetime">
                    </div>
                </div>
                <div class="col-lg-3 col-sm-4 col-12">
                    <div class="mb-3">
                        <label for="attended_datetime" class="form-label">Attended DateTime</label>
                        @if($call->attended_datetime != null)
                            <input type="text" readonly
                                value="{{ Carbon\Carbon::parse($call->attended_datetime)->format('d-m-Y H:i:s') }}"
                                class="form-control" id="attended_datetime">
                                @else
                                <input type="text" readonly
                                value=""
                                class="form-control" id="attended_datetime">
                                @endif
                    </div>
                </div>
                <div class="col-lg-3 col-sm-4 col-12">
                    <div class="mb-3">
                        <label for="submitted_date" class="form-label">Submitted DateTime</label>
                        <input type="text" readonly
                            value="{{ Carbon\Carbon::parse($call->submitted_date)->format('d-m-Y H:i:s') }}"
                            class="form-control" id="submitted_date">
                    </div>
                </div>
                <div class="col-lg-3 col-sm-4 col-12">
                    <div class="mb-3">
                        <label for="remarks" class="form-label">Remarks</label>
                        <textarea readonly name="remarks" class="form-control">{!! $call->remarks !!}</textarea>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="mb-3">
                    <img @if ($call->image == null) src="{{ asset('assets/images/zenig.png') }}" @else src="{{ asset('/calls/') }}/{{ $call->image }}" @endif
                        id="blah" style="width: 100px; height: 100px;" class="Front_img" />
                </div>
            </div>
        </div>
        <div class="card-footer">
            <div class="row">
                <div class="d-flex gap-2 justify-content-start col-12">
                    <a type="button" class="btn btn-info" href="{{ route('call_for_assistance.index') }}">
                        <i class="bi bi-arrow-left"></i> Back
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
