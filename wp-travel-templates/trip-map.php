<?php
/**
 * Trip Map Tab Content Template
 *
 * This template handles the display of the trip location on a map
 *
 * @package Priyansh_Tours_Theme
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

$trip_id = get_the_ID();

// Get lat/lng coordinates
$lat = get_post_meta($trip_id, 'wp_travel_lat', true);
$lng = get_post_meta($trip_id, 'wp_travel_lng', true);
$location = get_post_meta($trip_id, 'wp_travel_location', true);
$map_data = array(
    'lat' => $lat,
    'lng' => $lng,
    'location' => $location,
);
$map_data = apply_filters('wp_travel_map_data', $map_data, $trip_id);
$api_key = '';
if (function_exists('wptravel_get_settings')) {
    $settings = wptravel_get_settings();
    $api_key = isset($settings['google_map_api_key']) ? $settings['google_map_api_key'] : '';
}
?>

<div class="trip-map-container clearfix">
    <?php if (!empty($lat) && !empty($lng)) : ?>
        <div id="wp-travel-map" class="wp-travel-map" style="height: 400px; width: 100%;"></div>
        <script>
            function initMap() {
                var latLng = {
                    lat: <?php echo floatval($map_data['lat']); ?>, 
                    lng: <?php echo floatval($map_data['lng']); ?>
                };
                
                var map = new google.maps.Map(document.getElementById('wp-travel-map'), {
                    zoom: 12,
                    center: latLng
                });
                
                var marker = new google.maps.Marker({
                    position: latLng,
                    map: map,
                    title: '<?php echo esc_js($map_data['location']); ?>'
                });
            }
        </script>
        <?php if (!empty($api_key)) : ?>
            <script src="https://maps.googleapis.com/maps/api/js?key=<?php echo esc_attr($api_key); ?>&callback=initMap" async defer></script>
        <?php else : ?>
            <script src="https://maps.googleapis.com/maps/api/js?callback=initMap" async defer></script>
        <?php endif; ?>
    <?php else : ?>
        <p class="wp-travel-no-detail-found-msg"><?php esc_html_e('Map data not available for this trip.', 'wp-travel'); ?></p>
    <?php endif; ?>
</div> 