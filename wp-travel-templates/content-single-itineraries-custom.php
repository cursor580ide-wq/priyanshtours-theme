<?php
/**
 * Custom Itinerary Single Content Template for Priyansh Tours
 * This template overrides the default WP Travel single itinerary template
 *
 * @package Priyansh_Tours_Theme
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}
global $wp_travel_itinerary;

// Enqueue required styles and scripts
wp_enqueue_style('boxicons', 'https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css');
wp_enqueue_style('priyanshtours-shadcn-components', get_template_directory_uri() . '/assets/css/shadcn-components.css');

// Trip details
$trip_id = get_the_ID();
$trip = array();
$trip_data = array();
$pricing_data = array();

// Safely get trip data
if (class_exists('WP_Travel_Helpers_Trips')) {
    $trip = WP_Travel_Helpers_Trips::get_trip($trip_id);
    $trip_data = isset($trip['trip']) ? $trip['trip'] : array();
    $pricing_data = isset($trip['pricing_options']) ? $trip['pricing_options'] : array();
}

// Check if on sale
$is_sale_enabled = false;
$regular_price = 0;
$sale_price = 0;

if (class_exists('WP_Travel_Helpers_Trips') && class_exists('WP_Travel_Helpers_Pricings')) {
    $is_sale_enabled = WP_Travel_Helpers_Trips::is_sale_enabled(array('trip_id' => $trip_id));
    $regular_price = WP_Travel_Helpers_Pricings::get_price($trip_id);
    $sale_price = WP_Travel_Helpers_Pricings::get_price($trip_id, true);
}

// Trip details data
$trip_duration = get_post_meta($trip_id, 'wp_travel_trip_duration', true);
$trip_duration_night = get_post_meta($trip_id, 'wp_travel_trip_duration_night', true);
$trip_duration_days = $trip_duration;
$trip_duration_nights = $trip_duration_night;

// Trip locations
$locations = wp_get_post_terms($trip_id, 'travel_locations', array('fields' => 'all'));
$location_names = array();
foreach ($locations as $location) {
    $location_names[] = $location->name;
}

// Trip types
$trip_types = wp_get_post_terms($trip_id, 'itinerary_types', array('fields' => 'all'));
$trip_type_names = array();
foreach ($trip_types as $trip_type) {
    $trip_type_names[] = $trip_type->name;
}

// Trip activities
$activities = array();
if (taxonomy_exists('activity')) {
    $activities = wp_get_post_terms($trip_id, 'activity', array('fields' => 'all'));
}

// Group size
$group_size = get_post_meta($trip_id, 'wp_travel_group_size', true);
$min_pax = get_post_meta($trip_id, 'wp_travel_minimum_pax', true);
$max_pax = get_post_meta($trip_id, 'wp_travel_maximum_pax', true);

// Reviews - safely check if class exists
$average_rating = 0;
$review_count = 0;
if (class_exists('WP_Travel_Helpers_Reviews')) {
    $average_rating = WP_Travel_Helpers_Reviews::get_average_rating($trip_id);
    $review_count = WP_Travel_Helpers_Reviews::get_review_count($trip_id);
}
?>

<div class="tour-details-wrapper">
    <?php do_action('wp_travel_before_single_itinerary', $trip_id); ?>

    <!-- Hero Section with Featured Image -->
    <section class="tour-hero-section">
        <div class="tour-featured-image">
            <?php 
            if (has_post_thumbnail()) {
                echo get_the_post_thumbnail($trip_id, 'full', array('class' => 'tour-featured-img'));
            }
            
            // Sale badge
            if ($is_sale_enabled) : ?>
                <div class="tour-offer-badge">
                    <span><?php esc_html_e('OFFER', 'wp-travel'); ?></span>
                </div>
            <?php endif; ?>

            <?php if (isset($wp_travel_itinerary) && is_object($wp_travel_itinerary) && method_exists($wp_travel_itinerary, 'has_multiple_images') && $wp_travel_itinerary->has_multiple_images()) : ?>
                <button class="tour-gallery-button" id="open-tour-gallery">
                    <i class='bx bx-images'></i> <?php esc_html_e('View Gallery', 'wp-travel'); ?>
                </button>
            <?php endif; ?>
        </div>
    </section>

    <!-- Main Content Section -->
    <div class="tour-main-content shadcn-card">
        <div class="tour-header">
            <div class="tour-header-left">
                <h1 class="tour-title"><?php the_title(); ?></h1>
                
                <!-- Tour Meta Info -->
                <div class="tour-meta-info">
                    <?php if (!empty($location_names)) : ?>
                        <span class="tour-meta-item">
                            <i class='bx bx-map'></i> <?php echo esc_html(implode(', ', $location_names)); ?>
                        </span>
                    <?php endif; ?>

                    <?php if ($trip_duration) : ?>
                        <span class="tour-meta-item">
                            <i class='bx bx-time'></i> <?php echo esc_html($trip_duration_days); ?> <?php esc_html_e('Days', 'wp-travel'); ?>
                            <?php if ($trip_duration_nights) : ?>
                                / <?php echo esc_html($trip_duration_nights); ?> <?php esc_html_e('Nights', 'wp-travel'); ?>
                            <?php endif; ?>
                        </span>
                    <?php endif; ?>

                    <?php if (!empty($trip_type_names)) : ?>
                        <span class="tour-meta-item">
                            <i class='bx bx-category'></i> <?php echo esc_html(implode(', ', $trip_type_names)); ?>
                        </span>
                    <?php endif; ?>

                    <?php if ($average_rating > 0) : ?>
                        <span class="tour-meta-item">
                            <i class='bx bxs-star'></i> <?php echo esc_html(number_format($average_rating, 1)); ?> 
                            <span class="review-count">(<?php echo esc_html($review_count); ?> <?php esc_html_e('Reviews', 'wp-travel'); ?>)</span>
                        </span>
                    <?php endif; ?>
                </div>
            </div>

            <div class="tour-header-right">
                <div class="tour-price-container">
                    <?php if ($is_sale_enabled && $regular_price !== $sale_price) : ?>
                        <div class="tour-regular-price">
                            <span>
                            <?php 
                            if (function_exists('wp_travel_get_formated_price_currency')) {
                                echo wp_travel_get_formated_price_currency($regular_price);
                            } else {
                                echo '₹' . number_format($regular_price, 2);
                            }
                            ?>
                            </span>
                        </div>
                    <?php endif; ?>
                    
                    <div class="tour-sale-price">
                        <span class="price-label"><?php esc_html_e('From', 'wp-travel'); ?></span>
                        <span class="price-amount">
                        <?php 
                        if (function_exists('wp_travel_get_formated_price_currency')) {
                            echo wp_travel_get_formated_price_currency($sale_price);
                        } else {
                            echo '₹' . number_format($sale_price, 2);
                        }
                        ?>
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Dual Booking Options -->
        <div class="tour-booking-options">
            <div class="instant-book-option">
                <button class="shadcn-button shadcn-button-default book-now-button" id="book-now-button">
                    <i class='bx bx-calendar-check'></i> <?php esc_html_e('Book Now', 'wp-travel'); ?>
                </button>
            </div>
            <div class="inquiry-option">
                <button class="shadcn-button shadcn-button-outline inquiry-button" id="inquiry-button">
                    <i class='bx bx-message-square-detail'></i> <?php esc_html_e('Trip Inquiry', 'wp-travel'); ?>
                </button>
            </div>
        </div>

        <!-- Trip Overview -->
        <div class="tour-overview">
            <div class="tour-overview-content">
                <?php the_content(); ?>
            </div>
            
            <!-- Trip Details Grid -->
            <div class="tour-details-grid">
                <div class="tour-detail-card">
                    <div class="detail-icon">
                        <i class='bx bx-map-alt'></i>
                    </div>
                    <div class="detail-content">
                        <h4><?php esc_html_e('Locations', 'wp-travel'); ?></h4>
                        <p><?php echo !empty($location_names) ? esc_html(implode(', ', $location_names)) : esc_html__('N/A', 'wp-travel'); ?></p>
                    </div>
                </div>
                
                <div class="tour-detail-card">
                    <div class="detail-icon">
                        <i class='bx bx-time'></i>
                    </div>
                    <div class="detail-content">
                        <h4><?php esc_html_e('Duration', 'wp-travel'); ?></h4>
                        <p>
                            <?php 
                            if ($trip_duration) {
                                echo esc_html($trip_duration_days) . ' ' . esc_html__('Days', 'wp-travel');
                                if ($trip_duration_nights) {
                                    echo ' / ' . esc_html($trip_duration_nights) . ' ' . esc_html__('Nights', 'wp-travel');
                                }
                            } else {
                                esc_html_e('N/A', 'wp-travel');
                            }
                            ?>
                        </p>
                    </div>
                </div>
                
                <div class="tour-detail-card">
                    <div class="detail-icon">
                        <i class='bx bx-group'></i>
                    </div>
                    <div class="detail-content">
                        <h4><?php esc_html_e('Group Size', 'wp-travel'); ?></h4>
                        <p>
                            <?php 
                            if ($min_pax && $max_pax) {
                                echo esc_html($min_pax) . ' - ' . esc_html($max_pax);
                            } elseif ($min_pax) {
                                echo esc_html__('Min', 'wp-travel') . ': ' . esc_html($min_pax);
                            } elseif ($max_pax) {
                                echo esc_html__('Max', 'wp-travel') . ': ' . esc_html($max_pax);
                            } elseif ($group_size) {
                                echo esc_html($group_size);
                            } else {
                                esc_html_e('No limit', 'wp-travel');
                            }
                            ?>
                        </p>
                    </div>
                </div>
                
                <div class="tour-detail-card">
                    <div class="detail-icon">
                        <i class='bx bx-category-alt'></i>
                    </div>
                    <div class="detail-content">
                        <h4><?php esc_html_e('Trip Type', 'wp-travel'); ?></h4>
                        <p><?php echo !empty($trip_type_names) ? esc_html(implode(', ', $trip_type_names)) : esc_html__('N/A', 'wp-travel'); ?></p>
                    </div>
                </div>
                
                <?php if (!empty($activities)) : ?>
                <div class="tour-detail-card">
                    <div class="detail-icon">
                        <i class='bx bx-run'></i>
                    </div>
                    <div class="detail-content">
                        <h4><?php esc_html_e('Activities', 'wp-travel'); ?></h4>
                        <p>
                            <?php 
                            $activity_names = array();
                            foreach ($activities as $activity) {
                                $activity_names[] = $activity->name;
                            }
                            echo esc_html(implode(', ', $activity_names));
                            ?>
                        </p>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Tabs Navigation -->
        <div class="tour-tabs shadcn-tabs">
            <div class="shadcn-tabs-list" id="tour-tabs-list">
                <button class="shadcn-tabs-trigger active" data-tab="tab-itinerary">
                    <i class='bx bx-map-pin'></i> <?php esc_html_e('Itinerary', 'wp-travel'); ?>
                </button>
                <button class="shadcn-tabs-trigger" data-tab="tab-includes-excludes">
                    <i class='bx bx-list-check'></i> <?php esc_html_e('Includes/Excludes', 'wp-travel'); ?>
                </button>
                <button class="shadcn-tabs-trigger" data-tab="tab-photos">
                    <i class='bx bx-images'></i> <?php esc_html_e('Photos', 'wp-travel'); ?>
                </button>
                <button class="shadcn-tabs-trigger" data-tab="tab-map">
                    <i class='bx bx-map-alt'></i> <?php esc_html_e('Map', 'wp-travel'); ?>
                </button>
                <button class="shadcn-tabs-trigger" data-tab="tab-reviews">
                    <i class='bx bx-star'></i> <?php esc_html_e('Reviews', 'wp-travel'); ?>
                </button>
            </div>
            
            <!-- Tab Contents -->
            <div class="tab-content active" id="tab-itinerary">
                <h3><?php esc_html_e('Trip Itinerary', 'wp-travel'); ?></h3>
                <?php do_action('wp_travel_trip_itinerary_tab_content', $trip_id); ?>
            </div>
            
            <div class="tab-content" id="tab-includes-excludes">
                <h3><?php esc_html_e('Includes & Excludes', 'wp-travel'); ?></h3>
                <?php do_action('wp_travel_trip_includes_excludes_tab_content', $trip_id); ?>
            </div>
            
            <div class="tab-content" id="tab-photos">
                <h3><?php esc_html_e('Trip Photos', 'wp-travel'); ?></h3>
                <?php do_action('wp_travel_trip_gallery_tab_content', $trip_id); ?>
            </div>
            
            <div class="tab-content" id="tab-map">
                <h3><?php esc_html_e('Map', 'wp-travel'); ?></h3>
                <?php do_action('wp_travel_trip_map_tab_content', $trip_id); ?>
            </div>
            
            <div class="tab-content" id="tab-reviews">
                <h3><?php esc_html_e('Reviews', 'wp-travel'); ?></h3>
                <?php do_action('wp_travel_trip_reviews_tab_content', $trip_id); ?>
            </div>
        </div>
    </div>

    <!-- Trip Inquiry Modal -->
    <div class="inquiry-modal" id="inquiry-modal">
        <div class="inquiry-modal-content shadcn-card">
            <div class="inquiry-modal-header">
                <h3><?php esc_html_e('Trip Inquiry', 'wp-travel'); ?></h3>
                <button class="close-modal" id="close-inquiry-modal">
                    <i class='bx bx-x'></i>
                </button>
            </div>
            <div class="inquiry-modal-body">
                <form id="trip-inquiry-form" class="trip-inquiry-form">
                    <input type="hidden" name="trip_id" value="<?php echo esc_attr($trip_id); ?>">
                    <input type="hidden" name="trip_name" value="<?php echo esc_attr(get_the_title()); ?>">
                    <input type="hidden" name="action" value="trip_inquiry">
                    <?php wp_nonce_field('trip_inquiry_nonce', 'inquiry_nonce'); ?>
                    
                    <div class="form-group">
                        <label for="inquiry_name"><?php esc_html_e('Your Name', 'wp-travel'); ?> <span class="required">*</span></label>
                        <input type="text" id="inquiry_name" name="inquiry_name" class="form-input" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="inquiry_email"><?php esc_html_e('Email Address', 'wp-travel'); ?> <span class="required">*</span></label>
                        <input type="email" id="inquiry_email" name="inquiry_email" class="form-input" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="inquiry_phone"><?php esc_html_e('Phone Number', 'wp-travel'); ?></label>
                        <input type="tel" id="inquiry_phone" name="inquiry_phone" class="form-input">
                    </div>
                    
                    <div class="form-group">
                        <label for="inquiry_message"><?php esc_html_e('Your Inquiry', 'wp-travel'); ?> <span class="required">*</span></label>
                        <textarea id="inquiry_message" name="inquiry_message" class="form-textarea" rows="5" required></textarea>
                    </div>
                    
                    <div class="form-group">
                        <button type="submit" class="shadcn-button shadcn-button-default" id="inquiry-submit-btn">
                            <i class='bx bx-paper-plane'></i> <?php esc_html_e('Send Inquiry', 'wp-travel'); ?>
                        </button>
                    </div>
                    
                    <div id="inquiry-response" class="inquiry-response" style="display: none;"></div>
                </form>
            </div>
        </div>
    </div>

    <!-- Related Tours -->
    <section class="related-tours-section">
        <h3><?php esc_html_e('Related Tours', 'wp-travel'); ?></h3>
        <div class="related-tours-grid">
            <?php
            $args = array(
                'post_type' => 'itineraries',
                'posts_per_page' => 3,
                'post__not_in' => array($trip_id),
                'tax_query' => array(
                    array(
                        'taxonomy' => 'travel_locations',
                        'field' => 'term_id',
                        'terms' => wp_list_pluck($locations, 'term_id'),
                    ),
                ),
            );
            $related_trips = new WP_Query($args);
            
            if ($related_trips->have_posts()) :
                while ($related_trips->have_posts()) :
                    $related_trips->the_post();
                    $related_trip_id = get_the_ID();
                    $is_related_sale_enabled = false;
                    $related_price = 0;
                    if (class_exists('WP_Travel_Helpers_Trips') && class_exists('WP_Travel_Helpers_Pricings')) {
                        $is_related_sale_enabled = WP_Travel_Helpers_Trips::is_sale_enabled(array('trip_id' => $related_trip_id));
                        $related_price = WP_Travel_Helpers_Pricings::get_price($related_trip_id, true);
                    }
                    ?>
                    <div class="tour-card shadcn-card">
                        <a href="<?php the_permalink(); ?>" class="tour-card-link">
                            <div class="tour-card-image">
                                <?php if (has_post_thumbnail()) : ?>
                                    <?php the_post_thumbnail('medium', array('class' => 'tour-card-img')); ?>
                                <?php endif; ?>
                                <?php if ($is_related_sale_enabled) : ?>
                                    <div class="tour-card-offer">
                                        <span><?php esc_html_e('OFFER', 'wp-travel'); ?></span>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="tour-card-content">
                                <h4 class="tour-card-title"><?php the_title(); ?></h4>
                                <div class="tour-card-meta">
                                    <?php 
                                    $related_locations = wp_get_post_terms($related_trip_id, 'travel_locations', array('fields' => 'names'));
                                    if (!empty($related_locations)) : ?>
                                        <span class="tour-card-location">
                                            <i class='bx bx-map'></i> <?php echo esc_html($related_locations[0]); ?>
                                        </span>
                                    <?php endif; ?>
                                    
                                    <?php 
                                    $related_duration = get_post_meta($related_trip_id, 'wp_travel_trip_duration', true);
                                    if ($related_duration) : ?>
                                        <span class="tour-card-duration">
                                            <i class='bx bx-time'></i> <?php echo esc_html($related_duration); ?> <?php esc_html_e('Days', 'wp-travel'); ?>
                                        </span>
                                    <?php endif; ?>
                                </div>
                                <div class="tour-card-footer">
                                    <div class="tour-card-price">
                                        <span>
                                        <?php 
                                        if (function_exists('wp_travel_get_formated_price_currency')) {
                                            echo wp_travel_get_formated_price_currency($related_price);
                                        } else {
                                            echo '₹' . number_format($related_price, 2);
                                        }
                                        ?>
                                        </span>
                                    </div>
                                    <div class="tour-card-action">
                                        <span class="tour-explore"><?php esc_html_e('Explore', 'wp-travel'); ?> <i class='bx bx-right-arrow-alt'></i></span>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                <?php
                endwhile;
                wp_reset_postdata();
            endif;
            ?>
        </div>
    </section>
    
    <!-- Book Now panel (triggers WP Travel booking form) -->
    <div id="tour-booking-panel" class="booking-panel-container">
        <?php do_action('wp_travel_trip_booking_panel_content', $trip_id); ?>
    </div>

    <?php do_action('wp_travel_after_single_itinerary', $trip_id); ?>
</div>

<!-- JavaScript for tabs and modals -->
<script>
// Define ajaxurl if it's not already defined
var ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';
    
document.addEventListener('DOMContentLoaded', function() {
    // Tab functionality
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
    
    // Inquiry modal functionality
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
    
    // Handle inquiry form submission
    const inquiryForm = document.getElementById('trip-inquiry-form');
    if (inquiryForm) {
        inquiryForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const inquiryResponse = document.getElementById('inquiry-response');
            inquiryResponse.style.display = 'none'; // Hide previous response
            inquiryResponse.innerHTML = ''; // Clear previous response
            
            const loadingIndicator = document.createElement('div');
            loadingIndicator.className = 'inquiry-loading';
            loadingIndicator.innerHTML = '<i class="bx bx-loader-alt bx-spin"></i> <?php esc_html_e('Sending Inquiry...', 'wp-travel'); ?>';
            inquiryResponse.appendChild(loadingIndicator);
            
            const formData = new FormData(this);
            
            fetch(ajaxurl, {
                method: 'POST',
                headers: {
                    'X-WP-Nonce': inquiryForm.querySelector('input[name="inquiry_nonce"]').value
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                inquiryResponse.style.display = 'block';
                inquiryResponse.innerHTML = ''; // Clear loading indicator
                
                if (data.success) {
                    inquiryResponse.className = 'inquiry-response success';
                    inquiryResponse.innerHTML = '<i class="bx bx-check-circle"></i> <?php esc_html_e('Inquiry sent successfully!', 'wp-travel'); ?>';
                    inquiryForm.reset(); // Clear form fields
                } else {
                    inquiryResponse.className = 'inquiry-response error';
                    inquiryResponse.innerHTML = '<i class="bx bx-x-circle"></i> <?php esc_html_e('Error sending inquiry:', 'wp-travel'); ?> ' + data.message;
                }
            })
            .catch(error => {
                inquiryResponse.style.display = 'block';
                inquiryResponse.innerHTML = ''; // Clear loading indicator
                inquiryResponse.className = 'inquiry-response error';
                inquiryResponse.innerHTML = '<i class="bx bx-x-circle"></i> <?php esc_html_e('Error sending inquiry:', 'wp-travel'); ?> ' + error;
            });
        });
    }
    
    // Book Now button functionality
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
</script> 