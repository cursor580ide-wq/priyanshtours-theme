<?php
/**
 * Priyansh Tours functions and definitions
 *
 * @package Priyansh Tours
 */

// Define version constant
if (!defined('_S_VERSION')) {
    define('_S_VERSION', '1.0.0');
}

if ( ! function_exists( 'priyanshtours_theme_setup' ) ) :
    /**
     * Sets up theme defaults and registers support for various WordPress features.
     */
    function priyanshtours_theme_setup() {
        // Add default posts and comments RSS feed links to head.
        add_theme_support( 'automatic-feed-links' );

        /*
         * Let WordPress manage the document title.
         * By adding theme support, we declare that this theme does not use a
         * hard-coded <title> tag in the document head, and expect WordPress to
         * provide it for us.
         */
        add_theme_support( 'title-tag' );

        /*
         * Enable support for Post Thumbnails on posts and pages.
         */
        add_theme_support( 'post-thumbnails' );

        // Add support for core custom logo.
        add_theme_support(
            'custom-logo',
            array(
                'height'      => 40,
                'width'       => 150,
                'flex-width'  => true,
                'flex-height' => true,
            )
        );

        // This theme uses wp_nav_menu() in one location.
        register_nav_menus(
            array(
                'primary' => esc_html__( 'Primary Menu', 'priyanshtours-theme' ),
                'footer'  => esc_html__( 'Footer Menu', 'priyanshtours-theme' ),
                'primary_desktop'  => esc_html__( 'Primary Desktop Menu', 'priyanshtours-theme' ),
            )
        );

        /*
         * Switch default core markup for search form, comment form, and comments
         * to output valid HTML5.
         */
        add_theme_support(
            'html5',
            array(
                'search-form',
                'comment-form',
                'comment-list',
                'gallery',
                'caption',
                'style',
                'script',
            )
        );
    }
endif;
add_action( 'after_setup_theme', 'priyanshtours_theme_setup' );


/**
 * Enqueue scripts and styles.
 */
function priyanshtours_scripts() {
	// Define theme version for caching
	if (!defined('_S_VERSION')) {
		define('_S_VERSION', '1.0.0');
	}

	// External CSS
	wp_enqueue_style('boxicons', 'https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css', array(), '2.1.4');
	wp_enqueue_style('swiper-css', 'https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css', array(), '10.0.0');

	// Bundled and minified theme stylesheet
	wp_enqueue_style('priyanshtours-main-style', get_template_directory_uri() . '/dist/css/main.min.css', array(), _S_VERSION);

	// External JS
	wp_enqueue_script('swiper-js', 'https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js', array(), '10.0.0', true);

	// Bundled and minified theme script
	wp_enqueue_script('priyanshtours-main-script', get_template_directory_uri() . '/dist/js/main.min.js', array('jquery', 'swiper-js'), _S_VERSION, true);
    
    // Localize scripts - This makes PHP variables available in the bundled JS file
    wp_localize_script('priyanshtours-main-script', 'priyanshtours', array(
        'ajaxurl' => admin_url('admin-ajax.php'),
        'showFilters' => __('Filter Tours', 'priyanshtours'),
        'hideFilters' => __('Hide Filters', 'priyanshtours')
    ));

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'priyanshtours_scripts' );


/**
 * Customizer additions.
 */
require get_template_directory() . '/includes/customizer.php';


/**
 * Register Custom Post Type for Travel Stories.
 */
