@extends('layouts.malefashion')

@section('title', $product->PRODUCT_NAME)

@section('content')
  <section class="shop-details">
    {{-- 1. Phần Ảnh sản phẩm --}}
    @include('partials.shop-details.gallery', ['images' => $product->images])
    
    {{-- 2. Phần Thông tin chi tiết (Tên, Giá, Biến thể...) --}}
    @include('partials.shop-details.product-content')
    
    {{-- 3. Phần Tab Mô tả & Review (Review chưa làm logic dynamic) --}}
    @include('partials.shop-details.reviews')
  </section>

  {{-- 4. Phần Sản phẩm liên quan (Chưa làm logic dynamic) --}}
  @include('partials.shop-details.related')
@endsection