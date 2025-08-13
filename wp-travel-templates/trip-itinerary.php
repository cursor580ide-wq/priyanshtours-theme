<?php
/**
 * Custom template for displaying trip itinerary
 * Based on the default WP Travel itineraries-list.php
 *
 * @package Priyansh_Tours_Theme
 */

global $post;
$wptravel_trip_id = $post->ID;
$wptravel_itineraries = get_post_meta($wptravel_trip_id, 'wp_travel_trip_itinerary_data', true);

if (isset($wptravel_itineraries) && !empty($wptravel_itineraries)) : ?>
    <div class="tour-itinerary-container">
        <?php if (apply_filters('wptravel_enable_itinerary_toogle', true)) : ?>
            <div class="wp-collapse-open clearfix">
                <a href="#" class="open-all-itinerary-link">
                    <span class="open-all" id="open-all"><i class='bx bx-plus-circle'></i> <?php esc_html_e('Open All', 'wp-travel'); ?></span>
                </a>
                <a href="#" class="close-all-itinerary-link" style="display:none;">
                    <span class="close-all" id="close-all"><i class='bx bx-minus-circle'></i> <?php esc_html_e('Close All', 'wp-travel'); ?></span>
                </a>
            </div>

            <?php foreach ($wptravel_itineraries as $k => $wptravel_itinerary) : 
                // Get itinerary details
                $label = isset($wptravel_itinerary['label']) ? stripslashes($wptravel_itinerary['label']) : '';
                $title = isset($wptravel_itinerary['title']) ? stripslashes($wptravel_itinerary['title']) : '';
                $desc = isset($wptravel_itinerary['desc']) ? stripslashes($wptravel_itinerary['desc']) : '';
                $date = (isset($wptravel_itinerary['date']) && !empty($wptravel_itinerary['date']) && 'invalid date' !== strtolower($wptravel_itinerary['date'])) ? 
                    wptravel_format_date($wptravel_itinerary['date']) : '';
                $time = isset($wptravel_itinerary['time']) && !empty($wptravel_itinerary['time']) ? 
                    stripslashes($wptravel_itinerary['time']) : '';
                $image = isset($wptravel_itinerary['image']) && !empty($wptravel_itinerary['image']) ? 
                    wp_get_attachment_url($wptravel_itinerary['image']) : '';
                
                // Format title for display
                $display_title = $label;
                if ($title) {
                    $display_title .= $display_title ? ': ' . $title : $title;
                }
                
                // Generate a unique ID for this itinerary item
                $itinerary_id = 'itinerary-' . $wptravel_trip_id . '-' . $k;
            ?>
                <div class="itinerary-item">
                    <div class="itinerary-header" data-target="#<?php echo esc_attr($itinerary_id); ?>">
                        <h4 class="itinerary-day">
                            <i class='bx bx-map-pin'></i> <?php echo esc_html($display_title); ?>
                            <span class="toggle-icon"><i class='bx bx-chevron-down'></i></span>
                        </h4>
                    </div>
                    
                    <div id="<?php echo esc_attr($itinerary_id); ?>" class="itinerary-content">
                        <div class="itinerary-details">
                            <?php if ($date || $time) : ?>
                                <div class="itinerary-meta">
                                    <?php if ($date) : ?>
                                        <div class="itinerary-date">
                                            <span class="meta-label"><i class='bx bx-calendar'></i> <?php esc_html_e('Date:', 'wp-travel'); ?></span>
                                            <span class="meta-value"><?php echo esc_html($date); ?></span>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <?php if ($time) : ?>
                                        <div class="itinerary-time">
                                            <span class="meta-label"><i class='bx bx-time'></i> <?php esc_html_e('Time:', 'wp-travel'); ?></span>
                                            <span class="meta-value"><?php echo esc_html($time); ?></span>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                            
                            <?php if ($title && $label != $title) : ?>
                                <h3 class="itinerary-title"><?php echo esc_html($title); ?></h3>
                            <?php endif; ?>
                            
                            <?php if ($desc) : ?>
                                <div class="itinerary-description">
                                    <?php echo wp_kses_post(wpautop($desc)); ?>
                                </div>
                            <?php endif; ?>
                            
                            <?php if ($image) : ?>
                                <div class="itinerary-image">
                                    <img src="<?php echo esc_url($image); ?>" alt="<?php echo esc_attr($title); ?>">
                                </div>
                            <?php endif; ?>
                            
                            <?php do_action('wp_travel_itineraries_after_content', $wptravel_itinerary); ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
            
        <?php else : ?>
            <?php foreach ($wptravel_itineraries as $k => $wptravel_itinerary) : 
                // Get itinerary details
                $label = isset($wptravel_itinerary['label']) ? stripslashes($wptravel_itinerary['label']) : '';
                $title = isset($wptravel_itinerary['title']) ? stripslashes($wptravel_itinerary['title']) : '';
                $desc = isset($wptravel_itinerary['desc']) ? stripslashes($wptravel_itinerary['desc']) : '';
                $date = (isset($wptravel_itinerary['date']) && !empty($wptravel_itinerary['date']) && 'invalid date' !== strtolower($wptravel_itinerary['date'])) ? 
                    wptravel_format_date($wptravel_itinerary['date']) : '';
                $time = isset($wptravel_itinerary['time']) && !empty($wptravel_itinerary['time']) ? 
                    stripslashes($wptravel_itinerary['time']) : '';
                $image = isset($wptravel_itinerary['image']) && !empty($wptravel_itinerary['image']) ? 
                    wp_get_attachment_url($wptravel_itinerary['image']) : '';
            ?>
                <div class="itinerary-item itinerary-static">
                    <div class="itinerary-header">
                        <h4 class="itinerary-day">
                            <i class='bx bx-map-pin'></i> <?php echo esc_html($label); ?>
                        </h4>
                    </div>
                    
                    <div class="itinerary-content show" style="max-height:none;">
                        <div class="itinerary-details">
                            <?php if ($date || $time) : ?>
                                <div class="itinerary-meta">
                                    <?php if ($date) : ?>
                                        <div class="itinerary-date">
                                            <span class="meta-label"><i class='bx bx-calendar'></i> <?php esc_html_e('Date:', 'wp-travel'); ?></span>
                                            <span class="meta-value"><?php echo esc_html($date); ?></span>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <?php if ($time) : ?>
                                        <div class="itinerary-time">
                                            <span class="meta-label"><i class='bx bx-time'></i> <?php esc_html_e('Time:', 'wp-travel'); ?></span>
                                            <span class="meta-value"><?php echo esc_html($time); ?></span>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                            
                            <?php if ($title && $label != $title) : ?>
                                <h3 class="itinerary-title"><?php echo esc_html($title); ?></h3>
                            <?php endif; ?>
                            
                            <?php if ($desc) : ?>
                                <div class="itinerary-description">
                                    <?php echo wp_kses_post(wpautop($desc)); ?>
                                </div>
                            <?php endif; ?>
                            
                            <?php if ($image) : ?>
                                <div class="itinerary-image">
                                    <img src="<?php echo esc_url($image); ?>" alt="<?php echo esc_attr($title); ?>">
                                </div>
                            <?php endif; ?>
                            
                            <?php do_action('wp_travel_itineraries_after_content', $wptravel_itinerary); ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
<?php else: ?>
    <div class="no-itinerary-msg">
        <p><?php esc_html_e('No itinerary details found for this tour.', 'priyanshtours'); ?></p>
    </div>
<?php endif; ?> 