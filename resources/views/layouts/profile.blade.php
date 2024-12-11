@extends('layouts.app')
@section('title')
    ACCOUNT SETTINGS
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
                            <li class="nav-item" role="presentation">
                                <a class="nav-link active" id="tab-oneB" data-bs-toggle="tab" href="#oneB" role="tab"
                                    aria-controls="oneB" aria-selected="true"><i class="bi bi-person me-2"></i> Profile</a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link" id="tab-oneA" data-bs-toggle="tab" href="#oneA" role="tab"
                                    aria-controls="oneA" aria-selected="true"><i class="bi bi-gear me-2"></i> Personal
                                    Details</a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link" id="tab-fourA" data-bs-toggle="tab" href="#fourA" role="tab"
                                    aria-controls="fourA" aria-selected="false" tabindex="-1"><i
                                        class="bi bi-eye-slash me-2"></i>Reset Password</a>
                            </li>
                        </ul>
                        <!-- Nav tabs end -->

                        <!-- Tab content start -->
                        <div class="tab-content">
                            <div class="tab-pane fade active show" id="oneB" role="tabpanel" aria-labelledby="tab-oneB">
                                <!-- Row starts -->
                                <div class="row gx-3">
                                    <div class="col-sm-12 col-12">
                                        <div class="card border mb-3">
                                            <div class="card-body">
                                                <!-- Profile Header starts -->
                                                <div class="row gx-3">
                                                    <div class="col-lg-4 col-sm-6 col-12">
                                                        <div class="card mb-3">
                                                            <div class="card-body text-center">
                                                                <div class="mb-3">
                                                                    <img src="{{ asset('assets/images/man.png') }}" class="img-fluid rounded-circle" alt="Profile Image" style="width: 100px; height: 100px;">
                                                                </div>
                                                                <h5 class="mb-2">{{ Auth::user()->full_name }}</h5>

                                                                @if (Auth::user()->department)
                                                                    <h6 class="text-muted mb-3 me-3">{{ Auth::user()->department->name }}</h6>
                                                                @else
                                                                    <a href="{{ route('user.edit', Auth::user()->id) }}" class="text-muted mb-3 me-3">Assign Department</a>
                                                                @endif

                                                                @if (Auth::user()->designation)
                                                                    <h6 class="text-muted mb-3">{{ Auth::user()->designation->name }}</h6>
                                                                @else
                                                                    <a href="{{ route('user.edit', Auth::user()->id) }}" class="text-muted mb-3">Assign Designation</a>
                                                                @endif

                                                                <div class="mt-2">
                                                                    @php
                                                                        $roles = json_decode(Auth::user()->role_ids);
                                                                    @endphp
                                                                    @foreach ($roles as $role)
                                                                        @php
                                                                            $role_name = Spatie\Permission\Models\Role::find($role);
                                                                        @endphp
                                                                        <span class="badge bg-danger bg-opacity-10 text-danger">{{ $role_name->name }}</span>
                                                                    @endforeach
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!-- Profile Header ends -->

                                                    <!-- User Details starts -->
                                                    <div class="col-lg-8 col-sm-6 col-12">
                                                        <div class="card mb-3">
                                                            <div class="card-body">
                                                                <div class="mb-3">
                                                                    <label for="userName" class="form-label">User Name</label>
                                                                    <div class="input-group">
                                                                        <span class="input-group-text">
                                                                            <i class="bi bi-person"></i>
                                                                        </span>
                                                                        <input type="text" class="form-control" id="userName" value="{{ Auth::user()->user_name }}" readonly>
                                                                    </div>
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label for="email" class="form-label">Email</label>
                                                                    <div class="input-group">
                                                                        <span class="input-group-text">
                                                                            <i class="bi bi-envelope"></i>
                                                                        </span>
                                                                        <input type="email" class="form-control" id="email" value="{{ Auth::user()->email }}" readonly>
                                                                    </div>
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label for="phone" class="form-label">Phone No</label>
                                                                    <div class="input-group">
                                                                        <span class="input-group-text">
                                                                            <i class="bi bi-phone"></i>
                                                                        </span>
                                                                        <input type="text" class="form-control" id="phone" value="{{ Auth::user()->phone }}" readonly>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!-- User Details ends -->
                                                </div>
                                                <!-- Row ends -->
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Row ends -->
                            </div>

                            <div class="tab-pane fade" id="oneA" role="tabpanel" aria-labelledby="tab-oneA">
                                <form action="{{ route('profile.update') }}" method="POST">
                                    @csrf
                                    <!-- Row starts -->
                                    <div class="row gx-3">
                                        <div class="col-sm-12 col-12">
                                            <div class="card border mb-3">
                                                <div class="card-body">
                                                    <!-- Row starts -->
                                                    <div class="row gx-3">
                                                        <div class="col-sm-3 col-12">
                                                            <!-- Form field start -->
                                                            <div class="mb-3">
                                                                <label for="fullName" class="form-label">Full Name</label>
                                                                <div class="input-group">
                                                                    <span class="input-group-text">
                                                                        <i class="bi bi-p-circle"></i>
                                                                    </span>
                                                                    <input type="text" class="form-control"
                                                                        id="fullName" value="{{ Auth::user()->full_name }}"
                                                                        name="full_name" placeholder="Full name">
                                                                </div>
                                                            </div>
                                                            <!-- Form field end -->
                                                        </div>
                                                        <div class="col-sm-3 col-12">
                                                            <!-- Form field start -->
                                                            <div class="mb-3">
                                                                <label for="userName" class="form-label">User Name</label>
                                                                <div class="input-group">
                                                                    <span class="input-group-text">
                                                                        <i class="bi bi-person"></i>
                                                                    </span>
                                                                    <input type="text" class="form-control"
                                                                        id="userName"
                                                                        value="{{ Auth::user()->user_name }}"
                                                                        name="user_name" placeholder="User name">
                                                                </div>
                                                            </div>
                                                            <!-- Form field end -->
                                                        </div>
                                                        <div class="col-sm-3 col-12">
                                                            <!-- Form field start -->
                                                            <div class="mb-3">
                                                                <label for="yourEmail" class="form-label">Email</label>
                                                                <div class="input-group">
                                                                    <span class="input-group-text">
                                                                        <i class="bi bi-envelope"></i>
                                                                    </span>
                                                                    <input type="email" class="form-control"
                                                                        id="yourEmail" placeholder="Email ID"
                                                                        value="{{ Auth::user()->email }}" name="email"
                                                                        value="info@email.com">
                                                                </div>
                                                            </div>
                                                            <!-- Form field end -->
                                                        </div>
                                                        <div class="col-sm-3 col-12">
                                                            <!-- Form field start -->
                                                            <div class="mb-3">
                                                                <label for="contactNumber" class="form-label">Phone
                                                                    No</label>
                                                                <div class="input-group">
                                                                    <span class="input-group-text">
                                                                        <i class="bi bi-phone"></i>
                                                                    </span>
                                                                    <input type="text" class="form-control"
                                                                        id="contactNumber"
                                                                        value="{{ Auth::user()->phone }}" name="phone"
                                                                        placeholder="Phone">
                                                                </div>
                                                            </div>
                                                            <!-- Form field end -->
                                                        </div>
                                                    </div>
                                                    <!-- Row ends -->
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Row ends -->
                                    <div class="row gx-3">
                                        <!-- Buttons start -->
                                        <div class="d-flex gap-2 justify-content-end">
                                            <button type="button" class="btn btn-outline-secondary">
                                                Cancel
                                            </button>
                                            <button type="submit" class="btn btn-primary">
                                                Update
                                            </button>
                                        </div>
                                        <!-- Buttons end -->
                                    </div>
                                </form>
                            </div>
                            <div class="tab-pane fade" id="fourA" role="tabpanel" aria-labelledby="tab-fourA">
                                <form action="{{ route('profile.password.update') }}" method="POST">
                                    @csrf
                                    <!-- Row starts -->
                                    <div class="row align-items-end">
                                        <div class="col-xl-4 col-sm-6 col-12">
                                            <div class="p-3">
                                                <img src="{{ asset('assets/images/login.svg') }}" alt="Reset Password"
                                                    class="img-fluid">
                                            </div>
                                        </div>
                                        <div class="col-sm-4 col-12">
                                            <div class="card border mb-3">
                                                <div class="card-body">
                                                    <div class="mb-3">
                                                        <label class="form-label" for="currentPwd">Current password <span
                                                                class="text-danger">*</span></label>
                                                        <div class="input-group">
                                                            <input type="password" id="currentPwd"
                                                                placeholder="Enter Current password"
                                                                name="current_password" class="form-control">
                                                            <button class="btn btn-outline-secondary" id="togglePassword"
                                                                type="button">
                                                                <i class="bi bi-eye" id="icon"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label" for="newPwd">New password <span
                                                                class="text-danger">*</span></label>
                                                        <div class="input-group">
                                                            <input type="password" id="newPwd" name="new_password"
                                                                class="form-control"
                                                                placeholder="Your password must be 8 characters long.">
                                                            <button class="btn btn-outline-secondary" id="togglePassword1"
                                                                type="button">
                                                                <i class="bi bi-eye" id="icon1"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label" for="confNewPwd">Confirm new password
                                                            <span class="text-danger">*</span></label>
                                                        <div class="input-group">
                                                            <input type="password" id="confNewPwd"
                                                                name="confirm_password" placeholder="Confirm new password"
                                                                class="form-control">
                                                            <button class="btn btn-outline-secondary" id="togglePassword2"
                                                                type="button">
                                                                <i class="bi bi-eye" id="icon2"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Row ends -->
                                    <div class="row gx-3">
                                        <!-- Buttons start -->
                                        <div class="d-flex gap-2 justify-content-end">
                                            <button type="button" class="btn btn-outline-secondary">
                                                Cancel
                                            </button>
                                            <button type="submit" class="btn btn-primary">
                                                Update
                                            </button>
                                        </div>
                                        <!-- Buttons end -->
                                    </div>
                                </form>
                            </div>
                        </div>
                        <!-- Tab content end -->
                    </div>
                    <!-- Custom tabs end -->
                </div>
            </div>
        </div>
    </div>
    <script>
        // Toggle password visibility
        const togglePassword = document.querySelector('#togglePassword');
        const icon = document.querySelector('#icon');
        const password = document.querySelector('#currentPwd');
        togglePassword.addEventListener('click', () => {
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);
            icon.classList.toggle('bi-eye-slash');
        });

        const togglePassword1 = document.querySelector('#togglePassword1');
        const icon1 = document.querySelector('#icon1');
        const password1 = document.querySelector('#newPwd');
        togglePassword1.addEventListener('click', () => {
            const type = password1.getAttribute('type') === 'password' ? 'text' : 'password';
            password1.setAttribute('type', type);
            icon1.classList.toggle('bi-eye-slash');
        });

        const togglePassword2 = document.querySelector('#togglePassword2');
        const icon2 = document.querySelector('#icon2');
        const password2 = document.querySelector('#confNewPwd');
        togglePassword2.addEventListener('click', () => {
            const type = password2.getAttribute('type') === 'password' ? 'text' : 'password';
            password2.setAttribute('type', type);
            icon2.classList.toggle('bi-eye-slash');
        });
    </script>
@endsection
