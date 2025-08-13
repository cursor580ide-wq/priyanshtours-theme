/**
 * Itinerary functionality for collapsible sections
 */
document.addEventListener('DOMContentLoaded', function() {
    // Initialize itinerary items
    initItineraryItems();

    function initItineraryItems() {
        console.log('Initializing itinerary items...');
        
        // Fix for default WP Travel itinerary dropdown clicks
        const dayHeaders = document.querySelectorAll('.wp-travel-itinerary-items > li > a');
        if (dayHeaders.length) {
            console.log('Found default WP Travel itinerary headers:', dayHeaders.length);
            dayHeaders.forEach(header => {
                header.addEventListener('click', function(e) {
                    e.preventDefault();
                    const parent = this.closest('li');
                    if (parent) {
                        parent.classList.toggle('active');
                    }
                });
            });
        }
        
        // Custom itinerary collapsible functionality
        const itineraryHeaders = document.querySelectorAll('.itinerary-header');
        
        if (itineraryHeaders.length) {
            console.log('Found custom itinerary headers:', itineraryHeaders.length);
            itineraryHeaders.forEach(header => {
                header.addEventListener('click', function() {
                    console.log('Header clicked');
                    // Toggle collapsed class on header
                    this.classList.toggle('collapsed');
                    
                    // Get the content element
                    const content = this.nextElementSibling;
                    console.log('Content element:', content);
                    
                    // Toggle the display of content
                    if (content.classList.contains('show')) {
                        content.classList.remove('show');
                        content.style.maxHeight = null;
                        console.log('Content hidden');
                    } else {
                        content.classList.add('show');
                        content.style.maxHeight = content.scrollHeight + "px";
                        console.log('Content shown, height:', content.scrollHeight);
                    }
                });
            });
        }
        
        // Open/Close All functionality
        const openAllLink = document.querySelector('.open-all-itinerary-link, .open-all');
        const closeAllLink = document.querySelector('.close-all-itinerary-link, .close-all');
        
        if (openAllLink) {
            console.log('Found Open All link');
            openAllLink.addEventListener('click', function(e) {
                e.preventDefault();
                console.log('Open All clicked');
                
                // Handle default WP Travel toggle
                if (this.id === 'open-all') {
                    document.getElementById('open-all').parentElement.style.display = 'none';
                    document.getElementById('close-all').parentElement.style.display = 'block';
                    
                    const itineraryItems = document.querySelectorAll('.wp-travel-itinerary-items > li');
                    itineraryItems.forEach(item => {
                        item.classList.add('active');
                    });
                    return;
                }
                
                // Handle custom toggle
                if (openAllLink.style) openAllLink.style.display = 'none';
                if (closeAllLink.style) closeAllLink.style.display = 'inline-flex';
                
                itineraryHeaders.forEach(header => {
                    header.classList.add('collapsed');
                    const content = header.nextElementSibling;
                    content.classList.add('show');
                    content.style.maxHeight = content.scrollHeight + "px";
                });
            });
        }
        
        if (closeAllLink) {
            console.log('Found Close All link');
            closeAllLink.addEventListener('click', function(e) {
                e.preventDefault();
                console.log('Close All clicked');
                
                // Handle default WP Travel toggle
                if (this.id === 'close-all') {
                    document.getElementById('close-all').parentElement.style.display = 'none';
                    document.getElementById('open-all').parentElement.style.display = 'block';
                    
                    const itineraryItems = document.querySelectorAll('.wp-travel-itinerary-items > li');
                    itineraryItems.forEach(item => {
                        item.classList.remove('active');
                    });
                    return;
                }
                
                // Handle custom toggle
                if (closeAllLink.style) closeAllLink.style.display = 'none';
                if (openAllLink.style) openAllLink.style.display = 'inline-flex';
                
                itineraryHeaders.forEach(header => {
                    header.classList.remove('collapsed');
                    const content = header.nextElementSibling;
                    content.classList.remove('show');
                    content.style.maxHeight = null;
                });
            });
        }
        
        // Also handle clicks on the arrow icons
        const dropdownArrows = document.querySelectorAll('.toggle-icon, .accordion-toggle-arrow');
        if (dropdownArrows.length) {
            console.log('Found dropdown arrows:', dropdownArrows.length);
            dropdownArrows.forEach(arrow => {
                arrow.addEventListener('click', function(e) {
                    e.stopPropagation(); // Prevent double firing when clicking on the arrow
                    
                    // Find the parent header and trigger its click
                    const header = this.closest('.itinerary-header, .accordion-toggle');
                    if (header) {
                        header.click();
                    }
                });
            });
        }
    }
}); 