<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Size;
use App\Models\Color;
use Illuminate\Validation\Rule;

class ProductVariantController extends BaseController
{
    // Lấy dữ liệu cho Modal: Danh sách biến thể + List Sizes + List Colors
    public function index($productId)
    {
        $variants = ProductVariant::with(['size', 'color'])
            ->where('PRODUCT_ID', $productId)
            ->orderBy('VARIANT_ID', 'desc')
            ->get();

        $sizes = Size::all();
        $colors = Color::all();

        return response()->json([
            'variants' => $variants,
            'sizes' => $sizes,
            'colors' => $colors
        ]);
    }

    public function store(Request $request, $productId)
    {
        $request->validate([
            'SIZE_ID' => 'required|integer',
            'COLOR_ID' => 'required|integer',
            'SKU' => 'required|string|unique:PRODUCT_VARIANTS,SKU',
            'PRICE' => 'required|numeric|min:0',
            'STOCK' => 'required|integer|min:0',
        ]);

        $data = $request->all();
        $data['PRODUCT_ID'] = $productId;

        ProductVariant::create($data);

        return response()->json(['success' => true, 'message' => 'Thêm biến thể thành công!']);
    }

    public function update(Request $request, $variantId)
    {
        $variant = ProductVariant::findOrFail($variantId);

        $request->validate([
            'SIZE_ID' => 'required|integer',
            'COLOR_ID' => 'required|integer',
            // SKU unique trừ chính nó ra
            'SKU' => ['required', 'string', Rule::unique('PRODUCT_VARIANTS', 'SKU')->ignore($variantId, 'VARIANT_ID')],
            'PRICE' => 'required|numeric|min:0',
            'STOCK' => 'required|integer|min:0',
        ]);

        $variant->update($request->all());

        return response()->json(['success' => true, 'message' => 'Cập nhật thành công!']);
    }

    public function destroy($variantId)
    {
        ProductVariant::destroy($variantId);
        return response()->json(['success' => true]);
    }
}