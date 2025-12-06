<div class="product__details__content">
    <div class="container">
        <div class="row d-flex justify-content-center">
            <div class="col-lg-8">
                <div class="product__details__text">
                    <h4>{{ $product->PRODUCT_NAME }}</h4>
                    
                    <div class="rating">
                        @php $rating = (int) $product->RATING; @endphp
                        @for ($i = 1; $i <= 5; $i++)
                            <i class="fa {{ $i <= $rating ? 'fa-star' : 'fa-star-o' }}"></i>
                        @endfor
                        <span> - {{ $reviews->count() }} Reviews</span>
                    </div>

                    {{-- Giá hiển thị dynamic --}}
                    <h3 id="product-price">
                        {{ $defaultVariant ? number_format($defaultVariant->PRICE, 0, ',', '.') . ' VNĐ' : 'Liên hệ' }}
                    </h3>
                    
                    <p>{{ $product->SHORT_DESCRIPTION }}</p>

                    {{-- Form Add To Cart --}}
                    <form id="add-to-cart-form">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->PRODUCT_ID }}">
                        <input type="hidden" name="variant_id" id="selected_variant_id" value="{{ $defaultVariant->VARIANT_ID ?? '' }}">

                        <div class="product__details__option">
                            {{-- SIZE OPTION --}}
                            @if($uniqueSizes->isNotEmpty())
                                <div class="product__details__option__size">
                                    <span>Size:</span>
                                    @foreach($uniqueSizes as $size)
                                        @if($size)
                                            <label for="size-{{ $size->SIZE_ID }}" class="{{ $defaultVariant && $defaultVariant->SIZE_ID == $size->SIZE_ID ? 'active' : '' }}">
                                                {{ $size->SIZE_NAME }}
                                                <input type="radio" class="variant-selector" name="size" id="size-{{ $size->SIZE_ID }}" value="{{ $size->SIZE_ID }}" {{ $defaultVariant && $defaultVariant->SIZE_ID == $size->SIZE_ID ? 'checked' : '' }}>
                                            </label>
                                        @endif
                                    @endforeach
                                </div>
                            @endif

                            {{-- COLOR OPTION --}}
                            @if($uniqueColors->isNotEmpty())
                                <div class="product__details__option__color">
                                    <span>Color:</span>
                                    @foreach($uniqueColors as $color)
                                        @if($color)
                                            <label class="{{ $defaultVariant && $defaultVariant->COLOR_ID == $color->COLOR_ID ? 'active' : '' }}" 
                                                   style="background: {{ $color->COLOR_CODE }};" 
                                                   for="color-{{ $color->COLOR_ID }}"
                                                   title="{{ $color->COLOR_NAME }}">
                                                <input type="radio" class="variant-selector" name="color" id="color-{{ $color->COLOR_ID }}" value="{{ $color->COLOR_ID }}" {{ $defaultVariant && $defaultVariant->COLOR_ID == $color->COLOR_ID ? 'checked' : '' }}>
                                            </label>
                                        @endif
                                    @endforeach
                                </div>
                            @endif
                        </div>

                        <div class="product__details__cart__option">
                            <div class="quantity">
                                {{-- Sử dụng class custom để xử lý JS riêng, tránh xung đột với main.js của template --}}
                                <div class="pro-qty-custom">
                                    <span class="qty-btn dec">-</span>
                                    <input type="text" name="quantity" id="quantity_input" value="1">
                                    <span class="qty-btn inc">+</span>
                                </div>
                            </div>
                            <button type="button" id="btn-add-to-cart" class="primary-btn">add to cart</button>
                        </div>
                        {{-- Thông báo lỗi tồn kho --}}
                        <div id="stock-alert" style="color: #e53637; font-size: 14px; display: none; margin-top: 5px; font-weight: bold;"></div>
                    </form>

                    <div class="product__details__btns__option">
                        @php
                            $likedProductIds = [];
                            if (Illuminate\Support\Facades\Auth::check()) {
                                $likedProductIds = App\Models\Wishlist::where('USER_ID', Illuminate\Support\Facades\Auth::user()->USER_ID)->pluck('PRODUCT_ID')->toArray();
                            }
                            $isLiked = in_array($product->PRODUCT_ID, $likedProductIds);
                        @endphp
                        <a href="javascript:void(0);" class="wishlist-btn {{ $isLiked ? 'active' : '' }}" data-id="{{ $product->PRODUCT_ID }}">
                            <i class="fa fa-heart"></i> add to wishlist
                        </a>
                    </div>

                    <div class="product__details__last__option">
                        <h5><span>Guaranteed Safe Checkout</span></h5>
                        <img src="{{ asset('assets/malefashion/img/shop-details/details-payment.png') }}" alt="">
                        <ul>
                            <li><span>SKU:</span> <span id="product-sku">{{ $defaultVariant->SKU ?? 'N/A' }}</span></li>
                            <li><span>Categories:</span> {{ $product->category->CATEGORY_NAME ?? 'Uncategorized' }}</li>
                            <li>
                                <span>Tag:</span> 
                                @foreach($product->tags as $tag)
                                    {{ $tag->TAG_NAME }}{{ !$loop->last ? ',' : '' }}
                                @endforeach
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        
        {{-- Tab Mô tả và Review --}}
        <div class="row">
            <div class="col-lg-12">
                <div class="product__details__tab">
                    <ul class="nav nav-tabs" role="tablist">
                        <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#tabs-5" role="tab">Description</a></li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active" id="tabs-5" role="tabpanel">
                            <div class="product__details__tab__content">
                                {!! $product->DESCRIPTION !!}
                            </div>
                        </div>
                        <div class="tab-pane" id="tabs-6" role="tabpanel">
                            <div class="product__details__tab__content">
                                @include('partials.shop-details.reviews')
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- SCRIPT XỬ LÝ --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const variantsMap = @json($variantsMap);
        
        const variantSelectors = document.querySelectorAll('.variant-selector');
        const priceDisplay = document.getElementById('product-price');
        const skuDisplay = document.getElementById('product-sku');
        const variantInput = document.getElementById('selected_variant_id');
        const addToCartBtn = document.getElementById('btn-add-to-cart');
        
        const quantityInput = document.getElementById('quantity_input');
        const stockAlert = document.getElementById('stock-alert');
        
        let currentMaxStock = {{ $defaultVariant ? $defaultVariant->STOCK : 0 }};

        function updateVariantInfo() {
            const selectedSize = document.querySelector('input[name="size"]:checked');
            const selectedColor = document.querySelector('input[name="color"]:checked');

            if (selectedSize && selectedColor) {
                const key = selectedSize.value + '-' + selectedColor.value;
                const variant = variantsMap[key];

                if (variant) {
                    priceDisplay.innerText = variant.price;
                    skuDisplay.innerText = variant.sku;
                    variantInput.value = variant.id;
                    currentMaxStock = variant.stock;
                    
                    quantityInput.value = 1;
                    stockAlert.style.display = 'none';

                    if(variant.stock > 0) {
                        addToCartBtn.disabled = false;
                        addToCartBtn.innerText = 'add to cart';
                        addToCartBtn.style.opacity = '1';
                        addToCartBtn.style.cursor = 'pointer';
                    } else {
                        addToCartBtn.disabled = true;
                        addToCartBtn.innerText = 'Out of Stock';
                        addToCartBtn.style.opacity = '0.6';
                        addToCartBtn.style.cursor = 'not-allowed';
                        quantityInput.value = 0;
                    }
                } else {
                    priceDisplay.innerText = 'Unavailable';
                    skuDisplay.innerText = 'N/A';
                    variantInput.value = '';
                    addToCartBtn.disabled = true;
                    addToCartBtn.innerText = 'Unavailable';
                    addToCartBtn.style.opacity = '0.6';
                    addToCartBtn.style.cursor = 'not-allowed';
                    currentMaxStock = 0;
                    quantityInput.value = 0;
                }
            }
        }

        variantSelectors.forEach(input => {
            input.addEventListener('change', function() {
                const groupName = this.getAttribute('name');
                document.querySelectorAll(`input[name="${groupName}"]`).forEach(el => {
                    el.parentElement.classList.remove('active');
                });
                this.parentElement.classList.add('active');
                updateVariantInfo();
            });
        });

        document.querySelector('.qty-btn.inc').addEventListener('click', function() {
            let currentValue = parseInt(quantityInput.value) || 0;
            if(currentMaxStock <= 0) return;
            if (currentValue < currentMaxStock) {
                quantityInput.value = currentValue + 1;
                stockAlert.style.display = 'none';
            } else {
                stockAlert.innerText = `Chỉ còn ${currentMaxStock} sản phẩm trong kho!`;
                stockAlert.style.display = 'block';
            }
        });

        document.querySelector('.qty-btn.dec').addEventListener('click', function() {
            let currentValue = parseInt(quantityInput.value) || 0;
            if (currentValue > 1) {
                quantityInput.value = currentValue - 1;
                stockAlert.style.display = 'none';
            }
        });

        quantityInput.addEventListener('change', function() {
            let val = parseInt(this.value) || 1;
            if (val < 1) val = 1;
            if (val > currentMaxStock) {
                val = currentMaxStock;
                stockAlert.innerText = `Chỉ còn ${currentMaxStock} sản phẩm trong kho!`;
                stockAlert.style.display = 'block';
            } else {
                stockAlert.style.display = 'none';
            }
            this.value = val;
        });

        addToCartBtn.addEventListener('click', function() {
            const variantId = variantInput.value;
            if (!variantId) {
                alert('Vui lòng chọn Size và Màu sắc!');
                return;
            }
            let qty = parseInt(quantityInput.value);
            if (qty <= 0 || qty > currentMaxStock) {
                alert('Số lượng không hợp lệ hoặc vượt quá tồn kho!');
                return;
            }
const form = document.getElementById('add-to-cart-form');
            const formData = new FormData(form);
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            fetch("{{ route('cart.add') }}", {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': csrfToken },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    // 1. Thông báo thành công
                    alert(data.message);

                    // 2. CẬP NHẬT HEADER (Số lượng)
                    const headerCountEl = document.getElementById('header-cart-count');
                    if (headerCountEl) {
                        headerCountEl.innerText = data.new_count;
                    }

                    // 3. CẬP NHẬT HEADER (Tổng tiền - Format sang dạng tiền Việt)
                    const headerTotalEl = document.getElementById('header-cart-total');
                    if (headerTotalEl) {
                        // Hàm format tiền tệ của JS
                        const formattedPrice = new Intl.NumberFormat('vi-VN', { 
                            style: 'currency', 
                            currency: 'VND' 
                        }).format(data.new_total);
                        
                        // Thay chữ đ thành ký hiệu ₫ cho đẹp (tuỳ chọn)
                        headerTotalEl.innerText = formattedPrice.replace('₫', '') + '₫'; 
                    }
                } else {
                    alert(data.message); // Báo lỗi nếu có
                }
            })
            .catch(error => console.error('Error:', error));
        });

        document.addEventListener('click', function(e) {
            const btn = e.target.closest('.wishlist-btn');
            if (btn) {
                e.preventDefault();
                const productId = btn.getAttribute('data-id');
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                fetch(`/wishlist/toggle/${productId}`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'login_required') {
                        alert(data.message);
                        window.location.href = "{{ route('login') }}";
                    } else if (data.status === 'added') {
                        btn.classList.add('active');
                    } else if (data.status === 'removed') {
                        btn.classList.remove('active');
                    }
                })
                .catch(error => console.error('Error:', error));
            }
        });
    });
