@extends('front.layouts.index')
@section('title') Homepage @endsection
@section('css')
@endsection
@section('content')
    
    <section>
      <div class="main-hero">
        <div class="container">
          <div class="m-head">
            <h1>
              <span class="word">Your</span>
              <span class="word">Satisfaction</span>
              <span class="word last">is Our Reward!</span>
            </h1>

            <img src="{{ URL::asset('/assets/frontend/assets/hero-banner.svg') }}" alt="hero" />
          </div>
        </div>
      </div>
      <div class="vs-search-box">
        <h2>Search Vehicles</h2>

        <div>
          <form class="vs-search-controls" action="{{route('front.stock')}}" method="get">
            <div class="vs-search">
              <h3>By keywords</h3>
              <div class="vs-search-input">
                <i class="fa-light fa-magnifying-glass"></i>
                <input type="text" placeholder="Keywords" name="search_keyword" />
              </div>
            </div>
            <div class="vs-filter">
              <h3>By filters</h3>
              <div class="vs-filter-input">
                <!-- Make -->
                <div class="vs-select-wrapper">
                  <select id="makeSelect" class="vs-select" name="maker">
                    <option value="">Any</option>
                    @foreach($models as $model)
                        <option value="{{$model['category_name']}}">{{Str::upper($model['category_name'])}}</option>
                    @endforeach
                  </select>
                  <i class="fas fa-chevron-down vs-chevron"></i>
                </div>

                <!-- Model -->
                <div class="vs-select-wrapper">
                  <select id="modelSelect" class="vs-select" name="model_name">
                    <option value="">Any</option>
                    <option></option>
                  </select>
                  <i class="fas fa-chevron-down vs-chevron"></i>
                </div>

                <!-- Gear -->
                <div class="vs-select-wrapper">
                    <select id="gearSelect" class="vs-select" name="gear">
                        <option value="">Any</option>
                        @foreach($transmission as $row)
                            <option value="{{$row}}">{{$row}}</option>
                        @endforeach
                    </select>
                    <i class="fas fa-chevron-down vs-chevron"></i>
                </div>

                <!-- Year Range -->
                <div class="year-range">
                    <div class="year-label">Year</div>
                    <div class="year-select-wrapper">
                        <div class="vs-select-wrapper">
                            <select id="yearMin" class="vs-select" name="from_year">
                            <option value="">Any</option>
                            </select>
                            <i class="fas fa-chevron-down vs-chevron"></i>
                        </div>
                        <div class="vs-select-wrapper">
                            <select id="yearMax" class="vs-select" name="to_year">
                            <option value="">Any</option>
                            </select>
                            <i class="fas fa-chevron-down vs-chevron"></i>
                        </div>
                    </div>
                </div>
              </div>
            </div>

            <button class="vs-search-btn">
              <i class="fa-regular fa-magnifying-glass"></i>Search
            </button>
          </form>
        </div>
      </div>
    </section>
    <section class="search-main">
      <div class="container search-by">
        <div class="tab-button-group">
          <button id="searchByTypeBtn" class="tab-button active">
            Search By Type
          </button>
          <button id="searchByMakeBtn" class="tab-button">
            Search By Make
          </button>
        </div>

        <div
          class="navigation"
          style="position: relative; display: flex; align-items: center"
        >
          <div id="leftArrow" class="nav-arrow left hidden">
            <i class="fa-regular fa-angle-left"></i>
          </div>
          <div id="gridContainer" class="grid-wrapper">
            <!-- Vehicle Types -->
            <div class="vehicle-types">
              @foreach ($vehicle_type as $row)
              <a href="{{route('front.stock')}}{{'/?body_type='}}{{$row->vehicle_type}}" class="grid-item">
                <div class="grid-item-title">
                  {{$row->vehicle_type}} ({{ $row->cnt }})
                  <span class="arrow-icon"
                    ><i class="fa-regular fa-arrow-right"></i
                  ></span>
                </div>
                <img
                  src="{{URL::asset ('/uploads/body_type')}}/{{ $row->image }}"
                  class="vehicle-type-image"
                  alt="{{$row->vehicle_type}}"
                />
              </a>
              @endforeach
            </div>

            <!-- Vehicle Makes -->
            <div class="vehicle-makes">
              @foreach ($make_type as $row)
              <a href="{{route('front.stock')}}{{'/?make_type='}}{{$row->maker_type}}" class="grid-item">
                <div class="grid-item-title">
                  {{$row->maker_type}} ({{ $row->cnt }})
                  <span class="arrow-icon"
                    ><i class="fa-regular fa-arrow-right"></i
                  ></span>
                </div>
                <img
                  src="{{URL::asset ('/uploads/make_type')}}/{{ $row->image }}"
                  class="vehicle-make-image"
                  alt="{{$row->maker_type}}"
                />
              </a>
              @endforeach
            </div>
          </div>
          <div id="rightArrow" class="nav-arrow right">
            <i class="fa-regular fa-angle-right"></i>
          </div>
        </div>

        <div id="paginationDots" class="pagination-dots"></div>
      </div>
    </section>
    <section>
        <div class="arrive-bg">
            <div class="container">
                <div class="heading-arrive">
                    <h2>New Arrivals</h2>
                    <a href="#" class="view-more">View More</a>
                </div>

                <div class="vehicle-grid">
                    @foreach ($vehicle_data as $row)
                        <a href="{{route('front.details', ['id' => $row->vehicle_id])}}" class="vehicle-card">
                            <img src="{{URL::asset ('/uploads/vehicle')}}{{'/'}}{{$row->vehicle_id}}{{'/thumb/'}}{{$row->image}}" alt="Nissan Civilian" class="vehicle-hero" />
                            <div class="vehicle-info">
                                <h3>{{Str::limit($row->make_type . ' ' . $row->model_type, 18)}}</h3>
                                <p>Stock no: <span>{{ $row->stock_no }}</span></p>
                                <div class="price">
                                    @if($row->sale_price==null)    
                                    ${{number_format(round($row->price/$rate))}} <span>Price (FOB)</span>
                                    @else
                                    ${{number_format(round($row->sale_price/$rate))}} <span>Price (FOB)</span>
                                    @endif
                                </div>
                                <div class="specs">
                                    <div>
                                        <img src="{{ URL::asset('/assets/frontend/assets/guage.svg') }}" alt="Kilometer Icon" />
                                        <h4>490,000 km</h4>
                                    </div>
                                    <div>
                                        <img src="{{ URL::asset('/assets/frontend/assets/manual.svg') }}" alt="Manual Icon" />
                                        <h4>Manual</h4>
                                    </div>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
                <a href="{{route('front.stock')}}" class="view-more view-mbl">View More</a>
            </div>
        </div>
    </section>
    <section class="stars-main">
        <div class="container">
            <div class="stats-container">
                <div class="stat-card">
                    <div class="icon-circle">
                        <i class="fa-light fa-heart"></i>
                    </div>
                    <div class="stat-content">
                        <span class="stat-number">50+</span>
                        <span class="stat-text">Happy Customers</span>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="icon-circle">
                        <i class="fa-light fa-car-side"></i>
                    </div>
                    <div class="stat-content">
                        <span class="stat-number">750+</span>
                        <span class="stat-text">Total car sold</span>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="icon-circle">
                        <i class="fa-light fa-wrench"></i>
                    </div>
                    <div class="stat-content">
                        <span class="stat-number">75+</span>
                        <span class="stat-text">Cars in damage</span>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="icon-circle">
                        <i class="fa-light fa-award"></i>
                    </div>
                    <div class="stat-content">
                        <span class="stat-number">15</span>
                        <span class="stat-text">Years on the market</span>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="bg-relative">
        <div class="bg-shadow"></div>
        <div class="container">
            <div class="heading-arrive">
                <h2>Best Deals</h2>
                <a href="{{route('front.stock')}}" class="view-more">View More</a>
            </div>

            <div class="vehicle-grid">
                @foreach($best_vehicle_data as $row)
                <a href="{{route('front.details', ['id' => $row->vehicle_id])}}" class="vehicle-card">
                    @if($row->status == 'Invoice Issued')
                        <div class="vehicle-img-wrapper">
                        <img src="{{URL::asset ('/uploads/vehicle')}}{{'/'}}{{$row->vehicle_id}}{{'/thumb/'}}{{$row->image}}"  alt="Nissan Civilian" class="vehicle-hero" />
                        <div class="vehicle-badge">Reserved</div>
                        <div class="vehicle-overlay"></div>
                    </div>
                    @else
                        <img src="{{URL::asset ('/uploads/vehicle')}}{{'/'}}{{$row->vehicle_id}}{{'/thumb/'}}{{$row->image}}" alt="Nissan Civilian" class="vehicle-hero" />
                    @endif
                    <div class="vehicle-info">
                        <h3>{{Str::limit($row->make_type . ' ' . $row->model_type, 18)}}</h3>
                        <p>Stock no: <span>{{ $row->stock_no }}</span></p>
                        <div class="was-price">
                            @if($row->sale_price==null)
                                <div class="price">
                                    ${{number_format(round($row->price/$rate))}} <span>Price (FOB)</span>
                                </div>
                            @else
                                <div class="price">${{number_format(round($row->sale_price/$rate))}} <span>Price (FOB)</span></div>
                            @endif
                        </div>
                        <div class="specs">
                            <div>
                                <img src="{{ URL::asset('/assets/frontend/assets/guage.svg') }}" alt="Kilometer Icon" />
                                <h4>{{number_format($row->mileage)}} km</h4>
                            </div>
                            <div>
                                <img src="{{ URL::asset('/assets/frontend/assets/manual.svg') }}" alt="Manual Icon" />
                                <h4>{{$row->transmission}}</h4>
                            </div>
                        </div>
                    </div>
                </a>
                @endforeach
                <!-- <a href="#" class="vehicle-card">
                    <div class="vehicle-img-wrapper">
                        <img src="{{ URL::asset(path: '/assets/frontend/assets/card-img.jpg') }}" alt="Nissan Civilian" class="vehicle-hero" />
                        <div class="vehicle-badge">Reserved</div>
                        <div class="vehicle-overlay"></div>
                    </div>
                    <div class="vehicle-info">
                        <h3>Nissan Civilian</h3>
                        <p>Stock no: <span>9067</span></p>
                        <div class="was-price">
                            <h5>was $8,409</h5>
                            <div class="price"><i class="fa-solid fa-circle-arrow-down-right"></i>$7,879 <span>Price (FOB)</span></div>
                        </div>
                        <div class="specs">
                            <div>
                                <img src="{{ URL::asset('/assets/frontend/assets/guage.svg') }}" alt="Kilometer Icon" />
                                <h4>490,000 km</h4>
                            </div>
                            <div>
                                <img src="{{ URL::asset('/assets/frontend/assets/manual.svg') }}" alt="Manual Icon" />
                                <h4>Manual</h4>
                            </div>
                        </div>
                    </div>
                </a> -->
            </div>
            <a href="#" class="view-more view-mbl">View More</a>
        </div>
    </section>
    <section class="arrive-bg arrive-bg2">
        <div class="container">
            <div class="how-it-works">
                <h2>How does it work?</h2>
                <div class="steps">
                    <div class="step">
                        <div class="circle">1</div>
                        <h3>Get a price quote</h3>
                        <p>Lorem ipsum pellentesque malesuada cras at volutpat ridiculus et in.</p>
                        <img src="{{ URL::asset('/assets/frontend/assets/Arc_2.svg') }}" alt="Curve Arrow" class="curve-arrow arc-2 arc-3" />
                    </div>

                    <div class="step">
                        <div class="circle">2</div>
                        <h3>Payments</h3>
                        <p>Lorem ipsum pellentesque malesuada cras at volutpat ridiculus et in.</p>
                        <img src="{{ URL::asset('/assets/frontend/assets/Arc_4.svg') }}" alt="Curve Arrow" class="curve-arrow arc-4" />
                    </div>

                    <div class="step">
                        <div class="circle">3</div>
                        <h3>Shipment and delivery</h3>
                        <p>Lorem ipsum pellentesque malesuada cras at volutpat ridiculus et in.</p>
                        <img src="{{ URL::asset('/assets/frontend/assets/Arc_2.svg') }}" alt="Curve Arrow" class="curve-arrow arc-2" />
                    </div>

                    <div class="step">
                        <div class="circle">4</div>
                        <h3>Get the vehicle safe</h3>
                        <p>Lorem ipsum pellentesque malesuada cras at volutpat ridiculus et in.</p>
                    </div>
                </div>
            </div>
            <div class="heading-arrive">
                <h2>Our Customers Feedback</h2>
                <a href="#" class="view-more">View All Reviews</a>
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
            <a href="#" class="view-more view-mbl">View All Reviews</a>
        </div>
    </section>
    <section class="bg-relative2">
      <div class="bg-shadow2"></div>
      <div class="container">
        <div class="heading-arrive">
          <h2>Latest News</h2>
          <a href="#" class="view-more">View All News</a>
        </div>
        <div class="hero-section">
          <div class="hero-card large-card">
            <img
              src="{{URL::asset ('/uploads/news')}}{{'/'}}{{$news[0]->id}}/{{ $news[0]->image }}"
              alt="{{ $news[0]->image }}"
            />
            <div class="card-overlay">
              <p class="date">On {{ \Carbon\Carbon::parse($news[0]->date)->format('d/m/Y') }}</p>
              <h2>{{ $news[0]->title }}</h2>
              <p class="description">
                {!! Str::limit(strip_tags($news[0]->description), 300,'.....') !!}
              </p>
            </div>
          </div>
          <div class="side-cards">
            <div class="hero-card small-card">
              <img src="{{URL::asset ('/uploads/news')}}{{'/'}}{{$news[1]->id}}/{{ $news[1]->image }}" alt="{{ $news[1]->image }}" />
              <div class="card-overlay">
                <p class="date">On {{ \Carbon\Carbon::parse($news[1]->date)->format('d/m/Y') }}</p>
                <h2>{{ $news[1]->title }}</h2>
              </div>
            </div>
            <div class="hero-card small-card">
              <img src="{{URL::asset ('/uploads/news')}}{{'/'}}{{$news[2]->id}}/{{ $news[2]->image }}" alt="{{ $news[2]->image }}" />
              <div class="card-overlay">
                <p class="date">On {{ \Carbon\Carbon::parse($news[2]->date)->format('d/m/Y') }}</p>
                <h2>{{ $news[2]->title }}</h2>
              </div>
            </div>
          </div>
        </div>
        <!-- <div class="social-link-main">
          <a href="#" class="social-link">
            <img src="{{ URL::asset('/assets/frontend/assets/facebook.svg') }}" alt="facebook" />
          </a>
          <a href="#" class="social-link">
            <img src="{{ URL::asset('/assets/frontend/assets/yt.svg') }}" alt="yt" />
          </a>
          <a href="#" class="social-link">
            <img src="{{ URL::asset('/assets/frontend/assets/insta.svg') }}" alt="insta" />
          </a>
        </div> -->
      </div>
    </section>

@endsection

@section('script')
<script>
    var models = @json($models);
</script>
<script src="{{ URL::asset('/assets/frontend/js/script.js')}}"></script>
@endsection
