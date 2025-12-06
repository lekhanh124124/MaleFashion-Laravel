<?php

namespace App\Http\Controllers\Shop;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;
use App\Models\Wishlist;
use App\Models\Banner; // <--- Import Model Banner

class HomeController extends BaseController
{
    public function index()
    {
        // --- 1. Xử lý Banners ---
        $allBanners = Banner::where('IS_ACTIVE', 1)
            ->orderBy('DISPLAY_ORDER', 'asc')
            ->get();

        // Tách ra 2 bộ sưu tập riêng biệt
        $heroSliders = $allBanners->where('POSITION', 'hero_slider');
        
        // Dùng values() để reset lại key mảng (0, 1, 2) cho dễ gọi trong View
        $promoBanners = $allBanners->where('POSITION', 'promo_banner')->values();


        // --- 2. Xử lý Sản phẩm (Code cũ giữ nguyên) ---
        $products = Product::with(['thumbnail', 'variants.color'])
            ->where(function ($query) {
                $query->where('IS_NEW_ARRIVAL', 1)
                      ->orWhere('IS_HOT_SALE', 1)
                      ->orWhere('IS_BEST_SELLER', 1);
            })
            // ->where('STATUS', 'active') // Bỏ dòng này nếu DB không có cột STATUS
            ->orderBy('CREATED_AT', 'desc')
            ->limit(8)
            ->get();

        // --- 3. Xử lý Wishlist (Code cũ giữ nguyên) ---
        $likedProductIds = [];
        if (Auth::check()) {
            $likedProductIds = Wishlist::where('USER_ID', Auth::id())
                ->pluck('PRODUCT_ID')
                ->toArray();
        }

        return view('pages.index', compact('products', 'likedProductIds', 'heroSliders', 'promoBanners'));
    }
}