function priyanshtours_register_post_types() {
    $labels = array(
        'name'                  => _x( 'Travel Stories', 'Post type general name', 'priyanshtours-theme' ),
        'singular_name'         => _x( 'Travel Story', 'Post type singular name', 'priyanshtours-theme' ),
        'menu_name'             => _x( 'Travel Stories', 'Admin Menu text', 'priyanshtours-theme' ),
        'name_admin_bar'        => _x( 'Travel Story', 'Add New on Toolbar', 'priyanshtours-theme' ),
        'add_new'               => __( 'Add New', 'priyanshtours-theme' ),
        'add_new_item'          => __( 'Add New Travel Story', 'priyanshtours-theme' ),
        'new_item'              => __( 'New Travel Story', 'priyanshtours-theme' ),
        'edit_item'             => __( 'Edit Travel Story', 'priyanshtours-theme' ),
        'view_item'             => __( 'View Travel Story', 'priyanshtours-theme' ),
        'all_items'             => __( 'All Travel Stories', 'priyanshtours-theme' ),
        'search_items'          => __( 'Search Travel Stories', 'priyanshtours-theme' ),
        'not_found'             => __( 'No travel stories found.', 'priyanshtours-theme' ),
        'featured_image'        => _x( 'Story Cover Image', 'Overrides the "Featured Image" phrase for this post type.', 'priyanshtours-theme' ),
        'set_featured_image'    => _x( 'Set cover image', 'Overrides the "Set featured image" phrase for this post type.', 'priyanshtours-theme' ),
        'remove_featured_image' => _x( 'Remove cover image', 'Overrides the "Remove featured image" phrase for this post type.', 'priyanshtours-theme' ),
        'use_featured_image'    => _x( 'Use as cover image', 'Overrides the "Use as featured image" phrase for this post type.', 'priyanshtours-theme' ),
    );
    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array( 'slug' => 'travel-stories' ),
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => 20, // Below Pages
        'supports'           => array( 'title', 'editor', 'thumbnail', 'excerpt' ),
        'menu_icon'          => 'dashicons-palmtree',
    );
    register_post_type( 'travel-story', $args );
}
add_action( 'init', 'priyanshtours_register_post_types' );


/**
 * Customize the search form to match the theme's design.
 */
function priyanshtours_custom_search_form( $form ) {
    $form = '<form role="search" method="get" class="search-form" action="' . esc_url( home_url( '/' ) ) . '">
        <label>
            <span class="screen-reader-text">' . _x( 'Search for:', 'label', 'priyanshtours-theme' ) . '</span>
            <input type="search" class="search-field" placeholder="' . esc_attr_x( 'Search tours, destinations...', 'placeholder', 'priyanshtours-theme' ) . '" value="' . get_search_query() . '" name="s" />
        </label>
        <button type="submit" class="search-submit">
            <span class="screen-reader-text">' . esc_html_x( 'Search', 'submit button', 'priyanshtours-theme' ) . '</span>
            <svg class="search-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M11 19C15.4183 19 19 15.4183 19 11C19 6.58172 15.4183 3 11 3C6.58172 3 3 6.58172 3 11C3 15.4183 6.58172 19 11 19Z" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M21 21L16.65 16.65" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </button>
    </form>';
    return $form;
}
add_filter( 'get_search_form', 'priyanshtours_custom_search_form', 100 );


/**
 * Filter tours based on GET parameters for search functionality
 * Enhanced to handle homepage search and direct URL access
 */
