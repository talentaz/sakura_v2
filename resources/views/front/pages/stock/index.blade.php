@extends('front.layouts.index')
@section('title') Stock @endsection
@section('css')
<link rel="stylesheet" href="{{ URL::asset('/assets/frontend/css/stock.css') }}" />
@endsection
@section('content')
     <section>
        <div class="main-hero">
            <div class="container">
                <div class="m-head">
                    <h1>PRICE CALCULATOR</h1>
                    <p>Please calculate the price based on your country's inspection and insurance regulations.</p>
                </div>
            </div>
        </div>
        <div class="vs-search-box">
            <div>
                <div class="vs-search-controls">
                    <div class="vs-filter">
                        <div class="vs-filter-input">
                            <div class="stock-flt-head ">
                                <h3>Select country</h3>
                                <div class="vs-select-wrapper">
                                    <select id="makeSelect" class="vs-select" name="price_country" >
                                        @foreach($country as $row)
                                        {{$price_country}}
                                            @if($price_country)
                                                <option value="{{$row->id}}" {{ $price_country == $row->id ? "selected" : "" }}>{{$row->country}}</option>
                                            @else
                                                <option value="{{$row->id}}" {{ $current_country->country == $row->country ? "selected" : "" }}>{{$row->country}}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                    <i class="fas fa-chevron-down vs-chevron"></i>
                                </div>
                            </div>
                            <div class="stock-flt-head salaam-opt">
                                <h3>Select port</h3>
                                <div class="vs-select-wrapper">
                                <select id="salaamSelect" class="vs-select" name="price_port">
                                    @if($port_count)
                                        @foreach($port_list as $key=>$row)
                                            <option value='{{json_encode($row)}}'>{{$key}}</option>
                                        @endforeach
                                        <option value="0"></option>
                                    @else
                                        <option value="0"></option>
                                    @endif
                                </select>
                                 <i class="fas fa-chevron-down vs-chevron"></i>
                                </div>
                            </div>
                            <div class="stock-flt-head hide">
                                <h3>Inspection</h3>
                                <div class="vs-select-wrapper inspection">
                                    <select id="modelSelect" class="vs-select inspection" name="inspection">
                                        <option value="0" >No</option>
                                        <option value="{{$rate_ins->inspection}}" >Yes</option>
                                    </select>
                                    <i class="fas fa-chevron-down vs-chevron"></i>
                                </div>
                            </div>
                            <div class="stock-flt-head hide">
                                <h3>Insurance</h3>
                                <div class="vs-select-wrapper inspection">
                                    <select id="gearSelect" class="vs-select insurance" name="insurance">
                                        <option value="0" >No</option>
                                        <option value="{{$rate_ins->insurance}}" >Yes</option>
                                    </select>
                                    <i class="fas fa-chevron-down vs-chevron"></i>
                                </div>
                            </div>
                            <div class="toggle-container">
                                <div class="toggle-row">
                                    <span class="label">Inspection</span>
                                    <div class="button-group">
                                        <input type="radio" id="inspectionNo" name="inspection" value="0" checked />
                                        <label for="inspectionNo" class="toggle-button">No</label>
                                        <input type="radio" id="inspectionYes" name="inspection" value="{{$rate_ins->inspection}}"  />
                                        <label for="inspectionYes" class="toggle-button yes-button">Yes</label>
                                    </div>
                                </div>

                                <div class="toggle-row">
                                    <span class="label">Insurance</span>
                                    <div class="button-group">
                                        <input type="radio" id="insuranceNo" name="insurance" value="0" checked />
                                        <label for="insuranceNo" class="toggle-button">No</label>
                                        <input type="radio" id="insuranceYes" name="insurance" value="1" />
                                        <label for="insuranceYes" class="toggle-button yes-button">Yes</label>
                                    </div>
                                </div>
                            </div>


                            
                        </div>
                    </div>

                    <button class="vs-search-btn" id="price-calc"><i class="fa-regular fa-magnifying-glass"></i>Calculate</button>
                </div>
            </div>
        </div>
    </section>

    <section>
        <div class="container">
            <div class="stock-main">
                <aside class="sidebar">
                    <div class="search-box">
                        <h4>Search Vehicles</h4>
                        <form  action="{{route('front.stock')}}" method="get" >
                            <div class="vs-search">
                                <div class="vs-search-input">
                                    <i class="fa-light fa-magnifying-glass"></i>
                                    <input type="text" placeholder="Keywords" name="search_keyword" value="{{$search_keyword}}"/>
                                </div>
                            </div>
                            <button type="submit"><i class="fa-light fa-magnifying-glass"></i></button>
                        </form>
                        <small>*Stock No., Body Type, Chassis</small>
                    </div>

                    <form class="filters"  action="{{route('front.stock')}}" method="get" >
                        <h4>Filters</h4>

                        <div class="stock-flt-head">
                            <h3>Price</h3>
                            <div class="year-range">
                                <div class="vs-select-wrapper price-from">
                                    <select id="filterPriceFrom" class="vs-select" name="from_price">
                                        <option value="">From</option>
                                        @if(!is_null($from_price))
                                            @foreach($price as $row)
                                                <option value="{{$row}}" {{ $from_price == $row ? "selected" : "" }}>${{number_format($row)}}</option>
                                            @endforeach
                                        @else
                                            @foreach($price as $row)
                                                <option value="{{$row}}">${{number_format($row)}}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                    <i class="fas fa-chevron-down vs-chevron"></i>
                                </div>
                                <div class="year-right-border"></div>
                                <div class="vs-select-wrapper price-to">
                                    <select id="filterPriceTo" class="vs-select" name="to_price">
                                        <option value="">To</option>
                                        @if(!is_null($to_price))
                                            @foreach($price as $row)
                                                <option value="{{$row}}" {{ $to_price == $row ? "selected" : "" }}>${{number_format($row)}}</option>
                                            @endforeach
                                        @else
                                            @foreach($price as $row)
                                                <option value="{{$row}}">${{number_format($row)}}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                    <i class="fas fa-chevron-down vs-chevron"></i>
                                </div>
                            </div>
                        </div>

                        <div class="stock-flt-head">
                            <h3>Make</h3>
                            <div class="vs-select-wrapper make">
                                <select id="filterMake" class="vs-select select-category" name="maker">
                                    <option value="">Any</option>
                                    @if(!is_null($maker))
                                        @foreach($models as $model)
                                            <option value="{{$model['category_name']}}" {{ $maker == $model['category_name'] ? "selected" : "" }}>{{Str::upper($model['category_name'])}}</option>
                                        @endforeach
                                    @else
                                        @foreach($models as $model)
                                            <option value="{{$model['category_name']}}">{{Str::upper($model['category_name'])}}</option>
                                        @endforeach
                                    @endif
                                </select>
                                <i class="fas fa-chevron-down vs-chevron"></i>
                            </div>
                        </div>

                        <div class="stock-flt-head">
                            <h3>Model</h3>
                            <div class="vs-select-wrapper model">
                                <select id="filterModel" class="vs-select subcategory" name="model_name">
                                    <option value="">Any</option>
                                    @if(!is_null($model_name))
                                        <option value="{{$model_name}}" selected>{{$model_name}}</option>
                                    @else
                                        <option></option>
                                    @endif
                                </select>
                                <i class="fas fa-chevron-down vs-chevron"></i>
                            </div>
                        </div>

                        <div class="stock-flt-head">
                            <h3>Gear</h3>
                            <div class="vs-select-wrapper gear">
                                <select id="filterGear" class="vs-select" name="gear">
                                    <option value="">Any</option>
                                    @foreach($transmission as $row)
                                        <option value="{{$row}}" {{ $gear == $row ? "selected" : ""}}>{{$row}}</option>
                                    @endforeach
                                </select>
                                <i class="fas fa-chevron-down vs-chevron"></i>
                            </div>
                        </div>

                        <div class="stock-flt-head">
                            <h3>Year</h3>
                            <div class="year-range">
                                <div class="vs-select-wrapper year-min">
                                    <select id="filterYearMin" class="vs-select" name="from_year">
                                        <option value="">Any</option>
                                        @if(!is_null($from_year))
                                            @foreach($year as $row)
                                                <option value="{{$row}}" {{ $from_year == $row ? "selected" : "" }}>{{$row}}</option>
                                            @endforeach
                                        @else
                                            @foreach($year as $row)
                                                <option value="{{$row}}">{{$row}}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                    <i class="fas fa-chevron-down vs-chevron"></i>
                                </div>
                                <div class="year-right-border"></div>
                                <div class="vs-select-wrapper year-max">
                                    <select id="filterYearMax" class="vs-select"  name="to_year">
                                        <option value="">Any</option>
                                        @if(!is_null($to_year))
                                            @foreach($year as $row)
                                                <option value="{{$row}}" {{ $to_year == $row ? "selected" : "" }}>{{$row}}</option>
                                            @endforeach
                                        @else
                                            @foreach($year as $row)
                                                <option value="{{$row}}">{{$row}}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                    <i class="fas fa-chevron-down vs-chevron"></i>
                                </div>
                            </div>
                        </div>
                        <div class="filter-border"></div>
                        <button type="submit" class="apply-btn">Aply Filters</button>
                    </form>

                    <div class="search-type">
                        <h4>Search by type</h4>
                        <ul>
                            @foreach ($vehicle_type as $row)
                            <li>
                                <a href="{{route('front.stock')}}{{'/?body_type='}}{{$row->vehicle_type}}"><img src="{{URL::asset ('/uploads/body_type')}}/{{ $row->image }}" alt=""> {{$row->vehicle_type}} ({{$row->cnt}})</a>
                            </li>
                            @endforeach
                        </ul>
                    </div>

                    <div class="search-maker">
                        <h4>Search by maker</h4>
                        <ul>
                            @foreach ($make_type as $row)
                            <li>
                                <a href="{{route('front.stock')}}{{'/?make_type='}}{{$row->maker_type}}"><img src="{{URL::asset ('/uploads/maker_type')}}/{{ $row->image }}"/> {{$row->maker_type}} ({{$row->cnt}})</a>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </aside>
                <div id="moreOptions">
                        <section >
                            <div class="container search-by">
                                <div class="tab-button-group">
                                    <button id="searchByTypeBtn" class="tab-button active">Search By Type</button>
                                    <button id="searchByMakeBtn" class="tab-button">Search By Make</button>
                                </div>

                                <div class="navigation" style="position: relative; display: flex; align-items: center;">
                                    <div id="leftArrow" class="nav-arrow left hidden">
                                        <i class="fa-regular fa-angle-left"></i>
                                    </div>
                                    <div id="gridContainer" class="grid-wrapper">
                                        <!-- Vehicle Types -->
                                        <div class="vehicle-types">
                                            @foreach ($vehicle_type as $row)
                                            <a href="{{route('front.stock')}}{{'/?body_type='}}{{$row->vehicle_type}}" class="grid-item">
                                                <div class="grid-item-title">
                                                    {{$row->vehicle_type}} ({{$row->cnt}}) <span class="arrow-icon"><i class="fa-regular fa-arrow-right"></i></span>
                                                </div>
                                                <img src="{{URL::asset ('/uploads/body_type')}}/{{ $row->image }}" class="vehicle-type-image" alt="Large Bus" />
                                            </a>
                                            @endforeach
                                        </div>

                                        <!-- Vehicle Makes -->
                                        <div class="vehicle-makes">
                                            @foreach ($make_type as $row)
                                            <a href="{{route('front.stock')}}{{'/?make_type='}}{{$row->maker_type}}" class="grid-item">
                                                <div class="grid-item-title">
                                                    {{$row->maker_type}} ({{$row->cnt}}) <span class="arrow-icon"><i class="fa-regular fa-arrow-right"></i></span>
                                                </div>
                                                <img src="{{URL::asset ('/uploads/make_type')}}/{{ $row->image }}" class="vehicle-make-image" alt="Tayota" />
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
                </div>
                <button id="toggleBtn">View more search options</button>
                <!-- stock list section -->

                <div class="stock-right">
                    <h1 class="main-heading">List of Vehicles</h1>
                    <div class="item-count-main">
                        @if ($vehicle_data->isNotEmpty())
                            <h1>
                                <strong>{{ number_format($vehicle_data->first()->vehicle_count) }}</strong>
                                Results
                            </h1>
                        @else
                            <h1><strong>0</strong> Results</h1>
                        @endif
                        <div class="stock-flt-head">
                            <h3>Sort by:</h3>
                            <div class="vs-select-wrapper">
                                <select id="sortSelect" class="vs-select sort-by">
                                    <option value="new_arriaval">New Arriavals</option>
                                    <option value="price_to_low">Price Low to High</option>
                                    <option value="price_to_high">Price High to Low</option>
                                    <option value="year_to_new">Year New to Old</option>
                                    <option value="year_to_old">Year Old to New</option>
                                </select>
                                <i class="fas fa-chevron-down vs-chevron"></i>
                            </div>
                        </div>
                    </div>
                    <div id="car-listings-container">
                        @include('front.pages.stock.list')
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- <section>
        <div class="container">
            <div class="social-link-main">
                <a href="#" class="social-link">
                    <img src="{{ URL::asset('/assets/frontend/assets/facebook.svg') }}" alt="facebook" />
                </a>
                <a href="#" class="social-link">
                    <img src="{{ URL::asset('/assets/frontend/assets/yt.svg') }}" alt="yt" />
                </a>
                <a href="#" class="social-link">
                    <img src="{{ URL::asset('/assets/frontend/assets/insta.svg') }}" alt="insta" />
                </a>
            </div>
        </div>
    </section> -->
@endsection

@section('script')
<script>
    var models = @json($models);
    var sock_page = "{{route('front.stock')}}";
    var select_port = "{{route('front.select_port')}}";
    var search_keyword = "{{$search_keyword}}";
    var maker = "{{$maker}}";
    var model_name = "{{$model_name}}";
    var from_year = "{{$from_year}}";
    var to_year = "{{$to_year}}";
    var from_price = "{{$from_price}}";
    var to_price = "{{$to_price}}";
    var light_url = "{{route('front.light_gallery')}}";
</script>
<script src="{{ URL::asset('/assets/frontend/js/stock.js')}}"></script>
@endsection