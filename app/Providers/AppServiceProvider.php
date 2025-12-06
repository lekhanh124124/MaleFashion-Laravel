<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View; // Thêm dòng này
use Illuminate\Support\Facades\Auth; // Thêm dòng này
use App\Models\Cart;      // Thêm dòng này
use App\Models\CartItem;  // Thêm dòng này

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        \Illuminate\Pagination\Paginator::useBootstrap();

        // --- TÍNH TOÁN GIỎ HÀNG CHO TOÀN BỘ WEBSITE ---
        View::composer('*', function ($view) {
            $globalTotal = 0;
            $globalCount = 0;

            if (Auth::check()) {
                // 1. Đã đăng nhập: Lấy từ DB
                $cart = Cart::where('USER_ID', Auth::user()->USER_ID)->first();
                if ($cart) {
                    $items = CartItem::where('CART_ID', $cart->CART_ID)->get();
                    foreach ($items as $item) {
                        $globalTotal += $item->QUANTITY * $item->UNIT_PRICE;
                        $globalCount += $item->QUANTITY; // Đếm tổng số lượng sản phẩm
                    }
                }
            } else {
                // 2. Khách vãng lai: Lấy từ Session
                $sessionCart = session()->get('cart', []);
                foreach ($sessionCart as $item) {
                    $globalTotal += $item['quantity'] * $item['price'];
                    $globalCount += $item['quantity'];
                }
            }

            // Chia sẻ biến $globalTotal và $globalCount cho tất cả các View
            $view->with('globalCartTotal', $globalTotal);
            $view->with('globalCartCount', $globalCount);
        });
    }
}