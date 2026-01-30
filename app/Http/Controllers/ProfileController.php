<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    public function profile()
    {
        $title = 'Update Profile';
        if (auth('admin')->check()) {
            $user = auth('admin')->user();
        } else {
            $user = auth()->user();
        }
        return view('edit-profile', compact('title', 'user'));
    }

    public function changePassword()
    {
        $title = 'Change Password';
        return view('change-password', compact('title'));
    }

    public function updateProfile(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'first_name' => ['required', 'max:20', 'regex:/^[A-Za-z\s]+$/'],
                'last_name' => ['required', 'max:20', 'regex:/^[A-Za-z\s]+$/'],
            ], [
                'first_name.regex' => 'First name can only contain letters and spaces.',
                'last_name.regex' => 'Last name can only contain letters and spaces.',
            ]);

            if ($validator->fails()) {
                return response()->json(['success' => 0, 'errors' => $validator->errors()]);
            }

            $user = auth()->user();
            if (auth('admin')->check()) {
                $user = auth('admin')->user();
            }
            $user->first_name = $request->first_name;
            $user->last_name = $request->last_name;
            $user->save();
            Session::flash('success', 'Profile Updated Successfully.');
            return response()->json(['success' => 1]);
        } catch (Exception $err) {
            return response()->json(['success' => 0, 'msg' => $err->getMessage()]);
        }
    }

    public function doChangePassword(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'password' => ['bail', 'required', Password::min(8)->letters()->numbers()->symbols()->uncompromised()->max(25)],
                'confirm_password' => ['required', 'same:password', 'max:25'],
            ]);

            if ($validator->fails()) {
                return response()->json(['success' => 0, 'errors' => $validator->errors()]);
            }

            $user = auth()->user();
            if (auth('admin')->check()) {
                $user = auth('admin')->user();
            }
            $user->password = $request->password;
            $user->save();
            Session::flash('success', 'password Updated Successfully.');
            return response()->json(['success' => 1]);
        } catch (Exception $err) {
            return response()->json(['success' => 0, 'msg' => $err->getMessage()]);
        }
    }
}
