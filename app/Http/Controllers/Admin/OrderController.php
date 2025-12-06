<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use App\Models\Order;

class OrderController extends BaseController
{
    public function index(Request $request)
    {
        $query = Order::with(['user', 'coupon']);

        // Tìm kiếm theo ID, Email, SĐT
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('ORDER_ID', $search)
                    ->orWhere('BILLING_EMAIL', 'like', "%{$search}%")
                    ->orWhere('BILLING_PHONE', 'like', "%{$search}%");
            });
        }

        $orders = $query->orderBy('ORDER_DATE', 'desc')->paginate(10);

        return view('admin.pages.orders', compact('orders'));
    }

    // API lấy chi tiết đơn hàng (cho Modal Xem chi tiết & Modal Sửa)
    public function show($id)
    {
        // Eager load sâu để lấy thông tin sản phẩm từ Variant
        $order = Order::with([
            'user',
            'coupon',
            'items.variant.product.thumbnail', 
            'items.variant.product.brand',     
            'items.variant.size',
            'items.variant.color'
        ])->findOrFail($id);

        return response()->json($order);
    }

    public function update(Request $request, $id)
    {
        $order = Order::findOrFail($id);

        $request->validate([
            'STATUS' => 'required|string',
            'ORDER_NOTES' => 'nullable|string'
        ]);

        // Chỉ cập nhật Status và Notes, KHÔNG cập nhật Payment Method
        $order->update([
            'STATUS' => $request->STATUS,
            'ORDER_NOTES' => $request->ORDER_NOTES
        ]);

        return response()->json(['success' => true, 'message' => 'Cập nhật đơn hàng thành công!']);
    }

    public function destroy($id)
    {
        $order = Order::findOrFail($id);
        // Xóa các items trước (nếu DB chưa set cascade delete)
        $order->items()->delete();
        $order->delete();

        return redirect()->route('admin.orders.index')->with('success', 'Đã xóa đơn hàng!');
    }
}