function priyanshtours_filter_tours_query($query) {
    // Only modify main query on frontend for itinerary archives and taxonomy pages
    if (!is_admin() && $query->is_main_query()) {
        
        $request_uri = $_SERVER['REQUEST_URI'];
        
        // Check if we're on a tour-related page (including direct URL access)
        $is_tour_page = is_post_type_archive('itineraries') || 
                       is_tax('travel_locations') || 
                       is_tax('itinerary_types') ||
                       preg_match('/\/itinerary\/?(?:\?.*)?$/', $request_uri) ||
                       preg_match('/\/itineraries\/?(?:\?.*)?$/', $request_uri) ||
                       strpos($request_uri, 'wordpress/itinerary') !== false;
        
        if ($is_tour_page) {
            // Force this to be recognized as an itineraries archive
            $query->set('post_type', 'itineraries');
            $query->set('posts_per_page', 12); // Set a reasonable number of posts per page
            
            $tax_query = $query->get('tax_query') ?: array();
            $meta_query = $query->get('meta_query') ?: array();

            // Source filter (WP Travel vs Viator)
            if (!empty($_GET['source'])) {
                $source = sanitize_text_field($_GET['source']);
                if ($source === 'viator') {
                    $meta_query[] = array(
                        'key' => 'tour_source',
                        'value' => 'viator',
                        'compare' => '='
                    );
                } elseif ($source === 'wp_travel') {
                    $meta_query[] = array(
                        'relation' => 'OR',
                        array(
                            'key' => 'tour_source',
                            'compare' => 'NOT EXISTS'
                        ),
                        array(
                            'key' => 'tour_source',
                            'value' => 'viator',
                            'compare' => '!='
                        )
                    );
                }
            }

            // Destination filter
            if (!empty($_GET['destination'])) {
                $tax_query[] = array(
                    'taxonomy' => 'travel_locations',
                    'field'    => 'slug',
                    'terms'    => sanitize_text_field($_GET['destination']),
                );
            }

            // Trip Type filter
            $trip_type_param = !empty($_GET['trip-type']) ? $_GET['trip-type'] : (!empty($_GET['itinerary_types']) ? $_GET['itinerary_types'] : '');
            if (!is_tax('itinerary_types') && !empty($trip_type_param)) {
                $tax_query[] = array(
                    'taxonomy' => 'itinerary_types',
                    'field'    => 'slug',
                    'terms'    => sanitize_text_field($trip_type_param),
                );
            }

            // Duration filter
            if (!empty($_GET['duration'])) {
                $duration = sanitize_text_field($_GET['duration']);
                switch ($duration) {
                    case '1-3':
                        $meta_query[] = array(
                            'key' => 'wp_travel_trip_duration_days',
                            'value' => array(1, 3),
                            'type' => 'NUMERIC',
                            'compare' => 'BETWEEN'
                        );
                        break;
                    case '4-7':
                        $meta_query[] = array(
                            'key' => 'wp_travel_trip_duration_days',
                            'value' => array(4, 7),
                            'type' => 'NUMERIC',
                            'compare' => 'BETWEEN'
                        );
                        break;
                    case '8-14':
                        $meta_query[] = array(
                            'key' => 'wp_travel_trip_duration_days',
                            'value' => array(8, 14),
                            'type' => 'NUMERIC',
                            'compare' => 'BETWEEN'
                        );
                        break;
                    case '15+':
                        $meta_query[] = array(
                            'key' => 'wp_travel_trip_duration_days',
                            'value' => 15,
                            'type' => 'NUMERIC',
                            'compare' => '>='
                        );
                        break;
                }
            }

            // Price range filter
            if (!empty($_GET['min_price']) || !empty($_GET['max_price'])) {
                $price_meta = array(
                    'key' => 'wp_travel_trip_price',
                    'type' => 'NUMERIC',
                );

                if (!empty($_GET['min_price']) && !empty($_GET['max_price'])) {
                    $price_meta['value'] = array(intval($_GET['min_price']), intval($_GET['max_price']));
                    $price_meta['compare'] = 'BETWEEN';
                } elseif (!empty($_GET['min_price'])) {
                    $price_meta['value'] = intval($_GET['min_price']);
                    $price_meta['compare'] = '>=';
                } elseif (!empty($_GET['max_price'])) {
                    $price_meta['value'] = intval($_GET['max_price']);
                    $price_meta['compare'] = '<=';
                }

                $meta_query[] = $price_meta;
            }

            // Sort tours
            if (!empty($_GET['sort'])) {
                $sort = sanitize_text_field($_GET['sort']);
                switch ($sort) {
                    case 'price_low':
                        $query->set('meta_key', 'wp_travel_trip_price');
                        $query->set('orderby', 'meta_value_num');
                        $query->set('order', 'ASC');
                        break;
                    case 'price_high':
                        $query->set('meta_key', 'wp_travel_trip_price');
                        $query->set('orderby', 'meta_value_num');
                        $query->set('order', 'DESC');
                        break;
                    case 'duration_short':
                        $query->set('meta_key', 'wp_travel_trip_duration_days');
                        $query->set('orderby', 'meta_value_num');
                        $query->set('order', 'ASC');
                        break;
                    case 'duration_long':
                        $query->set('meta_key', 'wp_travel_trip_duration_days');
                        $query->set('orderby', 'meta_value_num');
                        $query->set('order', 'DESC');
                        break;
                    case 'alphabetical':
                        $query->set('orderby', 'title');
                        $query->set('order', 'ASC');
                        break;
                    case 'newest':
                        $query->set('orderby', 'date');
                        $query->set('order', 'DESC');
                        break;
                    default:
                        $query->set('orderby', 'menu_order');
                        $query->set('order', 'ASC');
                        break;
                }
            }

            // Apply tax_query if we have any
            if (!empty($tax_query)) {
                $tax_query['relation'] = 'AND'; // All conditions must be met
                $query->set('tax_query', $tax_query);
            }

            // Apply meta_query if we have any
            if (!empty($meta_query)) {
                $meta_query['relation'] = 'AND'; // All conditions must be met
                $query->set('meta_query', $meta_query);
            }
        }
    }
    
    return $query;
}
add_action('pre_get_posts', 'priyanshtours_filter_tours_query');

