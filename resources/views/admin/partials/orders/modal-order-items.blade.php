<div class="modal fade" id="modalOrderItems" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <div>
          <h5>Chi tiết đơn hàng</h5>
          <div class="modal-subtitle text-muted" id="modalItemsUser">Loading...</div>
        </div>
        <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
      </div>
      <div class="modal-body">

        <div class="table-responsive">
          <table class="table table-bordered table-sm">
            <thead class="thead-light">
              <tr>
                <th>ID</th>
                <th class="text-center" width="80">Ảnh</th>
                <th>Sản phẩm / Biến thể</th>
                <th class="text-center">SL</th>
                <th class="text-right">Đơn giá</th>
                <th class="text-right">Tổng</th>
              </tr>
            </thead>
            <tbody id="modalItemsTableBody">
              {{-- JS sẽ render vào đây --}}
            </tbody>
            <tfoot>
              <tr>
                <th colspan="5" class="text-right">Tạm tính</th>
                <th class="text-right" id="valSubtotal">0</th>
              </tr>
              <tr>
                <th colspan="5" class="text-right">
                  Giảm giá <span id="valCouponCode" class="text-primary"></span> </th>
                <th class="text-right" id="valDiscount">0</th>
              </tr>
              <tr>
                <th colspan="5" class="text-right">Thành tiền</th>
                <th class="text-right text-danger h6 mb-0" id="valFinal">0</th>
              </tr>
            </tfoot>
          </table>
        </div>
        <hr>
        <div class="row">
          <div class="col-md-6">
            <h6>Thông tin khác</h6>
            <div><strong>Thanh toán:</strong> <span id="modalItemsPayment">...</span></div>
            <div><strong>Ghi chú:</strong> <span id="modalItemsNote">...</span></div>
          </div>
          <div class="col-md-6">
            <h6>Địa chỉ giao hàng</h6>
            <div class="p-2 bg-light rounded border" id="modalItemsShipping">...</div>
          </div>
        </div>
      </div>
      <div class="modal-footer"><button class="btn btn-outline-secondary" data-dismiss="modal">Đóng</button></div>
    </div>
  </div>
</div>