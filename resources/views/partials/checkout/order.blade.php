<div class="col-lg-4 col-md-6">
    <div class="checkout__order">
        <h4 class="order__title">Đơn hàng của bạn</h4>
        
        <div class="checkout__order__products">Sản phẩm <span>Thành tiền</span></div>
        
        <ul class="checkout__total__products">
            @foreach($cartItems as $index => $item)
                <li>
                    {{ sprintf('%02d', $index + 1) }}. {{ $item['name'] }} 
                    <strong style="margin-left: 5px;">x {{ $item['quantity'] }}</strong>
                    <span>{{ number_format($item['total']) }}đ</span>
                </li>
            @endforeach
        </ul>

        <ul class="checkout__total__all">
            <li>Tạm tính <span>{{ number_format($total) }}đ</span></li>
            
            @if(isset($discount) && $discount > 0)
                <li class="text-success">
                    Giảm giá 
                    @if(Session::has('coupon'))
                        <small>({{ Session::get('coupon')['coupon_code'] }})</small>
                    @endif
                    <span>- {{ number_format($discount) }}đ</span>
                </li>
            @endif

            <li class="total-price">
                Tổng cộng 
                <span>{{ number_format($total - ($discount ?? 0)) }}đ</span>
            </li>
        </ul>

        <!-- Phương thức thanh toán -->
        <div class="checkout__input__checkbox">
            <label for="payment_cod">
                Thanh toán khi nhận hàng (COD)
                <input type="radio" id="payment_cod" name="payment_method" value="COD" checked onclick="toggleMomoInput(false)">
                <span class="checkmark"></span>
            </label>
        </div>
        
        <div class="checkout__input__checkbox">
            <label for="payment_momo">
                Thanh toán qua Ví Momo
                <input type="radio" id="payment_momo" name="payment_method" value="MOMO" onclick="toggleMomoInput(true)">
                <span class="checkmark"></span>
            </label>
        </div>

        <!-- Ô nhập STK Momo (Ẩn mặc định) -->
        <div id="momo_info_section" style="display: none; margin-bottom: 20px; border: 1px dashed #A50064; padding: 15px; border-radius: 5px; background: #fff0f6;">
            <p style="margin-bottom: 5px; color: #A50064; font-weight: bold;">Thông tin thanh toán Momo</p>
            <div class="checkout__input">
                <p>Số tài khoản / SĐT Momo của bạn<span>*</span></p>
                <input type="text" name="momo_number" placeholder="Nhập SĐT ví Momo..." style="color: #333;">
            </div>
            <small style="color: #666;">Hệ thống sẽ chuyển hướng bạn sang cổng thanh toán Momo sau khi bấm Đặt hàng.</small>
        </div>

        <div class="checkout__input__checkbox">
            <label for="payment_paypal">
                Paypal
                <input type="radio" id="payment_paypal" name="payment_method" value="PAYPAL" onclick="toggleMomoInput(false)">
                <span class="checkmark"></span>
            </label>
        </div>

        <button type="submit" class="site-btn" name="redirect">ĐẶT HÀNG</button>
    </div>
</div>

<script>
    function toggleMomoInput(show) {
        var section = document.getElementById('momo_info_section');
        if (show) {
            section.style.display = 'block';
        } else {
            section.style.display = 'none';
        }
    }
</script>