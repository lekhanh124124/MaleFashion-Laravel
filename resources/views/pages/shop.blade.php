@extends('layouts.malefashion')
@section('title', 'Shop')
@section('content')

@include('partials.shop.breadcrumb')

<section class="shop spad">
    <div class="container">
        <div class="row">
            <div class="col-lg-3">
                @include('partials.shop.sidebar')
            </div>
            <div class="col-lg-9">
                @include('partials.shop.product-option')
                @include('partials.shop.product-grid')
                @include('partials.shop.pagination')
            </div>
        </div>
    </div>
</section>

@endsection

@section('scripts')
<link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>

<script>
    // --- PHẦN 1: XỬ LÝ CHỌN MÀU VARIANT (Giữ nguyên) ---
    document.addEventListener('DOMContentLoaded', function() {
        const colorInputs = document.querySelectorAll('.color-variant-option');
        colorInputs.forEach(input => {
            input.addEventListener('change', function() {
                const newPrice = this.getAttribute('data-price');
                const productId = this.getAttribute('data-product-id');
                const priceDisplay = document.getElementById(`price-display-${productId}`);
                if (priceDisplay && newPrice) {
                    priceDisplay.innerText = newPrice;
                    priceDisplay.style.opacity = 0.5;
                    setTimeout(() => { priceDisplay.style.opacity = 1; }, 200);
                }
                const parentLabel = this.closest('label');
                const container = this.closest('.product__color__select');
                const allLabels = container.querySelectorAll('label');
                allLabels.forEach(lbl => lbl.classList.remove('active'));
                if (parentLabel) parentLabel.classList.add('active');
            });
        });
    });

    $(document).ready(function() {

        // --- 1. XỬ LÝ GIÁ (SLIDER) ---
        var minInput = $("#price-min-input");
        var maxInput = $("#price-max-input");
        var hiddenMin = $("#hidden_min_price");
        var hiddenMax = $("#hidden_max_price");
        var limitMax = 10000000;

        $("#price-slider-range").slider({
            range: true,
            min: 0,
            max: limitMax,
            step: 50000,
            values: [hiddenMin.val(), hiddenMax.val()],
            slide: function(event, ui) {
                minInput.val(ui.values[0]);
                maxInput.val(ui.values[1]);
                hiddenMin.val(ui.values[0]);
                hiddenMax.val(ui.values[1]);
            }
        });

        $(".price-input").on("change", function() {
            var minVal = parseInt(minInput.val()) || 0;
            var maxVal = parseInt(maxInput.val()) || limitMax;
            if (minVal > maxVal) minVal = maxVal;
            $("#price-slider-range").slider("values", [minVal, maxVal]);
            hiddenMin.val(minVal);
            hiddenMax.val(maxVal);
        });

        // --- 2. XỬ LÝ VISUAL CHECKBOX (MỚI) ---
        // Áp dụng cho Size và Tags (những input có class toggle-checkbox)
        // Khi check thì thêm class active vào label cha, bỏ check thì xóa đi
        $('.toggle-checkbox').on('change', function() {
            if($(this).is(':checked')) {
                $(this).closest('label').addClass('active');
            } else {
                $(this).closest('label').removeClass('active');
            }
        });

        // --- 3. XỬ LÝ RADIO COLORS (Giữ nguyên logic Toggle cho màu sắc vì nó vẫn là Radio) ---
        $('.toggle-radio').each(function() {
            $(this).data('waschecked', $(this).prop('checked'));
        });
        $('.toggle-radio').on('click', function() {
            var $radio = $(this);
            if ($radio.data('waschecked') == true) {
                $radio.prop('checked', false);
                $radio.data('waschecked', false);
                $radio.closest('label').removeClass('active');
            } else {
                $('input[name="' + $radio.attr('name') + '"]').data('waschecked', false);
                $('input[name="' + $radio.attr('name') + '"]').closest('label').removeClass('active');
                $radio.prop('checked', true);
                $radio.data('waschecked', true);
                $radio.closest('label').addClass('active');
            }
        });
    });

    // --- PHẦN 3: XỬ LÝ ADD TO WISHLIST (Giữ nguyên) ---
    document.addEventListener('click', function(e) {
        const btn = e.target.closest('.wishlist-btn');
        if (btn) {
            e.preventDefault();
            const productId = btn.getAttribute('data-id');
            const csrfToken = document.querySelector('meta[name="csrf-token"]') ? document.querySelector('meta[name="csrf-token"]').getAttribute('content') : '';

            fetch(`/wishlist/toggle/${productId}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'login_required') {
                        alert(data.message);
                        window.location.href = "{{ route('login') }}";
                    } else if (data.status === 'added') {
                        btn.classList.add('active');
                    } else if (data.status === 'removed') {
                        btn.classList.remove('active');
                    }
                })
                .catch(error => console.error('Error:', error));
        }
    });
</script>
@endsection