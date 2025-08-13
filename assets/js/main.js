document.addEventListener('DOMContentLoaded', function() {
    // Filter toggle functionality from archive-itineraries.php
    const filterToggleBtn = document.querySelector('.filter-toggle-btn');
    const filtersContainer = document.querySelector('.filters-container');

    if (filterToggleBtn && filtersContainer) {
        filterToggleBtn.addEventListener('click', function() {
            filtersContainer.classList.toggle('active');
            filterToggleBtn.classList.toggle('active');
        });
    }

    // Sort change handler from archive-itineraries.php
    const sortSelect = document.getElementById('sort-tours');
    if (sortSelect) {
        sortSelect.addEventListener('change', function() {
            const url = new URL(window.location);
            if (this.value) {
                url.searchParams.set('sort', this.value);
            } else {
                url.searchParams.delete('sort');
            }
            window.location = url;
        });
    }

    // Source filter buttons from archive-itineraries.php
    const sourceFilterBtns = document.querySelectorAll('.source-filter-btn');
    sourceFilterBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const source = this.dataset.source;
            const url = new URL(window.location);

            // Remove all source filter classes
            sourceFilterBtns.forEach(b => b.classList.remove('active'));
            this.classList.add('active');

            if (source) {
                url.searchParams.set('source', source);
            } else {
                url.searchParams.delete('source');
            }

            window.location = url;
        });
    });

    // Tab functionality from single-itineraries.php
    const tabTriggers = document.querySelectorAll('.shadcn-tabs-trigger');
    const tabContents = document.querySelectorAll('.tab-content');

    tabTriggers.forEach(trigger => {
        trigger.addEventListener('click', function() {
            // Remove active class from all triggers and contents
            tabTriggers.forEach(t => t.classList.remove('active'));
            tabContents.forEach(c => c.classList.remove('active'));

            // Add active class to clicked trigger and corresponding content
            this.classList.add('active');
            document.getElementById(this.getAttribute('data-tab')).classList.add('active');
        });
    });

    // Inquiry modal functionality from single-itineraries.php
    const inquiryButton = document.getElementById('inquiry-button');
    const inquiryModal = document.getElementById('inquiry-modal');
    const closeInquiryModal = document.getElementById('close-inquiry-modal');

    if (inquiryButton && inquiryModal && closeInquiryModal) {
        inquiryButton.addEventListener('click', function() {
            inquiryModal.style.display = 'flex';
            document.body.style.overflow = 'hidden'; // Prevent scrolling
        });

        closeInquiryModal.addEventListener('click', function() {
            inquiryModal.style.display = 'none';
            document.body.style.overflow = ''; // Enable scrolling
        });

        // Close if clicked outside the modal content
        window.addEventListener('click', function(e) {
            if (e.target === inquiryModal) {
                inquiryModal.style.display = 'none';
                document.body.style.overflow = '';
            }
        });
    }

    // Handle inquiry form submission from single-itineraries.php
    const inquiryForm = document.getElementById('trip-inquiry-form');
    // Note: ajaxurl is localized in functions.php and will be available globally

    if (inquiryForm && typeof ajaxurl !== 'undefined') {
        inquiryForm.addEventListener('submit', function(e) {
            e.preventDefault();

            const inquiryResponse = document.getElementById('inquiry-response');
            inquiryResponse.style.display = 'none'; // Hide previous response
            inquiryResponse.innerHTML = ''; // Clear previous response

            const loadingIndicator = document.createElement('div');
            loadingIndicator.className = 'inquiry-loading';
            loadingIndicator.innerHTML = '<i class="bx bx-loader-alt bx-spin"></i> Sending Inquiry...';
            inquiryResponse.appendChild(loadingIndicator);
            inquiryResponse.style.display = 'block';

            const formData = new FormData(this);

            fetch(ajaxurl, {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                inquiryResponse.style.display = 'block';
                inquiryResponse.innerHTML = ''; // Clear loading indicator

                if (data.success) {
                    inquiryResponse.className = 'inquiry-response success';
                    inquiryResponse.innerHTML = '<i class="bx bx-check-circle"></i> Inquiry sent successfully!';
                    inquiryForm.reset(); // Clear form fields
                } else {
                    inquiryResponse.className = 'inquiry-response error';
                    inquiryResponse.innerHTML = '<i class="bx bx-x-circle"></i> Error sending inquiry: ' + data.data.message;
                }
            })
            .catch(error => {
                inquiryResponse.style.display = 'block';
                inquiryResponse.innerHTML = ''; // Clear loading indicator
                inquiryResponse.className = 'inquiry-response error';
                inquiryResponse.innerHTML = '<i class="bx bx-x-circle"></i> Error sending inquiry. Please try again later.';
                console.error('Error:', error);
            });
        });
    }

    // Book Now button functionality from single-itineraries.php
    const bookNowButton = document.getElementById('book-now-button');
    if (bookNowButton) {
        bookNowButton.addEventListener('click', function() {
            // Scroll to the booking panel
            const bookingPanel = document.getElementById('tour-booking-panel');
            if (bookingPanel) {
                bookingPanel.scrollIntoView({ behavior: 'smooth' });
            }
        });
    }
});
