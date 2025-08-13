/**
 * Tour Listings page JavaScript with Shadcn UI compatibility
 */
document.addEventListener('DOMContentLoaded', function() {
    // Filter toggle functionality
    const filterToggleBtn = document.querySelector('.filter-toggle-btn');
    const filtersContainer = document.querySelector('.filters-container');
    
    if (filterToggleBtn && filtersContainer) {
        filterToggleBtn.addEventListener('click', function() {
            if (filtersContainer.style.display === 'none' || getComputedStyle(filtersContainer).display === 'none') {
                filtersContainer.style.display = 'block';
                filterToggleBtn.innerHTML = '<i class="bx bx-x"></i> ' + priyanshtours.hideFilters;
            } else {
                filtersContainer.style.display = 'none';
                filterToggleBtn.innerHTML = '<i class="bx bx-filter"></i> ' + priyanshtours.showFilters;
            }
        });
    }
    
    // Form submission - don't submit empty fields
    const tourFiltersForm = document.getElementById('tour-filters');
    
    if (tourFiltersForm) {
        tourFiltersForm.addEventListener('submit', function(e) {
            // Get all selects and inputs
            const formElements = tourFiltersForm.querySelectorAll('select, input[type="number"]');
            
            // Remove empty fields before submit
            formElements.forEach(function(element) {
                if (element.value === '' || element.value === null) {
                    element.disabled = true;
                }
            });
        });
    }
    
    // Price range inputs
    const minPriceInput = document.getElementById('min-price');
    const maxPriceInput = document.getElementById('max-price');
    
    if (minPriceInput && maxPriceInput) {
        // Make sure min doesn't exceed max
        minPriceInput.addEventListener('change', function() {
            if (parseInt(minPriceInput.value) > parseInt(maxPriceInput.value) && maxPriceInput.value !== '') {
                minPriceInput.value = maxPriceInput.value;
            }
        });
        
        // Make sure max isn't less than min
        maxPriceInput.addEventListener('change', function() {
            if (parseInt(maxPriceInput.value) < parseInt(minPriceInput.value) && minPriceInput.value !== '') {
                maxPriceInput.value = minPriceInput.value;
            }
        });
    }
    
    // Enhance tours grid with Shadcn card hover effects
    const tourCards = document.querySelectorAll('.tour-card');
    
    tourCards.forEach(card => {
        card.addEventListener('mouseenter', () => {
            card.classList.add('shadcn-card-hover');
        });
        
        card.addEventListener('mouseleave', () => {
            card.classList.remove('shadcn-card-hover');
        });
    });
    
    // Prepare for future AJAX filtering
    // This will be implemented in a future update
    const ajaxFilterElements = document.querySelectorAll('[data-ajax-filter]');
    
    ajaxFilterElements.forEach(element => {
        element.addEventListener('change', function() {
            console.log('AJAX filtering will be implemented in future update');
            // Future AJAX filtering code will go here
        });
    });
}); 