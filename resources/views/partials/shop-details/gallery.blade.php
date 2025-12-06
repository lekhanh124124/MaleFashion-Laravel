<div class="product__details__pic">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="product__details__breadcrumb">
                    <a href="{{ route('home') }}">Home</a>
                    <a href="{{ route('shop') }}">Shop</a>
                    <span>{{ $product->PRODUCT_NAME }}</span>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-3 col-md-3">
                <ul class="nav nav-tabs scrollable-thumbs" role="tablist">
                    @if($product->images->count() > 0)
                        @foreach($product->images as $key => $img)
                            <li class="nav-item">
                                <a class="nav-link {{ $key == 0 ? 'active' : '' }}" data-toggle="tab" href="#tabs-{{ $key }}" role="tab">
                                    <div class="product__thumb__pic set-bg" data-setbg="{{ $img->IMAGE_URL }}">
                                    </div>
                                </a>
                            </li>
                        @endforeach
                    @else
                        {{-- Fallback nếu không có ảnh --}}
                        <li class="nav-item">
                            <a class="nav-link active" data-toggle="tab" href="#tabs-0" role="tab">
                                <div class="product__thumb__pic set-bg" data-setbg="https://placehold.co/400x520?text=No+Image"></div>
                            </a>
                        </li>
                    @endif
                </ul>
            </div>

            <div class="col-lg-6 col-md-9">
                <div class="tab-content">
                    @if($product->images->count() > 0)
                        @foreach($product->images as $key => $img)
                            <div class="tab-pane {{ $key == 0 ? 'active' : '' }}" id="tabs-{{ $key }}" role="tabpanel">
                                <div class="product__details__pic__item">
                                    <img src="{{ $img->IMAGE_URL }}" alt="{{ $product->PRODUCT_NAME }}">
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="tab-pane active" id="tabs-0" role="tabpanel">
                            <div class="product__details__pic__item">
                                <img src="https://placehold.co/400x520?text=No+Image" alt="No Image">
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>