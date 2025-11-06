<?php

namespace App\Http\Controllers\Frontend;

use App\Enums\StatusPayment;
use App\Enums\StatusTransaction;
use App\Enums\TypePayment;
use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\Product;
use App\Models\Transaction;
use Carbon\Carbon;
use Darryldecode\Cart\CartCondition;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ShoppingCartController extends BaseController
{
    public function index()
    {
        $products = \Cart::session(Auth::id())->getContent();
        // \Cart::session(auth()->id())->removeConditionsByType('coupon');
        $data = [
            'products' => $products
        ];

        return view('fe.shopping-cart.index', $data);
    }

    public function getCoupon()
    {
        return view('fe.shopping-cart.handle-coupon');
    }

    // public function addCoupon(Request $request)
    // {
    //     $coupon = Coupon::where('coupon_code', $request->coupon)->first();
    //     if (empty($coupon)) {
    //         return back()->withInput()->with('error', __('Voucher application failed'));
    //     }

    //     $getCondition = \Cart::session(auth()->id())->getConditions();
    //     if (count($getCondition) <= 0) {
    //         $condition = new CartCondition(array(
    //             'name' => 'coupon',
    //             'type' => 'coupon',
    //             'target' => 'total',
    //             'value' => '-' . $coupon->discount . '%',
    //             'attributes' => [$coupon->toArray()]
    //         ));
    //     } else {
    //         $infoCartCondition = $getCondition->first();
    //         $discountFinal = $coupon->discount;
    //         foreach ($infoCartCondition->getAttributes() as $conditionAttr) {
    //             if ($conditionAttr['coupon_code'] == $request->coupon) {
    //                 return response()->json([
    //                     'status' => 'warning',
    //                     'message' => __('123123123 applied voucher')
    //                 ]);
    //             }

    //             $discountFinal += $conditionAttr['discount'];
    //         }

    //         $oldAttributes = $getCondition->first()->getAttributes();

    //         \Cart::session(auth()->id())->removeConditionsByType('coupon');

    //         $condition = new CartCondition(array(
    //             'name' => 'coupon',
    //             'type' => 'coupon',
    //             'target' => 'total',
    //             'value' => '-' . $discountFinal . '%',
    //             'attributes' => array_merge($oldAttributes, [$coupon->toArray()])
    //         ));
    //     }

    //     \Cart::session(auth()->id())->condition($condition);

    //     return response()->json([
    //         'status' => 'success',
    //         'message' => __('Successfully applied voucher'),
    //         'total' => \Cart::session(auth()->id())->getTotal(),
    //         'discount' => $coupon->discount
    //     ]);
    // }

    // public function removeCoupon(Request $request)
    // {
    //     $getCondition = \Cart::session(auth()->id())->getConditions();
    //     $infoCartCondition = $getCondition->first()->getAttributes();
    //     $discountFinal = 0;
    //     foreach ($infoCartCondition as $key => $conditionAttr) {
    //         if ($conditionAttr['coupon_code'] == $request->coupon_code) {
    //             unset($infoCartCondition[$key]);
    //         } else {
    //             $discountFinal += $conditionAttr['discount'];
    //         }
    //     }

    //     \Cart::session(auth()->id())->removeConditionsByType('coupon');

    //     $condition = new CartCondition(array(
    //         'name' => 'coupon',
    //         'type' => 'coupon',
    //         'target' => 'total',
    //         'value' => '-' . $discountFinal . '%',
    //         'attributes' => $infoCartCondition
    //     ));

    //     \Cart::session(auth()->id())->condition($condition);

    //     return response()->json([
    //         'status' => 'success',
    //         'message' => __('Successfully delete voucher'),
    //     ]);
    // }

    public function addCoupon(Request $request)
    {
        $userId = Auth::id();
        $cart = \Cart::session($userId);

        $coupon = Coupon::where('coupon_code', $request->coupon)->first();
        if (!$coupon) {
            return response()->json([
                'status' => 'error',
                'message' => __('Voucher application failed'),
            ], 400);
        }

        $conditions = $cart->getConditionsByType('coupon');
        $existingCoupons = $conditions->first()?->getAttributes() ?? [];

        foreach ($existingCoupons as $c) {
            if ($c['coupon_code'] === $coupon->coupon_code) {
                return response()->json([
                    'status' => 'warning',
                    'message' => __('This voucher has already been applied'),
                ]);
            }
        }

        $updatedCoupons = array_merge($existingCoupons, [$coupon->toArray()]);

        $totalDiscount = collect($updatedCoupons)->sum('discount');

        $cart->removeConditionsByType('coupon');

        $condition = new CartCondition([
            'name' => 'Coupon Discount',
            'type' => 'coupon',
            'target' => 'total',
            'value' => '-' . $totalDiscount . '%',
            'attributes' => $updatedCoupons,
        ]);

        $cart->condition($condition);

        return response()->json([
            'status' => 'success',
            'message' => __('Successfully applied voucher'),
            'total' => $cart->getTotal(),
            'discount' => $coupon->discount,
        ]);
    }

    public function removeCoupon(Request $request)
    {
        $userId = Auth::id();
        $cart = \Cart::session($userId);

        $conditions = $cart->getConditionsByType('coupon');
        $attributes = $conditions->first()?->getAttributes() ?? [];

        $updated = array_filter($attributes, function ($c) use ($request) {
            return $c['coupon_code'] !== $request->coupon_code;
        });

        $totalDiscount = collect($updated)->sum('discount');

        $cart->removeConditionsByType('coupon');

        if ($totalDiscount > 0) {
            $condition = new CartCondition([
                'name' => 'Coupon Discount',
                'type' => 'coupon',
                'target' => 'total',
                'value' => '-' . $totalDiscount . '%',
                'attributes' => array_values($updated),
            ]);

            $cart->condition($condition);
        }

        return response()->json([
            'status' => 'success',
            'message' => __('Successfully removed voucher'),
            'total' => $cart->getTotal(),
        ]);
    }

    public function addProduct(Request $request, $id)
    {
        if ($request->ajax()) {
            if (Auth::check()) {
                // find product
                $product = Product::find($id);

                //find product in cart for get number product in cart how many
                $product_in_cart = \Cart::session(Auth::id())->getContent()->where('id', $id)->first();
                // check product in cart exist and create variable test + 1 product to qty
                if (!empty($product_in_cart)) {
                    $then_number_product_in_cart = $product_in_cart->quantity + 1;
                    // check if test variable qty not ennough number product return false
                    if ($then_number_product_in_cart > $product->quantity)
                        return response()->json([
                            'status' => 2,
                            'product_less' => $product->quantity
                        ]);
                }
                // check product exist
                if (!$product) {
                    return response()->json([
                        'status' => 3
                    ]);
                }

                // get price when customer add product to cart
                $price = $product->price;
                if ($product->sale) {
                    $price = $price * (100 - $product->sale) / 100;
                }
                if ($product->quantity == 0)
                    return response()->json([
                        'status' => 4
                    ]);

                // add product to cart
                \Cart::session(Auth::id())->add(
                    [
                        'id' => $id,
                        'name' => $product->name,
                        'price' => $price,
                        'quantity' => 1,
                        'weight' => 0,
                        'attributes' => [
                            'uuid' => $product->uuid,
                            'image' => $product->image,
                            'price_old' => $product->price,
                            'sale' => $product->sale
                        ]
                    ]
                );
                // dd(\Cart::session(Auth::id())->getContent());
                return response()->json([
                    'status' => 1,
                    'number_product_in_cart' => \Cart::session(Auth::id())->getTotalQuantity(),
                    'price_total_cart' => number_format(\Cart::session(Auth::id())->getTotal(), 0, ',', '.')
                ]);
            }
        }
    }

    public function editProductItem(Request $request)
    {
        try {
            $pro_id = $request->pro_id;
            $number_product_edit = $request->number_product_edit;
            if ($number_product_edit <= 0) {
                return redirect()->back()->with('warning', __('The number of products must be greater than 0'));
            }
            // get cart
            $cart = \Cart::session(Auth::id())->getContent();
            //get number product in stock
            $number_product_in_stock = Product::find($pro_id)->quantity;
            $product_in_stock_name = Product::find($pro_id)->name;
            //get number product in cart
            $number_product_in_cart = $cart->where('id', $pro_id)->first()->quantity;
            //check number product edit bigger number product in stock
            if ($number_product_edit > $number_product_in_stock) {
                return redirect()->back()->with('warning', __('Product') . ' ' . $product_in_stock_name . ' ' .  __('only') . ' ' . $number_product_in_stock . ' ' .  __('in stock'));
            }

            //get rowId for update number product in cart
            $item = $cart->get($pro_id);
            if ($item) {
                \Cart::update($item->id, [
                    'quantity' => [
                        'relative' => false,
                        'value' => $number_product_edit,
                    ],
                ]);
            }

            return redirect()->back()->with('success', __('Update the number of successful products'));
        } catch (\Exception $exception) {
            Log::debug($exception->getMessage());
        }
    }

    public function deleteProductItem($key)
    {
        \Cart::session(Auth::id())->remove($key);
        return redirect()->back()->with('success', __('Delete successful products'));
    }

    public function execPostRequest($url, $data)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt(
            $ch,
            CURLOPT_HTTPHEADER,
            array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($data)
            )
        );
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        //execute post
        $result = curl_exec($ch);
        //close connection
        curl_close($ch);
        return $result;
    }

    public function paymentMomo(Request $request)
    {

        $amountTotal = (int)round($request->total_momo);
        $transactionId = Transaction::create([
            'customer_name' => $request->name ?? '',
            'user_id' => Auth::id(),
            'total_amount' => $amountTotal ?? '',
            'note' => $request->note ?? '',
            'address' => $request->address ?? '',
            'phone' => $request->phone_number ?? '',
            'status' => StatusTransaction::PENDING,
            'type_payment' => $request->type_payment ?? TypePayment::MOMO,
            'status_payment' => $request->status_payment ?? StatusPayment::NOT_RECEIVED,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        if ($transactionId) {
            Transaction::where('id', $transactionId->id)->update(['payment_code' => "MGD" . "-" . $transactionId->id]);
            $products = \Cart::session(Auth::id())->getContent();
            $dataOrders = [];
            foreach ($products as $product) {
                $dataOrders[] = [
                    'transaction_id' => $transactionId->id ?? '',
                    'product_id' => $product->id ?? '',
                    'quantity' => $product->quantity ?? '',
                    'price' => $product->attributes->price_old ?? '',
                    'sale' => $product->attributes->sale ?? '',
                    // 'payment_code' => "MGD" . "-" . $transactionId->id ?? '',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ];
            }
            Order::insert($dataOrders);
        }

        $endpoint = "https://test-payment.momo.vn/v2/gateway/api/create";
        $partnerCode = env('MOMO_PARTNER_CODE');
        $accessKey = env('MOMO_ACCESS_KEY');
        $secretKey = env('MOMO_SECRET_KEY');
        $orderInfo = "Thanh toán qua ATM MoMo";
        $amount = $amountTotal;
        $orderId = "MGD" . "-" . $transactionId->id . '_' . time();
        $redirectUrl = request()->getSchemeAndHttpHost() . '/feature-user/checkout/momo-check';
        $ipnUrl = request()->getSchemeAndHttpHost() . '/feature-user/checkout/momo-check';
        $extraData = "";

        $requestId = time() . "";
        $requestType = "payWithATM";

        //before sign HMAC SHA256 signature
        $rawHash = "accessKey=" . $accessKey . "&amount=" . $amount . "&extraData=" . $extraData . "&ipnUrl=" . $ipnUrl . "&orderId=" . $orderId . "&orderInfo=" . $orderInfo . "&partnerCode=" . $partnerCode . "&redirectUrl=" . $redirectUrl . "&requestId=" . $requestId . "&requestType=" . $requestType;
        $signature = hash_hmac("sha256", $rawHash, $secretKey);
        $data = array(
            'partnerCode' => $partnerCode,
            'partnerName' => "Test",
            "storeId" => "MomoTestStore",
            'requestId' => $requestId,
            'amount' => $amount,
            'orderId' => $orderId,
            'orderInfo' => $orderInfo,
            'redirectUrl' => $redirectUrl,
            'ipnUrl' => $ipnUrl,
            'lang' => 'vi',
            'extraData' => $extraData,
            'requestType' => $requestType,
            'signature' => $signature,
        );

        $result = $this->execPostRequest($endpoint, json_encode($data));
        $jsonResult = json_decode($result, true);  // decode json

        return redirect()->to($jsonResult['payUrl']);
    }

    public function paymentVNPay(Request $request)
    {
        // get value in total money cart
        $totalMoney = (int)round($request->total_money);
        // dd($request->all());
        // insert data transaction and get id then insert

        $transactionId = Transaction::insertGetId([
            'customer_name' => $request->name ?? '',
            'user_id' => Auth::user()->id,
            'total_amount' => $totalMoney ?? '',
            'note' => $request->note ?? '',
            'address' => $request->address ?? '',
            'phone' => $request->phone_number ?? '',
            'status' => StatusTransaction::PENDING,
            'type_payment' => $request->type_payment ?? TypePayment::VNPAY,
            'status_payment' => $request->status_payment ?? StatusPayment::NOT_RECEIVED,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        if ($transactionId) {
            Transaction::where('id', $transactionId)->update(['payment_code' => "MGD" . "-" . $transactionId]);
            $products = \Cart::session(Auth::id())->getContent();
            $dataOrders = [];
            foreach ($products as $product) {
                $dataOrders[] = [
                    'transaction_id' => $transactionId ?? '',
                    'product_id' => $product->id ?? '',
                    'quantity' => $product->quantity ?? '',
                    'price' => $product->attributes->price_old ?? '',
                    'sale' => $product->attributes->sale ?? '',
                    // 'payment_code' => "MGD" . "-" . $transactionId ?? '',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ];
            }
            Order::insert($dataOrders);
        }

        $vnp_Url = "https://sandbox.vnpayment.vn/paymentv2/vpcpay.html";
        $vnp_Returnurl = request()->getSchemeAndHttpHost() . '/feature-user/checkout/vnpay-check';
        $vnp_TmnCode = env('VNP_CODE');  //Mã website tại VNPAY
        $vnp_HashSecret = env('VNP_HASH_SECRET'); //Chuỗi bí mật

        $vnp_TxnRef = "MGD" .  "-" . $transactionId . '_' . time();
        $vnp_OrderInfo = "Thanh toán đơn hàng";
        $vnp_OrderType = 'billpayment';
        $vnp_Amount = $_POST['total_money'] * 100;
        $vnp_Locale = "vn";
        $vnp_BankCode = "";
        $vnp_IpAddr = $_SERVER['REMOTE_ADDR'];
        $inputData = array(
            "vnp_Version" => "2.1.0",
            "vnp_TmnCode" => $vnp_TmnCode,
            "vnp_Amount" => $vnp_Amount,
            "vnp_Command" => "pay",
            "vnp_CreateDate" => date('YmdHis'),
            "vnp_CurrCode" => "VND",
            "vnp_IpAddr" => $vnp_IpAddr,
            "vnp_Locale" => $vnp_Locale,
            "vnp_OrderInfo" => $vnp_OrderInfo,
            "vnp_OrderType" => $vnp_OrderType,
            "vnp_ReturnUrl" => $vnp_Returnurl,
            "vnp_TxnRef" => $vnp_TxnRef,
        );

        if (isset($vnp_BankCode) && $vnp_BankCode != "") {
            $inputData['vnp_BankCode'] = $vnp_BankCode;
        }
        if (isset($vnp_Bill_State) && $vnp_Bill_State != "") {
            $inputData['vnp_Bill_State'] = $vnp_Bill_State;
        }

        ksort($inputData);
        $query = "";
        $i = 0;
        $hashdata = "";
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashdata .= urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
            $query .= urlencode($key) . "=" . urlencode($value) . '&';
        }

        $vnp_Url = $vnp_Url . "?" . $query;
        if (isset($vnp_HashSecret)) {
            $vnpSecureHash = hash_hmac('sha512', $hashdata, $vnp_HashSecret); //
            $vnp_Url .= 'vnp_SecureHash=' . $vnpSecureHash;
        }
        $returnData = array('code' => '00', 'message' => 'success', 'data' => $vnp_Url);
        if (isset($_POST['redirect'])) {
            header('Location: ' . $vnp_Url);
            die();
        } else {
            echo json_encode($returnData);
        }
    }

    // Check payment Momo and save data to database
    public function momoCheck(Request $request)
    {
        try {
            DB::beginTransaction();
            $resultCode = $request->get('resultCode');
            $orderId = $request->get('orderId');

            // if ($resultCode != null) {
            //     if ($resultCode == 0) {
            //         $paymentCode = substr($orderId, 0, strpos($orderId, '_'));
            //         Transaction::where('payment_code', $paymentCode)->update(['status_payment' => 'Paуment received']);

            //         $products = \Cart::content();
            //         foreach ($products as $product) {
            //             $product_qty = Product::find($product->id);
            //             $quantity = $product_qty->quantity - $product->qty;
            //             $quantity_pay = $product_qty->qty_pay + $product->qty;
            //             Product::where('id', $product->id)->update(['quantity' => $quantity ?? '', 'qty_pay' => $quantity_pay ?? '']);
            //         }

            //         $transaction = Transaction::where('payment_code', $paymentCode)->first();
            //         $transactionId = $transaction->id ?? '';
            //         $name = $transaction->customer_name ?? '';
            //         $address = $transaction->address ?? '';
            //         $phone = $transaction->phone ?? '';
            //         $note = $transaction->note ?? '';
            //         $type_payment = $transaction->type_payment ?? '';
            //         $totalAmount = number_format($transaction->total) . " " . "VNĐ" ?? '';
            //         $days = Carbon::now()->day;
            //         $months = Carbon::now()->month;
            //         $years = Carbon::now()->year;
            //         $time_order = Carbon::now();
            //         $delivery_time = Carbon::now()->addDays(5);
            //         $status_payment = $transaction->status_payment ?? '';
            //         $codeVoucher = \session()->get('coupon')->code ?? '';
            //         $saleVoucher = \session()->get('coupon')->sale ?? '';
            //         $data = [
            //             'name' => $name,
            //             'address' => $address,
            //             'phone' => $phone,
            //             'note' => $note,
            //             'type_payment' => $type_payment,
            //             'total' => $totalAmount,
            //             'day' => $days,
            //             'month' => $months,
            //             'year' => $years,
            //             'time_order' => $time_order,
            //             'delivery_time' => $delivery_time,
            //             'status_payment' => $status_payment,
            //             'codeVoucher' => $codeVoucher ?? '',
            //             'saleVoucher' => $saleVoucher ?? '',
            //         ];

            //         //Send mail
            //         if (isset($products)) {
            //             Mail::send('fe.email.payment', compact('transactionId', 'data', 'products'), function ($email) {
            //                 $email->from(env('MAIL_USERNAME'), 'Kaiser Computer');
            //                 $email->subject('Hóa đơn thanh toán');  //Tieu de mail
            //                 $email->to(auth()->user()->email);
            //             });
            //         }

            //         \Cart::destroy();
            //         Session::forget('coupon');
            //         return view('fe.thank');
            //     } else {
            //         Order::where('payment_code', $orderId)->delete();
            //         Transaction::where('payment_code', $orderId)->delete();
            //         return redirect()->route('feature-user.checkout');
            //     }
            // }
            $this->handleOrder($resultCode, $orderId);
            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            Log::debug($exception->getMessage());
        }
    }

    // Check payment VNPay and save data to database
    public function vnpayCheck(Request $request)
    {
        $resultCode = $request->get('vnp_ResponseCode');
        $orderId = $request->get('vnp_TxnRef');
        $this->handleOrder($resultCode, $orderId);
        // $vnp_ResponseCode = $request->get('vnp_ResponseCode');
        // $vnp_TxnRef = $request->get('vnp_TxnRef');
        // $vnp_Amount = $request->get('vnp_Amount');

        // if ($vnp_ResponseCode != null) {
        //     if ($vnp_ResponseCode == 00) {
        //         $paymentCode = substr($vnp_TxnRef, 0, strpos($vnp_TxnRef, '_'));
        //         Transaction::where('payment_code', $paymentCode)->update(['status_payment' => 'Paуment received']);
        //         $products = \Cart::content();
        //         foreach ($products as $product) {
        //             $product_qty = Product::find($product->id);
        //             $quantity = $product_qty->quantity - $product->qty;
        //             $quantity_pay = $product_qty->qty_pay + $product->qty;
        //             Product::where('id', $product->id)->update(['quantity' => $quantity ?? '', 'qty_pay' => $quantity_pay ?? '']);
        //         }
        //         $transaction = Transaction::where('payment_code', $paymentCode)->first();
        //         $transactionId = $transaction->id ?? '';
        //         $name = $transaction->customer_name ?? '';
        //         $address = $transaction->address ?? '';
        //         $phone = $transaction->phone ?? '';
        //         $note = $transaction->note ?? '';
        //         $type_payment = $transaction->type_payment ?? '';
        //         $totalAmount = number_format($transaction->total) . " " . "VNĐ" ?? '';
        //         $days = Carbon::now()->day;
        //         $months = Carbon::now()->month;
        //         $years = Carbon::now()->year;
        //         $time_order = Carbon::now();
        //         $delivery_time = Carbon::now()->addDays(5);
        //         $status_payment = $transaction->status_payment ?? '';
        //         $codeVoucher = \session()->get('coupon')->code ?? '';
        //         $saleVoucher = \session()->get('coupon')->sale ?? '';
        //         $data = [
        //             'name' => $name,
        //             'address' => $address,
        //             'phone' => $phone,
        //             'note' => $note,
        //             'type_payment' => $type_payment,
        //             'total' => $totalAmount,
        //             'day' => $days,
        //             'month' => $months,
        //             'year' => $years,
        //             'time_order' => $time_order,
        //             'delivery_time' => $delivery_time,
        //             'status_payment' => $status_payment,
        //             'codeVoucher' => $codeVoucher ?? '',
        //             'saleVoucher' => $saleVoucher ?? '',
        //         ];

        //         //Send mail
        //         if (isset($products)) {
        //             Mail::send('fe.email.payment', compact('transactionId', 'data', 'products'), function ($email) {
        //                 $email->from(env('MAIL_USERNAME'), 'Kaiser Computer');
        //                 $email->subject('Hóa đơn thanh toán');  //Tieu de mail
        //                 $email->to(auth()->user()->email);
        //             });
        //         }
        //         \Cart::destroy();
        //         Session::forget('coupon');
        //         return view('fe.thank');
        //     } else {
        //         Order::where('payment_code', $vnp_TxnRef)->delete();
        //         Transaction::where('payment_code', $vnp_TxnRef)->delete();
        //         return redirect()->route('feature-user.checkout');
        //     }
        // }
    }

    private function handleOrder($resultCode, $orderId)
    {
        try {
            $cart = \Cart::session(Auth::id());
            DB::beginTransaction();
            if ($resultCode != null) {
                if ($resultCode == 00) {
                    $paymentCode = substr($orderId, 0, strpos($orderId, '_'));
                    Transaction::where('payment_code', $paymentCode)->update(['status_payment' => StatusPayment::RECEIVED]);
                    $products = $cart->getContent();
                    foreach ($products as $product) {
                        $product_qty = Product::find($product->id);
                        $quantity = $product_qty->quantity - $product->qty;
                        $quantity_pay = $product_qty->qty_pay + $product->qty;
                        Product::where('id', $product->id)->update(['quantity' => $quantity ?? '', 'qty_pay' => $quantity_pay ?? '']);
                    }
                    $transaction = Transaction::where('payment_code', $paymentCode)->first();
                    $transactionId = $transaction->id ?? '';
                    $name = $transaction->customer_name ?? '';
                    $address = $transaction->address ?? '';
                    $phone = $transaction->phone ?? '';
                    $note = $transaction->note ?? '';
                    $type_payment = $transaction->type_payment ?? '';
                    $totalAmount = number_format($transaction->total) . " " . "VNĐ" ?? '';
                    $days = Carbon::now()->day;
                    $months = Carbon::now()->month;
                    $years = Carbon::now()->year;
                    $time_order = Carbon::now();
                    $delivery_time = Carbon::now()->addDays(5);
                    $status_payment = $transaction->status_payment ?? '';

                    $conditions = $cart->getConditionsByType('coupon');
                    $attributes = $conditions->first()?->getAttributes() ?? [];

                    $updated = array_filter($attributes, function ($c) use ($request) {
                        return $c['coupon_code'] !== $request->coupon_code;
                    });

                    $codeVoucher = \session()->get('coupon')->code ?? '';
                    $saleVoucher = \session()->get('coupon')->sale ?? '';
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
                        'status_payment' => $status_payment,
                        'codeVoucher' => $codeVoucher ?? '',
                        'saleVoucher' => $saleVoucher ?? '',
                    ];

                    \Cart::session(Auth::id())->clear();
                    // Session::forget('coupon');
                    DB::commit();

                    //Send mail
                    if (isset($products)) {
                        Mail::send('fe.email.payment', compact('transactionId', 'data', 'products'), function ($email) {
                            $email->from(env('MAIL_USERNAME'), 'Kaiser Computer');
                            $email->subject('Hóa đơn thanh toán');
                            $email->to(auth()->user()->email);
                        });
                    }

                    return view('fe.thank');
                } else {
                    Order::where('payment_code', $orderId)->delete();
                    Transaction::where('payment_code', $orderId)->delete();
                    DB::commit();

                    return redirect()->route('feature-user.checkout');
                }
            }
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::debug($th->getMessage());
        }
    }
}
