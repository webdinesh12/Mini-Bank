<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    public function login()
    {
        $title = 'Sign in';
        if (auth('admin')->check()) {
            return redirect()->route('index');
        }
        return view('auth.login', compact('title'));
    }

    public function doLogin(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'email' => ['required', 'email'],
                'password' => ['required', 'max:50']
            ]);
            if ($validator->fails()) {
                return response()->json(['success' => 0, 'errors' => $validator->errors()]);
            }
            if (Auth::attempt(['email' => $request->email, 'password' => $request->password, 'type' => 'user'])) {
                if (Auth::user()->status == 'pending') {
                    Auth::logout();
                    Session::flash('error', 'Your account is pending admin approval. You will be notified once it is approved.');
                    return response()->json(['success' => 0, 'reload' => 1]);
                } else if (Auth::user()->status == 'blocked') {
                    Auth::logout();
                    Session::flash('error', 'Your account has been blocked by the administrator. Please contact support for assistance.');
                    return response()->json(['success' => 0, 'reload' => 1]);
                }
                return response()->json(['success' => 1, 'redirect' => route('index')]);
            } else if (Auth::guard('admin')->attempt(['email' => $request->email, 'password' => $request->password, 'type' => 'admin'])) {
                return response()->json(['success' => 1, 'redirect' => route('index')]);
            }
            return response()->json(['success' => 0, 'msg' => 'Invalid Credentials!']);
        } catch (Exception $err) {
            return response()->json(['success' => 0, 'msg' => $err->getMessage()]);
        }
    }

    public function logout()
    {
        Auth::logout();
        Auth::guard('admin')->logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect()->route('auth.login');
    }

    public function register()
    {
        $title = "Register";
        return view('auth.register', compact('title'));
    }

    public function doRegister(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'first_name' => ['required', 'max:20', 'regex:/^[A-Za-z\s]+$/'],
                'last_name' => ['required', 'max:20', 'regex:/^[A-Za-z\s]+$/'],
                'email' => ['required', 'email', 'unique:users,email'],
                'password' => ['required', 'max:50', Password::min(8)->letters()->numbers()->symbols()->uncompromised()->max(25)],
                'confirm_password' => ['required', 'same:password']
            ], [
                'first_name.regex' => 'First name can only contain letters and spaces.',
                'last_name.regex' => 'Last name can only contain letters and spaces.',
            ]);

            if ($validator->fails()) {
                return response()->json(['success' => 0, 'errors' => $validator->errors()]);
            }

            $user = new User();
            $user->first_name = $request->first_name;
            $user->last_name = $request->last_name;
            $user->email = $request->email;
            $user->password = $request->password;
            $user->save();
            Session::flash('success', 'Your account has been submitted for admin approval. Once approved, you will be notified via email and can log in using your credentials.');
            return response()->json(['success' => 1, 'redirect' => route('auth.login')]);
        } catch (Exception $err) {
            return response()->json(['success' => 0, 'msg' => $err->getMessage()]);
        }
    }
}
