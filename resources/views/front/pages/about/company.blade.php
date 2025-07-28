@extends('front.layouts.index')

@section('title', 'About Company')

@section('css')
<style>
/* About Company Page Styles */
.about-hero {
    background: var(--primary-color);
    color: white;
    text-align: center;
    padding: 60px 0;
}

.about-hero h1 {
    font-size: 48px;
    font-weight: 700;
    margin-bottom: 20px;
    text-transform: uppercase;
}

.about-hero p {
    font-size: 18px;
    max-width: 600px;
    margin: 0 auto;
    opacity: 0.9;
}

.about-content {
    padding: 80px 0;
}

.welcome-section {
    background: #f8f9fa;
    padding: 60px 0;
}

.welcome-content {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 60px;
    align-items: center;
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 20px;
}

.welcome-text h2 {
    font-size: 36px;
    font-weight: 700;
    color: var(--text-color);
    margin-bottom: 20px;
}

.welcome-text p {
    font-size: 16px;
    line-height: 1.6;
    color: var(--text-gray);
    margin-bottom: 20px;
}

.welcome-image {
    position: relative;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
}

.welcome-image img {
    width: 100%;
    height: 400px;
    object-fit: cover;
}

.feedback-section {
    padding: 80px 0;
    background: white;
}

.feedback-header {
    text-align: center;
    margin-bottom: 60px;
}

.feedback-header h2 {
    font-size: 36px;
    font-weight: 700;
    color: var(--text-color);
    margin-bottom: 20px;
}

.feedback-slider {
    max-width: 800px;
    margin: 0 auto;
    position: relative;
}

.feedback-item {
    text-align: center;
    padding: 40px;
    background: #f8f9fa;
    border-radius: 12px;
    margin: 0 10px;
}

.feedback-rating {
    display: flex;
    justify-content: center;
    gap: 5px;
    margin-bottom: 20px;
}

.feedback-rating i {
    color: #ffc107;
    font-size: 20px;
}

.feedback-text {
    font-size: 18px;
    font-style: italic;
    color: var(--text-color);
    margin-bottom: 20px;
    line-height: 1.6;
}

.feedback-author {
    font-weight: 600;
    color: var(--primary-color);
}

.feedback-avatar {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    margin: 0 auto 20px;
    overflow: hidden;
}

.feedback-avatar img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.satisfaction-banner {
    background: var(--primary-color);
    color: white;
    text-align: center;
    padding: 60px 0;
}

.satisfaction-banner h2 {
    font-size: 48px;
    font-weight: 700;
    text-transform: uppercase;
}

.satisfaction-banner .highlight {
    background: white;
    color: var(--primary-color);
    padding: 0 20px;
    border-radius: 8px;
    font-style: italic;
}

.company-info {
    padding: 80px 0;
    background: #f8f9fa;
}

.info-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 60px;
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 20px;
}

.info-card {
    background: white;
    padding: 40px;
    border-radius: 12px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.1);
}

.info-card h3 {
    font-size: 24px;
    font-weight: 700;
    color: var(--primary-color);
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    gap: 10px;
}

.info-card p {
    font-size: 16px;
    line-height: 1.6;
    color: var(--text-gray);
}

.contact-info {
    padding: 60px 0;
    background: white;
}

.contact-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 40px;
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 20px;
}

.contact-item {
    text-align: center;
    padding: 30px;
    border: 1px solid #e9ecef;
    border-radius: 12px;
    transition: transform 0.3s ease;
}

.contact-item:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
}

.contact-item i {
    font-size: 40px;
    color: var(--primary-color);
    margin-bottom: 20px;
}

.contact-item h4 {
    font-size: 20px;
    font-weight: 600;
    color: var(--text-color);
    margin-bottom: 10px;
}

.contact-item p {
    color: var(--text-gray);
    margin-bottom: 15px;
}

.contact-item a {
    color: var(--primary-color);
    text-decoration: none;
    font-weight: 600;
}

.social-links {
    display: flex;
    justify-content: center;
    gap: 20px;
    margin-top: 40px;
}

.social-links a {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    background: var(--primary-color);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    transition: transform 0.3s ease;
}

.social-links a:hover {
    transform: scale(1.1);
}

.view-all-btn {
    background: var(--primary-color);
    color: white;
    padding: 12px 24px;
    border: none;
    border-radius: var(--border-radius);
    text-decoration: none;
    font-weight: 600;
    font-size: 14px;
    transition: background-color 0.3s ease;
    display: inline-block;
}

.view-all-btn:hover {
    background: var(--primary-light);
    color: white;
    text-decoration: none;
}

