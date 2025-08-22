@extends('front.layouts.index')
@section('title') Latest News @endsection
@section('css')
    <link href="{{ URL::asset('/assets/frontend/css/hero.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ URL::asset('/assets/frontend/css/news-layout.css') }}" rel="stylesheet" type="text/css" />
@endsection
@section('content')
<section class="page-header">
    <div class="container">
        <h1>News</h1>
    </div>
</section>

<div class="news-layout-container">
    @if($news->count() > 0)
    <!-- Hero Layout: Large image left, content right -->
    <div class="news-hero-layout">
        <div class="hero-image-section">
            <a href="{{ route('front.news.detail', ['id' => $news[0]->id]) }}">
                <img src="{{ URL::asset ('/uploads/news/' . $news[0]->id . '/' . $news[0]->image) }}"
                     alt="{{ $news[0]->title }}">
                    
            </a>
        </div>
        <div class="hero-content-section">
            <p class="hero-date">On {{ \Carbon\Carbon::parse($news[0]->date)->format('d/m/Y') }}</p>
            <h2 class="hero-title">{{ $news[0]->title }}</h2>
            <p class="hero-description">{{ Str::limit(strip_tags($news[0]->description), 200) }}</p>
            <a href="{{ route('front.news.detail', ['id' => $news[0]->id]) }}" class="hero-read-more">Read More</a>
        </div>
    </div>

    <!-- Three Column Grid Below -->
    @if($news->count() > 1)
    <div class="news-three-columns">
        @foreach($news->skip(1) as $article)
        <div class="news-column">
            <div class="column-image">
                <a href="{{ route('front.news.detail', ['id' => $article->id]) }}">
                    <img src="{{ URL::asset('/uploads/news/' . $article->id . '/' . $article->image) }}"
                         alt="{{ $article->title }}">
                </a>
            </div>
            <div class="column-content">
                <p class="column-date">On {{ \Carbon\Carbon::parse($article->date)->format('d/m/Y') }}</p>
                <h3 class="column-title">{{ $article->title }}</h3>
                <p class="column-description">{{ Str::limit(strip_tags($article->description), 120) }}</p>
                <a href="{{ route('front.news.detail', ['id' => $article->id]) }}" class="column-read-more">Read More</a>
            </div>
        </div>
        @endforeach
    </div>
    @endif

    @else
    <!-- No News Available -->
    <div class="no-news">
        <h3>No News Available</h3>
        <p>Check back later for the latest updates and news.</p>
    </div>
    @endif
</div>
@endsection
@section('script')
@endsection
