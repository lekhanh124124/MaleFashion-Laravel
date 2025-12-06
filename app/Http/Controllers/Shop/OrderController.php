<?php

namespace App\Http\Controllers\Shop;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use App\Models\Order;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\OrderItem;
use Carbon\Carbon;

class OrderController extends BaseController
{
    // ... Giữ nguyên hàm checkout ...
    public function checkout()
    {
        $cartItems = $this->getCartItems();
        if (count($cartItems) == 0) {
            return redirect()->route('shopping.cart')->with('error', 'Giỏ hàng trống!');
        }

        $total = array_sum(array_column($cartItems, 'total'));

        $discount = 0;
        if (Session::has('coupon')) {
            $coupon = Session::get('coupon');
            if ($coupon['discount_type'] == 'PERCENTAGE') {
                $discount = $total * ($coupon['discount_value'] / 100);
            } else {
                $discount = $coupon['discount_value'];
            }
        }

        return view('pages.checkout', compact('cartItems', 'total', 'discount'));
    }

    public function placeOrder(Request $request)
    {
        $request->validate([
            'firstname' => 'required',
            'lastname' => 'required',
            'country' => 'required',
            'address' => 'required',
            'city' => 'required',
            'phone' => 'required',
            'email' => 'required|email',
        ]);

        if ($request->payment_method == 'MOMO') {
            $request->validate([
                'momo_number' => 'required|numeric'
            ], ['momo_number.required' => 'Vui lòng nhập số điện thoại ví Momo']);
        }

        if ($request->payment_method == 'MOMO') {
            return $this->processMomoPayment($request);
        }

        return $this->processRegularPayment($request);
    }

    private function processRegularPayment($request)
    {
        DB::beginTransaction();
        try {
            $cartItems = $this->getCartItems();
            if (empty($cartItems)) throw new \Exception("Giỏ hàng trống!");

            $total = array_sum(array_column($cartItems, 'total'));
            $discountAmount = 0;
            $couponId = null;
            if (Session::has('coupon')) {
                $coupon = Session::get('coupon');
                $couponId = $this->getCouponIdByCode($coupon['coupon_code']);
                if ($coupon['discount_type'] == 'PERCENTAGE') {
                    $discountAmount = $total * ($coupon['discount_value'] / 100);
                } else {
                    $discountAmount = $coupon['discount_value'];
                }
            }

            $order = Order::create([
                'USER_ID' => Auth::check() ? Auth::user()->USER_ID : null,
                'COUPON_ID' => $couponId,
                'ORDER_DATE' => Carbon::now(),
                'STATUS' => 'Pending',
                'PAYMENT_METHOD' => $request->payment_method,
                'SHIPPING_ADDRESS' => $request->address . ', ' . $request->city . ', ' . $request->country,
                'BILLING_NAME' => $request->firstname . ' ' . $request->lastname,
                'BILLING_PHONE' => $request->phone,
                'BILLING_EMAIL' => $request->email,
                'ORDER_NOTES' => $request->order_notes,
                'DISCOUNT_AMOUNT' => $discountAmount,
            ]);

            foreach ($cartItems as $item) {
                OrderItem::create([
                    'ORDER_ID'   => $order->ORDER_ID,
                    'VARIANT_ID' => $item['variant_id'],
                    'QUANTITY'   => $item['quantity'],
                    'UNIT_PRICE' => $item['price']
                ]);
            }

            $this->clearCart();
            Session::forget('coupon');

            DB::commit();
            return redirect()->route('shop.index')->with('success', 'Đặt hàng thành công!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Lỗi: ' . $e->getMessage());
        }
    }

