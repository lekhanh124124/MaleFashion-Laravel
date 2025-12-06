<div class="cart__discount">
    <h6>Discount codes</h6>
    <form action="{{ route('coupon.apply') }}" method="POST">
        @csrf <!-- Bắt buộc để bảo mật form trong Laravel -->
        
        <!-- name="coupon_code" phải khớp với validation trong Controller -->
        <input type="text" name="coupon_code" placeholder="Coupon code" required value="{{ old('coupon_code') }}">
        <button type="submit">Apply</button>
    </form>

    <!-- Hiển thị thông báo lỗi (mã sai, hết hạn...) -->
    @if(session('error'))
        <div class="text-danger mt-2" style="font-size: 14px;">
            <i class="fa fa-exclamation-circle"></i> {{ session('error') }}
        </div>
    @endif

    <!-- Hiển thị thông báo thành công -->
    @if(session('success'))
        <div class="text-success mt-2" style="font-size: 14px;">
            <i class="fa fa-check-circle"></i> {{ session('success') }}
        </div>
    @endif
</div>