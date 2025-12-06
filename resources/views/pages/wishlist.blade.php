@extends('layouts.malefashion')
@section('title', 'Wishlist')

@section('content')

@include('partials.wishlist.breadcrumb')

<section class="shop spad">
  <div class="container">
    <div class="row">
      <div class="col-lg-12">

        {{-- Thông báo --}}
        @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if($wishlistItems->count() > 0)
        <div class="row">
          @foreach ($wishlistItems as $item)
          @php
          $product = $item->product;
          // Lấy ảnh thumbnail
          $imageUrl = $product->thumbnail ? $product->thumbnail->IMAGE_URL : 'https://placehold.co/400x520?text=No+Image';

          // Lấy giá của biến thể đầu tiên
          $firstVariant = $product->variants->first();
          $price = $firstVariant ? number_format($firstVariant->PRICE, 0, ',', '.') . ' VNĐ' : 'Liên hệ';
          @endphp

          <div class="col-lg-4 col-md-6 col-sm-6 mb-4">
            <div class="product__item">
              <div class="product__item__pic set-bg" data-setbg="{{ $imageUrl }}">
                <ul class="product__hover">
                  <!-- Remove from wishlist -->
                  <li>
                    <form action="{{ route('wishlist.remove', ['id' => $product->PRODUCT_ID]) }}" method="POST" style="display:inline;">
                      @csrf
                      <button type="submit" style="background:none; border:none; padding:0;">
                        <a href="javascript:void(0);" onclick="this.parentNode.click();" title="Remove">
                          <img src="{{ asset('assets/malefashion/img/icon/heart.png') }}" alt="Remove" style="filter: brightness(0) invert(1); background: #e53637;">
                          {{-- Style tạm để icon màu trắng trên nền đỏ (biểu thị nút xóa) --}}
                        </a>
                      </button>
                    </form>
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
                <small class="text-muted d-block mb-1">
                  {{ $product->brand ? $product->brand->BRAND_NAME : 'No Brand' }}
                </small>
                <h5 class="mb-2">{{ $price }}</h5>
              </div>
            </div>
          </div>
          @endforeach
        </div>

        <!-- Actions row -->
        <div class="d-flex justify-content-between align-items-center mt-3">
          <a href="{{ route('shop') }}" class="site-btn">Continue Shopping</a>

          <form action="{{ route('wishlist.clear') }}" method="POST" onsubmit="return confirm('Bạn có chắc muốn xóa toàn bộ wishlist?');">
            @csrf
            <button type="submit" class="site-btn sb-dark">Clear Wishlist</button>
          </form>
        </div>
        @else
        <div class="text-center py-5">
          <h4 class="mb-3">Danh sách yêu thích của bạn đang trống.</h4>
          <a href="{{ route('shop') }}" class="primary-btn">Mua sắm ngay</a>
        </div>
        @endif

      </div>
    </div>
  </div>
</section>
@endsection