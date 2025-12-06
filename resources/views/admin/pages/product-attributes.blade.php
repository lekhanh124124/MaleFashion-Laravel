@extends('layouts.admin')

@section('content')
<div class="container mt-3">
    <h3 class="mb-4">Quản trị Thuộc tính sản phẩm</h3>
    <div class="row">
        <div class="col-md-6 mb-4">
            <div class="card h-100">
                @include('admin.partials.attributes.brand-table')
            </div>
        </div>
        <div class="col-md-6 mb-4">
            <div class="card h-100">
                @include('admin.partials.attributes.tag-table')
            </div>
        </div>
        <div class="col-md-6 mb-4">
            <div class="card h-100">
                @include('admin.partials.attributes.size-table')
            </div>
        </div>
        <div class="col-md-6 mb-4">
            <div class="card h-100">
                @include('admin.partials.attributes.color-table')
            </div>
        </div>
    </div>
</div>
@endsection