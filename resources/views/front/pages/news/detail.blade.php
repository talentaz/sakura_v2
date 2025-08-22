@extends('front.layouts.index')
@section('title') {{ $news->title }} @endsection
@section('css')
    <link href="{{ URL::asset('/assets/frontend/css/page-header.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ URL::asset('/assets/frontend/css/news-detail.css') }}" rel="stylesheet" type="text/css" />
    <style>
/* Page-specific banner background override */
.news-banner {
    background: url('{{ URL::asset("/assets/_frontend/images/banner.png") }}') center/cover !important;
    min-height: 300px;
}
</style>
@endsection
@section('content')
<section class="news-banner">
    
</section>

<div class="news-detail-container">

    <!-- News Detail Header -->
    <div class="news-detail-header">
        <p class="news-detail-date">{{ \Carbon\Carbon::parse($news->date)->format('F d, Y') }}</p>
        <h1 class="news-detail-title">{{ $news->title }}</h1>
        
    </div>

    <!-- News Image -->
    <div class="news-detail-image">
        <img src="{{ URL::asset('/uploads/news/' . $news->id . '/' . $news->image) }}" 
             alt="{{ $news->title }}">
    </div>

    <!-- News Content -->
    <div class="news-detail-content">
        {!! $news->description !!}
    </div>

    <!-- Back to News -->
    <div class="back-to-news-container">
        <a href="{{ route('front.news') }}" class="back-to-news">
            <i class="fas fa-arrow-left"></i> Back to News
        </a>
    </div>
</div>
@endsection
@section('script')
@endsection
