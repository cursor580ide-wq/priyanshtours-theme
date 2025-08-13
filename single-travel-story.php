<?php
/**
 * The template for displaying single travel story posts
 *
 * @package PriyanshTours
 */

get_header();

global $wp_query, $post;

// If no post is set (from our direct include), look for it in the URL
if (empty($post)) {
    // Get story slug from URL
    $story_slug = '';
    if (strpos($_SERVER['REQUEST_URI'], '/travel-stories/') !== false) {
        $url_parts = explode('/', trim($_SERVER['REQUEST_URI'], '/'));
        $index = array_search('travel-stories', $url_parts);
        if ($index !== false && isset($url_parts[$index + 1])) {
            $story_slug = $url_parts[$index + 1];
        }
    }

    // If we have a slug, query for the post
    if (!empty($story_slug)) {
        $args = array(
            'name' => $story_slug,
            'post_type' => array('travel-story', 'wp-travel-story', 'post'),
            'posts_per_page' => 1
        );
        $custom_query = new WP_Query($args);
        
        if ($custom_query->have_posts()) {
            $custom_query->the_post();
            $using_custom_query = true;
        }
    }
}
?>

<div class="container single-story-container">
    <?php
    // Check if we have a post to display
    if (isset($post) && !empty($post)) :
    ?>
    <article id="post-<?php the_ID(); ?>" <?php post_class('single-story'); ?>>
        <header class="story-header">
            <h1 class="story-title"><?php the_title(); ?></h1>
            
            <div class="story-meta">
                <span class="posted-on">
                    <i class="bx bx-calendar"></i>
                    <?php echo get_the_date(); ?>
                </span>
                
                <span class="byline">
                    <i class="bx bx-user"></i>
                    <?php the_author(); ?>
                </span>
                
                <?php
                // Try to get location if available
                $location = '';
                $story_id = get_the_ID();
                if (function_exists('wp_travel_get_story_location')) {
                    $location = wp_travel_get_story_location($story_id);
                } elseif (has_term('', 'travel_locations', $story_id)) {
                    $locations = get_the_terms($story_id, 'travel_locations');
                    if (!empty($locations) && !is_wp_error($locations)) {
                        $location = $locations[0]->name;
                    }
                }
                
                if (!empty($location)) : 
                ?>
                <span class="story-location-meta">
                    <i class="bx bx-map"></i>
                    <?php echo esc_html($location); ?>
                </span>
                <?php endif; ?>
            </div>
        </header>

        <?php if (has_post_thumbnail()) : ?>
        <div class="story-featured-image">
            <?php the_post_thumbnail('full'); ?>
        </div>
        <?php endif; ?>

        <div class="story-content">
            <?php the_content(); ?>
        </div>

        <footer class="story-footer">
            <?php
            // Get all terms for the current post
            $terms = get_the_terms(get_the_ID(), 'travel_locations');
            
            // Check if there are any terms and if there were no errors
            if ($terms && !is_wp_error($terms)) : 
            ?>
            <div class="story-tags">
                <span class="tags-title"><i class="bx bx-purchase-tag"></i> Locations:</span>
                <div class="tags-list">
                    <?php foreach ($terms as $term) : ?>
                        <a href="<?php echo esc_url(get_term_link($term)); ?>" class="tag-link">
                            <?php echo esc_html($term->name); ?>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>
            
            <?php 
            // Reset postdata if we used a custom query
            if (isset($using_custom_query) && $using_custom_query) {
                wp_reset_postdata();
            }
            ?>
            
            <div class="story-navigation">
                <div class="nav-previous">
                    <a href="<?php echo esc_url(home_url('/travel-stories/')); ?>"><i class="bx bx-arrow-back"></i> Back to All Stories</a>
                </div>
            </div>
        </footer>
    </article>
    <?php 
    else : 
    // No post found
    ?>
    <div class="no-story-found">
        <p>The requested story could not be found.</p>
        <a href="<?php echo esc_url(home_url('/travel-stories/')); ?>">Return to Travel Stories</a>
    </div>
    <?php 
    endif; 
    ?>
</div>

<?php get_footer(); ?> 