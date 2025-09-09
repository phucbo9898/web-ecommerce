<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showFormLogin()
    {
        return view('backend.auth.login');
    }

    public function login(LoginRequest $request)
    {
        $credentials = $request->only('email', 'password');
        $account = User::where('email', $request->email)->first();

        if ($account->status != 1) {
            return redirect()->back()->with('error', __('Your account has been deactivated.'));
        }

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            if (in_array($user->role, [1,2])) {
                return redirect()->route('admin.home');
            }
        }
        dd(123333);
        return redirect()->back()->with('error', __('Đăng nhập không thành công'));
    }
}
