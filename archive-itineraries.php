<?php
/**
 * The template for displaying Tour Archive / Tour Listings
 *
 * This template overrides the WP Travel plugin's archive-itineraries.php template
 * Implements Shadcn UI components for cards and buttons
 * Enhanced with Viator integration for hybrid tour display
 *
 * @package PriyanshTours
 */

get_header(); 

// Check Viator integration status
$viator_enabled = get_option('priyanshtours_viator_enabled', 0);
$viator_api_key = get_option('priyanshtours_viator_api_key', '');
$viator_integration_active = $viator_enabled && !empty($viator_api_key);

// Get current source filter
$current_source = isset($_GET['source']) ? sanitize_text_field($_GET['source']) : '';

?>

<main id="primary" class="site-main tours-listing-page">
    <div class="container">
        <?php do_action('wp_travel_before_main_content'); ?>
        
        <header class="tours-header">
            <h1 class="page-title"><?php _e('Find Your Perfect Tour', 'priyanshtours'); ?></h1>
            
            <!-- Viator Integration Status -->
            <?php if ($viator_integration_active) : ?>
                <div class="viator-integration-status enabled">
                    <i class="bx bx-check-circle"></i>
                    <span><?php _e('Showing tours from both local inventory and Viator', 'priyanshtours'); ?></span>
                </div>
            <?php else : ?>
                <div class="viator-integration-status disabled">
                    <i class="bx bx-x-circle"></i>
                    <span><?php _e('Showing local tours only - Viator integration inactive', 'priyanshtours'); ?></span>
                </div>
            <?php endif; ?>
        </header>

        <!-- Tour Source Filter -->
        <?php if ($viator_integration_active) : ?>
            <div class="tour-source-filter">
                <h3><?php _e('Tour Source', 'priyanshtours'); ?></h3>
                <div class="source-filters">
                    <button type="button" class="source-filter-btn <?php echo empty($current_source) ? 'active' : ''; ?>" data-source="">
                        <i class="bx bx-grid-alt"></i>
                        <?php _e('All Tours', 'priyanshtours'); ?>
                    </button>
                    <button type="button" class="source-filter-btn <?php echo $current_source === 'wp_travel' ? 'active' : ''; ?>" data-source="wp_travel">
                        <i class="bx bx-home"></i>
                        <?php _e('Local Tours', 'priyanshtours'); ?>
                    </button>
                    <button type="button" class="source-filter-btn <?php echo $current_source === 'viator' ? 'active' : ''; ?>" data-source="viator">
                        <i class="bx bx-globe"></i>
                        <?php _e('Viator Tours', 'priyanshtours'); ?>
                    </button>
                </div>
            </div>
        <?php endif; ?>

        <!-- Advanced Filter Section - Using Shadcn Button -->
        <div class="filter-section">
            <div class="filter-toggle">
                <button class="filter-toggle-btn shadcn-button shadcn-button-default">
                    <i class="bx bx-filter"></i> <?php _e('Filter Tours', 'priyanshtours'); ?>
                </button>
            </div>
            
            <div class="filters-container">
                <form id="tour-filters" class="tour-filters" method="get">
                    <?php if (!empty($current_source)) : ?>
                        <input type="hidden" name="source" value="<?php echo esc_attr($current_source); ?>">
                    <?php endif; ?>
                    
                    <div class="filters-grid">
                        <!-- Destination Filter -->
                        <div class="filter-group">
                            <label for="destination"><?php _e('Destination', 'priyanshtours'); ?></label>
                            <div class="filter-input">
                                <i class="bx bx-map"></i>
                                <select name="destination" id="destination" class="filter-select">
                                    <option value=""><?php _e('All Destinations', 'priyanshtours'); ?></option>
                                    <?php
                                    $destinations = get_terms([
                                        'taxonomy' => 'travel_locations',
                                        'hide_empty' => true,
                                    ]);
                                    if ($destinations && !is_wp_error($destinations)) {
                                        foreach ($destinations as $destination) {
                                            $selected = isset($_GET['destination']) && $_GET['destination'] == $destination->slug ? 'selected' : '';
                                            echo '<option value="' . esc_attr($destination->slug) . '" ' . $selected . '>' . esc_html($destination->name) . '</option>';
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        
                        <!-- Trip Type Filter -->
                        <div class="filter-group">
                            <label for="trip-type"><?php _e('Trip Type', 'priyanshtours'); ?></label>
                            <div class="filter-input">
                                <i class="bx bx-category"></i>
                                <select name="trip-type" id="trip-type" class="filter-select">
                                    <option value=""><?php _e('All Trip Types', 'priyanshtours'); ?></option>
                                    <?php
                                    $trip_types = get_terms([
                                        'taxonomy' => 'itinerary_types',
                                        'hide_empty' => true,
                                    ]);
                                    if ($trip_types && !is_wp_error($trip_types)) {
                                        foreach ($trip_types as $trip_type) {
                                            $selected = isset($_GET['trip-type']) && $_GET['trip-type'] == $trip_type->slug ? 'selected' : '';
                                            echo '<option value="' . esc_attr($trip_type->slug) . '" ' . $selected . '>' . esc_html($trip_type->name) . '</option>';
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        
                        <!-- Duration Filter -->
                        <div class="filter-group">
                            <label for="duration"><?php _e('Duration', 'priyanshtours'); ?></label>
                            <div class="filter-input">
                                <i class="bx bx-time"></i>
                                <select name="duration" id="duration" class="filter-select">
                                    <option value=""><?php _e('Any Duration', 'priyanshtours'); ?></option>
                                    <option value="1-3" <?php selected(isset($_GET['duration']) ? $_GET['duration'] : '', '1-3'); ?>><?php _e('1-3 Days', 'priyanshtours'); ?></option>
                                    <option value="4-7" <?php selected(isset($_GET['duration']) ? $_GET['duration'] : '', '4-7'); ?>><?php _e('4-7 Days', 'priyanshtours'); ?></option>
                                    <option value="8-14" <?php selected(isset($_GET['duration']) ? $_GET['duration'] : '', '8-14'); ?>><?php _e('8-14 Days', 'priyanshtours'); ?></option>
                                    <option value="15+" <?php selected(isset($_GET['duration']) ? $_GET['duration'] : '', '15+'); ?>><?php _e('15+ Days', 'priyanshtours'); ?></option>
                                </select>
                            </div>
                        </div>
                        
                        <!-- Price Range Filter -->
                        <div class="filter-group price-range">
                            <label><?php _e('Price Range', 'priyanshtours'); ?></label>
                            <div class="price-inputs">
                                <div class="filter-input">
                                    <i class="bx bx-rupee"></i>
                                    <input type="number" name="min_price" placeholder="<?php _e('Min', 'priyanshtours'); ?>" 
                                           value="<?php echo esc_attr(isset($_GET['min_price']) ? $_GET['min_price'] : ''); ?>">
                                </div>
                                <span class="price-separator">-</span>
                                <div class="filter-input">
                                    <i class="bx bx-rupee"></i>
                                    <input type="number" name="max_price" placeholder="<?php _e('Max', 'priyanshtours'); ?>" 
                                           value="<?php echo esc_attr(isset($_GET['max_price']) ? $_GET['max_price'] : ''); ?>">
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="filter-actions">
                        <button type="submit" class="shadcn-button shadcn-button-default">
                            <i class="bx bx-search"></i> <?php _e('Apply Filters', 'priyanshtours'); ?>
                        </button>
                        <a href="<?php echo esc_url(get_post_type_archive_link('itineraries')); ?>" 
                           class="shadcn-button shadcn-button-secondary">
                            <i class="bx bx-x"></i> <?php _e('Clear All', 'priyanshtours'); ?>
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Sort Options -->
        <div class="sort-section">
            <div class="sort-options">
                <label for="sort-tours"><?php _e('Sort by:', 'priyanshtours'); ?></label>
                <select name="sort" id="sort-tours" class="sort-select">
                    <option value="featured" <?php selected(isset($_GET['sort']) ? $_GET['sort'] : '', 'featured'); ?>><?php _e('Featured', 'priyanshtours'); ?></option>
                    <option value="price_low" <?php selected(isset($_GET['sort']) ? $_GET['sort'] : '', 'price_low'); ?>><?php _e('Price: Low to High', 'priyanshtours'); ?></option>
                    <option value="price_high" <?php selected(isset($_GET['sort']) ? $_GET['sort'] : '', 'price_high'); ?>><?php _e('Price: High to Low', 'priyanshtours'); ?></option>
                    <option value="duration_short" <?php selected(isset($_GET['sort']) ? $_GET['sort'] : '', 'duration_short'); ?>><?php _e('Duration: Short to Long', 'priyanshtours'); ?></option>
                    <option value="duration_long" <?php selected(isset($_GET['sort']) ? $_GET['sort'] : '', 'duration_long'); ?>><?php _e('Duration: Long to Short', 'priyanshtours'); ?></option>
                    <option value="alphabetical" <?php selected(isset($_GET['sort']) ? $_GET['sort'] : '', 'alphabetical'); ?>><?php _e('Alphabetical', 'priyanshtours'); ?></option>
                    <option value="newest" <?php selected(isset($_GET['sort']) ? $_GET['sort'] : '', 'newest'); ?>><?php _e('Newest First', 'priyanshtours'); ?></option>
                </select>
            </div>
        </div>

        <!-- Tours Grid Container -->
        <div class="tours-content">
            <?php if (have_posts()) : ?>
                
                <div class="tours-grid">
                    <?php
                    while (have_posts()) {
                        the_post();
                        // Include the custom tour card template
                        include locate_template('wp-travel-templates/content-archive-itineraries-custom.php');
                    }
                    ?>
                </div>

                <!-- Pagination -->
                <div class="tours-pagination">
                    <?php
                    $pagination_links = paginate_links([
                        'prev_text' => '<i class="bx bx-chevron-left"></i> ' . __('Previous', 'priyanshtours'),
                        'next_text' => __('Next', 'priyanshtours') . ' <i class="bx bx-chevron-right"></i>',
                        'type' => 'array'
                    ]);
                    
                    if ($pagination_links) {
                        echo '<nav class="pagination-nav">';
                        echo '<ul class="pagination-list">';
                        foreach ($pagination_links as $link) {
                            echo '<li class="pagination-item">' . $link . '</li>';
                        }
                        echo '</ul>';
                        echo '</nav>';
                    }
                    ?>
                </div>

            <?php else : ?>
                
                <div class="no-tours-found">
                    <div class="no-tours-content">
                        <i class="bx bx-map"></i>
                        <h3><?php _e('No tours found', 'priyanshtours'); ?></h3>
                        <p><?php _e('Try adjusting your search criteria or browse all available tours.', 'priyanshtours'); ?></p>
                        <a href="<?php echo esc_url(get_post_type_archive_link('itineraries')); ?>" 
                           class="shadcn-button shadcn-button-default">
                            <?php _e('View All Tours', 'priyanshtours'); ?>
                        </a>
                    </div>
                </div>
                
            <?php endif; ?>
        </div>

        <?php do_action('wp_travel_after_main_content'); ?>
    </div>
</main>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Filter toggle functionality
    const filterToggleBtn = document.querySelector('.filter-toggle-btn');
    const filtersContainer = document.querySelector('.filters-container');
    
    if (filterToggleBtn && filtersContainer) {
        filterToggleBtn.addEventListener('click', function() {
            filtersContainer.classList.toggle('active');
            filterToggleBtn.classList.toggle('active');
        });
    }
    
    // Sort change handler
    const sortSelect = document.getElementById('sort-tours');
    if (sortSelect) {
        sortSelect.addEventListener('change', function() {
            const url = new URL(window.location);
            if (this.value) {
                url.searchParams.set('sort', this.value);
            } else {
                url.searchParams.delete('sort');
            }
            window.location = url;
        });
    }
    
    // Source filter buttons
    const sourceFilterBtns = document.querySelectorAll('.source-filter-btn');
    sourceFilterBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const source = this.dataset.source;
            const url = new URL(window.location);
            
            // Remove all source filter classes
            sourceFilterBtns.forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            
            if (source) {
                url.searchParams.set('source', source);
            } else {
                url.searchParams.delete('source');
            }
            
            window.location = url;
        });
    });
});
</script>

<?php get_footer(); ?> 