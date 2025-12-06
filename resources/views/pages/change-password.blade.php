
@extends('layouts.malefashion')

@section('content')
<section class="spad">
  <div class="container">
    <h3>Đổi mật khẩu</h3>
    @if(session('ok'))<div class="alert alert-success">{{ session('ok') }}</div>@endif
    @if($errors->any())
      <div class="alert alert-danger py-2">
        @foreach($errors->all() as $e)<div class="small">{{ $e }}</div>@endforeach
      </div>
    @endif
    <form method="post" action="{{ route('customer.password.update') }}" class="mt-3 col-md-6 px-0">
      @csrf
      <div class="form-group">
        <label>Mật khẩu hiện tại</label>
        <input type="password" name="current_password" class="form-control" required minlength="6">
      </div>
      <div class="form-group">
        <label>Mật khẩu mới</label>
        <input type="password" name="new_password" class="form-control" required minlength="6">
      </div>
      <div class="form-group">
        <label>Xác nhận mật khẩu mới</label>
        <input type="password" name="new_password_confirmation" class="form-control" required minlength="6">
      </div>
      <button class="site-btn">Lưu</button>
      <a href="{{ route('customer.profile') }}" class="btn btn-link">Hủy</a>
    </form>
  </div>
</section>
@endsection