@extends('front.layouts.index')
@section('title') Contact Us @endsection
@section('css')
    <link href="{{ URL::asset('/assets/frontend/css/hero.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ URL::asset('/assets/frontend/css/contact.css') }}" rel="stylesheet" type="text/css" />
    <link href="https://api.mapbox.com/mapbox-gl-js/v2.7.0/mapbox-gl.css" rel="stylesheet">
    <script src="https://api.mapbox.com/mapbox-gl-js/v2.7.0/mapbox-gl.js"></script>
@endsection
@section('content')
<!-- Contact Header -->
<section class="page-header">
    <div class="container">
        <h1>Contact Us</h1>
    </div>
</section>

<!-- Contact Content -->
<div class="contact-container">
    <div class="contact-wrapper">
        <div class="contact-row">
            <!-- Contact Form Section -->
            <div class="contact-form-column">
                <div class="contact-form-section">
                    <div class="section-header">
                        <h3>Need help?</h3>
                        <h3>We're Here for You</h3>
                        <p>Please feel free to call or email us, if you have any questions or suggestions.</p>
                    </div>

                    <form class="contact-form" action="{{route('front.contact.email')}}" method="post">
                        @csrf
                        <div class="form-group">
                            <label for="subject">Subject</label>
                            <input type="text" id="subject" name="subject" class="form-input" placeholder="Lorem ipsum lorem" required>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="name">Name</label>
                                <input type="text" id="name" name="name" class="form-input" placeholder="Full Name" required>
                            </div>
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" id="email" name="email" class="form-input" placeholder="name@email.com" required>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="phone">Contact Number</label>
                                <input type="tel" id="phone" name="phone" class="form-input" placeholder="+16783231314" required>
                            </div>
                            <div class="form-group">
                                <label for="country">Country</label>
                                <select id="country" name="country" class="form-input" required>
                                    <option value="">Zambia</option>
                                    @foreach($country as $row)
                                        <option value="{{$row}}">{{$row}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="city">City</label>
                                <input type="text" id="city" name="city" class="form-input" placeholder="Dar es Salaam">
                            </div>
                            <div class="form-group">
                                <label for="address">Address</label>
                                <input type="text" id="address" name="address" class="form-input" placeholder="Street Address" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="message">Message</label>
                            <textarea id="message" name="comment" class="form-input form-textarea" rows="4" placeholder="Enter here your message"></textarea>
                        </div>

                        <button type="submit" class="btn-submit">Send Message</button>
                    </form>
                </div>
            </div>

            <!-- Map Section -->
            <div class="contact-map-column">
                <div class="map-section">
                    <div id="map" class="contact-map"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Branches Section -->
<div class="branches-section">
    <div class="branches-wrapper">
        <h2>Branches</h2>

        <div class="branch-card">
            <div class="branch-row">
                <div class="branch-info-column">
                    <div class="branch-info">
                        <h3>SAKURA MOTORS Tanzania</h3>
                        <h3>Official Contact Details</h3>

                        <div class="contact-item">
                            <i class="fab fa-whatsapp"></i>
                            <span>Whatsapp: +81-29-868-3668</span>
                        </div>

                        <div class="team-grid">
                            <div class="team-member">
                                <div class="member-photo">
                                    <img src="{{ asset('assets/frontend/images/team/yuseph.svg') }}" alt="Yuseph Khalifa">
                                </div>
                                <div class="member-details">
                                    <h5>Yuseph Khalifa</h5>
                                    <p>+255 716787 502</p>
                                </div>
                            </div>

                            <div class="team-member">
                                <div class="member-photo">
                                    <img src="{{ asset('assets/frontend/images/team/sia.svg') }}" alt="Sia Change">
                                </div>
                                <div class="member-details">
                                    <h5>Sia Change</h5>
                                    <p>+255 677075 474</p>
                                </div>
                            </div>

                            <div class="team-member">
                                <div class="member-photo">
                                    <img src="{{ asset('assets/frontend/images/team/ally.svg') }}" alt="Ally">
                                </div>
                                <div class="member-details">
                                    <h5>Ally</h5>
                                    <p>+255 677075 474</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="branch-map-column">
                    <div class="branch-map">
                        <div id="branch-map" class="branch-map-container"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('script')
    <script src="{{ URL::asset('/assets/libs/parsleyjs/parsleyjs.min.js') }}"></script>
    <script src="{{ URL::asset('/assets/js/pages/form-validation.init.js') }}"></script>
    <script>
        // Main contact map
        mapboxgl.accessToken = 'pk.eyJ1IjoiZGFuaWxhYiIsImEiOiJjbDAwaTk1a2owa2Q0M2x0OGtvc3hjc2t0In0.gmilwByvO7UW5lhwWiszfw';
        const map = new mapboxgl.Map({
            container: 'map',
            style: 'mapbox://styles/mapbox/streets-v11',
            center: [140.1, 36.1], // Tsukuba, Japan coordinates
            zoom: 12
        });

        // Add marker for main office
        new mapboxgl.Marker()
            .setLngLat([140.1, 36.1])
            .addTo(map);

        // Branch map
        const branchMap = new mapboxgl.Map({
            container: 'branch-map',
            style: 'mapbox://styles/mapbox/streets-v11',
            center: [39.2, -6.8], // Tanzania coordinates
            zoom: 10
        });

        // Add marker for Tanzania branch
        new mapboxgl.Marker()
            .setLngLat([39.2, -6.8])
            .addTo(branchMap);
    </script>
@endsection