/**
 * Function to flush rewrite rules on theme activation
 */
function priyanshtours_flush_rewrite_rules() {
    flush_rewrite_rules();
}
add_action('after_switch_theme', 'priyanshtours_flush_rewrite_rules');

// Also flush rewrite rules once manually to fix any current issues
// This should be removed after it runs once
function priyanshtours_flush_rewrite_rules_once() {
    if (!get_transient('priyanshtours_rewrite_flush')) {
        flush_rewrite_rules();
        set_transient('priyanshtours_rewrite_flush', 1, DAY_IN_SECONDS);
    }
}
add_action('init', 'priyanshtours_flush_rewrite_rules_once', 99);

/**
 * Consolidated WP Travel Template Overrides.
 * This function handles all template overrides for the WP Travel plugin in a single place.
 * It prioritizes using the plugin's own filters (`wptravel_get_template`, `wptravel_template_part`)
 * before falling back to the general `template_include` filter.
 *
 * @param string $template The path of the template to include.
 * @return string The path of the new template.
 */
function priyanshtours_consolidated_wp_travel_overrides($template) {
    // Single Itinerary Page
    if (is_singular('itineraries')) {
        $new_template = locate_template(array('single-itineraries.php'));
        if (!empty($new_template)) {
            return $new_template;
        }
    }

    // Itinerary Archive and Taxonomy Pages
    if (is_post_type_archive('itineraries') || is_tax('travel_locations') || is_tax('itinerary_types')) {
        $new_template = locate_template(array('archive-itineraries.php'));
        if (!empty($new_template)) {
            return $new_template;
        }
    }

    return $template;
}
add_filter('template_include', 'priyanshtours_consolidated_wp_travel_overrides', 20);


/**
 * Override specific WP Travel template parts using the plugin's filter.
 * This is a more robust method than overriding the entire template file.
 */
function priyanshtours_override_wp_travel_template_parts($template, $template_name) {
    $overrides = array(
        'content-archive-itineraries.php' => 'wp-travel-templates/content-archive-itineraries-custom.php',
        'content-single-itineraries.php' => 'wp-travel-templates/content-single-itineraries-custom.php',
        'account/form-login.php' => 'wp-travel-templates/account/form-login.php',
        'account/form-lostpassword.php' => 'wp-travel-templates/account/form-lostpassword.php',
        'account/form-reset-password.php' => 'wp-travel-templates/account/form-reset-password.php',
        'account/content-dashboard.php' => 'wp-travel-templates/account/content-dashboard.php',
    );

    if (isset($overrides[$template_name])) {
        $new_template = locate_template($overrides[$template_name]);
        if (!empty($new_template)) {
            return $new_template;
        }
    }

    return $template;
}
add_filter('wptravel_get_template', 'priyanshtours_override_wp_travel_template_parts', 20, 2);


/**
 * Clean up WP Travel default actions and filters to prevent conflicts.
 * This is a more targeted approach than removing all actions.
 */
function priyanshtours_cleanup_wp_travel_hooks() {
    // Remove the default WP Travel sidebar on archive pages.
    remove_action('wp_travel_archive_listing_sidebar', 'wp_travel_archive_listing_sidebar');
    
    // We will control the content on single itinerary pages via our custom template.
    // The following hooks are removed to prevent the plugin from outputting its default content.
    if(is_singular('itineraries')) {
        remove_action('wptravel_single_itinerary_main_content', 'wptravel_single_itinerary_main_content');
    }
}
add_action('wp', 'priyanshtours_cleanup_wp_travel_hooks');

/**
 * Force our Travel Stories template to load for the Travel Stories menu link
 */
