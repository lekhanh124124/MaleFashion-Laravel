@extends('layouts.admin')

@section('content')
<div id="pane-orders" class="mt-2">
  @if(session('success'))
  <div class="alert alert-success">{{ session('success') }}</div>
  @endif

  <div class="sticky-toolbar d-flex flex-wrap align-items-center">
    <h5 class="mb-0 mr-auto">Đơn hàng</h5>
    <form method="GET" class="d-flex">
      <input name="search" class="form-control w-auto" placeholder="Tìm ID / Email / SĐT" value="{{ request('search') }}">
      <button class="btn btn-outline-secondary ml-1"><i class="fa fa-search"></i></button>
    </form>
  </div>

  <div class="legend-box mt-2">
    <strong>Chú giải:</strong> Trạng thái: Pending, Processing, Shipped, Cancelled.
  </div>

  <div class="table-responsive mt-3">
    <table class="table table-striped table-hover">
      <thead class="thead-light">
        <tr>
          <th>ID</th>
          <th>Khách hàng</th>
          <th>Coupon</th>
          <th>Ngày đặt</th>
          <th>Trạng thái</th>
          <th>Thanh toán</th>
          <th>Giảm giá</th>
          <th class="text-right">Thao tác</th>
        </tr>
      </thead>
      <tbody>
        @foreach($orders as $o)
        <tr>
          <td>{{ $o->ORDER_ID }}</td>
          <td class="fk-ref">
            @if($o->user)
            <strong>[{{ $o->USER_ID }}]</strong> {{ $o->user->name }}
            @else
            <strong>Guest</strong>
            @endif
            <br><span class="soft-label">{{ $o->BILLING_EMAIL }}</span>
          </td>
          <td>
            @if($o->coupon)
            <span class="badge badge-warning text-dark">{{ $o->coupon->COUPON_CODE }}</span>
            @else
            ---
            @endif
          </td>
          <td>{{ \Carbon\Carbon::parse($o->ORDER_DATE)->format('d/m/Y H:i') }}</td>
          <td>
            @php
            $badgeClass = 'secondary';
            if($o->STATUS == 'Pending') $badgeClass = 'warning';
            elseif($o->STATUS == 'Processing') $badgeClass = 'info';
            elseif($o->STATUS == 'Shipped') $badgeClass = 'success';
            elseif($o->STATUS == 'Cancelled') $badgeClass = 'danger';
            @endphp
            <span class="badge badge-{{ $badgeClass }}">{{ $o->STATUS }}</span>
          </td>
          <td>{{ $o->PAYMENT_METHOD }}</td>
          <td>{{ number_format($o->DISCOUNT_AMOUNT) }}</td>
          <td class="text-right">
            <button class="btn btn-sm btn-outline-secondary btn-view-items" data-id="{{ $o->ORDER_ID }}">Chi tiết</button>
            <button class="btn btn-sm btn-outline-primary btn-edit-order" data-id="{{ $o->ORDER_ID }}">Sửa</button>
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>

    <div class="mt-2">
      {{ $orders->withQueryString()->links() }}
    </div>
  </div>
</div>

@include('admin.partials.orders.modal-order-items')
@include('admin.partials.orders.modal-order-edit')
@endsection

