<?php
/**
 * Custom Dashboard Content Template
 * 
 * @package Priyansh_Tours_Theme
 */

// If user is not logged in, show our custom login form
if (!is_user_logged_in()) {
    include(get_template_directory() . '/wp-travel-templates/account/form-login.php');
    exit;
}

// Continue with the standard dashboard for logged in users
$user_id = get_current_user_id();
$customer_data = get_userdata($user_id);

// Ensure required CSS is loaded
wp_enqueue_style('boxicons', 'https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css');
wp_enqueue_style('priyanshtours-shadcn-components', get_template_directory_uri() . '/assets/css/shadcn-components.css');

// User information
$display_name = '';
if (isset($customer_data->data->display_name)) {
    $display_name = $customer_data->data->display_name;
}

$first_name = get_user_meta($user_id, 'first_name', true);
$last_name = get_user_meta($user_id, 'last_name', true);

$full_name = '';
if (!empty($first_name) || !empty($last_name)) {
    $full_name = $first_name . ' ' . $last_name;
}

$name = !empty($full_name) ? $full_name : $display_name;
$email = $customer_data->data->user_email;
$phone = get_user_meta($user_id, 'wp_travel_phone', true);
$country = get_user_meta($user_id, 'billing_country', true);

// Get booking counts by status
$total_bookings = 0;
$confirmed_bookings = 0;
$pending_bookings = 0;

$booking_args = array(
    'post_type' => 'itinerary-booking',
    'posts_per_page' => -1,
    'post_status' => 'any',
    'meta_query' => array(
        array(
            'key' => 'wp_travel_user_id',
            'value' => $user_id
        )
    )
);

$bookings_query = new WP_Query($booking_args);
if ($bookings_query->have_posts()) {
    $total_bookings = $bookings_query->found_posts;
    
    while ($bookings_query->have_posts()) {
        $bookings_query->the_post();
        $status = get_post_status();
        
        if ($status === 'publish') {
            $confirmed_bookings++;
        } elseif ($status === 'pending') {
            $pending_bookings++;
        }
    }
    wp_reset_postdata();
}

// Get recent bookings
$recent_bookings = array();
$recent_bookings_args = array(
    'post_type' => 'itinerary-booking',
    'posts_per_page' => 5,
    'post_status' => 'any',
    'meta_query' => array(
        array(
            'key' => 'wp_travel_user_id',
            'value' => $user_id
        )
    )
);

$recent_bookings_query = new WP_Query($recent_bookings_args);
if ($recent_bookings_query->have_posts()) {
    while ($recent_bookings_query->have_posts()) {
        $recent_bookings_query->the_post();
        $booking_id = get_the_ID();
        $trip_id = get_post_meta($booking_id, 'wp_travel_post_id', true);
        $booking_status = get_post_status();
        $booking_date = date_i18n(get_option('date_format'), strtotime(get_the_date()));
        $payment_status = get_post_meta($booking_id, 'wp_travel_payment_status', true);
        
        $trip_title = '';
        $trip_image = '';
        if ($trip_id) {
            $trip_title = get_the_title($trip_id);
            if (has_post_thumbnail($trip_id)) {
                $trip_image = get_the_post_thumbnail_url($trip_id, 'thumbnail');
            }
        }
        
        $recent_bookings[] = array(
            'id' => $booking_id,
            'trip_id' => $trip_id,
            'trip_title' => $trip_title,
            'trip_image' => $trip_image,
            'status' => $booking_status,
            'payment_status' => $payment_status,
            'date' => $booking_date
        );
    }
    wp_reset_postdata();
}

// Get active tab
$active_tab = isset($_GET['tab']) ? sanitize_text_field($_GET['tab']) : 'dashboard';
?>

