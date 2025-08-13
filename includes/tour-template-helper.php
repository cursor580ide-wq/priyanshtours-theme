<?php
/**
 * Helper functions for tour templates
 *
 * @package Priyansh_Tours_Theme
 */

/**
 * Safely get WP Travel trip data
 */
function priyanshtours_get_trip_data($trip_id) {
    $data = array(
        'trip' => array(),
        'pricing_options' => array(),
        'is_sale_enabled' => false,
        'regular_price' => 0,
        'sale_price' => 0,
        'average_rating' => 0,
        'review_count' => 0,
    );

    if (!$trip_id) {
        return $data;
    }

    // Get trip data
    if (class_exists('WP_Travel_Helpers_Trips')) {
        $trip = WP_Travel_Helpers_Trips::get_trip($trip_id);
        $data['trip'] = isset($trip['trip']) ? $trip['trip'] : array();
        $data['pricing_options'] = isset($trip['pricing_options']) ? $trip['pricing_options'] : array();
    }

    // Check if on sale
    if (class_exists('WP_Travel_Helpers_Trips') && class_exists('WP_Travel_Helpers_Pricings')) {
        $data['is_sale_enabled'] = WP_Travel_Helpers_Trips::is_sale_enabled(array('trip_id' => $trip_id));
        $data['regular_price'] = WP_Travel_Helpers_Pricings::get_price($trip_id);
        $data['sale_price'] = WP_Travel_Helpers_Pricings::get_price($trip_id, true);
    }

    // Get reviews
    if (class_exists('WP_Travel_Helpers_Reviews')) {
        $data['average_rating'] = WP_Travel_Helpers_Reviews::get_average_rating($trip_id);
        $data['review_count'] = WP_Travel_Helpers_Reviews::get_review_count($trip_id);
    }

    return $data;
}

/**
 * Safely format price with currency
 */
function priyanshtours_format_price($price) {
    if (function_exists('wp_travel_get_formated_price_currency')) {
        return wp_kses_post(wp_travel_get_formated_price_currency($price));
    }
    
    // Fallback using WP-Travel's options or a sensible default.
    $currency_code = 'USD';
    $currency_symbol = '$';

    $currency_option = get_option('wp_travel_currency_option');
    if (isset($currency_option['currency']) && $currency_option['currency_symbol']) {
        $currency_code = $currency_option['currency'];
        $currency_symbol = $currency_option['currency_symbol'];
    }
    
    return $currency_symbol . number_format($price, 2);
} 