<div class="modal fade" id="modalUser" tabindex="-1">
  <div class="modal-dialog">
    <form class="modal-content" action="#" method="post">
      @csrf
      <div class="modal-header">
        <div>
          <h5 data-modal-title>Người dùng</h5>
          <div class="modal-subtitle">Form</div>
        </div>
        <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
      </div>
      <div class="modal-body">
        <div class="form-row">
          <div class="form-group col-md-6">
            <label>FIRST_NAME</label>
            <input name="FIRST_NAME" class="form-control" required>
          </div>
          <div class="form-group col-md-6">
            <label>LAST_NAME</label>
            <input name="LAST_NAME" class="form-control" required>
          </div>
          <div class="form-group col-md-12">
            <label>EMAIL</label>
            <input name="EMAIL" type="email" class="form-control" required>
          </div>
          <div class="form-group col-md-6">
            <label>PHONE</label>
            <input name="PHONE" class="form-control">
          </div>
            <div class="form-group col-md-6">
              <label>ROLE</label>
              <select name="ROLE" class="form-control">
                <option value="staff">staff</option>
                <option value="admin">admin</option>
              </select>
            </div>
          <div class="form-group col-md-12">
            <label>ADDRESS</label>
            <input name="ADDRESS" class="form-control">
          </div>
          <div class="form-group col-md-6">
            <label>STATUS</label>
            <select name="STATUS" class="form-control">
              <option value="active">active</option>
              <option value="inactive">inactive</option>
            </select>
          </div>
          <div class="form-group col-md-6" data-password-group>
            <label>PASSWORD</label>
            <input name="PASSWORD" type="password" class="form-control" placeholder="Nhập mật khẩu mới (nếu đổi)">
            <small class="text-muted">Khi tạo mới: tự động là 123456789.</small>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button class="btn btn-dark" type="submit">Lưu</button>
        <button class="btn btn-outline-secondary" type="button" data-dismiss="modal">Đóng</button>
      </div>
    </form>
  </div>
</div>