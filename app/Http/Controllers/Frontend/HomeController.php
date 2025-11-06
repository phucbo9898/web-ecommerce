<?php

namespace App\Http\Controllers\Frontend;

use App\Enums\ActiveStatus;
use App\Models\Article;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductQtyPay;
use App\Models\Slide;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class HomeController extends BaseController
{
    public function index()
    {
        $startDayOfMonth = Carbon::now()->startOfMonth();
        $endDayOfMonth = Carbon::now()->endOfMonth();
        $slides = Slide::limit(5)->get();
        $products = Product::where('publish', ActiveStatus::ACTIVE)->get();
        $categories = Category::where('status', ActiveStatus::ACTIVE)->get();
        $product_news = Product::where('publish', ActiveStatus::ACTIVE)->orderBy('updated_at', 'DESC')->limit(5)->get();
        $articles = Article::where('publish', ActiveStatus::ACTIVE)->orderBy('updated_at', 'DESC')->take(3)->get();

        $product_pay = ProductQtyPay::whereBetween('date_pay', [$startDayOfMonth, $endDayOfMonth]);
        if ($product_pay->count() > 0) {
            $arrayProductId = $product_pay->pluck('product_id')->toArray();
            $product_best_pays = Product::where('publish', ActiveStatus::ACTIVE)->whereIn('id', $arrayProductId)
                ->orderBy('qty_pay', 'DESC')
                ->limit(5)
                ->get();
        } else {
            $product_best_pays = Product::where('publish', ActiveStatus::ACTIVE)
                ->orderBy('qty_pay', 'DESC')
                ->limit(5)
                ->get();
        }
        return view('fe.index', compact('slides', 'products', 'categories', 'product_news', 'articles', 'product_best_pays'));
    }

    public function aboutUs()
    {
        return view('fe.aboutus');
    }

    public function contact()
    {
        return view('fe.contact');
    }
}
