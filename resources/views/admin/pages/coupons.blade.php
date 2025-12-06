@extends('layouts.admin')

@section('content')
<div id="pane-coupons" class="mt-2">
  <!-- FORM TÌM KIẾM & LỌC -->
  <form action="{{ route('admin.coupons.index') }}" method="GET">
      <div class="sticky-toolbar d-flex flex-wrap align-items-center gap-2">
        <h5 class="mb-0 mr-auto">Mã giảm giá</h5>
        
        <!-- Nút mở Modal Thêm mới -->
        <button type="button" class="btn btn-dark" onclick="openAddModal()">
          <i class="fa fa-plus"></i> Thêm coupon
        </button>

        <div class="ml-auto d-flex align-items-center">
          <select class="form-control w-auto mr-2" name="status" onchange="this.form.submit()">
            <option value="">Tất cả trạng thái</option>
            <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>Đang hoạt động</option>
            <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>Ngừng</option>
          </select>
          <input class="form-control w-auto" name="search" value="{{ request('search') }}" placeholder="Tìm mã coupon...">
          <button type="submit" class="btn btn-primary ml-1"><i class="fa fa-search"></i></button>
        </div>
      </div>
  </form>

  <!-- Legend chú thích -->
  <div class="legend-box mt-2">
    <strong>Bảng:</strong> COUPONS
    <div>- COUPON_ID, COUPON_CODE (unique), DISCOUNT_TYPE ('percentage' | 'fixed'), DISCOUNT_VALUE, EXPIRY_DATE (DATETIME), IS_ACTIVE (1|0)</div>
  </div>

  <!-- HIỂN THỊ THÔNG BÁO -->
  @if(session('success'))
      <div class="alert alert-success mt-2">{{ session('success') }}</div>
  @endif
  @if($errors->any())
      <div class="alert alert-danger mt-2">
          <ul class="mb-0">
             @foreach ($errors->all() as $error)
                 <li>{{ $error }}</li>
             @endforeach
          </ul>
      </div>
  @endif

  <div class="table-responsive mt-3">
    <table class="table table-striped">
      <thead class="thead-light">
        <tr>
          <th>ID</th>
          <th>Mã</th>
          <th>Loại giảm</th>
          <th>Giá trị</th>
          <th>Hết hạn</th>
          <th>Trạng thái</th>
          <th class="text-right">Thao tác</th>
        </tr>
      </thead>
      <tbody>
        @forelse($coupons as $c)
        <tr>
          <td>{{ $c->COUPON_ID }}</td>
          <td><code>{{ $c->COUPON_CODE }}</code></td>
          <td>
             @if($c->DISCOUNT_TYPE == 'PERCENTAGE')
                <span class="badge badge-info">% Percentage</span>
             @else
                <span class="badge badge-warning">$ Fixed</span>
             @endif
          </td>
          <td>
              {{ $c->DISCOUNT_TYPE == 'PERCENTAGE' ? number_format($c->DISCOUNT_VALUE) . '%' : number_format($c->DISCOUNT_VALUE) }}
          </td>
          <td>{{ \Carbon\Carbon::parse($c->EXPIRY_DATE)->format('d/m/Y H:i') }}</td>
          <td>
            @if($c->IS_ACTIVE)
                <span class="badge badge-success">Đang hoạt động</span>
            @else
                <span class="badge badge-secondary">Ngừng</span>
            @endif
          </td>
          <td class="text-right">
            <!-- Nút Sửa: Lưu JSON vào data-coupon -->
            <button class="btn btn-sm btn-outline-primary" 
                    data-coupon='{{ json_encode($c) }}' 
                    onclick="openEditModal(this)">
                    Sửa
            </button>

            <!-- Nút Bật/Tắt -->
            <form action="{{ route('admin.coupons.toggle', $c->COUPON_ID) }}" method="POST" style="display:inline-block;">
                @csrf
                @if($c->IS_ACTIVE)
                    <button type="submit" class="btn btn-sm btn-outline-secondary">Ngừng</button>
                @else
                    <button type="submit" class="btn btn-sm btn-outline-success">Kích hoạt</button>
                @endif
            </form>

            <!-- Nút Xóa -->
            <form action="{{ route('admin.coupons.destroy', $c->COUPON_ID) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Bạn chắc chắn muốn xóa mã này?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-sm btn-outline-danger">Xóa</button>
            </form>
          </td>
        </tr>
        @empty
        <tr>
            <td colspan="7" class="text-center">Chưa có mã giảm giá nào.</td>
        </tr>
        @endforelse
      </tbody>
    </table>
    
    <!-- Phân trang -->
    <div class="d-flex justify-content-center">
        {{ $coupons->withQueryString()->links() }} 
    </div>
  </div>
</div>

<!-- Include Modal -->
@include('admin.partials.coupons.modal-coupon')

@endsection

@section('scripts')
<script>
    // Hàm mở modal để Thêm mới
    function openAddModal() {
        $('#modalTitle').text('Thêm mã giảm giá mới');
        
        // Reset form về trạng thái ban đầu
        $('#couponForm').trigger("reset");
        // Đặt action về route store
        $('#couponForm').attr('action', "{{ route('admin.coupons.store') }}"); 
        $('#method_field').html(''); // Xóa method PUT
        
        // Giá trị mặc định
        $('#input_active').val('1');
        $('#input_type').val('PERCENTAGE');

        $('#modalCoupon').modal('show');
    }

    // Hàm mở modal để Edit (nhận vào đối tượng nút bấm)
    function openEditModal(element) {
        // Lấy dữ liệu từ data attribute (jQuery tự parse JSON)
        let coupon = $(element).data('coupon');

        $('#modalTitle').text('Cập nhật mã: ' + coupon.COUPON_CODE);
        
        // Đổi action form sang route update
        let updateUrl = "{{ route('admin.coupons.update', ':id') }}";
        updateUrl = updateUrl.replace(':id', coupon.COUPON_ID);
        $('#couponForm').attr('action', updateUrl);
        
        // Thêm input hidden method PUT cho Laravel
        $('#method_field').html('<input type="hidden" name="_method" value="PUT">');

        // Điền dữ liệu vào form
        $('#input_code').val(coupon.COUPON_CODE);
        $('#input_type').val(coupon.DISCOUNT_TYPE);
        $('#input_value').val(coupon.DISCOUNT_VALUE);
        
        // Format ngày giờ cho input datetime-local (YYYY-MM-DDTHH:MM)
        if(coupon.EXPIRY_DATE) {
             let expiry = coupon.EXPIRY_DATE.replace(' ', 'T').substring(0, 16);
             $('#input_expiry').val(expiry);
        }
        
        $('#input_active').val(coupon.IS_ACTIVE);

        $('#modalCoupon').modal('show');
    }
</script>
@endsection