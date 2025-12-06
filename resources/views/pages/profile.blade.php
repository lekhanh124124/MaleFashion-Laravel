
@extends('layouts.malefashion')

@section('content')
<section class="spad">
  <div class="container">
    <h3>Thông tin cá nhân</h3>
    @if(session('ok'))<div class="alert alert-success">{{ session('ok') }}</div>@endif
    <div class="card mt-3">
      <div class="card-body">
        <p><strong>ID:</strong> {{ $user->USER_ID }}</p>
        <p><strong>Họ tên:</strong> {{ $user->FULL_NAME }}</p>
        <p><strong>Email:</strong> {{ $user->EMAIL }}</p>
        <p><strong>Phone:</strong> {{ $user->PHONE }}</p>
        <p><strong>Địa chỉ:</strong> {{ $user->ADDRESS ?? '—' }}</p>
        <p><strong>Vai trò:</strong> {{ $user->ROLE }}</p>
        <p><strong>Trạng thái:</strong> {{ $user->STATUS }}</p>
        <a class="site-btn" href="{{ route('customer.password') }}">Đổi mật khẩu</a>
      </div>
    </div>
  </div>
</section>
@endsection