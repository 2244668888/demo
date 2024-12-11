@extends('layouts.app')
@section('title')
    QUOTATION VERIFY
@endsection
@section('content')
    <div class="card">
        <form method="post" action="{{ route('quotation.verify_update', $quotation->id) }}">
            @csrf
            <div class="card-body">
                <div class="row">
                    <h5>CUSTOMER DETAILS</h5>
                </div>
                <br>
                <div class="row">
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label for="customer_id" class="form-label">Customer Name</label>
                            <select name="customer_id" onchange="customer_change()" id="customer_id" class="form-select">
                                <option value="">Please Select</option>
                                @foreach ($customers as $customer)
                                    <option value="{{ $customer->id }}" @selected($quotation->customer_id == $customer->id)>
                                        {{ $customer->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label for="address" class="form-label">Customer Address</label>
                            <textarea id="address" rows="1" class="form-control" readonly></textarea>
                        </div>
                    </div>

                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label for="attn" class="form-label">Attn</label>
                            <input type="text" readonly id="attn" class="form-control ">
                        </div>
                    </div>

                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label for="pic_department" class="form-label">Department</label>
                            <input type="text" readonly id="pic_department" class="form-control ">
                        </div>
                    </div>

                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label for="cc" class="form-label">CC</label>
                            <input type="text" name="cc" id="cc" class="form-control" value="{{ $quotation->cc }}">
                        </div>
                    </div>

                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label for="department" class="form-label">Department</label>
                            <input type="text" name="department" id="department" class="form-control" value="{{ $quotation->department }}">
                        </div>
                    </div>
                </div>
                <br>
                <div class="row">
                    <h5>QUOTATION DETAILS</h5>
                </div>
                <br>
                <div class="row">
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label for="ref_no" class="form-label">Ref No</label>
                            <input type="text" readonly name="ref_no" id="ref_no" class="form-control"
                                value="{{ $quotation->ref_no }}">
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label for="date" class="form-label">Date</label>
                            <input type="date" name="date" id="date" class="form-control"
                                value="{{ $quotation->date }}">
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label for="created_by" class="form-label">Created By</label>
                            <input type="text" readonly name="created_by" id="created_by" class="form-control"
                                value="{{ Auth::user()->user_name }}">
                        </div>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-12 d-flex justify-content-between">
                        <h5>PART LIST</h5>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="table-responsive">
                        <table class="table table-bordered m-0" id="mainTable">
                            <thead>
                                <tr>
                                    <th>Sr No</th>
                                    <th>Part No</th>
                                    <th>Part Name</th>
                                    <th>Remarks</th>
                                    <th>Price (RM)</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($quotation_details as $quotation_detail)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $quotation_detail->products->part_no }}</td>
                                        <td>{{ $quotation_detail->products->part_name }}</td>
                                        <td>
                                            @if ($quotation_detail->remarks == '' || $quotation_detail->remarks == null)
                                                <button type="button"
                                                    class="btn btn-sm add-remarks btn-danger">Add</button>
                                            @else
                                                <button type="button"
                                                    class="btn btn-sm add-remarks btn-success">Edit</button>
                                            @endif
                                        </td>
                                        <td><input type="hidden" name="products[{{ $loop->iteration }}][product_id]"
                                                class="product_id" value="{{ $quotation_detail->product_id }}">
                                            <input type="hidden" class="unit"
                                                value="{{ $quotation_detail->products->units->name ?? '' }}">
                                            <input type="hidden" class="model"
                                                value="{{ $quotation_detail->products->model }}">
                                            <input type="hidden" class="variance"
                                                value="{{ $quotation_detail->products->variance }}">
                                            <input type="hidden" class="type_of_product"
                                                value="{{ $quotation_detail->products->type_of_products->type ?? '' }}">
                                            <input type="hidden" class="category"
                                                value="{{ $quotation_detail->products->category }}">
                                            <input type="hidden" class="remarks"
                                                name="products[{{ $loop->iteration }}][remarks]"
                                                value="{{ $quotation_detail->remarks }}">
                                            <input type="number" class="form-control price"
                                                name="products[{{ $loop->iteration }}][price]"
                                                value="{{ $quotation_detail->price }}">
                                        </td>
                                        <td><button type="button" class="btn btn-danger btn-sm remove-product"><i
                                                    class="bi bi-trash"></i></button></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <br>
                <div class="col-lg-12 col-sm-12 col-12">
                    <div class="mb-3">
                        <label for="term_conditions" class="form-label">TERM AND CONDITION</label>
                        <textarea placeholder="Enter Here" id="term_conditions" rows="4" class="form-control" name="term_conditions">{!! $quotation->term_conditions !!}</textarea>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="row">
                        <div class="d-flex gap-2 justify-content-between col-12">
                            <div class="d-flex gap-2 justify-content-start">
                                <a type="button" class="btn btn-info" href="{{ route('quotation.index') }}">
                                    <i class="bi bi-arrow-left"></i> Back
                                </a>
                            </div>
                            <div class="d-flex gap-2 justify-content-end">
                                <button type="submit" class="btn btn-success">Verify</button>
                                <button type="button" id="decline_button" value="decline"
                                    class="btn btn-warning">Decline</button>
                                <button type="button" id="cancel_button" value="cancel"
                                    class="btn btn-danger">Cancel</button>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
    {{-- DECLINE / CANCEL MODAL --}}
    <div class="modal fade show" id="exampleModalCenter" tabindex="-1" aria-labelledby="exampleModalCenterTitle"
        aria-modal="true" role="dialog" style="display: none;">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalCenterTitle">
                        DECLINE REASON
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('quotation.decline_cancel', $quotation->id) }}" method="POST"
                    id="decline_cancel_form">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" name="decline_cancel" id="decline_cancel" value="decline">
                        <textarea name="decline_cancel_reason" id="decline_cancel_reason" rows="5" class="form-control"
                            placeholder="Enter Reason"></textarea>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-danger" id="decline_cancel_button">
                            DECLINE
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    {{-- REMARKS MODAL --}}
    <div class="modal fade" id="remarksModal" tabindex="-1" aria-labelledby="remarksModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="remarksModalLabel">Add Remarks</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <textarea id="remarksText" class="form-control" rows="4" placeholder="Enter your remarks here"></textarea>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <script>
        let mainTable;
        let firstAttempt = true;
        $(document).ready(function() {
            flatpickr("#date", {
                dateFormat: "d-m-Y",
                defaultDate:@json(\Carbon\Carbon::parse($quotation->date)->format('d-m-Y'))
            });
            customer_change();
            $('.card-body input, select').prop('disabled', true);
            $('#term_conditions').prop('disabled', true);
            mainTable = $('#mainTable').DataTable();
        });

        var customers = {!! json_encode($customers) !!};

        function customer_change() {
            var customersId = $("#customer_id").val();
            var customer = customers.find(p => p.id == customersId);
            if (customer) {
                $('#address').val(customer.address);
                $('#attn').val(customer.pic_name);
                $('#pic_department').val(customer.pic_department);
            } else {
                $('#address').val('');
                $('#attn').val('');
                $('#pic_department').val('');
            }
        };

        $('#mainTable tbody').on('click', '.add-remarks', function() {
            $('#remarksText').val($(this).closest('tr').find('.remarks').val());
            $('#remarksModal').modal('show');
        });

        $('#decline_button, #cancel_button').on('click', function() {
            $('#decline_cancel').val($(this).val());
            if ($('#decline_cancel').val() == 'decline') {
                $('#exampleModalCenterTitle').text('DECLINE REASON');
                $('#decline_cancel_button').text('DECLINE');
            } else {
                $('#exampleModalCenterTitle').text('CANCEL REASON');
                $('#decline_cancel_button').text('CANCEL');
            }
            $('#exampleModalCenter').modal('show');
        });
    </script>
@endsection
