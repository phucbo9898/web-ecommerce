<?php

namespace App\Http\Controllers\Frontend\Auth;

use App\Http\Controllers\Frontend\BaseController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\URL;

class LoginController extends BaseController
{
    public function getLogin()
    {
        if (Auth::check()) {
            return redirect()->route('frontend.home');
        }

        Session::put('url.intended', URL::previous());
        return view('fe.auth.login');
    }

    public function login(Request $request)
    {
        $infologin = $request->only('email', 'password');
        $account = User::where('email', $request->email)->first();
        if (($account->status ?? '') == 'inactive') {
            return redirect()->back()->with('error-email', __('Your account has been deactivated.'));
        }
        if (Auth::attempt($infologin)) {
            if (Session::get('url.intended') == 'http://webpc.test/shopping') {
                return Redirect::to(Session::get('url.intended'));
            } else {
                return redirect()->route('frontend.home');
            }
        } else {
            return redirect()->back()->with('errorlogin', 'Lỗi');
        }
    }

    public function getLogout()
    {
        \Cart::destroy();
        Session::forget('coupon');
        Auth::logout();
        return redirect()->route('frontend.home');
    }
}
