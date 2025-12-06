<?php

namespace App\Http\Controllers\Shop;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\ProductReview;
use App\Models\Product;

class ProductReviewController extends BaseController
{
    public function store(Request $request)
    {
        // 1. Validate dữ liệu
        $request->validate([
            'product_id' => 'required|exists:PRODUCTS,PRODUCT_ID',
            'rating'     => 'required|integer|min:1|max:5',
            'comment'    => 'required|string|max:1000',
            // Nếu chưa đăng nhập thì bắt buộc nhập tên và email
            'review_name' => 'nullable|required_without:user_id|string|max:100',
            'review_email'=> 'nullable|required_without:user_id|email|max:100',
        ], [
            'rating.required' => 'Vui lòng chọn số sao đánh giá!',
            'comment.required' => 'Vui lòng nhập nội dung bình luận!',
            'review_name.required_without' => 'Vui lòng nhập tên của bạn!',
            'review_email.required_without' => 'Vui lòng nhập email của bạn!',
        ]);

        $userId = Auth::check() ? Auth::user()->USER_ID : null;

        // 2. Lưu Review
        // Nếu user đã đăng nhập thì lấy tên/email từ Auth, ngược lại lấy từ Form
        ProductReview::create([
            'PRODUCT_ID'   => $request->product_id,
            'USER_ID'      => $userId,
            'RATING'       => $request->rating,
            'COMMENT'      => $request->comment,
            'REVIEW_NAME'  => $userId ? Auth::user()->full_name : $request->review_name,
            'REVIEW_EMAIL' => $userId ? Auth::user()->EMAIL : $request->review_email,
            'CREATED_AT'   => now()
        ]);

        // 3. Cập nhật điểm RATING trung bình cho Sản phẩm
        $product = Product::find($request->product_id);
        if ($product) {
            // Tính trung bình cộng cột RATING trong bảng reviews
            $avgRating = $product->reviews()->avg('RATING');
            // Cập nhật vào bảng Products (làm tròn 1 chữ số thập phân)
            $product->RATING = round($avgRating, 1);
            $product->save();
        }

        return back()->with('success', 'Cảm ơn bạn đã đánh giá sản phẩm!');
    }
}