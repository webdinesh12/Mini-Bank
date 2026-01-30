<?php

use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BankFeatureController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Middleware\CheckAdminLoggedInMiddleware;
use App\Http\Middleware\CheckLoggedInMiddleware;
use App\Http\Middleware\CheckUserLoggedInMiddleware;
use Illuminate\Support\Facades\Route;

Route::controller(AuthController::class)->as('auth.')->group(function () {
    Route::get('/login', 'login')->name('login');
    Route::post('/login', 'doLogin');
    Route::get('/logout', 'logout')->name('logout');
    Route::get('/register', 'register')->name('register');
    Route::post('/register', 'doRegister');
});

//Middleware protected
Route::middleware(['web', CheckLoggedInMiddleware::class])->group(function () {
    Route::get('/', [DashboardController::class, 'dashboard'])->name('index');
    Route::get('/profile', [ProfileController::class, 'profile'])->name('profile');
    Route::post('/profile', [ProfileController::class, 'updateProfile']);
    Route::get('/change-password', [ProfileController::class, 'changePassword'])->name('change-password');
    Route::post('/change-password', [ProfileController::class, 'doChangePassword']);

    //Admin Routes
    Route::middleware(CheckAdminLoggedInMiddleware::class)->group(function () {
        Route::controller(UserController::class)->as('user.')->group(function () {
            Route::get('/pending-users', 'pendingUsers')->name('pending');
            Route::get('/activate-user/{id}', 'activateUser')->name('activate');
            Route::get('/delete-user/{id}', 'deleteUser')->name('delete');
            Route::get('/active-users', 'activeUsers')->name('active');
            Route::get('/update-status/{id}', 'updateStatus')->name('update.status');
        });
        Route::get('/transactions', [UserController::class, 'showAllTransactions'])->name('admin.transactions');
    });
    //User Routes
    Route::middleware(CheckUserLoggedInMiddleware::class)->group(function () {
        Route::controller(BankFeatureController::class)->group(function () {
            Route::get('user/transactions', 'transactions')->name('transactions');
            Route::post('user/withdrawl', 'withdrawlAmount')->name('user.withdrawl');
            Route::post('user/deposit', 'depositAmount')->name('user.deposit');
            Route::post('user/transfer', 'transferAmount')->name('user.transfer');
        });
    });
});
