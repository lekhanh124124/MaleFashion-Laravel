@extends('layouts.malefashion')

@section('title','Shopping Cart')

@section('content')
@include('partials.cart.breadcrumb')

<section class="shopping-cart spad">
    <div class="container">
        <div class="row">
            <div class="col-lg-8">
                @include('partials.cart.table')
                @include('partials.cart.actions')
            </div>
            <div class="col-lg-4">
                @include('partials.cart.discount')
                @include('partials.cart.total')
            </div>
        </div>
    </div>
</section>
@endsection