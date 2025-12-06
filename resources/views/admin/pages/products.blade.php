@extends('layouts.admin')

@section('content')
<div id="pane-products" class="mt-2">
  {{-- Hiển thị thông báo --}}
  @if(session('success'))
  <div class="alert alert-success">{{ session('success') }}</div>
  @endif

  <div class="sticky-toolbar d-flex flex-wrap align-items-center gap-2">
    <h5 class="mb-0 mr-auto">Sản phẩm</h5>
    <button class="btn btn-dark" onclick="openAddModal()">
      <i class="fa fa-plus"></i> Thêm sản phẩm
    </button>

    <form method="GET" class="ml-auto d-flex">
      <input name="search" class="form-control" placeholder="Tìm tên sản phẩm..." value="{{ request('search') }}">
      <button class="btn btn-outline-secondary ml-1">Tìm</button>
    </form>
  </div>

  <div class="table-responsive mt-3">
    <table class="table table-striped table-hover">
      <thead class="thead-light">
        <tr>
          <th>ID</th>
          <th>Tên sản phẩm</th>
          <th>Danh mục</th>
          <th>Thương hiệu</th>
          <th>Flags</th>
          <th>Ngày tạo</th>
          <th class="text-right">Thao tác</th>
        </tr>
      </thead>
      <tbody>
        @foreach($products as $p)
        <tr>
          <td>{{ $p->PRODUCT_ID }}</td>
          <td>
            {{ $p->PRODUCT_NAME }}
            <div class="soft-label text-muted small">{{ Str::limit($p->SHORT_DESCRIPTION, 30) }}</div>
          </td>
          <td>{{ $p->category->CATEGORY_NAME ?? '---' }}</td>
          <td>{{ $p->brand->BRAND_NAME ?? '---' }}</td>
          <td>
            @if($p->IS_NEW_ARRIVAL) <span class="badge badge-info">New</span> @endif
            @if($p->IS_HOT_SALE) <span class="badge badge-danger">Hot</span> @endif
            @if($p->IS_BEST_SELLER) <span class="badge badge-warning">Best</span> @endif
          </td>
          <td>{{ \Carbon\Carbon::parse($p->CREATED_AT)->format('d/m/Y') }}</td>
          <td class="text-right">
            <button class="btn btn-sm btn-outline-primary btn-edit"
              data-id="{{ $p->PRODUCT_ID }}"
              data-name="{{ $p->PRODUCT_NAME }}"
              data-cat="{{ $p->CATEGORY_ID }}"
              data-brand="{{ $p->BRAND_ID }}"
              data-short="{{ $p->SHORT_DESCRIPTION }}"
              data-desc="{{ $p->DESCRIPTION }}"
              data-new="{{ $p->IS_NEW_ARRIVAL }}"
              data-hot="{{ $p->IS_HOT_SALE }}"
              data-best="{{ $p->IS_BEST_SELLER }}">Sửa</button>

            {{-- Các nút chức năng khác tạm thời disable hoặc để sau --}}
            <button class="btn btn-sm btn-outline-secondary btn-variants" data-id="{{ $p->PRODUCT_ID }}">Biến thể</button>
            <button class="btn btn-sm btn-outline-secondary btn-images" data-id="{{ $p->PRODUCT_ID }}">Ảnh</button>
            <button class="btn btn-sm btn-outline-secondary btn-tags" data-id="{{ $p->PRODUCT_ID }}">Tags</button>
            <form action="{{ route('admin.products.destroy', $p->PRODUCT_ID) }}" method="POST" style="display:inline-block" onsubmit="return confirm('Xóa sản phẩm này?')">
              @csrf @method('DELETE')
              <button class="btn btn-sm btn-outline-danger">Xóa</button>
            </form>
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>

    {{-- Phân trang --}}
    <div class="mt-2">
      {{ $products->links() }}
    </div>
  </div>
</div>

@include('admin.partials.products.modal-product')
@include('admin.partials.products.modal-variants')
@include('admin.partials.products.modal-images')
@include('admin.partials.products.modal-tags')

@endsection

