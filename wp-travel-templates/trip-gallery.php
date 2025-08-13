<?php
/**
 * Trip Gallery/Photos Tab Content Template
 *
 * This template handles the display of the trip gallery
 *
 * @package Priyansh_Tours_Theme
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

$trip_id = get_the_ID();
global $wp_travel_itinerary;
?>

<div class="trip-gallery-container clearfix">
    <?php
    $gallery_ids = get_post_meta($trip_id, 'wp_travel_itinerary_gallery_ids', true);
    
    if (!empty($gallery_ids)) :
        // Convert to array if it's a comma separated string
        if (!is_array($gallery_ids)) {
            $gallery_ids = explode(',', $gallery_ids);
        }
        ?>
        <div class="wp-travel-gallery-wrapper">
            <div class="gallery-image-grid">
                <?php
                foreach ($gallery_ids as $gallery_id) :
                    $gallery_image_url = wp_get_attachment_image_url($gallery_id, 'large');
                    $gallery_image_thumb = wp_get_attachment_image_url($gallery_id, 'medium');
                    $attachment = get_post($gallery_id);
                    $image_title = !empty($attachment->post_title) ? $attachment->post_title : '';
                    $image_alt = get_post_meta($gallery_id, '_wp_attachment_image_alt', true);
                    if (empty($image_alt)) {
                        $image_alt = $image_title;
                    }
                    ?>
                    <div class="gallery-image-item">
                        <a href="<?php echo esc_url($gallery_image_url); ?>" class="gallery-image-link" title="<?php echo esc_attr($image_title); ?>">
                            <img src="<?php echo esc_url($gallery_image_thumb); ?>" alt="<?php echo esc_attr($image_alt); ?>" class="gallery-image">
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php else : ?>
        <p class="wp-travel-no-detail-found-msg"><?php esc_html_e('No gallery images found.', 'wp-travel'); ?></p>
    <?php endif; ?>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize lightbox for gallery images if lightbox library is available
        if (typeof jQuery !== 'undefined' && jQuery.fn.magnificPopup) {
            jQuery('.gallery-image-link').magnificPopup({
                type: 'image',
                gallery: {
                    enabled: true
                }
            });
        }
    });
</script> 