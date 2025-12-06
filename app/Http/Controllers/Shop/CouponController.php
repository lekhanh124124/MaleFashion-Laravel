<?php

namespace App\Http\Controllers\Shop;

use Illuminate\Routing\Controller as BaseController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Coupon;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;

class CouponController extends BaseController
{
    // Xử lý áp dụng mã giảm giá
    public function applyCoupon(Request $request)
    {
        $request->validate([
            'coupon_code' => 'required|string|max:50'
        ]);

        // --- BƯỚC 1: KIỂM TRA GIỎ HÀNG CÓ THỰC SỰ TRỐNG KHÔNG? ---
        $hasItem = false;

        if (Auth::check()) {
            // LOGIC 1: Khách đã đăng nhập -> Kiểm tra trong Database
            $userId = Auth::user()->USER_ID;
            // Kiểm tra xem user có giỏ hàng và giỏ hàng có item nào không
            $hasItem = \App\Models\CartItem::whereHas('cart', function ($query) use ($userId) {
                $query->where('USER_ID', $userId);
            })->exists();
        } else {
            // LOGIC 2: Khách vãng lai -> Kiểm tra Session
            $sessionCart = session()->get('cart', []);
            if (!empty($sessionCart) && count($sessionCart) > 0) {
                $hasItem = true;
            }
        }

        // Nếu không có sản phẩm nào thì báo lỗi ngay
        if (!$hasItem) {
            return redirect()->back()->with('error', 'Giỏ hàng trống! Vui lòng thêm sản phẩm trước khi nhập mã.');
        }

        // --- BƯỚC 2: XỬ LÝ MÃ COUPON (Logic cũ) ---
        $code = $request->input('coupon_code');
        $coupon = Coupon::where('COUPON_CODE', $code)->first();

        if (!$coupon) {
            return redirect()->back()->with('error', 'Mã giảm giá không tồn tại!');
        }

        if ($coupon->IS_ACTIVE == 0) {
            return redirect()->back()->with('error', 'Mã giảm giá này đang tạm khóa!');
        }

        if (\Carbon\Carbon::now()->greaterThan(\Carbon\Carbon::parse($coupon->EXPIRY_DATE))) {
            return redirect()->back()->with('error', 'Mã giảm giá đã hết hạn sử dụng!');
        }

        // Lưu vào session
        $couponSession = [
            'coupon_code'    => $coupon->COUPON_CODE,
            'discount_type'  => $coupon->DISCOUNT_TYPE,
            'discount_value' => $coupon->DISCOUNT_VALUE,
        ];

        Session::put('coupon', $couponSession);
        Session::save();

        return redirect()->back()->with('success', 'Áp dụng mã giảm giá thành công!');
    }

    // Hàm xóa mã giảm giá (nếu khách muốn đổi mã khác)
    public function removeCoupon()
    {
        if (Session::has('coupon')) {
            Session::forget('coupon');
            return redirect()->back()->with('success', 'Đã gỡ bỏ mã giảm giá.');
        }

        return redirect()->back();
    }
}
