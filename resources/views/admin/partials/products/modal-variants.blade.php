<div class="modal fade" id="modalVariants" tabindex="-1">
  <div class="modal-dialog modal-xl"> {{-- Dùng modal rộng hơn chút --}}
    <div class="modal-content">
      <div class="modal-header">
        <div>
            <h5>Quản lý Biến thể (Variants)</h5>
            <div class="modal-subtitle">Sản phẩm ID: <span id="varModalProductId"></span></div>
        </div>
        <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
      </div>
      
      <div class="modal-body">
        <div class="row">
            {{-- Cột trái: Form Thêm/Sửa --}}
            <div class="col-md-4 border-right">
                <h6 class="mb-3 text-primary" id="varFormTitle">Thêm biến thể mới</h6>
                <form id="formVariant">
                    <input type="hidden" id="varProductId">
                    <input type="hidden" id="varId"> {{-- Nếu có ID là sửa, không là thêm --}}
                    
                    <div class="form-group">
                        <label>Size</label>
                        <select class="form-control" id="varSize" required>
                            {{-- JS sẽ fill option vào đây --}}
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label>Màu sắc</label>
                        <select class="form-control" id="varColor" required>
                            {{-- JS sẽ fill option vào đây --}}
                        </select>
                    </div>

                    <div class="form-group">
                        <label>SKU (Mã kho)</label>
                        <input type="text" class="form-control" id="varSku" placeholder="VD: AO-DEN-S" required>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-6">
                            <label>Giá</label>
                            <input type="number" class="form-control" id="varPrice" value="0" min="0" required>
                        </div>
                        <div class="form-group col-6">
                            <label>Tồn kho</label>
                            <input type="number" class="form-control" id="varStock" value="0" min="0" required>
                        </div>
                    </div>

                    <div class="form-group mt-2">
                        <button type="submit" class="btn btn-dark btn-block" id="btnSaveVariant">Lưu biến thể</button>
                        <button type="button" class="btn btn-secondary btn-block d-none" id="btnCancelEdit">Hủy sửa</button>
                    </div>
                </form>
            </div>

            {{-- Cột phải: Danh sách --}}
            <div class="col-md-8">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover table-sm text-center">
                        <thead class="thead-light">
                            <tr>
                                <th>Size</th>
                                <th>Màu</th>
                                <th>SKU</th>
                                <th>Giá</th>
                                <th>Tồn</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="variantListBody">
                            {{-- JS load data --}}
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
      </div>
      
      <div class="modal-footer">
        <button class="btn btn-outline-secondary" data-dismiss="modal">Đóng</button>
      </div>
    </div>
  </div>
</div>