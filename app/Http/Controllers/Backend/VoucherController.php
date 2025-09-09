<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Voucher\StoreRequest;
use App\Http\Requests\Voucher\UpdateRequest;
use App\Models\Voucher;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class VoucherController extends Controller
{
    public function index()
    {
        $vouchers = Voucher::paginate(10);

        return view('backend.voucher.index', compact('vouchers'));
    }

    public function create()
    {
        return view('backend.voucher.create');
    }

    public function store(StoreRequest $request)
    {
        try {
            DB::beginTransaction();
            $data = $request->all();
            if ($request->expire_date) {
                $expire_date = Carbon::parse($request->expire_date)->format('Y-m-d H:i');
                $date_now = Carbon::now()->timezone('Asia/Ho_Chi_Minh')->format('Y-m-d H:i');
                if ($expire_date < $date_now) {
                    return back()->withInput()->with('error', 'Thời gian đã chọn phải lớn hơn hoặc bằng thời gian hiện tại');
                }
            }

            $handleData = $this->prepareVoucher($data);
            Voucher::create($handleData);
            DB::commit();
            return redirect()->route('admin.voucher.index')->with('success', 'Đã thêm 1 mã giảm giá !');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::debug($e->getMessage());
            return redirect()->back()->with('error', 'Thêm mã giảm giá không thành công');
        }
    }

    public function edit($id)
    {
        $voucher = Voucher::where('id', $id)->first();
        if (!$voucher) {
            return redirect()->route('admin.voucher.index')->with('error', __('The requested resource is not available'));
        }

        return view('backend.voucher.edit', compact('voucher'));
    }

    public function update(UpdateRequest $request, $id)
    {
        $voucher = Voucher::where('id', $id)->first();
        if (!$voucher) {
            return redirect()->route('admin.voucher.index')->with('error', __('The requested resource is not available'));
        }
        try {
            DB::beginTransaction();
            $data = $request->all();
            $expire_date = Carbon::parse($request->expire_date)->format('Y-m-d H:i');
            $date_now = Carbon::now()->timezone('Asia/Ho_Chi_Minh')->format('Y-m-d H:i');
            if (Carbon::parse($voucher->expire_date)->format('Y-m-d H:i') != Carbon::parse($request->expire_date)->format('Y-m-d H:i')) {
                if ($expire_date < $date_now) {
                    return back()->withInput()->with('error', 'Thời gian đã chọn phải lớn hơn hoặc bằng thời gian hiện tại');
                }
            }
            $handleData = $this->prepareVoucher($data);
            $voucher->update($handleData);
            DB::commit();
            return redirect()->route('admin.voucher.index')->with('success', 'Đã cập nhật mã giảm giá có id là ' . $id . ' !');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::debug($e->getMessage());
            return redirect()->back()->with('error', 'Cập nhật mã giảm giá không thành công');
        }
    }

    public function action(Request $request, $action, $id)
    {
        $voucher = Voucher::where('id', $id)->first();
        if (!$voucher) {
            return redirect()->route('admin.voucher.index')->with('error', __('The requested resource is not available'));
        }

        switch ($action) {
            case 'delete':
                $voucher->delete();
                $request->session()->flash('success', 'Đã xóa thành công mã giảm giá mang ID số ' . $id . '!');
                break;
            default:
                dd("Lỗi rồi");
                break;
        }
        return redirect()->route('admin.voucher.index');
    }

    private function prepareVoucher(array $data)
    {
        $voucher = [
            'category_id' => $data['category_id'] ?? 1,
            'code' => $data['code'] ?? '',
            'sale' => $data['sale'] ?? '',
            'expire_date' => $data['expire_date'] ?? Carbon::now()->format('Y-m-d'),
        ];

        return $voucher;
    }
}
