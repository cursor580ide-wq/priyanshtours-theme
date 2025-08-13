<?php
/**
 * Travel Story Handler
 * 
 * Place this file in your theme directory and include it in your functions.php
 */

/**
 * Handle travel story URLs directly
 */
function handle_travel_story_urls() {
    // Check if we're on a travel story page by URL
    if (isset($_GET['pagename']) && $_GET['pagename'] === 'travel-stories' && 
        isset($_GET['story']) && !empty($_GET['story'])) {
        
        $story_slug = sanitize_text_field($_GET['story']);
        
        // Try to find the post with this slug
        $args = array(
            'name' => $story_slug,
            'post_type' => array('travel-story', 'wp-travel-story', 'post'),
            'posts_per_page' => 1
        );
        
        $query = new WP_Query($args);
        
        if ($query->have_posts()) {
            // Set the global $post variable
            $query->the_post();
            global $post;
            
            // Load the template
            include(get_template_directory() . '/single-travel-story.php');
            exit;
        }
    }
}

// Add the action early in the WordPress request lifecycle
add_action('parse_request', 'handle_travel_story_urls', 0);
?> 