<div class="modal fade" id="modalBanner" tabindex="-1">
  <div class="modal-dialog modal-lg">
    {{-- Thêm enctype để upload ảnh --}}
    <form class="modal-content" id="formBanner" action="" method="post" enctype="multipart/form-data">
      @csrf
      <input type="hidden" name="_method" id="method_spoof" value="">

      <div class="modal-header">
        <h5 class="modal-title" id="modalTitle">Banner</h5>
        <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
      </div>
      <div class="modal-body">
        <div class="form-row">
          <div class="form-group col-md-6">
            <label>Tiêu đề (TITLE) <span class="text-danger">*</span></label>
            <input type="text" name="TITLE" id="inputTitle" class="form-control" required>
          </div>
          <div class="form-group col-md-6">
            <label>Phụ đề (SUBTITLE)</label>
            <input type="text" name="SUBTITLE" id="inputSubtitle" class="form-control">
          </div>
          
          <div class="form-group col-md-8">
            <label>Hình ảnh (IMAGE_URL)</label>
            <input type="file" name="IMAGE_URL" class="form-control-file">
            <small class="text-muted">Để trống nếu không thay đổi ảnh khi sửa.</small>
          </div>
          
          <div class="form-group col-md-4">
            <label>Trạng thái</label>
            <select name="IS_ACTIVE" id="inputActive" class="form-control">
                <option value="1">Hiển thị</option>
                <option value="0">Ẩn</option>
            </select>
          </div>
          
          <div class="form-group col-md-6">
            <label>Link liên kết (LINK_URL)</label>
            <input type="text" name="LINK_URL" id="inputLink" class="form-control" placeholder="/shop?category=men">
          </div>
          
          <div class="form-group col-md-6">
            <label>Danh mục (CATEGORY_ID)</label>
            <select name="CATEGORY_ID" id="inputCat" class="form-control">
                <option value="">-- Không liên kết --</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->CATEGORY_ID }}">[{{ $cat->CATEGORY_ID }}] {{ $cat->CATEGORY_NAME }}</option>
                @endforeach
            </select>
          </div>
          
          <div class="form-group col-md-6">
            <label>Vị trí (POSITION) <span class="text-danger">*</span></label>
            <input type="text" name="POSITION" id="inputPos" class="form-control" placeholder="home.hero" required>
            <small class="form-text text-muted">Vd: home.slider, shop.top, footer.promo</small>
          </div>
          
          <div class="form-group col-md-6">
            <label>Thứ tự (DISPLAY_ORDER)</label>
            <input type="number" name="DISPLAY_ORDER" id="inputOrder" class="form-control" value="0">
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button class="btn btn-dark" type="submit">Lưu dữ liệu</button>
        <button class="btn btn-outline-secondary" type="button" data-dismiss="modal">Đóng</button>
      </div>
    </form>
  </div>
</div>