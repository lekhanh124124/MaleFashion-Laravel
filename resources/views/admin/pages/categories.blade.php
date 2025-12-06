@extends('layouts.admin')

@section('content')
<div class="container mt-3">
    <h3 class="mb-4">Quản trị Danh mục sản phẩm</h3>

    <div class="row">
        <div class="col-md-6 mb-3">
            @include('admin.partials.categories.category-parent-table')
        </div>

        <div class="col-md-6 mb-3">
            @include('admin.partials.categories.category-child-table')
        </div>
    </div>
</div>
@endsection