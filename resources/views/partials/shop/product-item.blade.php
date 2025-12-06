@php
// 1. Lấy biến thể đầu tiên tổng thể để hiển thị mặc định
$firstVariant = $product->variants->first();
$defaultPrice = $firstVariant ? number_format($firstVariant->PRICE, 0, ',', '.') . ' VNĐ' : 'Liên hệ';

// 2. Logic hiển thị nhãn (như cũ)
$label = '';
$labelClass = '';
if ($product->IS_HOT_SALE) {
$label = 'Hot'; $labelClass = 'sale';
} elseif ($product->IS_NEW_ARRIVAL) {
$label = 'New'; $labelClass = 'new';
} elseif ($product->IS_BEST_SELLER) {
$label = 'Best'; $labelClass = '';
}

// 3. Lấy ảnh (như cũ)
$imageUrl = $product->thumbnail ? $product->thumbnail->IMAGE_URL : 'https://placehold.co/400x520?text=No+Image';

// 4. Lấy danh sách màu có sẵn
$availableColors = $product->variants->map(function($variant) {
return $variant->color;
})->filter()->unique('COLOR_ID');

$isLiked = isset($likedProductIds) && in_array($product->PRODUCT_ID, $likedProductIds);
@endphp

<div class="{{ $colClass ?? 'col-lg-4 col-md-6 col-sm-6' }}">
    <div class="product__item {{ $label ? 'sale' : '' }}">
        <div class="product__item__pic set-bg" data-setbg="{{ $imageUrl }}">
            @if($label)
            <span class="label {{ $labelClass }}">{{ $label }}</span>
            @endif
            <ul class="product__hover">
                <li>
                    <a href="javascript:void(0);"
                        class="wishlist-btn {{ $isLiked ? 'active' : '' }}"
                        data-id="{{ $product->PRODUCT_ID }}">
                        <img src="{{ asset('assets/malefashion/img/icon/heart.png') }}" alt="">
                    </a>
                </li>
            </ul>
        </div>
        <div class="product__item__text">
            <h6>
                {{ $product->PRODUCT_NAME }}
            </h6>
            <a href="{{ route('shop.details', ['id' => $product->PRODUCT_ID]) }}" style="color: #111; font-weight: 600;">
                {{ $product->PRODUCT_NAME }}
            </a>
            <div class="rating">
                @php $rating = (int) $product->RATING; @endphp
                @for ($i = 1; $i <= 5; $i++)
                    <i class="fa {{ $i <= $rating ? 'fa-star' : 'fa-star-o' }}"></i>
                    @endfor
            </div>

            <h5 class="product-price-display" id="price-display-{{ $product->PRODUCT_ID }}">
                {{ $defaultPrice }}
            </h5>

            <div class="product__color__select">
                @foreach($availableColors as $index => $color)
                @php
                $variantForThisColor = $product->variants->where('COLOR_ID', $color->COLOR_ID)->first();
                $priceForThisColor = $variantForThisColor
                ? number_format($variantForThisColor->PRICE, 0, ',', '.') . ' VNĐ'
                : 'Liên hệ';
                @endphp
                <label style="background: {{ $color->COLOR_CODE }};"
                    for="pc-{{ $product->PRODUCT_ID }}-{{ $color->COLOR_ID }}"
                    class="{{ $index === 0 ? 'active' : '' }}"
                    title="{{ $color->COLOR_NAME }}">
                    <input type="radio"
                        class="color-variant-option"
                        id="pc-{{ $product->PRODUCT_ID }}-{{ $color->COLOR_ID }}"
                        name="color-{{ $product->PRODUCT_ID }}"
                        data-price="{{ $priceForThisColor }}"
                        data-product-id="{{ $product->PRODUCT_ID }}"
                        {{ $index === 0 ? 'checked' : '' }}>
                </label>
                @endforeach
            </div>
        </div>
    </div>
</div>