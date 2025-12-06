<div class="shop__product__option">
  <div class="row">
    <div class="col-lg-6 col-md-6 col-sm-6">
      <div class="shop__product__option__left">
        @if($products->total() > 0)
        <p>Showing {{ $products->firstItem() }}–{{ $products->lastItem() }} of {{ $products->total() }} results</p>
        @else
        <p>No products found.</p>
        @endif
      </div>
    </div>
    <div class="col-lg-6 col-md-6 col-sm-6">
      <div class="shop__product__option__right">
        <p>Sort by Price:</p>
        <select onchange="location = this.value;">
          <option value="{{ request()->fullUrlWithQuery(['sort' => null]) }}">Default</option>

          <option value="{{ request()->fullUrlWithQuery(['sort' => 'desc']) }}"
            {{ request('sort') == 'desc' ? 'selected' : '' }}> High To Low
          </option>

          <option value="{{ request()->fullUrlWithQuery(['sort' => 'asc']) }}"
            {{ request('sort') == 'asc' ? 'selected' : '' }}> Low To High
          </option>

        </select>
      </div>
    </div>
  </div>
</div>