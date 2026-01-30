@extends('layouts.app')

@section('content')
    <div class="mt-4">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4 class="mb-0">{{ $title ?? '-' }}</h4>
                </div>
            </div>

            <div class="card-body table-responsive">
                <table class="table table-striped table-hover table-bordered">
                    <thead class="table-light">
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Created At</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                            <tr>
                                <td style="width: 30%;">{{ $user->name }}</td>
                                <td style="width: 30%;">{{ $user->email }}</td>
                                <td style="width: 20%;">{{ $user->created_at->format('d M Y, h:i A') }}</td>
                                <td style="width: 20%;">
                                    <div class="d-flex h-100 justify-content-center gap-2">
                                        <a href="javascript:void(0);" data-href="{{ route('user.activate', ['id' => $user->id]) }}"
                                            class="btn btn-sm btn-success approveItem">
                                            Approve
                                        </a>
                                        <a href="javascript:void(0);"
                                            data-href="{{ route('user.delete', ['id' => $user->id]) }}"
                                            class="btn btn-sm btn-danger deleteItem">
                                            Delete
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center">No pending users found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="mt-3">
                    {{ $users->appends(request()->all())->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            $('.deleteItem').on('click', function() {
                let url = $(this).data('href');
                Swal.fire({
                    title: "Are you sure you want to delete this user?",
                    showCancelButton: true,
                    confirmButtonText: "Yes",
                    cancelButtonText: "No",
                    icon: 'warning',
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = url;
                    }
                });
            });

            $('.approveItem').on('click', function() {
                let url = $(this).data('href');
                Swal.fire({
                    title: "Are you sure you want to approve this user?",
                    showCancelButton: true,
                    confirmButtonText: "Yes",
                    cancelButtonText: "No",
                    icon: 'warning',
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = url;
                    }
                });
            });
        });
    </script>
@endpush
