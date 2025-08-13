<?php
/**
 * Trip Reviews Tab Content Template
 *
 * This template handles the display of the trip reviews
 *
 * @package Priyansh_Tours_Theme
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

$trip_id = get_the_ID();
$comments = get_comments(array(
    'post_id' => $trip_id,
    'status' => 'approve',
));
?>

<div class="trip-reviews-container clearfix">
    <?php 
    // We're only displaying our custom reviews output
    if ($comments && is_array($comments) && count($comments) > 0) : 
    ?>
        <div class="trip-review-list">
            <?php foreach ($comments as $comment) : 
                // Get the actual rating from comment meta
                $rating = get_comment_meta($comment->comment_ID, 'wp_travel_rating', true);
                // Only proceed with rating if it's a valid numeric value
                $has_rating = !empty($rating) && is_numeric($rating);
                $rating = $has_rating ? intval($rating) : 0;
            ?>
                <div class="trip-review-item">
                    <div class="review-header">
                        <div class="reviewer-info">
                            <?php echo get_avatar($comment, 50); ?>
                            <div class="reviewer-details">
                                <h4 class="reviewer-name"><?php echo esc_html($comment->comment_author); ?></h4>
                                <div class="review-date"><?php echo esc_html(date_i18n(get_option('date_format'), strtotime($comment->comment_date))); ?></div>
                            </div>
                        </div>
                    </div>
                    <div class="review-content">
                        <?php echo wp_kses_post(wpautop($comment->comment_content)); ?>
                        
                        <?php if ($has_rating) : ?>
                        <!-- Rating display - showing actual user rating -->
                        <div class="review-rating">
                            <?php 
                            // Display the actual rating stars
                            for ($i = 1; $i <= 5; $i++) {
                                if ($i <= $rating) {
                                    echo '<i class="bx bxs-star"></i>'; // Filled star
                                } else {
                                    echo '<i class="bx bx-star"></i>'; // Empty star
                                }
                            }
                            ?>
                            <span class="rating-value"><?php echo esc_html($rating); ?>/5</span>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else : ?>
        <p class="wp-travel-no-detail-found-msg"><?php esc_html_e('No reviews found. Be the first to review this trip!', 'wp-travel'); ?></p>
    <?php endif; ?>
    
    <?php
    // Add review form using WordPress standard way
    if (comments_open($trip_id)) {
        ?>
        <div class="custom-review-form-wrapper">
            <h3><?php esc_html_e('Leave a Review', 'wp-travel'); ?></h3>
            
            <?php if (is_user_logged_in()) : ?>
                <form id="commentform" class="comment-form" action="<?php echo esc_url(site_url('/wp-comments-post.php')); ?>" method="post">
                    <div class="form-group wp-travel-rating-field">
                        <label><?php esc_html_e('Your Rating', 'wp-travel'); ?></label>
                        <div class="rating-select">
                            <div class="rating-stars">
                                <input type="radio" name="wp_travel_rating" id="wp-travel-rating-5" value="5">
                                <label for="wp-travel-rating-5"><i class="bx bx-star"></i></label>
                                
                                <input type="radio" name="wp_travel_rating" id="wp-travel-rating-4" value="4">
                                <label for="wp-travel-rating-4"><i class="bx bx-star"></i></label>
                                
                                <input type="radio" name="wp_travel_rating" id="wp-travel-rating-3" value="3">
                                <label for="wp-travel-rating-3"><i class="bx bx-star"></i></label>
                                
                                <input type="radio" name="wp_travel_rating" id="wp-travel-rating-2" value="2">
                                <label for="wp-travel-rating-2"><i class="bx bx-star"></i></label>
                                
                                <input type="radio" name="wp_travel_rating" id="wp-travel-rating-1" value="1">
                                <label for="wp-travel-rating-1"><i class="bx bx-star"></i></label>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="comment"><?php esc_html_e('Your Review', 'wp-travel'); ?></label>
                        <textarea id="comment" name="comment" class="form-textarea" rows="5" required></textarea>
                    </div>
                    
                    <?php
                    // Output hidden fields for the comment form
                    echo '<input type="hidden" name="comment_post_ID" value="' . esc_attr($trip_id) . '" id="comment_post_ID">';
                    echo '<input type="hidden" name="comment_parent" id="comment_parent" value="0">';
                    ?>
                    
                    <div class="form-submit">
                        <input name="submit" type="submit" id="submit" class="btn-submit" value="<?php esc_attr_e('Submit Review', 'wp-travel'); ?>">
                        <?php comment_id_fields(); ?>
                    </div>
                    
                    <?php do_action('comment_form', $trip_id); ?>
                </form>
            <?php else : ?>
                <div class="review-login-notice">
                    <?php
                    printf(
                        /* translators: %s: login URL */
                        esc_html__('Please %s to write a review.', 'wp-travel'),
                        '<a href="' . esc_url(wp_login_url(get_permalink($trip_id))) . '">' . esc_html__('log in', 'wp-travel') . '</a>'
                    );
                    ?>
                </div>
            <?php endif; ?>
        </div>
        <?php
    }
    ?>
</div> 