@extends('layouts.app')
@section('title')
    CALL FOR ASSISTANCE EDIT
@endsection
@section('content')
    <div class="card">
        <form action="{{ route('call_for_assistance.update', $call->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label for="attended_pic" class="form-label">Attended PIC</label>
                            <select name="attended_pic" class="form-select">
                                <option value="" selected disabled>Select PIC</option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}" @selected($call->attended_pic == $user->id)>{{ $user->user_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label for="calling_datetime" class="form-label">Calling DateTime</label>
                            <input type="text" readonly
                                value="{{ Carbon\Carbon::parse($call->datetime)->format('d-m-Y H:i:s') }}"
                                class="form-control" id="calling_datetime">
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
                            @if ($call->submitted_date != null)
                                <input type="text" readonly
                                    value="{{ Carbon\Carbon::parse($call->submitted_date)->format('d-m-Y H:i:s') }}"
                                    class="form-control" id="submitted_date">
                            @else
                                <input type="text" readonly
                                    value="{{ Carbon\Carbon::now('Asia/Kuala_Lumpur')->format('d-m-Y H:i:s') }}"
                                    class="form-control" id="submitted_date">
                            @endif
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label for="inputGroupFile02" class="form-label">Image</label>
                            <input type="file" class="form-control myfile" name="image" id="inputGroupFile02">
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <img @if ($call->image == null) src="{{ asset('assets/images/zenig.png') }}" @else src="{{ asset('/calls/') }}/{{ $call->image }}" @endif
                                id="blah" style="width: 100px; height: 100px;" class="Front_img" />
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label for="remarks" class="form-label">Remarks</label>
                            <textarea name="remarks" class="form-control">{!! $call->remarks !!}</textarea>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <div class="row">
                    <div class="d-flex gap-2 justify-content-between col-12">
                        <a type="button" class="btn btn-info" href="{{ route('call_for_assistance.index') }}">
                            <i class="bi bi-arrow-left"></i> Back
                        </a>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <script>
        $(document).ready(function() {
            var defaultImagePath = "{{ asset('assets/images/zenig.png') }}";

            $('.myfile').on('change', function() {
                var input = this;
                if (input.files && input.files[0]) {
                    if (input.files[0].type.match('image.*')) {
                        var reader = new FileReader();
                        reader.onload = function(e) {
                            $('.Front_img').attr('src', e.target.result);
                        }
                        reader.readAsDataURL(input.files[0]);
                    } else {
                        $('.Front_img').attr('src', defaultImagePath);
                    }
                }
            });

        });
    </script>
@endsection
