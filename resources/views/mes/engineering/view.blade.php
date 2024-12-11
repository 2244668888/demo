@extends('layouts.app')
@section('title')
    BOM VIEW
@endsection
@section('content')
    <style>
        #mainTable input {
            width: 100px;
        }
    </style>
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-lg-12">
                    <div class="col-lg-3 col-sm-4 col-12 float-end">
                        <div class="mb-3">
                            <label for="created_by" class="form-label">Created By</label>
                            <input type="text" readonly class="form-control"
                                value="{{ $created_by->user_name }}">
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-4 col-12">
                        <h5>PRODUCT INFORMATION</h5>
                    </div>
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-lg-3 col-sm-4 col-12">
                    <div class="mb-3">
                        <label for="rev_no" class="form-label">Revision No.</label>
                        <input type="text" readonly  value="{{$bom->rev_no}}" id="rev_no" class="form-control">
                    </div>
                </div>
                <div class="col-lg-3 col-sm-4 col-12">
                    <div class="mb-3">
                        <label for="ref_no" class="form-label">Ref No.</label>
                        <input type="text" readonly id="ref_no" class="form-control"
                            value="{{ $bom->ref_no }}">
                    </div>
                </div>
                <div class="col-lg-3 col-sm-4 col-12">
                    <div class="mb-3">
                        <label for="product_id" class="form-label">Part No.</label>
                        <select disabled id="product_id" class="form-select">
                            <option>
                                {{ $bom->products->part_no }}
                            </option>
                        </select>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-4 col-12">
                    <div class="mb-3">
                        <label for="part_name" class="form-label">Part Name:</label>
                        <input type="text" readonly id="part_name" class="form-control"
                            value="{{ $bom->products->part_name }}">
                    </div>
                </div>
                <div class="col-lg-3 col-sm-4 col-12">
                    <div class="mb-3">
                        <label for="customer_name" class="form-label">Customer Name:</label>
                        <input type="text" readonly id="customer_name" class="form-control"
                            value="{{ $bom->products->customers->name ?? '' }}">
                    </div>
                </div>
                <div class="col-lg-3 col-sm-4 col-12">
                    <div class="mb-3">
                        <label for="customer_product_code" class="form-label">Customer Product Code:</label>
                        <input type="text" readonly id="customer_product_code" class="form-control"
                            value="{{ $bom->products->customer_product_code ?? '' }}">
                    </div>
                </div>
                <div class="col-lg-3 col-sm-4 col-12">
                    <div class="mb-3">
                        <label for="model" class="form-label">Model:</label>
                        <input type="text" readonly  id="model" class="form-control"
                            value="{{ $bom->products->model }}">
                    </div>
                </div>
                <div class="col-lg-3 col-sm-4 col-12">
                    <div class="mb-3">
                        <label for="variance" class="form-label">Variance:</label>
                        <input type="text" readonly  id="variance" class="form-control"
                            value="{{ $bom->products->variance }}">
                    </div>
                </div>
                <div class="col-lg-3 col-sm-4 col-12">
                    <div class="mb-3">
                        <label for="part_weight" class="form-label">Part Weight:</label>
                        <input type="text" readonly name="part_weight" id="part_weight" class="form-control"
                            value="{{ $bom->products->part_weight }}">
                    </div>
                </div>

                <div class="col-lg-3 col-sm-4 col-12">
                    <div class="mb-3">
                        <label for="created_date" class="form-label">Created Date</label>
                        <input type="date" readonly name="created_date" id="created_date" class="form-control"
                            value="{{ $bom->created_date }}">
                    </div>
                </div>
                <div class="col-lg-6 col-sm-4 col-12">
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" name="description" id="description" rows="5" disabled>{{ $bom->description }}</textarea>
                    </div>
                </div>
                @if ($bom->attachment1)
                <div class="col-lg-4 col-sm-4 col-12">
                    <label for="inputGroupFileAddon03" class="form-label">Attachment 1</label>
                    <div class="input-group mb-3">
                        <a href="{{ asset('/bom-attachments/') }}/{{ $bom->attachment1 }}" target="_blank"
                            class="btn btn-outline-secondary" type="button" id="inputGroupFileAddon03">
                            <i class="bi bi-file-text"></i>{{ substr($bom->attachment1, 14) }}
                        </a>
                    </div>
                </div>
                @endif
                @if ($bom->attachment2)
                <div class="col-lg-4 col-sm-4 col-12">
                    <label for="inputGroupFileAddon03" class="form-label">Attachment 2</label>
                    <div class="input-group mb-3">
                        <a href="{{ asset('/bom-attachments/') }}/{{ $bom->attachment2 }}" target="_blank"
                            class="btn btn-outline-secondary" type="button" id="inputGroupFileAddon03">
                            <i class="bi bi-file-text"></i>{{ substr($bom->attachment2, 14) }}
                        </a>
                    </div>
                </div>
                @endif
                @if ($bom->attachment3)
                <div class="col-lg-4 col-sm-4 col-12">
                    <label for="inputGroupFileAddon03" class="form-label">Attachment 3</label>
                    <div class="input-group mb-3">
                        <a href="{{ asset('/bom-attachments/') }}/{{ $bom->attachment3 }}" target="_blank"
                            class="btn btn-outline-secondary" type="button" id="inputGroupFileAddon03">
                            <i class="bi bi-file-text"></i>{{ substr($bom->attachment3, 14) }}
                        </a>
                    </div>
                </div>
                @endif
            </div>
            <br>

            {{-- Material and Purchase Part --}}
            <div class="row">
                <div class="col-12 d-flex justify-content-between">
                    <h5>Material and Purchase Part</h5>
                </div>
            </div>
            <br>
            <div class="row">
                <div class="table-responsive">
                    <table class="table table-bordered m-0" id="material_pruchase_part_Table">
                        <thead>
                            <tr>
                                <th>Sr No</th>
                                <th>Part No</th>
                                <th>Part Name</th>
                                <th>Type of Product</th>
                                <th>Unit</th>
                                <th>Qty</th>
                                <th>Remarks</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($purchase_parts as $purchase_part)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{$purchase_part->product->part_no}}</td>
                                    <td>{{$purchase_part->product->part_name}}</td>
                                    <td>{{$purchase_part->product->type_of_products->type}}</td>
                                    <td>{{$purchase_part->product->units->name ?? ''}}</td>
                                    <td>{{$purchase_part->qty}}</td>
                                    <td>
                                        @php
                                            $text = '';
                                            $color = '';
                                            $state = '';
                                            if($purchase_part->remarks == '' || $purchase_part->remarks == null){
                                                $text = 'No Remarks';
                                                $color = 'danger';
                                                $state = 'disabled';
                                            }else{
                                                $text = 'View';
                                                $color = 'success';
                                                $state = '';
                                            }
                                        @endphp
                                        <button type="button" class="btn btn-sm add-remarks btn-{{ $color }}" {{ $state }} >{{ $text }}</button>
                                        <input type="hidden" class="remarks" value="{{$purchase_part->remarks ?? ''}}">
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <br>
            {{-- Crushing Material --}}
            <div class="row">
                <div class="col-12 d-flex justify-content-between">
                    <h5>Crushing Material</h5>
                </div>
            </div>
            <br>
            <div class="row">
                <div class="table-responsive">
                    <table class="table table-bordered m-0" id="crushing_Table">
                        <thead>
                            <tr>
                                <th>Sr No</th>
                                <th>Part No</th>
                                <th>Part Name</th>
                                <th>Type of Product</th>
                                <th>Unit</th>
                                <th>Remarks</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($crushings as $crushing)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{$crushing->product->part_no}}</td>
                                    <td>{{$crushing->product->part_name}}</td>
                                    <td>{{$crushing->product->type_of_products->type}}</td>
                                    <td>{{$crushing->product->units->name ?? ''}}</td>
                                    <td>
                                        @php
                                            $text = '';
                                            $color = '';
                                            $state = '';
                                            if($crushing->remarks == '' || $crushing->remarks == null){
                                                $text = 'No Remarks';
                                                $color = 'danger';
                                                $state = 'disabled';
                                            }else{
                                                $text = 'View';
                                                $color = 'success';
                                                $state = '';
                                            }
                                        @endphp
                                        <button type="button" class="btn btn-sm add-remarks btn-{{ $color }}" {{ $state }} >{{ $text }}</button>
                                        <input type="hidden" class="remarks" value="{{$crushing->remarks ?? ''}}">
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <br>

            {{-- Sub Part --}}
            <div class="row">
                <div class="col-12 d-flex justify-content-between">
                    <h5>Sub Part</h5>
                </div>
            </div>
            <br>
            <div class="row">
                <div class="table-responsive">
                    <table class="table table-bordered m-0" id="subpart_Table">
                        <thead>
                            <tr>
                                <th>Sr No</th>
                                <th>Part No</th>
                                <th>Part Name</th>
                                <th>Type of Product</th>
                                <th>Unit</th>
                                <th>QTY</th>
                                <th>Remarks</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($sub_parts as $sub_part)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{$sub_part->product->part_no}}</td>
                                    <td>{{$sub_part->product->part_name}}</td>
                                    <td>{{$sub_part->product->type_of_products->type}}</td>
                                    <td>{{$sub_part->product->units->name ?? ''}}</td>
                                    <td>{{$sub_part->qty}}</td>
                                    <td>
                                        @php
                                            $text = '';
                                            $color = '';
                                            $state = '';
                                            if($sub_part->remarks == '' || $sub_part->remarks == null){
                                                $text = 'No Remarks';
                                                $color = 'danger';
                                                $state = 'disabled';
                                            }else{
                                                $text = 'View';
                                                $color = 'success';
                                                $state = '';
                                            }
                                        @endphp
                                        <button type="button" class="btn btn-sm add-remarks btn-{{ $color }}" {{ $state }} >{{ $text }}</button>
                                        <input type="hidden" class="remarks" value="{{$sub_part->remarks ?? ''}}">
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <br>

            {{-- Process Material --}}
            <div class="row">
                <div class="col-12 d-flex justify-content-between">
                    <h5>Process</h5>
                </div>
            </div>
            <br>
            <div class="row">
                <div class="table-responsive">
                    <table class="table table-bordered m-0" id="process_Table">
                        <thead>
                            <tr>
                                <th>Sr No</th>
                                <th>Process</th>
                                <th>Process Number</th>
                                <th>Raw Part</th>
                                <th>Sub Part</th>
                                <th>Supplier</th>
                                <th>Machine Tonnage</th>
                                <th>Cavity</th>
                                <th>CT</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($processes as $process)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{$process->process->name ?? ''}}</td>
                                    <td>{{$process->process_no ?? ''}}</td>
                                    <td>
                                        <select class="form-select" disabled multiple>
                                            @php
                                                  $selectedRawProducts = json_decode($process->raw_part_ids);
                                                        if (is_null($selectedRawProducts)) {
                                                            $selectedRawProducts = [];
                                                        }
                                            @endphp
                                            @foreach ($products as $product)
                                                @if (in_array($product->id, $selectedRawProducts))
                                                    <option selected>{{ $product->part_name }}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <select class="form-select" disabled multiple>
                                            @php
                                                $selectedsubpartProducts = json_decode($process->sub_part_ids);
                                                if (is_null($selectedsubpartProducts)) {
                                                            $selectedsubpartProducts = [];
                                                        }
                                            @endphp
                                            @foreach ($products as $product)
                                                @if (in_array($product->id, $selectedsubpartProducts))
                                                    <option selected>{{ $product->part_name }}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        @foreach ($suppliers as $supplier)
                                            @if ($supplier->id == $process->supplier_id)
                                                {{ $supplier->name }}
                                            @endif
                                        @endforeach
                                    </td>
                                    <td>
                                        @foreach ($machine_tonnages as $machine_tonnage)
                                            @if ($machine_tonnage->id == $process->machine_tonnage_id)
                                                {{ $machine_tonnage->tonnage }}
                                            @endif
                                        @endforeach
                                    </td>
                                    <td>{{ $process->cavity }}</td>
                                    <td>{{ $process->ct }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <br>


        </div>
    </div>
    <div class="card mt-5">
        <div class="card-body">
            <div class="row">
                <div class="col-sm-12 my-5">
                    <h6>BOM Verfication History</h6>
                    <div class="table-responsive">
                    <table class="table table-bordered m-0" id="mainTable">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Date</th>
                                <th>Approver</th>
                                <th>Department</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                use App\Models\Department;
                                use App\Models\Designation;
                                use App\Models\User;
                            @endphp
                            @foreach ($bom_verifications as $bom_verification)
                            <tr>
                                <td>
                                    {{ $loop->iteration }}
                                </td>
                                <td>
                                    {{ date('d-m-Y',strtotime($bom_verification->date)) }}
                                </td>
                                <td>
                                    @php
                                        $user = User::find($bom_verification->approved_by);
                                    @endphp
                                    {{$user ? $user->user_name : '' }}
                                </td>
                                <td>
                                    @php
                                        $department = Department::find($bom_verification->department_id);
                                    @endphp
                                    {{$department ? $department->name : '' }}
                                </td>

                                <td>
                                    @if ($bom_verification->status == 'Submitted')
                                    <span class="badge border border-warning text-warning">Submitted</span>
                                    @elseif ($bom_verification->status == 'Verified')
                                        <span class="badge border border-primary text-primary">Verified</span>
                                    @elseif ($bom_verification->status == 'Declined')
                                        <span class="badge border border-secondary text-secondary">Declined</span>
                                    @elseif ($bom_verification->status == 'Cancelled')
                                        <span class="badge border border-Danger text-Danger">Cancelled</span>
                                    @elseif ($bom_verification->status == 'Inactive')
                                        <span class="badge border border-dark text-dark">Inactive</span>
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
            <div class="d-flex gap-2 justify-content-start col-12">
                <a type="button" class="btn btn-info" href="{{ route('bom') }}">
                    <i class="bi bi-arrow-left"></i> Back
                </a>
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
                    <textarea id="remarksText" class="form-control" rows="4" placeholder="Enter your remarks here" disabled></textarea>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <script>
        let material_pruchase_part_Table;
        let crushing_Table;
        let subpart_Table;
        let process_Table;
        $(document).ready(function() {
            material_pruchase_part_Table =  $('#material_pruchase_part_Table').DataTable();
            crushing_Table = $('#crushing_Table').DataTable();
            subpart_Table = $('#subpart_Table').DataTable();
            process_Table = $('#process_Table').DataTable();
        });
    // material pruchase part Remarks
        $('#material_pruchase_part_Table tbody').on('click', '.add-remarks', function() {
            selectedRow = material_pruchase_part_Table.row($(this).closest('tr'));
            $('#remarksText').val($(this).closest('tr').find('.remarks').val());
            $('#remarksModal').modal('show');
        });
    // crushing Table Remarks
        $('#crushing_Table tbody').on('click', '.add-remarks', function() {
            selectedRow = crushing_Table.row($(this).closest('tr'));
            $('#remarksText').val($(this).closest('tr').find('.remarks').val());
            $('#remarksModal').modal('show');
        });
    // subpart Table Remarks
        $('#subpart_Table tbody').on('click', '.add-remarks', function() {
            selectedRow = subpart_Table.row($(this).closest('tr'));
            $('#remarksText').val($(this).closest('tr').find('.remarks').val());
            $('#remarksModal').modal('show');
        });
    </script>
@endsection
