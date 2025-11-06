<?php

namespace App\Http\Controllers\Frontend;

use App\Enums\ActiveStatus;
use App\Models\Product;
use App\Models\Rating;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends BaseController
{
    public function show($uuid)
    {
        $product = Product::where('uuid', $uuid)->first();
        $ratings = Rating::where('product_id', $product->id)
            ->orderByDesc('id')
            ->get();

        // get number rating *
        $starCounts = Rating::where('product_id', $product->id)
            ->select('number_star', DB::raw('COUNT(*) as count'))
            ->groupBy('number_star')
            ->pluck('count', 'number_star');

        // push array for transmission
        $eachStar = collect(range(1, 5))
            ->mapWithKeys(fn($i) => [$i => $starCounts[$i] ?? 0])
            ->toArray();

        // list product
        $product_in_category_ids = Product::where('category_id', $product->category_id)
            ->where('publish', ActiveStatus::ACTIVE)
            ->where('id', '!=', $product->id)
            ->latest('updated_at')
            ->take(4)
            ->get();

        $data = [
            'product' => $product,
            'ratings' => $ratings,
            'eachStar' => $eachStar,
            'product_in_category_ids' => $product_in_category_ids,
        ];
        return view('fe.product.index', compact('product', 'ratings', 'eachStar', 'product_in_category_ids'));
    }
}