function priyanshtours_template_redirect() {
    // Check if we're on the travel stories URL
    if (strpos($_SERVER['REQUEST_URI'], 'travel-stories') !== false) {
        // Load our template
        include(get_template_directory() . '/travel-stories.php');
        exit;
    }
}
add_action('template_redirect', 'priyanshtours_template_redirect', 999);

/**
 * Force our custom templates for travel story URLs - more aggressive approach
 */
function priyanshtours_travel_story_template_handler() {
    // Check if this is a travel story URL
    if (strpos($_SERVER['REQUEST_URI'], '/travel-stories/') !== false && !is_archive() && !is_home()) {
        // Get the slug from the URL
        $url_parts = explode('/', trim($_SERVER['REQUEST_URI'], '/'));
        $index = array_search('travel-stories', $url_parts);
        
        if ($index !== false && isset($url_parts[$index + 1]) && !empty($url_parts[$index + 1])) {
            $story_slug = $url_parts[$index + 1];
            
            // Try to find the post with this slug
            $args = array(
                'name' => $story_slug,
                'post_type' => array('travel-story', 'wp-travel-story', 'post'),
                'posts_per_page' => 1
            );
            $story_query = new WP_Query($args);
            
            if ($story_query->have_posts()) {
                // We found the post, include our template directly
                include(get_template_directory() . '/single-travel-story.php');
                exit; // Stop WordPress from continuing
            }
        }
    }
}
// Higher priority to ensure it runs before other hooks
add_action('template_redirect', 'priyanshtours_travel_story_template_handler', 1);

// Remove the old template_include filter which isn't working correctly
remove_filter('template_include', 'priyanshtours_travel_story_template', 99);

/**
 * Register a travel stories shortcode
 */
function priyanshtours_travel_stories_shortcode($atts) {
    ob_start();
    include(get_template_directory() . '/template-parts/travel-stories-content.php');
    return ob_get_clean();
}
add_shortcode('travel_stories', 'priyanshtours_travel_stories_shortcode');

// Include the travel story handler
require_once(get_template_directory() . '/travel-story-handler.php');
require_once(get_template_directory() . '/includes/viator-integration.php');

/**
 * Add active class to Travel Stories menu item
 * 
 * @param array $classes Current menu item classes
 * @param object $item Menu item object
 * @return array Modified classes array
 */
function priyanshtours_fix_travel_stories_nav($classes, $item) {
    // Check if we're on the travel stories page or a single travel story
    if ((strpos($_SERVER['REQUEST_URI'], '/travel-stories/') !== false) && 
        ($item->title === 'Travel Stories' || $item->url === home_url('/travel-stories/') || 
         strpos($item->url, '/travel-stories') !== false)) {
        
        $classes[] = 'current-menu-item';
        $classes[] = 'current_page_item';
        $classes[] = 'active';
    }
    
    return $classes;
}
add_filter('nav_menu_css_class', 'priyanshtours_fix_travel_stories_nav', 10, 2);

/**
 * Tell WordPress that all travel stories URLs belong to the same page
 */
function priyanshtours_travel_stories_body_class($classes) {
    if (strpos($_SERVER['REQUEST_URI'], '/travel-stories/') !== false) {
        $classes[] = 'page-travel-stories';
        
        // Check if this is a single story
        $url_parts = explode('/', trim($_SERVER['REQUEST_URI'], '/'));
        $index = array_search('travel-stories', $url_parts);
        
        if ($index !== false && isset($url_parts[$index + 1]) && !empty($url_parts[$index + 1])) {
            $classes[] = 'single-travel-story';
        }
    }
    
    return $classes;
}
add_filter('body_class', 'priyanshtours_travel_stories_body_class');

/**
 * Fallback function for wp_travel_get_dashboard_url
 * This ensures the user icon always links to the dashboard even if the WP Travel function isn't available
 */
