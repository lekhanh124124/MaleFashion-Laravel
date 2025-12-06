<div class="card shadow-sm">
    <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
        <span><b>Tag</b></span>
        <button class="btn btn-sm btn-light" data-toggle="collapse" data-target="#addTagForm">+ Thêm</button>
    </div>
    <div class="collapse" id="addTagForm">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.tags.store') }}">
                @csrf
                <div class="form-group mb-2">
                    <label>Tên tag</label>
                    <input name="TAG_NAME" class="form-control" required>
                </div>
                <button class="btn btn-dark btn-sm">Thêm</button>
            </form>
        </div>
    </div>
    <div class="card-body p-0">
        <!-- Search form -->
        <form method="GET" class="p-2">
            <div class="input-group input-group-sm mb-2">
                <input type="text" name="tag_search" class="form-control" placeholder="Tìm kiếm tag..." value="{{ request('tag_search') }}">
                <div class="input-group-append">
                    <button class="btn btn-outline-secondary" type="submit">Tìm</button>
                </div>
            </div>
            @foreach(request()->except(['tag_search', 'tags_page']) as $key => $val)
                <input type="hidden" name="{{ $key }}" value="{{ $val }}">
            @endforeach
        </form>
        <table class="table table-hover table-sm mb-0">
            <thead class="thead-light">
                <tr>
                    <th>
                        <a href="?{{ http_build_query(array_merge(request()->except('tags_page'), [
                            'tag_sort' => 'TAG_ID',
                            'tag_order' => request('tag_order', 'desc') === 'asc' ? 'desc' : 'asc'
                        ])) }}">
                            ID
                            @if(request('tag_sort') === 'TAG_ID')
                                <span>{{ request('tag_order', 'desc') === 'asc' ? '▲' : '▼' }}</span>
                            @endif
                        </a>
                    </th>
                    <th>
                        <a href="?{{ http_build_query(array_merge(request()->except('tags_page'), [
                            'tag_sort' => 'TAG_NAME',
                            'tag_order' => request('tag_order', 'desc') === 'asc' ? 'desc' : 'asc'
                        ])) }}">
                            Tên
                            @if(request('tag_sort') === 'TAG_NAME')
                                <span>{{ request('tag_order', 'desc') === 'asc' ? '▲' : '▼' }}</span>
                            @endif
                        </a>
                    </th>
                    <th class="text-right">Hành động</th>
                </tr>
            </thead>
            <tbody>
                @foreach($tags as $tag)
                <tr>
                    <td>{{ $tag->TAG_ID }}</td>
                    <td>{{ $tag->TAG_NAME }}</td>
                    <td class="text-right">
                        <form class="d-inline" method="POST" action="{{ route('admin.tags.destroy', $tag->TAG_ID) }}">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger" type="submit">Xóa</button>
                        </form>
                        <button class="btn btn-sm btn-outline-primary" type="button" onclick="editTag({{ $tag->TAG_ID }}, '{{ $tag->TAG_NAME }}')">Sửa</button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="pt-2">{{ $tags->onEachSide(1)->links('pagination::bootstrap-4') }}</div>
    </div>
</div>