<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Order;

class RevenueController extends BaseController
{
    public function index(Request $request)
    {
        // 1. Xử lý lọc ngày (Mặc định là tháng hiện tại)
        $startDate = $request->start_date ? Carbon::parse($request->start_date) : Carbon::now()->startOfMonth();
        $endDate = $request->end_date ? Carbon::parse($request->end_date) : Carbon::now()->endOfMonth();

        // 2. Lấy dữ liệu đơn hàng trong khoảng thời gian
        $orders = Order::with('items')
            ->whereBetween('ORDER_DATE', [$startDate->format('Y-m-d 00:00:00'), $endDate->format('Y-m-d 23:59:59')])
            ->get();

        // 3. Tính toán KPI tổng quan
        $totalOrders = $orders->count();
        $cancelledOrders = $orders->where('STATUS', 'Cancelled')->count();
        
        // Tính doanh thu (Chỉ tính đơn KHÔNG bị hủy)
        // Công thức: Tổng (Số lượng * Đơn giá) - Giảm giá đơn hàng
        $validOrders = $orders->where('STATUS', '!=', 'Cancelled');
        $totalRevenue = 0;

        foreach ($validOrders as $order) {
            $orderTotal = $order->items->sum(function($item) {
                return $item->QUANTITY * $item->UNIT_PRICE;
            });
            // Trừ đi giảm giá (nếu có)
            $orderTotal -= $order->DISCOUNT_AMOUNT;
            $totalRevenue += max($orderTotal, 0); // Đảm bảo không âm
        }

        $avgOrderValue = $validOrders->count() > 0 ? $totalRevenue / $validOrders->count() : 0;
        $cancellationRate = $totalOrders > 0 ? ($cancelledOrders / $totalOrders) * 100 : 0;

        // 4. Dữ liệu biểu đồ (Doanh thu theo ngày)
        // Group valid orders by Date
        $dailyRevenue = [];
        $chartLabels = [];
        $chartData = [];

        // Tạo mảng ngày đầy đủ từ start đến end (để biểu đồ không bị đứt quãng ngày ko có đơn)
        $period = \Carbon\CarbonPeriod::create($startDate, $endDate);
        foreach ($period as $date) {
            $dateStr = $date->format('Y-m-d');
            $dailyRevenue[$dateStr] = 0;
            $chartLabels[] = $date->format('d/m');
        }

        foreach ($validOrders as $order) {
            $dateKey = Carbon::parse($order->ORDER_DATE)->format('Y-m-d');
            
            $orderSubtotal = $order->items->sum(function($item) {
                return $item->QUANTITY * $item->UNIT_PRICE;
            });
            $finalTotal = max($orderSubtotal - $order->DISCOUNT_AMOUNT, 0);

            if (isset($dailyRevenue[$dateKey])) {
                $dailyRevenue[$dateKey] += $finalTotal;
            }
        }
        $chartData = array_values($dailyRevenue);

        // 5. Top Danh mục (Sử dụng Query Builder cho nhanh vì join nhiều bảng)
        // Logic: Item -> Variant -> Product -> Category
        $topCategories = DB::table('ORDER_ITEMS as oi')
            ->join('ORDERS as o', 'oi.ORDER_ID', '=', 'o.ORDER_ID')
            ->join('PRODUCT_VARIANTS as pv', 'oi.VARIANT_ID', '=', 'pv.VARIANT_ID')
            ->join('PRODUCTS as p', 'pv.PRODUCT_ID', '=', 'p.PRODUCT_ID')
            ->join('CATEGORIES as c', 'p.CATEGORY_ID', '=', 'c.CATEGORY_ID')
            ->where('o.STATUS', '!=', 'Cancelled')
            ->whereBetween('o.ORDER_DATE', [$startDate, $endDate])
            ->select('c.CATEGORY_NAME', DB::raw('SUM(oi.QUANTITY * oi.UNIT_PRICE) as total_revenue'))
            ->groupBy('c.CATEGORY_ID', 'c.CATEGORY_NAME')
            ->orderByDesc('total_revenue')
            ->limit(5)
            ->get();

        return view('admin.pages.revenue', compact(
            'totalRevenue', 
            'totalOrders', 
            'avgOrderValue', 
            'cancellationRate',
            'chartLabels',
            'chartData',
            'topCategories',
            'startDate',
            'endDate'
        ));
    }
}