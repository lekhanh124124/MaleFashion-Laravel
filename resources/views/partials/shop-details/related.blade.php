<section class="related spad">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <h3 class="related-title">Related Product</h3>
            </div>
        </div>

        <div class="row">
            @if($relatedProducts->count() > 0)
                @foreach($relatedProducts as $related)
                    {{-- Tái sử dụng component thẻ sản phẩm đã có --}}
                    @include('partials.shop.product-item', ['product' => $related])
                @endforeach
            @else
                <div class="col-12 text-center">
                    <p>No related products found.</p>
                </div>
            @endif
        </div>
    </div>
</section>