if (!function_exists('wp_travel_get_dashboard_url')) {
    function wp_travel_get_dashboard_url() {
        // Try to get the dashboard page ID from options
        $dashboard_page_id = get_option('wp_travel_dashboard_page_id');
        
        if ($dashboard_page_id) {
            return get_permalink($dashboard_page_id);
        }
        
        // Try to find the dashboard page by title or slug
        $possible_slugs = array('dashboard', 'my-account', 'user-dashboard', 'traveler-dashboard', 'wp-travel-dashboard');
        
        foreach ($possible_slugs as $slug) {
            $page = get_page_by_path($slug);
            if ($page) {
                return get_permalink($page->ID);
            }
        }
        
        // Last resort - check for any page with "dashboard" in the title
        $dashboard_pages = get_pages(array(
            'meta_key' => '_wp_page_template',
            'meta_value' => 'templates/dashboard.php' // Common template name for dashboard
        ));
        
        if (!empty($dashboard_pages)) {
            return get_permalink($dashboard_pages[0]->ID);
        }
        
        // If all else fails, return admin profile or account page
        return get_edit_profile_url();
    }
}

/**
 * Custom Authentication UI for Priyansh Tours
 */
function priyanshtours_custom_login_redirect($redirect_to, $request, $user) {
    // Check if WP Travel is active and has its own dashboard
    if (function_exists('wptravel_get_page_permalink')) {
        $dashboard_url = wptravel_get_page_permalink('wp-travel-dashboard');
        if (!empty($dashboard_url)) {
            return $dashboard_url;
        }
    }
    
    // Fallback to admin dashboard or home
    if (!empty($user) && is_object($user) && is_a($user, 'WP_User')) {
        if ($user->has_cap('administrator')) {
            return admin_url();
        } else {
            return home_url();
        }
    }
    
    return $redirect_to;
}
add_filter('login_redirect', 'priyanshtours_custom_login_redirect', 10, 3);

/**
 * Redirect wp-login.php to WP Travel custom login page
 */
function priyanshtours_custom_login_page() {
    // Don't redirect admin users, AJAX, or post requests
    if (
        (defined('DOING_AJAX') && DOING_AJAX) ||
        isset($_POST['wp-submit']) ||
        (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'POST') ||
        isset($_GET['action']) && in_array($_GET['action'], array('logout', 'postpass', 'lostpassword', 'retrievepassword', 'resetpass', 'rp', 'register'))
    ) {
        return;
    }
    
    // Don't redirect if user is already logged in
    if (is_user_logged_in()) {
        return;
    }
    
    // Check if WP Travel is active and has its own dashboard
    if (function_exists('wptravel_get_page_permalink')) {
        $dashboard_url = wptravel_get_page_permalink('wp-travel-dashboard');
        if (!empty($dashboard_url)) {
            wp_redirect($dashboard_url);
            exit;
        }
    }
}
add_action('login_init', 'priyanshtours_custom_login_page');


/**
 * Enqueue auth page styles
 */
function priyanshtours_enqueue_auth_styles() {
    // Check if we're on a dashboard-related page or a login page
    $is_dashboard_page = (strpos($_SERVER['REQUEST_URI'], 'wp-travel-dashboard') !== false);
    $is_login_page = (strpos($_SERVER['REQUEST_URI'], 'wp-login.php') !== false);
    
    // Also check for the specific WP Travel dashboard page parameter
    $is_dashboard_param = isset($_GET['page']) && $_GET['page'] === 'wp-travel-dashboard';
    
    if ($is_dashboard_page || $is_dashboard_param || $is_login_page) {
        wp_enqueue_style('priyanshtours-auth-styles', get_template_directory_uri() . '/assets/css/auth-pages.css', array(), _S_VERSION);
    }
    
    // Also enqueue on wp-login.php
    if (strpos($_SERVER['SCRIPT_NAME'], 'wp-login.php') !== false) {
        wp_enqueue_style('priyanshtours-auth-styles', get_template_directory_uri() . '/assets/css/auth-pages.css', array(), _S_VERSION);
    }
}
add_action('wp_enqueue_scripts', 'priyanshtours_enqueue_auth_styles');

/**
 * Add body class to auth pages
 */
function priyanshtours_auth_body_class($classes) {
    if (isset($_GET['page']) && $_GET['page'] == 'wp-travel-dashboard') {
        $classes[] = 'wp-travel-auth-page';
    }
    return $classes;
}
add_filter('body_class', 'priyanshtours_auth_body_class');

/**
 * Add the auth page class to HTML tag too
 */
function priyanshtours_auth_html_class($classes) {
    if (isset($_GET['page']) && $_GET['page'] == 'wp-travel-dashboard' || 
        strpos($_SERVER['REQUEST_URI'], 'wp-travel-dashboard') !== false) {
        $classes[] = 'wp-travel-auth-page';
    }
    return $classes;
}
add_filter('html_class', 'priyanshtours_auth_html_class');

