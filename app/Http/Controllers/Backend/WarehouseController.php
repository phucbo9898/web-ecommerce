<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductHistory;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class WarehouseController extends Controller
{
    public function import()
    {
        $products = Product::paginate(10);

        return view('backend.warehouse.import', compact('products'));
    }

    public function importProduct(Request $request, $id)
    {
        try {
            DB::beginTransaction();
            $product = Product::where('id', $id)->first();
            if (!$product) {
                return redirect()->back()->with('error', __('The requested resource is not available'));
            }
            if ($request->product_number < 0) {
                return redirect()->route('admin.warehouse.import')->with('error', 'Vui lòng nhập số nguyên dương!');
            }

            $productQty = $product->quantity + $request->product_number;
            $product->update(['quantity' => $productQty]);

            ProductHistory::create([
                'product_id' => $id,
                'number_import' => $request->product_number,
                'time_import' => Carbon::now()
            ]);

            DB::commit();
            return redirect()->route('admin.warehouse.import')->with('success', 'Đã thêm ' . $request->product_number . ' sản phẩm "' . $product->name . ' " mã ID ' . $id . ' vào kho !');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::debug($e);
        }
    }

    public function exportProduct(Request $request, $id)
    {
        try {
            DB::beginTransaction();
            $product = Product::where('id', $id)->first();
            if (!$product) {
                return redirect()->back()->with('error', __('The requested resource is not available'));
            }
            if ($request->product_number < 0) {
                return redirect()->route('admin.warehouse.import')->with('error', 'Vui lòng nhập số nguyên dương!');
            }

            if ($product->quantity - $request->product_number < 0) {
                return redirect()->route('admin.warehouse.import')->with('error', 'Sản phẩm "' . $product->name . ' " mã ID là ' . $id . ' chỉ còn ' . $product->quantity . ' trong kho !');
            }

            $productQty = $product->quantity - $request->product_number;
            $product->update(['quantity' => $productQty]);

            ProductHistory::create([
                'product_id' => $id,
                'number_export' => $request->product_number,
                'time_export' => Carbon::now()
            ]);

            DB::commit();
            return redirect()->route('admin.warehouse.import')->with('success', 'Đã xuất ' . $request->product_number . ' sản phẩm "' . $product->name . ' " mã ID là ' . $id);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::debug($e);
        }
    }

    public function historyImport(Request $request)
    {
        $options = $request->all();
        $categories = Category::all();
        $productHistory = $this->queryImport($options);

        return view('backend.warehouse.history-import', compact('categories', 'productHistory', 'options'));
    }

    public function historyExport(Request $request)
    {
        $options = $request->all();
        $categories = $this->categoryRepository->get();
        $productHistory = $this->productHistoryRepository->queryExport($options)->get();

        return view('cms.warehouse.history-export', compact('categories', 'productHistory', 'options'));
    }

    private function queryImport($options)
    {
        $query = ProductHistory::with('product');

        if (isset($options['name'])) {
            $query = $query->whereHas('product', function ($sub) use ($options) {
                $sub->where('name', 'LIKE', '%' . escape_like($options['name']) . '%');
            });
        }

        if (isset($options['category_id'])) {
            $query = $query->whereHas('product', function ($sub) use ($options) {
                $sub->whereHas('category', function ($subQ) use ($options) {
                    $subQ->where('categories.id', $options['category_id']);
                });
            });
        }

        if (empty($options['end_time']) && !empty($options['start_time'])) {
            $startOfDay = Carbon::parse($options['start_time'])->format('Y-m-d');
            $query = $query->where(DB::raw('date_format(time_import, "%Y-%m-%d")'), $startOfDay)->orWhere(DB::raw('date_format(time_export, "%Y-%m-%d")'), $startOfDay);
        }

        if (empty($options['start_time']) && !empty($options['end_time'])) {
            $endOfDay = Carbon::parse($options['end_time'])->format('Y-m-d');
            $query = $query->where(DB::raw('date_format(time_import, "%Y-%m-%d")'), $endOfDay)->orWhere(DB::raw('date_format(time_export, "%Y-%m-%d")'), $endOfDay);
        }

        if (!empty($options['end_time']) && !empty($options['start_time'])) {
            $startOfTime = Carbon::parse($options['start_time'])->startOfDay();
            $endOfTime = Carbon::parse($options['end_time'])->endOfDay();
            $query = $query->whereBetween('time_import', [$startOfTime, $endOfTime])->orWhereBetween('time_export', [$startOfTime, $endOfTime]);
        }

        return $query->paginate(10);
    }
}
