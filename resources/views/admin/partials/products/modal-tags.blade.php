<style>
    /* CSS để biến checkbox thành dạng badge */
    .tag-check-label {
        cursor: pointer;
        user-select: none;
        margin-right: 8px;
        margin-bottom: 8px;
    }
    .tag-check-input {
        display: none; /* Ẩn checkbox thật */
    }
    .tag-check-badge {
        display: inline-block;
        padding: 5px 12px;
        border: 1px solid #ccc;
        border-radius: 20px;
        background: #f8f9fa;
        color: #333;
        transition: all 0.2s;
    }
    /* Khi checkbox được check */
    .tag-check-input:checked + .tag-check-badge {
        background: #343a40; /* Màu dark */
        color: #fff;
        border-color: #343a40;
    }
</style>
<div class="modal fade" id="modalTags" tabindex="-1">
  <div class="modal-dialog">
    <form class="modal-content" id="formTags">
      <div class="modal-header">
        <div>
            <h5>Tags sản phẩm</h5>
            <div class="modal-subtitle">Sản phẩm ID: <span id="tagModalProductId"></span></div>
        </div>
        <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
      </div>
      
      <div class="modal-body">
        <input type="hidden" id="tagProductId">
        <div class="legend-box mb-2">Chọn các thẻ phù hợp cho sản phẩm này.</div>
        
        {{-- Vùng chứa checkbox render từ JS --}}
        <div class="d-flex flex-wrap" id="tagListContainer">
            <div class="text-center w-100 p-3"><i class="fa fa-spinner fa-spin"></i> Đang tải...</div>
        </div>
      </div>
      
      <div class="modal-footer">
        <button class="btn btn-dark" type="submit" id="btnSaveTags">Lưu Tags</button>
        <button class="btn btn-outline-secondary" data-dismiss="modal">Đóng</button>
      </div>
    </form>
  </div>
</div>