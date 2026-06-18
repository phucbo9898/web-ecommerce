<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class SearchController extends BaseController
{
    public function index(Request $request)
    {
        $query = Product::query();
        if ($request->search_category_id != 0) {
            $query = $query->where('category_id', $request->search_category_id);
        }

        if (!empty($request->search_key)) {
            $query = $query->where('name', 'like', '%' . $request->search_key . '%');
        }

        $data = [
            'products' => $query->get()
        ];
        
        return view('fe.search.index', $data);
    }
}
