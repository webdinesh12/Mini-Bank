@extends('layouts.app')

@section('content')
    <div class="mt-4">
        <div class="card">
            <div class="card-header">
                <div class="row align-items-center">
                    <div class="col-12 mb-2">
                        <h4 class="mb-0">
                            {{ $title ?? 'Transaction History' }}
                        </h4>
                    </div>
                    <div class="col-12">
                        <form method="GET" action="{{ url()->current() }}">
                            <div class="row g-2 justify-content-end">
                                <div class="col-auto">
                                    <input type="date" name="start_date" class="form-control form-control-sm"
                                        value="{{ request('start_date') }}">
                                </div>
                                <div class="col-auto">
                                    <input type="date" name="end_date" class="form-control form-control-sm"
                                        value="{{ request('end_date') }}">
                                </div>
                                <div class="col-auto">
                                    <select name="type" class="form-select form-select-sm">
                                        <option value="">All Types</option>
                                        <option value="deposit" {{ request('type') == 'deposit' ? 'selected' : '' }}>
                                            Deposit
                                        </option>
                                        <option value="withdraw" {{ request('type') == 'withdraw' ? 'selected' : '' }}>
                                            Withdraw
                                        </option>
                                        <option value="transfer" {{ request('type') == 'transfer' ? 'selected' : '' }}>
                                            Transfer
                                        </option>
                                    </select>
                                </div>
                                <div class="col-auto">
                                    <input type="text" name="search" class="form-control form-control-sm"
                                        placeholder="Search by remarks" value="{{ request('search') }}">
                                </div>
                                <div class="col-auto">
                                    <button type="submit" class="btn btn-sm btn-primary">
                                        Search
                                    </button>
                                </div>
                                @if (request()->anyFilled(['start_date', 'end_date', 'type', 'search']))
                                    <div class="col-auto">
                                        <a href="{{ url()->current() }}" class="btn btn-sm btn-outline-secondary">
                                            Clear
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="card-body border-bottom">
                <div class="row">
                    <div class="col-md-8">
                        <div class="d-flex gap-2">
                            <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal"
                                data-bs-target="#depositModal">
                                + Deposit Money
                            </button>
                            <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal"
                                data-bs-target="#withdrawModal">
                                - Withdraw Money
                            </button>
                            <button type="button" class="btn btn-info btn-sm" data-bs-toggle="modal"
                                data-bs-target="#transferModal">
                                Transfer Money
                            </button>
                        </div>
                    </div>
                    <div class="col-md-4 text-end">
                        Current Balance: <strong>{{ format_price($currentBalance ?? 0) }}</strong>
                    </div>
                </div>

            </div>
            <div class="card-body table-responsive">
                <table class="table table-bordered table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Transaction ID</th>
                            <th>Amount</th>
                            <th>Type</th>
                            <th>Remarks</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($transactions as $transaction)
                            <tr>
                                <td>{{ $transaction->trnsaction_id }}</td>
                                <td>
                                    {{ format_price($transaction->amount) }}
                                    @if ($transaction?->transfer_type == 'send')
                                        <span class="text-danger">(Deduct)</span>
                                    @elseif($transaction?->transfer_type == 'recieved')
                                        <span class="text-success">(Credit)</span>
                                    @endif
                                </td>
                                <td>
                                    <span
                                        class="badge {{ $transaction->type === 'deposit' ? 'bg-success' : ($transaction->type === 'withdrawl' ? 'bg-danger' : 'bg-info') }}">
                                        {{ ucfirst($transaction->type) }}
                                    </span>
                                </td>
                                <td>
                                    {{ !empty($transaction->remarks) ? $transaction->remarks : '-' }}
                                </td>
                                <td>
                                    {{ $transaction->created_at->format('d M Y, h:i A') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">
                                    No transactions found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="mt-3">
                    {{ $transactions->withQueryString()->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
@endsection

@push('modal')
    <div class="modal fade" id="depositModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Deposit Money</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" action="{{ route('user.deposit') }}" id="depositForm" autocomplete="off">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Current Balance: {{ format_price($currentBalance ?? 0) }}</label>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Deposit Amount *</label>
                            <input type="text" name="deposit_amount" class="form-control" placeholder="Enter amount">
                            <span class="error deposit_amount_error"></span>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Remarks</label>
                            <textarea name="deposit_remarks" id="deposit_remarks" class="form-control" placeholder="Write a remarks here..."></textarea>
                            <span class="error deposit_remarks_error"></span>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">
                            Cancel
                        </button>

                        <button type="submit" class="btn btn-success btn-sm">
                            Deposit
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="withdrawModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Withdraw Money</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" action="{{ route('user.withdrawl') }}" id="withdrawlForm" autocomplete="off">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Current Balance: {{ format_price($currentBalance ?? 0) }}</label>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Withdraw Amount</label>
                            <input type="text" name="withdrawl_amount" class="form-control"
                                placeholder="Enter amount">
                            <span class="error withdrawl_amount_error"></span>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Remarks</label>
                            <textarea name="remarks" id="remarks" class="form-control" placeholder="Write a remarks here..."></textarea>
                            <span class="error remarks_error"></span>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">
                            Cancel
                        </button>
                        <button type="submit" class="btn btn-danger btn-sm">
                            Withdraw
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="transferModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Transfer Money</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" action="{{ route('user.transfer') }}" id="transferForm" autocomplete="off">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">
                                Current Balance: {{ format_price($currentBalance ?? 0) }}
                            </label>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Transfer To *</label>
                            <select name="to_user_id" class="form-select">
                                <option value="">Select User</option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}">
                                        {{ $user->name }} ({{ $user->email }})
                                    </option>
                                @endforeach
                            </select>
                            <span class="error to_user_id_error"></span>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Transfer Amount *</label>
                            <input type="text" name="transfer_amount" class="form-control"
                                placeholder="Enter amount">
                            <span class="error transfer_amount_error"></span>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Remarks</label>
                            <textarea name="transfer_remarks" class="form-control" placeholder="Write a remarks here..."></textarea>
                            <span class="error transfer_remarks_error"></span>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">
                            Cancel
                        </button>
                        <button type="submit" class="btn btn-primary btn-sm">
                            Transfer
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endpush

@push('js')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            $('#withdrawlForm').on('submit', function(e) {
                e.preventDefault();
                const form = $(this);
                let formData = new FormData(e.target);
                $.ajax({
                    url: e.target.action,
                    type: e.target.method,
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
                        form.find('button[type="submit"]').html('Withdraw');
                    },
                    error: function() {
                        showAlert('', 'Something went wrong!', 'error');
                    }
                })
            })
            $('#depositForm').on('submit', function(e) {
                e.preventDefault();
                const form = $(this);
                let formData = new FormData(e.target);
                $.ajax({
                    url: e.target.action,
                    type: e.target.method,
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
                        form.find('button[type="submit"]').html('Deposit');
                    },
                    error: function() {
                        showAlert('', 'Something went wrong!', 'error');
                    }
                })
            })
            $('#transferForm').on('submit', function(e) {
                e.preventDefault();
                const form = $(this);
                let formData = new FormData(e.target);
                $.ajax({
                    url: e.target.action,
                    type: e.target.method,
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
                        form.find('button[type="submit"]').html('Transfer');
                    },
                    error: function() {
                        showAlert('', 'Something went wrong!', 'error');
                    }
                })
            })
        });
    </script>
@endpush
