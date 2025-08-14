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

	// Main stylesheet
	wp_enqueue_style('priyanshtours-style', get_stylesheet_uri(), array(), _S_VERSION);

	// About Page specific styles
	if (is_page_template('template-about.php') || is_page('about') || is_page('about-us')) {
		wp_enqueue_style('priyanshtours-about-page', get_template_directory_uri() . '/assets/css/about-page.css', array(), _S_VERSION);
		wp_enqueue_script('priyanshtours-about-animations', get_template_directory_uri() . '/assets/js/about-animations.js', array('jquery'), _S_VERSION, true);
	}
	
	// Travel Stories page specific styles
	if (is_page_template('page-travel-stories.php') || is_page('travel-stories') || is_post_type_archive('travel-story') || is_singular('travel-story') || is_singular('wp-travel-story') || (is_page() && has_shortcode(get_post()->post_content, 'travel_stories'))) {
		wp_enqueue_style('priyanshtours-travel-stories', get_template_directory_uri() . '/assets/css/travel-stories.css', array(), _S_VERSION);
	}

	// Contact Us page specific styles
	if (is_page_template('template-contact.php')) {
		wp_enqueue_style('priyanshtours-contact-page', get_template_directory_uri() . '/assets/css/contact-page.css', array(), _S_VERSION);
	}
    
	// Boxicons CSS
    wp_enqueue_style('boxicons', 'https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css', array(), '2.1.4');
    
    // Swiper CSS
    wp_enqueue_style('swiper-css', 'https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css', array(), '10.0.0');

    // Custom Shadcn UI CSS Components
    wp_enqueue_style('shadcn-components', get_template_directory_uri() . '/assets/css/shadcn-components.css', array(), _S_VERSION);

	wp_enqueue_script( 'priyanshtours-navigation', get_template_directory_uri() . '/js/navigation.js', array(), _S_VERSION, true );

    // Swiper JS
    wp_enqueue_script('swiper-js', 'https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js', array(), '10.0.0', true);
    
    // Home page scripts
    wp_enqueue_script('priyanshtours-home', get_template_directory_uri() . '/assets/js/home.js', array('jquery', 'swiper-js'), _S_VERSION, true);
    

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'priyanshtours_scripts' );


/**
 * Customize Register - Add customizer sections and controls
 */
