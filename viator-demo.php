<?php
/**
 * Viator Integration Demo
 * 
 * This file demonstrates how to manually create a Viator tour for testing
 * Run this once to create a sample Viator tour based on your CSV data
 * 
 * IMPORTANT: This is for testing only - in production, use the automatic sync
 * 
 * @package PriyanshTours
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    // Load WordPress if running standalone
    require_once('wp-config.php');
    require_once('wp-load.php');
}

// Only allow this to run if user is admin
if (!current_user_can('manage_options')) {
    wp_die('Access denied. Admin privileges required.');
}

function create_sample_viator_tour() {
    // Sample data from your CSV
    $viator_tour_data = array(
        'title' => 'Golden Triangle Tour (Viator)',
        'description' => 'Experience the iconic Golden Triangle circuit covering Delhi, Agra, and Jaipur. This comprehensive tour takes you through India\'s most famous historical monuments including the Taj Mahal, Red Fort, and Amber Palace. Perfect for first-time visitors to India who want to experience the country\'s rich cultural heritage.',
        'price' => 15000, // Sample price in INR
        'duration_days' => 4,
        'duration_nights' => 3,
        'location' => 'Delhi',
        'product_code' => '5597044P4', // From your CSV
        'supplier_code' => '5597044',
        'rating' => 4.7,
        'review_count' => 143,
        'image_url' => 'https://images.unsplash.com/photo-1564507592333-c60657eea523?w=800&h=600', // Taj Mahal image
        'includes' => array(
            'Professional English-speaking guide',
            'Air-conditioned transportation',
            'Monument entrance fees',
            'Hotel pickup and drop-off',
            '3 nights accommodation',
            'Daily breakfast'
        ),
        'excludes' => array(
            'International flights',
            'Personal expenses',
            'Tips and gratuities',
            'Travel insurance',
            'Lunch and dinner'
        ),
        'itinerary' => array(
            array(
                'day' => 1,
                'title' => 'Arrival in Delhi - City Tour',
                'description' => 'Arrive in Delhi and check into your hotel. Begin with a comprehensive city tour covering Red Fort, Jama Masjid, India Gate, and Humayun\'s Tomb. Evening at leisure to explore local markets.'
            ),
            array(
                'day' => 2,
                'title' => 'Delhi to Agra - Taj Mahal Visit',
                'description' => 'Early morning departure to Agra (3-4 hours drive). Visit the magnificent Taj Mahal at sunrise, followed by Agra Fort. Explore local handicraft markets in the evening.'
            ),
            array(
                'day' => 3,
                'title' => 'Agra to Jaipur via Fatehpur Sikri',
                'description' => 'Drive to Jaipur with a stop at Fatehpur Sikri, the abandoned Mughal city. Continue to Jaipur, the Pink City. Evening arrival and check into hotel.'
            ),
            array(
                'day' => 4,
                'title' => 'Jaipur Sightseeing & Departure',
                'description' => 'Visit Amber Palace, City Palace, Hawa Mahal, and Jantar Mantar. Experience the vibrant bazaars of Jaipur. Evening departure to Delhi for onward journey.'
            )
        )
    );
    
    // Create the post
    $post_id = wp_insert_post(array(
        'post_type' => 'itineraries',
        'post_title' => $viator_tour_data['title'],
        'post_content' => $viator_tour_data['description'],
        'post_status' => 'publish',
        'meta_input' => array(
            'tour_source' => 'viator',
            'viator_product_code' => $viator_tour_data['product_code'],
            'viator_supplier_code' => $viator_tour_data['supplier_code'],
            'wp_travel_trip_price' => $viator_tour_data['price'],
            'wp_travel_trip_duration' => $viator_tour_data['duration_days'],
            'wp_travel_trip_duration_night' => $viator_tour_data['duration_nights'],
            'wp_travel_trip_duration_days' => $viator_tour_data['duration_days'],
            'viator_rating' => $viator_tour_data['rating'],
            'viator_review_count' => $viator_tour_data['review_count'],
            'viator_original_data' => $viator_tour_data
        )
    ));
    
    if ($post_id && !is_wp_error($post_id)) {
        // Set location taxonomy
        $location_term = get_term_by('name', $viator_tour_data['location'], 'travel_locations');
        if (!$location_term) {
            $location_term = wp_insert_term($viator_tour_data['location'], 'travel_locations');
            if (!is_wp_error($location_term)) {
                $location_term = get_term($location_term['term_id']);
            }
        }
        if ($location_term && !is_wp_error($location_term)) {
            wp_set_post_terms($post_id, array($location_term->term_id), 'travel_locations');
        }
        
        // Set trip type taxonomy
        $trip_type_term = wp_insert_term('Cultural Tour', 'itinerary_types');
        if (!is_wp_error($trip_type_term)) {
            wp_set_post_terms($post_id, array($trip_type_term['term_id']), 'itinerary_types');
        } else {
            // If it already exists, get and assign it
            $existing_term = get_term_by('name', 'Cultural Tour', 'itinerary_types');
            if ($existing_term) {
                wp_set_post_terms($post_id, array($existing_term->term_id), 'itinerary_types');
            }
        }
        
        // Set featured image from URL
        if (!empty($viator_tour_data['image_url'])) {
            set_viator_tour_featured_image($post_id, $viator_tour_data['image_url']);
        }
        
        // Save includes/excludes as WP Travel meta
        update_post_meta($post_id, 'wp_travel_trip_include', $viator_tour_data['includes']);
        update_post_meta($post_id, 'wp_travel_trip_exclude', $viator_tour_data['excludes']);
        
        // Save itinerary
        $formatted_itinerary = array();
        foreach ($viator_tour_data['itinerary'] as $day) {
            $formatted_itinerary[] = array(
                'label' => 'Day ' . $day['day'] . ': ' . $day['title'],
                'title' => $day['title'],
                'desc' => $day['description']
            );
        }
        update_post_meta($post_id, 'wp_travel_trip_itinerary_data', $formatted_itinerary);
        
        return array(
            'success' => true,
            'post_id' => $post_id,
            'message' => 'Sample Viator tour created successfully!'
        );
    } else {
        return array(
            'success' => false,
            'message' => 'Failed to create tour: ' . (is_wp_error($post_id) ? $post_id->get_error_message() : 'Unknown error')
        );
    }
}

function set_viator_tour_featured_image($post_id, $image_url) {
    if (!function_exists('media_sideload_image')) {
        require_once(ABSPATH . 'wp-admin/includes/media.php');
        require_once(ABSPATH . 'wp-admin/includes/file.php');
        require_once(ABSPATH . 'wp-admin/includes/image.php');
    }
    
    $image_id = media_sideload_image($image_url, $post_id, null, 'id');
    
    if (!is_wp_error($image_id)) {
        set_post_thumbnail($post_id, $image_id);
        return true;
    }
    
    return false;
}

// Execute the demo if accessed directly
if (isset($_GET['create_sample_tour']) && $_GET['create_sample_tour'] === 'yes') {
    $result = create_sample_viator_tour();
    
    if ($result['success']) {
        echo '<div style="background: #d4edda; color: #155724; padding: 15px; border: 1px solid #c3e6cb; border-radius: 4px; margin: 20px;">';
        echo '<h3>✅ Success!</h3>';
        echo '<p>' . $result['message'] . '</p>';
        echo '<p>Post ID: ' . $result['post_id'] . '</p>';
        echo '<p><a href="' . get_permalink($result['post_id']) . '" target="_blank">View Tour</a> | ';
        echo '<a href="' . admin_url('post.php?post=' . $result['post_id'] . '&action=edit') . '" target="_blank">Edit Tour</a></p>';
        echo '</div>';
    } else {
        echo '<div style="background: #f8d7da; color: #721c24; padding: 15px; border: 1px solid #f5c6cb; border-radius: 4px; margin: 20px;">';
        echo '<h3>❌ Error!</h3>';
        echo '<p>' . $result['message'] . '</p>';
        echo '</div>';
    }
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Viator Integration Demo</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 800px; margin: 50px auto; padding: 20px; }
        .demo-section { background: #f8f9fa; padding: 20px; border-radius: 8px; margin: 20px 0; }
        .btn { display: inline-block; padding: 12px 24px; background: #007cba; color: white; text-decoration: none; border-radius: 4px; border: none; cursor: pointer; }
        .btn:hover { background: #005a87; }
        .btn-danger { background: #dc3545; }
        .btn-danger:hover { background: #c82333; }
        .info { background: #cce5ff; padding: 15px; border-left: 4px solid #007cba; margin: 15px 0; }
        .warning { background: #fff3cd; padding: 15px; border-left: 4px solid #ffc107; margin: 15px 0; color: #856404; }
        pre { background: #f1f1f1; padding: 15px; border-radius: 4px; overflow-x: auto; }
    </style>
</head>
<body>
    <h1>🌍 Viator Integration Demo</h1>
    
    <div class="info">
        <strong>📋 About this demo:</strong> This page helps you test the Viator integration by creating a sample tour based on your CSV data. In production, tours will be automatically synced from the Viator API.
    </div>
    
    <div class="demo-section">
        <h2>1. Create Sample Viator Tour</h2>
        <p>Click the button below to create a sample Viator tour based on your CSV data:</p>
        
        <p><strong>Tour Details:</strong></p>
        <ul>
            <li><strong>Name:</strong> Golden Triangle Tour (Viator)</li>
            <li><strong>Product Code:</strong> 5597044P4</li>
            <li><strong>Supplier:</strong> Priyansh Tours</li>
            <li><strong>Duration:</strong> 4 Days, 3 Nights</li>
            <li><strong>Price:</strong> ₹15,000</li>
            <li><strong>Rating:</strong> 4.7/5 (143 reviews)</li>
        </ul>
        
        <a href="?create_sample_tour=yes" class="btn">Create Sample Tour</a>
    </div>
    
    <div class="demo-section">
        <h2>2. Viator API Setup</h2>
        <p>To enable live Viator integration:</p>
        <ol>
            <li>Go to <a href="<?php echo admin_url('themes.php?page=viator-integration'); ?>">Appearance → Viator Settings</a></li>
            <li>Enter your Viator API key</li>
            <li>Set your supplier ID (currently: 5597044)</li>
            <li>Enable the integration</li>
            <li>Click "Sync Tours Now" to import tours from Viator</li>
        </ol>
        
        <div class="warning">
            <strong>⚠️ Note:</strong> You'll need a valid Viator Partner API key for live integration. Contact Viator to get your API credentials.
        </div>
    </div>
    
    <div class="demo-section">
        <h2>3. How It Works</h2>
        
        <h3>🏠 Tour Sources</h3>
        <p>Your tour page now shows tours from two sources:</p>
        <ul>
            <li><strong>Local Tours (Green badge):</strong> Your regular WP Travel tours</li>
            <li><strong>Viator Tours (Blue badge):</strong> Tours imported from Viator API</li>
        </ul>
        
        <h3>🔄 Automatic Sync</h3>
        <p>When enabled, the system will:</p>
        <ul>
            <li>Automatically sync tours from Viator API every hour</li>
            <li>Create WordPress posts for each Viator tour</li>
            <li>Map Viator data to WP Travel structure</li>
            <li>Handle pricing, ratings, images, and descriptions</li>
            <li>Maintain all WP Travel booking functionality</li>
        </ul>
        
        <h3>🎛️ Filtering</h3>
        <p>Users can filter tours by:</p>
        <ul>
            <li><strong>Source:</strong> All Tours, Local Tours, or Viator Tours</li>
            <li><strong>Location, Type, Duration, Price:</strong> Works for both sources</li>
        </ul>
        
        <h3>📱 Booking Integration</h3>
        <p>Viator tours use the same WP Travel booking system:</p>
        <ul>
            <li>Same booking forms and processes</li>
            <li>Same inquiry system</li>
            <li>Same user dashboard</li>
            <li>Viator product code stored for reference</li>
        </ul>
    </div>
    
    <div class="demo-section">
        <h2>4. Sample CSV Data Structure</h2>
        <p>Your CSV format is supported. Here's what we're working with:</p>
        <pre>Supplier Code,Supplier Name,Viator Product Code,Viator Product Name,Viator Product Option Code,Start Time,Product Option Name,Product Option Description,API Supplier ID,System Product Code,System Option Code,Additional Options,Supplier Api Enabled
"5597044","Priyansh Tours","5597044P4","Golden Triangle","TG1","","","","","","","pickup=Y","FALSE"</pre>
    </div>
    
    <div class="demo-section">
        <h2>5. Next Steps</h2>
        <ol>
            <li><strong>Test the Demo:</strong> Create the sample tour and check how it appears on your tours page</li>
            <li><strong>Get API Access:</strong> Contact Viator to get your Partner API credentials</li>
            <li><strong>Configure Settings:</strong> Enter your API key in the admin settings</li>
            <li><strong>Sync Tours:</strong> Use the automatic sync to import your actual tours</li>
            <li><strong>Customize Display:</strong> Adjust styling and functionality as needed</li>
        </ol>
        
        <div class="info">
            <strong>🎯 Ready to go live?</strong> Once you have API credentials, the integration will handle everything automatically, showing both your local tours and Viator tours together seamlessly.
        </div>
    </div>
    
    <div style="text-align: center; margin-top: 40px;">
        <a href="<?php echo home_url('/itinerary/'); ?>" class="btn">View Tours Page</a>
        <a href="<?php echo admin_url('themes.php?page=viator-integration'); ?>" class="btn">Viator Settings</a>
    </div>
</body>
</html> 