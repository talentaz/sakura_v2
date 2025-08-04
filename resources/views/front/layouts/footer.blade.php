<section>
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
</section>
<footer class="footer">
            <div class="footer-container">
                <div class="footer-section about-us">
                    <div class="about-us-contact">
                        <a href="index.html" class="footer-logo">
                            <img src="{{ URL::asset('/assets/frontend/assets/logo.svg') }}" alt="Sakura Motors Footer Logo" />
                        </a>
                        <div class="contact-info-footer">
                            <i class="fa-regular fa-phone"></i>
                            <p>+81-29-868-3668</p>
                        </div>
                    </div>
                    <p>
                        We believe in offering you the best customer service possible. We have been in the vehicle trading business for over 15 years in Japan. Our new company, established in 2011, is even better equipped to provide you
                        with your dream vehicle.
                    </p>
                </div>
                <div class="footer-right">
                    <div class="footer-section helpful-links">
                        <h3>Helpful Links</h3>
                        <ul>
                            <li><a href="#" class="active-ft">Home</a></li>
                            <li><a href="#">Stock</a></li>
                            <li><a href="#">About Company</a></li>
                            <li><a href="#">News</a></li>
                            <li><a href="#">Contact Us</a></li>
                        </ul>
                    </div>
                    <div class="footer-section contact-details">
                        <h3>Contact</h3>
                        <p><strong>Email:</strong> info@sakuramotors.com</p>
                        <p><strong>Phone Office:</strong><span> +81-29-868-3668</span></p>
                        <p><strong>Phone Mobile:</strong><span> +81-90-9945-6908, +81-90-9345-1234</span></p>
                        <p><strong>Fax:</strong><span> +81-29-868-3669</span></p>
                    </div>
                    <div class="footer-section visit-us">
                        <h3>Visit Us</h3>
                        <p><strong>Showroom:</strong> Ibaraki Ken, Tsukuba Shi, Gakuen Minami, 3 - 48 - 48, 〒 305 - 0818</p>
                        <p><strong>Yard:</strong> Ibaraki Ken, Banda shi, Matate, 1086-1, 〒 306 - 0605</p>
                    </div>
                </div>
            </div>
            <div class="copyright">
                <p>© 2025 All rights reserved</p>
                <a href="#" class="back-to-top">Back to the top <i class="fas fa-chevron-up"></i></a>
            </div>
        </footer>
        <!-- jQuery + Select2 -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
        <script src="{{ URL::asset('/assets/libs/toastr/toastr.min.js')}}"></script>
        <script>
            // Configure toastr
            toastr.options = {
                "closeButton": true,
                "debug": false,
                "newestOnTop": true,
                "progressBar": true,
                "positionClass": "toast-top-right",
                "preventDuplicates": false,
                "onclick": null,
                "showDuration": "300",
                "hideDuration": "1000",
                "timeOut": "5000",
                "extendedTimeOut": "1000",
                "showEasing": "swing",
                "hideEasing": "linear",
                "showMethod": "fadeIn",
                "hideMethod": "fadeOut"
            };
        </script>
        <script src="{{ URL::asset('/assets/frontend/js/header.js')}}"></script>
        <script>
            $(document).ready(function(){
                //ajax loading spinner
                var loading = $('.spinner-wrapper').hide();
                $(document)
                    .ajaxStart(function () {
                        loading.show();
                    })
                    .ajaxStop(function () {
                        setTimeout(function(){
                            loading.hide();
                        }, 500)
                    });
                $('.whatsapp').on('click', function(){
                    $('#sideWhatsappAdd').animate({width:'toggle'},350);
                })
                console.log("Screen Width: " + screen.width);
            })
        </script>
@yield('script')