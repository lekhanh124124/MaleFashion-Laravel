<div class="modal fade" id="modalOrderEdit" tabindex="-1">
  <div class="modal-dialog">
    <form class="modal-content" id="formOrderEdit">
      <div class="modal-header">
        <h5>Sửa đơn hàng</h5>
        <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
      </div>
      <div class="modal-body">
        <div class="form-group">
          <label>Trạng thái (STATUS)</label>
          <select class="form-control" id="editStatus">
              <option value="Pending">Pending (Chờ xử lý)</option>
              <option value="Processing">Processing (Đang xử lý)</option>
              <option value="Shipped">Shipped (Đang giao)</option>
              <option value="Completed">Completed (Hoàn tất)</option>
              <option value="Cancelled">Cancelled (Đã hủy)</option>
          </select>
        </div>
        
        <div class="form-group">
          <label>Phương thức thanh toán</label>
          {{-- Disabled để không cho sửa --}}
          <input type="text" class="form-control" id="editPaymentMethod" disabled style="background-color: #e9ecef;">
          <small class="text-muted">Không thể thay đổi phương thức thanh toán.</small>
        </div>
        
        <div class="form-group">
          <label>Ghi chú đơn hàng (ORDER_NOTES)</label>
          <textarea class="form-control" id="editNotes" rows="3"></textarea>
        </div>
      </div>
      <div class="modal-footer">
        <button class="btn btn-dark" type="button" id="btnSaveOrder">Lưu thay đổi</button>
        <button class="btn btn-outline-secondary" data-dismiss="modal">Đóng</button>
      </div>
    </form>
  </div>
</div>