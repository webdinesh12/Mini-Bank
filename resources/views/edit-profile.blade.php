@extends('layouts.app')

@section('content')
    <div class="mt-4">
        <div class="card">
            <div class="card-header">
                <div class="gap-2 align-items-center no-h4-0 h4">
                    <h4>Update Profile</h4>
                </div>
            </div>
            <div class="card-body">
                <form id="updateProfileForm" enctype="multipart/form-data" action="{{ route('profile') }}"
                    autocomplete="off">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="first_name" class="form-label">First Name <span class="mendatory">*</span></label>
                            <input type="text" id="first_name" name="first_name" class="form-control"
                                value="{{ $user?->first_name ?? '' }}">
                            <span class="error first_name_error"></span>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="last_name" class="form-label">Last Name <span class="mendatory">*</span></label>
                            <input type="text" id="last_name" name="last_name" class="form-control"
                                value="{{ $user?->last_name ?? '' }}">
                            <span class="error last_name_error"></span>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label for="email" class="form-label">Email <span class="mendatory">*</span></label>
                            <input type="readonly" id="email" name="email" class="form-control"
                                value="{{ $user?->email ?? '' }}" disabled>
                            <span class="error email_error"></span>
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
            $('#image').on('change', function(e) {
                let reader = new FileReader();
                reader.onload = function() {
                    document.getElementById('imagePreview').src = reader.result;
                };
                reader.readAsDataURL(event.target.files[0]);
            })

            $('#updateProfileForm').on('submit', function(e) {
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
            })
        });
    </script>
@endpush