@section('scripts')
<script>
  function openAddModal() {
    $('#formProduct').attr('action', "{{ route('admin.products.store') }}");
    $('#method_spoof').val('');
    $('#modalTitle').text('Thêm sản phẩm mới');
    $('#formProduct')[0].reset();
    $('#modalProduct').modal('show');
  }

  document.querySelectorAll('.btn-edit').forEach(btn => {
    btn.addEventListener('click', function() {
      let id = this.dataset.id;
      let url = "{{ route('admin.products.update', ':id') }}".replace(':id', id);
      $('#formProduct').attr('action', url);
      $('#method_spoof').val('PUT');
      $('#modalTitle').text('Cập nhật sản phẩm #' + id);

      $('#inputName').val(this.dataset.name);
      $('#inputCat').val(this.dataset.cat);
      $('#inputBrand').val(this.dataset.brand);
      $('#inputShort').val(this.dataset.short);
      $('#inputDesc').val(this.dataset.desc);
      $('#checkNew').prop('checked', this.dataset.new == "1");
      $('#checkHot').prop('checked', this.dataset.hot == "1");
      $('#checkBest').prop('checked', this.dataset.best == "1");

      $('#modalProduct').modal('show');
    });
  });

  // --- Phần Code Mới cho PRODUCT IMAGES ---

  // 1. Mở modal và load ảnh
  $(document).on('click', '.btn-images', function() {
    let productId = $(this).data('id');
    $('#imgModalProductId').text(productId);
    $('#uploadProductId').val(productId); // Set ID vào form upload ẩn

    loadImages(productId);
    $('#modalImages').modal('show');
  });

  // Hàm load ảnh từ server
  function loadImages(productId) {
    let url = "{{ route('admin.product.images.index', ':id') }}".replace(':id', productId);
    let tbody = $('#imageListBody');
    tbody.html('<tr><td colspan="5">Đang tải...</td></tr>');

    $.get(url, function(data) {
      tbody.empty();
      if (data.length === 0) {
        tbody.html('<tr><td colspan="5" class="text-muted">Chưa có ảnh nào</td></tr>');
        return;
      }

      data.forEach(img => {
        let badge = img.IS_THUMBNAIL == 1 ?
          '<span class="badge badge-success">Thumbnail</span>' :
          '<button class="btn btn-sm btn-light btn-set-thumb" data-id="' + img.IMAGE_ID + '">Đặt làm Thumb</button>';

        let row = `
                    <tr>
                        <td>${img.IMAGE_ID}</td>
                        <td><img src="${img.IMAGE_URL}" style="height: 50px; border: 1px solid #ddd;"></td>
                        <td class="text-left small text-muted align-middle">${img.IMAGE_URL}</td>
                        <td class="align-middle">${badge}</td>
                        <td class="align-middle">
                            <button class="btn btn-sm btn-outline-danger btn-del-img" data-id="${img.IMAGE_ID}">Xóa</button>
                        </td>
                    </tr>
                `;
        tbody.append(row);
      });
    });
  }

  // 2. Xử lý Upload (AJAX)
  $('#formUploadImage').on('submit', function(e) {
    e.preventDefault();
    let productId = $('#uploadProductId').val();
    let formData = new FormData(this);
    let url = "{{ route('admin.product.images.store', ':id') }}".replace(':id', productId);
    let btn = $('#btnUpload');

    btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Uploading...');

    $.ajax({
      url: url,
      type: 'POST',
      data: formData,
      contentType: false,
      processData: false,
      success: function(res) {
        if (res.success) {
          loadImages(productId); // Reload lại list
          $('#formUploadImage')[0].reset(); // Reset form input
        } else {
          alert('Lỗi: ' + res.message);
        }
        btn.prop('disabled', false).html('<i class="fa fa-upload"></i> Upload');
      },
      error: function(err) {
        alert('Có lỗi xảy ra khi upload!');
        console.log(err);
        btn.prop('disabled', false).html('<i class="fa fa-upload"></i> Upload');
      }
    });
  });

  // 3. Xử lý Xóa ảnh
  $(document).on('click', '.btn-del-img', function() {
    if (!confirm('Bạn chắc chắn muốn xóa ảnh này?')) return;

    let imgId = $(this).data('id');
    let productId = $('#uploadProductId').val();
    let url = "{{ route('admin.product.images.destroy', ':id') }}".replace(':id', imgId);
    let token = $('meta[name="csrf-token"]').attr('content'); // Laravel CSRF

    $.ajax({
      url: url,
      type: 'DELETE',
      data: {
        _token: "{{ csrf_token() }}"
      },
      success: function(res) {
        loadImages(productId);
      },
      error: function() {
        alert('Không thể xóa ảnh này.');
      }
    });
  });

  // 4. Xử lý Set Thumbnail
  $(document).on('click', '.btn-set-thumb', function() {
    let imgId = $(this).data('id');
    let productId = $('#uploadProductId').val();
    let url = "{{ route('admin.product.images.thumbnail', ':id') }}".replace(':id', imgId);

    $.post(url, {
      _token: "{{ csrf_token() }}"
    }, function(res) {
      loadImages(productId);
    });
  });

  // --- Phần Code Mới cho PRODUCT VARIANTS ---

  // 1. Mở modal và load dữ liệu
  $(document).on('click', '.btn-variants', function() {
    let productId = $(this).data('id');
    $('#varModalProductId').text(productId);
    $('#varProductId').val(productId);

    // Reset form về trạng thái thêm mới
    resetVariantForm();

    loadVariants(productId);
    $('#modalVariants').modal('show');
  });

  // Hàm load variants + sizes + colors
  function loadVariants(productId) {
    let url = "{{ route('admin.product.variants.index', ':id') }}".replace(':id', productId);
    let tbody = $('#variantListBody');

    // Hiển thị trạng thái đang tải
    tbody.html('<tr><td colspan="6">Đang tải...</td></tr>');

    $.get(url, function(res) {
      // 1. Fill Dropdowns (Sizes & Colors)
      let sizeSelect = $('#varSize');
      let colorSelect = $('#varColor');

      // --- Xử lý Size ---
      sizeSelect.empty();
      sizeSelect.append('<option value="">-- Chọn Size --</option>');
      if (res.sizes && res.sizes.length > 0) {
        res.sizes.forEach(s => {
          sizeSelect.append(`<option value="${s.SIZE_ID}">${s.SIZE_NAME}</option>`);
        });
      }

      // --- Xử lý Color ---
      colorSelect.empty();
      colorSelect.append('<option value="">-- Chọn Màu --</option>');
      if (res.colors && res.colors.length > 0) {
        res.colors.forEach(c => {
          colorSelect.append(`<option value="${c.COLOR_ID}" data-code="${c.COLOR_CODE}">${c.COLOR_NAME}</option>`);
        });
      }

      // [QUAN TRỌNG] Cập nhật lại giao diện Dropdown (Thử lần lượt các lệnh phổ biến)
      // Nếu template dùng NiceSelect
      if ($.fn.niceSelect) {
        sizeSelect.niceSelect('update');
        colorSelect.niceSelect('update');
      }
      // Nếu template dùng Select2
      if ($.fn.select2) {
        sizeSelect.trigger('change');
        colorSelect.trigger('change');
      }
      // Nếu template dùng Bootstrap SelectPicker
      if ($.fn.selectpicker) {
        sizeSelect.selectpicker('refresh');
        colorSelect.selectpicker('refresh');
      }

      // 2. Render Table danh sách biến thể
      tbody.empty();
      if (res.variants.length === 0) {
        tbody.html('<tr><td colspan="6" class="text-muted">Chưa có biến thể nào</td></tr>');
      } else {
        res.variants.forEach(v => {
          let sizeName = v.size ? v.size.SIZE_NAME : 'N/A';
          let colorName = v.color ? v.color.COLOR_NAME : 'N/A';
          let colorCode = v.color ? v.color.COLOR_CODE : '#ccc';
          let colorBox = `<span class="d-inline-block border" style="width:12px;height:12px;background:${colorCode};margin-right:4px;"></span>`;

          let row = `
                <tr>
                    <td>${sizeName}</td>
                    <td class="text-left">${colorBox} ${colorName}</td>
                    <td>${v.SKU}</td>
                    <td>${new Intl.NumberFormat().format(v.PRICE)}</td>
                    <td>${v.STOCK}</td>
                    <td>
                        <button class="btn btn-sm btn-outline-primary btn-edit-variant" 
                            data-id="${v.VARIANT_ID}"
                            data-size="${v.SIZE_ID}"
                            data-color="${v.COLOR_ID}"
                            data-sku="${v.SKU}"
                            data-price="${v.PRICE}"
                            data-stock="${v.STOCK}">Sửa</button>
                        <button class="btn btn-sm btn-outline-danger btn-del-variant" data-id="${v.VARIANT_ID}">Xóa</button>
                    </td>
                </tr>
            `;
          tbody.append(row);
        });
      }
    }).fail(function(jqXHR, textStatus, errorThrown) {
      console.error("Lỗi AJAX:", textStatus, errorThrown);
      alert('Không thể tải dữ liệu. Vui lòng kiểm tra Console (F12).');
    });
  }

  // 2. Xử lý Submit Form (Thêm hoặc Sửa)
  $('#formVariant').on('submit', function(e) {
    e.preventDefault();

    let productId = $('#varProductId').val();
    let varId = $('#varId').val(); // Nếu có ID -> Đang sửa
    let isEdit = varId !== "";

    let url = isEdit ?
      "{{ route('admin.product.variants.update', ':id') }}".replace(':id', varId) :
      "{{ route('admin.product.variants.store', ':id') }}".replace(':id', productId);

    let method = isEdit ? 'PUT' : 'POST';

    let data = {
      SIZE_ID: $('#varSize').val(),
      COLOR_ID: $('#varColor').val(),
      SKU: $('#varSku').val(),
      PRICE: $('#varPrice').val(),
      STOCK: $('#varStock').val(),
      _token: "{{ csrf_token() }}"
    };

    $.ajax({
      url: url,
      type: method,
      data: data,
      success: function(res) {
        if (res.success) {
          loadVariants(productId); // Reload bảng
          resetVariantForm(); // Reset form về thêm mới
          if (isEdit) alert('Cập nhật thành công!');
        }
      },
      error: function(err) {
        let errors = err.responseJSON.errors;
        let msg = 'Lỗi: ';
        for (let k in errors) {
          msg += errors[k][0] + '\n';
        }
        alert(msg);
      }
    });
  });

  // 3. Xử lý nút Sửa (trên bảng) -> Đẩy data lên form
  $(document).on('click', '.btn-edit-variant', function() {
    let d = this.dataset;
    $('#varId').val(d.id);
    $('#varSize').val(d.size);
    $('#varColor').val(d.color);
    $('#varSku').val(d.sku);
    $('#varPrice').val(d.price);
    $('#varStock').val(d.stock);

    // Đổi giao diện form
    $('#varFormTitle').text('Cập nhật biến thể #' + d.id);
    $('#btnSaveVariant').text('Lưu thay đổi').removeClass('btn-dark').addClass('btn-primary');
    $('#btnCancelEdit').removeClass('d-none');
  });

  // 4. Xử lý nút Hủy Sửa -> Reset form
  $('#btnCancelEdit').click(function() {
    resetVariantForm();
  });

  function resetVariantForm() {
    $('#varId').val('');
    $('#varFormTitle').text('Thêm biến thể mới');
    $('#btnSaveVariant').text('Lưu biến thể').removeClass('btn-primary').addClass('btn-dark');
    $('#btnCancelEdit').addClass('d-none');

    // Reset inputs trừ productId
    $('#varSize').val('');
    $('#varColor').val('');
    $('#varSku').val('');
    $('#varPrice').val('0');
    $('#varStock').val('0');
  }

  // 5. Xử lý Xóa biến thể
  $(document).on('click', '.btn-del-variant', function() {
    if (!confirm('Bạn có chắc muốn xóa biến thể này?')) return;

    let varId = $(this).data('id');
    let productId = $('#varProductId').val();
    let url = "{{ route('admin.product.variants.destroy', ':id') }}".replace(':id', varId);

    $.ajax({
      url: url,
      type: 'DELETE',
      data: {
        _token: "{{ csrf_token() }}"
      },
      success: function(res) {
        loadVariants(productId);
      },
      error: function() {
        alert('Có lỗi xảy ra khi xóa.');
      }
    });
  });

  // --- Phần Code Mới cho PRODUCT TAGS ---

  // 1. Mở modal và load tags
  $(document).on('click', '.btn-tags', function() {
    let productId = $(this).data('id');
    $('#tagModalProductId').text(productId);
    $('#tagProductId').val(productId);

    let url = "{{ route('admin.product.tags.index', ':id') }}".replace(':id', productId);
    let container = $('#tagListContainer');

    container.html('<div class="text-center w-100 p-3">Đang tải dữ liệu...</div>');
    $('#modalTags').modal('show');

    $.get(url, function(res) {
      container.empty();

      if (res.all_tags.length === 0) {
        container.html('<div class="text-muted">Chưa có Tag nào trong hệ thống. Hãy tạo Tag trước.</div>');
        return;
      }

      // Render list checkboxes
      res.all_tags.forEach(tag => {
        // Kiểm tra xem tag này có trong danh sách tag của sản phẩm không
        let isChecked = res.current_tag_ids.includes(tag.TAG_ID) ? 'checked' : '';

        let html = `
                    <label class="tag-check-label">
                        <input type="checkbox" name="tags[]" value="${tag.TAG_ID}" class="tag-check-input" ${isChecked}>
                        <span class="tag-check-badge">${tag.TAG_NAME}</span>
                    </label>
                `;
        container.append(html);
      });
    });
  });

  // 2. Lưu Tags
  $('#formTags').on('submit', function(e) {
    e.preventDefault();

    let productId = $('#tagProductId').val();
    let url = "{{ route('admin.product.tags.update', ':id') }}".replace(':id', productId);
    let formData = $(this).serialize(); // Lấy tất cả input name="tags[]"
    let btn = $('#btnSaveTags');

    // Thêm csrf token vào data serialize nếu chưa có (thường form đã có thẻ @csrf nhưng ở modal ta chưa thêm)
    // Cách nhanh nhất là gửi kèm _token trong object data của ajax hoặc append vào string
    formData += '&_token={{ csrf_token() }}';

    btn.prop('disabled', true).text('Đang lưu...');

    $.post(url, formData, function(res) {
      if (res.success) {
        alert('Đã cập nhật Tags thành công!');
        $('#modalTags').modal('hide');
      }
      btn.prop('disabled', false).text('Lưu Tags');
    }).fail(function() {
      alert('Có lỗi xảy ra!');
      btn.prop('disabled', false).text('Lưu Tags');
    });
  });
</script>
@endsection