<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use App\Models\Coupon;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;

class CouponController extends BaseController
{
    public function index(Request $request)
    {
        $query = Coupon::query();

        if ($request->has('search') && $request->search != '') {
            $query->where('COUPON_CODE', 'like', '%' . $request->search . '%');
        }

        if ($request->has('status') && $request->status != '') {
            $query->where('IS_ACTIVE', $request->status);
        }

        $coupons = $query->orderBy('COUPON_ID', 'desc')->paginate(10);

        // ĐÂY LÀ VIEW: trỏ đến thư mục resources/views/admin/pages/coupons.blade.php
        return view('admin.pages.coupons', compact('coupons'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'COUPON_CODE' => 'required|unique:COUPONS,COUPON_CODE',
            'DISCOUNT_VALUE' => 'required|numeric',
            'EXPIRY_DATE' => 'required|date',
        ]);

        Coupon::create($request->all());

        // --- SỬA LỖI TẠI ĐÂY ---
        // SAI: route('admin.pages.coupons') -> Lỗi "Route not defined"
        // ĐÚNG: route('admin.coupons.index') -> Theo định nghĩa trong routes/web.php
        return redirect()->route('admin.coupons.index')->with('success', 'Thêm thành công!');
    }

    public function update(Request $request, $id)
    {
        $coupon = Coupon::findOrFail($id);
        
        $request->validate([
            'COUPON_CODE' => 'required|unique:COUPONS,COUPON_CODE,'.$id.',COUPON_ID',
            'DISCOUNT_VALUE' => 'required|numeric',
            'EXPIRY_DATE' => 'required|date',
        ]);

        $coupon->update($request->all());

        // --- SỬA LỖI TẠI ĐÂY ---
        return redirect()->route('admin.coupons.index')->with('success', 'Cập nhật thành công!');
    }

    public function destroy($id)
    {
        $coupon = Coupon::findOrFail($id);
        $coupon->delete();

        // --- SỬA LỖI TẠI ĐÂY ---
        return redirect()->route('admin.coupons.index')->with('success', 'Đã xóa thành công!');
    }
    
    public function toggleStatus($id)
    {
        $coupon = Coupon::findOrFail($id);
        $coupon->IS_ACTIVE = !$coupon->IS_ACTIVE;
        $coupon->save();
        return redirect()->back()->with('success', 'Đã đổi trạng thái!');
    }
}