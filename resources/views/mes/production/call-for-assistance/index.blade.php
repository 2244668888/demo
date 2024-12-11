@extends('layouts.app')
@section('title')
    CALL FOR ASSISTANCE LIST
@endsection
@section('content')
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table id="myTable" class="table table-bordered m-0">
                    <thead>
                        <tr>
                            <th>Sr No</th>
                            <th>Machine</th>
                            <th>Calling Datetime</th>
                            <th>Attended Datetime</th>
                            <th>Attended PIC</th>
                            <th>Remarks</th>
                            <th>Submitted Datetime</th>
                            <th>Status</th>
                            <th>Image</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($calls as $call)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $call->mc_no }}</td>
                                <td>{{ $call->datetime }}</td>
                                <td>{{ $call->attended_datetime }}</td>
                                <td>{{ $call->user->user_name ?? '' }}</td>
                                <td>{!! $call->remarks !!}</td>
                                <td>{{ $call->submitted_datetime }}</td>
                                <td>
                                    @if ($call->status == 'Not-initiated')
                                        <span class="badge border border-warning text-warning">{{ $call->status }}</span>
                                    @else
                                        <span class="badge border border-success text-success">{{ $call->status }}</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($call->image != null)
                                        <a class="btn btn-success btn-sm" target="_blank"
                                            href="{{ asset('/calls/') }}/{{ $call->image }}"><i
                                                class="bi bi-images"></i></a>
                                    @else
                                        <a class="btn btn-success btn-sm" title="Not Uploaded"><i
                                                class="bi bi-images"></i></a>
                                    @endif
                                </td>
                                <td>

                                        <a class="btn btn-info btn-sm"
                                            href="{{ route('call_for_assistance.edit', $call->id) }}"><i
                                                class="bi bi-pencil"></i></a>
                                    <a class="btn btn-success btn-sm"
                                        href="{{ route('call_for_assistance.view', $call->id) }}"><i
                                            class="bi bi-eye"></i></a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
