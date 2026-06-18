<?php

namespace App\Http\Controllers\Frontend;

use App\Enums\ActiveStatus;
use App\Enums\PublishEnum;
use App\Http\Controllers\Controller;
use App\Models\AttributeValue;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductAttribute;
use Illuminate\Http\Request;

class CategoryController extends BaseController
{
    public function index($uuid)
    {
        $category = Category::where('uuid', $uuid)->with('categoryAttribute')->first();
        $products = Product::where([
            'category_id' => $category->id,
            'publish' => PublishEnum::PUBLISHED
        ])->paginate(10);

        $data = [
            'categoryDetail' => $category,
            'products' => $products,
        ];

        return view('fe.category.index', $data);
    }

    public function indexOrder($uuid, $order)
    {
        $category = Category::where('uuid', $uuid)->first();
        $products = Product::where([
            'category_id' => $category->id,
            'publish' => PublishEnum::PUBLISHED
        ]);

        switch ($order) {
            case 'duoi-1trieu':
                $products->where('price', '<', 1000000);
                break;
            case '1trieu-den-10trieu':
                $products->whereBetween('price', [1000000, 10000000]);
                break;
            case '10trieu-den-20trieu':
                $products->whereBetween('price', [10000000, 20000000]);
                break;
            case '20trieu-den-50trieu':
                $products->whereBetween('price', [20000000, 50000000]);
                break;
            case 'tren-50trieu':
                $products->where('price', '>', 50000000);
                break;
            case 'sap-xep-tang-dan':
                $products->orderBy('name', 'ASC');
                break;
            case 'sap-xep-giam-dan':
                $products->orderBy('name', 'DESC');
                break;
            case 'sap-xep-theo-san-pham-moi-nhat':
                $products->orderBy('created_at', 'DESC');
                break;
            case 'sap-xep-theo-san-pham-cu-nhat':
                $products->orderBy('created_at', 'ASC');
                break;
            case 'sap-xep-theo-gia-tang-dan':
                $products->orderBy('price', 'ASC');
                break;
            case 'sap-xep-theo-gia-giam-dan':
                $products->orderBy('price', 'DESC');
                break;
        }

        $products = $products->paginate(10);

        $data = [
            'categoryDetail' => $category,
            'products' => $products
        ];
        return view('fe.category.index', $data);
    }

    public function indexOrderAttribute($uuid, $id)
    {
        $category = Category::where('uuid', $uuid)->first();
        $productByAttributeValueId = ProductAttribute::where('attribute_value_id', $id)->pluck('product_id')->toArray();
        $products = Product::where([
            'category_id' => $category->id,
            'publish' => PublishEnum::PUBLISHED,
        ])->whereIn('id', $productByAttributeValueId)->paginate(10);

        $data = [
            'categoryDetail' => $category,
            'products' => $products,
        ];

        return view('fe.category.index', $data);
    }
}
