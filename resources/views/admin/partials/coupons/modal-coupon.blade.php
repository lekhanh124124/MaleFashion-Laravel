<div class="modal fade" id="modalCoupon" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form id="couponForm" action="" method="POST">
        @csrf
        <!-- Vùng chứa method PUT khi edit -->
        <div id="method_field"></div>

        <div class="modal-header">
          <h5 class="modal-title" id="modalTitle">Thêm mã giảm giá</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        
        <div class="modal-body">
          <div class="form-group">
            <label>Mã Coupon <span class="text-danger">*</span></label>
            <input type="text" name="COUPON_CODE" id="input_code" class="form-control" required placeholder="VD: SALE50">
          </div>

          <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label>Loại giảm giá</label>
                    <select name="DISCOUNT_TYPE" id="input_type" class="form-control">
                        <option value="PERCENTAGE">Theo phần trăm (%)</option>
                        <option value="FIXED_AMOUNT">Số tiền cố định</option>
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>Giá trị <span class="text-danger">*</span></label>
                    <input type="number" step="0.01" name="DISCOUNT_VALUE" id="input_value" class="form-control" required placeholder="VD: 10">
                </div>
            </div>
          </div>

          <div class="form-group">
            <label>Ngày hết hạn <span class="text-danger">*</span></label>
            <input type="datetime-local" name="EXPIRY_DATE" id="input_expiry" class="form-control" required>
          </div>

          <div class="form-group">
            <label>Trạng thái</label>
            <select name="IS_ACTIVE" id="input_active" class="form-control">
                <option value="1">Kích hoạt</option>
                <option value="0">Tạm khóa</option>
            </select>
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
          <button type="submit" class="btn btn-primary">Lưu dữ liệu</button>
        </div>
      </form>
    </div>
  </div>
</div>