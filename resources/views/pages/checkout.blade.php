@extends('layouts.malefashion')

@section('title', 'Check Out')

@section('content')
    @include('partials.checkout.breadcrumb')

    <section class="checkout spad">
        <div class="container">
            <div class="checkout__form">
                
                <!-- 1. HIỂN THỊ THÔNG BÁO LỖI LOGIC (Ví dụ: Giỏ hàng trống, lỗi server...) -->
                @if(session('error'))
                    <div class="alert alert-danger" style="margin-bottom: 20px;">
                        <i class="fa fa-exclamation-triangle"></i> {{ session('error') }}
                    </div>
                @endif

                <!-- 2. HIỂN THỊ THÔNG BÁO THÀNH CÔNG -->
                @if(session('success'))
                    <div class="alert alert-success" style="margin-bottom: 20px;">
                        <i class="fa fa-check"></i> {{ session('success') }}
                    </div>
                @endif

                <!-- 3. HIỂN THỊ LỖI VALIDATION (Quan trọng: Để biết nhập thiếu trường nào) -->
                @if ($errors->any())
                    <div class="alert alert-danger" style="margin-bottom: 20px;">
                        <strong>Vui lòng kiểm tra lại thông tin:</strong>
                        <ul style="margin-bottom: 0; padding-left: 20px; margin-top: 5px;">
                            @foreach ($errors->all() as $error)
                                <li>- {{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- 4. FORM CHÍNH -->
                <!-- Sửa action="#" thành route('shop.place-order') và thêm method POST -->
                <form action="{{ route('shop.place-order') }}" method="POST">
                    @csrf <!-- Bắt buộc phải có -->
                    
                    <div class="row">
                        <!-- Bên trái: Nhập thông tin -->
                        @include('partials.checkout.billing')
                        
                        <!-- Bên phải: Xem lại đơn hàng & Nút đặt hàng -->
                        @include('partials.checkout.order')
                    </div>
                </form>

            </div>
        </div>
    </section>
@endsection