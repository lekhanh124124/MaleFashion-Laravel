@extends('layouts.admin')

@section('content')
@include('admin.partials.contacts.modal-contact')

<div id="pane-contacts" class="mt-2">
  @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif

  <div class="sticky-toolbar d-flex flex-wrap align-items-center gap-2">
    <h5 class="mb-0 mr-auto">Liên hệ</h5>
    
    {{-- Form lọc và tìm kiếm --}}
    <form method="GET" class="d-flex gap-2">
        <select class="form-control w-auto mr-2" name="status" onchange="this.form.submit()">
          <option value="">Tất cả trạng thái</option>
          <option value="new" {{ request('status') == 'new' ? 'selected' : '' }}>New</option>
          <option value="read" {{ request('status') == 'read' ? 'selected' : '' }}>Read</option>
          <option value="replied" {{ request('status') == 'replied' ? 'selected' : '' }}>Replied</option>
        </select>
        <div class="input-group">
            <input class="form-control" name="search" value="{{ request('search') }}" placeholder="Tìm tên / email...">
            <div class="input-group-append">
                <button class="btn btn-outline-secondary"><i class="fa fa-search"></i></button>
            </div>
        </div>
    </form>
  </div>

  <div class="table-responsive mt-3">
    <table class="table table-striped table-hover" id="contacts-table">
      <thead class="thead-light">
        <tr>
            <th>ID</th>
            <th>Người gửi</th>
            <th>Thành viên (User)</th>
            <th width="30%">Nội dung</th>
            <th>Trạng thái</th>
            <th>Ngày gửi</th>
            <th class="text-right">Thao tác</th>
        </tr>
      </thead>
      <tbody>
        @forelse($contacts as $c)
        <tr>
          <td>{{ $c->CONTACT_ID }}</td>
          <td>
            <strong>{{ $c->NAME }}</strong><br>
            <span class="text-muted small">{{ $c->EMAIL }}</span>
          </td>
          <td>
            @if($c->user)
                <strong>[{{ $c->USER_ID }}]</strong> {{ $c->user->name ?? 'User' }}
            @else
                <span class="badge badge-light">Khách vãng lai</span>
            @endif
          </td>
          <td>
            <div class="text-truncate" style="max-width: 300px;" title="{{ $c->MESSAGE }}">
                {{ $c->MESSAGE }}
            </div>
          </td>
          <td>
             @if($c->STATUS == 'new') 
                <span class="badge badge-info">Mới</span>
             @elseif($c->STATUS == 'read') 
                <span class="badge badge-warning">Đã xem</span>
             @elseif($c->STATUS == 'replied') 
                <span class="badge badge-success">Đã trả lời</span>
             @endif
          </td>
          <td>{{ \Carbon\Carbon::parse($c->CREATED_AT)->format('d/m/Y H:i') }}</td>
          <td class="text-right">
            <button class="btn btn-sm btn-outline-primary btn-view-contact" data-id="{{ $c->CONTACT_ID }}">
                Xem / Xử lý
            </button>
            
            <form action="{{ route('admin.contacts.destroy', $c->CONTACT_ID) }}" method="POST" style="display:inline-block" onsubmit="return confirm('Xóa tin nhắn này?')">
                @csrf @method('DELETE')
                <button class="btn btn-sm btn-outline-danger">Xóa</button>
            </form>
          </td>
        </tr>
        @empty
        <tr>
            <td colspan="7" class="text-center text-muted">Không tìm thấy liên hệ nào.</td>
        </tr>
        @endforelse
      </tbody>
    </table>
    
    <div class="mt-2">
        {{ $contacts->withQueryString()->links() }}
    </div>
  </div>
</div>

@endsection

@section('scripts')
<script>
    $(document).on('click', '.btn-view-contact', function() {
        let id = $(this).data('id');
        let url = "{{ route('admin.contacts.show', ':id') }}".replace(':id', id);
        
        // Reset modal
        $('#mc-id').val('Loading...');
        $('#mc-reply').val(''); 
        $('#modalContact').modal('show');

        // Gọi AJAX lấy chi tiết
        $.get(url, function(data) {
            $('#mc-id').val(data.CONTACT_ID);
            $('#mc-user').val(data.USER_ID ? `[${data.USER_ID}]` : 'Guest');
            $('#mc-created').val(data.CREATED_AT);
            $('#mc-name').val(data.NAME);
            $('#mc-email').val(data.EMAIL);
            $('#mc-message').val(data.MESSAGE);
            $('#mc-status').val(data.STATUS);
            
            // Lưu ID vào nút save để dùng khi update
            $('#btn-save-status').data('id', data.CONTACT_ID);
        });
    });

    // Xử lý nút Lưu trạng thái
    $('#btn-save-status').click(function() {
        let id = $(this).data('id');
        let status = $('#mc-status').val();
        let url = "{{ route('admin.contacts.update', ':id') }}".replace(':id', id);
        let btn = $(this);

        btn.prop('disabled', true).text('Đang lưu...');

        $.ajax({
            url: url,
            type: 'PUT',
            data: {
                STATUS: status,
                _token: "{{ csrf_token() }}"
            },
            success: function(res) {
                alert(res.message);
                location.reload(); // Reload trang để cập nhật bảng
            },
            error: function() {
                alert('Có lỗi xảy ra');
                btn.prop('disabled', false).text('Lưu trạng thái');
            }
        });
    });
    
    // Nút Gửi phản hồi (Mock)
    $('#btn-send-reply').click(function() {
        let reply = $('#mc-reply').val();
        if(!reply) {
            alert('Vui lòng nhập nội dung phản hồi');
            return;
        }
        // Ở đây bạn có thể gọi API gửi mail thật nếu có.
        // Tạm thời chỉ giả lập:
        alert('Đã gửi email phản hồi đến khách hàng: ' + $('#mc-email').val());
        
        // Tự động chuyển status sang replied
        $('#mc-status').val('replied');
        $('#btn-save-status').click(); 
    });
</script>
@endsection