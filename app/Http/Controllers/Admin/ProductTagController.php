<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Tag;

class ProductTagController extends BaseController
{
    public function index($productId)
    {
        // Lấy sản phẩm và các tag đã gán
        $product = Product::with('tags')->findOrFail($productId);
        
        // Lấy tất cả các tag có trong hệ thống để hiển thị list
        $allTags = Tag::all();

        // Lấy mảng các TAG_ID mà sản phẩm này đang sở hữu
        $currentTagIds = $product->tags->pluck('TAG_ID')->toArray();

        return response()->json([
            'all_tags' => $allTags,
            'current_tag_ids' => $currentTagIds
        ]);
    }

    public function update(Request $request, $productId)
    {
        $product = Product::findOrFail($productId);
        
        // Lấy mảng tag_ids từ request (nếu không chọn gì thì là mảng rỗng)
        $tagIds = $request->input('tags', []);
        
        // Sync: Tự động thêm mới tag chưa có và xóa tag không được chọn trong bảng trung gian
        $product->tags()->sync($tagIds);

        return response()->json(['success' => true, 'message' => 'Cập nhật tags thành công!']);
    }
}