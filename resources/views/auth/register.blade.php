@extends('auth.layout.app')

@section('content')
    <div class="text-center mt-4">
        <h1 class="h2">Create your account</h1>
        <p class="lead">
            Fill in the details below to get started
        </p>
    </div>
    <div class="card">
        <div class="card-body">
            <div class="m-sm-3">
                <form id="registerForm" action="{{ route('auth.register') }}" method="POST" autocomplete="off">
                    <div class="mb-3">
                        <label class="form-label">First Name</label>
                        <input class="form-control form-control-lg" type="text" name="first_name"
                            placeholder="Enter your first name" />
                        <span class="error first_name_error"></span>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Last Name</label>
                        <input class="form-control form-control-lg" type="text" name="last_name"
                            placeholder="Enter your last name" />
                        <span class="error last_name_error"></span>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input class="form-control form-control-lg" type="email" name="email"
                            placeholder="Enter your email" />
                        <span class="error email_error"></span>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input class="form-control form-control-lg" type="password" name="password"
                            placeholder="Enter your password" />
                        <span class="error password_error"></span>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Confirm Password</label>
                        <input class="form-control form-control-lg" type="password" name="confirm_password"
                            placeholder="Confirm your password" />
                        <span class="error confirm_password_error"></span>
                    </div>

                    <div class="d-grid gap-2 mt-3">
                        <button type="submit" class="btn btn-lg btn-primary">Register</button>
                        <div class="text-center my-2 fw-bold">OR</div>
                        <a href="{{ route('auth.login') }}" class="btn btn-lg btn-primary">Login</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@push('js')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            $('#registerForm').on('submit', function(e) {
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
                                showAlert('', res.msg || 'Something went wrong!', 'error');
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
                        form.find('button[type="submit"]').html('Register');
                    },
                    error: function() {
                        showAlert('', 'Something went wrong!', 'error');
                    }
                });
            });
        });
    </script>
@endpush
