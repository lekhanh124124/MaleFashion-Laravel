<div class="row">
    <div class="col-lg-12">
        <div class="product__pagination">
            {{ $products->onEachSide(1)->links('pagination::bootstrap-4') }}
        </div>
    </div>
</div>