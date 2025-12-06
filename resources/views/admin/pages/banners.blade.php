@extends('layouts.admin')

@section('content')
<div id="pane-banners" class="mt-2">
  @if(session('success'))
  <div class="alert alert-success">{{ session('success') }}</div>
  @endif

  <div class="sticky-toolbar d-flex align-items-center">
    <h5 class="mb-0 mr-auto">Banner</h5>
    <button class="btn btn-dark" onclick="openAddModal()"><i class="fa fa-plus"></i> Thêm Banner</button>
  </div>

  <div class="legend-box mt-2">
    <strong>Chú giải:</strong> POSITION ví dụ: <code>home.hero</code>, <code>shop.sidebar</code>.
  </div>

  <div class="table-responsive mt-3">
    <table class="table table-striped table-hover align-middle">
      <thead class="thead-light">
        <tr>
          <th>ID</th>
          <th>Hình ảnh</th>
          <th>Thông tin</th>
          <th>Vị trí / Thứ tự</th>
          <th>Trạng thái</th>
          <th class="text-right">Thao tác</th>
        </tr>
      </thead>
      <tbody>
        @foreach($banners as $b)
        <tr>
          <td>{{ $b->BANNER_ID }}</td>
          <td>
            <img src="{{ $b->IMAGE_URL }}" alt="Banner" style="height: 60px; width: auto; object-fit: cover; border-radius: 4px; border: 1px solid #ddd;">
          </td>
          <td>
            <div class="font-weight-bold">{{ $b->TITLE }}</div>
            <div class="small text-muted">{{ $b->SUBTITLE }}</div>
            @if($b->LINK_URL) <div class="small text-primary"><i class="fa fa-link"></i> {{ Str::limit($b->LINK_URL, 30) }}</div> @endif
            @if($b->category) <div class="small text-info"><i class="fa fa-folder"></i> {{ $b->category->CATEGORY_NAME }}</div> @endif
          </td>
          <td>
            <span class="badge badge-light border">{{ $b->POSITION }}</span>
            <div class="small mt-1">Order: <strong>{{ $b->DISPLAY_ORDER }}</strong></div>
          </td>
          <td>
            @if($b->IS_ACTIVE)
            <span class="badge badge-success">Hiển thị</span>
            @else
            <span class="badge badge-secondary">Ẩn</span>
            @endif
          </td>
          <td class="text-right">
            <button class="btn btn-sm btn-outline-primary btn-edit"
              data-id="{{ $b->BANNER_ID }}"
              data-title="{{ $b->TITLE }}"
              data-subtitle="{{ $b->SUBTITLE }}"
              data-link="{{ $b->LINK_URL }}"
              data-cat="{{ $b->CATEGORY_ID }}"
              data-pos="{{ $b->POSITION }}"
              data-order="{{ $b->DISPLAY_ORDER }}"
              data-active="{{ $b->IS_ACTIVE }}">Sửa</button>

            <form action="{{ route('admin.banners.destroy', $b->BANNER_ID) }}" method="POST" style="display:inline-block" onsubmit="return confirm('Xóa banner này?')">
              @csrf @method('DELETE')
              <button class="btn btn-sm btn-outline-danger">Xóa</button>
            </form>
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>
</div>

@include('admin.partials.banners.modal-banner')
@endsection

@section('scripts')
<script>
  function openAddModal() {
    $('#formBanner').attr('action', "{{ route('admin.banners.store') }}");
    $('#method_spoof').val(''); // POST
    $('#modalTitle').text('Thêm Banner Mới');
    $('#formBanner')[0].reset();

    // Mặc định active
    $('#inputActive').val(1);
    $('#modalBanner').modal('show');
  }

  $('.btn-edit').click(function() {
    let id = $(this).data('id');
    let url = "{{ route('admin.banners.update', ':id') }}".replace(':id', id);

    $('#formBanner').attr('action', url);
    $('#method_spoof').val('PUT'); // PUT
    $('#modalTitle').text('Cập nhật Banner #' + id);

    // Fill data
    $('#inputTitle').val($(this).data('title'));
    $('#inputSubtitle').val($(this).data('subtitle'));
    $('#inputLink').val($(this).data('link'));
    $('#inputCat').val($(this).data('cat'));
    $('#inputPos').val($(this).data('pos'));
    $('#inputOrder').val($(this).data('order'));
    $('#inputActive').val($(this).data('active'));

    $('#modalBanner').modal('show');
  });
</script>
@endsection