@extends('front.layouts.index')
@section('title') Customer Testimonials @endsection
@section('css')
    <link href="{{ URL::asset('/assets/frontend/css/hero.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ URL::asset('/assets/frontend/css/testimonials.css') }}" rel="stylesheet" type="text/css" />
@endsection
@section('content')
<section class="page-header">
    <div class="container">
        <h1>Testimonials</h1>
    </div>
</section>

<div class="testimonials-container">
    @if($customer->count() > 0)
    <div class="testimonials-grid">
        @foreach($customer as $index => $row)
        <div class="slide {{ $index % 2 == 0 ? 'odd' : '' }}">
            <div class="testimonial-content">
                <div class="star-rating">
                    @for ($i=0; $i < $row->rate; $i++)
                    <i class="fa-sharp fa-solid fa-star"></i>
                    @endfor
                </div>
                <h2>{{$row->title}}</h2>
                <p class="grate-desc">{{$row->description}}</p>
                <p class="date-on">On {{ \Carbon\Carbon::parse($row->register_date)->format('d/m/Y') }}</p>
            </div>
            <div class="quote-image-wrapper">
                <img src="{{URL::asset ('/uploads/review')}}{{'/'}}{{$row->id}}/{{ $row->image }}" alt="{{ $row->customer_name }}" class="quote-image" />
                <div class="quote-icon"><img src="{{ URL::asset('/assets/frontend/assets/quote.svg') }}" alt="" /></div>
            </div>
        </div>
        @endforeach
    </div>
    @else
    <div class="no-testimonials">
        <h3>No Testimonials Available</h3>
        <p>Check back later for customer reviews and testimonials.</p>
    </div>
    @endif
</div>
@endsection
@section('script')
@endsection
