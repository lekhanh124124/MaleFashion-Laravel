@extends('layouts.malefashion')
@section('title', 'Home')
@section('content')

<section class="hero">
    <div class="hero__slider owl-carousel">
        @foreach($heroSliders as $banner)
            <div class="hero__items set-bg" data-setbg="{{ $banner->IMAGE_URL }}">
                <div class="container">
                    <div class="row">
                        <div class="col-xl-5 col-lg-7 col-md-8">
                            <div class="hero__text">
                                
                                {{-- Hiển thị Title (Tiêu đề chính) --}}
                                <h2>{{ $banner->TITLE }}</h2>
                                
                                <p>{{ $banner->SUBTITLE }}</p>
                                
                                {{-- Link Shop theo logic Category --}}
                                <a href="{{ $banner->shop_url }}" class="primary-btn">
                                    Shop now <span class="arrow_right"></span>
                                </a>
                                
                                <div class="hero__social">
                                    <a href="#"><i class="fa fa-facebook"></i></a>
                                    <a href="#"><i class="fa fa-twitter"></i></a>
                                    <a href="#"><i class="fa fa-pinterest"></i></a>
                                    <a href="#"><i class="fa fa-instagram"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</section>
<section class="banner spad">
    <div class="container">
        <div class="row">
            {{-- VỊ TRÍ 1: Promo Banner đầu tiên (DISPLAY_ORDER = 1) --}}
            @if(isset($promoBanners[0]))
                <div class="col-lg-7 offset-lg-4">
                    <div class="banner__item">
                        <div class="banner__item__pic">
                            <img src="{{ $promoBanners[0]->IMAGE_URL }}" alt="{{ $promoBanners[0]->TITLE }}">
                        </div>
                        <div class="banner__item__text">
                            <h2>{{ $promoBanners[0]->TITLE }}</h2>
                            <a href="{{ $promoBanners[0]->shop_url }}">Shop now</a>
                        </div>
                    </div>
                </div>
            @endif

            {{-- VỊ TRÍ 2: Promo Banner thứ hai (DISPLAY_ORDER = 2) --}}
            @if(isset($promoBanners[1]))
                <div class="col-lg-5">
                    <div class="banner__item banner__item--middle">
                        <div class="banner__item__pic">
                            <img src="{{ $promoBanners[1]->IMAGE_URL }}" alt="{{ $promoBanners[1]->TITLE }}">
                        </div>
                        <div class="banner__item__text">
                            <h2>{{ $promoBanners[1]->TITLE }}</h2>
                            <a href="{{ $promoBanners[1]->shop_url }}">Shop now</a>
                        </div>
                    </div>
                </div>
            @endif

            {{-- VỊ TRÍ 3: Promo Banner thứ ba (DISPLAY_ORDER = 3) --}}
            @if(isset($promoBanners[2]))
                <div class="col-lg-7">
                    <div class="banner__item banner__item--last">
                        <div class="banner__item__pic">
                            <img src="{{ $promoBanners[2]->IMAGE_URL }}" alt="{{ $promoBanners[2]->TITLE }}">
                        </div>
                        <div class="banner__item__text">
                            <h2>{{ $promoBanners[2]->TITLE }}</h2>
                            <a href="{{ $promoBanners[2]->shop_url }}">Shop now</a>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</section>

<section class="product spad">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <ul class="filter__controls">
                    <li class="active" data-filter="*">Best Sellers</li>
                    <li data-filter=".new-arrivals">New Arrivals</li>
                    <li data-filter=".hot-sales">Hot Sales</li>
                </ul>
            </div>
        </div>
        
        <div class="row product__filter">
            @foreach($products as $product)
                @php
                    // Tính toán class cho MixItUp
                    $mixClasses = 'mix'; // Class bắt buộc của plugin

                    if ($product->IS_NEW_ARRIVAL) {
                        $mixClasses .= ' new-arrivals';
                    }
                    if ($product->IS_HOT_SALE) {
                        $mixClasses .= ' hot-sales';
                    }
                    
                    // Tạo class cột cho Homepage (col-lg-3 thay vì col-lg-4) + class MixItUp
                    // Lưu ý: col-md-6 col-sm-6 giữ nguyên để responsive mobile
                    $customColClass = "col-lg-3 col-md-6 col-sm-6 col-md-6 col-sm-6 " . $mixClasses;
                @endphp

                {{-- Gọi Partial và truyền đè class cột --}}
                @include('partials.shop.product-item', [
                    'product' => $product,
                    'likedProductIds' => $likedProductIds,
                    'colClass' => $customColClass
                ])
            @endforeach
        </div>
    </div>
</section>

<!-- Categories Section Begin -->
<section class="categories spad">
  <div class="container">
    <div class="row">
      <div class="col-lg-3">
        <div class="categories__text">
          <h2>Clothings Hot <br /> <span>Shoe Collection</span> <br /> Accessories</h2>
        </div>
      </div>
      <div class="col-lg-4">
        <div class="categories__hot__deal">
          <img src="{{ asset('assets/malefashion/img/product-sale.png') }}" alt="">
          <div class="hot__deal__sticker">
            <span>Sale Of</span>
            <h5>$29.99</h5>
          </div>
        </div>
      </div>
      <div class="col-lg-4 offset-lg-1">
        <div class="categories__deal__countdown">
          <span>Deal Of The Week</span>
          <h2>Multi-pocket Chest Bag Black</h2>
          <div class="categories__deal__countdown__timer" id="countdown">
            <div class="cd-item"><span>3</span>
              <p>Days</p>
            </div>
            <div class="cd-item"><span>1</span>
              <p>Hours</p>
            </div>
            <div class="cd-item"><span>50</span>
              <p>Minutes</p>
            </div>
            <div class="cd-item"><span>18</span>
              <p>Seconds</p>
            </div>
          </div>
          <a href="/shop" class="primary-btn">Shop now</a>
        </div>
      </div>
    </div>
  </div>
</section>
<!-- Categories Section End -->

<!-- Instagram Section Begin -->
<section class="instagram spad">
  <div class="container">
    <div class="row">
      <div class="col-lg-8">
        <div class="instagram__pic">
          <div class="instagram__pic__item set-bg" data-setbg="{{ asset('assets/malefashion/img/instagram/instagram-1.jpg') }}"></div>
          <div class="instagram__pic__item set-bg" data-setbg="{{ asset('assets/malefashion/img/instagram/instagram-2.jpg') }}"></div>
          <div class="instagram__pic__item set-bg" data-setbg="{{ asset('assets/malefashion/img/instagram/instagram-3.jpg') }}"></div>
          <div class="instagram__pic__item set-bg" data-setbg="{{ asset('assets/malefashion/img/instagram/instagram-4.jpg') }}"></div>
          <div class="instagram__pic__item set-bg" data-setbg="{{ asset('assets/malefashion/img/instagram/instagram-5.jpg') }}"></div>
          <div class="instagram__pic__item set-bg" data-setbg="{{ asset('assets/malefashion/img/instagram/instagram-6.jpg') }}"></div>
        </div>
      </div>
      <div class="col-lg-4">
        <div class="instagram__text">
          <h2>Instagram</h2>
          <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
          <h3>#Male_Fashion</h3>
        </div>
      </div>
    </div>
  </div>
</section>
<!-- Instagram Section End -->

<!-- Latest Blog Section Begin -->
<section class="latest spad">
</section>
<!-- Latest Blog Section End -->
@endsection