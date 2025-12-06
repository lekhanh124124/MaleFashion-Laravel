<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use App\Models\Banner;
use App\Models\Category;
use Illuminate\Support\Str;
use Cloudinary\Configuration\Configuration;
use Cloudinary\Api\Upload\UploadApi;

class BannerController extends BaseController
{
    // 1. Cấu hình thủ công ngay khi khởi tạo Controller
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

    public function index()
    {
        $banners = Banner::with('category')->orderBy('DISPLAY_ORDER', 'asc')->get();
        $categories = Category::all();
        return view('admin.pages.banners', compact('banners', 'categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'TITLE' => 'required|string|max:255',
            'IMAGE_URL' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'POSITION' => 'required|string',
            // ... validate khác
        ]);

        $data = $request->all();
        $data['IS_ACTIVE'] = $request->has('IS_ACTIVE') ? 1 : 0;
        $data['DISPLAY_ORDER'] = $request->DISPLAY_ORDER ?? 0;

        // --- UPLOAD ---
        if ($request->hasFile('IMAGE_URL')) {
            $file = $request->file('IMAGE_URL');
            
            // Đặt tên Public ID: folder/slug-time
            $publicId = 'malefashion/banners/' . Str::slug($request->TITLE) . '-' . time();

            // Upload trực tiếp bằng SDK
            $upload = (new UploadApi())->upload($file->getRealPath(), [
                'public_id' => $publicId,
                'overwrite' => true
            ]);

            // Lấy link secure_url
            $data['IMAGE_URL'] = $upload['secure_url'];
        }

        Banner::create($data);

        return redirect()->route('admin.banners.index')->with('success', 'Thêm banner thành công!');
    }

    public function update(Request $request, $id)
    {
        $banner = Banner::findOrFail($id);
        // ... validate ...

        $data = $request->all();
        $data['IS_ACTIVE'] = $request->has('IS_ACTIVE') ? 1 : 0;

        if ($request->hasFile('IMAGE_URL')) {
            // 1. Xóa ảnh cũ trên Cloudinary
            $this->deleteImageFromCloudinary($banner->IMAGE_URL);

            // 2. Upload ảnh mới
            $file = $request->file('IMAGE_URL');
            $publicId = 'malefashion/banners/' . Str::slug($request->TITLE) . '-' . time();

            $upload = (new UploadApi())->upload($file->getRealPath(), [
                'public_id' => $publicId
            ]);
            
            $data['IMAGE_URL'] = $upload['secure_url'];
        } else {
            unset($data['IMAGE_URL']);
        }

        $banner->update($data);

        return redirect()->route('admin.banners.index')->with('success', 'Cập nhật banner thành công!');
    }

    public function destroy($id)
    {
        $banner = Banner::findOrFail($id);
        
        // Xóa ảnh trên Cloudinary trước khi xóa DB
        $this->deleteImageFromCloudinary($banner->IMAGE_URL);

        $banner->delete();
        return redirect()->route('admin.banners.index')->with('success', 'Đã xóa banner và ảnh trên cloud!');
    }
    
    // Hàm phụ trợ xóa ảnh dùng Native SDK
    private function deleteImageFromCloudinary($url)
    {
        if (!$url) return;
        try {
            // Regex lấy Public ID từ URL
            if (preg_match('/\/v\d+\/(.+)\.[a-zA-Z]+$/', $url, $matches)) {
                $publicId = $matches[1]; 
                // Gọi API xóa
                (new UploadApi())->destroy($publicId);
            }
        } catch (\Exception $e) {
            // Log lỗi nếu cần
        }
    }
    
    public function toggleStatus($id) { /* ... giữ nguyên ... */ }
}