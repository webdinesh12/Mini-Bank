@extends('auth.layout.app')

@section('content')
    <div class="text-center mt-4">
        <h1 class="h2">Welcome back!</h1>
        <p class="lead">
            Sign in to your account to continue
        </p>
    </div>
    <div class="card">
        <div class="card-body">
            <div class="m-sm-3">
                <form id="loginForm" action="{{ route('auth.login') }}" method="POST" autocomplete="off">
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input class="form-control form-control-lg" type="text" name="email"
                            placeholder="Enter your email" />
                        <span class="error email_error"></span>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input class="form-control form-control-lg" type="password" name="password"
                            placeholder="Enter your password" />
                        <span class="error password_error"></span>
                    </div>
                    <div class="d-grid gap-2 mt-3">
                        <button type="submit" class="btn btn-lg btn-primary">Login</button>
                        <div class="text-center my-2 fw-bold">OR</div>
                        <a href="{{ route('auth.register') }}" class="btn btn-lg btn-primary">Register</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            $('#loginForm').on('submit', function(e) {
                e.preventDefault();
                const form = $(this);
                let formData = new FormData(e.target);
                $.ajax({
                    url: e.target.action,
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(res) {
                        if (res.success) {
                            window.location.href = res.redirect;
                        } else {
                            if (res.errors) {
                                showValidationErrors(res.errors);
                            } else {
                                if (res.reload) {
                                    window.location.reload();
                                } else {
                                    showAlert('', res.msg || 'Something went wrong!', 'error');
                                }
                            }
                        }
                    },
                    beforeSend: function() {
                        $('.error').empty();
                        form.find('button[type="submit"]').addClass('disabled')
                        form.find('button[type="submit"]').html('Please wait...');
                    },
                    complete: function() {
                        form.find('button[type="submit"]').removeClass('disabled');
                        form.find('button[type="submit"]').html('Sign In');
                    },
                    error: function() {
                        showAlert('', 'Something went wrong!', 'error');
                    }
                })
            })
        });
    </script>
@endpush
