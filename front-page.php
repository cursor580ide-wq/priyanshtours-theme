<?php get_header(); ?>

<main id="main" class="site-main">

    <?php
    // Display Hero Section from Customizer
    $hero_image = get_theme_mod('hero_background_image', get_template_directory_uri() . '/assets/images/default-hero.jpg');
    $hero_title = get_theme_mod('hero_title', 'Authentic Journeys, Unforgettable Memories.');
    $hero_subtitle = get_theme_mod('hero_subtitle', 'Discover India with local experts.');
    $hero_cta_text = get_theme_mod('hero_cta_text', 'Find My Adventure');
    $hero_cta_link = get_theme_mod('hero_cta_link', home_url('/itinerary/')); // Updated to point to the tours page
    $show_hero_cta = get_theme_mod('show_hero_cta', true);
    $hero_slideshow_images = get_theme_mod('hero_slideshow_images');

    ?>

    <section class="hero-section">
        <!-- Swiper -->
        <div class="swiper hero-swiper">
            <div class="swiper-wrapper">
                <?php
                $has_slides = false;
                for ( $i = 1; $i <= 8; $i++ ) {
                    $image_url = get_theme_mod( "hero_slide_image_{$i}" );
                    if ( ! empty( $image_url ) ) {
                        $has_slides = true;
                        echo '<div class="swiper-slide" style="background-image: url(' . esc_url( $image_url ) . ');"></div>';
                    }
                }

                // Fallback if no images are set
                if ( ! $has_slides ) {
                    $fallback_image = get_theme_mod('hero_background_image', get_template_directory_uri() . '/assets/images/default-hero.jpg');
                    echo '<div class="swiper-slide" style="background-image: url(' . esc_url( $fallback_image ) . ');"></div>';
                }
                ?>
            </div>
            <!-- Add Pagination -->
            <div class="swiper-pagination"></div>

            <!-- Add Navigation -->
            <div class="swiper-button-prev"></div>
            <div class="swiper-button-next"></div>
        </div>

        <div class="hero-content">
            <h1 class="hero-title"><?php echo esc_html($hero_title); ?></h1>
            <p class="hero-subtitle"><?php echo esc_html($hero_subtitle); ?></p>
            <?php if ($show_hero_cta) : ?>
                <a href="<?php echo esc_url($hero_cta_link); ?>" class="btn btn-primary hero-cta-btn"><?php echo esc_html($hero_cta_text); ?></a>
            <?php endif; ?>
        </div>
    </section>

    <!-- Search Bar Component -->
    <section class="search-bar-section">
        <div class="container">
            <div class="search-bar-container">
                <h2 class="search-bar-title"><?php _e('Find Your Perfect Tour', 'priyanshtours'); ?></h2>
                
                <form class="home-search-form" action="<?php echo esc_url(home_url('/itinerary/')); ?>" method="get">
                    <div class="search-fields-row">
                        <div class="search-field-group destination-field">
                            <label for="destination">
                                <i class="bx bx-map"></i>
                                <?php _e('Destination', 'priyanshtours'); ?>
                            </label>
                            <?php 
                            // Get all destinations from wp-travel
                            $destinations = get_terms(array(
                                'taxonomy' => 'travel_locations',
                                'hide_empty' => true,
                            ));
                            ?>
                            <select name="destination" id="destination">
                                <option value=""><?php _e('Any Destination', 'priyanshtours'); ?></option>
                                <?php foreach ($destinations as $destination) : ?>
                                    <option value="<?php echo esc_attr($destination->slug); ?>"><?php echo esc_html($destination->name); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div class="search-field-group duration-field">
                            <label for="trip-duration">
                                <i class="bx bx-time"></i>
                                <?php _e('Duration', 'priyanshtours'); ?>
                            </label>
                            <select name="duration" id="trip-duration">
                                <option value=""><?php _e('Any Duration', 'priyanshtours'); ?></option>
                                <option value="1-3"><?php _e('1-3 Days', 'priyanshtours'); ?></option>
                                <option value="4-7"><?php _e('4-7 Days', 'priyanshtours'); ?></option>
                                <option value="8-14"><?php _e('1-2 Weeks', 'priyanshtours'); ?></option>
                                <option value="15-30"><?php _e('2-4 Weeks', 'priyanshtours'); ?></option>
                                <option value="30-plus"><?php _e('30+ Days', 'priyanshtours'); ?></option>
                            </select>
                        </div>
                        
                        <div class="search-field-group type-field">
                            <label for="trip-type">
                                <i class="bx bx-category-alt"></i>
                                <?php _e('Trip Type', 'priyanshtours'); ?>
                            </label>
                            <?php 
                            // Get all trip types from wp-travel
                            $trip_types = get_terms(array(
                                'taxonomy' => 'itinerary_types',
                                'hide_empty' => true,
                            ));
                            ?>
                            <select name="trip-type" id="trip-type">
                                <option value=""><?php _e('Any Trip Type', 'priyanshtours'); ?></option>
                                <?php foreach ($trip_types as $trip_type) : ?>
                                    <option value="<?php echo esc_attr($trip_type->slug); ?>"><?php echo esc_html($trip_type->name); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div class="search-field-group search-submit-field">
                            <button type="submit" class="btn btn-primary search-submit-btn">
                                <i class="bx bx-search"></i>
                                <?php _e('Search Tours', 'priyanshtours'); ?>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>

    <!-- Featured Tours Section -->
    <section class="featured-tours-section">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title"><?php _e('Popular Tours', 'priyanshtours'); ?></h2>
                <a href="<?php echo esc_url(home_url('/all-tours/')); ?>" class="view-all-link">
                    <?php _e('View All Tours', 'priyanshtours'); ?>
                    <i class="bx bx-right-arrow-alt"></i>
                </a>
            </div>
            
            <!-- Swiper for Featured Tours -->
            <div class="swiper featured-tours-swiper">
                <div class="swiper-wrapper">
                    <?php
                    // Check if WP Travel plugin is active and itineraries post type exists
                    $post_type = 'itineraries';
                    
                    if (!post_type_exists($post_type)) {
                        // Fallback to regular posts if itineraries don't exist
                        $post_type = 'post';
                    }
                    
                    // Query for featured tours (using wp-travel featured itineraries if available)
                    $args = array(
                        'post_type' => $post_type,
                        'posts_per_page' => 6,
                    );
                    
                    // Add meta query for featured tours only if it's the itineraries post type
                    if ($post_type === 'itineraries') {
                        $args['meta_query'] = array(
                            array(
                                'key' => 'wp_travel_featured',
                                'value' => 'yes',
                                'compare' => '=',
                            ),
                        );
                    }
                    
                    $featured_tours_query = new WP_Query($args);
                    
                    // If no featured tours, show latest tours
                    if (!$featured_tours_query->have_posts()) {
                        $args = array(
                            'post_type' => $post_type,
                            'posts_per_page' => 6,
                        );
                        $featured_tours_query = new WP_Query($args);
                    }
                    
                    if ($featured_tours_query->have_posts()) :
                        while ($featured_tours_query->have_posts()) : $featured_tours_query->the_post();
                            // Get wp_travel data
                            $trip_price = '';
                            $trip_location = '';
                            $trip_duration = '';
                            
                            // Safely get price if helper class exists
                            if (class_exists('WP_Travel_Helpers_Pricings')) {
                                $trip_price = WP_Travel_Helpers_Pricings::get_price(get_the_ID());
                            }
                            
                            // Safely get location if function exists
                            if (function_exists('wp_travel_get_trip_location')) {
                                $trip_location = wp_travel_get_trip_location(get_the_ID());
                            } else {
                                // Fallback to categories or custom handling
                                $locations = get_the_terms(get_the_ID(), 'travel_locations');
                                if (!empty($locations) && !is_wp_error($locations)) {
                                    $trip_location = $locations[0]->name;
                                }
                            }
                            
                            // Safely get duration if function exists
                            if (function_exists('wp_travel_get_trip_duration')) {
                                $trip_duration = wp_travel_get_trip_duration(get_the_ID());
                            } else {
                                // Fallback to a custom field or default
                                $days = get_post_meta(get_the_ID(), 'wp_travel_trip_duration', true);
                                $nights = get_post_meta(get_the_ID(), 'wp_travel_trip_duration_night', true);
                                if ($days) {
                                    $trip_duration = sprintf(_n('%s Day', '%s Days', $days, 'priyanshtours'), $days);
                                    if ($nights) {
                                        $trip_duration .= ' ' . sprintf(_n('%s Night', '%s Nights', $nights, 'priyanshtours'), $nights);
                                    }
                                }
                            }
                            
                            $thumbnail = get_the_post_thumbnail_url(get_the_ID(), 'large');
                            if (!$thumbnail) {
                                $thumbnail = get_template_directory_uri() . '/assets/images/placeholder.jpg';
                            }
                    ?>
                    <div class="swiper-slide">
                        <div class="tour-card">
                            <a href="<?php the_permalink(); ?>" class="tour-card-link">
                                <div class="tour-card-image">
                                    <img src="<?php echo esc_url($thumbnail); ?>" alt="<?php the_title_attribute(); ?>">
                                    <?php if ($trip_price) : ?>
                                        <div class="tour-card-price">
                                            <span>
                                                <?php 
                                                if (function_exists('wp_travel_get_formated_price_currency')) {
                                                    echo wp_travel_get_formated_price_currency($trip_price);
                                                } else {
                                                    echo '$' . number_format($trip_price, 2);
                                                }
                                                ?>
                                            </span>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <div class="tour-card-content">
                                    <h3 class="tour-card-title"><?php the_title(); ?></h3>
                                    <div class="tour-card-meta">
                                        <?php if ($trip_location) : ?>
                                            <div class="tour-card-location">
                                                <i class="bx bx-map"></i>
                                                <?php echo esc_html($trip_location); ?>
                                            </div>
                                        <?php endif; ?>
                                        <div class="tour-card-duration">
                                            <i class="bx bx-time-five"></i>
                                            <?php 
                                            if (!empty($trip_duration)) {
                                                echo esc_html($trip_duration);
                                            } else {
                                                echo '5-7 Days'; // Default duration as fallback
                                            }
                                            ?>
                                        </div>
                                    </div>
                                    <div class="tour-card-footer">
                                        <span class="btn-explore"><?php _e('Explore Tour', 'priyanshtours'); ?></span>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                    <?php
                        endwhile;
                        wp_reset_postdata();
                    endif;
                    ?>
                </div>
                
                <!-- Swiper pagination -->
                <div class="swiper-pagination featured-tours-pagination"></div>
                
                <!-- Swiper navigation buttons -->
                <div class="swiper-button-prev featured-tours-prev"></div>
                <div class="swiper-button-next featured-tours-next"></div>
            </div>
        </div>
    </section>

    <!-- Why Choose Us Section -->
    <section class="why-choose-us-section">
        <div class="container">
            <h2 class="section-title why-title"><?php _e('Why Travel With Us', 'priyanshtours'); ?></h2>
            
            <div class="usp-grid">
                <!-- USP Item 1: Local Expertise -->
                <div class="usp-item">
                    <div class="usp-icon-wrapper">
                        <i class="bx bx-map-pin"></i>
                    </div>
                    <h3 class="usp-title"><?php _e('Local Expertise', 'priyanshtours'); ?></h3>
                    <p class="usp-description">
                        <?php _e('Our guides are born and raised in India with insider knowledge that transforms your journey.', 'priyanshtours'); ?>
                    </p>
                </div>
                
                <!-- USP Item 2: Authentic Experiences -->
                <div class="usp-item">
                    <div class="usp-icon-wrapper">
                        <i class="bx bx-star"></i>
                    </div>
                    <h3 class="usp-title"><?php _e('Authentic Experiences', 'priyanshtours'); ?></h3>
                    <p class="usp-description">
                        <?php _e('Go beyond tourist spots and discover the real culture through immersive activities.', 'priyanshtours'); ?>
                    </p>
                </div>
                
                <!-- USP Item 3: Personalized Service -->
                <div class="usp-item">
                    <div class="usp-icon-wrapper">
                        <i class="bx bx-user-voice"></i>
                    </div>
                    <h3 class="usp-title"><?php _e('Personalized Service', 'priyanshtours'); ?></h3>
                    <p class="usp-description">
                        <?php _e('Every tour is tailored to your interests with flexible itineraries and personal attention.', 'priyanshtours'); ?>
                    </p>
                </div>
                
                <!-- USP Item 4: Responsible Travel -->
                <div class="usp-item">
                    <div class="usp-icon-wrapper">
                        <i class="bx bx-leaf"></i>
                    </div>
                    <h3 class="usp-title"><?php _e('Responsible Travel', 'priyanshtours'); ?></h3>
                    <p class="usp-description">
                        <?php _e('We prioritize sustainable practices and support local communities in all our operations.', 'priyanshtours'); ?>
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section class="testimonials-section">
        <div class="container">
            <h2 class="section-title"><?php _e('What Our Travelers Say', 'priyanshtours'); ?></h2>
            
            <!-- Swiper for Testimonials -->
            <div class="swiper testimonials-swiper">
                <div class="swiper-wrapper">
                    <!-- Testimonial 1 -->
                    <div class="swiper-slide">
                        <div class="testimonial-card" style="background-image: url('<?php echo get_template_directory_uri(); ?>/assets/images/testimonial-bg-1.jpg'); background-size: cover; background-position: center;">
                            <div class="testimonial-rating">
                                <i class="bx bxs-star"></i>
                                <i class="bx bxs-star"></i>
                                <i class="bx bxs-star"></i>
                                <i class="bx bxs-star"></i>
                                <i class="bx bxs-star"></i>
                            </div>
                            <div class="testimonial-content">
                                <p>"Our tour guide was incredibly knowledgeable and made sure we experienced the authentic culture of each location. The hidden gems we discovered wouldn't have been possible without Priyansh Tours!"</p>
                            </div>
                            <div class="testimonial-author">
                                <div class="testimonial-author-image">
                                    <img src="<?php echo get_template_directory_uri(); ?>/assets/images/testimonials/testimonial-1.jpg" alt="Sarah M.">
                                </div>
                                <div class="testimonial-author-info">
                                    <h4>Sarah M.</h4>
                                    <p>Golden Triangle Tour</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Testimonial 2 -->
                    <div class="swiper-slide">
                        <div class="testimonial-card" style="background-image: url('<?php echo get_template_directory_uri(); ?>/assets/images/testimonial-bg-2.jpg'); background-size: cover; background-position: center;">
                            <div class="testimonial-rating">
                                <i class="bx bxs-star"></i>
                                <i class="bx bxs-star"></i>
                                <i class="bx bxs-star"></i>
                                <i class="bx bxs-star"></i>
                                <i class="bx bxs-star"></i>
                            </div>
                            <div class="testimonial-content">
                                <p>"The level of personalization in our itinerary was outstanding. They took the time to understand exactly what we wanted and delivered beyond our expectations. Will definitely book with them again!"</p>
                            </div>
                            <div class="testimonial-author">
                                <div class="testimonial-author-image">
                                    <img src="<?php echo get_template_directory_uri(); ?>/assets/images/testimonials/testimonial-2.jpg" alt="James & Emily K.">
                                </div>
                                <div class="testimonial-author-info">
                                    <h4>James & Emily K.</h4>
                                    <p>Kerala Backwaters Retreat</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Testimonial 3 -->
                    <div class="swiper-slide">
                        <div class="testimonial-card" style="background-image: url('<?php echo get_template_directory_uri(); ?>/assets/images/testimonial-bg-3.jpg'); background-size: cover; background-position: center;">
                            <div class="testimonial-rating">
                                <i class="bx bxs-star"></i>
                                <i class="bx bxs-star"></i>
                                <i class="bx bxs-star"></i>
                                <i class="bx bxs-star"></i>
                                <i class="bx bxs-star-half"></i>
                            </div>
                            <div class="testimonial-content">
                                <p>"What impressed me most was how smoothly everything ran despite the complex logistics. Every transfer was on time, accommodations were perfect, and the guides were passionate about sharing their culture."</p>
                            </div>
                            <div class="testimonial-author">
                                <div class="testimonial-author-image">
                                    <img src="<?php echo get_template_directory_uri(); ?>/assets/images/testimonials/testimonial-3.jpg" alt="David L.">
                                </div>
                                <div class="testimonial-author-info">
                                    <h4>David L.</h4>
                                    <p>Rajasthan Heritage Tour</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Swiper pagination -->
                <div class="swiper-pagination testimonials-pagination"></div>
            </div>
        </div>
    </section>

    <!-- Call-to-Action Section -->
    <section class="cta-section">
        <div class="container">
            <div class="cta-container">
                <div class="cta-content">
                    <h2 class="cta-title"><?php _e('Ready for Your Indian Adventure?', 'priyanshtours'); ?></h2>
                    <p class="cta-text"><?php _e('Start planning your personalized journey with our expert team today.', 'priyanshtours'); ?></p>
                    <div class="cta-buttons">
                        <a href="<?php echo esc_url(home_url('/itinerary/')); ?>" class="btn btn-primary cta-btn-primary">
                            <?php _e('Explore Tours', 'priyanshtours'); ?>
                        </a>
                        <a href="<?php echo esc_url(home_url('/contact/')); ?>" class="btn cta-btn-secondary">
                            <?php _e('Contact Us', 'priyanshtours'); ?>
                        </a>
                    </div>
                </div>
                <div class="cta-image">
                    <!-- This will be styled with a background image in CSS -->
                </div>
            </div>
        </div>
    </section>

</main>

<?php get_footer(); ?> 