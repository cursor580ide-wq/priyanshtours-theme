/**
 * About Page Animations
 * Handles scroll-based animations for elements on the About Us page
 */

document.addEventListener('DOMContentLoaded', function() {
    // Check if we're on the about page
    if (!document.querySelector('.about-us-page-wrapper')) {
        return;
    }
    
    // Function to check if an element is in viewport
    const isInViewport = function(el) {
        const rect = el.getBoundingClientRect();
        return (
            rect.top <= (window.innerHeight || document.documentElement.clientHeight) * 0.85 &&
            rect.bottom >= 0
        );
    };
    
    // Function to handle scroll animations
    const handleScrollAnimations = function() {
        const elements = document.querySelectorAll('.animate-on-scroll');
        
        elements.forEach(function(element) {
            if (isInViewport(element)) {
                element.classList.add('is-visible');
            }
        });
    };
    
    // Run once on page load
    handleScrollAnimations();
    
    // Add scroll event listener
    window.addEventListener('scroll', handleScrollAnimations);
    
    // Add resize event listener to handle viewport changes
    window.addEventListener('resize', handleScrollAnimations);
});