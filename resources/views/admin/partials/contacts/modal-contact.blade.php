<div class="modal fade" id="modalContact" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <div><h5>Chi tiết liên hệ</h5><div class="modal-subtitle">Xem nội dung và cập nhật trạng thái</div></div>
        <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
      </div>
      <div class="modal-body">
        <div class="form-row">
          <div class="form-group col-md-4"><label>ID</label><input class="form-control" id="mc-id" readonly></div>
          <div class="form-group col-md-4"><label>User ID</label><input class="form-control" id="mc-user" readonly></div>
          <div class="form-group col-md-4"><label>Ngày gửi</label><input class="form-control" id="mc-created" readonly></div>
          
          <div class="form-group col-md-6"><label>Họ tên</label><input class="form-control" id="mc-name" readonly></div>
          <div class="form-group col-md-6"><label>Email</label><input class="form-control" id="mc-email" readonly></div>
          
          <div class="form-group col-md-12"><label>Nội dung tin nhắn</label>
            <textarea class="form-control" id="mc-message" rows="5" readonly></textarea>
          </div>
          
          <div class="form-group col-md-4">
            <label>Trạng thái xử lý</label>
            <select class="form-control font-weight-bold" id="mc-status">
                <option value="new">Mới (New)</option>
                <option value="read">Đã xem (Read)</option>
                <option value="replied">Đã trả lời (Replied)</option>
            </select>
          </div>
        </div>
        <hr>
        <h6 class="mb-2 text-primary">Phản hồi nhanh</h6>
        <div class="form-row">
          <div class="form-group col-md-12">
            <textarea class="form-control" id="mc-reply" rows="3" placeholder="Nhập nội dung phản hồi tại đây..."></textarea>
            <small class="form-help text-muted"><i class="fa fa-info-circle"></i> Hệ thống sẽ gửi email này đến khách hàng (Simulation).</small>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button class="btn btn-dark" type="button" id="btn-send-reply"><i class="fa fa-paper-plane"></i> Gửi & Cập nhật Replied</button>
        <button class="btn btn-outline-primary" type="button" id="btn-save-status">Chỉ lưu trạng thái</button>
        <button class="btn btn-outline-secondary" type="button" data-dismiss="modal">Đóng</button>
      </div>
    </div>
  </div>
</div>