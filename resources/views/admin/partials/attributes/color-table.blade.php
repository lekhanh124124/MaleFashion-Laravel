<div class="card shadow-sm">
    <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
        <span><b>Màu sắc</b></span>
        <button class="btn btn-sm btn-light" data-toggle="collapse" data-target="#addColorForm">+ Thêm</button>
    </div>
    <div class="collapse" id="addColorForm">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.colors.store') }}">
                @csrf
                <div class="form-group mb-2">
                    <label>Tên màu</label>
                    <input name="COLOR_NAME" class="form-control" required>
                </div>
                <div class="form-group mb-2">
                    <label>Mã màu (VD: #ff0000)</label>
                    <input name="COLOR_CODE" class="form-control">
                </div>
                <button class="btn btn-dark btn-sm">Thêm</button>
            </form>
        </div>
    </div>
    <div class="card-body p-0">
        <!-- Search form -->
        <form method="GET" class="p-2">
            <div class="input-group input-group-sm mb-2">
                <input type="text" name="color_search" class="form-control" placeholder="Tìm kiếm màu..." value="{{ request('color_search') }}">
                <div class="input-group-append">
                    <button class="btn btn-outline-secondary" type="submit">Tìm</button>
                </div>
            </div>
            @foreach(request()->except(['color_search', 'colors_page']) as $key => $val)
                <input type="hidden" name="{{ $key }}" value="{{ $val }}">
            @endforeach
        </form>
        <table class="table table-hover table-sm mb-0">
            <thead class="thead-light">
                <tr>
                    <th>
                        <a href="?{{ http_build_query(array_merge(request()->except('colors_page'), [
                            'color_sort' => 'COLOR_ID',
                            'color_order' => request('color_order', 'desc') === 'asc' ? 'desc' : 'asc'
                        ])) }}">
                            ID
                            @if(request('color_sort') === 'COLOR_ID')
                                <span>{{ request('color_order', 'desc') === 'asc' ? '▲' : '▼' }}</span>
                            @endif
                        </a>
                    </th>
                    <th>
                        <a href="?{{ http_build_query(array_merge(request()->except('colors_page'), [
                            'color_sort' => 'COLOR_NAME',
                            'color_order' => request('color_order', 'desc') === 'asc' ? 'desc' : 'asc'
                        ])) }}">
                            Tên
                            @if(request('color_sort') === 'COLOR_NAME')
                                <span>{{ request('color_order', 'desc') === 'asc' ? '▲' : '▼' }}</span>
                            @endif
                        </a>
                    </th>
                    <th>Mã màu</th>
                    <th class="text-right">Hành động</th>
                </tr>
            </thead>
            <tbody>
                @foreach($colors as $color)
                <tr>
                    <td>{{ $color->COLOR_ID }}</td>
                    <td>{{ $color->COLOR_NAME }}</td>
                    <td>
                        @if($color->COLOR_CODE)
                            <span style="display:inline-block;width:20px;height:20px;background:{{ $color->COLOR_CODE }};border:1px solid #ccc;"></span>
                            <span class="ml-1">{{ $color->COLOR_CODE }}</span>
                        @endif
                    </td>
                    <td class="text-right">
                        <form class="d-inline" method="POST" action="{{ route('admin.colors.destroy', $color->COLOR_ID) }}">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger" type="submit">Xóa</button>
                        </form>
                        <button class="btn btn-sm btn-outline-primary" type="button" onclick="editColor({{ $color->COLOR_ID }}, '{{ $color->COLOR_NAME }}', '{{ $color->COLOR_CODE }}')">Sửa</button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="pt-2">{{ $colors->onEachSide(1)->links('pagination::bootstrap-4') }}</div>
    </div>
</div>