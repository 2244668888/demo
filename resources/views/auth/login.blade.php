<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>ZENIG AUTO</title>

    <!-- Meta -->
    <link rel="shortcut icon" href="{{ asset('assets/images/zenig.png') }}" />

    <!-- *************
   ************ CSS Files *************
  ************* -->
    <link rel="stylesheet" href="{{ asset('assets/fonts/bootstrap/bootstrap-icons.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/main.min.css') }}" />

</head>

<body>

    <!-- Page wrapper starts -->
    <div class="page-wrapper">

        <!-- Auth container starts -->
        <div class="auth-container">

            <div class="d-flex justify-content-center">

                <!-- Form starts -->
                <form action="{{ route('login') }}" method="POST">
                    @csrf
                    <!-- Logo starts -->
                    <!-- <a href="index.html" class="auth-logo mt-5 mb-3">
                        <img src="{{ asset('assets/images/zenig1.png') }}" alt="Bootstrap Gallery" />
                    </a> -->
                    <!-- Logo ends -->

                    <!-- Authbox starts -->
                    <div class="auth-box">

                        <h4 class="mb-4">LOGIN</h4>

                        <div class="mb-3">
                            <label class="form-label" for="email">Email <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="bi bi-envelope"></i>
                                </span>
                                <input type="email" id="email" name="email" class="form-control @error('email') is-invalid @enderror"
                                    placeholder="Enter your email">
                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-2">
                            <label class="form-label" for="password">Password <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="bi bi-lock"></i>
                                </span>
                                <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror"
                                    placeholder="Enter password">
                                <button class="btn btn-outline-secondary" id="togglePassword" type="button">
                                    <i class="bi bi-eye" id="icon"></i>
                                </button>
                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="d-grid gap-2 mt-4">
                            <button type="submit" class="btn btn-primary">Login</button>
                        </div>

                    </div>
                    <!-- Authbox ends -->

                </form>
                <!-- Form ends -->

            </div>

        </div>
        <!-- Auth container ends -->

    </div>
    <!-- Page wrapper ends -->
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
    </script>
</body>

</html>
