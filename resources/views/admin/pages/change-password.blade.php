
@extends('layouts.admin')

@section('content')
  <div class="card-kpi mb-4">
    <h5 class="mb-2"><i class="fa fa-lock"></i> Đổi mật khẩu</h5>
    <div class="text-muted" style="font-size:13px;">Nhập mật khẩu hiện tại và mật khẩu mới.</div>
  </div>

  @if(session('ok'))<div class="alert alert-success py-1 mb-3">{{ session('ok') }}</div>@endif
  @if($errors->any())
    <div class="alert alert-danger py-1 mb-3">
      @foreach($errors->all() as $e)<div>{{ $e }}</div>@endforeach
    </div>
  @endif

  <form id="changePassForm" method="post" action="{{ route('admin.change-password.update') }}">
    @csrf
    <div class="form-group">
      <label>Mật khẩu hiện tại</label>
      <input type="password" class="form-control" name="current_password" required minlength="6">
    </div>
    <div class="form-group">
      <label>Mật khẩu mới</label>
      <input type="password" class="form-control" name="new_password" required minlength="6">
    </div>
    <div class="form-group">
      <label>Nhập lại mật khẩu mới</label>
      <input type="password" class="form-control" name="new_password_confirmation" required minlength="6">
    </div>
    <button class="btn btn-dark"><i class="fa fa-save"></i> Cập nhật</button>
  </form>
@endsection