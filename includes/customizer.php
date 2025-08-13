<?php
/**
 * Theme Customizer
 *
 * @package Priyansh Tours
 */

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
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
?>
