@extends('layouts.app')
@section('title')
    PURCHASE REQUISITION VERIFICATION AND APPROVAL
@endsection
@section('content')
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-lg-3 col-sm-4 col-12">
                    <div class="mb-3">
                        <label for="requested_by" class="form-label">Requested By</label>
                        <input type="text" readonly name="requested_by" id="requested_by" class="form-control"
                            value="{{ $purchase_requisitions->user->user_name ?? '' }}">
                    </div>
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-lg-3 col-sm-4 col-12">
                    <div class="mb-3">
                        <label for="" class="form-label">PR No.</label>
                        <input type="text" readonly value="{{ $purchase_requisitions->pr_no }}" readonly id="part_name"
                            class="form-control">
                    </div>
                </div>

                <div class="col-lg-3 col-sm-4 col-12">
                    <div class="mb-3">
                        <label for="" class="form-label">Department</label>
                        <input type="text" readonly value="{{ $department ? $department->name : '' }}"
                            class="form-control">
                    </div>
                </div>

                <div class="col-lg-3 col-sm-4 col-12">
                    <div class="mb-3">
                        <label for="" class="form-label">Status</label>
                        <select name="status" disabled class="form-select">
                            <option {{ $purchase_requisitions->status == 'Priority' ? 'selected' : '' }} value="Priority">
                                Priority</option>
                            <option {{ $purchase_requisitions->status == 'Urgent' ? 'selected' : '' }} value="Urgent">Urgent
                            </option>
                            <option {{ $purchase_requisitions->status == 'Not Urgent' ? 'selected' : '' }}
                                value="Not Urgent">Not Urgent</option>
                        </select>
                    </div>
                </div>

                <div class="col-lg-3 col-sm-4 col-12">
                    <div class="mb-3">
                        <label for="" class="form-label">Date :</label>
                        <input type="date" value="{{ date('Y-m-d', strtotime($purchase_requisitions->date)) }}" readonly
                            name="date" class="form-control">
                    </div>
                </div>
                <div class="col-lg-3 col-sm-4 col-12">
                    <div class="mb-3">
                        <label for="" class="form-label">Require Date :</label>
                        <input type="date" name="require_date" readonly
                            value="{{ date('Y-m-d', strtotime($purchase_requisitions->require_date)) }}"
                            class="form-control">
                    </div>
                </div>
                <div class="col-lg-3 col-sm-4 col-12">
                    <div class="mb-3">
                        <label for="" class="form-label">Category:</label>
                        <select name="category" disabled class="form-select">
                            <option {{ $purchase_requisitions->category == 'Printing & Stationary' ? 'selected' : '' }}
                                value="Printing & Stationary">Printing & Stationary</option>
                            <option {{ $purchase_requisitions->category == 'Direct Item' ? 'selected' : '' }}
                                value="Direct Item">Direct Item</option>
                            <option {{ $purchase_requisitions->category == 'Urgent' ? 'selected' : '' }} value="Urgent">
                                Indirect Item</option>
                            <option {{ $purchase_requisitions->category == 'Asset' ? 'selected' : '' }} value="Asset">
                                Asset</option>
                            <option {{ $purchase_requisitions->category == 'Others' ? 'selected' : '' }} value="Others">
                                Others (please Specify)</option>
                        </select>
                    </div>
                </div>
                @if ($purchase_requisitions->category_other)
                    <div class="col-lg-3 col-sm-4 col-12 d-none" id="other_div">
                        <div class="mb-3">
                            <label for="" class="form-label">Others:</label>
                            <input type="text" value="{{ $purchase_requisitions->category_other }}"
                                class="form-control">
                        </div>
                    </div>
                @endif


            </div>
            <div class="row">
                <div class="col-md-12">
                    <h6>Purchase Requisition Products</h6>
                    <div class="row">
                        <div class="col-sm-12 my-5">
                            <div class="table-responsive">
                                <table class="table table-bordered m-0" id="mainTable">
                                    <thead>
                                        <tr>
                                            <th>No.</th>
                                            <th>Part No.</th>
                                            <th>Part Name</th>
                                            <th>Price(RM)</th>
                                            <th>Quantity</th>
                                            <th>Total (RM)</th>
                                            <th>Purpose</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($purchase_requisition_details as $purchase_requisition_detail)
                                            <tr>
                                                <td>
                                                    {{ $loop->iteration }}
                                                </td>
                                                <td>
                                                    {{ $purchase_requisition_detail->product->part_no }}
                                                </td>
                                                <td>
                                                    {{ $purchase_requisition_detail->product->part_name }}
                                                </td>
                                                <td>
                                                    {{ $purchase_requisition_detail->price }}
                                                </td>
                                                <td>
                                                    {{ $purchase_requisition_detail->qty }}
                                                </td>
                                                <td>
                                                    {{ $purchase_requisition_detail->total }}
                                                </td>
                                                <td class="purpose-column">
                                                    {{ $purchase_requisition_detail->purpose }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-4 col-12">
                    <div class="mb-3">
                        <label for="" class="form-label">Grand Total (RM)</label>
                        <input type="text" id="grandtotal" value="{{ $purchase_requisitions->total }}" readonly
                            name="total" class="form-control">
                    </div>
                </div>
                <div class="col-lg-3 col-sm-4 col-12">
                    <label for="inputGroupFileAddon03" class="form-label">Attachment</label>
                    <div class="input-group mb-3">
                        <a href="{{ asset('/pr-attachments/') }}/{{ $purchase_requisitions->attachment }}"
                            class="btn btn-outline-secondary" type="button" id="inputGroupFileAddon03">
                            <i class="bi bi-file-text"></i>
                        </a>
                        <input disabled type="file" name="attachment" class="form-control" id="inputGroupFile03"
                            aria-describedby="inputGroupFileAddon03" aria-label="Upload">
                    </div>
                    {{ substr($purchase_requisitions->attachment, 14) }}
                </div>
                <div class="col-lg-3 col-sm-4 col-12">
                    <div class="mb-3">
                        <label for="" class="form-label">Remarks</label>
                        <textarea class="form-control" readonly name="remarks" rows="1">{{ $purchase_requisitions->remarks }}</textarea>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 justify-content-start">
                    <a type="button" class="btn btn-info" href="{{ route('purchase_requisition.index') }}">
                        <i class="bi bi-arrow-left"></i> Back
                    </a>
                </div>
                <div class="col-md-6 justify-content-end">
                    @if ($purchase_requisitions->verified_by == 'acc')
                        <button type="button" data-bs-toggle="modal" data-bs-target="#Modalcancel"
                            class="btn btn-warning float-end" style="margin-right: 25px;">Cancel</button>
                        <button type="button" data-bs-toggle="modal" data-bs-target="#Modaldecline"
                            class="btn btn-danger float-end" style="margin-right: 25px;">Decline</button>
                        <button type="button" data-bs-toggle="modal" data-bs-target="#Modalapprove"
                            class="btn btn-success float-end" style="margin-right: 25px;">Approve</button>
                    @elseif ($purchase_requisitions->verified_by == 'hod')
                        <button type="button" data-bs-toggle="modal" data-bs-target="#Modalcancel"
                            class="btn btn-warning float-end" style="margin-right: 25px;">Cancel</button>
                        <button type="button" data-bs-toggle="modal" data-bs-target="#Modaldecline"
                            class="btn btn-danger float-end" style="margin-right: 25px;">Decline</button>
                        <button type="button" data-bs-toggle="modal" data-bs-target="#Modalverify"
                            class="btn btn-success float-end" style="margin-right: 25px;">Verify by Acc</button>
                    @else
                        <button type="button" data-bs-toggle="modal" data-bs-target="#Modalcancel"
                            class="btn btn-warning float-end" style="margin-right: 25px;">Cancel</button>
                        <button type="button" data-bs-toggle="modal" data-bs-target="#Modaldecline"
                            class="btn btn-danger float-end" style="margin-right: 25px;">Decline</button>
                        <button type="button" data-bs-toggle="modal" data-bs-target="#Modalverify"
                            class="btn btn-success float-end" style="margin-right: 25px;">Verify by HOD</button>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <!-- Modal approve -->
    <div class="modal modal-lg fade" id="Modalapprove" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">
                        Approval
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

                                $department = App\Models\Department::find(Auth::user()->department_id);
                                $designation = App\Models\Designation::find(Auth::user()->designation_id);
                            @endphp
                            <tr>
                                <td>{{ date('d/m/y') }}</td>
                                <td>{{ Auth::user()->user_name }}</td>
                                <td>{{ $designation->name ?? '' }}</td>
                                <td>{{ $department->name ?? '' }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        Close
                    </button>
                    <form method="post"
                        action="{{ route('purchase_requisition.approve', $purchase_requisitions->id) }}">
                        @csrf
                        <input type="text" name="approved_by" value="{{ Auth::user()->id }}" hidden>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal verify -->
    <div class="modal modal-lg fade" id="Modalverify" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">
                        Verification
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
                                <td>{{ date('d/m/y') }}</td>
                                <td>{{ Auth::user()->user_name }}</td>
                                <td>{{ $designation->name ?? '' }}</td>
                                <td>{{ $department->name ?? '' }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        Close
                    </button>
                    @php
                        $verified_by = '';
                        if ($purchase_requisitions->verified_by == '') {
                            $verified_by = 'hod';
                        }
                        if ($purchase_requisitions->verified_by == 'hod') {
                            $verified_by = 'acc';
                        }
                        if ($purchase_requisitions->verified_by == 'acc') {
                            $verified_by = 'head';
                        }
                    @endphp
                    <form method="post"
                        action="{{ route('purchase_requisition.verify', [$purchase_requisitions->id, $verified_by]) }}">
                        @csrf
                        <input type="text" name="status" value="Approve" hidden>
                        <input type="text" name="approved_by" value="{{ Auth::user()->id }}" hidden>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal decline -->
    <div class="modal modal-lg fade" id="Modaldecline" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">
                        Verification
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="post"
                    action="{{ route('purchase_requisition.decline', [$purchase_requisitions->id, $verified_by]) }}">
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
                                    <td>{{ date('d/m/y') }}</td>
                                    <td>{{ Auth::user()->user_name }}</td>
                                    <td>{{ $designation->name ?? '' }}</td>
                                    <td>{{ $department->name ?? '' }}</td>
                                    <td><input type="text" name="reason" class="form-control"></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            Close
                        </button>
                        @php
                            if ($purchase_requisitions->verified_by == '') {
                                $verified_by = 'hod';
                            }
                            if ($purchase_requisitions->verified_by == 'hod') {
                                $verified_by = 'acc';
                            }
                            if ($purchase_requisitions->verified_by == 'acc') {
                                $verified_by = 'head';
                            }
                        @endphp
                        <input type="text" name="status" value="Declined" hidden>
                        <input type="text" name="approved_by" value="{{ Auth::user()->id }}" hidden>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                </form>
            </div>
        </div>
    </div>
    </div>
    <!-- Modal cancel -->
    <div class="modal modal-lg fade" id="Modalcancel" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">
                        Verification
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="post" action="{{ route('purchase_requisition.cancel', $purchase_requisitions->id) }}">
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
                                    <td>{{ date('d/m/y') }}</td>
                                    <td>{{ Auth::user()->user_name }}</td>
                                    <td>{{ $designation->name ?? '' }}</td>
                                    <td>{{ $department->name ?? '' }}</td>
                                    <td><input type="text" name="reason" class="form-control"></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            Close
                        </button>

                        <input type="text" name="status" value="Cancelled" hidden>
                        <input type="text" name="approved_by" value="{{ Auth::user()->id }}" hidden>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                </form>
            </div>
        </div>
    </div>
    </div>

    <script>
        $(document).ready(function() {
            $('.purpose-column').each(function() {
                const $cell = $(this);
                const fullText = $cell.text().trim();

                if (fullText.length > 25) {
                    const shortText = fullText.substring(0, 25) + '...';
                    $cell.html(`
                        <div class="description">
                            <p class="short-desc">${shortText}</p>
                            <p class="full-desc" style="display:none;">${fullText}</p>
                            <a href="#" class="read-more">Read More</a>
                        </div>
                    `);
                }
            });

            $(document).on('click', '.read-more', function(event) {
                event.preventDefault();
                const descriptionDiv = $(this).closest('.description');
                descriptionDiv.find('.short-desc').toggle();
                descriptionDiv.find('.full-desc').toggle();
                $(this).text($(this).text() === 'Read More' ? 'Read Less' : 'Read More');
            });
        });
    </script>
@endsection
