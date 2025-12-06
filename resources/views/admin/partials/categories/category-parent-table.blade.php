<div class="card shadow-sm mb-4">
    <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
        <span><b>Danh mục cha</b></span>
        <button class="btn btn-sm btn-light" data-toggle="collapse" data-target="#addCategoryForm">+ Thêm</button>
    </div>
    <div class="collapse" id="addCategoryForm">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.categories.store') }}">
                @csrf
                <div class="form-group">
                    <label>Tên danh mục</label>
                    <input type="text" name="CATEGORY_NAME" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Chọn danh mục cha (tuỳ chọn)</label>
                    <select name="PARENT_ID" class="form-control" style="max-height:200px; overflow-y:auto;">
                        <option value="">-- Không --</option>
                        @foreach($parentCategories as $cat)
                            <option value="{{ $cat->CATEGORY_ID }}">{{ $cat->CATEGORY_NAME }}</option>
                        @endforeach
                    </select>
                </div>
                <button class="btn btn-dark btn-sm">Thêm</button>
            </form>
        </div>
    </div>
    <div class="card-body p-0">
        <form method="GET" class="p-2">
            <div class="input-group input-group-sm mb-2">
                <input type="text" name="parent_search" class="form-control" placeholder="Tìm kiếm danh mục cha..." value="{{ request('parent_search') }}">
                <div class="input-group-append">
                    <button class="btn btn-outline-secondary" type="submit">Tìm</button>
                </div>
            </div>
            @foreach(request()->except(['parent_search', 'parent_page']) as $key => $val)
                <input type="hidden" name="{{ $key }}" value="{{ $val }}">
            @endforeach
        </form>
        <table class="table table-hover table-sm mb-0">
            <thead class="thead-light">
                <tr>
                    <th>
                        <a href="?{{ http_build_query(array_merge(request()->except('parent_page'), [
                            'parent_sort' => 'CATEGORY_ID',
                            'parent_order' => request('parent_order', 'desc') === 'asc' ? 'desc' : 'asc'
                        ])) }}">
                            ID
                            @if(request('parent_sort') === 'CATEGORY_ID')
                                <span>{{ request('parent_order', 'desc') === 'asc' ? '▲' : '▼' }}</span>
                            @endif
                        </a>
                    </th>
                    <th>
                        <a href="?{{ http_build_query(array_merge(request()->except('parent_page'), [
                            'parent_sort' => 'CATEGORY_NAME',
                            'parent_order' => request('parent_order', 'desc') === 'asc' ? 'desc' : 'asc'
                        ])) }}">
                            Tên
                            @if(request('parent_sort') === 'CATEGORY_NAME')
                                <span>{{ request('parent_order', 'desc') === 'asc' ? '▲' : '▼' }}</span>
                            @endif
                        </a>
                    </th>
                    <th>Số danh mục con</th>
                    <th width="180">Thao tác</th>
                </tr>
            </thead>
            <tbody>
                @foreach($parentCategories as $cat)
                <tr>
                    <td>{{ $cat->CATEGORY_ID }}</td>
                    <td>{{ $cat->CATEGORY_NAME }}</td>
                    <td>{{ $cat->children_count }}</td>
                    <td>
                        <form method="POST" action="{{ route('admin.categories.destroy', $cat->CATEGORY_ID) }}" style="display:inline;">
                            @csrf @method('DELETE')
                            <button class="btn btn-outline-danger btn-sm" onclick="return confirm('Xóa?')">Xóa</button>
                        </form>
                        <button class="btn btn-outline-primary btn-sm" data-toggle="modal" data-target="#editCat{{ $cat->CATEGORY_ID }}">Sửa</button>
                        <a href="?parent_id={{ $cat->CATEGORY_ID }}" class="btn btn-outline-info btn-sm">Xem danh mục con</a>
                        <div class="modal fade" id="editCat{{ $cat->CATEGORY_ID }}">
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
                                                <label>Chọn danh mục cha (tuỳ chọn)</label>
                                                <select name="PARENT_ID" class="form-control" style="max-height:200px; overflow-y:auto;">
                                                    <option value="">-- Không --</option>
                                                    @foreach($parentCategories as $p)
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
            {{ $parentCategories->appends(request()->except('parent_page'))->onEachSide(1)->links('pagination::bootstrap-4') }}
        </div>
    </div>
</div>