</script>

<style>
    /* Style cho nút Wishlist khi active (trái tim đỏ) */
    .wishlist-btn.active i {
        color: #e53637 !important;
    }

    /* Ẩn input radio mặc định của size/color để giao diện sạch sẽ */
    .product__details__option__size label input,
    .product__details__option__color label input {
        position: absolute;
        visibility: hidden;
    }

    /* Style cơ bản cho khung số lượng tùy chỉnh (giữ lại để nó không bị vỡ giao diện) */
    .pro-qty-custom {
        width: 120px; /* Cố định chiều rộng */
        height: 40px; /* Cố định chiều cao */
        display: flex;
        align-items: center;
        justify-content: center;
        background: #f3f2ee;
        margin-right: 15px;
        border-radius: 4px;
        overflow: hidden;
    }
    .pro-qty-custom input {
        width: 40px;    /* Cố định chiều rộng input */
        height: 100%;
        font-size: 16px;
        color: #111111;
        border: none;
        text-align: center;
        background: transparent;
        padding: 0;
        margin: 0 2px;
    }
    .pro-qty-custom .qty-btn {
        width: 35px;    /* Cố định chiều rộng nút */
        height: 100%;
        color: #111111;
        font-size: 18px;
        line-height: 40px;
        cursor: pointer;
        user-select: none;
        font-weight: 700;
        display: flex;
        align-items: center;
        justify-content: center;
        background: none;
        border: none;
        padding: 0;
    }
    
    /* Đảm bảo con trỏ chuột hiển thị đúng khi hover vào nhãn chọn size/màu */
    .product__details__option__size label,
    .product__details__option__color label {
        cursor: pointer;
    }
</style>