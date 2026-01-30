@extends('layouts.app')

@push('cdn')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css" />
@endpush

@push('css')
    <style>
        .password-wrapper {
            position: relative;
            display: flex;
            align-items: center;
        }

        .password-wrapper input {
            width: 100%;
            padding-right: 40px;
        }

        .toggle-password {
            position: absolute;
            right: 10px;
            cursor: pointer;
            color: #555;
            font-size: 16px;
            user-select: none;
        }

        .toggle-password:hover {
            color: #000;
        }
    </style>
@endpush

@section('content')
    <div class="mt-4">
        <div class="card">
            <div class="card-header">
                <div class="gap-2 align-items-center no-h4-0 h4">
                    <h4>Change Password</h4>
                </div>
            </div>
            <div class="card-body">
                <form id="updatePasswordForm" action="{{ route('change-password') }}" autocomplete="off">
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label for="password" class="form-label">Password <span class="mendatory">*</span></label>
                            <div class="password-wrapper">
                                <input type="password" id="password" name="password" class="form-control">
                                <span class="toggle-password" toggle="#password">
                                    <i class="fa fa-eye-slash"></i>
                                </span>
                            </div>
                            <span class="error password_error"></span>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label for="confirm_password" class="form-label">Confirm Password <span
                                    class="mendatory">*</span></label>
                            <div class="password-wrapper">
                                <input type="password" id="confirm_password" name="confirm_password" class="form-control">
                                <span class="toggle-password" toggle="#confirm_password">
                                    <i class="fa fa-eye-slash"></i>
                                </span>
                            </div>
                            <span class="error confirm_password_error"></span>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary mt-2">Submit</button>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            $('#updatePasswordForm').on('submit', function(e) {
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
                            window.location.reload();
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
                        form.find('button[type="submit"]').html('Submit');
                    },
                    error: function() {
                        showAlert('', 'Something went wrong!', 'error');
                    }
                })
            });

            $(".toggle-password").click(function() {
                let input = $($(this).attr("toggle"));
                let icon = $(this).find("i");

                if (input.attr("type") === "password") {
                    input.attr("type", "text");
                    icon.removeClass("fa-eye-slash").addClass("fa-eye");
                } else {
                    input.attr("type", "password");
                    icon.removeClass("fa-eye").addClass("fa-eye-slash");
                }
            });
        });
    </script>
@endpush