@section('scripts')
<script>
  // 1. Xử lý Xem chi tiết (ORDER ITEMS)
  $(document).on('click', '.btn-view-items', function() {
    let id = $(this).data('id');
    let url = "{{ route('admin.orders.show', ':id') }}".replace(':id', id);

    $('#modalItemsBody').html('Đang tải...');
    $('#modalOrderItems').modal('show');

    $.get(url, function(order) {
      // 1. Render thông tin khách (Giữ nguyên)
      let userHtml = `Khách: <strong>${order.BILLING_NAME}</strong> - ${order.BILLING_EMAIL} | SĐT: ${order.BILLING_PHONE}`;
      $('#modalItemsUser').html(userHtml);
      $('#modalItemsPayment').text(order.PAYMENT_METHOD);
      $('#modalItemsShipping').text(order.SHIPPING_ADDRESS);
      $('#modalItemsNote').text(order.ORDER_NOTES || 'Không có');

      // 2. Render bảng items (CẬP NHẬT MỚI)
      let html = '';
      let subtotal = 0;

      order.items.forEach(item => {
        // Xử lý dữ liệu an toàn (null check)
        let product = item.variant.product;
        let productName = product ? product.PRODUCT_NAME : 'Sản phẩm đã xóa';

        // Xử lý ảnh
        let imgUrl = '/images/no-image.png'; // Ảnh mặc định nếu không có
        if (product && product.thumbnail) {
          imgUrl = product.thumbnail.IMAGE_URL;
          // Nếu bạn lưu đường dẫn tương đối, có thể cần thêm prefix, ví dụ: '/storage/' + ...
        }

        // Xử lý Brand
        let brandName = (product && product.brand) ? product.brand.BRAND_NAME : '';

        let sku = item.variant.SKU || 'N/A';
        let size = item.variant.size ? item.variant.size.SIZE_NAME : '-';
        let color = item.variant.color ? item.variant.color.COLOR_NAME : '-';
        let total = item.QUANTITY * item.UNIT_PRICE;
        subtotal += total;

        html += `
                <tr>
                    <td>${item.VARIANT_ID}</td>
                    <td class="text-center">
                        <img src="${imgUrl}" class="img-thumbnail" style="width: 60px; height: 60px; object-fit: cover;">
                    </td>
                    <td>
                        <div>
                            ${brandName ? `<span class="badge badge-info mb-1">${brandName}</span>` : ''}
                            <strong>${productName}</strong>
                        </div>
                        <small class="text-muted">SKU: ${sku} | Size: ${size} | Màu: ${color}</small>
                    </td>
                    <td class="text-center align-middle">${item.QUANTITY}</td>
                    <td class="text-right align-middle">${new Intl.NumberFormat('vi-VN').format(item.UNIT_PRICE)} ₫</td>
                    <td class="text-right font-weight-bold align-middle">${new Intl.NumberFormat('vi-VN').format(total)} ₫</td>
                </tr>
            `;
      });

      $('#modalItemsTableBody').html(html);

      // 3. Tính toán Footer (CẬP NHẬT MỚI)
      let discount = parseFloat(order.DISCOUNT_AMOUNT) || 0;
      let finalTotal = subtotal - discount;

      // Hiển thị mã Coupon nếu có
      if (order.coupon) {
        $('#valCouponCode').text(`(${order.coupon.COUPON_CODE})`);
      } else {
        $('#valCouponCode').text('');
      }

      $('#valSubtotal').text(new Intl.NumberFormat('vi-VN').format(subtotal) + ' ₫');
      $('#valDiscount').text('-' + new Intl.NumberFormat('vi-VN').format(discount) + ' ₫');
      $('#valFinal').text(new Intl.NumberFormat('vi-VN').format(finalTotal) + ' ₫');
    });
  });

  // 2. Xử lý Sửa Đơn hàng (Load data vào form)
  $(document).on('click', '.btn-edit-order', function() {
    let id = $(this).data('id');
    let url = "{{ route('admin.orders.show', ':id') }}".replace(':id', id);

    $('#formOrderEdit')[0].reset();
    $('#modalOrderEdit').modal('show');
    $('#btnSaveOrder').data('id', id); // Lưu ID vào nút Save

    $.get(url, function(order) {
      $('#editStatus').val(order.STATUS);
      $('#editPaymentMethod').val(order.PAYMENT_METHOD); // Chỉ hiển thị, không cho sửa (disabled input)
      $('#editNotes').val(order.ORDER_NOTES);
    });
  });

  // 3. Lưu thay đổi đơn hàng
  $('#btnSaveOrder').click(function() {
    let id = $(this).data('id');
    let url = "{{ route('admin.orders.update', ':id') }}".replace(':id', id);
    let btn = $(this);

    btn.prop('disabled', true).text('Đang lưu...');

    $.ajax({
      url: url,
      type: 'PUT',
      data: {
        STATUS: $('#editStatus').val(),
        ORDER_NOTES: $('#editNotes').val(),
        _token: "{{ csrf_token() }}"
      },
      success: function(res) {
        alert(res.message);
        location.reload();
      },
      error: function(err) {
        alert('Có lỗi xảy ra!');
        console.log(err);
        btn.prop('disabled', false).text('Lưu thay đổi');
      }
    });
  });
</script>
@endsection