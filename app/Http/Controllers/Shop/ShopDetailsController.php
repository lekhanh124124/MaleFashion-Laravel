<?php

namespace App\Http\Controllers\Shop;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use App\Models\Product;

class ShopDetailsController extends BaseController
{
    public function show($id)
    {
        // 1. Lấy thông tin sản phẩm và eager load các quan hệ
        $product = Product::with([
            'category', 
            'brand', 
            'images', 
            'tags', 
            'variants.size', 
            'variants.color',
            'reviews.user'
        ])->findOrFail($id);

        // 2. Chuẩn bị dữ liệu Variant Map cho JavaScript
        // Key sẽ là "SIZE_ID-COLOR_ID" -> Value là object chứa thông tin
        $variantsMap = [];
        foreach ($product->variants as $variant) {
            $key = $variant->SIZE_ID . '-' . $variant->COLOR_ID;
            $variantsMap[$key] = [
                'id' => $variant->VARIANT_ID,
                'price' => number_format($variant->PRICE, 0, ',', '.') . ' VNĐ',
                'sku' => $variant->SKU,
                'stock' => $variant->STOCK
            ];
        }

        // 3. Lấy danh sách Size và Color duy nhất để hiển thị nút chọn
        $uniqueSizes = $product->variants->pluck('size')->unique('SIZE_ID')->sortBy('SIZE_ID');
        $uniqueColors = $product->variants->pluck('color')->unique('COLOR_ID')->sortBy('COLOR_ID');

        // 4. Biến thể mặc định (để hiển thị giá ban đầu)
        $defaultVariant = $product->variants->first();

        // 5. Sản phẩm liên quan (Cùng Category, khác ID hiện tại)
        $relatedProducts = Product::where('CATEGORY_ID', $product->CATEGORY_ID)
            ->where('PRODUCT_ID', '!=', $id)
            ->with('thumbnail')
            ->limit(4)
            ->get();

        // 6. Reviews (Lấy tất cả review của sp)
        $reviews = $product->reviews;

        return view('pages.shop-details', compact(
            'product', 
            'variantsMap', // Truyền biến này sang View để JS dùng
            'uniqueSizes', 
            'uniqueColors', 
            'defaultVariant',
            'relatedProducts',
            'reviews'
        ));
    }
}