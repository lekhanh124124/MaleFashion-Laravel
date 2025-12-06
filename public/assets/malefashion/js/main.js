/*  ---------------------------------------------------
    Template Name: Male Fashion
    Description: Male Fashion - ecommerce teplate
    Author: Colorib
    Author URI: https://www.colorib.com/
    Version: 1.0
    Created: Colorib
---------------------------------------------------------  */

'use strict';

(function ($) {

    /*------------------
        Preloader
    --------------------*/
    $(window).on('load', function () {
        $(".loader").fadeOut();
        $("#preloder").delay(200).fadeOut("slow");

        /*------------------
            Gallery filter
        --------------------*/
        $('.filter__controls li').on('click', function () {
            $('.filter__controls li').removeClass('active');
            $(this).addClass('active');
        });
        if ($('.product__filter').length > 0) {
            var containerEl = document.querySelector('.product__filter');
            var mixer = mixitup(containerEl);
        }
    });

    /*------------------
        Background Set
    --------------------*/
    $('.set-bg').each(function () {
        var bg = $(this).data('setbg');
        $(this).css('background-image', 'url(' + bg + ')');
    });

    //Search Switch
    $('.search-switch').on('click', function () {
        $('.search-model').fadeIn(400);
    });

    $('.search-close-switch').on('click', function () {
        $('.search-model').fadeOut(400, function () {
            $('#search-input').val('');
        });
    });

    /*------------------
		Navigation
	--------------------*/
    $(".mobile-menu").slicknav({
        prependTo: '#mobile-menu-wrap',
        allowParentLinks: true
    });

    /*------------------
        Accordin Active
    --------------------*/
    $('.collapse').on('shown.bs.collapse', function () {
        $(this).prev().addClass('active');
    });

    $('.collapse').on('hidden.bs.collapse', function () {
        $(this).prev().removeClass('active');
    });

    //Canvas Menu
    $(".canvas__open").on('click', function () {
        $(".offcanvas-menu-wrapper").addClass("active");
        $(".offcanvas-menu-overlay").addClass("active");
    });

    $(".offcanvas-menu-overlay").on('click', function () {
        $(".offcanvas-menu-wrapper").removeClass("active");
        $(".offcanvas-menu-overlay").removeClass("active");
    });

    /*-----------------------
        Hero Slider
    ------------------------*/
    $(".hero__slider").owlCarousel({
        loop: true,
        margin: 0,
        items: 1,
        dots: false,
        nav: true,
        navText: ["<span class='arrow_left'><span/>", "<span class='arrow_right'><span/>"],
        animateOut: 'fadeOut',
        animateIn: 'fadeIn',
        smartSpeed: 1200,
        autoHeight: false,
        autoplay: false
    });

    /*--------------------------
        Select
    ----------------------------*/
    $("select").niceSelect();

    /*-------------------
		Radio Btn
	--------------------- */
    $(".product__color__select label, .shop__sidebar__size label, .product__details__option__size label").on('click', function () {
        $(".product__color__select label, .shop__sidebar__size label, .product__details__option__size label").removeClass('active');
        $(this).addClass('active');
    });

    /*-------------------
		Scroll
	--------------------- */
    $(".nice-scroll").niceScroll({
        cursorcolor: "#0d0d0d",
        cursorwidth: "5px",
        background: "#e5e5e5",
        cursorborder: "",
        autohidemode: true,
        horizrailenabled: false
    });

    /*------------------
        CountDown
    --------------------*/
    // For demo preview start
    var today = new Date();
    var dd = String(today.getDate()).padStart(2, '0');
    var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
    var yyyy = today.getFullYear();

    if(mm == 12) {
        mm = '01';
        yyyy = yyyy + 1;
    } else {
        mm = parseInt(mm) + 1;
        mm = String(mm).padStart(2, '0');
    }
    var timerdate = mm + '/' + dd + '/' + yyyy;
    // For demo preview end


    // Uncomment below and use your date //

    /* var timerdate = "2020/12/30" */

    $("#countdown").countdown(timerdate, function (event) {
        $(this).html(event.strftime("<div class='cd-item'><span>%D</span> <p>Days</p> </div>" + "<div class='cd-item'><span>%H</span> <p>Hours</p> </div>" + "<div class='cd-item'><span>%M</span> <p>Minutes</p> </div>" + "<div class='cd-item'><span>%S</span> <p>Seconds</p> </div>"));
    });

    /*------------------
		Magnific
	--------------------*/
    $('.video-popup').magnificPopup({
        type: 'iframe'
    });

    /*-------------------
		Quantity change
	--------------------- */
    var proQty = $('.pro-qty');
    proQty.prepend('<span class="fa fa-angle-up dec qtybtn"></span>');
    proQty.append('<span class="fa fa-angle-down inc qtybtn"></span>');
    proQty.on('click', '.qtybtn', function () {
        var $button = $(this);
        var oldValue = $button.parent().find('input').val();
        if ($button.hasClass('inc')) {
            var newVal = parseFloat(oldValue) + 1;
        } else {
            // Don't allow decrementing below zero
            if (oldValue > 0) {
                var newVal = parseFloat(oldValue) - 1;
            } else {
                newVal = 0;
            }
        }
        $button.parent().find('input').val(newVal);
    });

    var proQty = $('.pro-qty-2');
    proQty.prepend('<span class="fa fa-angle-left dec qtybtn"></span>');
    proQty.append('<span class="fa fa-angle-right inc qtybtn"></span>');
    proQty.on('click', '.qtybtn', function () {
        var $button = $(this);
        var oldValue = $button.parent().find('input').val();
        if ($button.hasClass('inc')) {
            var newVal = parseFloat(oldValue) + 1;
        } else {
            // Don't allow decrementing below zero
            if (oldValue > 0) {
                var newVal = parseFloat(oldValue) - 1;
            } else {
                newVal = 0;
            }
        }
        $button.parent().find('input').val(newVal);
    });

    /*------------------
        Achieve Counter
    --------------------*/
    $('.cn_num').each(function () {
        $(this).prop('Counter', 0).animate({
            Counter: $(this).text()
        }, {
            duration: 4000,
            easing: 'swing',
            step: function (now) {
                $(this).text(Math.ceil(now));
            }
        });
    });

    function isEmail(v) {
        return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(v);
    }

    function setInvalid($input, message) {
        $input.addClass('is-invalid');
        const $feedback = $input.closest('.form-group, .input-group').find('.invalid-feedback').first();
        if (message && $feedback.length) $feedback.text(message);
    }

    function clearInvalid($input) {
        $input.removeClass('is-invalid');
    }

    function togglePassword() {
        $(document).on('click', '.toggle-password', function () {
            const target = $(this).data('target');
            const $inp = $(target);
            if (!$inp.length) return;
            const type = $inp.attr('type') === 'password' ? 'text' : 'password';
            $inp.attr('type', type);
            $(this).find('i').toggleClass('fa-eye fa-eye-slash');
        });
    }

    function handleLogin() {
        const $form = $('#loginForm');
        if (!$form.length) return;

        $form.on('submit', function (e) {
            e.preventDefault();
            const $email = $('#loginEmail');
            const $pass  = $('#loginPassword');
            let ok = true;

            clearInvalid($email); clearInvalid($pass);

            if (!isEmail($email.val().trim())) {
                setInvalid($email, 'Vui lòng nhập email hợp lệ.');
                ok = false;
            }
            if (($pass.val() || '').length < 6) {
                setInvalid($pass, 'Mật khẩu tối thiểu 6 ký tự.');
                ok = false;
            }

            if (!ok) return;

            // Giả lập thành công phía client
            $('#loginAlert').removeClass('d-none');
            setTimeout(function () { window.location.href = '/'; }, 1200);
        });
    }

    function handleRegister() {
        const $form = $('#registerForm');
        if (!$form.length) return;

        $form.on('submit', function (e) {
            e.preventDefault();
            const $name = $('#regName');
            const $email = $('#regEmail');
            const $pass = $('#regPassword');
            const $confirm = $('#regPasswordConfirm');
            const $terms = $('#regTerms');

            let ok = true;
            [$name,$email,$pass,$confirm].forEach(clearInvalid);

            if (($name.val() || '').trim().length === 0) {
                setInvalid($name, 'Vui lòng nhập họ và tên.');
                ok = false;
            }
            if (!isEmail($email.val().trim())) {
                setInvalid($email, 'Vui lòng nhập email hợp lệ.');
                ok = false;
            }
            if (($pass.val() || '').length < 6) {
                setInvalid($pass, 'Mật khẩu tối thiểu 6 ký tự.');
                ok = false;
            }
            if ($confirm.val() !== $pass.val()) {
                setInvalid($confirm, 'Mật khẩu xác nhận không khớp.');
                ok = false;
            }
            if (!$terms.is(':checked')) {
                $terms.closest('.form-group').addClass('is-invalid');
                ok = false;
            } else {
                $terms.closest('.form-group').removeClass('is-invalid');
            }

            if (!ok) return;

            // Giả lập thành công phía client
            $('#registerAlert').removeClass('d-none');
            setTimeout(function () { window.location.href = '/login'; }, 1200);
        });
    }

    $(function () {
        togglePassword();
        handleLogin();
        handleRegister();
        initRatingStars();
        initWishlist(); // added
    });

    function initWishlist() {
        // Unified remove (table row or product card)
        $(document).on('click', '.wishlist-remove, .wishlist-heart', function (e) {
            e.preventDefault();
            const $tr = $(this).closest('tr');
            if ($tr.length) {
                $tr.fadeOut(200, function(){ $(this).remove(); });
            } else {
                const $card = $(this).closest('.col-lg-4, .col-md-6, .col-sm-6');
                $card.fadeOut(200, function(){ $(this).remove(); });
            }
        });

        // Add to cart (UI mock)
        $(document).on('click', '.wishlist-add-to-cart, .add-cart', function (e) {
            e.preventDefault();
            if ($(this).hasClass('disabled')) return;
            $(this).text('Added').addClass('disabled');
        });
    }

    function initRatingStars() {
        const group = document.querySelector('.rating-group');
        if (!group) return;
        const inputs = group.querySelectorAll('input[type=radio]');
        const labels = group.querySelectorAll('label');
        function paint(val) {
            labels.forEach(l => {
                const v = parseInt(l.dataset.value);
                l.style.color = v <= val ? '#ffc107' : '#ccc';
            });
        }
        inputs.forEach(i => {
            i.addEventListener('change', () => paint(parseInt(i.value)));
        });
        const checked = group.querySelector('input[type=radio]:checked');
        if (checked) paint(parseInt(checked.value));
    }

    // --- Dropdown user menu (không cần Bootstrap JS/Popper) ---
    document.addEventListener('DOMContentLoaded', function() {
      var btn = document.querySelector('.user-dropdown-toggle');
      var menu = document.querySelector('.user-menu');
      if(btn && menu) {
        btn.addEventListener('click', function(e) {
          e.stopPropagation();
          if(menu.style.display === 'block') {
            menu.style.display = 'none';
          } else {
            menu.style.display = 'block';
          }
        });
        document.addEventListener('click', function(e) {
          if(!menu.contains(e.target) && !btn.contains(e.target)) {
            menu.style.display = 'none';
          }
        });
      }
    });
    // --- End dropdown user menu ---

})(jQuery);