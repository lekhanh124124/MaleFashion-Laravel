<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use Cloudinary\Configuration\Configuration;
use Cloudinary\Api\Upload\UploadApi;

class ProductController extends BaseController
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

    public function index(Request $request)
    {
        // ... code giữ nguyên ...
        $categories = Category::all();
        $brands = Brand::all();
        $query = Product::with(['category', 'brand', 'variants']);
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where('PRODUCT_NAME', 'like', "%{$search}%")
                  ->orWhere('SHORT_DESCRIPTION', 'like', "%{$search}%");
        }
        $products = $query->orderBy('PRODUCT_ID', 'desc')->paginate(10);
        return view('admin.pages.products', compact('products', 'categories', 'brands'));
    }

    public function store(Request $request)
    {
        // ... code giữ nguyên ...
        $request->validate([
            'PRODUCT_NAME' => 'required|string|max:255',
            // ...
        ]);
        $data = $request->all();
        $data['IS_NEW_ARRIVAL'] = $request->has('IS_NEW_ARRIVAL') ? 1 : 0;
        $data['IS_HOT_SALE'] = $request->has('IS_HOT_SALE') ? 1 : 0;
        $data['IS_BEST_SELLER'] = $request->has('IS_BEST_SELLER') ? 1 : 0;
        $data['RATING'] = 0; 
        $data['CREATED_AT'] = now();
        Product::create($data);
        return redirect()->route('admin.products.index')->with('success', 'Thêm sản phẩm thành công!');
    }

    public function update(Request $request, $id)
    {
        // ... code giữ nguyên ...
        $product = Product::findOrFail($id);
        $request->validate([
            'PRODUCT_NAME' => 'required|string|max:255',
            // ...
        ]);
        $data = $request->all();
        $data['IS_NEW_ARRIVAL'] = $request->has('IS_NEW_ARRIVAL') ? 1 : 0;
        $data['IS_HOT_SALE'] = $request->has('IS_HOT_SALE') ? 1 : 0;
        $data['IS_BEST_SELLER'] = $request->has('IS_BEST_SELLER') ? 1 : 0;
        $product->update($data);
        return redirect()->route('admin.products.index')->with('success', 'Cập nhật sản phẩm thành công!');
    }

    // Xóa sản phẩm -> Xóa tất cả ảnh con trên Cloudinary
    public function destroy($id)
    {
        $product = Product::with('images')->findOrFail($id);
        
        if ($product->images) {
            foreach ($product->images as $image) {
                // Xóa từng ảnh trên cloud
                $this->deleteImageFromCloudinary($image->IMAGE_URL);
            }
        }

        $product->delete();

        return redirect()->route('admin.products.index')->with('success', 'Đã xóa sản phẩm và dọn dẹp ảnh trên Cloud!');
    }

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
}