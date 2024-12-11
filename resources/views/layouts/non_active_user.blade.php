<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 - Forbidden</title>
    <link href="{{ asset('assets/css/main.css') }}" rel="stylesheet">
</head>

<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card text-center">
                    <div class="card-header bg-warning text-white">
                        <h2>Access Denied</h2>
                    </div>
                    <div class="card-body py-5">
                        <h3 class="card-title">Inactive Account</h3>
                        <p class="card-text">You do not have access to this part of the application because your account
                            is currently inactive. Please contact support or try logging in again if you believe this is
                            an error.</p>
                        <a href="{{ route('login') }}" class="btn btn-primary">Return to Login</a>
                    </div>
                    <div class="card-footer text-muted">
                        If you need assistance, please contact our support team.
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
