@extends('layouts.app')
@section('title')
    GENERAL SETTINGS
@endsection
@section('content')
    <div class="row gx-3">
        <div class="col-xxl-12">
            <div class="card">
                <div class="card-body">
                    <!-- Custom tabs start -->
                    <div class="custom-tabs-container">
                        <!-- Nav tabs start -->
                        <ul class="nav nav-tabs" id="customTab2" role="tablist">
                            @php
                                $activeTab = '';
                                if (auth()->user()->can('General Settings SST Percentage')) {
                                    $activeTab = 'oneB';
                                } elseif (auth()->user()->can('General Settings PO Important Note')) {
                                    $activeTab = 'oneA';
                                } elseif (auth()->user()->can('General Settings Spec Break')) {
                                    $activeTab = 'fourA';
                                } elseif (auth()->user()->can('General Settings Initial Ref No')) {
                                    $activeTab = 'fourB';
                                } elseif (auth()->user()->can('General Settings PR Approval')) {
                                    $activeTab = 'fourC';
                                } elseif (auth()->user()->can('General Settings Payroll Setup')) {
                                    $activeTab = 'fourD';
                                }

                                if ($active) {
                                    if ($active == 1) {
                                        $activeTab = 'oneB';
                                    } elseif ($active == 2) {
                                        $activeTab = 'oneA';
                                    } elseif ($active == 3) {
                                        $activeTab = 'fourA';
                                    } elseif ($active == 4) {
                                        $activeTab = 'fourB';
                                    } elseif ($active == 5) {
                                        $activeTab = 'fourC';
                                    } elseif ($active == 6) {
                                        $activeTab = 'fourD';
                                    }
                                }
                            @endphp
                            @can('General Settings SST Percentage')
                                <li class="nav-item @if ($activeTab === 'oneB') active @endif" role="presentation">
                                    <a class="nav-link @if ($activeTab === 'oneB') active @endif" id="tab-oneB"
                                        data-bs-toggle="tab" href="#oneB" role="tab" aria-controls="oneB"
                                        aria-selected="true"><i class="bi bi-percent me-2"></i> SST
                                        Percentage</a>
                                </li>
                            @endcan
                            @can('General Settings PO Important Note')
                                <li class="nav-item @if ($activeTab === 'oneA') active @endif" role="presentation">
                                    <a class="nav-link @if ($activeTab === 'oneA') active @endif" id="tab-oneA"
                                        data-bs-toggle="tab" href="#oneA" role="tab" aria-controls="oneA"
                                        aria-selected="false"><i class="bi bi-journal-text me-2"></i> PO
                                        Important Note</a>
                                </li>
                            @endcan
                            @can('General Settings Spec Break')
                                <li class="nav-item @if ($activeTab === 'fourA') active @endif" role="presentation">
                                    <a class="nav-link @if ($activeTab === 'fourA') active @endif" id="tab-fourA"
                                        data-bs-toggle="tab" href="#fourA" role="tab" aria-controls="fourA"
                                        aria-selected="false"><i class="bi bi-exclamation-triangle me-2"></i>Spec Break</a>
                                </li>
                            @endcan
                            @can('General Settings Initial Ref No')
                                <li class="nav-item @if ($activeTab === 'fourB') active @endif" role="presentation">
                                    <a class="nav-link @if ($activeTab === 'fourB') active @endif" id="tab-fourB"
                                        data-bs-toggle="tab" href="#fourB" role="tab" aria-controls="fourB"
                                        aria-selected="false"><i class="bi bi-file-earmark-code me-2"></i>Initial Ref No</a>
                                </li>
                            @endcan
                            @can('General Settings PR Approval')
                                <li class="nav-item @if ($activeTab === 'fourC') active @endif" role="presentation">
                                    <a class="nav-link @if ($activeTab === 'fourC') active @endif" id="tab-fourC"
                                        data-bs-toggle="tab" href="#fourC" role="tab" aria-controls="fourC"
                                        aria-selected="false"><i class="bi bi-check-circle me-2"></i>PR
                                        Approval</a>
                                </li>
                            @endcan
                            @can('General Settings Payroll Setup')
                                <li class="nav-item @if ($activeTab === 'fourD') active @endif" role="presentation">
                                    <a class="nav-link @if ($activeTab === 'fourD') active @endif" id="tab-fourD"
                                        data-bs-toggle="tab" href="#fourD" role="tab" aria-controls="fourD"
                                        aria-selected="false"><i class="bi bi-cash-coin me-2"></i>PAYROLL SETUP</a>
                                </li>
                            @endcan
                        </ul>
                        <!-- Nav tabs end -->

                        <!-- Tab content start -->
                        <div class="tab-content">
                            <!-- SST Percentage Tab Content -->
                            @can('General Settings SST Percentage')
                                <div class="tab-pane fade @if ($activeTab === 'oneB') active show @endif" id="oneB"
                                    role="tabpanel" aria-labelledby="tab-oneB">
                                    <div class="row gx-3">
                                        <div class="col-sm-12 col-12">
                                            <div class="card border mb-3">
                                                <div class="card-body">
                                                    <!-- SST Percentage Form -->
                                                    <form action="{{ route('general_setting.updateSST') }}" method="POST">
                                                        @csrf
                                                        <div class="mb-3 col-md-6">
                                                            <label for="sstPercentage" class="form-label">SST Percentage</label>
                                                            <div class="input-group">
                                                                <span class="input-group-text">
                                                                    <i class="bi bi-percent"></i>
                                                                </span>
                                                                <input type="number" class="form-control" id="sstPercentage"
                                                                    name="sst_percentage"
                                                                    value="{{ $sstPercentages->sst_percentage }}"
                                                                    placeholder="Enter SST Percentage">
                                                            </div>
                                                        </div>
                                                        <div class="d-flex gap-2 justify-content-end">
                                                            <button type="submit" class="btn btn-primary">Update</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endcan
                            @can('General Settings PO Important Note')
                                <!-- PO Important Note Tab Content -->
                                <div class="tab-pane fade @if ($activeTab === 'oneA') active show @endif" id="oneA"
                                    role="tabpanel" aria-labelledby="tab-oneA">
                                    <div class="row gx-3">
                                        <div class="col-sm-12 col-12">
                                            <div class="card border mb-3">
                                                <div class="card-body">
                                                    <!-- PO Important Note Form -->
                                                    <form action="{{ route('general_setting.updatePO') }}" method="POST">
                                                        @csrf
                                                        <div class="mb-3 col-md-6">
                                                            <label for="poNote" class="form-label">PO Important Note</label>
                                                            <div class="input-group">
                                                                <span class="input-group-text">
                                                                    <i class="bi bi-journal-text"></i>
                                                                </span>
                                                                <textarea class="form-control" id="poNote" name="po_note" placeholder="Enter PO Important Note">{{ $poimportantnotes->po_note }}</textarea>
                                                            </div>
                                                        </div>
                                                        <div class="d-flex gap-2 justify-content-end">
                                                            <button type="submit" class="btn btn-primary">Update</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endcan
                            @can('General Settings Spec Break')
                                <!-- Spec Break Tab Content -->
                                <div class="tab-pane fade @if ($activeTab === 'fourA') active show @endif"
                                    id="fourA" role="tabpanel" aria-labelledby="tab-fourA">
                                    <div class="row gx-3">
                                        <div class="col-sm-12 col-12">
                                            <div class="card border mb-3">
                                                <div class="card-body">
                                                    <!-- Spec Break Form -->
                                                    <form action="{{ route('general_setting.updateSB') }}" method="POST">
                                                        @csrf
                                                        <div class="row">
                                                            <div class="mb-3 col-md-6">
                                                                <label for="normalHour" class="form-label">Normal Hour</label>
                                                                <div class="input-group">
                                                                    <span class="input-group-text">
                                                                        <i class="bi bi-clock"></i>
                                                                    </span>
                                                                    <input type="number" class="form-control"
                                                                        id="normalHour" name="normal_hour"
                                                                        placeholder="Enter Normal Hour"
                                                                        value="{{ $specbreaks->normal_hour }}">
                                                                </div>
                                                            </div>
                                                            <div class="mb-3 col-md-6">
                                                                <label for="otHour" class="form-label">OT Hour</label>
                                                                <div class="input-group">
                                                                    <span class="input-group-text">
                                                                        <i class="bi bi-clock-history"></i>
                                                                    </span>
                                                                    <input type="number" class="form-control" id="otHour"
                                                                        name="ot_hour" placeholder="Enter OT Hour"
                                                                        value="{{ $specbreaks->ot_hour }}">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="d-flex gap-2 justify-content-end">
                                                            <button type="submit" class="btn btn-primary">Update</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endcan
                            @can('General Settings Initial Ref No')
                                <!-- Initial Ref No Tab Content -->
                                <div class="tab-pane fade @if ($activeTab === 'fourB') active show @endif"
                                    id="fourB" role="tabpanel" aria-labelledby="tab-fourB">
                                    <div class="row gx-3">
                                        <div class="col-sm-12 col-12">
                                            <div class="card border mb-3">
                                                <div class="card-header d-flex justify-content-end">
                                                    <button type="button" class="btn btn-primary" id="addRow">+
                                                        Add Row</button>
                                                </div>
                                                <div class="card-body">
                                                    <!-- Initial Ref No Form -->
                                                    <form id="initialFrom" action="{{ route('general_setting.updateRefNo') }}"
                                                        method="POST">
                                                        @csrf
                                                        <div class="table-responsive">
                                                            <table class="table table-bordered w-100" id="initialRefNoTable">
                                                                <thead>
                                                                    <tr>
                                                                        <th>Sr No.</th>
                                                                        <th>Screen</th>
                                                                        <th>Ref No.</th>
                                                                        <th>Running No.</th>
                                                                        <th>Sample</th>
                                                                        <th>Action</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    @foreach ($initialRefNo as $initialRef)
                                                                        <tr>
                                                                            <td>{{ $loop->iteration }}</td>
                                                                            <td>
                                                                                <select
                                                                                    name="initial[{{ $loop->iteration }}][screen]"
                                                                                    class="form-control w-100 screen"
                                                                                    onchange="updateSample(this); updateDropdownOptions();">
                                                                                    <option value="Quotation"
                                                                                        @selected($initialRef->screen == 'Quotation')>Quotation
                                                                                    </option>
                                                                                    <option value="Delivery Order"
                                                                                        @selected($initialRef->screen == 'Delivery Order')>Delivery
                                                                                        Order</option>
                                                                                    <option value="Purchase Planning"
                                                                                        @selected($initialRef->screen == 'Purchase Planning')>Purchase
                                                                                        Planning</option>
                                                                                    <option value="Purchase Requisition"
                                                                                        @selected($initialRef->screen == 'Purchase Requisition')>Purchase
                                                                                        Requisition</option>
                                                                                    <option value="Purchase Order"
                                                                                        @selected($initialRef->screen == 'Purchase Order')>Purchase
                                                                                        Order</option>
                                                                                    <option value="Material Requisition"
                                                                                        @selected($initialRef->screen == 'Material Requisition')>Material
                                                                                        Requisition</option>
                                                                                    <option value="Transfer Request"
                                                                                        @selected($initialRef->screen == 'Transfer Request')>Transfer
                                                                                        Request</option>
                                                                                    <option value="Discrepancy"
                                                                                        @selected($initialRef->screen == 'Discrepancy')>Discrepancy
                                                                                    </option>
                                                                                    <option value="Stock Relocation"
                                                                                        @selected($initialRef->screen == 'Stock Relocation')>Stock
                                                                                        Relocation</option>
                                                                                    <option value="Sales Return"
                                                                                        @selected($initialRef->screen == 'Sales Return')>Sales
                                                                                        Return</option>
                                                                                    <option value="Purchase Return"
                                                                                        @selected($initialRef->screen == 'Purchase Return')>Purchase
                                                                                        Return</option>
                                                                                    <option value="Good Receiving"
                                                                                        @selected($initialRef->screen == 'Good Receiving')>Good
                                                                                        Receiving</option>
                                                                                    <option value="Daily Production Planning"
                                                                                        @selected($initialRef->screen == 'Daily Production Planning')>Good
                                                                                        Receiving</option>
                                                                                </select>
                                                                            </td>
                                                                            <td><input
                                                                                    name="initial[{{ $loop->iteration }}][ref_no]"
                                                                                    type="text" class="form-control ref-no"
                                                                                    value="{{ $initialRef->ref_no }}"
                                                                                    onchange="updateSample(this)">
                                                                            </td>
                                                                            <td><input
                                                                                    name="initial[{{ $loop->iteration }}][running_no]"
                                                                                    type="number"
                                                                                    class="form-control running-no"
                                                                                    value="{{ $initialRef->running_no }}"
                                                                                    onchange="updateSample(this)">
                                                                            </td>
                                                                            <td><input type="text"
                                                                                    name="initial[{{ $loop->iteration }}][sample]"
                                                                                    class="form-control sample"
                                                                                    value="{{ $initialRef->sample }}" readonly
                                                                                    placeholder="Sample"></td>
                                                                            <td><button type="button"
                                                                                    class="btn btn-sm btn-danger remove-row"><i
                                                                                        class="bi bi-trash"></i></button>
                                                                            </td>
                                                                        </tr>
                                                                    @endforeach
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                        <div class="d-flex gap-2 justify-content-end mt-3">
                                                            <button type="submit" class="btn btn-primary">Update</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endcan
                            @can('General Settings PR Approval')
                                <!-- PR Approval Tab Content -->
                                <div class="tab-pane fade @if ($activeTab === 'fourC') active show @endif"
                                    id="fourC" role="tabpanel" aria-labelledby="tab-fourC">
                                    <div class="row gx-3">
                                        <div class="col-sm-12 col-12">
                                            <div class="card border mb-3">
                                                <div class="card-header d-flex justify-content-end">
                                                    <button type="button" class="btn btn-primary" id="addRowPR">+
                                                        Add Row</button>
                                                </div>
                                                <div class="card-body">
                                                    <!-- PR Approval Form -->
                                                    <form id="PRFrom" action="{{ route('general_setting.updatePR') }}"
                                                        method="POST">
                                                        @csrf
                                                        <div class="table-responsive">
                                                            <table class="table table-bordered w-100" id="PRTable">
                                                                <thead>
                                                                    <tr>
                                                                        <th>Sr No.</th>
                                                                        <th>Designation</th>
                                                                        <th>less < more than</th>
                                                                        <th>Amount (RM)</th>
                                                                        <th>Category</th>
                                                                        <th>Action</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    @foreach ($prapprovals as $prapproval)
                                                                        <tr>
                                                                            <td>{{ $loop->iteration }}</td>
                                                                            <td>
                                                                                <select
                                                                                    name="pr[{{ $loop->iteration }}][designation]"
                                                                                    class="form-control w-100 designation"
                                                                                    onchange="updateDropdownDesignations();">
                                                                                    @foreach ($designations as $designation)
                                                                                        <option value="{{ $designation->id }}"
                                                                                            @selected($prapproval->designation_id == $designation->id)>
                                                                                            {{ $designation->name }}
                                                                                        </option>
                                                                                    @endforeach
                                                                                </select>
                                                                            </td>
                                                                            <td><select
                                                                                    name="pr[{{ $loop->iteration }}][operator]"
                                                                                    class="form-control w-100 operator">
                                                                                    <option value="<"
                                                                                        @selected($prapproval->operator == '<')><</option>
                                                                                    <option value=">"
                                                                                        @selected($prapproval->operator == '>')>></option>
                                                                                    <option value="="
                                                                                        @selected($prapproval->operator == '=')>=</option>
                                                                                </select>
                                                                            </td>
                                                                            <td><input
                                                                                    name="pr[{{ $loop->iteration }}][amount]"
                                                                                    type="number" class="form-control amount"
                                                                                    value="{{ $prapproval->amount }}"><span
                                                                                    class="d-none amount1"></span>
                                                                            </td>
                                                                            <td><select
                                                                                name="pr[{{ $loop->iteration }}][category]" id="categorySelect" onchange="handleCategoryChange()"
                                                                                class="form-control w-100 category" >
                                                                                <option value="Printing & Stationary"
                                                                                    @selected($prapproval->category == 'Printing & Stationary')>Printing & Stationary</option>
                                                                                <option value="Direct Item"
                                                                                    @selected($prapproval->category == 'Direct Item')>Direct Item</option>
                                                                                <option value="Urgent"
                                                                                    @selected($prapproval->category == 'Urgent')>Indirect Item</option>
                                                                                <option value="Asset"
                                                                                    @selected($prapproval->category == 'Asset')>Asset</option>
                                                                                <option value="Others"
                                                                                    @selected($prapproval->category == 'Others')>Others</option>
                                                                            </select>
                                                                            <div class=" @if (!$prapproval->category_other) d-none @endif" id="other_div">
                                                                                <div class="mb-3">
                                                                                    <label for=""  class="form-label">Others:</label>
                                                                                    <input type="text" name="pr[{{ $loop->iteration }}][category_other]" value="{{ $prapproval->category_other }}"
                                                                                        class="form-control">
                                                                                </div>
                                                                            </div>
                                                                        </td>
                                                                            <td><button type="button"
                                                                                    class="btn btn-sm btn-danger remove-row-pr"><i
                                                                                        class="bi bi-trash"></i></button>
                                                                            </td>
                                                                        </tr>
                                                                    @endforeach
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                        <div class="d-flex gap-2 justify-content-end mt-3">
                                                            <button type="submit" class="btn btn-primary">Update</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endcan
                            @can('General Settings Payroll Setup')
                                <!-- PR Approval Tab Content -->
                                <div class="tab-pane fade @if ($activeTab === 'fourD') active show @endif"
                                    id="fourD" role="tabpanel" aria-labelledby="tab-fourC">
                                    <div class="row gx-3">
                                        <div class="col-sm-12 col-12">
                                            <div class="card border mb-3">
                                                <div class="card-header d-flex justify-content-end">
                                                    <legend class="fs-4">Payroll Standard Setting</legend>
                                                </div>
                                                <div class="card-body">
                                                    <!-- PR Approval Form -->
                                                    <form id="PRFrom" action="{{ route('general_setting.updatePS') }}"
                                                        method="POST">
                                                        @csrf
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <div class="d-flex mb-2">
                                                                    <h5>HRDF</h5>
                                                                    {{-- <div class="mt-4 d-flex"> --}}
                                                                    <label class=" mx-2" for="hrdf">(
                                                                        NO</label>
                                                                    {{-- <div class="d-flex align-items-center"> --}}
                                                                    <div class="form-check form-switch">
                                                                        <input class="form-check-input" type="checkbox"
                                                                            id="hrdf" name="hrdf"
                                                                            @checked($payroll_setup->hrdf ?? 0) value="1">
                                                                    </div>
                                                                    {{-- </div> --}}
                                                                    <label class="form-check-label" for="hrdf">YES
                                                                        )</label>
                                                                    {{-- </div> --}}
                                                                </div>
                                                                <label for="" class="form-label">Percentage
                                                                    (%)</label>
                                                                <div class="input-group">
                                                                    <input type="number" class="form-control" id="a13"
                                                                        name="hrdf_per" min="1" max="100" step="1" value="{{ $payroll_setup->hrdf_per ?? '' }}">
                                                                    <span class="input-group-text">%</span>
                                                                </div>


                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="d-flex mb-2">
                                                                    <h5>Payslip</h5>
                                                                    {{-- <div class="mt-4 d-flex"> --}}
                                                                    <label class="mx-2" for="hrdf">(
                                                                        NO</label>
                                                                    {{-- <div class="d-flex align-items-center"> --}}
                                                                    <div class="form-check form-switch">
                                                                        <input class="form-check-input" type="checkbox"
                                                                            id="hrdf" name="paysilp"
                                                                            @checked($payroll_setup->paysilp ?? 0) value="1">
                                                                    </div>
                                                                    {{-- </div> --}}
                                                                    <label class="form-check-label" for="hrdf">YES
                                                                        )</label>
                                                                    {{-- </div> --}}
                                                                </div>
                                                                <label for="" class="form-label">Payslip remarks at
                                                                    Pay Slip Print</label>
                                                                <textarea name="paysilp_remarks" class="form-control" id="payslip" cols="30" rows="2">{{ $payroll_setup->paysilp_remarks ?? '' }}</textarea>
                                                            </div>
                                                        </div>

                                                        <div class="row mt-3">
                                                            <div class="col-md-6">
                                                                <div class="d-flex mb-2">
                                                                    <h5>KWSP/EPF</h5>
                                                                    {{-- <div class="mt-4 d-flex"> --}}
                                                                    <label class="mx-2" for="hrdf">(
                                                                        NO</label>
                                                                    {{-- <div class="d-flex align-items-center"> --}}
                                                                    <div class="form-check form-switch">
                                                                        <input class="form-check-input" type="checkbox"
                                                                            id="hrdf" name="kwsp"
                                                                            @checked($payroll_setup->kwsp ?? 0) value="1">
                                                                    </div>
                                                                    {{-- </div> --}}
                                                                    <label class="form-check-label" for="hrdf">YES
                                                                        )</label>
                                                                    {{-- </div> --}}
                                                                </div>
                                                                <h5 class="mb-2">CATEGORY 1  (Base Salary < 5k) Employees aged below 60 years old</h5>
                                                                <label for="" class="form-label">Employee (%)</label>
                                                                <div class="input-group">
                                                                    <input type="number" class="form-control" id="a13"
                                                                        name="kwsp_category_1_employee_per" value="11" readonly min="1" max="100" step="1" value="{{ $payroll_setup->kwsp_category_1_employee_per ?? '' }}">
                                                                    <span class="input-group-text">%</span>
                                                                </div>
                                                                <br>
                                                                <label for="" class="form-label">Employer (%)</label>
                                                                <div class="input-group">
                                                                    <input type="number" class="form-control" id="a13"
                                                                        name="kwsp_category_1_employer_per" value="13" readonly min="1" max="100" step="1" value="{{ $payroll_setup->kwsp_category_1_employer_per ?? '' }}">
                                                                    <span class="input-group-text">%</span>
                                                                </div>


                                                            </div>
                                                            <div class="col-md-6">
                                                                <br>
                                                                <h5 class="mt-3 mb-2">CATEGORY 2  (Base Salary > 5k) Employees aged below 60 years old </h5>
                                                                <label for="" class="form-label">Employee (%)</label>
                                                                <div class="input-group">
                                                                    <input type="number" class="form-control" id="a23"
                                                                        name="kwsp_category_2_employee_per" value="11" readonly min="1" max="100" step="1" value="{{ $payroll_setup->kwsp_category_2_employee_per ?? '' }}">
                                                                    <span class="input-group-text">%</span>
                                                                </div>
                                                                <br>
                                                                <label for="" class="form-label">Employer (%)</label>
                                                                <div class="input-group">
                                                                    <input type="number" class="form-control" id="a23"
                                                                        name="kwsp_category_2_employer_per" value="12" readonly min="1" max="100" step="1" value="{{ $payroll_setup->kwsp_category_2_employer_per ?? '' }}">
                                                                    <span class="input-group-text">%</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row mt-3">
                                                            <div class="col-md-6">
                                                               
                                                                <h5 class="mb-2">CATEGORY 3  (Base Salary < 5k) Employees aged 60</h5>
                                                                <label for="" class="form-label">Employee (%)</label>
                                                                <div class="input-group">
                                                                    <input type="number" class="form-control" id="a13"
                                                                        name="kwsp_category_3_employee_per" value="0" readonly min="1" max="100" step="1" value="{{ $payroll_setup->kwsp_category_1_employee_per ?? '' }}">
                                                                    <span class="input-group-text">%</span>
                                                                </div>
                                                                <br>
                                                                <label for="" class="form-label">Employer (%)</label>
                                                                <div class="input-group">
                                                                    <input type="number" class="form-control" id="a13"
                                                                        name="kwsp_category_3_employer_per" value="4" readonly min="1" max="100" step="1" value="{{ $payroll_setup->kwsp_category_1_employer_per ?? '' }}">
                                                                    <span class="input-group-text">%</span>
                                                                </div>


                                                            </div>
                                                            <div class="col-md-6">
                                                                <h5 class="mb-2">CATEGORY 4  (Base Salary > 5k) Employees aged 60 </h5>
                                                                <label for="" class="form-label">Employee (%)</label>
                                                                <div class="input-group">
                                                                    <input type="number" class="form-control" id="a23"
                                                                        name="kwsp_category_4_employee_per" value="0" readonly min="1" max="100" step="1" value="{{ $payroll_setup->kwsp_category_2_employee_per ?? '' }}">
                                                                    <span class="input-group-text">%</span>
                                                                </div>
                                                                <br>
                                                                <label for="" class="form-label">Employer (%)</label>
                                                                <div class="input-group">
                                                                    <input type="number" class="form-control" id="a23"
                                                                        name="kwsp_category_4_employer_per" value="4" readonly min="1" max="100" step="1" value="{{ $payroll_setup->kwsp_category_2_employer_per ?? '' }}">
                                                                    <span class="input-group-text">%</span>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="row mt-3">
                                                            <div class="col-md-6">
                                                                <div class="d-flex mb-2">
                                                                    <h5>Employee Voluntary Excess </h5>
                                                                    {{-- <div class="mt-4 d-flex"> --}}
                                                                    <label class="mx-2" for="eve">(
                                                                        NO</label>
                                                                    {{-- <div class="d-flex align-items-center"> --}}
                                                                    <div class="form-check form-switch">
                                                                        <input class="form-check-input" type="checkbox"
                                                                            id="eve" name="eve_employee"
                                                                            @checked($payroll_setup->eve_employee ?? 0) value="1">
                                                                    </div>
                                                                    {{-- </div> --}}
                                                                    <label class="form-check-label" for="eve">YES
                                                                        )</label>
                                                                    {{-- </div> --}}
                                                                </div>
                                                                <label for="" class="form-label">Employee (%) </label>
                                                                <div class="input-group">
                                                                    <input type="number" class="form-control" id="a13"
                                                                        name="eve_employee_per" value="5" readonly min="1" max="100" step="1" value="{{ $payroll_setup->kwsp_category_1_employee_per ?? '' }}">
                                                                    <span class="input-group-text">%</span>
                                                                </div>
                                                               


                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="d-flex mb-2">
                                                                    <h5>Employer Voluntary Excess</h5>
                                                                    {{-- <div class="mt-4 d-flex"> --}}
                                                                    <label class="mx-2" for="hrdf">(
                                                                        NO</label>
                                                                    {{-- <div class="d-flex align-items-center"> --}}
                                                                    <div class="form-check form-switch">
                                                                        <input class="form-check-input" type="checkbox"
                                                                            id="hrdf" name="eve_employer"
                                                                            @checked($payroll_setup->eve_employer ?? 0) value="1">
                                                                    </div>
                                                                    {{-- </div> --}}
                                                                    <label class="form-check-label" for="hrdf">YES
                                                                        )</label>
                                                                    {{-- </div> --}}
                                                                </div>
                                                                <label for="" class="form-label">Employer (%) </label>
                                                                <div class="input-group">
                                                                    <input type="number" class="form-control" id="a13"
                                                                        name="eve_employer_per" value="5" readonly min="1" max="100" step="1" value="{{ $payroll_setup->kwsp_category_1_employee_per ?? '' }}">
                                                                    <span class="input-group-text">%</span>
                                                                </div>
                                                               


                                                            </div>          
                                                        </div>


                                                        <div class="row mt-3">
                                                            <div class="col-md-6">
                                                                <div class="d-flex mb-2">
                                                                    <h5>Employee Voluntary Excess & Over Time</h5>
                                                                    {{-- <div class="mt-4 d-flex"> --}}
                                                                    <label class="mx-2" for="eve_ot">(
                                                                        NO</label>
                                                                    {{-- <div class="d-flex align-items-center"> --}}
                                                                    <div class="form-check form-switch">
                                                                        <input class="form-check-input" type="checkbox"
                                                                            id="eve_ot" name="eve_ot"
                                                                            @checked($payroll_setup->eve_ot ?? 0) value="1">
                                                                    </div>
                                                                    {{-- </div> --}}
                                                                    <label class="form-check-label" for="eve_ot">YES
                                                                        )</label>
                                                                    {{-- </div> --}}
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="d-flex mb-2">
                                                                    <h5>Employee Voluntary Excess & Over Time & Allowance</h5>
                                                                    {{-- <div class="mt-4 d-flex"> --}}
                                                                    <label class="mx-2" for="eve_ot_allowance">(
                                                                        NO</label>
                                                                    {{-- <div class="d-flex align-items-center"> --}}
                                                                    <div class="form-check form-switch">
                                                                        <input class="form-check-input" type="checkbox"
                                                                            id="eve_ot_allowance" name="eve_ot_allowance"
                                                                            @checked($payroll_setup->eve_ot_allowance ?? 0) value="1">
                                                                    </div>
                                                                    {{-- </div> --}}
                                                                    <label class="form-check-label" for="eve_ot_allowance">YES
                                                                        )</label>
                                                                    {{-- </div> --}}
                                                                </div>
                                                            </div>          
                                                        </div>

                                                        <div class="row mt-3">
                                                            <div class="col-md-6">
                                                                <div class="d-flex mb-2">
                                                                    <h5>SOCSO</h5>
                                                                    {{-- <div class="mt-4 d-flex"> --}}
                                                                    <label class="mx-2" for="hrdf">(
                                                                        NO</label>
                                                                    {{-- <div class="d-flex align-items-center"> --}}
                                                                    <div class="form-check form-switch">
                                                                        <input class="form-check-input" type="checkbox"
                                                                            id="hrdf" name="socso"
                                                                            @checked($payroll_setup->socso ?? 0) value="1">
                                                                    </div>
                                                                    {{-- </div> --}}
                                                                    <label class="form-check-label" for="hrdf">YES
                                                                        )</label>
                                                                    {{-- </div> --}}
                                                                </div>
                                                                <label for="" class="form-label">Employee </label>
                                                                    <input type="number" class="form-control" id="a13"
                                                                        name="socso_employee_per" value="{{ $payroll_setup->socso_employee_per ?? '' }}">
                                                                <br>
                                                                <label for="" class="form-label">Employer</label>
                                                                    <input type="number" class="form-control" id="a13"
                                                                        name="socso_employer_per" value="{{ $payroll_setup->socso_employer_per ?? '' }}">


                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="d-flex mb-2">
                                                                    <h5>EIS</h5>
                                                                    {{-- <div class="mt-4 d-flex"> --}}
                                                                    <label class="mx-2" for="hrdf">(
                                                                        NO</label>
                                                                    {{-- <div class="d-flex align-items-center"> --}}
                                                                    <div class="form-check form-switch">
                                                                        <input class="form-check-input" type="checkbox"
                                                                            id="hrdf" name="eis"
                                                                            @checked($payroll_setup->eis ?? 0) value="1">
                                                                    </div>
                                                                    {{-- </div> --}}
                                                                    <label class="form-check-label" for="hrdf">YES
                                                                        )</label>
                                                                    {{-- </div> --}}
                                                                </div>
                                                                <label for="" class="form-label">Employee</label>
                                                                    <input type="number" class="form-control" id="a23"
                                                                        name="eis_employee_per" value="{{ $payroll_setup->eis_employee_per ?? '' }}">
                                                                <br>
                                                                <label for="" class="form-label">Employer</label>
                                                                    <input type="number" class="form-control" id="a23"
                                                                        name="eis_employer_per" value="{{ $payroll_setup->eis_employer_per ?? '' }}">
                                                            </div>
                                                        </div>


                                                        <div class="d-flex gap-2 justify-content-end mt-3">
                                                            <button type="submit" class="btn btn-primary">Update</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endcan
                        </div>
                        <!-- Tab content end -->
                    </div>
                    <!-- Custom tabs end -->
                </div>
            </div>
        </div>
    </div>
    <script>
        var table;
        var tablePR;
        let options = '';
        let designationOptions = '';
        $(document).ready(function() {


            // Toggle switches Jquery
            if ($('#eve').is(':checked')) {
                $('#eve_ot_allowance, #eve_ot').prop('disabled', true);
            } else if ($('#eve_ot_allowance').is(':checked')) {
                $('#eve, #eve_ot').prop('disabled', true);
            } else if ($('#eve_ot').is(':checked')) {
                $('#eve, #eve_ot_allowance').prop('disabled', true);
            }


            $('#eve').on('change', function () {
            const isChecked = $(this).is(':checked');
            $('#eve_ot_allowance, #eve_ot').prop('disabled', isChecked);
            });
            $('#eve_ot_allowance').on('change', function () {
            const isChecked = $(this).is(':checked');
            $('#eve, #eve_ot').prop('disabled', isChecked);
            });
            $('#eve_ot').on('change', function () {
            const isChecked = $(this).is(':checked');
            $('#eve_ot_allowance, #eve').prop('disabled', isChecked);
            });




            let desigOptions = @json($designations);
            table = $('#initialRefNoTable').DataTable({
                columnDefs: [{
                        width: '10%',
                        targets: 0
                    },
                    {
                        width: '10%',
                        targets: -1
                    }
                ],
                lengthMenu: [
                    [25, 50, 100],
                    [25, 50, 100]
                ]
            });

            tablePR = $('#PRTable').DataTable({
                columnDefs: [{
                        width: '10%',
                        targets: 0
                    },
                    {
                        width: '10%',
                        targets: -1
                    }
                ],
                lengthMenu: [
                    [desigOptions.length],
                    [desigOptions.length]
                ]
            });

            options = [{
                    value: 'Quotation',
                    text: 'Quotation'
                },
                {
                    value: 'Delivery Order',
                    text: 'Delivery Order'
                },
                {
                    value: 'Purchase Planning',
                    text: 'Purchase Planning'
                },
                {
                    value: 'Purchase Requisition',
                    text: 'Purchase Requisition'
                },
                {
                    value: 'Purchase Order',
                    text: 'Purchase Order'
                },
                {
                    value: 'Material Requisition',
                    text: 'Material Requisition'
                },
                {
                    value: 'Transfer Request',
                    text: 'Transfer Request'
                },
                {
                    value: 'Discrepancy',
                    text: 'Discrepancy'
                },
                {
                    value: 'Stock Relocation',
                    text: 'Stock Relocation'
                },
                {
                    value: 'Sales Return',
                    text: 'Sales Return'
                },
                {
                    value: 'Purchase Return',
                    text: 'Purchase Return'
                },
                {
                    value: 'Good Receiving',
                    text: 'Good Receiving'
                },
                {
                    value: 'Daily Production Planning',
                    text: 'Daily Production Planning'
                }
            ];

            designationOptions = desigOptions.map(function(value) {
                return {
                    value: value.id,
                    text: value.name
                };
            });

            updateDropdownOptions();
            updateDropdownDesignationOptions();

            let popUp = false;
            // Add row functionality
            $('#addRow').on('click', function() {
                var availableOptions = options.filter(function(option) {
                    return !isOptionSelected(option.value);
                });

                if (availableOptions.length === 0) {
                    popUp = true;
                }

                if (popUp) {
                    $('.tab-pane.active').find('.card-body').prepend(`
                        <div class="alert border-warning alert-dismissible fade show text-warning" role="alert">
                        <b>Warning!</b> Can't add Row (no more screens).
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    `);
                    return;
                }

                if (availableOptions.length === 1) {
                    popUp = true;
                }

                table.row.add([
                    table.rows().count() + 1,
                    createDropdown(table.rows().count() + 1),
                    `<input type="text" name="initial[${table.rows().count() + 1}][ref_no]" class="form-control ref-no" value="" placeholder="Ref No." onchange="updateSample(this)"><span class="d-none sample1"></span>`,
                    `<input type="number" name="initial[${table.rows().count() + 1}][running_no]" class="form-control running-no" value="1" placeholder="Running No." onchange="updateSample(this)">`,
                    `<input type="text" name="initial[${table.rows().count() + 1}][sample]" class="form-control sample" value="" readonly placeholder="Sample">`,
                    '<button type="button" class="btn btn-sm btn-danger remove-row"><i class="bi bi-trash"></i></button>'
                ]).draw(false).node();
                updateDropdownOptions();
            });

            let popUpPR = false;
            // Add row functionality PR
            $('#addRowPR').on('click', function() {
                var availableOptionsPR = designationOptions.filter(function(option1) {
                    return !isOptionSelectedDesignation(option1.value);
                });

                if (availableOptionsPR.length === 0) {
                    popUpPR = true;
                }

                if (popUpPR) {
                    $('.tab-pane.active').find('.card-body').prepend(`
                        <div class="alert border-warning alert-dismissible fade show text-warning" role="alert">
                        <b>Warning!</b> Can't add Row (no more designations).
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    `);
                    return;
                }

                if (availableOptionsPR.length === 1) {
                    popUpPR = true;
                }

                tablePR.row.add([
                    tablePR.rows().count() + 1,
                    createDropdownDesignation(tablePR.rows().count() + 1),
                    `<select name="pr[${tablePR.rows().count() + 1}][operator]" class="form-control w-100 operator">
                        <option value="<"><</option>
                        <option value=">">></option>
                        <option value="=">=</option>
                    </select>`,
                    `<input type="number" name="pr[${tablePR.rows().count() + 1}][amount]" class="form-control amount" value="" onchange="updateAmount(this)" placeholder="Amount"><span class="d-none amount1"></span>`,
                    `<select name="pr[${tablePR.rows().count() + 1}][category]" class="form-control w-100 category">
                        <option value="Printing & Stationary">Printing & Stationary</option>
                        <option value="Direct Item">Direct Item</option>
                        <option value="Urgent">Urgent</option>
                        <option value="Asset">Asset</option>
                        <option value="Others">Others</option>
                    </select> 
                     <div class="d-none" id="other_div">
                        <div class="mb-3">
                            <label for="" class="form-label">Others:</label>
                            <input type="text" value=""
                                class="form-control">
                        </div>
                    </div>`,
                    '<button type="button" class="btn btn-sm btn-danger remove-row-pr"><i class="bi bi-trash"></i></button>'
                ]).draw(false).node();
                updateDropdownDesignationOptions();
            });

            // Remove row functionality
            $('#initialRefNoTable tbody').on('click', '.remove-row', function() {
                table.row($(this).parents('tr')).remove().draw();
                updateDropdownOptions();
                resetSerialNumbers();
            });

            // Remove PR row functionality
            $('#PRTable tbody').on('click', '.remove-row-pr', function() {
                tablePR.row($(this).parents('tr')).remove().draw();
                updateDropdownDesignationOptions();
                resetDesignationSerialNumbers();
            });
        });

        // add update sample functionality
        function updateSample(element) {
            var row = $(element).closest('tr');
            var refNo = row.find('.ref-no').val();
            var runningNo = row.find('.running-no').val();
            var currentYear = new Date().getFullYear();
            var sample = refNo + '/' + runningNo + '/' + currentYear;
            row.find('.sample').val(sample);
            row.find('.sample1').text(sample);
        }

        // add update amount functionality
        function updateAmount(element) {
            var row = $(element).closest('tr');
            var val = $(element).val();
            row.find('.amount1').text(val);
        }

        // add reset serail no functionality
        function resetSerialNumbers() {
            if ($('#initialRefNoTable tbody tr:first').find('td:first').text() != 'No data available in table') {
                $('#initialRefNoTable tbody tr').each(function(index) {
                    $(this).find('td:first').text(index + 1);
                });
            }
        }

        // add designation reset serail no functionality
        function resetDesignationSerialNumbers() {
            $('#PRTable tbody tr').each(function(index) {
                $(this).find('td:first').text(index + 1);
            });
        }

        // add update options functionality
        function createDropdown(loop) {
            var select = $(
                '<select class="form-control screen" name="initial[' + loop +
                '][screen]" onchange="updateSample(this); updateDropdownOptions();"></select>');
            var selectedValues = [];
            // Get all currently selected values
            $('#initialRefNoTable .screen').each(function() {
                selectedValues.push($(this).val());
            });

            // Append options excluding those that are already selected
            options.forEach(function(option) {
                if (!selectedValues.includes(option.value)) {
                    select.append($('<option>', {
                        value: option.value,
                        text: option.text
                    }));
                }
            });
            return select.prop('outerHTML');
        }

        // add create designation options functionality
        function createDropdownDesignation(loop_designation) {
            var select1 = $(
                '<select class="form-control designation" name="pr[' + loop_designation +
                '][designation]" onchange="updateDropdownDesignationOptions();"></select>');
            var selectedValues1 = [];
            // Get all currently selected values
            $('#PRTable .designation').each(function() {
                selectedValues1.push($(this).val());
            });

            // Append options excluding those that are already selected
            designationOptions.forEach(function(option1) {
                if (!selectedValues1.includes(option1.value.toString())) {
                    select1.append($('<option>', {
                        value: option1.value,
                        text: option1.text
                    }));
                }
            });
            return select1.prop('outerHTML');
        }

        // add update options functionality
        function updateDropdownOptions() {
            var selectedValues = [];
            $('#initialRefNoTable .screen').each(function() {
                selectedValues.push($(this).val());
            });

            $('#initialRefNoTable .screen').each(function() {
                var currentValue = $(this).val();
                $(this).empty();
                options.forEach(function(option) {
                    if (!selectedValues.includes(option.value) || option.value === currentValue) {
                        $(this).append($('<option>', {
                            value: option.value,
                            text: option.text
                        }));
                    }
                }, this);
                $(this).val(currentValue);
            });
        }

        // add update designation options functionality
        function updateDropdownDesignationOptions() {
            var selectedValues1 = [];
            $('#PRTable .designation').each(function() {
                selectedValues1.push($(this).val());
            });

            $('#PRTable .designation').each(function() {
                var currentValue1 = $(this).val();
                $(this).empty();
                designationOptions.forEach(function(option1) {
                    // Ensure comparison is consistent (both values should be strings)
                    if (!selectedValues1.includes(option1.value.toString()) || option1.value.toString() ===
                        currentValue1) {
                        $(this).append($('<option>', {
                            value: option1.value,
                            text: option1.text
                        }));
                    }
                }, this);
                $(this).val(currentValue1);
            });
        }

        // add selected option functionality
        function isOptionSelected(value) {
            var selected = false;
            $('#initialRefNoTable .screen').each(function() {
                if ($(this).val() === value) {
                    selected = true;
                    return false; // break out of each loop
                }
            });
            return selected;
        }

        // add selected designation option functionality
        function isOptionSelectedDesignation(value) {
            var selected = false;
            $('#PRTable .designation').each(function() {
                if ($(this).val() === value.toString()) {
                    selected = true;
                    return false; // break out of each loop
                }
            });
            return selected;
        }

        // Form submit event
        $('#initialFrom').on('submit', function(event) {
            var isValid = true;
            $('#initialRefNoTable .ref-no, #initialRefNoTable .running-no').each(function() {
                if ($(this).val() === '') {
                    isValid = false;
                    return false; // break out of each loop
                }
            });

            if (!isValid) {
                event.preventDefault();
                $('.tab-pane.active').find('.card-body').prepend(`
                <div class="alert border-warning alert-dismissible fade show text-warning" role="alert">
                    <b>Warning!</b> Please fill in all Ref No. and Running No. fields.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            `);
            }
        });

        function handleCategoryChange() {
            var selectedValue = $("#categorySelect").val();

            var otherDiv = $('#other_div');
            var categoryOtherInput = $('#category_other');

            if (selectedValue === "Others") {
                otherDiv.removeClass('d-none');
                categoryOtherInput.prop('disabled', false);
            } else {
                otherDiv.addClass('d-none');
                categoryOtherInput.prop('disabled', true);
            }
        };

        // PR Form submit event
        $('#PRFrom').on('submit', function(event) {
            var isValid = true;
            $('#PRTable .amount, #PRTable .designation').each(function() {
                if ($(this).val() === '') {
                    isValid = false;
                    return false; // break out of each loop
                }
            });

            if (!isValid) {
                event.preventDefault();
                $('.tab-pane.active').find('.card-body').prepend(`
                <div class="alert border-warning alert-dismissible fade show text-warning" role="alert">
                    <b>Warning!</b> Please fill in all Amount (RM) and Designation fields.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            `);
            }
        });
    </script>
@endsection
