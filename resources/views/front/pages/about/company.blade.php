@extends('front.layouts.index')
@section('title') Company @endsection
@section('css')
<link rel="stylesheet" href="{{ URL::asset('/assets/frontend/css/hero.css') }}" />
<link rel="stylesheet" href="{{ URL::asset('/assets/frontend/css/company.css') }}" />
@endsection
@section('content')
<section class="page-header">
    <div class="container">
        <h1>About COMPANY</h1>
    </div>
</section>

<!-- Welcome Section -->
<section class="welcome-section">
    <div class="container">
        <div class="welcome-content">
            <div class="welcome-text">
                <h2>Welcome to SAKURA MOTORS</h2>
                <p>We believe in offering you the best customer service possible. We have been in the vehicle trading business for over 15 years in Japan.</p>
                <p>Our new company, established in 2011, is even better equipped to provide you with your dream vehicle.</p>
                <p>We offer you 24hour prompt and reliable service. We will also help you to find the best customised solution to meet your specific needs.</p>
            </div>
            <div class="welcome-image">
                <img src="{{ URL::asset('/assets/frontend/assets/company.jpg') }}" alt="Sakura Motors Office" />
            </div>
        </div>
    </div>
</section>
<!-- slider section -->
<section class="slider-section">
    <div class="container">
        <div class="heading-arrive">
            <h2>Our Customers Feedback</h2>
            <a href="{{ route('front.testimonials') }}" class="view-more">View All Reviews</a>
        </div>
        <div class="slider-container">
            <div class="slides-wrapper" id="slidesWrapper">
                @foreach($customer as $row )
                <div class="slide">
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
            <div class="dot-ft">
                <button class="nav-button prev" id="prevButton">
                    <i class="fa-regular fa-angle-left"></i>
                </button>

                <div class="dot-navigation" id="dotNavigation"></div>
                <button class="nav-button next" id="nextButton">
                    <i class="fa-regular fa-angle-right"></i>
                </button>
            </div>
        </div>
        <a href="{{ route('front.testimonials') }}" class="view-more view-mbl">View All Reviews</a>
    </div>
</section>

<!-- animation word -->
<section class="page-header">
    <div class="container">
        <div class="m-head">
            <h1>
              <span class="word">Your</span>
              <span class="word">Satisfaction</span>
              <span class="word last">is Our Reward!</span>
            </h1>
        </div>
    </div>
</section>

<!-- Company Details Section -->
<section class="company-details-section">
    <div class="container">
        <div class="details-content">
            <!-- Mission & Vision -->
            <div class="mission-vision">
                <div class="mission-item">
                    <div class="icon-wrapper">
                        <div class="mission-icon">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                                <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2"/>
                                <path d="M12 6v6l4 2" stroke="currentColor" stroke-width="2"/>
                            </svg>
                        </div>
                        <h3>Our Mission</h3>
                    </div>
                    <p>To be a trustworthy, responsible and innovative value provider to all our partners and customers.</p>
                </div>

                <div class="vision-item">
                    <div class="icon-wrapper">
                        <div class="vision-icon">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                                <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z" stroke="currentColor" stroke-width="2"/>
                                <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z" stroke="currentColor" stroke-width="2"/>
                            </svg>
                        </div>
                        <h3>Our Vision</h3>
                    </div>
                    <p>To become a globally respected enterprise that provides outstanding service, excellent products and value for money.</p>
                </div>
            </div>

            <!-- Company Details -->
            <div class="company-info">
                <div class="info-header">
                    <h3>Company details</h3>
                    <div class="contact-phone">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
                            <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z" stroke="currentColor" stroke-width="2"/>
                        </svg>
                        +81-29-868-3668
                    </div>
                </div>

                <div class="info-grid">
                    <div class="info-item">
                        <span class="label">Company Name:</span>
                        <span class="value">Sakura Motors</span>
                    </div>

                    <div class="info-item">
                        <span class="label">Capital:</span>
                        <span class="value">10,000,000 Yen</span>
                    </div>

                    <div class="info-item">
                        <span class="label">Head Office:</span>
                        <span class="value">Ibaraki Ken, Tsukuba Shi, Gakuen Minami, 3 – 48 – 48 , 〒 305 – 0818</span>
                    </div>

                    <div class="info-item">
                        <span class="label">Stock Yard Ibaraki:</span>
                        <span class="value">1086 Matate, Bando, Ibaraki 306-0605</span>
                    </div>

                    <div class="info-item">
                        <span class="label">Scrap Yard & Container Ibaraki:</span>
                        <span class="value">1086 Matate, Bando, Ibaraki 306-0605</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('script')
<script src="{{ URL::asset('/assets/frontend/js/company.js')}}"></script>
<!-- <script src="{{ URL::asset('/assets/frontend/js/script.js')}}"></script> -->
@endsection
