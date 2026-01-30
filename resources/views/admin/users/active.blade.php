@extends('layouts.app')

@section('content')
    <div class="mt-4">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">
                        {{ $title ?? 'Users' }}
                    </h4>
                    <form method="GET" action="{{ url()->current() }}" class="d-flex gap-2">
                        <input type="text" name="search" class="form-control form-control-sm"
                            placeholder="Search Name/Email/Account Id" value="{{ request('search') }}">
                        @if (request()->filled('search'))
                            <a href="{{ url()->current() }}" class="btn btn-sm btn-secondary">
                                Clear
                            </a>
                        @endif
                        <button type="submit" class="btn btn-sm btn-primary">
                            Search
                        </button>
                    </form>
                </div>
            </div>

            <div class="card-body table-responsive">
                <table class="table table-bordered table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Account ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Balance</th>
                            <th class="text-center">Status</th>
                            <th>Creation Date</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($users as $user)
                            <tr>
                                <td>{{ $user?->bank_account?->account_id ?? '-' }}</td>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    {{ format_price($user->bank_account?->balance ?? 0) }}
                                </td>
                                <td class="text-center">
                                    <div class="form-check form-switch d-inline-block">
                                        <input class="form-check-input userStatusToggle" type="checkbox" role="switch"
                                            {{ $user->status === 'active' ? 'checked' : '' }}
                                            data-href="{{ route('user.update.status', $user->id) }}">
                                    </div>
                                </td>
                                <td>
                                    {{ $user->created_at ? $user->created_at->format('jS F Y, H:i A') : '-' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">
                                    No users found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="mt-3">
                    {{ $users->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
@endsection
@push('js')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            $('.userStatusToggle').on('change', function() {
                let url = $(this).data('href');
                let checkbox = $(this);
                Swal.fire({
                    title: 'Change user status?',
                    text: `Are you sure you want to update status?`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes',
                    cancelButtonText: 'No',
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.get(url, function(res) {
                            if (res.success == 0) {
                                checkbox.prop('checked', !checkbox.prop('checked'));
                            }
                        }).fail(function() {
                            checkbox.prop('checked', !checkbox.prop('checked'));
                        });
                    } else {
                        checkbox.prop('checked', !checkbox.prop('checked'));
                    }
                });
            });

        });
    </script>
@endpush
