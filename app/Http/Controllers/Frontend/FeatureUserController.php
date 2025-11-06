<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FeatureUserController extends BaseController
{
    public function getFormPay()
    {
        $products = \Cart::session(Auth::id())->getContent();

        $data = [
            'products' => $products,
            'payment' => $checkPayment ?? ''
        ];
        return view('fe.feature-user.formpay', $data);
    }

    public function testAttribute()
    {
        return view('fe.feature-user.test-attribute');
    }
}