/**
 * Forcibly disable admin bar on login pages
 */
function priyanshtours_disable_admin_bar_on_login() {
    if (isset($_GET['page']) && $_GET['page'] == 'wp-travel-dashboard' || 
        strpos($_SERVER['REQUEST_URI'], 'wp-travel-dashboard') !== false) {
        add_filter('show_admin_bar', '__return_false');
    }
}
add_action('init', 'priyanshtours_disable_admin_bar_on_login');


/**
 * Validate password confirmation on registration
 */
function priyanshtours_validate_registration_password_match($validation_errors) {
    if (isset($_POST['password']) && isset($_POST['confirm_password'])) {
        if ($_POST['password'] !== $_POST['confirm_password']) {
            $validation_errors->add('password_mismatch', esc_html__('Passwords do not match', 'priyanshtours'));
        }
    }
    return $validation_errors;
}
add_filter('wp_travel_register_validation', 'priyanshtours_validate_registration_password_match', 10, 1);

/**
 * Dequeue default WP Travel dashboard styles and enqueue our custom styles
 */
function priyanshtours_customize_dashboard_styles() {
    if (is_page() && function_exists('wptravel_get_page_id') && get_the_ID() === wptravel_get_page_id('dashboard')) {
        // Dequeue WP Travel dashboard styles
        wp_dequeue_style('wp-travel-user-account');
        wp_dequeue_style('wp-travel-frontend-account');
        wp_dequeue_style('wp-travel-frontend-tabs');
        
        // Enqueue our custom styles
        wp_enqueue_style('boxicons', 'https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css');
        wp_enqueue_style('priyanshtours-shadcn-components', get_template_directory_uri() . '/assets/css/shadcn-components.css');
    }
}
add_action('wp_enqueue_scripts', 'priyanshtours_customize_dashboard_styles', 99);

/**
 * Enqueue tour details styles and scripts
 */
function priyanshtours_enqueue_tour_details_styles() {
    // Load on single tour pages
    if (is_singular('itineraries')) {
        wp_enqueue_style('priyanshtours-tour-details', get_template_directory_uri() . '/assets/css/tour-details.css', array(), _S_VERSION);
        wp_enqueue_script('priyanshtours-itinerary', get_template_directory_uri() . '/assets/js/itinerary.js', array('jquery'), _S_VERSION, true);
    }
    
    // Load responsive styles on both tour listings and single tour pages
    if (is_singular('itineraries') || is_post_type_archive('itineraries') || is_tax('itinerary_types') || is_tax('travel_locations')) {
        wp_enqueue_style('priyanshtours-tour-responsive', get_template_directory_uri() . '/assets/css/tour-responsive.css', array(), _S_VERSION);
    }
}
add_action('wp_enqueue_scripts', 'priyanshtours_enqueue_tour_details_styles');

/**
 * Update our WP Travel template override function to include single tour template
 */
function priyanshtours_update_wp_travel_templates() {
    // Add the single itinerary content template to our existing override
    add_filter('wp_travel_locate_template', function($template, $template_name, $template_path) {
        // Custom tour single content template
        if ('content-single-itineraries.php' === $template_name) {
            $new_template = get_template_directory() . '/wp-travel-templates/content-single-itineraries-custom.php';
            if (file_exists($new_template)) {
                return $new_template;
            }
        }
        
        return $template;
    }, 15, 3); // Higher priority than our existing filter
}
add_action('init', 'priyanshtours_update_wp_travel_templates');

/**
 * Process trip inquiry form submission
 */
