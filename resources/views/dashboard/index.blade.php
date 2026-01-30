@extends('layouts.app')
@push('css')
    <style>
        .dashboard-card {
            transition: all 0.2s ease-in-out;
            cursor: pointer;
        }

        .dashboard-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 18px rgba(0, 0, 0, 0.08);
        }
    </style>
@endpush
@section('content')
    <h1 class="h3 mb-4">Dashboard</h1>

    <div class="row g-3">
        @if (auth('admin')->check())
            <div class="col-md-3">
                <a href="{{route('user.active')}}" class="text-decoration-none text-dark">
                    <div class="card shadow-sm h-100 dashboard-card">
                        <div class="card-body">
                            <h6 class="text-muted">Users</h6>
                            <h3 class="mb-0">{{ $totalUsers }}</h3>
                            <small class="text-muted">Total Active Users</small>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-3">
                <a href="{{ route('user.pending') }}" class="text-decoration-none text-dark">
                    <div class="card shadow-sm h-100 dashboard-card">
                        <div class="card-body">
                            <h6 class="text-muted">Pending Users</h6>
                            <h3 class="mb-0">{{ $pendingUsers }}</h3>
                            <small class="text-muted">Users Need Approval</small>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-3">
                <a href="{{route('admin.transactions')}}" class="text-decoration-none text-dark">
                    <div class="card shadow-sm h-100 dashboard-card">
                        <div class="card-body">
                            <h6 class="text-muted">Total Today's Transaction</h6>
                            <h3 class="mb-0">{{ $todayTotalTransaction }}</h3>
                            <small class="text-muted">Total Transaction Done Today</small>
                        </div>
                    </div>
                </a>
            </div>
        @else
            <div class="col-md-3">
                <a href="{{ route('transactions') }}" class="text-decoration-none text-dark">
                    <div class="card shadow-sm h-100 dashboard-card">
                        <div class="card-body">
                            <h6 class="text-muted">Balance</h6>
                            <h3 class="mb-0">{{ $balance }}</h3>
                            <small class="text-muted">Total Account Balance</small>
                        </div>
                    </div>
                </a>
            </div>
        @endif
    </div>
@endsection
