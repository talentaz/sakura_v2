@extends('front.layouts.index')
@section('title') Gallery @endsection
@section('css')
<!-- Fancybox CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.css" />
<!-- Gallery Custom CSS -->
<link rel="stylesheet" href="{{ asset('assets/frontend/css/hero.css') }}" />
<link rel="stylesheet" href="{{ asset('assets/frontend/css/gallery.css') }}" />

@endsection

@section('content')
<!-- Page Header -->
<section class="page-header">
    <div class="container">
        <h1>Gallery</h1>
    </div>
</section>

<!-- Gallery Section -->
<section class="gallery-section">
    <div class="container">
        <!-- Tab Navigation -->
        <div class="tab-navigation">
            <a href="#" class="tab-btn active" id="imagesTab">Images</a>
            <a href="#" class="tab-btn" id="videosTab">Videos</a>
        </div>

        <!-- Images Tab Content -->
        <div id="imagesContent" class="tab-content">
            <!-- Sakura Motors Japan - Yard Section -->
            <div class="location-section">
                <h2 class="location-title">Sakura Motors Japan - Yard</h2>
                <div class="gallery-grid">
                    <a href="https://images.unsplash.com/photo-1558618666-fcd25c85cd64?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80" data-fancybox="japan-gallery" data-caption="Main Yard View" class="gallery-item">
                        <img src="https://images.unsplash.com/photo-1558618666-fcd25c85cd64?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80" alt="Japan Yard 1" class="gallery-image">
                    </a>

                    <a href="https://images.unsplash.com/photo-1449824913935-59a10b8d2000?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80" data-fancybox="japan-gallery" data-caption="Vehicle Inspection Area" class="gallery-item">
                        <img src="https://images.unsplash.com/photo-1449824913935-59a10b8d2000?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80" alt="Japan Yard 2" class="gallery-image">
                    </a>

                    <a href="https://images.unsplash.com/photo-1486496572940-2bb2341fdbdf?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80" data-fancybox="japan-gallery" data-caption="Storage Facility" class="gallery-item">
                        <img src="https://images.unsplash.com/photo-1486496572940-2bb2341fdbdf?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80" alt="Japan Yard 3" class="gallery-image">
                    </a>

                    <a href="https://images.unsplash.com/photo-1503376780353-7e6692767b70?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80" data-fancybox="japan-gallery" data-caption="Loading Bay" class="gallery-item">
                        <img src="https://images.unsplash.com/photo-1503376780353-7e6692767b70?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80" alt="Japan Yard 4" class="gallery-image">
                    </a>

                    <a href="https://images.unsplash.com/photo-1552519507-da3b142c6e3d?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80" data-fancybox="japan-gallery" data-caption="Quality Control" class="gallery-item">
                        <img src="https://images.unsplash.com/photo-1552519507-da3b142c6e3d?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80" alt="Japan Yard 5" class="gallery-image">
                    </a>

                    <a href="https://images.unsplash.com/photo-1605559424843-9e4c228bf1c2?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80" data-fancybox="japan-gallery" data-caption="Administrative Office" class="gallery-item">
                        <img src="https://images.unsplash.com/photo-1605559424843-9e4c228bf1c2?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80" alt="Japan Yard 6" class="gallery-image">
                    </a>

                    <!-- Additional images to fill the grid -->
                    <a href="https://images.unsplash.com/photo-1560472354-b33ff0c44a43?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80" data-fancybox="japan-gallery" data-caption="Workshop Area" class="gallery-item">
                        <img src="https://images.unsplash.com/photo-1560472354-b33ff0c44a43?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80" alt="Japan Yard 7" class="gallery-image">
                    </a>

                    <a href="https://images.unsplash.com/photo-1556761175-4b46a572b786?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80" data-fancybox="japan-gallery" data-caption="Parts Storage" class="gallery-item">
                        <img src="https://images.unsplash.com/photo-1556761175-4b46a572b786?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80" alt="Japan Yard 8" class="gallery-image">
                    </a>

                    <a href="https://images.unsplash.com/photo-1541888946425-d81bb19240f5?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80" data-fancybox="japan-gallery" data-caption="Customer Area" class="gallery-item">
                        <img src="https://images.unsplash.com/photo-1541888946425-d81bb19240f5?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80" alt="Japan Yard 9" class="gallery-image">
                    </a>

                    <a href="https://images.unsplash.com/photo-1497366216548-37526070297c?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80" data-fancybox="japan-gallery" data-caption="Reception Area" class="gallery-item">
                        <img src="https://images.unsplash.com/photo-1497366216548-37526070297c?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80" alt="Japan Yard 10" class="gallery-image">
                    </a>

                    <a href="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80" data-fancybox="japan-gallery" data-caption="Staff Area" class="gallery-item">
                        <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80" alt="Japan Yard 11" class="gallery-image">
                    </a>

                    <a href="https://images.unsplash.com/photo-1494790108755-2616b612b786?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80" data-fancybox="japan-gallery" data-caption="Meeting Room" class="gallery-item">
                        <img src="https://images.unsplash.com/photo-1494790108755-2616b612b786?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80" alt="Japan Yard 12" class="gallery-image">
                    </a>
                </div>
            </div>

            <!-- Sakura Tanzania Section -->
            <div class="location-section">
                <h2 class="location-title">Sakura Tanzania</h2>
                <div class="gallery-grid">
                    <a href="https://images.unsplash.com/photo-1497366216548-37526070297c?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80" data-fancybox="tanzania-gallery" data-caption="Tanzania Showroom" class="gallery-item">
                        <img src="https://images.unsplash.com/photo-1497366216548-37526070297c?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80" alt="Tanzania Office 1" class="gallery-image">
                    </a>

                    <a href="https://images.unsplash.com/photo-1560472354-b33ff0c44a43?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80" data-fancybox="tanzania-gallery" data-caption="Customer Service Area" class="gallery-item">
                        <img src="https://images.unsplash.com/photo-1560472354-b33ff0c44a43?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80" alt="Tanzania Office 2" class="gallery-image">
                    </a>

                    <a href="https://images.unsplash.com/photo-1556761175-4b46a572b786?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80" data-fancybox="tanzania-gallery" data-caption="Service Center" class="gallery-item">
                        <img src="https://images.unsplash.com/photo-1556761175-4b46a572b786?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80" alt="Tanzania Office 3" class="gallery-image">
                    </a>

                    <a href="https://images.unsplash.com/photo-1541888946425-d81bb19240f5?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80" data-fancybox="tanzania-gallery" data-caption="Parts Department" class="gallery-item">
                        <img src="https://images.unsplash.com/photo-1541888946425-d81bb19240f5?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80" alt="Tanzania Office 4" class="gallery-image">
                    </a>

                    <!-- Additional images to fill the grid -->
                    <a href="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80" data-fancybox="tanzania-gallery" data-caption="Office Building" class="gallery-item">
                        <img src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80" alt="Tanzania Office 5" class="gallery-image">
                    </a>

                    <a href="https://images.unsplash.com/photo-1600880292203-757bb62b4baf?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80" data-fancybox="tanzania-gallery" data-caption="Parking Area" class="gallery-item">
                        <img src="https://images.unsplash.com/photo-1600880292203-757bb62b4baf?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80" alt="Tanzania Office 6" class="gallery-image">
                    </a>

                    <a href="https://images.unsplash.com/photo-1600607687939-ce8a6c25118c?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80" data-fancybox="tanzania-gallery" data-caption="Reception Desk" class="gallery-item">
                        <img src="https://images.unsplash.com/photo-1600607687939-ce8a6c25118c?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80" alt="Tanzania Office 7" class="gallery-image">
                    </a>

                    <a href="https://images.unsplash.com/photo-1600607687644-aac4c3eac7f4?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80" data-fancybox="tanzania-gallery" data-caption="Waiting Area" class="gallery-item">
                        <img src="https://images.unsplash.com/photo-1600607687644-aac4c3eac7f4?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80" alt="Tanzania Office 8" class="gallery-image">
                    </a>
                </div>
            </div>
        </div>

        <!-- Videos Tab Content -->
        <div id="videosContent" class="tab-content" style="display: none;">
            <div class="section-header">
                <h2 class="section-title">Video Gallery</h2>
            </div>

            <div class="video-grid">
                <div class="video-item">
                    <iframe src="https://www.youtube.com/embed/dQw4w9WgXcQ"
                            title="Your Dream vehicle is just a one click away !!"
                            frameborder="0"
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                            allowfullscreen
                            class="youtube-video">
                    </iframe>
                </div>

                <div class="video-item">
                    <iframe src="https://www.youtube.com/embed/dQw4w9WgXcQ"
                            title="Sakura Motors Japan Official Intro"
                            frameborder="0"
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                            allowfullscreen
                            class="youtube-video">
                    </iframe>
                </div>

                <div class="video-item">
                    <iframe src="https://www.youtube.com/embed/dQw4w9WgXcQ"
                            title="Vehicle Inspection Process"
                            frameborder="0"
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                            allowfullscreen
                            class="youtube-video">
                    </iframe>
                </div>

                <div class="video-item">
                    <iframe src="https://www.youtube.com/embed/dQw4w9WgXcQ"
                            title="Customer Success Stories"
                            frameborder="0"
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                            allowfullscreen
                            class="youtube-video">
                    </iframe>
                </div>

                <div class="video-item">
                    <iframe src="https://www.youtube.com/embed/dQw4w9WgXcQ"
                            title="Shipping Process"
                            frameborder="0"
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                            allowfullscreen
                            class="youtube-video">
                    </iframe>
                </div>

                <div class="video-item">
                    <iframe src="https://www.youtube.com/embed/dQw4w9WgXcQ"
                            title="Tanzania Operations"
                            frameborder="0"
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                            allowfullscreen
                            class="youtube-video">
                    </iframe>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection

@section('script')
<!-- Fancybox JS -->
<script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.umd.js"></script>
<!-- Gallery Custom JS -->
<script src="{{ asset('assets/frontend/js/gallery.js') }}"></script>
@endsection
