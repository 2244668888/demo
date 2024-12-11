@extends('layouts.app')
@section('title')
    PURCHASE REQUISITION VIEW
@endsection
@section('content')
    <div class="card mb-3">
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
                        <input type="text" readonly value="{{ $purchase_requisitions->department->name ?? '' }}"
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
                    <div class="col-lg-3 col-sm-4 col-12" id="other_div">
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
                    <div class="row">
                        <div class="col-sm-12 my-5">
                            <h6>Purchase Requisition Products</h6>
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
                    {{ substr($purchase_requisitions->attachment,14) }}
                </div>
                <div class="col-lg-3 col-sm-4 col-12">
                    <div class="mb-3">
                        <label for="" class="form-label">Remarks</label>
                        <textarea class="form-control" readonly name="remarks" rows="1">{{ $purchase_requisitions->remarks }}</textarea>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-sm-12 my-5">
                    <h6>Purchase Requisition Verfication History</h6>
                    <div class="table-responsive">
                        <table class="table table-bordered m-0" id="mainTable">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Date</th>
                                    <th>Approve</th>
                                    <th>Department</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    use App\Models\Department;
                                    use App\Models\User;
                                @endphp
                                @foreach ($purchase_requisition_statuses as $purchase_requisition_status)
                                    <tr>
                                        <td>
                                            {{ $loop->iteration }}
                                        </td>
                                        <td>
                                            {{ date('d-m-Y',strtotime($purchase_requisition_status->date)) }}
                                        </td>
                                        <td>
                                            @php
                                                $user = User::find($purchase_requisition_status->approved_by);
                                            @endphp
                                            {{ $user ? $user->user_name : '' }}
                                        </td>
                                        <td>
                                            @php
                                                $department = Department::find(
                                                    $purchase_requisition_status->department_id,
                                                );
                                            @endphp
                                            {{ $department ? $department->name : '' }}
                                        </td>

                                        <td>
                                            @if ($purchase_requisition_status->status == 'Requested')
                                                <span class="badge bg-dark">Requested</span></h1>
                                            @endif
                                            @if ($purchase_requisition_status->status == 'Verified')
                                                @if ($purchase_requisitions->verified_by == '') 
                                                    <span class="badge bg-info">Requested</span></h1>
                                                @elseif ($purchase_requisitions->verified_by == 'hod') 
                                                    <span class="badge bg-info">Verified (HOD)</span></h1>
                                                @elseif ($purchase_requisitions->verified_by == 'acc') 
                                                    <span class="badge bg-info">Verified (Acc)</span></h1>
                                                @elseif ($purchase_requisitions->verified_by == 'head')
                                                    <span class="badge bg-info">Approved</span></h1>
                                                @endif
                                            @endif
                                            @if ($purchase_requisition_status->status == 'Approved')
                                                <span class="badge bg-success">Approved</span></h1>
                                            @endif
                                            @if ($purchase_requisition_status->status == 'Declined')
                                                <span class="badge bg-warning">Declined</span></h1>
                                            @endif
                                            @if ($purchase_requisition_status->status == 'Cancelled')
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
                    <a type="button" class="btn btn-info" href="{{ route('purchase_requisition.index') }}">
                        <i class="bi bi-arrow-left"></i> Back
                    </a>
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
