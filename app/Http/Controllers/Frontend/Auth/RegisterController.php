<?php

namespace App\Http\Controllers\Frontend\Auth;

use App\Enums\ActiveStatus;
use App\Enums\UserType;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Frontend\BaseController;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class RegisterController extends BaseController
{
    public function getRegister()
    {
        return view('fe.auth.register');
    }
    public function postRegister(Request $request)
    {
        $data = $request->all();
        if ($data['password'] != $data['confirmpassword']) {
            return redirect()->back()->withInput()->with('errorconfirmpassword', 'có lỗi');
        }

        if ($request->hasFile('image')) {     // image
            $file = $request->file('image');
            $filename = Carbon::now()->toDateString() . '_' . $file->getClientOriginalName();
            $path_upload = '/upload/user/';
            $file->move(public_path($path_upload), $filename);
            $data['image'] = $path_upload . $filename;
        }

        $register = $this->prepareRegister($data);
        $result = User::create($register);

        if ($result) {
            return redirect()->route('frontend.get.login')->with('successregister', 'success');
        }
    }

    private function prepareRegister(array $data)
    {
        $user = [
            'name' => $data['name'] ?? '',
            'address' => $data['address'] ?? '',
            'phone' => $data['phone'] ?? '',
            'email' => $data['email'] ?? '',
            'password' => bcrypt($data['password']) ?? '',
            'avatar' => $data['image'] ?? '',
            'role' => UserType::USER,
            'status' => ActiveStatus::ACTIVE
        ];

        return $user;
    }
}
