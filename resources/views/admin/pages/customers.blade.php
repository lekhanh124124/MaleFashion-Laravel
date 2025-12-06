@extends('layouts.admin')

@section('content')
@php
  $q = $q ?? request('q') ?? '';
  $customers = $customers ?? collect();
@endphp
<div id="pane-customers" class="mt-2">
  @if(session('ok'))<div class="alert alert-success py-1 mb-2">{{ session('ok') }}</div>@endif
  @if(session('err'))<div class="alert alert-danger py-1 mb-2">{{ session('err') }}</div>@endif
  <div class="sticky-toolbar d-flex align-items-center">
    <h5 class="mb-0 mr-auto">Khách hàng</h5>
    <form class="ml-auto" method="get" action="{{ route('admin.customers.index') }}">
      <input name="q" value="{{ $q }}" class="form-control" placeholder="Tìm tên / email / phone">
    </form>
  </div>
  <div class="table-responsive mt-3">
    <table class="table table-striped">
      <thead class="thead-light">
        <tr>
          <th>ID</th><th>Họ tên</th><th>Email</th><th>Phone</th><th>Trạng thái</th><th>Ngày tạo</th><th class="text-right">Sửa</th>
        </tr>
      </thead>
      <tbody>
        @forelse($customers as $u)
        <tr>
          <td>{{ $u->USER_ID }}</td>
          <td>{{ $u->FIRST_NAME }} {{ $u->LAST_NAME }}</td>
          <td>{{ $u->EMAIL }}</td>
          <td>{{ $u->PHONE }}</td>
          <td>
            <form method="post" action="{{ route('admin.customers.toggle',$u) }}">
              @csrf
              <button class="btn btn-sm {{ $u->STATUS==='active'?'btn-success':'btn-secondary' }}" title="Đổi trạng thái">
                {{ $u->STATUS }}
              </button>
            </form>
          </td>
          <td>{{ $u->CREATED_AT }}</td>
          <td class="text-right">
            <span class="text-muted small">Chỉ đổi trạng thái</span>
          </td>
        </tr>
        @empty
        <tr><td colspan="7" class="text-center text-muted">Không có dữ liệu</td></tr>
        @endforelse
      </tbody>
    </table>
    @if(method_exists($customers,'links'))
      <div class="pb-2 px-2">{{ $customers->links() }}</div>
    @endif
  </div>
</div>
@endsection