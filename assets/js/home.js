/**
 * Home Page Scripts
 * 
 * Initialize Swiper sliders and handle home page functionality
 */
document.addEventListener('DOMContentLoaded', function() {
    
    // Hero Swiper Initialization
    const heroSwiper = new Swiper('.hero-swiper', {
        // Optional parameters
        loop: true,
        autoplay: {
            delay: 5000,
            disableOnInteraction: false,
        },
        effect: 'fade',
        fadeEffect: {
            crossFade: true
        },
        
        // Pagination
        pagination: {
            el: '.hero-swiper .swiper-pagination',
            clickable: true,
        },
        
        // Navigation arrows
        navigation: {
            nextEl: '.hero-swiper .swiper-button-next',
            prevEl: '.hero-swiper .swiper-button-prev',
        }
    });
    
    // Featured Tours Swiper Initialization - Fixed to prevent animation issues
    const featuredToursSwiper = new Swiper('.featured-tours-swiper', {
        slidesPerView: 1.2,
        spaceBetween: 15,
        centeredSlides: false,
        loop: false,
        grabCursor: true,
        speed: 400, // Faster transition
        init: true, // Ensure proper initialization
        observer: true, // Update swiper when content changes
        observeParents: true,
        resizeObserver: true,
        
        // Disable transitions that might cause the animation issue
        on: {
            init: function() {
                // Fix initial card sizing to prevent the size jump
                document.querySelectorAll('.featured-tours-swiper .tour-card').forEach(card => {
                    card.style.transform = 'none';
                });
            }
        },
        
        // Pagination
        pagination: {
            el: '.featured-tours-pagination',
            clickable: true,
        },
        
        // Navigation arrows
        navigation: {
            nextEl: '.featured-tours-next',
            prevEl: '.featured-tours-prev',
        },
        
        // Responsive breakpoints - Fixed to ensure consistent sizing
        breakpoints: {
            // When window width is >= 640px
            640: {
                slidesPerView: 2,
                spaceBetween: 20
            },
            // When window width is >= 768px
            768: {
                slidesPerView: 2,
                spaceBetween: 20
            },
            // When window width is >= 1024px
            1024: {
                slidesPerView: 3,
                spaceBetween: 25
            }
        }
    });
    
    // Testimonials Swiper Initialization - Fixed to prevent animation issues
    const testimonialsSwiper = new Swiper('.testimonials-swiper', {
        slidesPerView: 1,
        spaceBetween: 20,
        loop: true,
        grabCursor: true,
        speed: 400, // Faster transition
        init: true, // Ensure proper initialization
        observer: true, // Update swiper when content changes
        observeParents: true,
        resizeObserver: true,
        autoplay: {
            delay: 6000,
            disableOnInteraction: false,
        },
        
        // Disable transitions that might cause the animation issue
        on: {
            init: function() {
                // Fix initial card sizing to prevent the size jump
                document.querySelectorAll('.testimonials-swiper .testimonial-card').forEach(card => {
                    card.style.transform = 'none';
                });
            }
        },
        
        // Pagination
        pagination: {
            el: '.testimonials-pagination',
            clickable: true,
        },
        
        // Responsive breakpoints - Adjusted for better display
        breakpoints: {
            // When window width is >= 768px
            768: {
                slidesPerView: 2,
                spaceBetween: 30
            },
            // When window width is >= 1024px
            1024: {
                slidesPerView: 2, // Reduced to 2 for better appearance
                spaceBetween: 30
            }
        }
    });
    
    // No need to initialize date picker since we're using duration dropdown
}); 