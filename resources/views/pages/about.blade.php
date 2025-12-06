
@extends('layouts.malefashion')

@section('title', 'About Us')

@section('content')
    @include('partials.about.breadcrumb')
    @include('partials.about.intro')
    @include('partials.about.testimonial')
    @include('partials.about.counter')
    @include('partials.about.team')
    @include('partials.about.clients')
@endsection