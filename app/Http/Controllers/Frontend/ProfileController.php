<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class ProfileController extends BaseController
{
    public function index()
    {
        $profile = Auth::user();
        return view('fe.profile.index', compact('profile'));
    }

    public function update(Request $request, $id)
    {
        try {
            $user = User::find($id);
            if (!$user) {
                return redirect()->route('profile.index')->with('error-user', __('The requested resource is not available'));
            }

            if (!empty($request->current_password) && !Hash::check($request->current_password, $user->password)) {
                return redirect()->route('profile.index')->with('error-password', __('Current password is incorrect'));
            }

            $data = $request->all();
            if (!empty($request['new_password'])) {
                $data['password'] = Hash::make($data['new_password']) ?? '';
            }

            unset($data['current_password']);
            unset($data['new_password']);

            if ($request->hasFile('avatar')) {
                $file = $request->file('avatar');
                $filename = Carbon::now()->toDateString() . '_' . $file->getClientOriginalName();
                $path_upload = '/upload/user/';
                $file->move(public_path($path_upload), $filename);
                $data['avatar'] = $path_upload . $filename;
            } else {
                $data['avatar'] = $user->avatar;
            }

            $result = $user->update($data);
            if ($result) {
                return redirect()->route('frontend.profile.index')->with('success', __('Updated successfully'));
            }
        } catch (\Exception $ex) {
            Log::error($ex->getMessage());
            return redirect()->back()->with('error-update', __('The Update failed'));
        }
    }
}
