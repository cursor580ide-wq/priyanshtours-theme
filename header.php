<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
    <div id="page" class="site">

        <header id="masthead" class="site-header">
            <div class="site-branding">
                <?php
                if ( function_exists( 'the_custom_logo' ) && has_custom_logo() ) {
                    the_custom_logo();
                } else {
                    ?>
                    <a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
                        <img src="<?php echo get_template_directory_uri(); ?>/assets/images/logo.png" alt="<?php bloginfo( 'name' ); ?>">
                    </a>
                    <?php
                }
                ?>
            </div>

            <div class="desktop-search">
                <?php get_search_form(); ?>
            </div>

            <div class="header-icons">
                <?php
                if ( is_user_logged_in() ) {
                    // User is logged in - show dropdown menu
                    ?>
                    <div class="user-account-dropdown">
                        <button class="user-dropdown-toggle">
                            <i class="bx bxs-user-circle"></i>
                            <span class="screen-reader-text">User Menu</span>
                        </button>
                        <div class="dropdown-menu">
                            <?php 
                            // Get the current user
                            $current_user = wp_get_current_user();
                            ?>
                            <div class="dropdown-header">
                                <span>Hello, <?php echo esc_html($current_user->display_name); ?></span>
                            </div>
                            
                            <?php if (function_exists('wp_travel_get_dashboard_url')) : ?>
                                <a href="<?php echo esc_url(wp_travel_get_dashboard_url()); ?>" class="dropdown-item">
                                    <i class="bx bxs-dashboard"></i> My Dashboard
                                </a>
                                <a href="<?php echo esc_url(wp_travel_get_dashboard_url('bookings')); ?>" class="dropdown-item">
                                    <i class="bx bxs-calendar-check"></i> My Bookings
                                </a>
                                <a href="<?php echo esc_url(wp_travel_get_dashboard_url('account')); ?>" class="dropdown-item">
                                    <i class="bx bxs-user-detail"></i> My Account
                                </a>
                            <?php endif; ?>
                            
                            <div class="dropdown-divider"></div>
                            
                            <a href="<?php echo esc_url(wp_logout_url(home_url())); ?>" class="dropdown-item">
                                <i class="bx bx-log-out"></i> Logout
                            </a>
                        </div>
                    </div>
                    <?php
                } else {
                    // User is logged out - link to login page
                    ?>
                    <a href="<?php echo esc_url(wp_login_url(get_permalink())); ?>" class="user-account-link">
                        <i class="bx bx-user-circle"></i>
                        <span class="login-text">Login</span>
                    </a>
                    <?php
                }
                ?>
            </div>
        </header>

        <div class="desktop-nav">
            <?php
            wp_nav_menu( array(
                'theme_location' => 'primary_desktop',
                'container'      => 'nav',
                'container_class'=> 'main-navigation',
                'fallback_cb'    => false, // Do not show anything if menu is not set
            ) );
            ?>
            
            <?php if (strpos($_SERVER['REQUEST_URI'], '/travel-stories/') !== false) : ?>
            <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Find Travel Stories link in navigation
                const menuItems = document.querySelectorAll('.main-navigation li a');
                menuItems.forEach(function(item) {
                    if (item.textContent.includes('Travel Stories') || item.href.includes('/travel-stories')) {
                        // Add active classes to parent li
                        item.parentElement.classList.add('current-menu-item');
                        item.parentElement.classList.add('current_page_item');
                        item.parentElement.classList.add('active');
                    }
                });
            });
            </script>
            <?php endif; ?>
            
            <?php if (is_user_logged_in()) : ?>
            <script>
            document.addEventListener('DOMContentLoaded', function() {
                const dropdownToggle = document.querySelector('.user-dropdown-toggle');
                const dropdownMenu = document.querySelector('.dropdown-menu');
                
                // Toggle dropdown on click
                dropdownToggle.addEventListener('click', function(e) {
                    e.preventDefault();
                    dropdownMenu.classList.toggle('show');
                });
                
                // Close dropdown when clicking outside
                document.addEventListener('click', function(e) {
                    if (!dropdownToggle.contains(e.target) && !dropdownMenu.contains(e.target)) {
                        dropdownMenu.classList.remove('show');
                    }
                });
                
                // Add show class for click toggle
                document.head.insertAdjacentHTML('beforeend', 
                    '<style>.dropdown-menu.show { opacity: 1; visibility: visible; transform: translateY(0); }</style>'
                );
            });
            </script>
            <?php endif; ?>
        </div>

        <div id="content" class="site-content">

 