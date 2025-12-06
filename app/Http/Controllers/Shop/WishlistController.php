<?php

namespace App\Http\Controllers\Shop;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Wishlist;

class WishlistController extends BaseController
{
    // 1. Hiển thị trang Wishlist (MỚI)
    public function index()
    {
        // Kiểm tra đăng nhập
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Vui lòng đăng nhập để xem danh sách yêu thích.');
        }

        $userId = Auth::user()->USER_ID;

        // Lấy danh sách wishlist của user hiện tại
        // Eager load: product (thông tin sp), product.thumbnail (ảnh), product.variants (để lấy giá)
        $wishlistItems = Wishlist::with(['product.thumbnail', 'product.variants'])
                                ->where('USER_ID', $userId)
                                ->orderBy('WISHLIST_ID', 'desc')
                                ->get();

        return view('pages.wishlist', compact('wishlistItems'));
    }

    // 2. Toggle (Thêm/Xóa) - Giữ nguyên logic cũ
    public function toggle($productId)
    {
        if (!Auth::check()) {
            return response()->json(['status' => 'login_required', 'message' => 'Vui lòng đăng nhập để thực hiện chức năng này!']);
        }

        $userId = Auth::user()->USER_ID;

        $wishlist = Wishlist::where('USER_ID', $userId)
                            ->where('PRODUCT_ID', $productId)
                            ->first();

        if ($wishlist) {
            $wishlist->delete();
            return response()->json(['status' => 'removed', 'message' => 'Đã xóa khỏi danh sách yêu thích']);
        } else {
            Wishlist::create([
                'USER_ID' => $userId,
                'PRODUCT_ID' => $productId
            ]);
            return response()->json(['status' => 'added', 'message' => 'Đã thêm vào danh sách yêu thích']);
        }
    }
    
    // 3. Xóa (Dành cho nút Xóa trong trang Wishlist) - MỚI
    public function remove($id)
    {
        if (!Auth::check()) return redirect()->route('login');

        // Tìm theo ID của dòng trong bảng WISHLIST (không phải Product ID)
        // Hoặc tìm theo ProductID + UserID để an toàn hơn
        $item = Wishlist::where('USER_ID', Auth::user()->USER_ID)
                        ->where('PRODUCT_ID', $id)
                        ->first();
        
        if ($item) {
            $item->delete();
            return back()->with('success', 'Đã xóa sản phẩm khỏi wishlist.');
        }
        
        return back()->with('error', 'Không tìm thấy sản phẩm.');
    }
    
    // 4. Xóa tất cả - MỚI
    public function clear()
    {
        if (!Auth::check()) return redirect()->route('login');
        
        Wishlist::where('USER_ID', Auth::user()->USER_ID)->delete();
        
        return back()->with('success', 'Đã xóa toàn bộ danh sách yêu thích.');
    }
}