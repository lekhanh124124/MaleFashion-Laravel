
@extends('layouts.admin')

@section('content')
  <div class="card-kpi mb-4">
    <h5 class="mb-2"><i class="fa fa-user"></i> Thông tin cá nhân</h5>
    <div class="text-muted" style="font-size:13px;">Xem chi tiết hồ sơ quản trị</div>
  </div>

  <div class="row">
    <div class="col-md-6">
      <div class="card-kpi">
        <h6 class="mb-3">Thông tin cơ bản</h6>
        <table class="table table-sm">
          <tr><th style="width:140px;">Họ</th><td>{{ $user->LAST_NAME }}</td></tr>
          <tr><th>Tên</th><td>{{ $user->FIRST_NAME }}</td></tr>
          <tr><th>Họ & Tên</th><td>{{ $user->FULL_NAME }}</td></tr>
          <tr><th>Email</th><td>{{ $user->EMAIL }}</td></tr>
          <tr><th>Điện thoại</th><td>{{ $user->PHONE ?? '—' }}</td></tr>
          <tr><th>Địa chỉ</th><td>{{ $user->ADDRESS ?? '—' }}</td></tr>
          <tr><th>Vai trò</th><td><span class="badge badge-dark">{{ strtoupper($user->ROLE) }}</span></td></tr>
          <tr><th>Trạng thái</th>
              <td>
                @if($user->STATUS==='active')
                  <span class="badge badge-success">Active</span>
                @else
                  <span class="badge badge-secondary">Inactive</span>
                @endif
              </td>
          </tr>
          <tr><th>Ngày tạo</th><td>{{ $user->CREATED_AT ?? '—' }}</td></tr>
        </table>
      </div>
    </div>

    <div class="col-md-6">
      <div class="card-kpi">
        <h6 class="mb-3">Hành động</h6>
        <a href="{{ route('admin.change-password') }}" class="btn btn-outline-dark btn-sm">
          <i class="fa fa-lock"></i> Đổi mật khẩu
        </a>
        <form class="d-inline" action="{{ route('admin.logout') }}" method="post">
          @csrf
          <button class="btn btn-outline-danger btn-sm" type="submit">
            <i class="fa fa-sign-out"></i> Đăng xuất
          </button>
        </form>
      </div>
    </div>
  </div>
@endsection