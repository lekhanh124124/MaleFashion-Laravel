@extends('layouts.malefashion')

@section('content')
    <link rel="stylesheet" href="{{ asset('assets/malefashion/css/auth.css') }}">

    <section class="breadcrumb-option">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="breadcrumb__text">
                        <h4>Đăng nhập</h4>
                        <div class="breadcrumb__links">
                            <a href="{{ route('home') }}">Trang chủ</a>
                            <span>Đăng nhập</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="auth-section">
        <div class="container">
            <div class="auth-card">
                <h3 class="auth-title">Chào mừng trở lại</h3>

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

                <form method="post" action="{{ route('login.do') }}">
                    @csrf
                    <div class="form-group">
                        <label>Email đăng nhập</label>
                        <input type="email" name="EMAIL" class="form-control" value="{{ old('EMAIL') }}" 
                               placeholder="customer@example.com" required>
                    </div>
                    <div class="form-group">
                        <label>Mật khẩu</label>
                        <input type="password" name="PASSWORD" class="form-control" 
                               placeholder="Nhập mật khẩu..." required minlength="6">
                    </div>
                    <button type="submit" class="site-btn">Đăng nhập ngay</button>
                </form>

                <div class="auth-alt">
                    Chưa có tài khoản? <a href="{{ route('register') }}">Đăng ký ngay</a>
                </div>
            </div>
        </div>
    </section>
    @endsection