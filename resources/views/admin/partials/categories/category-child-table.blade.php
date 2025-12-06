<div class="card shadow-sm">
    <div class="card-header bg-secondary text-white">
        <b>Danh mục con</b>
        @if(request('parent_id'))
            <span class="ml-2">(của: {{ optional($allCategories->firstWhere('CATEGORY_ID', request('parent_id')))->CATEGORY_NAME }})</span>
            <a href="{{ route('admin.categories.index') }}" class="btn btn-sm btn-light float-right">Xem tất cả</a>
        @endif
    </div>
    <div class="card-body p-0">
        <form method="GET" class="p-2">
            <div class="input-group input-group-sm mb-2">
                <input type="text" name="child_search" class="form-control" placeholder="Tìm kiếm danh mục con..." value="{{ request('child_search') }}">
                <div class="input-group-append">
                    <button class="btn btn-outline-secondary" type="submit">Tìm</button>
                </div>
            </div>
            @foreach(request()->except(['child_search', 'child_page']) as $key => $val)
                <input type="hidden" name="{{ $key }}" value="{{ $val }}">
            @endforeach
        </form>
        <table class="table table-hover table-sm mb-0">
            <thead class="thead-light">
                <tr>
                    <th>
                        <a href="?{{ http_build_query(array_merge(request()->except('child_page'), [
                            'child_sort' => 'CATEGORY_ID',
                            'child_order' => request('child_order', 'desc') === 'asc' ? 'desc' : 'asc'
                        ])) }}">
                            ID
                            @if(request('child_sort') === 'CATEGORY_ID')
                                <span>{{ request('child_order', 'desc') === 'asc' ? '▲' : '▼' }}</span>
                            @endif
                        </a>
                    </th>
                    <th>
                        <a href="?{{ http_build_query(array_merge(request()->except('child_page'), [
                            'child_sort' => 'CATEGORY_NAME',
                            'child_order' => request('child_order', 'desc') === 'asc' ? 'desc' : 'asc'
                        ])) }}">
                            Tên
                            @if(request('child_sort') === 'CATEGORY_NAME')
                                <span>{{ request('child_order', 'desc') === 'asc' ? '▲' : '▼' }}</span>
                            @endif
                        </a>
                    </th>
                    <th>Danh mục cha</th>
                    <th width="120">Thao tác</th>
                </tr>
            </thead>
            <tbody>
                @foreach($childCategories as $cat)
                <tr>
                    <td>{{ $cat->CATEGORY_ID }}</td>
                    <td>{{ $cat->CATEGORY_NAME }}</td>
                    <td>{{ $cat->parent ? $cat->parent->CATEGORY_NAME : '-' }}</td>
                    <td>
                        <form method="POST" action="{{ route('admin.categories.destroy', $cat->CATEGORY_ID) }}" style="display:inline;">
                            @csrf @method('DELETE')
                            <button class="btn btn-outline-danger btn-sm" onclick="return confirm('Xóa?')">Xóa</button>
                        </form>
                        <button class="btn btn-outline-primary btn-sm" data-toggle="modal" data-target="#editCatChild{{ $cat->CATEGORY_ID }}">Sửa</button>
                        <div class="modal fade" id="editCatChild{{ $cat->CATEGORY_ID }}">
                            <div class="modal-dialog">
                                <form method="POST" action="{{ route('admin.categories.update', $cat->CATEGORY_ID) }}">
                                    @csrf @method('PUT')
                                    <div class="modal-content">
                                        <div class="modal-header bg-dark text-white"><h5 class="modal-title">Sửa danh mục</h5></div>
                                        <div class="modal-body">
                                            <div class="form-group">
                                                <label>Tên danh mục</label>
                                                <input type="text" name="CATEGORY_NAME" class="form-control" value="{{ $cat->CATEGORY_NAME }}" required>
                                            </div>
                                            <div class="form-group">
                                                <label>Danh mục cha</label>
                                                <select name="PARENT_ID" class="form-control">
                                                    <option value="">-- Không --</option>
                                                    @foreach($allCategories as $p)
                                                        @if($p->CATEGORY_ID != $cat->CATEGORY_ID)
                                                        <option value="{{ $p->CATEGORY_ID }}" @if($cat->PARENT_ID == $p->CATEGORY_ID) selected @endif>
                                                            {{ $p->CATEGORY_NAME }}
                                                        </option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button class="btn btn-dark btn-sm">Lưu</button>
                                            <button type="button" class="btn btn-outline-secondary btn-sm" data-dismiss="modal">Đóng</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <!-- end modal -->
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="p-2">
            {{ $childCategories->appends(request()->except('child_page'))->onEachSide(1)->links('pagination::bootstrap-4') }}
        </div>
    </div>
</div>