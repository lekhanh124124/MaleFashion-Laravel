<div class="shopping__cart__table">
    <table>
        <thead>
            <tr>
                <th>Product</th>
                <th>Quantity</th>
                <th>Total</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @if(count($cartItems) > 0)
                @foreach($cartItems as $item)
                <tr>
                    <td class="product__cart__item">
                        <div class="product__cart__item__pic">
                            <!-- Kiểm tra xem ảnh là đường dẫn http hay file nội bộ -->
                            @if(Str::startsWith($item['image'], 'http'))
                                <img src="{{ $item['image'] }}" alt="{{ $item['name'] }}" style="width: 90px; height: 90px; object-fit: cover;">
                            @else
                                <img src="{{ asset($item['image']) }}" alt="{{ $item['name'] }}" style="width: 90px;">
                            @endif
                        </div>
                        <div class="product__cart__item__text">
                            <h6>{{ $item['name'] }}</h6>
                            <!-- Hiển thị Size/Màu nếu có -->
                            @if($item['size'] || $item['color'])
                                <span style="font-size: 12px; color: #888;">
                                    {{ $item['size'] }} / {{ $item['color'] }}
                                </span>
                            @endif
                            <h5>{{ number_format($item['price']) }}đ</h5>
                        </div>
                    </td>
                    <td class="quantity__item">
                        <div class="quantity">
                            <div class="pro-qty-2">
                                <!-- Data-rowid dùng để gọi Ajax cập nhật số lượng -->
                                <input type="text" class="cart-qty-input" 
                                       data-rowid="{{ $item['rowId'] }}" 
                                       value="{{ $item['quantity'] }}">
                            </div>
                        </div>
                    </td>
                    <td class="cart__price">
                        {{ number_format($item['total']) }}đ
                    </td>
                    <td class="cart__close">
                        <!-- Nút xóa item (cần viết thêm Ajax hoặc Route xóa) -->
                        <i class="fa fa-close" style="cursor: pointer;" onclick="removeCartItem({{ $item['rowId'] }})"></i>
                    </td>
                </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="4" class="text-center" style="padding: 50px;">
                        <p>Giỏ hàng của bạn đang trống!</p>
                        <a href="{{ url('/') }}" class="primary-btn">Mua sắm ngay</a>
                    </td>
                </tr>
            @endif
        </tbody>
    </table>
</div>

<!-- Đừng quên thêm Script xử lý xóa/sửa nếu chưa có -->
<script>
    function removeCartItem(id) {
        if(confirm('Bạn muốn xóa sản phẩm này?')) {
            // Gọi Ajax hoặc redirect đến route xóa
            // window.location.href = "/cart/remove/" + id;
        }
    }
</script>