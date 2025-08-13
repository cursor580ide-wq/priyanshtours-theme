<?php
/**
 * The template for displaying single itineraries (Tours)
 * 
 * This is a direct override of WP Travel's single-itineraries.php
 * Uses native WordPress functions instead of WP Travel helpers
 * 
 * @package Priyansh_Tours_Theme
 */

get_header(); 

// Enqueue required CSS
wp_enqueue_style('boxicons', 'https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css');
wp_enqueue_style('priyanshtours-tour-details', get_template_directory_uri() . '/assets/css/tour-details.css', array(), _S_VERSION);

$trip_id = get_the_ID();

// Fire WP Travel before main content hook (with priority to run after our hook removal)
do_action('wp_travel_before_main_content');
?>

<div class="tour-details-wrapper">
    <?php do_action('wp_travel_before_single_itinerary', $trip_id); ?>

    <section class="tour-hero-section">
        <div class="tour-featured-image">
            <?php 
            if (has_post_thumbnail()) {
                echo get_the_post_thumbnail($trip_id, 'full', array('class' => 'tour-featured-img'));
            }
            
            // Add hook after featured image
            do_action('wp_travel_single_trip_feat_img_after', $trip_id);
            ?>
        </div>
    </section>

    <div class="tour-main-content shadcn-card">
        <div class="tour-header">
            <div class="tour-header-left">
                <h1 class="tour-title"><?php the_title(); ?></h1>
                
                <?php do_action('wp_travel_single_trip_after_title', $trip_id); ?>
                
                <!-- Tour Meta Info -->
                <div class="tour-meta-info">
                    <?php 
                    $locations = wp_get_post_terms($trip_id, 'travel_locations', array('fields' => 'names'));
                    if (!empty($locations)) : 
                    ?>
                        <span class="tour-meta-item">
                            <i class='bx bx-map'></i> <?php echo esc_html(implode(', ', $locations)); ?>
                        </span>
                    <?php endif; ?>

                    <?php 
                    $trip_duration = get_post_meta($trip_id, 'wp_travel_trip_duration', true);
                    $trip_duration_night = get_post_meta($trip_id, 'wp_travel_trip_duration_night', true);
                    if ($trip_duration) : 
                    ?>
                        <span class="tour-meta-item">
                            <i class='bx bx-time'></i> <?php echo esc_html($trip_duration); ?> <?php esc_html_e('Days', 'wp-travel'); ?>
                            <?php if ($trip_duration_night) : ?>
                                / <?php echo esc_html($trip_duration_night); ?> <?php esc_html_e('Nights', 'wp-travel'); ?>
                            <?php endif; ?>
                        </span>
                    <?php endif; ?>

                    <?php 
                    $trip_types = wp_get_post_terms($trip_id, 'itinerary_types', array('fields' => 'names'));
                    if (!empty($trip_types)) : 
                    ?>
                        <span class="tour-meta-item">
                            <i class='bx bx-category'></i> <?php echo esc_html(implode(', ', $trip_types)); ?>
                        </span>
                    <?php endif; ?>
                    
                    <?php do_action('wp_travel_single_trip_meta_list', $trip_id); ?>
                </div>
            </div>

            <div class="tour-header-right">
                <div class="tour-price-container">
                    <?php 
                    // Use WP Travel helper functions to get correct price data
                    $sale_price = 0;
                    $regular_price = 0;
                    $has_sale = false;

                    // Check if WP Travel pricing helper exists
                    if (class_exists('WP_Travel_Helpers_Pricings')) {
                        $args = array('trip_id' => $trip_id);
                        $args_regular = array('trip_id' => $trip_id, 'is_regular_price' => true);
                        
                        $sale_price = WP_Travel_Helpers_Pricings::get_price($args);
                        $regular_price = WP_Travel_Helpers_Pricings::get_price($args_regular);
                        $has_sale = $sale_price < $regular_price && $regular_price > 0;
                    } else {
                        // Fallback to post meta if helper class doesn't exist
                        $sale_price = get_post_meta($trip_id, 'wp_travel_sale_price', true);
                        $regular_price = get_post_meta($trip_id, 'wp_travel_regular_price', true);
                        
                        if (!$sale_price) {
                            $sale_price = $regular_price;
                        }
                        
                        $has_sale = $regular_price && $regular_price != $sale_price;
                    }
                    
                    if ($has_sale && $regular_price) : 
                    ?>
                        <div class="tour-regular-price">
                            <span>
                            <?php 
                            if (function_exists('wp_travel_get_formated_price_currency')) {
                                echo wp_kses_post(wp_travel_get_formated_price_currency($regular_price));
                            } else {
                                // Get currency symbol from WordPress if possible
                                $currency_symbol = function_exists('get_woocommerce_currency_symbol') ? get_woocommerce_currency_symbol() : '$';
                                echo esc_html($currency_symbol) . number_format($regular_price, 2);
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
                            echo wp_kses_post(wp_travel_get_formated_price_currency($sale_price));
                        } else {
                            // Get currency symbol from WordPress if possible
                            $currency_symbol = function_exists('get_woocommerce_currency_symbol') ? get_woocommerce_currency_symbol() : '$';
                            echo esc_html($currency_symbol) . number_format($sale_price ? $sale_price : 0, 2);
                        }
                        ?>
                        </span>
                    </div>
                </div>
                
                <?php do_action('wp_travel_single_trip_after_price', $trip_id); ?>
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
            
            <?php 
            // Add Trip Facts/Additional Info if available
            if (function_exists('wptravel_trip_facts') && !has_action('wp_travel_frontend_trip_facts', 'wptravel_trip_facts')) : 
            ?>
            <div class="tour-facts">
                <h3><?php esc_html_e('Trip Facts', 'wp-travel'); ?></h3>
                <div class="tour-facts-content">
                    <?php wptravel_trip_facts($trip_id); ?>
                </div>
            </div>
            <?php endif; ?>
            
            <!-- Trip Details Grid -->
            <div class="tour-details-grid">
                <div class="tour-detail-card">
                    <div class="detail-icon">
                        <i class='bx bx-map-alt'></i>
                    </div>
                    <div class="detail-content">
                        <h4><?php esc_html_e('Locations', 'wp-travel'); ?></h4>
                        <p><?php echo !empty($locations) ? esc_html(implode(', ', $locations)) : esc_html__('N/A', 'wp-travel'); ?></p>
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
                                echo esc_html($trip_duration) . ' ' . esc_html__('Days', 'wp-travel');
                                if ($trip_duration_night) {
                                    echo ' / ' . esc_html($trip_duration_night) . ' ' . esc_html__('Nights', 'wp-travel');
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
                            $group_size = get_post_meta($trip_id, 'wp_travel_group_size', true);
                            $min_pax = get_post_meta($trip_id, 'wp_travel_minimum_pax', true);
                            $max_pax = get_post_meta($trip_id, 'wp_travel_maximum_pax', true);
                            
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
                        <p><?php echo !empty($trip_types) ? esc_html(implode(', ', $trip_types)) : esc_html__('N/A', 'wp-travel'); ?></p>
                    </div>
                </div>
                
                <?php 
                // Show activities if available
                $activities = array();
                if (taxonomy_exists('activity')) {
                    $activities = wp_get_post_terms($trip_id, 'activity', array('fields' => 'all'));
                    if (!empty($activities)) : 
                    ?>
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
                    <?php 
                    endif;
                }
                
                // Check if the tour has departure dates
                if (function_exists('wptravel_get_fixed_departure_date')) {
                    $fixed_departure = wptravel_get_fixed_departure_date($trip_id);
                    if ($fixed_departure) :
                    ?>
                    <div class="tour-detail-card">
                        <div class="detail-icon">
                            <i class='bx bx-calendar'></i>
                        </div>
                        <div class="detail-content">
                            <h4><?php esc_html_e('Departure', 'wp-travel'); ?></h4>
                            <p><?php echo esc_html($fixed_departure); ?></p>
                        </div>
                    </div>
                    <?php
                    endif;
                }
                ?>
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
                
                <?php 
                // Allow plugins to add more tabs
                do_action('wp_travel_trip_tabs', $trip_id); 
                ?>
            </div>
            
            <!-- Tab Contents -->
            <div class="tab-content active" id="tab-itinerary">
                <h3><?php esc_html_e('Trip Itinerary', 'wp-travel'); ?></h3>
                <?php 
                if (function_exists('do_action')) {
                    do_action('wp_travel_trip_itinerary_tab_content', $trip_id); 
                } else {
                    echo '<p>' . esc_html__('Itinerary details are not available.', 'wp-travel') . '</p>';
                }
                ?>
            </div>
            
            <div class="tab-content" id="tab-includes-excludes">
                <h3><?php esc_html_e('Includes & Excludes', 'wp-travel'); ?></h3>
                <?php 
                if (function_exists('do_action')) {
                    do_action('wp_travel_trip_includes_excludes_tab_content', $trip_id); 
                } else {
                    echo '<p>' . esc_html__('Includes/excludes information is not available.', 'wp-travel') . '</p>';
                }
                ?>
            </div>
            
            <div class="tab-content" id="tab-photos">
                <h3><?php esc_html_e('Trip Photos', 'wp-travel'); ?></h3>
                <?php 
                if (function_exists('do_action')) {
                    do_action('wp_travel_trip_gallery_tab_content', $trip_id); 
                } else {
                    echo '<p>' . esc_html__('Photo gallery is not available.', 'wp-travel') . '</p>';
                }
                ?>
            </div>
            
            <div class="tab-content" id="tab-map">
                <h3><?php esc_html_e('Map', 'wp-travel'); ?></h3>
                <?php 
                if (function_exists('do_action')) {
                    do_action('wp_travel_trip_map_tab_content', $trip_id); 
                } else {
                    echo '<p>' . esc_html__('Map is not available.', 'wp-travel') . '</p>';
                }
                ?>
            </div>
            
            <div class="tab-content" id="tab-reviews">
                <h3><?php esc_html_e('Reviews', 'wp-travel'); ?></h3>
                <?php 
                if (function_exists('do_action')) {
                    do_action('wp_travel_trip_reviews_tab_content', $trip_id); 
                } else {
                    echo '<p>' . esc_html__('Reviews are not available.', 'wp-travel') . '</p>';
                }
                ?>
            </div>
            
            <?php 
            // Allow plugins to add more tab content
            do_action('wp_travel_trip_tab_contents', $trip_id);
            ?>
        </div>
        
        <?php do_action('wp_travel_single_trip_after_tabs', $trip_id); ?>
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
                    
                    <?php
                    // Get current user data if logged in
                    $user_name = '';
                    $user_email = '';
                    $user_phone = '';
                    $default_message = '';
                    
                    if (is_user_logged_in()) {
                        $current_user = wp_get_current_user();
                        $user_name = $current_user->display_name;
                        $user_email = $current_user->user_email;
                        
                        // Try to get phone from user meta if available
                        $user_phone = get_user_meta($current_user->ID, 'phone', true);
                        if (!$user_phone) {
                            // Check common phone field variations in user meta
                            $possible_phone_fields = array('user_phone', 'billing_phone', 'phone_number', 'contact_phone', 'wp_travel_phone');
                            foreach ($possible_phone_fields as $field) {
                                $phone = get_user_meta($current_user->ID, $field, true);
                                if ($phone) {
                                    $user_phone = $phone;
                                    break;
                                }
                            }
                        }
                        
                        // Create a personalized default message
                        $default_message = sprintf(__("Hi, I'm %s and I'm interested in the %s tour. I would like to request more information about availability and pricing. Thank you.", 'wp-travel'), 
                            $user_name, 
                            get_the_title()
                        );
                    }
                    ?>
                    
                    <div class="form-group">
                        <label for="inquiry_name"><?php esc_html_e('Your Name', 'wp-travel'); ?> <span class="required">*</span></label>
                        <input type="text" id="inquiry_name" name="inquiry_name" class="form-input" value="<?php echo esc_attr($user_name); ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="inquiry_email"><?php esc_html_e('Email Address', 'wp-travel'); ?> <span class="required">*</span></label>
                        <input type="email" id="inquiry_email" name="inquiry_email" class="form-input" value="<?php echo esc_attr($user_email); ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="inquiry_phone"><?php esc_html_e('Phone Number', 'wp-travel'); ?></label>
                        <input type="tel" id="inquiry_phone" name="inquiry_phone" class="form-input" value="<?php echo esc_attr($user_phone); ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="inquiry_message"><?php esc_html_e('Your Inquiry', 'wp-travel'); ?> <span class="required">*</span></label>
                        <textarea id="inquiry_message" name="inquiry_message" class="form-textarea" rows="5" required><?php echo esc_textarea($default_message); ?></textarea>
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
            // Get current tour locations
            $current_locations = wp_get_post_terms($trip_id, 'travel_locations', array('fields' => 'ids'));
            
            $args = array(
                'post_type' => 'itineraries',
                'posts_per_page' => 3,
                'post__not_in' => array($trip_id),
            );
            
            if (!empty($current_locations)) {
                $args['tax_query'] = array(
                    array(
                        'taxonomy' => 'travel_locations',
                        'field' => 'term_id',
                        'terms' => $current_locations,
                    ),
                );
            }
            
            $related_trips = new WP_Query($args);
            
            if ($related_trips->have_posts()) :
                while ($related_trips->have_posts()) :
                    $related_trips->the_post();
                    $related_trip_id = get_the_ID();
                    $related_price = 0;
                    
                    // Use WP Travel helper functions to get the correct price
                    if (class_exists('WP_Travel_Helpers_Pricings')) {
                        $args = array('trip_id' => $related_trip_id);
                        $related_price = WP_Travel_Helpers_Pricings::get_price($args);
                    } else {
                        // Fallback to post meta
                        $related_price = get_post_meta($related_trip_id, 'wp_travel_sale_price', true);
                        if (!$related_price) {
                            $related_price = get_post_meta($related_trip_id, 'wp_travel_regular_price', true);
                        }
                    }
                    ?>
                    <div class="tour-card shadcn-card">
                        <a href="<?php the_permalink(); ?>" class="tour-card-link">
                            <div class="tour-card-image">
                                <?php if (has_post_thumbnail()) : ?>
                                    <?php the_post_thumbnail('medium', array('class' => 'tour-card-img')); ?>
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
                                            echo wp_kses_post(wp_travel_get_formated_price_currency($related_price));
                                        } else {
                                            // Get currency symbol from WordPress if possible
                                            $currency_symbol = function_exists('get_woocommerce_currency_symbol') ? get_woocommerce_currency_symbol() : '$';
                                            echo esc_html($currency_symbol) . number_format($related_price ? $related_price : 0, 2);
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

<?php do_action('wp_travel_after_main_content'); ?>

<!-- JavaScript for tabs and modals -->
<script>
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
    
    // Itinerary collapsible functionality
    // This code has been moved to itinerary.js
    // Using the separate file for better organization
    
    // Open/Close All functionality
    const openAllLink = document.querySelector('.open-all-itinerary-link');
    const closeAllLink = document.querySelector('.close-all-itinerary-link');
    
    if (openAllLink && closeAllLink) {
        openAllLink.addEventListener('click', function(e) {
            e.preventDefault();
            openAllLink.style.display = 'none';
            closeAllLink.style.display = 'inline-flex';
            
            itineraryHeaders.forEach(header => {
                header.classList.add('collapsed');
                const content = header.nextElementSibling;
                content.classList.add('show');
                content.style.maxHeight = content.scrollHeight + "px";
            });
        });
        
        closeAllLink.addEventListener('click', function(e) {
            e.preventDefault();
            closeAllLink.style.display = 'none';
            openAllLink.style.display = 'inline-flex';
            
            itineraryHeaders.forEach(header => {
                header.classList.remove('collapsed');
                const content = header.nextElementSibling;
                content.classList.remove('show');
                setTimeout(() => {
                    content.style.maxHeight = null;
                }, 10);
            });
        });
    }
    
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
    const ajaxurl = '<?php echo esc_js(admin_url('admin-ajax.php')); ?>';
    
    if (inquiryForm && ajaxurl) {
        inquiryForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const inquiryResponse = document.getElementById('inquiry-response');
            inquiryResponse.style.display = 'none'; // Hide previous response
            inquiryResponse.innerHTML = ''; // Clear previous response
            
            const loadingIndicator = document.createElement('div');
            loadingIndicator.className = 'inquiry-loading';
            loadingIndicator.innerHTML = '<i class="bx bx-loader-alt bx-spin"></i> <?php echo esc_js(__('Sending Inquiry...', 'wp-travel')); ?>';
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
                    inquiryResponse.innerHTML = '<i class="bx bx-check-circle"></i> <?php echo esc_js(__('Inquiry sent successfully!', 'wp-travel')); ?>';
                    inquiryForm.reset(); // Clear form fields
                } else {
                    inquiryResponse.className = 'inquiry-response error';
                    inquiryResponse.innerHTML = '<i class="bx bx-x-circle"></i> <?php echo esc_js(__('Error sending inquiry:', 'wp-travel')); ?> ' + data.message;
                }
            })
            .catch(error => {
                inquiryResponse.style.display = 'block';
                inquiryResponse.innerHTML = ''; // Clear loading indicator
                inquiryResponse.className = 'inquiry-response error';
                inquiryResponse.innerHTML = '<i class="bx bx-x-circle"></i> <?php echo esc_js(__('Error sending inquiry. Please try again later.', 'wp-travel')); ?>';
                console.error('Error:', error);
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

<?php
get_footer();
?> 