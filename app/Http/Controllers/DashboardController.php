<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function dashboard()
    {
        $title = "Dashboard";
        $totalUsers = auth('admin')->check() ? User::where('type', 'user')->where('status', 'active')->count() : 0;
        $pendingUsers = auth('admin')->check() ? pending_users_count() : 0;
        $todayTotalTransaction = auth('admin')->check() ? Transaction::where('created_at', '>=', now()->startOfDay())->count() : 0;
        $balance = auth()->check() ? format_price((auth()->user()->bank_account?->balance ?? 0)) : 0;
        return view('dashboard.index', compact('title', 'totalUsers', 'todayTotalTransaction', 'balance', 'pendingUsers'));
    }
}