/* Mobile Responsive */
@media (max-width: 768px) {
    .about-hero {
        padding: 40px 0;
    }

    .about-hero h1 {
        font-size: 32px;
    }

    .about-hero p {
        font-size: 16px;
        padding: 0 20px;
    }

    .about-content {
        padding: 40px 0;
    }

    .welcome-section {
        padding: 40px 0;
    }

    .welcome-content {
        grid-template-columns: 1fr;
        gap: 40px;
        padding: 0 20px;
    }

    .welcome-text h2 {
        font-size: 28px;
        text-align: center;
    }

    .welcome-image {
        order: -1;
    }

    .feedback-section {
        padding: 40px 0;
    }

    .feedback-header h2 {
        font-size: 28px;
        padding: 0 20px;
    }

    .satisfaction-banner {
        padding: 40px 0;
    }

    .satisfaction-banner h2 {
        font-size: 24px;
        padding: 0 20px;
        line-height: 1.3;
    }

    .satisfaction-banner .highlight {
        display: block;
        margin-top: 10px;
    }

    .company-info {
        padding: 40px 0;
    }

    .info-grid {
        grid-template-columns: 1fr;
        gap: 30px;
        padding: 0 20px;
    }

    .info-card {
        padding: 30px 20px;
    }

    .contact-info {
        padding: 40px 0;
    }

    .contact-grid {
        grid-template-columns: 1fr;
        gap: 20px;
        padding: 0 20px;
    }

    .contact-item {
        padding: 20px;
    }

    .feedback-item {
        margin: 0 5px;
        padding: 30px 20px;
    }

    .feedback-text {
        font-size: 16px;
    }

    .social-links {
        margin-top: 30px;
    }

    .social-links a {
        width: 45px;
        height: 45px;
        font-size: 18px;
    }
}

@media (max-width: 480px) {
    .about-hero h1 {
        font-size: 28px;
    }

    .welcome-text h2 {
        font-size: 24px;
    }

    .feedback-header h2 {
        font-size: 24px;
    }

    .satisfaction-banner h2 {
        font-size: 20px;
    }

    .info-card h3 {
        font-size: 20px;
    }

    .contact-item h4 {
        font-size: 18px;
    }
}
</style>
@endsection

@section('script')
<script>
// Feedback slider functionality
document.addEventListener('DOMContentLoaded', function() {
    // Add multiple feedback items for slider
    const feedbackSlider = document.querySelector('.feedback-slider');
    const feedbackItems = [
        {
            avatar: '{{ URL::asset("/assets/_frontend/images/chrisopherS.jpg") }}',
            name: 'Christopher',
            text: "I'm grateful for quality service really dependable. Trusty and very kind SAKURA. Great SAKURA About information.",
            rating: 5
        },
        {
            avatar: '{{ URL::asset("/assets/_frontend/images/Ezekia-Ndone--250x300.jpg") }}',
            name: 'Ezekia Ndone',
            text: "Excellent service and high-quality vehicles. The team at Sakura Motors made my car buying experience smooth and enjoyable.",
            rating: 5
        },
        {
            avatar: '{{ URL::asset("/assets/_frontend/images/chrisopherS.jpg") }}',
            name: 'John Smith',
            text: "Professional staff and great selection of vehicles. I highly recommend Sakura Motors for anyone looking for reliable cars.",
            rating: 4
        }
    ];

    let currentSlide = 0;

    function createFeedbackHTML(item) {
        const stars = '★'.repeat(item.rating) + '☆'.repeat(5 - item.rating);
        return `
            <div class="feedback-item">
                <div class="feedback-avatar">
                    <img src="${item.avatar}" alt="${item.name}" onerror="this.src='{{ URL::asset("/assets/_frontend/images/chrisopherS.jpg") }}'">
                </div>
                <div class="feedback-rating">
                    ${Array(5).fill().map((_, i) => `<i class="fas fa-star${i < item.rating ? '' : ' text-muted'}"></i>`).join('')}
                </div>
                <p class="feedback-text">"${item.text}"</p>
                <div class="feedback-author">${item.name}</div>
            </div>
        `;
    }

    function updateSlider() {
        if (feedbackSlider) {
            feedbackSlider.innerHTML = createFeedbackHTML(feedbackItems[currentSlide]);
        }
    }

    function nextSlide() {
        currentSlide = (currentSlide + 1) % feedbackItems.length;
        updateSlider();
    }

    // Auto-advance slider every 5 seconds
    setInterval(nextSlide, 5000);

    // Initialize slider
    updateSlider();
});

// Smooth scrolling for anchor links
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
            target.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        }
    });
});

// Add animation on scroll
function animateOnScroll() {
    const elements = document.querySelectorAll('.info-card, .contact-item, .welcome-text, .welcome-image');

    elements.forEach(element => {
        const elementTop = element.getBoundingClientRect().top;
        const elementVisible = 150;

        if (elementTop < window.innerHeight - elementVisible) {
            element.style.opacity = '1';
            element.style.transform = 'translateY(0)';
        }
    });
}

