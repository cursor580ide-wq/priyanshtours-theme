<?php
/**
 * Template part for displaying travel stories grid
 *
 * @package PriyanshTours
 */

// First check if the WP Travel Stories post type exists
$post_types = get_post_types(array(), 'names');
$wp_travel_stories_active = in_array('travel-story', $post_types) || in_array('wp-travel-story', $post_types);
?>

<div class="stories-grid">
    <?php
    if ($wp_travel_stories_active) {
        // Determine the correct post type name
        $story_post_type = in_array('travel-story', $post_types) ? 'travel-story' : 'wp-travel-story';
        
        // WP Travel Stories is active
        $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
        
        // Arguments for WP_Query to get WP Travel Stories
        $args = array(
            'post_type'      => $story_post_type,
            'posts_per_page' => 9,
            'paged'          => $paged,
        );
        
        // Query for travel stories
        $travel_stories = new WP_Query($args);
        
        if ($travel_stories->have_posts()) :
            while ($travel_stories->have_posts()) : $travel_stories->the_post();
                // Get story details
                $story_id = get_the_ID();
                $thumbnail = get_the_post_thumbnail_url($story_id, 'large') ?: get_template_directory_uri() . '/assets/images/placeholder.jpg';
                
                // Try to get location if available
                $location = '';
                if (function_exists('wp_travel_get_story_location')) {
                    $location = wp_travel_get_story_location($story_id);
                } elseif (has_term('', 'travel_locations', $story_id)) {
                    $locations = get_the_terms($story_id, 'travel_locations');
                    if (!empty($locations) && !is_wp_error($locations)) {
                        $location = $locations[0]->name;
                    }
                }
                ?>
                <article id="post-<?php the_ID(); ?>" <?php post_class('story-card'); ?>>
                    <div class="story-image">
                        <a href="<?php the_permalink(); ?>">
                            <img src="<?php echo esc_url($thumbnail); ?>" alt="<?php the_title_attribute(); ?>">
                        </a>
                        <?php if (!empty($location)) : ?>
                        <div class="story-location">
                            <i class="bx bx-map"></i> <?php echo esc_html($location); ?>
                        </div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="story-content">
                        <header class="entry-header">
                            <h2 class="entry-title">
                                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                            </h2>
                        </header>
                        
                        <div class="entry-meta">
                            <span class="posted-on">
                                <i class="bx bx-calendar"></i>
                                <?php echo get_the_date(); ?>
                            </span>
                            
                            <span class="byline">
                                <i class="bx bx-user"></i>
                                <?php the_author(); ?>
                            </span>
                        </div>
                        
                        <div class="entry-summary">
                            <?php the_excerpt(); ?>
                        </div>
                        
                        <div class="entry-footer">
                            <a href="<?php the_permalink(); ?>" class="read-more">
                                Read Full Story <i class="bx bx-right-arrow-alt"></i>
                            </a>
                        </div>
                    </div>
                </article>
                <?php
            endwhile;
            
            // Pagination
            echo '<div class="stories-pagination">';
            $big = 999999999; // need an unlikely integer
            echo paginate_links(array(
                'base' => str_replace($big, '%#%', esc_url(get_pagenum_link($big))),
                'format' => '?paged=%#%',
                'current' => max(1, get_query_var('paged')),
                'total' => $travel_stories->max_num_pages,
                'prev_text' => '<i class="bx bx-chevron-left"></i> Previous',
                'next_text' => 'Next <i class="bx bx-chevron-right"></i>'
            ));
            echo '</div>';
            
            // Restore original post data
            wp_reset_postdata();
        else :
            ?>
            <div class="no-stories-found">
                <p>No travel stories found. Please add some stories in the WP Travel Stories section.</p>
                <?php if (current_user_can('edit_posts')) : ?>
                    <p><a href="<?php echo admin_url('post-new.php?post_type=' . $story_post_type); ?>" class="admin-link">Create a travel story</a></p>
                <?php endif; ?>
            </div>
            <?php
        endif;
    } else {
        // FALLBACK: WP Travel Stories post type not found, try regular posts or itineraries
        $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
        
        // Check if itineraries exists as fallback
        $wp_travel_tours_active = in_array('itineraries', $post_types);
        
        if ($wp_travel_tours_active) {
            // Use itineraries as fallback
            $args = array(
                'post_type'      => 'itineraries',
                'posts_per_page' => 9,
                'paged'          => $paged,
            );
            
            $fallback_type = "WP Travel Tours";
        } else {
            // Use regular posts as ultimate fallback
            $args = array(
                'post_type'      => 'post',
                'posts_per_page' => 9,
                'paged'          => $paged,
                'category_name'  => 'travel',
            );
            
            $fallback_type = "regular blog posts";
        }
        
        // Query for fallback content
        $travel_stories = new WP_Query($args);
        
        if ($travel_stories->have_posts()) :
            ?>
            <div class="fallback-notice">
                <p><strong>Note:</strong> The WP Travel Stories feature is not active. Displaying <?php echo $fallback_type; ?> instead. Please create travel stories from the admin menu.</p>
            </div>
            <?php
            while ($travel_stories->have_posts()) : $travel_stories->the_post();
                $thumbnail = get_the_post_thumbnail_url(get_the_ID(), 'large') ?: get_template_directory_uri() . '/assets/images/placeholder.jpg';
                
                // Get location if it's an itinerary
                $location = '';
                if ($wp_travel_tours_active && function_exists('wp_travel_get_trip_location')) {
                    $locations = wp_travel_get_trip_location(get_the_ID());
                    if (!empty($locations) && is_array($locations) && isset($locations[0]->name)) {
                        $location = $locations[0]->name;
                    } elseif (!empty($locations) && is_object($locations) && isset($locations->name)) {
                        $location = $locations->name;
                    }
                }
                ?>
                <article id="post-<?php the_ID(); ?>" <?php post_class('story-card'); ?>>
                    <div class="story-image">
                        <a href="<?php the_permalink(); ?>">
                            <img src="<?php echo esc_url($thumbnail); ?>" alt="<?php the_title_attribute(); ?>">
                        </a>
                        <?php if (!empty($location)) : ?>
                        <div class="story-location">
                            <i class="bx bx-map"></i> <?php echo esc_html($location); ?>
                        </div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="story-content">
                        <header class="entry-header">
                            <h2 class="entry-title">
                                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                            </h2>
                        </header>
                        
                        <div class="entry-meta">
                            <span class="posted-on">
                                <i class="bx bx-calendar"></i>
                                <?php echo get_the_date(); ?>
                            </span>
                            
                            <span class="byline">
                                <i class="bx bx-user"></i>
                                <?php the_author(); ?>
                            </span>
                        </div>
                        
                        <div class="entry-summary">
                            <?php the_excerpt(); ?>
                        </div>
                        
                        <div class="entry-footer">
                            <a href="<?php the_permalink(); ?>" class="read-more">
                                Read More <i class="bx bx-right-arrow-alt"></i>
                            </a>
                        </div>
                    </div>
                </article>
                <?php
            endwhile;
            
            // Pagination
            echo '<div class="stories-pagination">';
            $big = 999999999; // need an unlikely integer
            echo paginate_links(array(
                'base' => str_replace($big, '%#%', esc_url(get_pagenum_link($big))),
                'format' => '?paged=%#%',
                'current' => max(1, get_query_var('paged')),
                'total' => $travel_stories->max_num_pages,
                'prev_text' => '<i class="bx bx-chevron-left"></i> Previous',
                'next_text' => 'Next <i class="bx bx-chevron-right"></i>'
            ));
            echo '</div>';
            
            // Restore original post data
            wp_reset_postdata();
        else :
            ?>
            <div class="no-stories-found">
                <p>No travel stories or fallback content found.</p>
                <?php if (current_user_can('edit_posts')) : ?>
                    <p>You can:</p>
                    <ul class="no-stories-options">
                        <li><a href="<?php echo admin_url('admin.php?page=wp-travel-travel-stories'); ?>" class="admin-link">Check WP Travel Stories settings</a></li>
                        <li><a href="<?php echo admin_url('post-new.php?post_type=post&category=travel'); ?>" class="admin-link">Create a regular blog post in Travel category</a></li>
                    </ul>
                <?php endif; ?>
            </div>
            <?php
        endif;
    }
    ?>
</div> 