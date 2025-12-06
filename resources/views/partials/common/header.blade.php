<header class="header">
  <div class="header__top">
    <div class="container">
      <div class="row">
        <div class="col-lg-6 col-md-7">
          <div class="header__top__left">
            <p>Free shipping, 30-day return or refund guarantee.</p>
          </div>
        </div>
        <div class="col-lg-6 col-md-5">
          <div class="header__top__right">
            <div class="header__top__links">
              @auth
              {{-- Đã đăng nhập --}}
              <div class="dropdown d-inline custom-dropdown">
                <button class="btn btn-sm btn-outline-dark dropdown-toggle user-dropdown-toggle"
                  type="button"
                  aria-expanded="false">
                  {{-- Avatar từ Gravatar dựa trên email --}}
                  <img src="https://www.gravatar.com/avatar/{{ md5(strtolower(trim(Auth::user()->EMAIL))) }}?s=28&d=mp"
                    class="rounded-circle" style="width:24px;height:24px;">
                  {{-- Hiển thị tên đầy đủ (dùng Accessor getFullNameAttribute trong Model) --}}
                  <span class="ml-1 user-name-text">Chào, {{ Auth::user()->full_name }}</span>
                </button>
                <div class="dropdown-menu dropdown-menu-right shadow user-menu" style="display:none;">
                  <span class="dropdown-item-text text-muted small">Vai trò: {{ strtoupper(Auth::user()->ROLE) }}</span>
                  <div class="dropdown-divider"></div>
                  <a class="dropdown-item" href="{{ route('customer.profile') }}">Thông tin cá nhân</a>
                  <a class="dropdown-item" href="{{ route('customer.password') }}">Đổi mật khẩu</a>
                  <div class="dropdown-divider"></div>

                  {{-- Form Đăng xuất --}}
                  <form method="post" action="{{ route('logout.customer') }}" class="px-3 pt-2">
                    @csrf
                    <button class="btn btn-sm btn-danger btn-block">Đăng xuất</button>
                  </form>
                </div>
              </div>
              @else
              {{-- Chưa đăng nhập --}}
              <a href="{{ route('login') }}">Log In</a> |
              <a href="{{ route('register') }}">Register</a>
              @endauth
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="container">
    <div class="row">
      <div class="col-lg-3 col-md-3">
        <div class="header__logo">
          <a href="{{ url('/') }}"><img src="{{ asset('assets/malefashion/img/logo.png') }}" alt=""></a>
        </div>
      </div>
      <div class="col-lg-6 col-md-6">
        <nav class="header__menu mobile-menu">
          <ul>
            <li class="{{ request()->is('/') ? 'active' : '' }}"><a href="{{ url('/') }}">Home</a></li>
            <li class="{{ request()->is('shop*') ? 'active' : '' }}"><a href="{{ url('/shop') }}">Shop</a></li>
            <li class="{{ request()->is('about') ? 'active' : '' }}"><a href="{{ url('/about') }}">About Us</a></li>
            <li class="{{ request()->is('contact') ? 'active' : '' }}"><a href="{{ url('/contact') }}">Contacts</a></li>
          </ul>
        </nav>
      </div>
      <div class="col-lg-3 col-md-3">
        <div class="header__nav__option">
          <a href="#" class="search-switch"><img src="{{ asset('assets/malefashion/img/icon/search.png') }}" alt=""></a>
          <a href="{{ route('wishlist') }}"><img src="{{ asset('assets/malefashion/img/icon/heart.png') }}" alt=""></a>

          <a href="{{ route('shopping.cart') }}">
            <img src="{{ asset('assets/malefashion/img/icon/cart.png') }}" alt="">
            <span id="header-cart-count">{{ $globalCartCount ?? 0 }}</span>
          </a>

          <div class="price" id="header-cart-total">
            {{ number_format($globalCartTotal ?? 0, 0, ',', '.') }}₫
          </div>
        </div>
      </div>
    </div>
    <div class="canvas__open"><i class="fa fa-bars"></i></div>
  </div>
</header>