// Initialize animations
document.addEventListener('DOMContentLoaded', function() {
    const elements = document.querySelectorAll('.info-card, .contact-item, .welcome-text, .welcome-image');
    elements.forEach(element => {
        element.style.opacity = '0';
        element.style.transform = 'translateY(30px)';
        element.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
    });

    window.addEventListener('scroll', animateOnScroll);
    animateOnScroll(); // Check on load
});
</script>
@endsection

@section('content')
<!-- Hero Section -->
<section class="about-hero">
    <div class="container">
        <h1>About COMPANY</h1>
        <p>Discover our journey, mission, and commitment to providing exceptional automotive solutions</p>
    </div>
</section>

<!-- Welcome Section -->
<section class="welcome-section">
    <div class="welcome-content">
        <div class="welcome-text">
            <h2>Welcome to SAKURA MOTORS</h2>
            <p>We are a leading automotive company dedicated to providing high-quality vehicles and exceptional customer service. With years of experience in the industry, we have built a reputation for reliability, integrity, and excellence.</p>
            <p>Our commitment to customer satisfaction drives everything we do. From our carefully selected inventory to our professional sales team, we ensure that every customer receives the best possible experience when choosing their next vehicle.</p>
            <p>At Sakura Motors, we believe that finding the right vehicle should be an enjoyable and stress-free experience. That's why we go above and beyond to understand your needs and help you find the perfect match.</p>
        </div>
        <div class="welcome-image">
            <img src="{{ URL::asset('/assets/_frontend/images/Sakura-Motors-Company.jpg') }}" alt="Sakura Motors Company">
        </div>
    </div>
</section>

<!-- Customer Feedback Section -->
<section class="feedback-section">
    <div class="container">
        <div class="feedback-header">
            <h2>Our Customers Feedback</h2>
            <a href="#" class="view-all-btn">View All Reviews</a>
        </div>

        <div class="feedback-slider">
            <div class="feedback-item">
                <div class="feedback-avatar">
                    <img src="{{ URL::asset('/assets/_frontend/images/chrisopherS.jpg') }}" alt="Customer">
                </div>
                <div class="feedback-rating">
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                </div>
                <p class="feedback-text">"I'm grateful for quality service really dependable. Trusty and very kind SAKURA. Great SAKURA About information."</p>
                <div class="feedback-author">Christopher</div>
            </div>
        </div>
    </div>
</section>

<!-- Satisfaction Banner -->
<section class="satisfaction-banner">
    <div class="container">
        <h2>YOUR SATISFACTION <span class="highlight">IS OUR REWARD!</span></h2>
    </div>
</section>

<!-- Company Information -->
<section class="company-info">
    <div class="info-grid">
        <div class="info-card">
            <h3><i class="fas fa-bullseye"></i>Our Mission</h3>
            <p>To provide exceptional automotive solutions that exceed customer expectations while maintaining the highest standards of quality, reliability, and service. We strive to build lasting relationships with our customers through trust, transparency, and dedication.</p>
        </div>

        <div class="info-card">
            <h3><i class="fas fa-eye"></i>Our Vision</h3>
            <p>To be the leading automotive company recognized for innovation, customer satisfaction, and sustainable business practices. We envision a future where every customer finds their perfect vehicle with confidence and peace of mind.</p>
        </div>
    </div>
</section>

<!-- Contact Information -->
<section class="contact-info">
    <div class="container">
        <h2 style="text-align: center; margin-bottom: 50px; font-size: 36px; color: var(--text-color);">Company Details</h2>

        <div class="contact-grid">
            <div class="contact-item">
                <i class="fas fa-map-marker-alt"></i>
                <h4>Head Office</h4>
                <p>Sakura Motors<br>
                123 Business Street<br>
                Tokyo, Japan</p>
            </div>

            <div class="contact-item">
                <i class="fas fa-phone"></i>
                <h4>Phone Numbers</h4>
                <p>Japan: <a href="tel:+81298683668">+81-29-868-3668</a></p>
                <p>Tanzania: <a href="tel:+255716787502">+255-716-787-502</a></p>
            </div>

            <div class="contact-item">
                <i class="fas fa-envelope"></i>
                <h4>Email</h4>
                <p><a href="mailto:info@sakuramotors.com">info@sakuramotors.com</a></p>
                <p><a href="mailto:sales@sakuramotors.com">sales@sakuramotors.com</a></p>
            </div>

            <div class="contact-item">
                <i class="fas fa-clock"></i>
                <h4>Business Hours</h4>
                <p>Monday - Friday: 9:00 AM - 6:00 PM</p>
                <p>Saturday: 9:00 AM - 4:00 PM</p>
                <p>Sunday: Closed</p>
            </div>
        </div>

        <div class="social-links">
            <a href="#" title="Facebook"><i class="fab fa-facebook-f"></i></a>
            <a href="#" title="Instagram"><i class="fab fa-instagram"></i></a>
            <a href="#" title="YouTube"><i class="fab fa-youtube"></i></a>
        </div>
    </div>
</section>
@endsection
