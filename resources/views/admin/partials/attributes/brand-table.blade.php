<div class="card shadow-sm">
    <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
        <span><b>Thương hiệu</b></span>
        <button class="btn btn-sm btn-light" data-toggle="collapse" data-target="#addBrandForm">+ Thêm</button>
    </div>
    <div class="collapse" id="addBrandForm">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.brands.store') }}">
                @csrf
                <div class="form-group mb-2">
                    <label>Tên thương hiệu</label>
                    <input name="BRAND_NAME" class="form-control" required>
                </div>
                <button class="btn btn-dark btn-sm">Thêm</button>
            </form>
        </div>
    </div>
    <div class="card-body p-0">
        <!-- Search form -->
        <form method="GET" class="p-2">
            <div class="input-group input-group-sm mb-2">
                <input type="text" name="brand_search" class="form-control" placeholder="Tìm kiếm thương hiệu..." value="{{ request('brand_search') }}">
                <div class="input-group-append">
                    <button class="btn btn-outline-secondary" type="submit">Tìm</button>
                </div>
            </div>
            <!-- giữ lại các query khác khi search -->
            @foreach(request()->except(['brand_search', 'brands_page']) as $key => $val)
                <input type="hidden" name="{{ $key }}" value="{{ $val }}">
            @endforeach
        </form>
        <table class="table table-hover table-sm mb-0">
            <thead class="thead-light">
                <tr>
                    <th>
                        <a href="?{{ http_build_query(array_merge(request()->except('brands_page'), [
                            'brand_sort' => 'BRAND_ID',
                            'brand_order' => request('brand_order', 'desc') === 'asc' ? 'desc' : 'asc'
                        ])) }}">
                            ID
                            @if(request('brand_sort') === 'BRAND_ID')
                                <span>{{ request('brand_order', 'desc') === 'asc' ? '▲' : '▼' }}</span>
                            @endif
                        </a>
                    </th>
                    <th>
                        <a href="?{{ http_build_query(array_merge(request()->except('brands_page'), [
                            'brand_sort' => 'BRAND_NAME',
                            'brand_order' => request('brand_order', 'desc') === 'asc' ? 'desc' : 'asc'
                        ])) }}">
                            Tên
                            @if(request('brand_sort') === 'BRAND_NAME')
                                <span>{{ request('brand_order', 'desc') === 'asc' ? '▲' : '▼' }}</span>
                            @endif
                        </a>
                    </th>
                    <th class="text-right">Hành động</th>
                </tr>
            </thead>
            <tbody>
                @foreach($brands as $brand)
                <tr>
                    <td>{{ $brand->BRAND_ID }}</td>
                    <td>{{ $brand->BRAND_NAME }}</td>
                    <td class="text-right">
                        <form class="d-inline" method="POST" action="{{ route('admin.brands.destroy', $brand->BRAND_ID) }}">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger" type="submit">Xóa</button>
                        </form>
                        <button class="btn btn-sm btn-outline-primary" type="button" onclick="editBrand({{ $brand->BRAND_ID }}, '{{ $brand->BRAND_NAME }}')">Sửa</button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="pt-2">{{ $brands->onEachSide(1)->links('pagination::bootstrap-4') }}</div>
    </div>
</div>