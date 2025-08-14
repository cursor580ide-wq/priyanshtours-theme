/**
 * Tour Listings page JavaScript with Shadcn UI compatibility
 */
document.addEventListener('DOMContentLoaded', function() {
    const tourFiltersForm = document.getElementById('tour-filters');
    if (!tourFiltersForm) return; // Exit if the form is not on the page

    const sortSelect = document.getElementById('sort-tours');
    const sourceFilterBtns = document.querySelectorAll('.source-filter-btn');
    const resultsContainer = document.getElementById('tours-results-container');
    const loader = document.querySelector('.tours-loader');
    let currentPage = 1;

    function fetchTours() {
        if (!loader || !resultsContainer) {
            console.error('Required elements (loader or results container) not found for AJAX filtering.');
            return;
        }
        loader.style.display = 'flex';

        // Use a single source of truth for form data
        const formData = new FormData(tourFiltersForm);

        // Add action, nonce, and page
        formData.append('action', 'filter_tours');
        formData.append('nonce', priyanshtours.nonce);
        formData.append('page', currentPage);

        // Ensure sort value is included
        if (sortSelect) {
            formData.append('sort', sortSelect.value);
        }

        const params = new URLSearchParams(formData);

        fetch(priyanshtours.ajaxurl, {
            method: 'POST',
            body: params
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            if (data.success && data.data.html) {
                resultsContainer.innerHTML = data.data.html;
                updateURL();
            } else {
                resultsContainer.innerHTML = '<div class="no-tours-found"><div class="no-tours-content"><p>No tours found or an error occurred.</p></div></div>';
                console.error('AJAX request was not successful:', data);
            }
        })
        .catch(error => {
            console.error('Fetch Error:', error);
            resultsContainer.innerHTML = '<div class="no-tours-found"><div class="no-tours-content"><p>An error occurred while fetching tours. Please try again.</p></div></div>';
        })
        .finally(() => {
            loader.style.display = 'none';
        });
    }

    function updateURL() {
        const formData = new FormData(tourFiltersForm);
        const params = new URLSearchParams();

        formData.forEach((value, key) => {
            if (value && key !== 'action' && key !== 'nonce') {
                params.append(key, value);
            }
        });
        
        if (sortSelect && sortSelect.value) {
            params.set('sort', sortSelect.value);
        }

        if (currentPage > 1) {
            params.set('paged', currentPage);
        } else {
            params.delete('paged');
        }

        const newUrl = `${window.location.pathname}?${params.toString()}`;
        window.history.pushState({ path: newUrl }, '', newUrl);
    }

    // Handle all filter changes in one go
    tourFiltersForm.addEventListener('change', (e) => {
        // We want to trigger on select changes and when price inputs lose focus
        if (e.target.tagName === 'SELECT' || e.target.type === 'number') {
            currentPage = 1;
            fetchTours();
        }
    });

    // Handle form submission for the "Apply Filters" button
    tourFiltersForm.addEventListener('submit', (e) => {
        e.preventDefault();
        currentPage = 1;
        fetchTours();
    });

    // Handle source filter buttons
    sourceFilterBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            currentPage = 1;
            const source = this.dataset.source;
            let sourceInput = tourFiltersForm.querySelector('input[name="source"]');

            if (!sourceInput) {
                sourceInput = document.createElement('input');
                sourceInput.type = 'hidden';
                sourceInput.name = 'source';
                tourFiltersForm.appendChild(sourceInput);
            }
            sourceInput.value = source;

            sourceFilterBtns.forEach(b => b.classList.remove('active'));
            this.classList.add('active');

            fetchTours();
        });
    });

    // Delegated event listener for pagination links
    document.body.addEventListener('click', function(e) {
        let target = e.target;
        // Handle clicks on <i> inside <a>
        if (target.tagName === 'I' && target.parentElement.classList.contains('page-link')) {
            target = target.parentElement;
        }

        if (target.classList.contains('page-link')) {
            e.preventDefault();
            const url = new URL(target.href);
            const page = url.searchParams.get('paged');
            currentPage = page ? parseInt(page, 10) : 1;
            fetchTours();
        }
    });

    // Filter toggle functionality (from original file)
    const filterToggleBtn = document.querySelector('.filter-toggle-btn');
    const filtersContainer = document.querySelector('.filters-container');
    
    if (filterToggleBtn && filtersContainer) {
        filterToggleBtn.addEventListener('click', function() {
            filtersContainer.classList.toggle('active');
            filterToggleBtn.classList.toggle('active');
        });
    }
}); 