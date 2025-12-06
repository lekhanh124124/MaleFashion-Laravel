<div class="shop__sidebar">
    <div class="shop__sidebar__search">
        <form action="{{ route('shop') }}" method="GET">
            <input type="text" name="search" placeholder="Search..." value="{{ request('search') }}">
            <button type="submit"><span class="icon_search"></span></button>
        </form>
    </div>

    <div class="shop__sidebar__accordion">
        <div class="accordion" id="accordionExample">

            <div class="card">
                <div class="card-heading">
                    <a data-toggle="collapse" data-target="#collapseOne">Categories</a>
                </div>
                <div id="collapseOne" class="collapse show" data-parent="#accordionExample">
                    <div class="card-body">
                        <div class="shop__sidebar__categories">
                            <ul class="nice-scroll">
                                <li><a href="{{ route('shop') }}" class="{{ !request('category') ? 'text-dark font-weight-bold' : '' }}">All</a></li>
                                @foreach($categories as $cat)
                                <li>
                                    <a href="{{ route('shop', array_merge(request()->except('page'), ['category' => $cat->CATEGORY_ID])) }}"
                                        class="{{ request('category') == $cat->CATEGORY_ID ? 'text-dark font-weight-bold' : '' }}">
                                        {{ $cat->CATEGORY_NAME }}
                                    </a>
                                    @if($cat->children->isNotEmpty())
                                    <ul style="margin-left: 15px; margin-bottom: 10px; border-left: 1px solid #eee; padding-left: 10px;">
                                        @foreach($cat->children as $child)
                                        <li>
                                            <a href="{{ route('shop', array_merge(request()->except('page'), ['category' => $child->CATEGORY_ID])) }}"
                                                style="font-size: 13px; line-height: 24px; color: {{ request('category') == $child->CATEGORY_ID ? '#111; font-weight:bold' : '#b7b7b7' }};">
                                                {{ $child->CATEGORY_NAME }}
                                            </a>
                                        </li>
                                        @endforeach
                                    </ul>
                                    @endif
                                </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <form action="{{ route('shop') }}" method="GET" id="filterForm">
                @if(request('category')) <input type="hidden" name="category" value="{{ request('category') }}"> @endif
                @if(request('search')) <input type="hidden" name="search" value="{{ request('search') }}"> @endif

                <div class="card">
                    <div class="card-heading">
                        <a data-toggle="collapse" data-target="#collapseTwo">Branding</a>
                    </div>
                    <div id="collapseTwo" class="collapse show" data-parent="#accordionExample">
                        <div class="card-body">
                            <div class="shop__sidebar__brand">
                                @php
                                // Lấy danh sách brand đang chọn từ URL, ép kiểu về mảng
                                $selectedBrands = (array)request('brand', []);
                                @endphp
                                @foreach($brands as $brand)
                                <div class="custom-control custom-checkbox mb-2">
                                    <input type="checkbox"
                                        id="brand-{{ $brand->BRAND_ID }}"
                                        name="brand[]"
                                        value="{{ $brand->BRAND_ID }}"
                                        class="custom-control-input"
                                        {{ in_array($brand->BRAND_ID, $selectedBrands) ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="brand-{{ $brand->BRAND_ID }}">{{ $brand->BRAND_NAME }}</label>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-heading">
                        <a data-toggle="collapse" data-target="#collapseThree">Filter Price</a>
                    </div>
                    <div id="collapseThree" class="collapse show" data-parent="#accordionExample">
                        <div class="card-body">
                            <div class="shop__sidebar__price">
                                <div id="price-slider-range" style="margin-bottom: 20px;"></div>
                                <div style="display: flex; justify-content: space-between; align-items: center;">
                                    <input type="text" id="price-min-input" class="price-input" value="{{ request('min_price', 0) }}">
                                    <span style="color: #888;">-</span>
                                    <input type="text" id="price-max-input" class="price-input" value="{{ request('max_price', 10000000) }}">
                                </div>
                                <input type="hidden" name="min_price" id="hidden_min_price" value="{{ request('min_price', 0) }}">
                                <input type="hidden" name="max_price" id="hidden_max_price" value="{{ request('max_price', 10000000) }}">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-heading">
                        <a data-toggle="collapse" data-target="#collapseFour">Size</a>
                    </div>
                    <div id="collapseFour" class="collapse show" data-parent="#accordionExample">
                        <div class="card-body">
                            <div class="shop__sidebar__tags">
                                @php $selectedSizes = (array)request('size', []); @endphp
                                @foreach($sizes as $size)
                                <label class="{{ in_array($size->SIZE_ID, $selectedSizes) ? 'active' : '' }}"
                                    style="cursor: pointer; display: inline-block; margin-right: 5px; background: #f1f1f1; color: #444; padding: 6px 15px; font-size: 13px;">

                                    {{ $size->SIZE_NAME }}

                                    <input type="checkbox" class="toggle-checkbox"
                                        name="size[]"
                                        value="{{ $size->SIZE_ID }}"
                                        style="display:none;"
                                        {{ in_array($size->SIZE_ID, $selectedSizes) ? 'checked' : '' }}>
                                </label>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-heading">
                        <a data-toggle="collapse" data-target="#collapseFive">Colors</a>
                    </div>
                    <div id="collapseFive" class="collapse show" data-parent="#accordionExample">
                        <div class="card-body">
                            <div class="shop__sidebar__color">
                                @foreach($colors as $color)
                                <label class="{{ request('color') == $color->COLOR_ID ? 'active' : '' }}"
                                    style="background: {{ $color->COLOR_CODE }}; width: 30px; height: 30px; border-radius: 50%; display: inline-block; margin-right: 5px; cursor: pointer; border: 1px solid #ddd;"
                                    for="sidebar-color-{{ $color->COLOR_ID }}"
                                    title="{{ $color->COLOR_NAME }}">
                                    <input type="radio" class="toggle-radio" id="sidebar-color-{{ $color->COLOR_ID }}" name="color" value="{{ $color->COLOR_ID }}" {{ request('color') == $color->COLOR_ID ? 'checked' : '' }}>
                                </label>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-heading">
                        <a data-toggle="collapse" data-target="#collapseSix">Tags</a>
                    </div>
                    <div id="collapseSix" class="collapse show" data-parent="#accordionExample">
                        <div class="card-body">
                            <div class="shop__sidebar__tags">
                                @php $selectedTags = (array)request('tag', []); @endphp
                                @foreach($tags as $tag)
                                <label class="{{ in_array($tag->TAG_ID, $selectedTags) ? 'active' : '' }}"
                                    style="cursor: pointer; display: inline-block; margin-right: 5px; background: #f1f1f1; color: #444; padding: 6px 15px; font-size: 13px;">
                                    {{ $tag->TAG_NAME }}
                                    <input type="checkbox" class="toggle-checkbox"
                                        name="tag[]"
                                        value="{{ $tag->TAG_ID }}"
                                        style="display:none;"
                                        {{ in_array($tag->TAG_ID, $selectedTags) ? 'checked' : '' }}>
                                </label>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <div style="margin-top: 30px; display: flex; gap: 10px;">
                    <button type="submit" class="site-btn" style="flex: 1; padding: 10px 0; background: #000; color: #fff; border: none; cursor: pointer;">APPLY</button>
                    <a href="{{ route('shop') }}" class="site-btn" style="flex: 1; padding: 10px 0; background: #f3f2ee; color: #111; text-align: center; text-decoration: none;">REFRESH</a>
                </div>

            </form>
        </div>
    </div>
</div>