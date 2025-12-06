<div class="row">
    <div class="col-lg-6 col-md-6 col-sm-6">
        <div class="continue__btn">
            <a href="{{ url('/shop') }}">Continue Shopping</a>
        </div>
    </div>
    <div class="col-lg-6 col-md-6 col-sm-6">
        <div class="continue__btn update__btn">
            <!-- Thêm onclick để gọi hàm xử lý -->
            <a href="javascript:void(0)" onclick="updateCart()" id="btn-update-cart">
                <i class="fa fa-spinner"></i> Update cart
            </a>
        </div>
    </div>
</div>

<script>
    function updateCart() {
        // 1. Thu thập dữ liệu: Lấy tất cả input có class .cart-qty-input từ table.blade.php
        let cartItems = [];
        let hasError = false;

        $('.cart-qty-input').each(function() {
            let rowId = $(this).data('rowid');
            let qty = $(this).val();

            // Validate đơn giản
            if (qty < 1 || isNaN(qty)) {
                alert('Số lượng sản phẩm không hợp lệ!');
                hasError = true;
                return false; // Break loop
            }

            cartItems.push({
                rowId: rowId,
                quantity: qty
            });
        });

        if (hasError) return;

        // 2. Gửi Ajax lên Server
        $.ajax({
            url: '{{ route("cart.update") }}', // Route cần khai báo trong web.php
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                items: cartItems
            },
            beforeSend: function() {
                // Hiệu ứng xoay icon loading
                $('#btn-update-cart i').addClass('fa-spin');
            },
            success: function(response) {
                if (response.status === 'success') {
                    // Reload lại trang để PHP tính toán lại tổng tiền mới
                    window.location.reload();
                } else {
                    alert(response.message || 'Cập nhật thất bại!');
                }
            },
            error: function(xhr) {
                console.error(xhr);
                alert('Có lỗi xảy ra khi cập nhật giỏ hàng.');
            },
            complete: function() {
                $('#btn-update-cart i').removeClass('fa-spin');
            }
        });
    }
</script>