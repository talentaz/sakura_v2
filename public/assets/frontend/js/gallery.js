/**
 * Gallery Page jQuery Functions
 */

$(document).ready(function() {
    
    // Tab functionality
    $('#imagesTab').on('click', function(e) {
        e.preventDefault();
        
        // Update tab states
        $('#imagesTab').addClass('active');
        $('#videosTab').removeClass('active');
        
        // Update content visibility
        $('#imagesContent').show();
        $('#videosContent').hide();
    });

    $('#videosTab').on('click', function(e) {
        e.preventDefault();
        
        // Update tab states
        $('#videosTab').addClass('active');
        $('#imagesTab').removeClass('active');
        
        // Update content visibility
        $('#videosContent').show();
        $('#imagesContent').hide();
    });

    // Initialize Fancybox for Images
    Fancybox.bind("[data-fancybox='japan-gallery']", {
        Toolbar: {
            display: {
                left: ["infobar"],
                middle: [
                    "zoomIn",
                    "zoomOut",
                    "toggle1to1",
                    "rotateCCW",
                    "rotateCW",
                    "flipX",
                    "flipY",
                ],
                right: ["slideshow", "thumbs", "close"],
            },
        },
        Thumbs: {
            autoStart: false,
        },
        Images: {
            zoom: true,
        },
        Carousel: {
            infinite: true,
        },
        l10n: {
            CLOSE: "Close",
            NEXT: "Next",
            PREV: "Previous",
            MODAL: "You can close this modal content with the ESC key",
            ERROR: "Something went wrong. Please try again later.",
            IMAGE_ERROR: "Image not found",
        },
    });

    // Initialize Fancybox for Tanzania Gallery
    Fancybox.bind("[data-fancybox='tanzania-gallery']", {
        Toolbar: {
            display: {
                left: ["infobar"],
                middle: [
                    "zoomIn",
                    "zoomOut",
                    "toggle1to1",
                    "rotateCCW",
                    "rotateCW",
                    "flipX",
                    "flipY",
                ],
                right: ["slideshow", "thumbs", "close"],
            },
        },
        Thumbs: {
            autoStart: false,
        },
        Images: {
            zoom: true,
        },
        Carousel: {
            infinite: true,
        },
        l10n: {
            CLOSE: "Close",
            NEXT: "Next",
            PREV: "Previous",
            MODAL: "You can close this modal content with the ESC key",
            ERROR: "Something went wrong. Please try again later.",
            IMAGE_ERROR: "Image not found",
        },
    });

    // Initialize Fancybox for Videos
    Fancybox.bind("[data-fancybox='video-gallery']", {
        Toolbar: {
            display: {
                left: ["infobar"],
                middle: [],
                right: ["slideshow", "close"],
            },
        },
        Video: {
            autoplay: true,
        },
        l10n: {
            CLOSE: "Close",
            NEXT: "Next",
            PREV: "Previous",
            MODAL: "You can close this modal content with the ESC key",
            ERROR: "Something went wrong. Please try again later.",
            VIDEO_ERROR: "Video not found",
        },
    });

    // Video play button click handlers
    $('.video-play-btn').on('click', function(e) {
        e.preventDefault();
        e.stopPropagation();
        
        // Get the parent video item
        var $videoItem = $(this).closest('.video-item');
        var videoSrc = $videoItem.data('video-src');
        var videoTitle = $videoItem.data('video-title');
        
        // Open video in Fancybox
        Fancybox.show([{
            src: videoSrc,
            type: "iframe",
            preload: false,
            width: 800,
            height: 600,
            caption: videoTitle,
        }]);
    });

    // Smooth scrolling for internal links
    $('a[href^="#"]').on('click', function(e) {
        e.preventDefault();
        
        var target = this.hash;
        var $target = $(target);
        
        if ($target.length) {
            $('html, body').animate({
                'scrollTop': $target.offset().top - 100
            }, 1000, 'swing');
        }
    });

    // Lazy loading for images (if needed)
    if ('IntersectionObserver' in window) {
        const imageObserver = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const img = entry.target;
                    img.src = img.dataset.src;
                    img.classList.remove('lazy');
                    imageObserver.unobserve(img);
                }
            });
        });

        document.querySelectorAll('img[data-src]').forEach(img => {
            imageObserver.observe(img);
        });
    }

    // Add loading animation
    $(document).ajaxStart(function() {
        $('body').addClass('loading');
    }).ajaxStop(function() {
        $('body').removeClass('loading');
    });

    // Keyboard navigation for gallery
    $(document).on('keydown', function(e) {
        // Only if no modal is open
        if (!$('.fancybox__container').length) {
            switch(e.which) {
                case 37: // left arrow
                    if ($('#imagesContent').is(':visible')) {
                        // Navigate to previous image section
                    }
                    break;
                case 39: // right arrow
                    if ($('#imagesContent').is(':visible')) {
                        // Navigate to next image section
                    }
                    break;
                case 86: // 'v' key
                    $('#videosTab').trigger('click');
                    break;
                case 73: // 'i' key
                    $('#imagesTab').trigger('click');
                    break;
            }
        }
    });

    // Touch/swipe support for mobile
    var startX = 0;
    var startY = 0;
    
    $('.gallery-section').on('touchstart', function(e) {
        startX = e.originalEvent.touches[0].clientX;
        startY = e.originalEvent.touches[0].clientY;
    });
    
    $('.gallery-section').on('touchend', function(e) {
        if (!startX || !startY) {
            return;
        }
        
        var endX = e.originalEvent.changedTouches[0].clientX;
        var endY = e.originalEvent.changedTouches[0].clientY;
        
        var diffX = startX - endX;
        var diffY = startY - endY;
        
        // Only trigger if horizontal swipe is greater than vertical
        if (Math.abs(diffX) > Math.abs(diffY)) {
            if (Math.abs(diffX) > 50) { // Minimum swipe distance
                if (diffX > 0) {
                    // Swiped left - show videos
                    $('#videosTab').trigger('click');
                } else {
                    // Swiped right - show images
                    $('#imagesTab').trigger('click');
                }
            }
        }
        
        startX = 0;
        startY = 0;
    });

    // Initialize tooltips if using Bootstrap
    if (typeof $().tooltip === 'function') {
        $('[data-toggle="tooltip"]').tooltip();
    }

    // Performance optimization: Debounce resize events
    var resizeTimer;
    $(window).on('resize', function() {
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(function() {
            // Handle resize events here if needed
            console.log('Window resized');
        }, 250);
    });

    // Add fade-in animation for gallery items
    $('.gallery-item, .video-item').each(function(index) {
        $(this).css({
            'opacity': '0',
            'transform': 'translateY(20px)'
        }).delay(index * 50).animate({
            'opacity': '1'
        }, 500).css('transform', 'translateY(0)');
    });

});

// Additional utility functions
function preloadImages(urls) {
    urls.forEach(function(url) {
        var img = new Image();
        img.src = url;
    });
}

function showNotification(message, type = 'info') {
    // Simple notification system
    var notification = $('<div class="notification notification-' + type + '">' + message + '</div>');
    $('body').append(notification);
    
    setTimeout(function() {
        notification.fadeOut(function() {
            $(this).remove();
        });
    }, 3000);
}