function priyanshtours_process_trip_inquiry() {
    // Verify nonce
    if (!isset($_POST['inquiry_nonce']) || !wp_verify_nonce($_POST['inquiry_nonce'], 'trip_inquiry_nonce')) {
        wp_send_json_error(array('message' => 'Security check failed'));
        return;
    }

    $trip_id = isset($_POST['trip_id']) ? absint($_POST['trip_id']) : 0;
    $trip_name = isset($_POST['trip_name']) ? sanitize_text_field($_POST['trip_name']) : '';
    $name = isset($_POST['inquiry_name']) ? sanitize_text_field($_POST['inquiry_name']) : '';
    $email = isset($_POST['inquiry_email']) ? sanitize_email($_POST['inquiry_email']) : '';
    $phone = isset($_POST['inquiry_phone']) ? sanitize_text_field($_POST['inquiry_phone']) : '';
    $message = isset($_POST['inquiry_message']) ? sanitize_textarea_field($_POST['inquiry_message']) : '';

    // Validation
    if (empty($name) || empty($email) || empty($message)) {
        wp_send_json_error(array('message' => 'Please fill in all required fields'));
        return;
    }

    // Email to admin
    $admin_email = get_option('admin_email');
    $subject = sprintf('Trip Inquiry: %s', $trip_name);
    
    $email_body = sprintf(
        "Trip Inquiry Details:\n\nTrip: %s\nName: %s\nEmail: %s\nPhone: %s\n\nMessage:\n%s",
        $trip_name,
        $name,
        $email,
        $phone,
        $message
    );
    
    $headers = array('Content-Type: text/plain; charset=UTF-8');
    
    $sent = wp_mail($admin_email, $subject, $email_body, $headers);
    
    if ($sent) {
        wp_send_json_success(array('message' => 'Your inquiry has been sent successfully!'));
    } else {
        wp_send_json_error(array('message' => 'There was an error sending your inquiry. Please try again later.'));
    }
}
add_action('wp_ajax_trip_inquiry', 'priyanshtours_process_trip_inquiry');
add_action('wp_ajax_nopriv_trip_inquiry', 'priyanshtours_process_trip_inquiry');


/**
 * Use custom itinerary template for trip tab
 */
function priyanshtours_custom_itinerary_tab_content($trip_id) {
    // Load our custom itinerary template
    include(get_template_directory() . '/wp-travel-templates/trip-itinerary.php');
}
// Remove default action and add our custom one
remove_action('wp_travel_trip_itinerary_tab_content', 'wptravel_trip_itinerary_tab_callback');
add_action('wp_travel_trip_itinerary_tab_content', 'priyanshtours_custom_itinerary_tab_content');

/**
 * Use custom includes/excludes template for trip tab
 */
function priyanshtours_custom_includes_excludes_tab_content($trip_id) {
    // Load our custom includes/excludes template
    include(get_template_directory() . '/wp-travel-templates/trip-includes-excludes.php');
}
// Remove default action (if exists) and add our custom one
remove_action('wp_travel_trip_includes_excludes_tab_content', 'wptravel_trip_includes_excludes_tab_callback');
add_action('wp_travel_trip_includes_excludes_tab_content', 'priyanshtours_custom_includes_excludes_tab_content');

/**
 * Use custom gallery template for trip tab
 */
function priyanshtours_custom_gallery_tab_content($trip_id) {
    // Load our custom gallery template
    include(get_template_directory() . '/wp-travel-templates/trip-gallery.php');
}
// Remove default action (if exists) and add our custom one
remove_action('wp_travel_trip_gallery_tab_content', 'wptravel_trip_gallery_tab_callback');
add_action('wp_travel_trip_gallery_tab_content', 'priyanshtours_custom_gallery_tab_content');

/**
 * Use custom map template for trip tab
 */
function priyanshtours_custom_map_tab_content($trip_id) {
    // Load our custom map template
    include(get_template_directory() . '/wp-travel-templates/trip-map.php');
}
// Remove default action (if exists) and add our custom one
remove_action('wp_travel_trip_map_tab_content', 'wptravel_trip_map_tab_callback');
add_action('wp_travel_trip_map_tab_content', 'priyanshtours_custom_map_tab_content');

/**
 * Use custom reviews template for trip tab
 */
function priyanshtours_custom_reviews_tab_content($trip_id) {
    // Load our custom reviews template
    include(get_template_directory() . '/wp-travel-templates/trip-reviews.php');
}
// Remove default action (if exists) and add our custom one
remove_action('wp_travel_trip_reviews_tab_content', 'wptravel_trip_reviews_tab_callback');
add_action('wp_travel_trip_reviews_tab_content', 'priyanshtours_custom_reviews_tab_content');

// Include tour template helper functions
require get_template_directory() . '/includes/tour-template-helper.php';

?> 