function priyanshtours_customize_register($wp_customize) {
    
    // 1. Add a new section for Front Page settings
    $wp_customize->add_section('front_page_settings', array(
        'title'    => __('Front Page Hero', 'priyanshtours'),
        'priority' => 30,
        'description' => __('Settings for the homepage hero section.', 'priyanshtours'),
    ));
    
    // Add setting for hero headline
    $wp_customize->add_setting('hero_title', array(
        'default'   => __('Authentic Journeys, Unforgettable Memories.', 'priyanshtours'),
        'transport' => 'refresh',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    
    // Add control for hero headline
    $wp_customize->add_control('hero_title_control', array(
        'label'    => __('Hero Title', 'priyanshtours'),
        'section'  => 'front_page_settings',
        'settings' => 'hero_title',
        'type'     => 'text',
    ));
    
    // Add setting for hero subtitle
    $wp_customize->add_setting('hero_subtitle', array(
        'default'   => __('Discover India with local experts.', 'priyanshtours'),
        'transport' => 'refresh',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    
    // Add control for hero subtitle
    $wp_customize->add_control('hero_subtitle_control', array(
        'label'    => __('Hero Subtitle', 'priyanshtours'),
        'section'  => 'front_page_settings',
        'settings' => 'hero_subtitle',
        'type'     => 'text',
    ));

    // Add setting for hero cta button text
    $wp_customize->add_setting('hero_cta_text', array(
        'default'   => __('Find My Adventure', 'priyanshtours'),
        'transport' => 'refresh',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    
    // Add control for hero cta button text
    $wp_customize->add_control('hero_cta_text_control', array(
        'label'    => __('Hero Button Text', 'priyanshtours'),
        'section'  => 'front_page_settings',
        'settings' => 'hero_cta_text',
        'type'     => 'text',
    ));
    
    // Add setting for hero cta button url
    $wp_customize->add_setting('hero_cta_link', array(
        'default'   => home_url('/itinerary/'),
        'transport' => 'refresh',
        'sanitize_callback' => 'esc_url_raw',
    ));
    
    // Add control for hero cta button url
    $wp_customize->add_control('hero_cta_link_control', array(
        'label'    => __('Hero Button Link', 'priyanshtours'),
        'section'  => 'front_page_settings',
        'settings' => 'hero_cta_link',
        'type'     => 'url',
    ));
    
    // Add setting to show/hide CTA
    $wp_customize->add_setting('show_hero_cta', array(
        'default' => true,
        'transport' => 'refresh',
        'sanitize_callback' => 'wp_validate_boolean',
    ));
    
    $wp_customize->add_control('show_hero_cta_control', array(
        'label' => __('Show Hero Call-to-Action Button', 'priyanshtours'),
        'section' => 'front_page_settings',
        'settings' => 'show_hero_cta',
        'type' => 'checkbox',
    ));

    // Add multiple image uploaders for the slideshow
    for ($i = 1; $i <= 8; $i++) {
        // Setting
        $wp_customize->add_setting("hero_slide_image_{$i}", array(
            'default'   => '',
            'transport' => 'refresh',
            'sanitize_callback' => 'esc_url_raw',
        ));
        
        // Control
        $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, "hero_slide_image_{$i}_control", array(
            'label'    => sprintf(__('Slide Image %d', 'priyanshtours'), $i),
            'section'  => 'front_page_settings',
            'settings' => "hero_slide_image_{$i}",
            'description' => __('Select an image for this slide.', 'priyanshtours'),
        )));
    }
    
    // Fallback hero image if no slides are set
    $wp_customize->add_setting('hero_background_image', array(
        'default'   => get_template_directory_uri() . '/assets/images/hero-background.jpg',
        'transport' => 'refresh',
        'sanitize_callback' => 'esc_url_raw',
    ));
    
    $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'hero_background_image_control', array(
        'label'    => __('Fallback Hero Background Image', 'priyanshtours'),
        'section'  => 'front_page_settings',
        'settings' => 'hero_background_image',
        'description' => __('This image is used if no slide images are selected above.', 'priyanshtours'),
    )));
    
    // Footer Section
    $wp_customize->add_section('priyanshtours_footer', array(
        'title'    => __('Footer Settings', 'priyanshtours'),
        'priority' => 130,
    ));
    
    // Footer Tagline/Description
    $wp_customize->add_setting('footer_description', array(
        'default'           => 'Experience India\'s true essence with authentic journeys curated by local experts.',
        'sanitize_callback' => 'sanitize_textarea_field',
    ));
    
    $wp_customize->add_control('footer_description', array(
        'label'    => __('Footer Description', 'priyanshtours'),
        'section'  => 'priyanshtours_footer',
        'type'     => 'textarea',
        'settings' => 'footer_description',
    ));
    
    // Contact Information Section
    $wp_customize->add_setting('footer_address', array(
        'default'           => 'Karawal Nagar, Delhi, India',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    
    $wp_customize->add_control('footer_address', array(
        'label'    => __('Address', 'priyanshtours'),
        'section'  => 'priyanshtours_footer',
        'type'     => 'text',
        'settings' => 'footer_address',
    ));
    
    $wp_customize->add_setting('footer_phone', array(
        'default'           => '+91 987 654 3210',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    
    $wp_customize->add_control('footer_phone', array(
        'label'    => __('Phone Number', 'priyanshtours'),
        'section'  => 'priyanshtours_footer',
        'type'     => 'text',
        'settings' => 'footer_phone',
    ));
    
    $wp_customize->add_setting('footer_email', array(
        'default'           => 'contact@priyanshtours.com',
        'sanitize_callback' => 'sanitize_email',
    ));
    
    $wp_customize->add_control('footer_email', array(
        'label'    => __('Email Address', 'priyanshtours'),
        'section'  => 'priyanshtours_footer',
        'type'     => 'email',
        'settings' => 'footer_email',
    ));
    
    // Social Media Links
    $wp_customize->add_setting('footer_facebook_url', array(
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
    ));
    
    $wp_customize->add_control('footer_facebook_url', array(
        'label'    => __('Facebook URL', 'priyanshtours'),
        'section'  => 'priyanshtours_footer',
        'type'     => 'url',
        'settings' => 'footer_facebook_url',
    ));
    
    $wp_customize->add_setting('footer_instagram_url', array(
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
    ));
    
    $wp_customize->add_control('footer_instagram_url', array(
        'label'    => __('Instagram URL', 'priyanshtours'),
        'section'  => 'priyanshtours_footer',
        'type'     => 'url',
        'settings' => 'footer_instagram_url',
    ));
    
    $wp_customize->add_setting('footer_tripadvisor_url', array(
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
    ));
    
    $wp_customize->add_control('footer_tripadvisor_url', array(
        'label'    => __('TripAdvisor URL', 'priyanshtours'),
        'section'  => 'priyanshtours_footer',
        'type'     => 'url',
        'settings' => 'footer_tripadvisor_url',
    ));
    
    $wp_customize->add_setting('footer_youtube_url', array(
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
    ));
    
    $wp_customize->add_control('footer_youtube_url', array(
        'label'    => __('YouTube URL', 'priyanshtours'),
        'section'  => 'priyanshtours_footer',
        'type'     => 'url',
        'settings' => 'footer_youtube_url',
    ));
    
    $wp_customize->add_setting('footer_twitter_url', array(
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
    ));
    
    $wp_customize->add_control('footer_twitter_url', array(
        'label'    => __('Twitter URL', 'priyanshtours'),
        'section'  => 'priyanshtours_footer',
        'type'     => 'url',
        'settings' => 'footer_twitter_url',
    ));
}
add_action('customize_register', 'priyanshtours_customize_register');

/**
 * Include the custom gallery control for the Customizer.
 * We need to do this because the gallery control is not part of the WordPress core.
 */
if ( class_exists( 'WP_Customize_Control' ) ) {
    class WP_Customize_Gallery_Control extends WP_Customize_Control {
        public $type = 'gallery';

        public function enqueue() {
            wp_enqueue_media();
            // You might need to add custom JS here to handle the gallery functionality
        }

        public function render_content() {
            ?>
            <label>
                <span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
                <span class="description customize-control-description"><?php echo esc_html( $this->description ); ?></span>
                <div class="gallery-preview-container">
                    <?php
                    $image_ids = $this->value();
                    if ( is_array( $image_ids ) && ! empty( $image_ids ) ) {
                        foreach ( $image_ids as $image_id ) {
                            echo '<img src="' . esc_url( wp_get_attachment_thumb_url( $image_id ) ) . '" class="gallery-preview-image" style="max-width:80px; height:auto; margin:5px; border:1px solid #ddd;">';
                        }
                    }
                    ?>
                </div>
                <input type="hidden" class="gallery-input" <?php $this->link(); ?> value="<?php echo esc_attr( implode( ',', (array) $this->value() ) ); ?>">
                <button type="button" class="button button-primary upload-button"><?php esc_html_e( 'Edit Gallery', 'priyanshtours-theme' ); ?></button>
                <button type="button" class="button button-secondary remove-button"><?php esc_html_e( 'Clear Gallery', 'priyanshtours-theme' ); ?></button>
            </label>
            <?php
        }
    }
}

function priyanshtours_add_customizer_gallery_js() {
    wp_enqueue_script(
        'priyanshtours-customizer-gallery',
        get_template_directory_uri() . '/assets/js/customizer-gallery.js',
        array( 'jquery', 'customize-controls' ),
        false,
        true
    );
}
add_action( 'customize_controls_enqueue_scripts', 'priyanshtours_add_customizer_gallery_js' );


function priyanshtours_sanitize_gallery( $value ) {
    if ( is_string( $value ) ) {
        $image_ids = array_map( 'absint', explode( ',', $value ) );
        return array_filter( $image_ids );
    }
    return array();
}


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


// Remove any filters that might be interfering
function priyanshtours_remove_interfering_filters() {
    // Remove various filters that might be interfering with template display
    remove_all_filters('the_content', 50);
    remove_all_filters('wp_travel_archive_listing_sidebar');
}
add_action('wp', 'priyanshtours_remove_interfering_filters');

/**
 * Remove any conflicting WP Travel filters and actions
 */
function priyanshtours_disable_wp_travel_conflicting_template_hooks() {
    // If we are on a tour listing or taxonomy page
    if (is_post_type_archive('itineraries') || is_tax('itinerary_types') || is_tax('travel_locations')) {
        // Remove the template loader filter from WP Travel
        remove_all_filters('template_include', 11);
        
        // Remove content hooks that might interfere
        remove_all_actions('wp_travel_before_main_content');
        remove_all_actions('wp_travel_after_main_content');
        remove_all_actions('wp_travel_archive_listing_sidebar');
        
        // Re-add our own hooks
        add_action('wp_travel_before_main_content', 'priyanshtours_before_main_content');
        add_action('wp_travel_after_main_content', 'priyanshtours_after_main_content');
    }
}
add_action('template_redirect', 'priyanshtours_disable_wp_travel_conflicting_template_hooks', 1);

/**
 * Custom before content hook
 */
function priyanshtours_before_main_content() {
    // We can add custom content here if needed
    echo '<!-- Custom before content -->';
}

/**
 * Custom after content hook
 */
function priyanshtours_after_main_content() {
    // We can add custom content here if needed
    echo '<!-- Custom after content -->';
}

/**
 * Force our custom template on the itinerary page
 */
function priyanshtours_force_custom_template($template) {
    global $wp_query;
    
    // If it's a tour archive or taxonomy
    if (is_post_type_archive('itineraries') || is_tax('itinerary_types') || is_tax('travel_locations')) {
        return get_template_directory() . '/archive-itineraries.php';
    }
    
    return $template;
}
add_filter('template_include', 'priyanshtours_force_custom_template', 9999);

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
 * Override WP Travel templates
 * Priority set to 20 to override WP Travel's own filter (usually at priority 10)
 */
function priyanshtours_override_auth_templates($template, $template_name) {
    // Define the templates we want to override
    $auth_templates = array(
        'account/form-login.php',
        'account/form-lostpassword.php',
        'account/form-reset-password.php',
        'account/content-dashboard.php',
    );
    
    if (in_array($template_name, $auth_templates)) {
        $theme_template = get_template_directory() . '/wp-travel-templates/' . $template_name;
        
        if (file_exists($theme_template)) {
            return $theme_template;
        }
    }
    
    return $template;
}
add_filter('wptravel_get_template', 'priyanshtours_override_auth_templates', 20, 2);

/**
 * Direct template override for login/register page
 * This is a more aggressive approach to ensure our template is used
 */
function priyanshtours_override_auth_template_include($template) {
    // Check if this is the WP Travel dashboard login page
    if (isset($_GET['page']) && $_GET['page'] === 'wp-travel-dashboard') {
        // Get the template file
        $our_template = get_template_directory() . '/wp-travel-templates/account/form-login.php';
        
        if (file_exists($our_template)) {
            return $our_template;
        }
    }
    
    return $template;
}
add_filter('template_include', 'priyanshtours_override_auth_template_include', 999);

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
 * Debug the dashboard URL
 */
function priyanshtours_debug_dashboard() {
    // Check if we're on a dashboard-related page
    if (strpos($_SERVER['REQUEST_URI'], 'wp-travel-dashboard') !== false) {
        // Add debugging info to the footer
        add_action('wp_footer', function() {
            echo '<!-- WP Travel Dashboard detected -->';
            
            // Get dashboard URL from WP Travel
            if (function_exists('wptravel_get_page_permalink')) {
                $dashboard_url = wptravel_get_page_permalink('wp-travel-dashboard');
                echo '<!-- Dashboard URL: ' . esc_html($dashboard_url) . ' -->';
            }
        });
    }
}
add_action('template_redirect', 'priyanshtours_debug_dashboard');

/**
 * Force our auth templates to load directly
 */
function priyanshtours_force_auth_templates() {
    // Check if this is the WP Travel dashboard page
    $dashboard_id = get_option('wp_travel_dashboard_page_id');
    
    if (is_page($dashboard_id) && !is_user_logged_in()) {
        // Include our login form
        include(get_template_directory() . '/wp-travel-templates/account/form-login.php');
        exit;
    }
}
add_action('template_redirect', 'priyanshtours_force_auth_templates', 99);

/**
 * Hook into WP Travel's specific template loading functions
 */
function priyanshtours_locate_template($located, $template_name, $args) {
    // Debug which template is being loaded
    error_log('WP Travel template being loaded: ' . $template_name);
    
    // Override specific templates
    if ($template_name == 'account/form-login.php') {
        $our_template = get_template_directory() . '/wp-travel-templates/account/form-login.php';
        if (file_exists($our_template)) {
            return $our_template;
        }
    } 
    else if ($template_name == 'account/form-lostpassword.php') {
        $our_template = get_template_directory() . '/wp-travel-templates/account/form-lostpassword.php';
        if (file_exists($our_template)) {
            return $our_template;
        }
    } 
    else if ($template_name == 'account/form-reset-password.php') {
        $our_template = get_template_directory() . '/wp-travel-templates/account/form-reset-password.php';
        if (file_exists($our_template)) {
            return $our_template;
        }
    }
    else if ($template_name == 'account/content-dashboard.php') {
        $our_template = get_template_directory() . '/wp-travel-templates/account/content-dashboard.php';
        if (file_exists($our_template)) {
            return $our_template;
        }
    }
    
    return $located;
}
add_filter('wptravel_locate_template', 'priyanshtours_locate_template', 20, 3);

/**
 * Override WP Travel's template part function
 */
add_action('init', function() {
    if (function_exists('WP_Travel')) {
        // Add our filter with very high priority to ensure it runs after WP Travel's filters
        add_filter('wptravel_get_template_part', function($template, $slug, $name) {
            if ($slug == 'account' && ($name == 'form-login' || $name == 'form-lostpassword' || $name == 'form-reset-password' || $name == 'content-dashboard')) {
                $our_template = get_template_directory() . "/wp-travel-templates/account/{$name}.php";
                if (file_exists($our_template)) {
                    return $our_template;
                }
            }
            return $template;
        }, 999, 3);
    }
});

/**
 * Replace the dashboard page content with our custom login form
 */
function priyanshtours_replace_dashboard_content($content) {
    // Check if this is the WP Travel dashboard page
    $dashboard_id = get_option('wp_travel_dashboard_page_id');
    
    if (is_page($dashboard_id) && !is_user_logged_in()) {
        ob_start();
        include(get_template_directory() . '/wp-travel-templates/account/form-login.php');
        $login_content = ob_get_clean();
        
        return $login_content;
    }
    
    return $content;
}
add_filter('the_content', 'priyanshtours_replace_dashboard_content', 999);

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
 * Debug which template is being used for single itineraries
 */
function priyanshtours_debug_template($template) {
    if (is_singular('itineraries')) {
        error_log('Template being used for itinerary: ' . $template);
    }
    return $template;
}
add_filter('template_include', 'priyanshtours_debug_template', 999);

/**
 * Force our custom template for single itineraries with highest priority
 */
function priyanshtours_force_custom_template_final($template) {
    if (is_singular('itineraries')) {
        $custom_template = get_template_directory() . '/single-itineraries.php';
        if (file_exists($custom_template)) {
            error_log('Forcing custom itinerary template with highest priority: ' . $custom_template);
            return $custom_template;
        }
    }
    return $template;
}
add_filter('template_include', 'priyanshtours_force_custom_template_final', 9999);

/**
 * Override the main content action for single itineraries
 */
function priyanshtours_override_itinerary_content() {
    // Remove the default content display
    remove_all_actions('wptravel_single_itinerary_main_content');
    
    // Add our custom template
    add_action('wptravel_single_itinerary_main_content', function() {
        include(get_template_directory() . '/wp-travel-templates/content-single-itineraries-custom.php');
    });
}
add_action('template_redirect', 'priyanshtours_override_itinerary_content');

/**
 * Remove default WP Travel single itinerary template hooks and add our custom ones
 * This ensures our custom template is used and the default UI doesn't show
 */
function priyanshtours_override_single_itinerary_hooks() {
    if (is_singular('itineraries')) {
        // Remove all default WP Travel content hooks for single itinerary
        remove_all_actions('wptravel_single_itinerary_main_content');
        remove_all_actions('wp_travel_before_single_itinerary');
        remove_all_actions('wp_travel_single_trip_after_title');
        remove_all_actions('wp_travel_single_trip_after_price');
        remove_all_actions('wp_travel_single_trip_meta_list');
        remove_all_actions('wp_travel_single_trip_after_tabs');
        remove_all_actions('wp_travel_single_trip_feat_img_after');
        
        // Remove trip tabs that we'll implement ourselves
        remove_all_actions('wp_travel_frontend_trip_facts');
        
        // Disable WP Travel itinerary main content
        add_filter('wp_travel_enable_trip_content', '__return_true');
    }
}
add_action('wp', 'priyanshtours_override_single_itinerary_hooks', 99);

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

/**
 * Debug template loading for troubleshooting
 * This can be removed once everything is working
 */
function priyanshtours_debug_template_loading($template) {
    if (isset($_GET['debug_template']) && current_user_can('manage_options')) {
        $request_uri = $_SERVER['REQUEST_URI'];
        $debug_info = array(
            'template' => $template,
            'request_uri' => $request_uri,
            'is_post_type_archive_itineraries' => is_post_type_archive('itineraries'),
            'is_tax_travel_locations' => is_tax('travel_locations'),
            'is_tax_itinerary_types' => is_tax('itinerary_types'),
            'post_type' => get_query_var('post_type'),
            'wp_query_vars' => $GLOBALS['wp_query']->query_vars,
        );
        
        error_log('Template Debug: ' . print_r($debug_info, true));
        
        // Show debug info on screen for admins
        add_action('wp_footer', function() use ($debug_info) {
            if (current_user_can('manage_options')) {
                echo '<div style="position:fixed; bottom:0; left:0; background:black; color:white; padding:10px; z-index:9999; max-width:400px; font-size:12px; overflow-y:auto; max-height:300px;">';
                echo '<h4>Template Debug Info:</h4>';
                echo '<pre>' . print_r($debug_info, true) . '</pre>';
                echo '</div>';
            }
        });
    }
    
    return $template;
}
add_filter('template_include', 'priyanshtours_debug_template_loading', 99999);


?> 