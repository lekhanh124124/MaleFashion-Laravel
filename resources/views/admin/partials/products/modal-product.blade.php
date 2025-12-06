<div class="modal fade" id="modalProduct" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    {{-- Form dùng chung --}}
    <form class="modal-content" id="formProduct" action="" method="post">
      @csrf
      {{-- Input ẩn để giả lập method PUT khi sửa --}}
      <input type="hidden" name="_method" id="method_spoof" value="">

      <div class="modal-header">
        <div>
            <h5 class="modal-title" id="modalTitle">Sản phẩm</h5>
            <div class="modal-subtitle">Quản lý thông tin chung</div>
        </div>
        <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
      </div>
      
      <div class="modal-body">
        <div class="form-row">
          <div class="form-group col-md-12">
            <label>Tên sản phẩm <span class="text-danger">*</span></label>
            <input type="text" name="PRODUCT_NAME" id="inputName" class="form-control" required>
          </div>
          
          <div class="form-group col-md-6">
            <label>Danh mục</label>
            <select name="CATEGORY_ID" id="inputCat" class="form-control">
                <option value="">-- Chọn danh mục --</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->CATEGORY_ID }}">{{ $cat->CATEGORY_NAME }}</option>
                @endforeach
            </select>
          </div>
          
          <div class="form-group col-md-6">
            <label>Thương hiệu</label>
            <select name="BRAND_ID" id="inputBrand" class="form-control">
                <option value="">-- Chọn thương hiệu --</option>
                @foreach($brands as $brand)
                    <option value="{{ $brand->BRAND_ID }}">{{ $brand->BRAND_NAME }}</option>
                @endforeach
            </select>
          </div>

          <div class="form-group col-md-12">
            <label>Mô tả ngắn</label>
            <input type="text" name="SHORT_DESCRIPTION" id="inputShort" class="form-control" maxlength="500">
          </div>
          
          <div class="form-group col-md-12">
            <label>Mô tả chi tiết</label>
            <textarea name="DESCRIPTION" id="inputDesc" class="form-control" rows="5"></textarea>
          </div>
          
          <div class="form-group col-md-12">
            <label>Cài đặt hiển thị (Flags)</label>
            <div class="d-flex flex-wrap mt-2">
              <label class="mr-4 cursor-pointer">
                  <input type="checkbox" name="IS_NEW_ARRIVAL" id="checkNew" value="1"> New Arrival
              </label>
              <label class="mr-4 cursor-pointer">
                  <input type="checkbox" name="IS_HOT_SALE" id="checkHot" value="1"> Hot Sale
              </label>
              <label class="mr-4 cursor-pointer">
                  <input type="checkbox" name="IS_BEST_SELLER" id="checkBest" value="1"> Best Seller
              </label>
            </div>
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