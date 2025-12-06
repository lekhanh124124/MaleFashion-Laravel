<div class="card shadow-sm">
    <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
        <span><b>Size</b></span>
        <button class="btn btn-sm btn-light" data-toggle="collapse" data-target="#addSizeForm">+ Thêm</button>
    </div>
    <div class="collapse" id="addSizeForm">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.sizes.store') }}">
                @csrf
                <div class="form-group mb-2">
                    <label>Tên size</label>
                    <input name="SIZE_NAME" class="form-control" required>
                </div>
                <button class="btn btn-dark btn-sm">Thêm</button>
            </form>
        </div>
    </div>
    <div class="card-body p-0">
        <!-- Search form -->
        <form method="GET" class="p-2">
            <div class="input-group input-group-sm mb-2">
                <input type="text" name="size_search" class="form-control" placeholder="Tìm kiếm size..." value="{{ request('size_search') }}">
                <div class="input-group-append">
                    <button class="btn btn-outline-secondary" type="submit">Tìm</button>
                </div>
            </div>
            @foreach(request()->except(['size_search', 'sizes_page']) as $key => $val)
                <input type="hidden" name="{{ $key }}" value="{{ $val }}">
            @endforeach
        </form>
        <table class="table table-hover table-sm mb-0">
            <thead class="thead-light">
                <tr>
                    <th>
                        <a href="?{{ http_build_query(array_merge(request()->except('sizes_page'), [
                            'size_sort' => 'SIZE_ID',
                            'size_order' => request('size_order', 'desc') === 'asc' ? 'desc' : 'asc'
                        ])) }}">
                            ID
                            @if(request('size_sort') === 'SIZE_ID')
                                <span>{{ request('size_order', 'desc') === 'asc' ? '▲' : '▼' }}</span>
                            @endif
                        </a>
                    </th>
                    <th>
                        <a href="?{{ http_build_query(array_merge(request()->except('sizes_page'), [
                            'size_sort' => 'SIZE_NAME',
                            'size_order' => request('size_order', 'desc') === 'asc' ? 'desc' : 'asc'
                        ])) }}">
                            Tên
                            @if(request('size_sort') === 'SIZE_NAME')
                                <span>{{ request('size_order', 'desc') === 'asc' ? '▲' : '▼' }}</span>
                            @endif
                        </a>
                    </th>
                    <th class="text-right">Hành động</th>
                </tr>
            </thead>
            <tbody>
                @foreach($sizes as $size)
                <tr>
                    <td>{{ $size->SIZE_ID }}</td>
                    <td>{{ $size->SIZE_NAME }}</td>
                    <td class="text-right">
                        <form class="d-inline" method="POST" action="{{ route('admin.sizes.destroy', $size->SIZE_ID) }}">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger" type="submit">Xóa</button>
                        </form>
                        <button class="btn btn-sm btn-outline-primary" type="button" onclick="editSize({{ $size->SIZE_ID }}, '{{ $size->SIZE_NAME }}')">Sửa</button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="pt-2">{{ $sizes->onEachSide(1)->links('pagination::bootstrap-4') }}</div>
    </div>
</div>