<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="x-ua-compatible" content="ie=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Admin Panel | Malefashion</title>

    <!-- Google Font (Dùng Inter cho hiện đại) -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- CSS Assets -->
    <link rel="stylesheet" href="{{ asset('assets/malefashion/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"> 
    {{-- Dùng FontAwesome bản mới hơn qua CDN để có nhiều icon đẹp hơn, nếu không có internet thì dùng bản cũ trong assets --}}
    {{-- <link rel="stylesheet" href="{{ asset('assets/malefashion/css/font-awesome.min.css') }}"> --}}
    
    <link rel="stylesheet" href="{{ asset('assets/malefashion/css/nice-select.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/malefashion/css/style.css') }}">

    <style>
        :root {
            --sidebar-width: 260px;
            --header-height: 70px;
            --primary-color: #0d6efd;
            --sidebar-bg: #1e1e2d;
            --sidebar-color: #a2a3b7;
            --sidebar-active-bg: #2b2b40;
            --sidebar-active-color: #ffffff;
            --body-bg: #f5f8fa;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--body-bg);
            font-size: 14px;
            color: #3f4254;
        }

        /* --- SIDEBAR --- */
        .admin-sidebar {
            width: var(--sidebar-width);
            height: 100vh;
            background-color: var(--sidebar-bg);
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1000;
            display: flex;
            flex-direction: column;
            transition: all 0.3s;
        }

        .sidebar-brand {
            height: var(--header-height);
            display: flex;
            align-items: center;
            padding: 0 25px;
            color: #fff;
            font-size: 20px;
            font-weight: 700;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
            background: #1a1a27;
        }

        .sidebar-brand i { color: var(--primary-color); margin-right: 10px; }

        .sidebar-menu {
            flex: 1;
            overflow-y: auto;
            padding: 20px 0;
        }

        .sidebar-menu::-webkit-scrollbar { width: 5px; }
        .sidebar-menu::-webkit-scrollbar-thumb { background: #333; border-radius: 5px; }

        .menu-header {
            padding: 15px 25px 10px;
            font-size: 11px;
            text-transform: uppercase;
            color: #585968;
            font-weight: 600;
            letter-spacing: 0.5px;
        }

        .nav-item .nav-link {
            color: var(--sidebar-color);
            padding: 12px 25px;
            display: flex;
            align-items: center;
            font-weight: 500;
            transition: all 0.2s;
            border-left: 3px solid transparent;
        }

        .nav-item .nav-link:hover {
            color: #fff;
            background-color: rgba(255,255,255,0.03);
        }

        .nav-item .nav-link.active {
            color: var(--sidebar-active-color);
            background-color: var(--sidebar-active-bg);
            border-left-color: var(--primary-color);
        }

        .nav-item .nav-link i {
            width: 25px;
            font-size: 16px;
            margin-right: 5px;
            opacity: 0.8;
        }

        /* --- MAIN CONTENT --- */
        .admin-main {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* --- HEADER --- */
        .admin-header {
            height: var(--header-height);
            background: #fff;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 30px;
            box-shadow: 0 2px 20px rgba(0,0,0,0.04);
            position: sticky;
            top: 0;
            z-index: 999;
        }

        .header-left h4 { margin: 0; font-size: 18px; font-weight: 600; }

        /* User Profile Dropdown */
        .user-profile {
            display: flex;
            align-items: center;
            cursor: pointer;
            position: relative;
        }
        
        .user-avatar {
            width: 38px;
            height: 38px;
            border-radius: 6px;
            background: #e1f0ff;
            color: #009ef7;
            font-weight: 600;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 10px;
        }

        .user-info { display: flex; flex-direction: column; line-height: 1.2; margin-right: 10px;}
        .user-name { font-weight: 600; color: #333; }
        .user-role { font-size: 11px; color: #888; text-transform: uppercase;}

        .dropdown-menu-custom {
            position: absolute;
            top: 100%;
            right: 0;
            width: 200px;
            background: #fff;
            border: 1px solid #f0f0f0;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            border-radius: 8px;
            padding: 10px 0;
            display: none;
            margin-top: 10px;
            z-index: 100;
        }
        
        .dropdown-menu-custom.show { display: block; animation: fadeIn 0.2s; }
        .dropdown-item { padding: 8px 20px; color: #555; display: flex; align-items: center; transition: .2s;}
        .dropdown-item:hover { background: #f8f9fa; color: var(--primary-color); }
        .dropdown-item i { width: 20px; margin-right: 10px; }
        .dropdown-divider { border-top: 1px solid #f0f0f0; margin: 5px 0; }

        /* --- CONTENT AREA --- */
        .content-wrapper {
            padding: 30px;
            flex: 1;
        }

        /* --- CUSTOM COMPONENTS UI --- */
        .card-kpi {
            background: #fff;
            border: none;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.02);
            transition: transform 0.2s;
        }
        .card-kpi:hover { transform: translateY(-3px); box-shadow: 0 10px 20px rgba(0,0,0,0.05); }

        .sticky-toolbar {
            background: #fff;
            padding: 15px 20px;
            border-radius: 10px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.03);
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        /* Table Styles */
        .table-responsive {
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.03);
            padding: 20px;
        }
        
        .table thead th {
            border-top: none;
            border-bottom: 1px solid #eff2f5;
            text-transform: uppercase;
            font-size: 11px;
            font-weight: 600;
            color: #b5b5c3;
            padding: 12px 10px;
        }
        
        .table td {
            vertical-align: middle;
            padding: 12px 10px;
            border-top: 1px solid #eff2f5;
            font-weight: 500;
        }

        /* Badges & Labels */
        .status-badge { padding: 5px 10px; border-radius: 6px; font-size: 11px; font-weight: 600; display: inline-block;}
        .status-new { background: #e1f0ff; color: #009ef7; } /* Xanh dương */
        .status-read, .status-processing { background: #fff4de; color: #ffc700; } /* Vàng */
        .status-replied, .status-shipped, .status-success { background: #e8fff3; color: #50cd89; } /* Xanh lá */
        .status-cancelled, .status-danger { background: #ffe2e5; color: #f1416c; } /* Đỏ */
        .status-pending { background: #f5f8fa; color: #7e8299; } /* Xám */

        .flag-chip { font-size: 10px; padding: 3px 8px; border-radius: 4px; font-weight: 700; text-transform: uppercase; margin-right: 4px; }
        .flag-new { background: #7239ea; color: #fff; }
        .flag-hot { background: #f1416c; color: #fff; }
        .flag-best { background: #ffc700; color: #fff; }

        /* Form elements */
        .form-control { border-radius: 6px; border: 1px solid #e1e3ea; padding: 0.6rem 1rem; font-size: 13px; }
        .form-control:focus { border-color: var(--primary-color); box-shadow: none; background: #f9f9f9; }
        .btn { border-radius: 6px; font-weight: 500; font-size: 13px; padding: 8px 16px; }
        
        /* Animations */
        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }

        /* Scrollbar */
        ::-webkit-scrollbar { width: 8px; height: 8px; }
        ::-webkit-scrollbar-track { background: #f1f1f1; }
        ::-webkit-scrollbar-thumb { background: #c1c1c1; border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: #a8a8a8; }
    </style>
    @stack('styles')
</head>

<body>
    <?php 
        $authUser = null;
        if(session('admin_user_id')){
            $authUser = \App\Models\User::find(session('admin_user_id'));
        }
    ?>

    <!-- SIDEBAR -->
    <aside class="admin-sidebar">
        <div class="sidebar-brand">
            <i class="fas fa-cube"></i> MALEFASHION
        </div>
        
        <div class="sidebar-menu">
            <div class="menu-header">Thống kê</div>
            <div class="nav-item">
                <a href="{{ route('admin.revenue.index') }}" class="nav-link {{ request()->routeIs('admin.revenue.*') || request()->routeIs('admin.index') ? 'active' : '' }}">
                    <i class="fas fa-chart-line"></i> <span>Doanh thu</span>
                </a>
            </div>

            <div class="menu-header">Bán hàng</div>
            <div class="nav-item">
                <a href="{{ route('admin.orders.index') }}" class="nav-link {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
                    <i class="fas fa-shopping-cart"></i> <span>Đơn hàng</span>
                </a>
            </div>
            <div class="nav-item">
                <a href="{{ route('admin.contacts.index') }}" class="nav-link {{ request()->routeIs('admin.contacts.*') ? 'active' : '' }}">
                    <i class="fas fa-envelope"></i> <span>Liên hệ / CSKH</span>
                </a>
            </div>
            <div class="nav-item">
                <a href="{{ route('admin.coupons.index') }}" class="nav-link {{ request()->routeIs('admin.coupons.*') ? 'active' : '' }}">
                    <i class="fas fa-ticket-alt"></i> <span>Mã giảm giá</span>
                </a>
            </div>

            <div class="menu-header">Kho & Sản phẩm</div>
            <div class="nav-item">
                <a href="{{ route('admin.products.index') }}" class="nav-link {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
                    <i class="fas fa-tshirt"></i> <span>Sản phẩm</span>
                </a>
            </div>
            <div class="nav-item">
                <a href="{{ route('admin.categories.index') }}" class="nav-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                    <i class="fas fa-folder"></i> <span>Danh mục</span>
                </a>
            </div>
            <div class="nav-item">
                <a href="{{ route('admin.attributes.index') }}" class="nav-link {{ request()->routeIs('admin.attributes.*') ? 'active' : '' }}">
                    <i class="fas fa-tags"></i> <span>Thuộc tính (Size/Màu)</span>
                </a>
            </div>

            <div class="menu-header">Hệ thống</div>
            <div class="nav-item">
                <a href="{{ route('admin.banners.index') }}" class="nav-link {{ request()->routeIs('admin.banners.*') ? 'active' : '' }}">
                    <i class="fas fa-images"></i> <span>Quản lý Banner</span>
                </a>
            </div>
            <div class="nav-item">
                <a href="{{ route('admin.customers.index') }}" class="nav-link {{ request()->routeIs('admin.customers.*') ? 'active' : '' }}">
                    <i class="fas fa-users"></i> <span>Khách hàng</span>
                </a>
            </div>
            <div class="nav-item">
                <a href="{{ route('admin.staff.index') }}" class="nav-link {{ request()->routeIs('admin.staff.*') ? 'active' : '' }}">
                    <i class="fas fa-user-shield"></i> <span>Nhân viên</span>
                </a>
            </div>
        </div>
    </aside>

    <!-- MAIN LAYOUT -->
    <div class="admin-main">
        <!-- HEADER -->
        <header class="admin-header">
            <div class="header-left">
                <!-- Có thể để breadcrumb ở đây -->
                <a href="{{ route('home') }}" class="btn btn-light btn-sm text-muted">
                    <i class="fas fa-external-link-alt"></i> Xem trang web
                </a>
            </div>

            <div class="header-right">
                @if($authUser)
                <div class="user-profile" id="userDropdownTrigger">
                    <div class="user-info text-right d-none d-md-flex">
                        <span class="user-name">{{ $authUser->FIRST_NAME }} {{ $authUser->LAST_NAME }}</span>
                        <span class="user-role">{{ $authUser->ROLE }}</span>
                    </div>
                    <div class="user-avatar">
                        {{ strtoupper(substr($authUser->FIRST_NAME, 0, 1)) }}
                    </div>
                    <i class="fas fa-chevron-down text-muted small"></i>

                    <!-- Dropdown -->
                    <div class="dropdown-menu-custom" id="userDropdownMenu">
                        <a href="{{ route('admin.profile') }}" class="dropdown-item">
                            <i class="far fa-user"></i> Hồ sơ cá nhân
                        </a>
                        <a href="{{ route('admin.change-password') }}" class="dropdown-item">
                            <i class="fas fa-key"></i> Đổi mật khẩu
                        </a>
                        <div class="dropdown-divider"></div>
                        <form action="{{ route('admin.logout') }}" method="post" id="logoutForm">
                            @csrf
                            <a href="#" class="dropdown-item text-danger" onclick="document.getElementById('logoutForm').submit(); return false;">
                                <i class="fas fa-sign-out-alt"></i> Đăng xuất
                            </a>
                        </form>
                    </div>
                </div>
                @endif
            </div>
        </header>

        <!-- CONTENT -->
        <div class="content-wrapper">
            @yield('content')
        </div>
    </div>

    <!-- JS Assets -->
    <script src="{{ asset('assets/malefashion/js/jquery-3.3.1.min.js') }}"></script>
    <script src="{{ asset('assets/malefashion/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/malefashion/js/jquery.nice-select.min.js') }}"></script>
    
    <script>
        // User Dropdown Logic
        $(document).ready(function() {
            $('#userDropdownTrigger').click(function(e) {
                e.stopPropagation();
                $('#userDropdownMenu').toggleClass('show');
            });

            $(document).click(function() {
                $('#userDropdownMenu').removeClass('show');
            });
        });
    </script>

    @yield('scripts')
</body>
</html>