<div class="dashboard-container">
    <div class="dashboard-header">
        <h1 class="dashboard-title"><?php esc_html_e('Dashboard', 'wp-travel'); ?></h1>
    </div>
    
    <!-- User Welcome Card -->
    <div class="user-welcome-card shadcn-card">
        <div class="user-welcome-content">
            <div class="shadcn-avatar shadcn-avatar-lg">
                <?php 
                // Get user avatar or show fallback
                $avatar_url = get_avatar_url($user_id, array('size' => 96));
                if ($avatar_url) {
                    echo '<img class="shadcn-avatar-image" src="' . esc_url($avatar_url) . '" alt="' . esc_attr($name) . '">';
                } else {
                    $initials = !empty($name) ? substr($name, 0, 1) : '?';
                    echo '<div class="shadcn-avatar-fallback">' . esc_html($initials) . '</div>';
                }
                ?>
            </div>
            <div class="user-welcome-info">
                <h2><?php echo sprintf(__('Welcome back, %s!', 'wp-travel'), esc_html($name)); ?></h2>
                <div class="user-contact-info">
                    <span><i class='bx bx-envelope'></i> <?php echo esc_html($email); ?></span>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Stats Cards Row -->
    <div class="stats-grid">
        <div class="stat-card shadcn-card total-bookings">
            <div class="stat-card-content">
                <div class="stat-card-header">
                    <span class="stat-card-title"><?php esc_html_e('TOTAL BOOKINGS', 'wp-travel'); ?></span>
                    <div class="stat-card-icon"><i class='bx bx-calendar-check'></i></div>
                </div>
                <div class="stat-card-value"><?php echo esc_html($total_bookings); ?></div>
                <div class="stat-card-description"><?php esc_html_e('All time bookings', 'wp-travel'); ?></div>
            </div>
        </div>
        
        <div class="stat-card shadcn-card confirmed-bookings">
            <div class="stat-card-content">
                <div class="stat-card-header">
                    <span class="stat-card-title"><?php esc_html_e('CONFIRMED', 'wp-travel'); ?></span>
                    <div class="stat-card-icon"><i class='bx bx-check-circle'></i></div>
                </div>
                <div class="stat-card-value"><?php echo esc_html($confirmed_bookings); ?></div>
                <div class="stat-card-description"><?php esc_html_e('Confirmed bookings', 'wp-travel'); ?></div>
            </div>
        </div>
        
        <div class="stat-card shadcn-card pending-bookings">
            <div class="stat-card-content">
                <div class="stat-card-header">
                    <span class="stat-card-title"><?php esc_html_e('PENDING', 'wp-travel'); ?></span>
                    <div class="stat-card-icon"><i class='bx bx-time'></i></div>
                </div>
                <div class="stat-card-value"><?php echo esc_html($pending_bookings); ?></div>
                <div class="stat-card-description"><?php esc_html_e('Pending confirmations', 'wp-travel'); ?></div>
            </div>
        </div>
    </div>
    
    <!-- Main Dashboard Content -->
    <div class="dashboard-content-grid">
        <!-- Sidebar Navigation -->
        <div class="dashboard-nav shadcn-card">
            <div class="dashboard-nav-header">
                <h3><?php esc_html_e('Navigation', 'wp-travel'); ?></h3>
            </div>
            <div class="dashboard-nav-links">
                <a href="<?php echo esc_url(add_query_arg('tab', 'dashboard', wptravel_get_page_permalink('wp-travel-dashboard'))); ?>" 
                   class="dashboard-nav-link <?php echo $active_tab === 'dashboard' ? 'active' : ''; ?>">
                    <i class='bx bx-home-alt'></i> <?php esc_html_e('Overview', 'wp-travel'); ?>
                </a>
                
                <a href="<?php echo esc_url(add_query_arg('tab', 'bookings', wptravel_get_page_permalink('wp-travel-dashboard'))); ?>" 
                   class="dashboard-nav-link <?php echo $active_tab === 'bookings' ? 'active' : ''; ?>">
                    <i class='bx bx-list-check'></i> <?php esc_html_e('My Bookings', 'wp-travel'); ?>
                </a>
                
                <a href="<?php echo esc_url(add_query_arg('tab', 'inquiries', wptravel_get_page_permalink('wp-travel-dashboard'))); ?>" 
                   class="dashboard-nav-link <?php echo $active_tab === 'inquiries' ? 'active' : ''; ?>">
                    <i class='bx bx-chat'></i> <?php esc_html_e('My Inquiries', 'wp-travel'); ?>
                </a>
                
                <a href="<?php echo esc_url(add_query_arg('tab', 'account', wptravel_get_page_permalink('wp-travel-dashboard'))); ?>" 
                   class="dashboard-nav-link <?php echo $active_tab === 'account' ? 'active' : ''; ?>">
                    <i class='bx bx-user-circle'></i> <?php esc_html_e('Account Settings', 'wp-travel'); ?>
                </a>
                
                <a href="<?php echo esc_url(wp_logout_url(get_home_url())); ?>" class="dashboard-nav-link">
                    <i class='bx bx-log-out'></i> <?php esc_html_e('Logout', 'wp-travel'); ?>
                </a>
            </div>
        </div>
        
        <!-- Main Content Area -->
        <div class="dashboard-main">
            <?php if ($active_tab === 'dashboard' || $active_tab === '') : ?>
                <!-- Recent Bookings Section -->
                <div class="shadcn-card recent-bookings">
                    <div class="shadcn-card-header">
                        <h3 class="shadcn-card-title"><?php esc_html_e('Recent Bookings', 'wp-travel'); ?></h3>
                    </div>
                    
                    <div class="shadcn-card-content">
                        <?php if (!empty($recent_bookings)) : ?>
                            <div class="bookings-list">
                                <?php foreach ($recent_bookings as $booking) : ?>
                                    <div class="booking-item shadcn-card">
                                        <div class="booking-item-left">
                                            <?php if (!empty($booking['trip_image'])) : ?>
                                                <div class="booking-image">
                                                    <img src="<?php echo esc_url($booking['trip_image']); ?>" alt="<?php echo esc_attr($booking['trip_title']); ?>">
                                                </div>
                                            <?php else: ?>
                                                <div class="booking-image booking-image-placeholder">
                                                    <i class='bx bx-map-alt'></i>
                                                </div>
                                            <?php endif; ?>
                                            
                                            <div class="booking-details">
                                                <h4 class="booking-title"><?php echo esc_html($booking['trip_title']); ?></h4>
                                                <div class="booking-meta">
                                                    <span class="booking-date"><i class='bx bx-calendar'></i> <?php echo esc_html($booking['date']); ?></span>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="booking-item-right">
                                            <?php
                                            $status_class = 'status-badge-warning';
                                            $status_icon = '<i class="bx bx-time"></i>';
                                            if ($booking['status'] === 'publish') {
                                                $status_class = 'status-badge-success';
                                                $status_icon = '<i class="bx bx-check-circle"></i>';
                                            } elseif ($booking['status'] === 'cancelled') {
                                                $status_class = 'status-badge-danger';
                                                $status_icon = '<i class="bx bx-x-circle"></i>';
                                            }
                                            ?>
                                            <span class="status-badge <?php echo esc_attr($status_class); ?>">
                                                <?php echo $status_icon; ?> <?php echo esc_html(ucfirst($booking['status'])); ?>
                                            </span>
                                            
                                            <a href="<?php echo esc_url(add_query_arg('view_booking', $booking['id'], wptravel_get_page_permalink('wp-travel-dashboard'))); ?>" 
                                               class="shadcn-button shadcn-button-sm shadcn-button-outline">
                                                <i class='bx bx-show'></i> <?php esc_html_e('View', 'wp-travel'); ?>
                                            </a>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            
                            <div class="shadcn-card-footer">
                                <a href="<?php echo esc_url(add_query_arg('tab', 'bookings', wptravel_get_page_permalink('wp-travel-dashboard'))); ?>" 
                                   class="shadcn-button shadcn-button-default">
                                    <i class='bx bx-list-ul'></i> <?php esc_html_e('View All Bookings', 'wp-travel'); ?>
                                </a>
                            </div>
                        <?php else : ?>
                            <div class="empty-state">
                                <div class="empty-state-icon">
                                    <i class='bx bx-calendar'></i>
                                </div>
                                <h4 class="empty-state-title"><?php esc_html_e('No Bookings Yet', 'wp-travel'); ?></h4>
                                <p class="empty-state-description"><?php esc_html_e('You have not made any bookings yet. Start exploring tours and create memories!', 'wp-travel'); ?></p>
                                <a href="<?php echo esc_url(get_post_type_archive_link('itineraries')); ?>" class="shadcn-button shadcn-button-default">
                                    <i class='bx bx-search'></i> <?php esc_html_e('Browse Tours', 'wp-travel'); ?>
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                
            <?php elseif ($active_tab === 'bookings') : ?>
                <!-- All Bookings Tab Content -->
                <div class="shadcn-card">
                    <div class="shadcn-card-header">
                        <h3 class="shadcn-card-title"><?php esc_html_e('All Bookings', 'wp-travel'); ?></h3>
                    </div>
                    
                    <div class="shadcn-card-content">
                        <?php if (!empty($recent_bookings)) : ?>
                            <div class="bookings-list">
                                <?php foreach ($recent_bookings as $booking) : ?>
                                    <div class="booking-item shadcn-card">
                                        <div class="booking-item-left">
                                            <?php if (!empty($booking['trip_image'])) : ?>
                                                <div class="booking-image">
                                                    <img src="<?php echo esc_url($booking['trip_image']); ?>" alt="<?php echo esc_attr($booking['trip_title']); ?>">
                                                </div>
                                            <?php else: ?>
                                                <div class="booking-image booking-image-placeholder">
                                                    <i class='bx bx-map-alt'></i>
                                                </div>
                                            <?php endif; ?>
                                            
                                            <div class="booking-details">
                                                <h4 class="booking-title"><?php echo esc_html($booking['trip_title']); ?></h4>
                                                <div class="booking-meta">
                                                    <span class="booking-date"><i class='bx bx-calendar'></i> <?php echo esc_html($booking['date']); ?></span>
                                                    
                                                    <?php
                                                    $payment_class = 'payment-badge-warning';
                                                    $payment_icon = '<i class="bx bx-time"></i>';
                                                    if ($booking['payment_status'] === 'paid') {
                                                        $payment_class = 'payment-badge-success';
                                                        $payment_icon = '<i class="bx bx-credit-card"></i>';
                                                    } elseif ($booking['payment_status'] === 'cancelled') {
                                                        $payment_class = 'payment-badge-danger';
                                                        $payment_icon = '<i class="bx bx-x"></i>';
                                                    }
                                                    ?>
                                                    <span class="payment-badge <?php echo esc_attr($payment_class); ?>">
                                                        <?php echo $payment_icon; ?> <?php echo esc_html(ucfirst($booking['payment_status'])); ?>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="booking-item-right">
                                            <?php
                                            $status_class = 'status-badge-warning';
                                            $status_icon = '<i class="bx bx-time"></i>';
                                            if ($booking['status'] === 'publish') {
                                                $status_class = 'status-badge-success';
                                                $status_icon = '<i class="bx bx-check-circle"></i>';
                                            } elseif ($booking['status'] === 'cancelled') {
                                                $status_class = 'status-badge-danger';
                                                $status_icon = '<i class="bx bx-x-circle"></i>';
                                            }
                                            ?>
                                            <span class="status-badge <?php echo esc_attr($status_class); ?>">
                                                <?php echo $status_icon; ?> <?php echo esc_html(ucfirst($booking['status'])); ?>
                                            </span>
                                            
                                            <a href="<?php echo esc_url(add_query_arg('view_booking', $booking['id'], wptravel_get_page_permalink('wp-travel-dashboard'))); ?>" 
                                               class="shadcn-button shadcn-button-sm shadcn-button-outline">
                                                <i class='bx bx-show'></i> <?php esc_html_e('View Details', 'wp-travel'); ?>
                                            </a>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php else : ?>
                            <div class="empty-state">
                                <div class="empty-state-icon">
                                    <i class='bx bx-calendar'></i>
                                </div>
                                <h4 class="empty-state-title"><?php esc_html_e('No Bookings Yet', 'wp-travel'); ?></h4>
                                <p class="empty-state-description"><?php esc_html_e('You have not made any bookings yet. Start exploring tours and create memories!', 'wp-travel'); ?></p>
                                <a href="<?php echo esc_url(get_post_type_archive_link('itineraries')); ?>" class="shadcn-button shadcn-button-default">
                                    <i class='bx bx-search'></i> <?php esc_html_e('Browse Tours', 'wp-travel'); ?>
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                
            <?php elseif ($active_tab === 'inquiries') : ?>
                <!-- Inquiries Tab Content -->
                <div class="shadcn-card">
                    <div class="shadcn-card-header">
                        <h3 class="shadcn-card-title"><?php esc_html_e('My Inquiries', 'wp-travel'); ?></h3>
                    </div>
                    
                    <div class="shadcn-card-content">
                        <div class="empty-state">
                            <div class="empty-state-icon">
                                <i class='bx bx-chat'></i>
                            </div>
                            <h4 class="empty-state-title"><?php esc_html_e('No Inquiries Yet', 'wp-travel'); ?></h4>
                            <p class="empty-state-description"><?php esc_html_e('When you make inquiries about tours, they will appear here for easy tracking.', 'wp-travel'); ?></p>
                            <a href="<?php echo esc_url(get_post_type_archive_link('itineraries')); ?>" class="shadcn-button shadcn-button-default">
                                <i class='bx bx-search'></i> <?php esc_html_e('Browse Tours', 'wp-travel'); ?>
                            </a>
                        </div>
                    </div>
                </div>
                
            <?php elseif ($active_tab === 'account') : ?>
                <!-- Account Settings Tab Content -->
                <div class="shadcn-card">
                    <div class="shadcn-card-header">
                        <h3 class="shadcn-card-title"><?php esc_html_e('Account Settings', 'wp-travel'); ?></h3>
                    </div>
                    
                    <div class="shadcn-card-content">
                        <?php
                        // Include the WP Travel account form or create a custom one
                        if (function_exists('wptravel_account_form')) {
                            wptravel_account_form();
                        } else {
                            // Fallback if WP Travel function doesn't exist
                            ?>
                            <form class="account-settings-form" method="post">
                                <div class="form-row">
                                    <div class="form-group">
                                        <label class="form-label" for="first_name"><?php esc_html_e('First Name', 'wp-travel'); ?></label>
                                        <input id="first_name" class="form-input" name="first_name" type="text" value="<?php echo esc_attr($first_name); ?>" />
                                    </div>
                                    
                                    <div class="form-group">
                                        <label class="form-label" for="last_name"><?php esc_html_e('Last Name', 'wp-travel'); ?></label>
                                        <input id="last_name" class="form-input" name="last_name" type="text" value="<?php echo esc_attr($last_name); ?>" />
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label class="form-label" for="email"><?php esc_html_e('Email Address', 'wp-travel'); ?></label>
                                    <input id="email" class="form-input" name="email" type="email" value="<?php echo esc_attr($email); ?>" required />
                                </div>
                                
                                <div class="form-row">
                                    <div class="form-group">
                                        <label class="form-label" for="phone"><?php esc_html_e('Phone', 'wp-travel'); ?></label>
                                        <input id="phone" class="form-input" name="wp_travel_phone" type="tel" value="<?php echo esc_attr($phone); ?>" />
                                    </div>
                                    
                                    <div class="form-group">
                                        <label class="form-label" for="country"><?php esc_html_e('Country', 'wp-travel'); ?></label>
                                        <input id="country" class="form-input" name="billing_country" type="text" value="<?php echo esc_attr($country); ?>" />
                                    </div>
                                </div>
                                
                                <div class="form-action">
                                    <button type="submit" name="save_account_details" class="shadcn-button shadcn-button-default">
                                        <i class='bx bx-save'></i> <?php esc_html_e('Save Changes', 'wp-travel'); ?>
                                    </button>
                                </div>
                            </form>
                            <?php
                        }
                        ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
    
    <?php do_action('wp_travel_user_dashboard_after_content'); ?>
</div> 