<?php
/**
 * Trip Includes Excludes Tab Content Template
 *
 * This template handles the display of what's included and excluded in the trip
 *
 * @package Priyansh_Tours_Theme
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

$trip_id = get_the_ID();
$wp_travel_trip_includes = get_post_meta($trip_id, 'wp_travel_trip_include', true);
$wp_travel_trip_excludes = get_post_meta($trip_id, 'wp_travel_trip_exclude', true);
?>

<div class="trip-includes-excludes clearfix">
    <div class="trip-includes">
        <div class="title">
            <h3><?php esc_html_e('Includes', 'wp-travel'); ?></h3>
        </div>
        <?php if ($wp_travel_trip_includes) : ?>
            <ul>
                <?php 
                // Handle both string and array data types
                if (is_array($wp_travel_trip_includes)) {
                    foreach ($wp_travel_trip_includes as $include) : ?>
                        <li><i class="bx bx-check"></i> <?php echo wp_kses_post($include); ?></li>
                    <?php endforeach;
                } else {
                    // If it's a string, just display it as a single item
                    ?>
                    <li><i class="bx bx-check"></i> <?php echo wp_kses_post($wp_travel_trip_includes); ?></li>
                <?php } ?>
            </ul>
        <?php else : ?>
            <p class="wp-travel-no-detail-found-msg"><?php esc_html_e('No includes found.', 'wp-travel'); ?></p>
        <?php endif; ?>
    </div>

    <div class="trip-excludes">
        <div class="title">
            <h3><?php esc_html_e('Excludes', 'wp-travel'); ?></h3>
        </div>
        <?php if ($wp_travel_trip_excludes) : ?>
            <ul>
                <?php 
                // Handle both string and array data types
                if (is_array($wp_travel_trip_excludes)) {
                    foreach ($wp_travel_trip_excludes as $exclude) : ?>
                        <li><i class="bx bx-x"></i> <?php echo wp_kses_post($exclude); ?></li>
                    <?php endforeach;
                } else {
                    // If it's a string, just display it as a single item
                    ?>
                    <li><i class="bx bx-x"></i> <?php echo wp_kses_post($wp_travel_trip_excludes); ?></li>
                <?php } ?>
            </ul>
        <?php else : ?>
            <p class="wp-travel-no-detail-found-msg"><?php esc_html_e('No excludes found.', 'wp-travel'); ?></p>
        <?php endif; ?>
    </div>
</div> 