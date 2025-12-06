@extends('layouts.malefashion')

@section('content')
    <link rel="stylesheet" href="{{ asset('assets/malefashion/css/auth.css') }}">

    <section class="breadcrumb-option">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="breadcrumb__text">
                        <h4>Đăng ký</h4>
                        <div class="breadcrumb__links">
                            <a href="{{ route('home') }}">Trang chủ</a>
                            <span>Tạo tài khoản</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="auth-section">
        <div class="container">
            <div class="auth-card" style="max-width: 650px;">
                <h3 class="auth-title">Tạo tài khoản mới</h3>

                @if (session('ok'))
                    <div class="alert alert-success text-center mb-4">{{ session('ok') }}</div>
                @endif
                @if ($errors->any())
                    <div class="alert alert-danger mb-4">
                        @foreach ($errors->all() as $e)
                            <div><i class="fa fa-exclamation-circle"></i> {{ $e }}</div>
                        @endforeach
                    </div>
                @endif

                <form method="post" action="{{ route('register.do') }}">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Họ & Tên đệm <span class="text-danger">*</span></label>
                                <input name="LAST_NAME" class="form-control" value="{{ old('LAST_NAME') }}" required placeholder="Nguyễn Văn">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Tên <span class="text-danger">*</span></label>
                                <input name="FIRST_NAME" class="form-control" value="{{ old('FIRST_NAME') }}" required placeholder="A">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Địa chỉ Email <span class="text-danger">*</span></label>
                        <input type="email" name="EMAIL" class="form-control" value="{{ old('EMAIL') }}" required placeholder="email@example.com">
                    </div>

                    <div class="form-group">
                        <label>Số điện thoại <span class="text-danger">*</span></label>
                        <input name="PHONE" class="form-control" value="{{ old('PHONE') }}" required placeholder="09xxxxxxxxx">
                    </div>

                    <div class="form-group">
                        <label>Địa chỉ nhận hàng</label>
                        <input name="ADDRESS" class="form-control" value="{{ old('ADDRESS') }}" placeholder="Số nhà, Đường, Quận/Huyện...">
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Mật khẩu <span class="text-danger">*</span></label>
                                <input type="password" name="PASSWORD" class="form-control" required minlength="6">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Xác nhận mật khẩu <span class="text-danger">*</span></label>
                                <input type="password" name="PASSWORD_confirmation" class="form-control" required minlength="6">
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="site-btn mt-3">Đăng ký ngay</button>
                </form>

                <div class="auth-alt">
                    Đã có tài khoản? <a href="{{ route('login') }}">Đăng nhập tại đây</a>
                </div>
            </div>
        </div>
    </section>
@endsection