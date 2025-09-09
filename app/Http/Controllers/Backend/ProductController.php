<?php

namespace App\Http\Controllers\Backend;

use App\Enums\ActiveStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Product\StoreRequest;
use App\Http\Requests\Product\UpdateRequest;
use App\Models\AttributeValue;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductAttribute;
use App\Models\ProductHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $options = $request->all();
        $products = $this->querySearch($options);
        $categories = Category::all();

        return view('backend.product.index', compact('options', 'products', 'categories'));
    }

    public function create()
    {
        $categories = Category::all();

        return view('backend.product.create', compact('categories'));
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

            $data = $request->except('image');
            // Upload image
            if ($request->hasFile('image')) {
                // get file
                $imageUpload = $request->file('image');
                //get name file
                $name = $imageUpload->getClientOriginalName();
                //random name file for save database
                $nameImage = Str::random(3) . "_" . $name;
                // save file
                $imageUpload->move(public_path('/upload/product/image/'), $nameImage);
                $data['image'] = $nameImage;
            }

            $product = $this->prepareProduct($data);
            $result = Product::create($product);

            foreach ($data as $key => $value) {
                if (is_int($key)) {
                    // check exist attribute value
                    $check_attribute_value = AttributeValue::where([
                        ['attribute_id', $key],
                        ['value', $value]
                    ])->first();

                    if ($check_attribute_value) {
                        // save product_id and atribute_value_id in product_atribute
                        $product_attribute = new ProductAttribute();
                        $product_attribute->product_id = $result->id ?? '';
                        $product_attribute->attribute_value_id = $check_attribute_value->id ?? '';
                        $product_attribute->save();
                    } else {
                        // create attribute value id
                        $attribute_value = new AttributeValue();
                        $attribute_value->attribute_id = $key ?? '';
                        $attribute_value->value = $value ?? '';
                        $attribute_value->save();
                        // save product_id and atribute_value_id in product_atribute
                        $product_attribute = new ProductAttribute();
                        $product_attribute->product_id = $result->id ?? '';
                        $product_attribute->attribute_value_id = $attribute_value->id ?? '';
                        $product_attribute->save();
                    }
                }
            }
            DB::commit();
            return redirect()->route('admin.product.index')->with('success', 'Đã thêm 1 Product!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::debug($e);
            return redirect()->back()->with('error', 'Thêm Product không thành công')->withInput();
        }
    }

    public function edit($id)
    {
        $product = Product::where('id', $id)->first();
        if (!$product) {
            return redirect()->route('admin.product.index')->with('error', __('The requested resource is not available'));
        }
        $categories = Category::all();
        $attribute_product = ProductAttribute::where('product_id', $id)->get();
        $data = [
            'product' => $product,
            'categories' => $categories,
            'attribute_product' => $attribute_product
        ];
        return view('backend.product.edit', $data);
    }

    public function update(UpdateRequest $request, $id)
    {
        try {
            DB::beginTransaction();
            $product = Product::where('id', $id)->first();
            if (!$product) {
                return redirect()->route('admin.product.index')->with('error', __('The requested resource is not available'));
            }

            if (!empty($request->file('image'))) {
                $checkExtImage = checkExtensionImage($request->file('image'));
                if (!$checkExtImage) {
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
                $imageUpload->move(public_path('/upload/product/image/'), $nameImage);
                $data['image'] = $nameImage;
            } else {
                $data['image'] = $product->image;
            }

            $handleData = $this->prepareProduct($data);
            $result = $product->update($handleData);

            // get attribute if Request->variable is int !! That is attribute
            ProductAttribute::where('product_id', $id)->delete();
            foreach ($request->all() as $key => $value) {
                if (is_int($key)) {
                    // variable for check exist attribute value
                    $check_attribute_value = AttributeValue::where([
                        ['attribute_id', '=', $key],
                        ['value', '=', $value]
                    ])->first();
                    if ($check_attribute_value) {
                        // save product_id and atribute_value_id in product_atribute
                        $product_attribute = new ProductAttribute();
                        $product_attribute->product_id = $result->id ?? '';
                        $product_attribute->attribute_value_id = $check_attribute_value->id ?? '';
                        $product_attribute->save();
                    } else {
                        // create attribute value id
                        $attribute_value = new AttributeValue();
                        $attribute_value->attribute_id = $key ?? '';
                        $attribute_value->value = $value ?? '';
                        $attribute_value->save();
                        // save product_id and atribute_value_id in product_atribute
                        $product_attribute = new ProductAttribute();
                        $product_attribute->product_id = $result->id ?? '';
                        $product_attribute->attribute_value_id = $attribute_value->id ?? '';
                        $product_attribute->save();
                    }
                }
            }
            DB::commit();
            return redirect()->route('admin.product.index')->with('success', 'Đã sửa thành công sản phẩm mang ID số ' . $product->id . '!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::debug($e);
            return redirect()->back()->with('error', 'Sửa không thành công sản phẩm mang ID số ' . $product->id . '!');
        }
    }

    public function handle(Request $request, $action, $id)
    {
        $product = Product::where('id', $id)->first();
        if (!$product) {
            return redirect()->route('admin.product.index')->with('error', __('The requested resource is not available'));
        }
        switch ($action) {
            case 'delete':
                try {
                    DB::beginTransaction();
                    $productAttrs = ProductAttribute::where('product_id', $id);
                    if ($productAttrs->get()) {
                        foreach ($productAttrs->get() as $productAttr) {
                            AttributeValue::where('id', $productAttr->attribute_value_id)->delete();
                            $productAttrs->delete();
                        }
                    }
                    ProductHistory::where('product_id', $id)->delete();
                    $product->delete();
                    $request->session()->flash('success', 'Đã xóa thành công sản phẩm mang ID số ' . $id . '!');
                    DB::commit();
                } catch (\Exception $e) {
                    DB::rollBack();
                    Log::debug($e);
                }
                break;
            case 'status':
                $product->publish = $product->publish == 1 ? 2 : 1;
                $product->save();
                break;
            case 'hot':
                $product->hot = $product->hot == 1 ? 2 : 1;
                $product->save();
                break;

            default:
                dd("Lỗi rồi");
                break;
        }
        return redirect()->route('admin.product.index');
    }

    public function getAttribute(Request $request)
    {
        $category = Category::where('id', $request->category_id)->where('status', ActiveStatus::ACTIVE)->first();
        // check if this is update or add !! if id ==0 this is add form and opposite
        if ($request->id == 0) {
            //render html
            $html = view('backend.product.getattribute', compact('category'))->render();
        } else {
            // get product
            $product = Product::where('id', $request->id)->where('publish', ActiveStatus::ACTIVE)->first();
            if (empty($product)) {
                return response()->json('error2');
            }
            $data = [
                'product' => $product,
                'category' => $category
            ];
            //render html
            $html = view('backend.product.getattribute', $data)->render();
        }
        return \response()->json($html);
    }

    private function querySearch($options)
    {
        $query = Product::query();
        if (count($options) <= 0 || count($options) > 0 && empty($options['filter_price']) && empty($options['filter_sold'])) {
            $query = $query->orderby('created_at', 'desc');
        }

        if (!empty($options['name'])) {
            $query = $query->where('name', 'LIKE', '%' . escape_like($options['name']) . '%');
        }

        if (!empty($options['category_id'])) {
            $query = $query->whereHas('category', function ($sub) use ($options) {
                $sub->where('categories.id', $options['category_id']);
            });
        }

        if (!empty($options['filter_price'])) {
            $query = $query->orderBy('price', $options['filter_price']);
        }

        if (!empty($options['filter_sold'])) {
            $query = $query->orderBy('qty_pay', $options['filter_sold']);
        }

        if (!empty($options['publish'])) {
            $query = $query->whereIn('publish', $options['publish']);
        }

        return $query->paginate(10);
    }

    private function prepareProduct(array $data)
    {
        $product = [
            'name' => $data['name'] ?? '',
            'full_name' => $data['full_name'] ?? '',
            'image' => $data['image'] ?? '',
            'content' => $data['content'] ?? '',
            'category_id' => $data['category_id'] ?? '',
            'author_id' => Auth::id(),
            'price' => $data['price'] ?? '',
            'sale' => $data['sale'] ?? '',
            'hot' => $data['hot'] ?? 2,
            'publish' => $data['publish'] ?? 1,
            'description' => $data['description'] ?? ''
        ];

        return $product;
    }
}
