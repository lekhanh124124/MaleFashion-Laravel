<div class="modal fade" id="modalImages" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <div>
            <h5>Quản lý Ảnh sản phẩm</h5>
            <div class="modal-subtitle">Sản phẩm ID: <span id="imgModalProductId"></span></div>
        </div>
        <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
      </div>
      
      <div class="modal-body">
        <div class="card bg-light mb-3">
            <div class="card-body p-3">
                <form id="formUploadImage" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" id="uploadProductId" name="product_id">
                    <div class="form-row align-items-end">
                        <div class="col-md-7">
                            <label>Chọn file ảnh</label>
                            <input type="file" name="image" class="form-control-file" required>
                        </div>
                        <div class="col-md-3">
                            <label class="mb-1">Là Thumbnail?</label>
                            <select name="is_thumbnail" class="form-control form-control-sm">
                                <option value="0">Không</option>
                                <option value="1">Có</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-dark btn-block btn-sm" id="btnUpload">
                                <i class="fa fa-upload"></i> Upload
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="table-responsive">
          <table class="table table-bordered table-sm text-center">
            <thead class="thead-light">
                <tr>
                    <th width="50">ID</th>
                    <th>Hình ảnh</th>
                    <th>Đường dẫn (URL)</th>
                    <th>Vai trò</th>
                    <th width="150">Thao tác</th>
                </tr>
            </thead>
            <tbody id="imageListBody">
                </tbody>
          </table>
        </div>
      </div>
      
      <div class="modal-footer">
        <button class="btn btn-outline-secondary" data-dismiss="modal">Đóng</button>
      </div>
    </div>
  </div>
</div>