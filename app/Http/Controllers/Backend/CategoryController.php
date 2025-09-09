<?php

namespace App\Http\Controllers\Backend;

use App\Enums\ActiveStatus;
use App\Enums\PublishEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\Category\StoreRequest;
use App\Http\Requests\Category\UpdateRequest;
use App\Models\Attribute as ModelsAttribute;
use App\Models\Category;
use App\Models\CategoryAttribute;
use App\Models\Product;
use App\Models\ProductHistory;
use Carbon\Carbon;
use Illuminate\Session\Store;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $categoryName = $request->input('category_name');
        $attributeId = intval($request->integer('attribute_id'));
        $categoryStatus = intval($request->integer('status', PublishEnum::PUBLISHED));

        $categories = $this->searchCondition(
            $categoryName,
            $attributeId,
            $categoryStatus
        );

        $attributes = ModelsAttribute::all();

        return view('backend.category.index', compact('categories', 'attributes', 'categoryName', 'attributeId', 'categoryStatus'));
    }

    public function create()
    {
        $attributes = ModelsAttribute::all();
        return view('backend.category.create', compact('attributes'));
    }

    public function store(StoreRequest $request)
    {
        try {
            $data = $request->validated();
            $categoryCreated = Category::create([
                'name' => $data['name'] ?? $categoryOld['name'] ?? null,
                'status' => PublishEnum::PUBLISHED
            ]);
            $categoryCreated->categoryAttribute()->attach($data['attributes'], [
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]);

            return redirect()->route('admin.category.index')->with('success', 'Created Successfully');
        } catch (\Exception $exception) {
            DB::rollBack();
            Log::error($exception->getMessage());
            return redirect()->back()->with('error', 'Created Failed');
        }
    }

    public function edit($id)
    {
        $category = Category::where('id', $id)->first();
        if (!$category) {
            return redirect()->route('admin.category.index')->with('error', __('The requested resource is not available'));
        }

        $attributes = ModelsAttribute::all();
        $categoryAttribute = CategoryAttribute::where('category_id', $id)->pluck('attribute_id')->toArray();

        $data = [
            'attributes' => $attributes,
            'category' => $category,
            'arrayCategoryAttribute' => $categoryAttribute
        ];

        return view('backend.category.edit', $data);
    }

    public function update(UpdateRequest $request, $id)
    {
        try {
            DB::beginTransaction();
            $category = Category::where('id', $id)->first();
            if (!$category) {
                return redirect()->route('admin.category.index')->with('error', __('The requested resource is not available'));
            }

            $data = $request->all();
            $category->categoryAttribute()->detach();
            $category->update([
                'name' => $data['name'] ?? $categoryOld['name'] ?? null,
                'status' => PublishEnum::PUBLISHED
            ]);

            $category = Category::where('id', $id)->first();
            $categoryAttributes = array_keys($data, 'on');
            $category->categoryAttribute()->attach($categoryAttributes, ['created_at' => Carbon::now(), 'updated_at' => Carbon::now()]);
            DB::commit();
            return redirect()->route('admin.category.index')->with('success', 'Update success!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::debug($e);
            return redirect()->back()->with('error', 'Update failed!');
        }
    }

    public function handle(Request $request, $action, $id)
    {
        $category = Category::where('id', $id)->first();
        if (!$category) {
            return redirect()->route('admin.category.index')->with('error', __('The requested resource is not available'));
        }
        try {
            DB::beginTransaction();
            switch ($action) {
                case 'delete':
                    $products = Product::where('category_id', $id);
                    ProductHistory::whereIn('product_id', $products->pluck('id')->toArray())->delete();
                    $products->delete();
                    $category->categoryAttribute()->detach();
                    $category->delete();
                    $request->session()->flash('success', 'Delete success!');
                    break;
                case 'status':
                    $category->status = $request->status;
                    $category->save();
                    break;
                default:
                    break;
            }
            DB::commit();
            return redirect()->route('admin.category.index');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::debug($e);
        }
    }

    private function searchCondition(
        ?string $name = null,
        ?int $attributeId = null,
        ?int $status = null
    ) {
        return Category::when($name, function ($query, ?string $name) {
            return $query->where('name', 'like', '%' . $name . '%');
        })->when($attributeId, function ($query, ?int $attributeId) {
            return $query->whereHas('categoryAttribute', function ($query) use ($attributeId) {
                return $query->where('attribute_id', $attributeId);
            });
        })->when($status, function ($query, ?int $status) {
            return $query->where('status', $status);
        })->with('categoryAttribute')->orderBy('created_at', 'desc')->get();
    }
}
