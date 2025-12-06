<?php

namespace App\Http\Controllers\Shop;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Tag;
use App\Models\Color;
use App\Models\Size;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Support\Facades\Auth;
use App\Models\Wishlist;

class ShopController extends BaseController
{
    public function index(Request $request)
    {
        // 1. Sidebar Data
        $categories = Category::whereNull('PARENT_ID')->with('children')->get();
        $brands = Brand::withCount('products')->get();
        $tags = Tag::all();
        $colors = Color::all();
        $sizes = Size::all();

        // 2. Query Builder
        $query = Product::with(['thumbnail', 'variants.color']);

        // --- A. TÌM KIẾM ---
        if ($request->filled('search')) {
            $query->where('PRODUCT_NAME', 'like', '%' . $request->search . '%');
        }

        // --- B. CATEGORY ---
        if ($request->has('category')) {
            $catId = $request->get('category');
            $category = Category::find($catId);
            if ($category) {
                $catIds = $category->children()->pluck('CATEGORY_ID')->push($category->CATEGORY_ID);
                $query->whereIn('CATEGORY_ID', $catIds);
            }
        }

        // --- C. BRAND (SỬA: Hỗ trợ chọn nhiều) ---
        if ($request->has('brand')) {
            // Ép kiểu về array để đảm bảo luôn là mảng, kể cả khi chỉ có 1 giá trị
            $brandIds = (array) $request->get('brand'); 
            $query->whereIn('BRAND_ID', $brandIds);
        }

        // --- D. TAGS (SỬA: Hỗ trợ chọn nhiều) ---
        if ($request->has('tag')) {
            $tagIds = (array) $request->get('tag');
            $query->whereHas('tags', function ($q) use ($tagIds) {
                $q->whereIn('TAGS.TAG_ID', $tagIds);
            });
        }

        // --- E. SIZE (SỬA: Hỗ trợ chọn nhiều) ---
        if ($request->has('size')) {
            $sizeIds = (array) $request->get('size');
            $query->whereHas('variants', function ($q) use ($sizeIds) {
                $q->whereIn('SIZE_ID', $sizeIds)->where('STOCK', '>', 0);
            });
        }

        // --- F. COLOR (Giữ nguyên hoặc sửa nếu muốn chọn nhiều màu - Ở đây tôi giữ nguyên logic cũ là chọn 1, nếu muốn nhiều thì làm giống Size) ---
        if ($request->has('color')) {
            $colorId = $request->get('color');
            $query->whereHas('variants', function ($q) use ($colorId) {
                $q->where('COLOR_ID', $colorId)->where('STOCK', '>', 0);
            });
        }

        // --- G. PRICE SLIDER ---
        $min_price = $request->input('min_price', 0);
        $max_price = $request->input('max_price', 99999999);
        $query->whereHas('variants', function ($q) use ($min_price, $max_price) {
            $q->whereBetween('PRICE', [$min_price, $max_price]);
        });

        // --- H. SORT ---
        if ($request->has('sort')) {
            $sort = $request->get('sort');
            $query->addSelect([
                'price_sorting' => ProductVariant::select('PRICE')
                    ->whereColumn('PRODUCT_ID', 'PRODUCTS.PRODUCT_ID')
                    ->limit(1)
            ]);
            $query->orderBy('price_sorting', $sort == 'asc' ? 'asc' : 'desc');
        } else {
            $query->orderBy('CREATED_AT', 'desc');
        }

        // --- LẤY DANH SÁCH LIKE ---
        $likedProductIds = [];
        if (Auth::check()) {
            $likedProductIds = Wishlist::where('USER_ID', Auth::user()->USER_ID)
                ->pluck('PRODUCT_ID')
                ->toArray();
        }

        $products = $query->paginate(12);
        // Quan trọng: append query string để khi phân trang không bị mất filter
        $products->appends($request->all());

        return view('pages.shop', compact(
            'categories', 'brands', 'tags', 'colors', 'sizes', 'products', 'likedProductIds'
        ));
    }
}