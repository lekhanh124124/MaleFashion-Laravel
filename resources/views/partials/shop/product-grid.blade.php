<div class="row">
    @foreach ($products as $product)
        @include('partials.shop.product-item', ['product' => $product])
    @endforeach
</div>

@if($products->isEmpty())
    <div class="col-12 text-center">
        <p>No products found.</p>
    </div>
@endif