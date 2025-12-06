<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="x-ua-compatible" content="ie=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Admin Login | Malefashion</title>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('assets/malefashion/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #fff;
            height: 100vh;
            overflow: hidden;
        }

        .login-wrapper {
            display: flex;
            height: 100%;
            width: 100%;
        }

        /* --- Left Side (Brand/Decorative) --- */
        .login-sidebar {
            width: 45%;
            background-color: #1e1e2d; /* Màu giống Sidebar Admin */
            color: #fff;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 60px;
            position: relative;
            background-image: url('https://images.unsplash.com/photo-1441986300917-64674bd600d8?q=80&w=2070&auto=format&fit=crop');
            background-size: cover;
            background-position: center;
        }

        /* Lớp phủ tối để chữ dễ đọc hơn trên nền ảnh */
        .login-sidebar::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background: linear-gradient(135deg, rgba(30, 30, 45, 0.95) 0%, rgba(30, 30, 45, 0.8) 100%);
            z-index: 1;
        }

        .sidebar-content {
            position: relative;
            z-index: 2;
        }

        .sidebar-content h1 { font-weight: 700; font-size: 3rem; margin-bottom: 20px; }
        .sidebar-content p { font-size: 1.1rem; opacity: 0.7; line-height: 1.6; max-width: 80%; }

        /* --- Right Side (Form) --- */
        .login-main {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px;
            background: #f8f9fa;
        }

        .login-form-container {
            width: 100%;
            max-width: 420px;
            background: #fff;
            padding: 40px;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.04);
        }

        .brand-logo {
            font-size: 24px;
            font-weight: 800;
            color: #1e1e2d;
            margin-bottom: 10px;
            display: block;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .login-title { font-size: 20px; font-weight: 600; color: #333; margin-bottom: 30px; }
        .text-muted-small { font-size: 13px; color: #888; }

        /* Form Elements */
        .form-group { margin-bottom: 20px; }
        .form-label { font-size: 13px; font-weight: 500; color: #555; margin-bottom: 8px; display: block; }
        
        .input-group-custom { position: relative; }
        .input-group-custom input {
            width: 100%;
            height: 48px;
            padding: 10px 15px 10px 45px; /* padding-left cho icon */
            border: 1px solid #e1e1e1;
            border-radius: 8px;
            font-size: 14px;
            transition: all 0.2s;
            background: #fcfcfc;
        }
        .input-group-custom input:focus {
            border-color: #0d6efd;
            background: #fff;
            box-shadow: 0 0 0 4px rgba(13, 110, 253, 0.1);
            outline: none;
        }
        .input-icon {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #aaa;
            font-size: 16px;
        }

        .btn-login {
            width: 100%;
            height: 48px;
            background: #1e1e2d;
            color: #fff;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.2s;
            margin-top: 10px;
        }
        .btn-login:hover { background: #000; transform: translateY(-1px); }
        .btn-login:active { transform: translateY(0); }
        .btn-login:disabled { background: #ccc; cursor: not-allowed; }

        .back-link { display: block; text-align: center; margin-top: 25px; font-size: 13px; color: #666; text-decoration: none; }
        .back-link:hover { color: #0d6efd; }

        /* Responsive */
        @media (max-width: 991px) {
            .login-sidebar { display: none; }
            .login-main { background: #fff; }
            .login-form-container { box-shadow: none; padding: 20px; }
        }
    </style>
</head>

<body>

    <div class="login-wrapper">
        <div class="login-sidebar">
            <div class="sidebar-content">
                <h1>Malefashion<span style="color: #e53637">.</span></h1>
                <p>Hệ thống quản trị dành riêng cho cửa hàng thời trang nam. Quản lý sản phẩm, đơn hàng và khách hàng một cách hiệu quả.</p>
                <div style="margin-top: 40px; font-size: 12px; opacity: 0.5;">&copy; 2025 Malefashion Admin Panel</div>
            </div>
        </div>

        <div class="login-main">
            <div class="login-form-container">
                <div class="mb-4">
                    <span class="brand-logo">Admin Portal</span>
                    <div class="text-muted-small">Vui lòng đăng nhập để tiếp tục</div>
                </div>

                <div id="login-error" class="alert alert-danger text-center small p-2 mb-3" style="display:none;"></div>
                
                <div id="login-success" class="alert alert-success text-center small p-2 mb-3" style="display:none;">
                    <i class="fa fa-check-circle"></i> Đăng nhập thành công!
                </div>

                <form id="admin-login-form" action="{{ route('admin.login.do') }}" method="post">
                    @csrf
                    <div class="form-group">
                        <label class="form-label">Địa chỉ Email</label>
                        <div class="input-group-custom">
                            <i class="fa fa-envelope input-icon"></i>
                            <input type="email" name="EMAIL" placeholder="admin@example.com" required value="admin@gmail.com">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Mật khẩu</label>
                        <div class="input-group-custom">
                            <i class="fa fa-lock input-icon"></i>
                            <input type="password" name="PASSWORD" placeholder="Nhập mật khẩu..." required>
                        </div>
                    </div>

                    <button type="submit" class="btn-login" id="login-btn">
                        <span>Đăng nhập</span> <i class="fa fa-arrow-right ms-2" style="font-size:12px;"></i>
                    </button>
                </form>

                <a href="{{ route('home') }}" class="back-link">
                    <i class="fa fa-long-arrow-left"></i> Quay về trang chủ cửa hàng
                </a>
            </div>
        </div>
    </div>

    <script src="{{ asset('assets/malefashion/js/jquery-3.3.1.min.js') }}"></script>
    <script src="{{ asset('assets/malefashion/js/bootstrap.min.js') }}"></script>

    <script>
        // Clear thông tin user cũ nếu có session flash
        @if(session('ok'))
            try { localStorage.removeItem('adminUser'); } catch(e){}
        @endif

        $('#admin-login-form').on('submit', function(e) {
            e.preventDefault();
            let btn = $('#login-btn');
            let originalText = btn.html();

            // UI Loading state
            btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Đang xử lý...');
            $('#login-error').hide();

            $.ajax({
                url: $(this).attr('action'),
                method: 'POST',
                data: $(this).serialize(),
                success: function(res) {
                    // Lưu info sơ bộ vào localStorage (để hiển thị avatar ở trang kia)
                    if(res.user) {
                        localStorage.setItem('adminUser', JSON.stringify(res.user));
                    }
                    
                    // Hiện thông báo success
                    $('#login-success').slideDown();
                    
                    // Chuyển hướng sau 1s
                    setTimeout(() => {
                        window.location.href = "{{ route('admin.index') }}";
                    }, 800);
                },
                error: function(xhr) {
                    let msg = 'Thông tin đăng nhập không chính xác.';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        msg = xhr.responseJSON.message;
                    } else if (xhr.status === 419) {
                        msg = 'Phiên làm việc hết hạn, vui lòng tải lại trang.';
                    }
                    
                    $('#login-error').html('<i class="fa fa-exclamation-circle"></i> ' + msg).slideDown();
                    
                    // Reset button
                    btn.prop('disabled', false).html(originalText);
                }
            });
        });
    </script>
</body>
</html>