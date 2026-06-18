<?php

namespace App\Http\Controllers\Frontend;

use App\Enums\StatusPayment;
use App\Enums\StatusTransaction;
use App\Enums\TypePayment;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class FeatureUserController extends BaseController
{
    public function getFormPay()
    {
        $products = \Cart::session(Auth::id())->getContent();

        $data = [
            'products' => $products,
            'payment' => $checkPayment ?? ''
        ];
        return view('fe.feature-user.formPay', $data);
    }

    public function saveInfoShoppingCart(Request $request)
    {
        $days = Carbon::now()->day;
        $months = Carbon::now()->month;
        $years = Carbon::now()->year;
        $time_order = Carbon::now();
        $delivery_time = Carbon::now()->addDays(5);

        $name = $request->name;
        $address = $request->address;
        $phone = $request->phone;
        $note = $request->note;
        $type_payment = $request->type_payment;
        $totalAmount = \Cart::session(Auth::id())->getSubTotal(0, ',', '.');

        // get value in total money cart
        $totalMoney = str_replace(',', '', \Cart::session(Auth::id())->getSubTotal());

        // insert data transaction and get id then insert
        $transactionId = Transaction::insertGetId([
            'user_id' => Auth::user()->id,
            'customer_name' => $request->name,
            'total_amount' => $totalMoney,
            'note' => $request->note,
            'address' => $request->address,
            'phone' => $request->phone,
            'status' => StatusTransaction::PENDING,
            'type_payment' => $request->type_payment ?? TypePayment::NORMAL,
            'status_payment' => $request->status_payment ?? StatusPayment::NOT_RECEIVED,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        $products = \Cart::session(Auth::id())->getContent();

        // check exist id transaction above and insert order
        if ($transactionId) {
            Transaction::where('id', $transactionId)->update(['payment_code' => 'MGD-' . $transactionId]);
            foreach ($products as $product) {
                Order::insert([
                    'transaction_id' => $transactionId,
                    'product_id' => $product->id,
                    'quantity' => $product->quantity,
                    'price' => $product->attributes->price_old,
                    'sale' => $product->attributes->sale,
                    // 'payment_code' => 'MGD-' . $transactionId,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ]);
                $product_qty = Product::find($product->id);
                $quantity = $product_qty->quantity - $product->quantity;
                $quantity_pay = $product_qty->qty_pay + $product->quantity;
                Product::where('id', $product->id)->update(['quantity' => $quantity, 'qty_pay' => $quantity_pay]);
            }

            $data = [
                'name' => $name,
                'address' => $address,
                'phone' => $phone,
                'note' => $note,
                'type_payment' => $type_payment,
                'total' => $totalAmount,
                'day' => $days,
                'month' => $months,
                'year' => $years,
                'time_order' => $time_order,
                'delivery_time' => $delivery_time,
                'transactionId' => $transactionId,
                'products' => $products
            ];

            if (isset($products)) {
                //Send mail
                Mail::send('fe.email.payment', compact('data'), function ($email) {
                    $email->from(env('MAIL_USERNAME'), 'Kaiser Computer');
                    $email->subject('Hóa đơn thanh toán');  //Tieu de mail
                    $email->to(auth()->user()->email);
                });
            }
            \Cart::session(Auth::id())->clear();
        }

        return redirect()->route('frontend.thank');
        return view('fe.thank');
    }

    public function thankPage()
    {
        return view('fe.thank');
    }
}
