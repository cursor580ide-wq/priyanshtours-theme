        </div><!-- #content -->

        <footer id="colophon" class="site-footer">
            <div class="desktop-footer">
                <div class="container footer-grid">
                    <div class="footer-column footer-about">
                        <?php if ( function_exists( 'the_custom_logo' ) && has_custom_logo() ) { the_custom_logo(); } ?>
                        <p><?php echo esc_html(get_theme_mod('footer_description', 'Experience India\'s true essence with authentic journeys curated by local experts.')); ?></p>
                    </div>
                    <div class="footer-column">
                        <h4 class="footer-widget-title">Quick Links</h4>
                        <?php
                        wp_nav_menu( array(
                            'theme_location' => 'footer',
                            'menu_class'     => 'footer-menu',
                            'fallback_cb'    => false,
                        ) );
                        ?>
                    </div>
                    <div class="footer-column">
                        <h4 class="footer-widget-title">Contact Us</h4>
                        <ul class="footer-contact-info">
                            <li><i class='bx bx-map'></i> <?php echo esc_html(get_theme_mod('footer_address', 'Karawal Nagar, Delhi, India')); ?></li>
                            <li><i class='bx bx-phone'></i> <?php echo esc_html(get_theme_mod('footer_phone', '+91 987 654 3210')); ?></li>
                            <li><i class='bx bx-envelope'></i> <?php echo esc_html(get_theme_mod('footer_email', 'contact@priyanshtours.com')); ?></li>
                        </ul>
                    </div>
                     <div class="footer-column">
                        <h4 class="footer-widget-title">Follow Us</h4>
                        <div class="footer-social-links">
                            <?php 
                            $facebook_url = get_theme_mod('footer_facebook_url', '');
                            $instagram_url = get_theme_mod('footer_instagram_url', '');
                            $tripadvisor_url = get_theme_mod('footer_tripadvisor_url', '');
                            $youtube_url = get_theme_mod('footer_youtube_url', '');
                            $twitter_url = get_theme_mod('footer_twitter_url', '');
                            
                            if ($facebook_url) : ?>
                                <a href="<?php echo esc_url($facebook_url); ?>" target="_blank" rel="noopener"><i class='bx bxl-facebook-square'></i></a>
                            <?php endif;
                            
                            if ($instagram_url) : ?>
                                <a href="<?php echo esc_url($instagram_url); ?>" target="_blank" rel="noopener"><i class='bx bxl-instagram-alt'></i></a>
                            <?php endif;
                            
                            if ($tripadvisor_url) : ?>
                                <a href="<?php echo esc_url($tripadvisor_url); ?>" target="_blank" rel="noopener"><i class='bx bxl-trip-advisor'></i></a>
                            <?php endif;
                            
                            if ($youtube_url) : ?>
                                <a href="<?php echo esc_url($youtube_url); ?>" target="_blank" rel="noopener"><i class='bx bxl-youtube'></i></a>
                            <?php endif;
                            
                            if ($twitter_url) : ?>
                                <a href="<?php echo esc_url($twitter_url); ?>" target="_blank" rel="noopener"><i class='bx bxl-twitter'></i></a>
                            <?php endif;
                            
                            // Show placeholder links if no social URLs are set
                            if (!$facebook_url && !$instagram_url && !$tripadvisor_url && !$youtube_url && !$twitter_url) : ?>
                                <a href="#"><i class='bx bxl-facebook-square'></i></a>
                                <a href="#"><i class='bx bxl-instagram-alt'></i></a>
                                <a href="#"><i class='bx bxl-trip-advisor'></i></a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <div class="site-info">
                    <div class="container">
                        &copy; <?php echo date( 'Y' ); ?> <?php bloginfo( 'name' ); ?>. All Rights Reserved.
                    </div>
                </div>
            </div>

            <nav class="bottom-nav">
                <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="nav-item <?php if (is_front_page()) echo 'active'; ?>">
                    <i class='bx bxs-home'></i>
                    <span>Home</span>
                </a>
                <a href="<?php echo esc_url( home_url('/itinerary/') ); ?>" class="nav-item <?php 
                    if (is_page('itinerary') || 
                        is_post_type_archive('itineraries') || 
                        is_singular('itineraries') || 
                        is_tax('itinerary_types') || 
                        is_tax('travel_locations') ||
                        strpos($_SERVER['REQUEST_URI'], '/itinerary/') !== false) 
                        echo 'active'; 
                ?>">
                    <i class='bx bx-compass'></i>
                    <span>Tours</span>
                </a>
                <a href="<?php echo esc_url( home_url('/search/') ); ?>" class="nav-item <?php if (is_search()) echo 'active'; ?>">
                    <i class='bx bx-search'></i>
                    <span>Search</span>
                </a>
                <a href="<?php echo esc_url( home_url('/travel-stories/') ); ?>" class="nav-item <?php if (strpos($_SERVER['REQUEST_URI'], '/travel-stories/') !== false) echo 'active'; ?>">
                    <i class='bx bx-book-open'></i>
                    <span>Stories</span>
                </a>
                <a href="<?php echo esc_url( home_url('/contact-us/') ); ?>" class="nav-item <?php if (is_page('contact-us') || is_page_template('template-contact.php')) echo 'active'; ?>">
                    <i class='bx bx-message-square-dots'></i>
                    <span>Contact</span>
                </a>
            </nav>
        </footer>

    </div><!-- #page -->

    <?php wp_footer(); ?>
</body>
</html> 