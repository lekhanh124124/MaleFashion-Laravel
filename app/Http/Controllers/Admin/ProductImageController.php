<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Support\Str;
use Cloudinary\Configuration\Configuration;
use Cloudinary\Api\Upload\UploadApi;

class ProductImageController extends BaseController
{
    // Cấu hình
    public function __construct()
    {
        Configuration::instance([
            'cloud' => [
                'cloud_name' => env('CLOUDINARY_CLOUD_NAME'),
                'api_key'    => env('CLOUDINARY_API_KEY'),
                'api_secret' => env('CLOUDINARY_API_SECRET')
            ],
            'url' => ['secure' => true]
        ]);
    }

    public function index($productId)
    {
        $images = ProductImage::where('PRODUCT_ID', $productId)->get();
        return response()->json($images);
    }

    public function store(Request $request, $productId)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'is_thumbnail' => 'boolean'
        ]);

        $product = Product::findOrFail($productId);
        
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $count = ProductImage::where('PRODUCT_ID', $productId)->count() + 1;
            
            // Public ID: malefashion/products/ten-sp-stt-time
            $publicId = 'malefashion/products/' . Str::slug($product->PRODUCT_NAME) . '-' . $count . '-' . time();

            // Upload Native
            $upload = (new UploadApi())->upload($file->getRealPath(), [
                'public_id' => $publicId
            ]);

            $dbPath = $upload['secure_url'];

            $isThumb = $request->is_thumbnail ? 1 : 0;
            if ($isThumb) {
                ProductImage::where('PRODUCT_ID', $productId)->update(['IS_THUMBNAIL' => 0]);
            }

            ProductImage::create([
                'PRODUCT_ID' => $productId,
                'IMAGE_URL' => $dbPath,
                'IS_THUMBNAIL' => $isThumb
            ]);

            return response()->json(['success' => true, 'message' => 'Upload thành công!']);
        }

        return response()->json(['success' => false, 'message' => 'Lỗi file'], 400);
    }

    public function destroy($imageId)
    {
        $image = ProductImage::findOrFail($imageId);
        
        // Xóa trên Cloud
        $this->deleteImageFromCloudinary($image->IMAGE_URL);

        $image->delete();
        return response()->json(['success' => true]);
    }
    
    // Hàm phụ trợ xóa ảnh
    private function deleteImageFromCloudinary($url)
    {
        if (!$url) return;
        try {
            if (preg_match('/\/v\d+\/(.+)\.[a-zA-Z]+$/', $url, $matches)) {
                $publicId = $matches[1];
                (new UploadApi())->destroy($publicId);
            }
        } catch (\Exception $e) {}
    }

    public function setThumbnail($imageId) { /* ... giữ nguyên ... */ }
}