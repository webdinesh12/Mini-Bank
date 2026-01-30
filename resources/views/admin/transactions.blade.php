@extends('layouts.app')

@section('content')
    <div class="mt-4">
        <div class="card">
            <div class="card-header">
                <div class="row align-items-center">
                    <div class="col-12 mb-2">
                        <h4 class="mb-0">
                            {{ $title ?? '' }}
                        </h4>
                    </div>
                    <div class="col-12">
                        <form method="GET" action="{{ url()->current() }}">
                            <div class="row g-2 justify-content-end">
                                <div class="col-auto">
                                    <select name="bank_account_id" class="form-select form-select-sm">
                                        <option value="">All Accounts</option>
                                        @foreach ($users as $user)
                                            <option value="{{ $user->bank_account->id }}" {{ $user->bank_account->id == request('bank_account_id') ? 'selected' : ''}}>
                                                {{ $user?->name ?? '' }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
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
                                @if (request()->anyFilled(['start_date', 'end_date', 'type', 'search', 'bank_account_id']))
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

            <div class="card-body table-responsive">
                <table class="table table-bordered table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Transaction ID</th>
                            <th>Account ID</th>
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
                                <td>{{ $transaction?->bank_account?->account_id ?? '-' }}</td>
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
                                <td colspan="6" class="text-center">
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
