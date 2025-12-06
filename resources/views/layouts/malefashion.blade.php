<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>@yield('title','MaleFashion')</title>

  <link href="https://fonts.googleapis.com/css2?family=Nunito+Sans:wght@300;400;600;700;800;900&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('assets/malefashion/css/bootstrap.min.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/malefashion/css/font-awesome.min.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/malefashion/css/elegant-icons.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/malefashion/css/magnific-popup.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/malefashion/css/nice-select.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/malefashion/css/owl.carousel.min.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/malefashion/css/slicknav.min.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/malefashion/css/style.css') }}">
</head>
<body>
  @include('partials.common.preloader')
  @include('partials.common.offcanvas')
  @include('partials.common.header')

  @yield('content')

  @include('partials.common.footer')
  @include('partials.common.search-modal')

  <script src="{{ asset('assets/malefashion/js/jquery-3.3.1.min.js') }}"></script>
  <script src="{{ asset('assets/malefashion/js/bootstrap.min.js') }}"></script>
  <script src="{{ asset('assets/malefashion/js/jquery.nice-select.min.js') }}"></script>
  <script src="{{ asset('assets/malefashion/js/jquery.nicescroll.min.js') }}"></script>
  <script src="{{ asset('assets/malefashion/js/jquery.magnific-popup.min.js') }}"></script>
  <script src="{{ asset('assets/malefashion/js/jquery.countdown.min.js') }}"></script>
  <script src="{{ asset('assets/malefashion/js/jquery.slicknav.js') }}"></script>
  <script src="{{ asset('assets/malefashion/js/mixitup.min.js') }}"></script>
  <script src="{{ asset('assets/malefashion/js/owl.carousel.min.js') }}"></script>
  <script src="{{ asset('assets/malefashion/js/main.js') }}"></script>
  @yield('scripts')
</body>
</html>