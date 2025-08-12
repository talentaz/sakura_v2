@extends('front.layouts.index')
@section('title') Stock @endsection
@section('css')
<link rel="stylesheet" href="{{ URL::asset('/assets/frontend/css/details.css') }}" />
@endsection
@section('content')
    <section>
            <div class="container">
                <div class="stock-main">
                    <div class="stock-left">
                        <div class="stock-nav">
                            <ul>
                                <li class="active"><a href="{{ route('front.stock') }}">Stock/</a></li>
                                <li><a href="{{ route('front.stock', ['body_type' => $vehicle_data->body_type]) }}">{{ $vehicle_data->body_type }}/</a></li>
                                <li><a >{{$vehicle_data->make_type}} {{$vehicle_data->model_type}}</a></li>
                            </ul>
                        </div>
                        <div class="main-price">
                            <h1>{{$vehicle_data->make_type}} {{$vehicle_data->model_type}}</h1>
                            <ul>
                                <li>Price (FOB):</li>
                                <input type="hidden" class="cubic-meter" value="{{($vehicle_data->width*$vehicle_data->length*$vehicle_data->height)/1000000}}">
                                @if($vehicle_data->sale_price==null)
                                    <input type="hidden" class="price" value="{{round($vehicle_data->price/$rate->rate)}}"/>
                                    <li class="price">${{number_format(round($vehicle_data->price/$rate->rate))}}</li>
                                @else
                                    <input type="hidden" class="price" value="{{round($vehicle_data->sale_price/$rate->rate)}}"/>
                                    <li class="price">${{number_format(round($vehicle_data->sale_price/$rate->rate))}}</li>
                                @endif
                                @if($vehicle_data->sale_price)
                                    <li class="orginal-price">${{number_format(round($vehicle_data->price/$rate->rate))}}</li>
                                @endif
                            </ul>
                        </div>
                        <div id="imageData" style="display: none;">
                            @if(isset($vehicle_data->video_link))
                                <div data-type="video" data-src="{{$vehicle_data->video_link}}" data-poster="https://placehold.co/800x480/FFDDC1/FF6F61?text=Video+Thumbnail"></div>
                            @endif
                            @if($vehicle_img)
                                @foreach($vehicle_img as $row)
                                    <div data-type="image" data-src="{{$real_url}}/{{$row->image}}"></div>
                                @endforeach
                            @endif
                        </div>

                        <div class="image-viewer-container">
                            <div class="main-image-section">
                                <img id="mainImage" class="main-media" src="" alt="Main Media" />
                                <video id="mainVideo" class="main-media" src="" controls muted playsinline></video>

                                <button class="nav-button left" id="prevButton"><i class="fas fa-chevron-left"></i></button>
                                <button class="nav-button right" id="nextButton"><i class="fas fa-chevron-right"></i></button>
                                <div class="overlay-tags">
                                    <span class="tag">MP3 interface</span>
                                    <span class="tag">ABS</span>
                                </div>
                                <a href="{{route('front.details.image_download', ['id' => $id])}}" class="download-button" id="mainDownloadBtn">
                                    Download all images
                                </a>
                            </div>

                            <div class="thumbnail-section">
                                <div class="thumbnail-carousel" id="thumbnailCarousel"></div>
                                <div class="carousel-nav-arrows">
                                    <button class="carousel-arrow" id="carouselPrev"><i class="fas fa-chevron-left"></i></button>
                                    <button class="carousel-arrow" id="carouselNext"><i class="fas fa-chevron-right"></i></button>
                                </div>
                            </div>
                        </div>

                        <div id="overlaySlider" class="overlay-slider">
                            <button id="overlayPrevBtn" class="nav-arrow"><i class="fas fa-chevron-left"></i></button>
                            <button id="overlayNextBtn" class="nav-arrow"><i class="fas fa-chevron-right"></i></button>

                            <div class="gallery">
                                <div class="gallery-header">
                                    <div class="gallery-header-left">
                                        <a href="#" id="closeOverlay" class="close-overlay-btn"> <i class="fa-regular fa-arrow-left"></i> Back to details </a>
                                    </div>
                                    <div class="gallery-header-right">
                                        <button id="overlayShareIcon" class="icon-button share-icon-btn" title="Share Media">
                                            <i class="fa-regular fa-share-nodes"></i>
                                        </button>
                                        <button id="overlayDownloadIcon" class="icon-button download-icon-btn" title="Download All Images">
                                            <i class="fa-regular fa-down-to-bracket"></i>
                                        </button>
                                    </div>
                                </div>

                                <div class="main-image-container">
                                    <img id="overlayMainImage" class="overlay-main-media" src="" alt="Overlay Main Media" />
                                    <video id="overlayMainVideo" class="overlay-main-media" src="" controls muted playsinline></video>
                                    <span id="overlayImageCounter"></span>
                                </div>
                                <div>
                                    <div id="overlayThumbnails" class="thumbnails"></div>

                                    <div class="thumbnails-wrapper">
                                        <button id="thumbsPrev" class="thumb-nav"><i class="fas fa-chevron-left"></i></button>

                                        <!-- <div class="pagination" id="overlayPaginationDots"></div> -->
                                        <button id="thumbsNext" class="thumb-nav"><i class="fas fa-chevron-right"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="offer-box offer-box-1">
                            <p>Under offer until:</p>
                            <div class="countdown">00<span class="unit">h</span> 00<span class="unit">m</span> 00<span class="unit">s</span></div>
                        </div>

                        <div class="box">
                            <h2 class="box-title">
                                Vehicle details <span class="share-btn"><i class="fa-regular fa-share-nodes"></i> Share</span>
                            </h2>
                            <div class="details-list">
                                <ul>
                                    <li>Stock no: <strong>{{$vehicle_data->stock_no}}</strong></li>
                                    <li>Transmission:<strong>{{$vehicle_data->transmission}}</strong></li>
                                    <li>Engine Cc: <strong>{{$vehicle_data->engine_size}}</strong></li>
                                    <li>Model Code: <strong>{{$vehicle_data->model_code}}</strong></li>
                                    <li>Body Type: <strong> {{$vehicle_data->body_type}}</strong></li>

                                    <li>Steering: <strong>Right</strong></li>
                                    <li>Year: <strong>{{$vehicle_data->registration}}</strong></li>
                                    <li>Engine Model: <strong> {{$vehicle_data->engine_model}}</strong></li>
                                    <li>Seating: <strong> {{$vehicle_data->seats}}</strong></li>
                                </ul>
                                <ul>
                                    <li>Fuel Type: <strong>{{$vehicle_data->fuel_type}}</strong></li>
                                    <li>Drive Type: <strong> {{$vehicle_data->drive_type}}</strong></li>
                                    <li>Doors: <strong>2</strong></li>

                                    <li>Chassis: <strong>{{$vehicle_data->chassis}}</strong></li>
                                    <li>Length (Cm): <strong>{{$vehicle_data->length}}</strong></li>
                                    <li>Height (Cm): <strong>{{$vehicle_data->height}}</strong></li>
                                    <li>Width (Cm): <strong>{{$vehicle_data->width}}</strong></li>
                                    <li>Mileage: <strong>{{number_format($vehicle_data->mileage)}} (km)</strong></li>
                                </ul>
                            </div>
                        </div>

                        <div class="box">
                            <h2 class="box-title">
                                Featured options <span class="share-btn"><i class="fa-regular fa-share-nodes"></i> Share</span>
                            </h2>
                            <ul class="features-list">
                                <li class="{{$vehicle_data->abs==1 ? 'highlight': ''}}">ABS</li>
                                <li class="{{$vehicle_data->air_bag==1 ? 'highlight': ''}}">Air bag</li>
                                <li class="{{$vehicle_data->auto_door==1 ? 'highlight': ''}}">Auto Door</li>
                                <li class="{{$vehicle_data->backup_camera==1 ? 'highlight': ''}}">Backup Camera</li>
                                <li class="{{$vehicle_data->cd_player==1 ? 'highlight': ''}}">CD player</li>
                                <li class="{{$vehicle_data->parking_sensors==1 ? 'highlight': ''}}">Parking sensors</li>
                                <li class="{{$vehicle_data->mp3_interface==1 ? 'highlight': ''}}">MP3 interface</li>
                                <li class="{{$vehicle_data->power_steering==1 ? 'highlight': ''}}">Power Steering</li>
                                <li class="{{$vehicle_data->power_mirrors==1 ? 'highlight': ''}}">Power Mirrors</li>
                                <li class="{{$vehicle_data->power_window==1 ? 'highlight': ''}}">Power Window</li>
                                <li class="{{$vehicle_data->radio==1 ? 'highlight': ''}}">Radio</li>
                                <li class="{{$vehicle_data->remote_key==1 ? 'highlight': ''}}">Remote Key</li>
                                <li class="{{$vehicle_data->ac==1 ? 'highlight': ''}}">AC</li>
                                <li class="{{$vehicle_data->alloy_wheels==1 ? 'highlight': ''}}">Alloy wheels</li>
                                <li class="{{$vehicle_data->backup_camera==1 ? 'highlight': ''}}">Back Camera</li>
                                <li class="{{$vehicle_data->bluetooth==1 ? 'highlight': ''}}">Bluetooth</li>
                                <li class="{{$vehicle_data->dvd==1 ? 'highlight': ''}}">DVD</li>
                                <li class="{{$vehicle_data->leather_seat==1 ? 'highlight': ''}}">Leather Seat</li>
                                <li class="{{$vehicle_data->navigation==1 ? 'highlight': ''}}">Navigation</li>
                                <li class="{{$vehicle_data->power_locks==1 ? 'highlight': ''}}">Power Locks</li>
                                <li class="{{$vehicle_data->power_seat==1 ? 'highlight': ''}}">Power Seat</li>
                                <li class="{{$vehicle_data->power_window==1 ? 'highlight': ''}}">Power Window</li>
                                <li class="{{$vehicle_data->rear_spoiler==1 ? 'highlight': ''}}">Rear Spoiler</li>
                                <li class="{{$vehicle_data->sun_roof==1 ? 'highlight': ''}}">Sun Roof</li>
                            </ul>
                        </div>
                    </div>
                    <div class="stock-right">
                        <div class="quote-main">
                            <!-- <a href="#">Get a price quote now</a> -->
                            <button class="btn-share" onclick="copyLink()"><i class="fa-regular fa-share-nodes"></i> Share</button>
                        </div>
                        <div class="offer-box offer-box-2">
                            <p>Under offer until:</p>
                            <div class="countdown">00<span class="unit">h</span> 00<span class="unit">m</span> 00<span class="unit">s</span></div>
                        </div>

                        <div class="box">
                            <h2>Price calculator</h2>
                            <div class="vs-search-controls">
                                <div class="vs-filter">
                                    <div class="vs-filter-input">
                                        <div class="stock-flt-head">
                                            <h3>Select country</h3>
                                            <div class="vs-select-wrapper inspection">
                                                <select id="makeSelect" class="vs-select">
                                                    @foreach($country as $row)
                                                        <option value="{{$row->id}}" {{ $current_country->country == $row->country ? "selected" : "" }}>{{$row->country}}</option>
                                                    @endforeach
                                                </select>
                                                <i class="fas fa-chevron-down vs-chevron"></i>
                                            </div>
                                        </div>
                                        <div class="stock-flt-head salaam-opt">
                                            <h3>Select port</h3>
                                            <div class="vs-select-wrapper inspection">
                                                <select id="salaamSelect" class="vs-select">
                                                    @if($port_count)
                                                        @foreach($port_list as $key=>$row)
                                                            <option value='{{json_encode($row)}}' data-price="$1,234" data-subtitle="Cost & Insurance & Freight Fees">{{$key}}</option>
                                                        @endforeach
                                                        <option value="0"></option>
                                                    @else
                                                        <option value="0"></option>
                                                    @endif
                                                </select>

                                                <i class="fas fa-chevron-down vs-chevron"></i>
                                            </div>
                                        </div>

                                        <div class="toggle-container">
                                            <div class="toggle-row">
                                                <span class="label">Inspection</span>
                                                <div class="button-group">
                                                    <input type="radio" id="inspectionNo" name="inspection" value="no" data-id="0" {{ $rate->insurance == 0 ? "": "checked"}} />
                                                    <label for="inspectionNo" class="toggle-button">No</label>
                                                    <input type="radio" id="inspectionYes" name="inspection" value="yes" data-id="{{$rate->inspection}}" {{ $rate->insurance == 0 ? "": "checked"}}/>
                                                    <label for="inspectionYes" class="toggle-button yes-button" >Yes</label>
                                                    <input type="hidden" class="insp-value" value="{{$inspection?$inspection:0}}">
                                                </div>
                                            </div>

                                            <div class="toggle-row">
                                                <span class="label">Insurance</span>
                                                <div class="button-group">
                                                    <input type="radio" id="insuranceNo" name="insurance" value="no"  data-id="0" {{ $rate->insurance == 0 ? "": "checked"}} />
                                                    <label for="insuranceNo" class="toggle-button">No</label>
                                                    <input type="radio" id="insuranceYes" name="insurance" value="yes" data-id="{{$rate->insurance}}" {{ $rate->insurance == 0 ? "": "checked"}}  />
                                                    <label for="insuranceYes" class="toggle-button yes-button">Yes</label>
                                                    <input type="hidden" class="insu-value" value="{{$insurance?$insurance:0}}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <button class="vs-search-btn" type="button" id="calc-final-price">Calculate Final Price</button>
                            </div>
                            <div class="stock-price-info">
                                <h1>Final Price</h1>
                                @if($total_price)
                                    <h1 class="total-price-value">{{ $total_price }}</h1>
                                @else
                                    <h1 class="total-price-value">-</h1>
                                @endif
                            </div>
                            <button class="vs-search-btn" type="button"><i class="fa-regular fa-magnifying-glass"></i>Next</button>
                        </div>
                        <div class="box">
                            <h2>
                                Get Inquiry
                                <a href="https://wa.me/81298683668" target="_blank">
                                    <i class="fa-regular c-t-a fa-phone"></i>
                                    <span>+81-29-868-3668</span>
                                </a>
                            </h2>

                            <form id="contactForm" method="post">
                                @csrf
                                <input type="hidden" name="vehicle_name" value="{{($vehicle_data->make_type)}} {{$vehicle_data->model_type}} {{$vehicle_data->body_type}}">
                                <input type="hidden" name="fob_price" class="inqu_fob_price" value="">
                                <input type="hidden" name="inspection" class="inqu_inspection" value="">
                                <input type="hidden" name="insurance" class="inqu_insurance" value="">
                                <input type="hidden" name="inqu_port" class="inqu_port" value="">
                                <input type="hidden" name="total_price" class="inqu_total_price" value="">
                                <input type="hidden" name="site_url" class="inqu_url" value="">
                                <input type="hidden" name="stock_no" class="stock_no" value="{{$vehicle_data->stock_no}}">
                                <input type="hidden" name="vehicle_id" class="vehicle_id" value="{{$vehicle_data->id}}">
                                <input type="hidden" name="user_id" class="user_id" value="{{isset(Auth::user()->id)?Auth::user()->id:''}}">
                                <input type="hidden" name="customer_id" value="{{ Auth::guard('customer')->check() ? Auth::guard('customer')->id() : '' }}">
                                <input type="hidden" name="freight_fee" class="inqu_freight_fee" value="">
                                    
                                <!-- Hidden inputs for price calculation -->
                                <!-- <input type="hidden" class="vehicle-price-hidden" value="{{$vehicle_data->price}}"> -->
                                <!-- <input type="hidden" class="cubic-meter-hidden" value="{{$vehicle_data->cubic_meter}}"> -->
                                <input type="hidden" class="body-type-hidden" value="{{$vehicle_data->body_type}}">
                                <input type="hidden" class="insp-value" value="0">
                                <input type="hidden" class="insu-value" value="0">
                                <input type="hidden" class="vehicle-price-hidden" value="{{$vehicle_data->sale_price ? round($vehicle_data->sale_price/$rate->rate) : round($vehicle_data->price/$rate->rate)}}">
                                <input type="hidden" class="cubic-meter-hidden" value="{{($vehicle_data->length * $vehicle_data->width * $vehicle_data->height)/1000000}}">
                                <input type="hidden" class="body-type-hidden" value="{{$vehicle_data->body_type}}">
                                <div class="form-group">
                                    <label for="name">Your Name</label>
                                    <input type="text" id="name" placeholder="John Doe" name="inqu_name" value="{{ Auth::guard('customer')->check() ? Auth::guard('customer')->user()->name : '' }}"/>
                                </div>
                                <div class="stock-flt-head">
                                    <h3>Select country</h3>
                                    <div class="vs-select-wrapper inspection">
                                        <select id="makeSelectCountry" class="vs-select" name="inqu_country">
                                            @foreach($country as $row)
                                                <option value="{{$row->id}}" {{ (Auth::guard('customer')->check() && Auth::guard('customer')->user()->country_id == $row->id) ? "selected" : "" }}>{{$row->country}}</option>
                                            @endforeach
                                        </select>
                                        <i class="fas fa-chevron-down vs-chevron"></i>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="email">Your Email</label>
                                    <input type="email" id="email" placeholder="johndoe@gmail.com" name="inqu_email" value="{{ Auth::guard('customer')->check() ? Auth::guard('customer')->user()->email : '' }}" />
                                </div>

                                <div class="form-group">
                                    <label for="mobile">Mobile</label>
                                    <input type="tel" id="mobile" name="inqu_mobile" placeholder="+1215626515" value="{{ Auth::guard('customer')->check() ? (Auth::guard('customer')->user()->mobile ?? '') : '' }}" />
                                </div>

                                <div class="form-group">
                                    <label for="city">City</label>
                                    <input type="text" id="city" name="inqu_city" placeholder="City" value="{{ Auth::check() ? Auth::user()->city : '' }}" />
                                </div>

                                <div class="form-group">
                                    <label for="address">Address</label>
                                    <input type="text" id="address" name="inqu_address" placeholder="Address" value="{{ Auth::check() ? Auth::user()->address : '' }}" />
                                </div>

                                <div class="form-group">
                                    <label for="message">Message</label>
                                    <textarea id="message" name="inqu_comment" rows="4" placeholder="Enter here your message"></textarea>
                                </div>
                                <button type="submit" class="vs-search-btn" id="quoteSubmitBtn">
                                    <i class="fa-regular fa-magnifying-glass"></i>
                                    <span class="btn-text">Get a price quote now</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
@endsection
@section('script')

<script>
    var select_port = "{{route('front.select_port')}}";
    var inquiry_url = "{{route('front.inquiry.email')}}";
    var light_url = "{{route('front.light_gallery')}}";
    var login_url = "{{route('front.customer.login')}}";
</script>
<script src="{{ URL::asset('/assets/frontend/js/details.js')}}"></script>
@endsection
