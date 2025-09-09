<?php

namespace App\Http\Controllers\Backend;

use App\Enums\UserType;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\StoreRequest;
use App\Http\Requests\User\UpdateRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $options = $request->all();
        $users = $this->searchQuery($options);

        return view('backend.user.index', ['users' => $users, 'options' => $options]);
    }

    public function create()
    {
        return view('backend.user.create');
    }

    public function store(StoreRequest $request)
    {
        try {
            DB::beginTransaction();
            if (!empty($request->file('image'))) {
                $checkExtensionImage = checkExtensionImage($request->file('image'));
                if (!$checkExtensionImage) {
                    return redirect()->back()->withInput()->with('error', __('Only PNG, JPEG and JPG files can be uploaded.'));
                }
            }

            $data = $request->all();

            if ($request->hasFile('image')) {     // image
                $imageUpload = $request->file('image');
                //get name file
                $name = $imageUpload->getClientOriginalName();
                //random name file for save database
                $nameImage = Str::random(3) . "_" . $name;
                // save file
                $imageUpload->move(public_path('/upload/user/image/'), $nameImage);
                $data['image'] = $nameImage;
            }

            $handleData = $this->prepareUser($data);
            User::create($handleData);

            DB::commit();
            return redirect()->route('admin.user.index')->with('success', 'Đã thêm 1 tài khoản người dùng với mật khẩu mặc định là "1" !');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::debug($e);
            return redirect()->back()->with('error', 'Thêm User không thành công');
        }
    }

    public function edit($id)
    {
        $user = User::where('id', $id)->first();
        if (!$user) {
            return redirect()->route('admin.user.index')->with('error', __('The requested resource is not available'));
        }
        return view('backend.user.edit', ['user' => $user]);
    }

    public function update(UpdateRequest $request, $id)
    {
        try {
            DB::beginTransaction();

            $user = User::where('id', $id)->first();
            if (!$user) {
                return redirect()->route('admin.user.index')->with('error', __('The requested resource is not available'));
            }

            if (!empty($request->file('image'))) {
                $checkExtensionImage = checkExtensionImage($request->file('image'));
                if (!$checkExtensionImage) {
                    return redirect()->back()->withInput()->with('error', __('Only PNG, JPEG and JPG files can be uploaded.'));
                }
            }

            $data = $request->all();
            if ($request->hasFile('image')) {     // image
                $imageUpload = $request->file('image');
                //get name file
                $name = $imageUpload->getClientOriginalName();
                //random name file for save database
                $nameImage = Str::random(3) . "_" . $name;
                // save file
                $imageUpload->move(public_path('/upload/user/image/'), $nameImage);
                $data['image'] = $nameImage;
            } else {
                $data['image'] = $user->avatar;
            }

            $data['password'] = $user->password ?? '';
            $handleData = $this->prepareUser($data);
            $user->update($handleData);

            DB::commit();
            return redirect()->route('admin.user.index')->with('success', 'Đã sửa thành công user id số ' . $user->id . '!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::debug($e);
            return redirect()->route('admin.user.index')->with('error', 'Sửa không thành công user id số ' . $user->id . '!');
        }
    }

    public function changePassword(Request $request, $id)
    {
        if ($request->ajax()) {
            $user = User::where('id', $id)->first();
            if (!$user) {
                return redirect()->route('admin.user.index')->with('error', __('The requested resource is not available'));
            }
            if ($request->new_password != $request->confirm_password) {
                return response()->json([
                    'status' => 2
                ]);
            }
            $newPassword = Hash::make($request->new_password);
            $user->update(['password' => $newPassword]);
            return response()->json([
                'status' => 1
            ]);
        }
        return response()->json([
            'status' => 2
        ]);
    }

    public function action(Request $request, $action, $id)
    {
        $user = User::where('id', $id)->first();
        if (!$user) {
            return redirect()->route('admin.user.index')->with('error', __('The requested resource is not available'));
        }

        switch ($action) {
            case 'delete':
                $user->delete();
                $request->session()->flash('success', 'Đã xóa thành công tài khoản mang ID số ' . $id . '!');
                break;
            default:
                dd("Lỗi rồi");
                break;
        }
        return redirect()->route('admin.user.index');
    }

    private function searchQuery($options)
    {
        $query = User::orderBy('created_at', 'desc');

        if (Auth::user()->isSystemAdmin()) {
            $query = $query->where('role', UserType::USER);
        }

        if (isset($options['name'])) {
            $query = $query->where('name', 'LIKE', '%' . escape_like($options['name']) . '%');
        }

        if (isset($options['email'])) {
            $query = $query->where('email', 'LIKE', '%' . escape_like($options['email']) . '%');
        }

        if (isset($options['role'])) {
            $query = $query->where('role', $options['role']);
        }

        return $query->paginate(10);
    }

    private function prepareUser(array $data)
    {
        $user = [
            'name' => $data['name'] ?? '',
            'email' => $data['email'] ?? '',
            'password' => Hash::make(1),
            'phone' => $data['phone'] ?? '',
            'avatar' => $data['image'] ?? '',
            'role' => $data['role'] ?? '',
            'status' => $data['status'] ?? ''
        ];

        return $user;
    }
}
