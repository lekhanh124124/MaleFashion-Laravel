@php
    // 1. Lấy Subtotal từ biến $total do Controller truyền sang
    // Nếu chưa có thì mặc định là 0
    $subtotal = isset($total) ? $total : 0;
    $discount = 0;

    // 2. Tính toán Discount nếu có Coupon trong Session
    if(Session::has('coupon')) {
        $coupon = Session::get('coupon');
        
        if($coupon['discount_type'] == 'PERCENTAGE') {
            // Tính theo %
            $discount = $subtotal * ($coupon['discount_value'] / 100);
        } else {
            // Tính theo số tiền cố định
            $discount = $coupon['discount_value'];
        }

        // Đảm bảo tiền giảm không vượt quá tổng tiền
        if($discount > $subtotal) {
            $discount = $subtotal;
        }
    }

    // 3. Tính Total cuối cùng
    $finalTotal = $subtotal - $discount;
@endphp

<div class="cart__total">
    <h6>Cart total</h6>
    <ul>
        <li>Subtotal <span>{{ number_format($subtotal) }}đ</span></li>
        
        <!-- Dòng Discount: Hiển thị 0đ nếu không có mã -->
        <li>
            Discount 
            @if(Session::has('coupon'))
                <br><small style="color: #e53637;">({{ Session::get('coupon')['coupon_code'] }})</small>
            @endif
            <span>- {{ number_format($discount) }}đ</span>
        </li>

        <li>Total <span>{{ number_format($finalTotal) }}đ</span></li>
    </ul>
    
    <!-- Nút thanh toán -->
    <a href="{{ url('/checkout') }}" class="primary-btn">Proceed to checkout</a>
</div>