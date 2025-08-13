<?php
/**
 * Custom Itinerary Archive Content Template for WP Travel & Viator
 * Enhanced with Shadcn UI styling and Viator integration
 * 
 * @package PriyanshTours
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

global $wp_query;
$trip_id = get_the_ID();

// Check if this is a Viator tour
$is_viator_tour = get_post_meta($trip_id, 'tour_source', true) === 'viator';
$viator_integration = null;

if (class_exists('PriyanshTours_Viator_Integration')) {
    $viator_integration = new PriyanshTours_Viator_Integration();
}

// Get tour data based on source
$min_price = 0;
$regular_price = 0;
$has_sale = false;
$rating_data = array('rating' => 0, 'count' => 0);

if ($is_viator_tour && $viator_integration) {
    // Get Viator tour data
    $viator_data = $viator_integration->get_viator_tour_data($trip_id);
    $min_price = get_post_meta($trip_id, 'wp_travel_trip_price', true) ?: 0;
    $rating_data['rating'] = get_post_meta($trip_id, 'viator_rating', true) ?: 0;
    $rating_data['count'] = get_post_meta($trip_id, 'viator_review_count', true) ?: 0;
    $regular_price = $min_price; // Viator doesn't typically have sale prices in the same way
    $has_sale = false;
} else {
    // Get WP Travel tour data
    if (class_exists('WP_Travel_Helpers_Pricings')) {
        $args = $args_regular = array('trip_id' => $trip_id);
        $args_regular['is_regular_price'] = true;
        $trip_price = WP_Travel_Helpers_Pricings::get_price($args);
        $regular_price = WP_Travel_Helpers_Pricings::get_price($args_regular);
        $has_sale = $trip_price < $regular_price && $regular_price > 0;
        
        // Make sure we have a valid price
        $min_price = !empty($trip_price) ? $trip_price : 0;
    }
    
    // WP Travel rating
    if (function_exists('wptravel_get_average_rating')) {
        $rating_data['rating'] = wptravel_get_average_rating($trip_id);
    }
    if (function_exists('wptravel_get_review_count')) {
        $rating_data['count'] = wptravel_get_review_count($trip_id);
    }
}

// Trip locations
$locations = array();
if (function_exists('wp_travel_get_trip_location')) {
    $locations = wp_travel_get_trip_location($trip_id);
}
$location_name = !empty($locations) ? $locations[0]->name : '';

// Trip duration
$trip_duration = get_post_meta($trip_id, 'wp_travel_trip_duration', true);
$trip_duration_night = get_post_meta($trip_id, 'wp_travel_trip_duration_night', true);
$duration_text = '';

if ($trip_duration) {
    $duration_text = sprintf(_n('%s Day', '%s Days', $trip_duration, 'priyanshtours'), $trip_duration);
    if ($trip_duration_night) {
        $duration_text .= sprintf(_n(' %s Night', ' %s Nights', $trip_duration_night, 'priyanshtours'), $trip_duration_night);
    }
}

// Trip thumbnail
$thumbnail = get_the_post_thumbnail_url($trip_id, 'large') ?: get_template_directory_uri() . '/assets/images/placeholder.jpg';

// Format prices with proper currency
$price_display = '';
$regular_price_display = '';

if ($min_price > 0) {
    if (function_exists('wp_travel_get_formated_price_currency')) {
        $price_display = wp_travel_get_formated_price_currency($min_price);
        if ($regular_price > $min_price) {
            $regular_price_display = wp_travel_get_formated_price_currency($regular_price);
        }
    } else {
        $currency_symbol = get_option('wp_travel_currency_option', array())['currency'] ?? '₹';
        $price_display = $currency_symbol . number_format($min_price, 2);
        if ($regular_price > $min_price) {
            $regular_price_display = $currency_symbol . number_format($regular_price, 2);
        }
    }
} else {
    $price_display = __('Contact for Price', 'priyanshtours');
}

// Generate star rating HTML
$star_html = '';
$avg_rating = floatval($rating_data['rating']);
$review_count = intval($rating_data['count']);

if ($avg_rating > 0) {
    $full_stars = floor($avg_rating);
    $half_star = ($avg_rating - $full_stars) >= 0.5;
    
    for ($i = 1; $i <= 5; $i++) {
        if ($i <= $full_stars) {
            $star_html .= '<i class="bx bxs-star"></i>';
        } elseif ($i == $full_stars + 1 && $half_star) {
            $star_html .= '<i class="bx bxs-star-half"></i>';
        } else {
            $star_html .= '<i class="bx bx-star"></i>';
        }
    }
}
?>

<div class="tour-card shadcn-card <?php echo $has_sale ? 'has-sale' : ''; ?> <?php echo $is_viator_tour ? 'viator-tour' : 'wp-travel-tour'; ?>">
    <a href="<?php echo esc_url(get_permalink($trip_id)); ?>">
        <?php if ($has_sale && !$is_viator_tour) : ?>
            <div class="sale-badge">
                <span><?php _e('Sale', 'priyanshtours'); ?></span>
            </div>
        <?php endif; ?>
        
        <!-- Tour Source Indicator -->
        <div class="tour-source-badge <?php echo $is_viator_tour ? 'viator-badge' : 'wp-travel-badge'; ?>">
            <?php if ($is_viator_tour) : ?>
                <i class="bx bx-globe"></i>
                <span><?php _e('Viator', 'priyanshtours'); ?></span>
            <?php else : ?>
                <i class="bx bx-home"></i>
                <span><?php _e('Local', 'priyanshtours'); ?></span>
            <?php endif; ?>
        </div>
        
        <div class="tour-card-image">
            <img src="<?php echo esc_url($thumbnail); ?>" alt="<?php echo esc_attr(get_the_title($trip_id)); ?>">
        </div>
        
        <div class="tour-card-content">
            <h3 class="tour-card-title"><?php echo get_the_title($trip_id); ?></h3>
            
            <div class="tour-meta">
                <?php if (!empty($location_name)) : ?>
                    <span class="tour-location">
                        <i class="bx bx-map"></i>
                        <?php echo esc_html($location_name); ?>
                    </span>
                <?php endif; ?>
                
                <?php if (!empty($duration_text)) : ?>
                    <span class="tour-duration">
                        <i class="bx bx-time"></i>
                        <?php echo esc_html($duration_text); ?>
                    </span>
                <?php endif; ?>
            </div>
            
            <?php if (!empty($star_html)) : ?>
                <div class="tour-rating">
                    <div class="stars">
                        <?php echo $star_html; ?>
                    </div>
                    <span class="rating-text">
                        (<?php echo number_format($avg_rating, 1); ?>) 
                        <?php printf(_n('%s review', '%s reviews', $review_count, 'priyanshtours'), $review_count); ?>
                    </span>
                </div>
            <?php endif; ?>
        </div>
        
        <div class="tour-card-footer">
            <div class="tour-explore">
                <span class="explore-text"><?php _e('Explore', 'priyanshtours'); ?></span>
                <i class="bx bx-right-arrow-alt"></i>
            </div>
            
            <div class="tour-card-price">
                <?php if ($has_sale && !empty($regular_price_display)) : ?>
                    <span class="regular-price"><?php echo wp_kses_post($regular_price_display); ?></span>
                    <span class="price-amount"><?php echo wp_kses_post($price_display); ?></span>
                <?php else : ?>
                    <span class="price-amount"><?php echo wp_kses_post($price_display); ?></span>
                <?php endif; ?>
            </div>
        </div>
    </a>
</div> 