    // --- LOGIC MOMO TEST (SANDBOX) ---
    private function processMomoPayment(Request $request)
    {
        DB::beginTransaction();
        try {
            $cartItems = $this->getCartItems();
            if (empty($cartItems)) throw new \Exception("Giỏ hàng trống!");

            $totalRaw = array_sum(array_column($cartItems, 'total'));
            $discountAmount = 0;
            $couponId = null;
            if (Session::has('coupon')) {
                $coupon = Session::get('coupon');
                $couponId = $this->getCouponIdByCode($coupon['coupon_code']);
                if ($coupon['discount_type'] == 'PERCENTAGE') {
                    $discountAmount = $totalRaw * ($coupon['discount_value'] / 100);
                } else {
                    $discountAmount = $coupon['discount_value'];
                }
            }
            $amount = (int)($totalRaw - $discountAmount);

            // Lưu đơn hàng trước (Unpaid)
            $order = Order::create([
                'USER_ID' => Auth::check() ? Auth::user()->USER_ID : null,
                'COUPON_ID' => $couponId,
                'ORDER_DATE' => Carbon::now(),
                'STATUS' => 'Unpaid',
                'PAYMENT_METHOD' => 'MOMO',
                'SHIPPING_ADDRESS' => $request->address . ', ' . $request->city . ', ' . $request->country,
                'BILLING_NAME' => $request->firstname . ' ' . $request->lastname,
                'BILLING_PHONE' => $request->phone,
                'BILLING_EMAIL' => $request->email,
                'ORDER_NOTES' => $request->order_notes . " (Momo SĐT: " . $request->momo_number . ")",
                'DISCOUNT_AMOUNT' => $discountAmount,
            ]);

            foreach ($cartItems as $item) {
                OrderItem::create([
                    'ORDER_ID' => $order->ORDER_ID,
                    'VARIANT_ID' => $item['variant_id'],
                    'QUANTITY' => $item['quantity'],
                    'UNIT_PRICE' => $item['price']
                ]);
            }

            // 3. CẤU HÌNH API MOMO TEST
            $endpoint = "https://test-payment.momo.vn/v2/gateway/api/create";

            // Thông tin tài khoản Test mặc định
            $partnerCode = "MOMOBKUN20180529";
            $accessKey = "klm05TvNBzhg7h7j";
            $secretKey = "at67qH6mk8w5Y1nAyMoYKMWACiEi2bsa";

            // Mã đơn hàng: Kết hợp ID + Time + Random để không trùng trên hệ thống Test chung
            $orderId = $order->ORDER_ID . "_" . time() . "_" . rand(1000, 9999);
            $requestId = $orderId;
            $orderInfo = "Thanh toan don hang #" . $order->ORDER_ID;

            $redirectUrl = route('shop.momo.return');
            $ipnUrl = route('shop.momo.return');
            $extraData = "";

            $rawHash = "accessKey=" . $accessKey .
                "&amount=" . $amount .
                "&extraData=" . $extraData .
                "&ipnUrl=" . $ipnUrl .
                "&orderId=" . $orderId .
                "&orderInfo=" . $orderInfo .
                "&partnerCode=" . $partnerCode .
                "&redirectUrl=" . $redirectUrl .
                "&requestId=" . $requestId .
                "&requestType=captureWallet";

            $signature = hash_hmac("sha256", $rawHash, $secretKey);

            $data = [
                'partnerCode' => $partnerCode,
                'partnerName' => "Test MaleFashion",
                'storeId' => "MomoTestStore",
                'requestId' => $requestId,
                'amount' => $amount,
                'orderId' => $orderId,
                'orderInfo' => $orderInfo,
                'redirectUrl' => $redirectUrl,
                'ipnUrl' => $ipnUrl,
                'lang' => 'vi',
                'extraData' => $extraData,
                'requestType' => 'captureWallet',
                'signature' => $signature
            ];

            // 4. Gọi API
            $result = $this->execPostRequest($endpoint, json_encode($data));
            $jsonResult = json_decode($result, true);

            if (isset($jsonResult['payUrl'])) {
                $this->clearCart();
                Session::forget('coupon');
                DB::commit();
                return redirect()->to($jsonResult['payUrl']);
            } else {
                DB::rollBack();
                return redirect()->back()->with('error', 'Lỗi Momo Test: ' . ($jsonResult['message'] ?? 'Unknown error'));
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Lỗi hệ thống: ' . $e->getMessage());
        }
    }

    public function momoReturn(Request $request)
    {
        if ($request->resultCode == 0) {
            $parts = explode('_', $request->orderId);
            $realOrderId = $parts[0];

            $order = Order::find($realOrderId);
            if ($order) {
                $order->STATUS = 'Processing';
                $order->save();
            }

            return redirect()->route('shop.index')->with('success', 'Thanh toán Momo thành công! Đơn hàng đang được xử lý.');
        } else {
            return redirect()->route('shopping.cart')->with('error', 'Thanh toán thất bại. ' . $request->message);
        }
    }

    private function execPostRequest($url, $data)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt(
            $ch,
            CURLOPT_HTTPHEADER,
            [
                'Content-Type: application/json',
                'Content-Length: ' . strlen($data)
            ]
        );
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
        $result = curl_exec($ch);
        return $result;
    }

    // --- CÁC HÀM PHỤ TRỢ GIỮ NGUYÊN ---
    private function getCartItems()
    {
        $items = [];
        if (Auth::check()) {
            $userId = Auth::user()->USER_ID;
            $cart = Cart::where('USER_ID', $userId)->first();
            if ($cart) {
                $dbItems = CartItem::with(['variant.product'])->where('CART_ID', $cart->CART_ID)->get();
                foreach ($dbItems as $item) {
                    $items[] = [
                        'variant_id' => $item->VARIANT_ID,
                        'name' => $item->variant->product->PRODUCT_NAME ?? 'Sản phẩm',
                        'price' => $item->UNIT_PRICE,
                        'quantity' => $item->QUANTITY,
                        'total' => $item->UNIT_PRICE * $item->QUANTITY
                    ];
                }
            }
        } else {
            $sessionCart = session()->get('cart', []);
            foreach ($sessionCart as $item) {
                $items[] = [
                    'variant_id' => $item['variant_id'],
                    'name' => $item['name'],
                    'price' => $item['price'],
                    'quantity' => $item['quantity'],
                    'total' => $item['price'] * $item['quantity']
                ];
            }
        }
        return $items;
    }

    private function clearCart()
    {
        if (Auth::check()) {
            $cart = Cart::where('USER_ID', Auth::user()->USER_ID)->first();
            if ($cart) CartItem::where('CART_ID', $cart->CART_ID)->delete();
        } else {
            Session::forget('cart');
        }
    }

    private function getCouponIdByCode($code)
    {
        $c = \App\Models\Coupon::where('COUPON_CODE', $code)->first();
        return $c ? $c->COUPON_ID : null;
    }
}
