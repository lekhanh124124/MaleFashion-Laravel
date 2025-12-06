@extends('layouts.admin')

@section('content')
@php
  // Khởi tạo mặc định nếu chưa được truyền từ Controller
  $q = $q ?? request('q') ?? '';
  // $users có thể là Paginator hoặc Collection; nếu chưa có thì dùng collection rỗng
  $users = $users ?? collect();
@endphp
<div id="pane-staff" class="mt-2">
  @if(session('ok'))<div class="alert alert-success py-1 mb-2">{{ session('ok') }}</div>@endif
  @if(session('err'))<div class="alert alert-danger py-1 mb-2">{{ session('err') }}</div>@endif
  <div class="sticky-toolbar d-flex align-items-center">
    <h5 class="mb-0 mr-auto">Quản trị viên</h5>
    <button class="btn btn-dark" data-toggle="modal" data-target="#modalUser" id="btnCreateUser">
      <i class="fa fa-plus"></i> Thêm
    </button>
    <form class="ml-2" method="get" action="{{ route('admin.staff.index') }}">
      <input name="q" value="{{ $q }}" class="form-control" placeholder="Tìm tên / email / phone">
    </form>
  </div>
  <div class="legend-box mt-2"><strong>Chú giải:</strong> ROLE: admin / staff; STATUS: active / inactive.</div>
  <div class="table-responsive mt-3">
    <table class="table table-hover">
      <thead class="thead-light">
        <tr>
          <th>ID</th><th>Họ tên</th><th>Email</th><th>Phone</th>
          <th>Role</th><th>Trạng thái</th><th>Ngày tạo</th>
          <th class="text-right">Hành động</th>
        </tr>
      </thead>
      <tbody>
        @forelse($users as $u)
        <tr>
          <td>{{ $u->USER_ID }}</td>
          <td>{{ $u->FIRST_NAME }} {{ $u->LAST_NAME }}</td>
          <td>{{ $u->EMAIL }}</td>
          <td>{{ $u->PHONE }}</td>
          <td><span class="badge badge-{{ $u->ROLE==='admin'?'dark':'info' }}">{{ $u->ROLE }}</span></td>
          <td>
            <form method="post" action="{{ route('admin.staff.toggle',$u) }}">
              @csrf
              <button class="btn btn-sm {{ $u->STATUS==='active'?'btn-success':'btn-secondary' }}" title="Đổi trạng thái">
                {{ $u->STATUS }}
              </button>
            </form>
          </td>
          <td>{{ $u->CREATED_AT }}</td>
          <td class="text-right">
            <button class="btn btn-sm btn-outline-primary btn-edit-user"
              data-toggle="modal" data-target="#modalUser"
              data-user='@json($u)'>Sửa</button>
          </td>
        </tr>
        @empty
        <tr><td colspan="8" class="text-center text-muted">Không có dữ liệu</td></tr>
        @endforelse
      </tbody>
    </table>
    @if(method_exists($users,'links'))
      <div class="pb-2 px-2">{{ $users->links() }}</div>
    @endif
  </div>
</div>

@include('admin.partials.users.modal-user')
@endsection

@push('scripts')
<script>
(function(){
  const modal = document.getElementById('modalUser');
  const form  = modal.querySelector('form');
  const title = modal.querySelector('[data-modal-title]');
  const subtitle = modal.querySelector('.modal-subtitle');
  const passwordGroup = form.querySelector('[data-password-group]');
  function fill(u){
    form.reset();
    form.querySelector('[name=FIRST_NAME]').value = u.FIRST_NAME || '';
    form.querySelector('[name=LAST_NAME]').value  = u.LAST_NAME || '';
    form.querySelector('[name=EMAIL]').value      = u.EMAIL || '';
    form.querySelector('[name=PHONE]').value      = u.PHONE || '';
    form.querySelector('[name=ADDRESS]').value    = u.ADDRESS || '';
    form.querySelector('[name=ROLE]').value       = u.ROLE || 'staff';
    form.querySelector('[name=STATUS]').value     = u.STATUS || 'active';
  }
  document.getElementById('btnCreateUser').addEventListener('click', function(){
    fill({});
    form.action = "{{ route('admin.staff.store') }}";
    form.querySelector('input[name=_method]')?.remove();
    passwordGroup.style.display='none';
    title.textContent='Thêm người dùng';
    subtitle.textContent='Mật khẩu mặc định: 123456789';
  });
  document.querySelectorAll('.btn-edit-user').forEach(btn=>{
    btn.addEventListener('click',()=>{
      const u = JSON.parse(btn.getAttribute('data-user'));
      fill(u);
      form.action = "{{ url('admin/staff') }}/"+u.USER_ID;
      if(!form.querySelector('input[name=_method]')){
        const m = document.createElement('input');
        m.type='hidden'; m.name='_method'; m.value='PUT';
        form.appendChild(m);
      }
      passwordGroup.style.display='block';
      title.textContent='Sửa người dùng';
      subtitle.textContent='Chỉnh sửa #' + u.USER_ID;
    });
  });
})();
</script>
@endpush