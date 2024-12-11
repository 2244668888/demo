@extends('layouts.app')
@section('title')
    STAFF REGISTRATION EDIT
@endsection
@section('button')
    <a type="button" class="btn btn-info" href="{{ route('user.index') }}">
        <i class="bi bi-arrow-left"></i> Back
    </a>
@endsection
@section('content')
<style>
    .tooltip-arrow{
        display: none !important;
        width: 0px !important;
        height: 0px !important;
    }
    .tooltip-inner{
        display: none !important;
        width: 0px !important;
        height: 0px !important;
    }
</style>
    <div class="card">
        <form method="post" action="{{ route('user.update', $user->id) }}" enctype="multipart/form-data">
            @csrf
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label class="form-label" for="phone">Code</label>
                            <input type="text" class="form-control" id="code" name="code"
                                value="{{ $user->code }}" placeholder="Enter code">
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label class="form-label" for="full_name">Full Name</label>
                            <input type="text" class="form-control" id="full_name" name="full_name"
                                value="{{ $user->full_name }}" placeholder="Enter Full name">
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label class="form-label" for="user_name">User Name</label>
                            <input type="text" class="form-control" id="user_name" name="user_name"
                                value="{{ $user->user_name }}" placeholder="Enter User name">
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label class="form-label" for="email">Email</label>
                            <input type="email" class="form-control" id="email" name="email"
                                value="{{ $user->email }}" placeholder="Enter Email">
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label class="form-label" for="password">Password</label>
                            <div class="input-group">
                                <input type="password" name="password" id="password" class="form-control"
                                    placeholder="Enter password">
                                <button class="btn btn-outline-secondary" id="togglePassword" type="button">
                                    <i class="bi bi-eye" id="icon"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label class="form-label" for="department">Department</label>
                            <select class="form-select" id="department" name="department">
                                <option value="" selected disabled>Select Department</option>
                                @foreach ($departments as $department)
                                    <option value="{{ $department->id }}" @selected($user->department_id == $department->id)>
                                        {{ $department->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label class="form-label" for="designation">Designation</label>
                            <select class="form-select" id="designation" name="designation">
                                <option value="" selected disabled>Select Designation</option>
                                @foreach ($designations as $designation)
                                    <option value="{{ $designation->id }}" @selected($user->designation_id == $designation->id)>
                                        {{ $designation->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            @php
                                $item = json_decode($user->role_ids);
                            @endphp
                            <label class="form-label" for="role">Role</label>
                            <select class="form-select" id="role" name="role[]" multiple>
                                @foreach ($roles as $role)
                                    <option value="{{ $role->id }}"
                                        @if ($item) {{ in_array($role->id, $item) ? 'selected' : '' }} @endif>
                                        {{ $role->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3 mt-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="{{ $user->is_active }}"
                                    id="is_active" name="is_active"
                                    @if ($user->is_active == "yes") checked
                                  @endif>
                                <label class="form-check-label" for="is_active"
                                   >Is Active</label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mt-2">
                    <div class="custom-tabs-container">
                        <ul class="nav nav-tabs" id="customTab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <a class="nav-link active" id="personal" data-bs-toggle="tab" href="#perosnal-tab"
                                    role="tab" aria-controls="perosnal-tab" aria-selected="true">Personal</a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link" id="family" data-bs-toggle="tab" href="#family-tab"
                                    role="tab" aria-controls="family-tab" aria-selected="false"
                                    tabindex="-1">Family</a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link" id="bank" data-bs-toggle="tab" href="#bank-tab" role="tab"
                                    aria-controls="bank-tab" aria-selected="false" tabindex="-1">Bank Detail</a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link" id="more" data-bs-toggle="tab" href="#more-tab" role="tab"
                                    aria-controls="more-tab" aria-selected="false" tabindex="-1">More</a>
                            </li>
                        </ul>
                        <div class="tab-content" id="customTabContent">
                            <div class="tab-pane fade active show" id="perosnal-tab" role="tabpanel"
                                aria-labelledby="personal">
                                <div class="row">
                                    <div class="col-lg-3 col-sm-4 col-12">
                                        <div class="mb-3">
                                            <label class="form-label" for="role1">Gender</label>
                                            <select class="form-select" id="role1" name="gender">
                                                <option value="male"
                                                    @if ($user_personal->gender ?? '' == 'male') selected @else "" @endif>Male</option>
                                                <option value="female" value="male"
                                                    @if ($user_personal->gender ?? '' == 'female') selected @else "" @endif>Female
                                                </option>
                                            </select>

                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-sm-4 col-12">
                                        <div class="mb-3">
                                            <label class="form-label" for="role2">Marital Status</label>
                                            <select class="form-select" id="role2" name="marital_status">
                                                <option value="married"
                                                    @if ($user_personal->marital_status  ?? '' == 'married') selected @else "" @endif>Married
                                                </option>
                                                <option value="unmarried" value="male"
                                                    @if ($user_personal->marital_status ?? '' == 'unmarried') selected @else "" @endif>Single
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-sm-4 col-12">
                                        <div class="mb-3">
                                            <label class="form-label" for="role">D.O.B</label>
                                            <input type="date" placeholder="" name="dob" id="dob"
                                                value="{{ $user_personal->dob ?? '' }}" class="form-control form-control-sm">
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-sm-4 col-12">
                                        <div class="mb-3">
                                            <label class="form-label" for="role">Age</label>
                                            <input type="text" placeholder="Y/M"  id="age"
                                                value="{{ $user_personal->age ?? '' }}" class="form-control form-control-sm"
                                                disabled>
                                                <input type="hidden"  name="age" id="age_input"
                                                value="{{ $user_personal->age ?? '' }}" class="form-control form-control-sm"
                                                >
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-sm-4 col-12">
                                        <div class="mb-3">
                                            <label class="form-label" for="role">Address</label>
                                            <textarea class="form-control" id="address" placeholder="" name="address" rows="2">{{ $user_personal->address ?? '' }}</textarea>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-sm-4 col-12">
                                        <div class="mb-3">
                                            <label class="form-label" for="phone">Phone</label>
                                            <input type="number" class="form-control" id="personal_phone"
                                                name="personal_phone" value="{{ $user_personal->personal_phone ?? '' }}"
                                                placeholder="Enter Phone">
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-sm-4 col-12">
                                        <div class="mb-3">
                                            <label class="form-label" for="phone">Mobile</label>
                                            <input type="number" class="form-control" id="personal_mobile"
                                                name="personal_mobile" value="{{ $user_personal->personal_mobile ?? '' }}"
                                                placeholder="Enter Mobile">
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-sm-4 col-12">
                                        <div class="mb-3">
                                            <label class="form-label" for="phone">NRIC</label>
                                            <input type="text" class="form-control" id="nric" name="nric"
                                                value="{{ $user_personal->nric ?? '' }}" placeholder="Enter NRIC">
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-sm-4 col-12">
                                        <div class="mb-3">
                                            <label class="form-label" for="phone">Passport</label>
                                            <input type="text" class="form-control" id="passport" name="passport"
                                                value="{{ $user_personal->passport ?? '' }}" placeholder="Enter Passport">
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-sm-4 col-12">
                                        <div class="mb-3">
                                            <label class="form-label" for="phone">Expiry Date</label>
                                            <input type="date" class="form-control" id="passport_expiry_date"
                                                name="passport_expiry_date"
                                                value="{{ $user_personal->passport_expiry_date ?? '' }}"
                                                placeholder="Enter Expiry Date">
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-sm-4 col-12">
                                        <div class="mb-3">
                                            <label class="form-label" for="phone">Immigration No</label>
                                            <input type="text" class="form-control" id="immigration_no"
                                                name="immigration_no" value="{{ $user_personal->immigration_no ?? '' }}"
                                                placeholder="Enter Immigration No">
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-sm-4 col-12">
                                        <div class="mb-3">
                                            <label class="form-label" for="phone">Expiry Date</label>
                                            <input type="date" class="form-control" id="immigration_no_expiry_date"
                                                name="immigration_no_expiry_date"
                                                value="{{ $user_personal->immigration_no_expiry_date ?? '' }}"
                                                placeholder="Enter Expiry Date">
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-sm-4 col-12">
                                        <div class="mb-3">
                                            <label class="form-label" for="phone">Permit No</label>
                                            <input type="text" class="form-control" id="permit_no" name="permit_no"
                                                value="{{ $user_personal->permit_no ?? '' }}" placeholder="Enter Permit No">
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-sm-4 col-12">
                                        <div class="mb-3">
                                            <label class="form-label" for="phone">Expiry Date</label>
                                            <input type="date" class="form-control" id="permit_no_expiry_date"
                                                name="permit_no_expiry_date"
                                                value="{{ $user_personal->permit_no_expiry_date ?? '' }}"
                                                placeholder="Enter Expiry Date">
                                        </div>
                                    </div>

                                    <div class="col-lg-3 col-sm-4 col-12">
                                        <div class="mb-3">
                                            <label class="form-label" for="phone">Nationality</label>
                                            <input type="text" class="form-control" id="nationality"
                                                name="nationality" value="{{ $user_personal->nationality ?? ''  }}"
                                                placeholder="Enter Nationality">
                                        </div>
                                    </div>

                                    <div class="col-lg-3 col-sm-4 col-12">
                                        <div class="mb-3">
                                            <label class="form-label" for="phone">EPF No</label>
                                            <input type="text" class="form-control" id="epf_no"
                                                name="epf_no" value="{{ $user_personal->epf_no  ?? ''}}"
                                                placeholder="Enter EPF No">
                                        </div>
                                    </div>



                                    <div class="col-lg-3 col-sm-4 col-12">
                                        <div class="mb-3">
                                            <label class="form-label" for="dob">Sosco No</label>

                                                <input type="text" name="sosco_no" id="sosco_no"
                                                value="{{ $user_personal->sosco_no  ?? ''}}" class="form-control">

                                        </div>
                                    </div>

                                    <div class="col-lg-3 col-sm-4 col-12">
                                        <div class="mb-3">
                                            <label class="form-label" for="phone">Tax Identification No(TIN)</label>
                                            <input type="text" class="form-control" id="tin"
                                                name="tin" value="{{ $user_personal->tin ?? '' }}"
                                                placeholder="Enter Tax Identification No(TIN)">
                                        </div>
                                    </div>

                                    <div class="col-lg-3 col-sm-4 col-12">
                                        <div class="mb-3">
                                            <label class="form-label" for="phone">Base Salary (RM)</label>
                                            <input type="number" class="form-control" id="base_salary"
                                                name="base_salary" value="{{ $user_personal->base_salary ?? 0 }}"
                                                placeholder="Enter Base Salary (RM)">
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <div class="tab-pane fade" id="family-tab" role="tabpanel" aria-labelledby="family">
                                <h4>Spouse :</h4>
                                <div class="row">
                                    <div class="col-lg-3 col-sm-4 col-12">
                                        <div class="mb-3">
                                            <label class="form-label" for="role1">Spouse Name</label>
                                            <input type="text" placeholder="" name="spouse_name" id="spouse_name"
                                                value="{{ $user_family->spouse_name ?? '' }}"
                                                class="form-control form-control-sm">
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-3 col-sm-4 col-12">
                                        <div class="mb-3">
                                            <label class="form-label" for="role">D.O.B</label>
                                            <input type="date" placeholder="" name="family_dob" id="family_dob"
                                                value="{{ $user_family->family_dob ?? '' }}"
                                                class="form-control form-control-sm">
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-sm-4 col-12">
                                        <div class="mb-3">
                                            <label class="form-label" for="role">Age</label>
                                            <input type="text" placeholder="Y/M" id="family_age"
                                                class="form-control form-control-sm"
                                                value="{{ $user_family->family_age ?? '' }}" disabled>
                                                <input type="hidden" placeholder="Y/M" id="family_age_name_input"
                                                class="form-control form-control-sm" name="family_age"
                                                value="{{ $user_family->family_age ?? '' }}" >

                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-sm-4 col-12">
                                        <div class="mb-3">
                                            <label class="form-label" for="role">Address</label>
                                            <textarea class="form-control" id="family_address" placeholder="" name="family_address" rows="2">{{ $user_family->family_address ?? '' }}</textarea>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-sm-4 col-12">
                                        <div class="mb-3">
                                            <label class="form-label" for="phone">Phone</label>
                                            <input type="number" class="form-control" id="family_phone"
                                                name="family_phone" value="{{ $user_family->family_phone ?? '' }}"
                                                placeholder="Enter Phone">
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-sm-4 col-12">
                                        <div class="mb-3">
                                            <label class="form-label" for="phone">Mobile</label>
                                            <input type="number" class="form-control" id="family_mobile"
                                                name="family_mobile" value="{{ $user_family->family_mobile ?? '' }}"
                                                placeholder="Enter Mobile">
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-sm-4 col-12">
                                        <div class="mb-3">
                                            <label class="form-label" for="phone">NRIC</label>
                                            <input type="text" class="form-control" id="family_nric" name="family_nric"
                                                value="{{ $user_family->family_nric ?? '' }}" placeholder="Enter NRIC">
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-sm-4 col-12">
                                        <div class="mb-3">
                                            <label class="form-label" for="phone">Passport</label>
                                            <input type="text" class="form-control" id="family_passport"
                                                name="family_passport" value="{{ $user_family->family_passport ?? '' }}"
                                                placeholder="Enter Passport">
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-sm-4 col-12">
                                        <div class="mb-3">
                                            <label class="form-label" for="phone">Expiry Date</label>
                                            <input type="date" class="form-control" id="family_passport_expiry_date"
                                                name="family_passport_expiry_date"
                                                value="{{ $user_family->family_passport_expiry_date ?? ''  }}"
                                                placeholder="Enter Expiry Date">
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-sm-4 col-12">
                                        <div class="mb-3">
                                            <label class="form-label" for="phone">Immigration No</label>
                                            <input type="text" class="form-control" id="family_immigration_no"
                                                name="family_immigration_no"
                                                value="{{ $user_family->family_immigration_no ?? ''  }}"
                                                placeholder="Enter Immigration No">
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-sm-4 col-12">
                                        <div class="mb-3">
                                            <label class="form-label" for="phone">Expiry Date</label>
                                            <input type="date" class="form-control"
                                                id="family_immigration_no_expiry_date"
                                                name="family_immigration_no_expiry_date"
                                                value="{{ $user_family->family_immigration_no_expiry_date  ?? '' }}"
                                                placeholder="Enter Expiry Date">
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-sm-4 col-12">
                                        <div class="mb-3">
                                            <label class="form-label" for="phone">Permit No</label>
                                            <input type="text" class="form-control" id="family_permit_no"
                                                name="family_permit_no" value="{{ $user_family->family_permit_no ?? '' }}"
                                                placeholder="Enter Permit No">
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-sm-4 col-12">
                                        <div class="mb-3">
                                            <label class="form-label" for="phone">Expiry Date</label>
                                            <input type="date" class="form-control" id="family_permit_no_expiry_date"
                                                name="family_permit_no_expiry_date"
                                                value="{{ $user_family->family_permit_no_expiry_date ?? '' }}"
                                                placeholder="Enter Expiry Date">
                                        </div>
                                    </div>
                                </div>

                                <h4>Child :</h4>
                                <div class="row">
                                    <div class="col-lg-3 col-sm-4 col-12">
                                        <div class="mb-3">
                                            <label class="form-label" for="role1">Children no</label>
                                            <input type="number" placeholder="" name="children_no" id="children_no"
                                                value="{{ $user_family->children_no ?? '' }}"
                                                class="form-control form-control-sm">
                                        </div>
                                    </div>

                                    <div class="d-flex gap-2 justify-content-end">
                                        <button type="button" class="btn btn-primary" id="addRow"><i
                                                class="fs-5 bi bi-plus" style="width: 10px"></i>Add</button>
                                    </div>
                                    <div class="table-responsive">
                                        <table class="table table-bordered m-0 w-100" id="family_Table">
                                            <thead>
                                                <tr>
                                                    <th>Sr No</th>
                                                    <th>Name</th>
                                                    <th>D.O.B</th>
                                                    <th>Age</th>
                                                    <th>Birth Certificate No</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @if(isset($user_family_child))
                                                @foreach ($user_family_child as $row)
                                                    <tr>
                                                        <td>
                                                            {{ $loop->iteration }}
                                                        </td>
                                                {{-- @php
                                                    dd($row);
                                                @endphp --}}
                                                        <td>
                                                            <input type="text" class="form-control"
                                                                value="{{ $row->name }}"
                                                                name="family[{{ $loop->iteration }}][name]" >
                                                                <input type="hidden" value="{{ $row->family_id }}" name="family_id">
                                                        </td>
                                                        <td>
                                                            <input type="date" class="form-control"
                                                                value="{{ $row->dob }}"
                                                                name="family[{{ $loop->iteration }}][dob]">
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control"
                                                                value="{{ $row->age }}"
                                                                name="family[{{ $loop->iteration }}][age]" readonly>
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control"
                                                                value="{{ $row->birth_certificate_no }}"
                                                                name="family[{{ $loop->iteration }}][birth_certificate_no]" readonly>
                                                        </td>
                                                        <td>
                                                            <button type="button"
                                                                class="btn btn-sm btn-danger remove-row"><i
                                                                    class="bi bi-trash"></i></button>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                                @endif
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="col-lg-3 col-sm-4 col-12">
                                        <label class="form-label" for="attachment">Attachment </label>
                                        <div class="input-group mb-3">
                                            <a target="_blank" href="{{ asset('/family-attachments/') }}/{{ $user_family->attachment }}" class="btn btn-outline-secondary" type="button" id="inputGroupFileAddon03">
                                                <i class="bi bi-file-text"></i>
                                            </a>
                                            <input type="file" class="form-control" id="attachment" aria-describedby="attachment" aria-label="attachment" name="attachment">
                                        </div>
                                    </div>
                                </div>

                            </div>

                            <div class="tab-pane fade" id="bank-tab" role="tabpanel" aria-labelledby="bank">
                                <div class="row mt-3">
                                    <div class="col-lg-3 col-sm-4 col-12">
                                        <div class="mb-3">
                                            <label class="form-label" for="phone">Bank</label>
                                            <input type="text" class="form-control" id="bank"
                                                name="bank" value="{{ $user_bank->bank ?? '' }}"
                                                placeholder="Enter Bank">
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-sm-4 col-12">
                                        <div class="mb-3">
                                            <label class="form-label" for="phone">Account no</label>
                                            <input type="number" class="form-control" id="account_no"
                                                name="account_no"
                                                value="{{ $user_bank->account_no ?? 0 }}"
                                                placeholder="Enter Account no">
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-sm-4 col-12">
                                        <div class="mb-3">
                                            <label class="form-label" for="phone">Account Type</label> <br>
                                            <select name="account_type" class="form-select" id="account_type">
                                                <option value="-1" selected disabled>Select a Account Type</option>
                                                <option value="Saving" @selected($user_bank->account_type ?? '' == "Saving")>Saving</option>
                                                <option value="Current" @selected($user_bank->account_type ?? '' == "Current")>Current</option>
                                                <option value="Fixed" @selected($user_bank->account_type ?? '' == "Fixed")>Fixed</option>
                                                <option value="Other" @selected($user_bank->account_type ?? '' == "Other")>Other</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-sm-4 col-12">
                                        <div class="mb-3">
                                            <label class="form-label" for="phone">Branch</label>
                                            <input type="text" class="form-control" id="branch"
                                                name="branch"
                                                value="{{ $user_bank->branch ?? '' }}"
                                                placeholder="Enter Branch">
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-lg-3 col-sm-4 col-12">
                                        <div class="mb-3">
                                            <label class="form-label" for="phone">Account Status</label> <br>
                                            <select name="account_status" class="form-select" id="account_status">
                                                <option value="-1" selected disabled>Select a Account Status</option>
                                                <option value="Active" @selected($user_bank->account_status ?? '' == "Active")>Active</option>
                                                <option value="In-Active" @selected($user_bank->account_status ?? '' == "In-Active")>In-Active</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                            </div>


                            <div class="tab-pane fade" id="more-tab" role="tabpanel" aria-labelledby="more">
                                <h4 >Emergency Contact</h4>
                                <div class="row mt-3">
                                    <div class="col-lg-3 col-sm-4 col-12">
                                        <div class="mb-3">
                                            <label class="form-label" for="phone">Name</label>
                                            <input type="text" class="form-control" id="emergency_contact_name"
                                                name="emergency_contact_name" value="{{ $user_more->emergency_contact_name ?? '' }}"
                                                placeholder="Enter Name">
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-sm-4 col-12">
                                        <div class="mb-3">
                                            <label class="form-label" for="phone">Relationship</label>
                                            <input type="text" class="form-control" id="emergency_contact_relationship"
                                                name="emergency_contact_relationship"
                                                value="{{ $user_more->emergency_contact_relationship ?? '' }}"
                                                placeholder="Enter Relationship">
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-sm-4 col-12">
                                        <div class="mb-3">
                                            <label class="form-label" for="phone">Address </label>
                                            <textarea name="emergency_contact_address" class="form-control" id="" cols="30" rows="1">{{ $user_more->emergency_contact_address ?? '' }}</textarea>

                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-sm-4 col-12">
                                        <div class="mb-3">
                                            <label class="form-label" for="phone">Phone no</label>
                                            <input type="text" class="form-control" id="emergency_contact_phone_no"
                                                name="emergency_contact_phone_no"
                                                value="{{ $user_more->emergency_contact_phone_no ?? 0 }}"
                                                placeholder="Enter Phone no">
                                        </div>
                                    </div>
                                </div>
                                <legend class="fs-5"><h4>Leave Detail</h4></legend>
                                <div class="row">
                                    <div class="col-lg-3 col-sm-4 col-12">
                                        <div class="mb-3">
                                            <label class="form-label" for="phone">Annual Leave </label>
                                            <input type="text" class="form-control" id="annual_leave"
                                                name="annual_leave" value="{{ $user_more->annual_leave ?? '' }}"
                                                placeholder="Enter Annual Leave">
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-sm-4 col-12">
                                        <div class="mb-3">
                                            <label class="form-label" for="phone">Balance (day)</label>
                                            <input type="text" class="form-control" id="annual_leave_balance_day"
                                                name="annual_leave_balance_day"
                                                value="{{ $user_more->annual_leave_balance_day ?? '' }}"
                                                placeholder="Enter Balance">
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-sm-4 col-12">
                                        <div class="mb-3">
                                            <label class="form-label" for="phone">Carried Leave </label>
                                            <input type="text" class="form-control" id="carried_leave"
                                                name="carried_leave" value="{{ $user_more->carried_leave ?? '' }}"
                                                placeholder="Enter Carried Leave">
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-sm-4 col-12">
                                        <div class="mb-3">
                                            <label class="form-label" for="phone">Balance (day)</label>
                                            <input type="text" class="form-control" id="carried_leave_balance_day"
                                                name="carried_leave_balance_day"
                                                value="{{ $user_more->carried_leave_balance_day ?? '' }}"
                                                placeholder="Enter Balance">
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-sm-4 col-12">
                                        <div class="mb-3">
                                            <label class="form-label" for="phone">Medical</label>
                                            <input type="text" class="form-control" id="medical_leave"
                                                name="medical_leave" value="{{ $user_more->medical_leave ?? '' }}"
                                                placeholder="Enter Medical">
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-sm-4 col-12">
                                        <div class="mb-3">
                                            <label class="form-label" for="phone">Balance (day)</label>
                                            <input type="text" class="form-control" id="medical_leave_balance_day"
                                                name="medical_leave_balance_day"
                                                value="{{ $user_more->medical_leave_balance_day ?? '' }}"
                                                placeholder="Enter Balance">
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-sm-4 col-12">
                                        <div class="mb-3">
                                            <label class="form-label" for="phone">Unpaid</label>
                                            <input type="text" class="form-control" id="unpaid_leave"
                                                name="unpaid_leave" value="{{ $user_more->unpaid_leave ?? '' }}"
                                                placeholder="Enter unpaid">
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-sm-4 col-12">
                                        <div class="mb-3">
                                            <label class="form-label" for="phone">Balance (day)</label>
                                            <input type="text" class="form-control" id="unpaid_leave_balance_day"
                                                name="unpaid_leave_balance_day"
                                                value="{{ $user_more->unpaid_leave_balance_day ?? '' }}"
                                                placeholder="Enter Balance">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


            </div>
            <div class="card-footer">
                <div class="d-flex gap-2 justify-content-end">
                    <button type="submit" class="btn btn-primary" onclick="saveActiveTab()">Update</button>
                </div>
            </div>
        </form>
    </div>


       <script>
        document.addEventListener("DOMContentLoaded", function() {
            if (!$('.alert').hasClass('show')) {
                sessionStorage.removeItem('activeTab');
            }
            const activeTab = sessionStorage.getItem('activeTab');
            if (activeTab) {
                const tab = document.querySelector(`#customTab a[href="${activeTab}"]`);
                const tabContent = document.querySelector(activeTab);
                if (tab && tabContent) {
                    const tabInstance = new bootstrap.Tab(tab);
                    tabInstance.show();
                }
            }

            document.querySelectorAll('#customTab a').forEach(tab => {
                tab.addEventListener('click', function(event) {
                    sessionStorage.setItem('activeTab', event.target.getAttribute('href'));
                });
            });
        });

        function saveActiveTab() {
            const activeTab = document.querySelector('#customTab .nav-link.active');
            if (activeTab && $('.alert .border-danger')) {
                sessionStorage.setItem('activeTab', activeTab.getAttribute('href'));
            }
        }
    </script>


    <script>


        const togglePassword = document
            .querySelector('#togglePassword');
        const icon = document
            .querySelector('#icon');
        const password = document.querySelector('#password');
        togglePassword.addEventListener('click', () => {
            // Toggle the type attribute using
            const type = password
                .getAttribute('type') === 'password' ?
                'text' : 'password';
            password.setAttribute('type', type);
            if (password.getAttribute('type') === 'password') {
                // Toggle the eye and bi-eye icon
                icon.setAttribute('class', 'bi-eye');
            } else if (password.getAttribute('type') === 'text') {
                // Toggle the eye and bi-eye icon
                icon.setAttribute('class', 'bi-eye-slash');
            }
        });


        $(document).on('change', '#is_active', function() {
            if ($(this).is(':checked')) {
                $(this).val("yes");
            } else {
                $(this).val("no");
            }
        });

        $(document).ready(function() {

            if ($("#is_active").is(':checked')) {
                $(this).val("yes");
            } else {
                $(this).val("no");
            }

            $('#dob').on('change', function() {
                var dob = new Date($(this).val());
                var today = new Date();
                var age = today.getFullYear() - dob.getFullYear();
                var monthDiff = today.getMonth() - dob.getMonth();
                var month;
                if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < dob.getDate())) {
                    age--;
                    monthDiff += 12
                }
                $('#age').val(`${age} year(s) - ${monthDiff} month(s)`);
                $('#age_input').val(`${age} year(s) - ${monthDiff} month(s)`);
            });

            $('#family_dob').on('change', function() {
                var dob = new Date($(this).val());
                var today = new Date();
                var age = today.getFullYear() - dob.getFullYear();
                var monthDiff = today.getMonth() - dob.getMonth();
                var month;
                if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < dob.getDate())) {
                    age--;
                    monthDiff += 12
                }
                $('#family_age').val(`${age} year(s) - ${monthDiff} month(s)`);
                $('#family_age_name_input').val(`${age} year(s) - ${monthDiff} month(s)`);
            });
        });

        $(document).on('change', '.dob_clone', function() {
                var age_clone = $(this).closest('tr').find('.age_clone');
                // console.log("age_clone found:", age_clone.length); // Check if the input is found
                var dob = new Date($(this).val());
                var today = new Date();
                var age = today.getFullYear() - dob.getFullYear();
                var monthDiff = today.getMonth() - dob.getMonth();
                if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < dob.getDate())) {
                    age--;
                    monthDiff += 12;
                }
                var ageString = `${age} year(s) - ${monthDiff} month(s)`;
                // console.log("ageString:", ageString); // Check the calculated age string
                age_clone.val(ageString); // Ensure the correct method to set the value is used
                });
    </script>
    {{-- Add_row_script --}}
    <script>
        $(document).ready(function() {
            var table = $('#family_Table').DataTable();
            $('#addRow').on('click', function() {
                var newRow = table.row.add([
                    table.rows().count() + 1,
                    `<input type="text" class="form-control" name="family[${table.rows().count() + 1}][name]">`,
                    `<input type="date" class="form-control dob_clone" name="family[${table.rows().count() + 1}][dob]">`,
                    `<input type="text" class="form-control age_clone" name="family[${table.rows().count() + 1}][age]" readonly>`,
                    `<input type="text" class="form-control birth_certificate_no" name="family[${table.rows().count() + 1}][birth_certificate_no]">`,
                    '<button type="button" class="btn btn-sm btn-danger remove-row"><i class="bi bi-trash"></i></button>'
                ]).draw(false).node();
            });

            $('#family_Table tbody').on('click', '.remove-row', function() {
                table.row($(this).parents('tr')).remove().draw();
                var length = $('#family_Table tbody tr').length;
                if (length > 1) {
                    resetSerialNumbersP();
                }
            });

            function resetSerialNumbersP() {
                $('#family_Table tbody tr').each(function(index) {

                    $(this).find('td:first').text(index + 1);
                });
            }
        });
    </script>
@endsection
