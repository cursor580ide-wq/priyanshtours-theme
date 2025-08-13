<?php
/**
 * The template for displaying Travel Stories archive
 *
 * @package PriyanshTours
 */

get_header();
?>

<div class="container travel-stories-page">
    <header class="page-header">
        <h1 class="page-title">Travel Stories</h1>
        <div class="archive-description">
            <p>Read inspiring travel stories and experiences from around the world</p>
        </div>
    </header>

    <div class="stories-grid">
        <?php
        // Check if this is the travel stories page
        if (is_page('travel-stories')) {
            // Create a custom query to get blog posts
            $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
            $args = array(
                'post_type' => 'post',
                'posts_per_page' => 9,
                'paged' => $paged,
                'category_name' => 'travel-stories'  // Assuming you have a category called "travel-stories"
            );
            
            $travel_stories = new WP_Query($args);
            
            if ($travel_stories->have_posts()) :
                while ($travel_stories->have_posts()) : $travel_stories->the_post();
                    ?>
                    <article id="post-<?php the_ID(); ?>" <?php post_class('story-card'); ?>>
                        <div class="story-image">
                            <?php if (has_post_thumbnail()) : ?>
                                <a href="<?php the_permalink(); ?>">
                                    <?php the_post_thumbnail('large'); ?>
                                </a>
                            <?php else : ?>
                                <a href="<?php the_permalink(); ?>">
                                    <img src="<?php echo get_template_directory_uri(); ?>/assets/images/placeholder.jpg" alt="<?php the_title_attribute(); ?>">
                                </a>
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
                    <p>No travel stories found. Check back soon for inspiring adventures!</p>
                </div>
                <?php
            endif;
        } else {
            // If it's not specifically the travel stories page, show regular page content
            while (have_posts()) : the_post();
                the_content();
            endwhile;
        }
        ?>
    </div>
</div>

<?php
get_footer();
?> 