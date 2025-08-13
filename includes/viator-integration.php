<?php
/**
 * Viator API Integration for Priyansh Tours
 * Integrates Viator tours with WP Travel backend functionality
 * 
 * @package PriyanshTours
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

class PriyanshTours_Viator_Integration {
    
    private $api_key;
    private $base_url = 'https://api.viator.com/partner/';
    private $supplier_id = '5597044'; // Your supplier ID from CSV
    private $cache_duration = 3600; // 1 hour cache
    
    public function __construct() {
        add_action('init', array($this, 'init_hooks'));
        add_action('wp_ajax_viator_sync_tours', array($this, 'ajax_sync_tours'));
        add_action('wp_ajax_nopriv_viator_sync_tours', array($this, 'ajax_sync_tours'));
        
        // Get API key from wp-config.php (recommended) or WordPress options (fallback)
        // To use the recommended method, add the following line to your wp-config.php file:
        // define('VIATOR_API_KEY', 'your_viator_api_key_here');
        if (defined('VIATOR_API_KEY')) {
            $this->api_key = VIATOR_API_KEY;
        } else {
            $this->api_key = get_option('priyanshtours_viator_api_key', '');
        }

        // Get Supplier ID from WordPress options
        $this->supplier_id = get_option('priyanshtours_viator_supplier_id', '5597044');
    }
    
    /**
     * Initialize WordPress hooks
     */
    public function init_hooks() {
        // Add admin menu for Viator settings
        add_action('admin_menu', array($this, 'add_admin_menu'));
        
        // Register settings
        add_action('admin_init', array($this, 'register_settings'));
        
        // Schedule automatic sync
        if (!wp_next_scheduled('priyanshtours_viator_sync')) {
            wp_schedule_event(time(), 'hourly', 'priyanshtours_viator_sync');
        }
        add_action('priyanshtours_viator_sync', array($this, 'sync_viator_tours'));
    }
    
    /**
     * Add admin menu for Viator settings
     */
    public function add_admin_menu() {
        add_submenu_page(
            'themes.php',
            'Viator Integration',
            'Viator Settings',
            'manage_options',
            'viator-integration',
            array($this, 'admin_page')
        );
    }
    
    /**
     * Register settings
     */
    public function register_settings() {
        register_setting('viator_settings', 'priyanshtours_viator_api_key');
        register_setting('viator_settings', 'priyanshtours_viator_enabled');
        register_setting('viator_settings', 'priyanshtours_viator_supplier_id');
    }
    
    /**
     * Admin page for Viator settings
     */
    public function admin_page() {
        ?>
        <div class="wrap">
            <h1>Viator Integration Settings</h1>
            <form method="post" action="options.php">
                <?php settings_fields('viator_settings'); ?>
                <table class="form-table">
                    <tr>
                        <th scope="row">Enable Viator Integration</th>
                        <td>
                            <input type="checkbox" name="priyanshtours_viator_enabled" value="1" 
                                <?php checked(1, get_option('priyanshtours_viator_enabled', 0)); ?> />
                            <p class="description">Enable or disable Viator tour integration</p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">Viator API Key</th>
                        <td>
                            <input type="text" name="priyanshtours_viator_api_key" 
                                value="<?php echo esc_attr(get_option('priyanshtours_viator_api_key', '')); ?>" 
                                class="regular-text" />
                            <p class="description">Enter your Viator API key</p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">Supplier ID</th>
                        <td>
                            <input type="text" name="priyanshtours_viator_supplier_id" 
                                value="<?php echo esc_attr(get_option('priyanshtours_viator_supplier_id', '5597044')); ?>" 
                                class="regular-text" />
                            <p class="description">Your Viator supplier ID</p>
                        </td>
                    </tr>
                </table>
                <?php submit_button(); ?>
            </form>
            
            <hr>
            <h2>Sync Viator Tours</h2>
            <p>Click the button below to manually sync tours from Viator API:</p>
            <button type="button" id="sync-viator-tours" class="button button-primary">Sync Tours Now</button>
            <div id="sync-status"></div>
            
            <script>
            document.getElementById('sync-viator-tours').addEventListener('click', function() {
                var button = this;
                var status = document.getElementById('sync-status');
                
                button.disabled = true;
                button.textContent = 'Syncing...';
                status.innerHTML = '<p>Syncing tours from Viator...</p>';
                
                fetch(ajaxurl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: 'action=viator_sync_tours&_wpnonce=' + '<?php echo wp_create_nonce("viator_sync"); ?>'
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        status.innerHTML = '<p style="color: green;">✓ ' + data.data.message + '</p>';
                    } else {
                        status.innerHTML = '<p style="color: red;">✗ Error: ' + data.data + '</p>';
                    }
                    button.disabled = false;
                    button.textContent = 'Sync Tours Now';
                })
                .catch(error => {
                    status.innerHTML = '<p style="color: red;">✗ Network error occurred</p>';
                    button.disabled = false;
                    button.textContent = 'Sync Tours Now';
                });
            });
            </script>
        </div>
        <?php
    }
    
    /**
     * Fetch tours from Viator API
     */
    public function fetch_viator_tours() {
        if (empty($this->api_key)) {
            return new WP_Error('no_api_key', 'Viator API key not configured');
        }
        
        // Check cache first
        $cache_key = 'viator_tours_' . md5($this->supplier_id);
        $cached_tours = get_transient($cache_key);
        
        if ($cached_tours !== false) {
            return $cached_tours;
        }
        
        // Fetch from API
        $response = wp_remote_get($this->base_url . 'products/search', array(
            'headers' => array(
                'exp-api-key' => $this->api_key,
                'Accept' => 'application/json',
                'Accept-Language' => 'en'
            ),
            'body' => array(
                'supplierId' => $this->supplier_id,
                'count' => 100
            ),
            'timeout' => 30
        ));
        
        if (is_wp_error($response)) {
            return $response;
        }
        
        $body = wp_remote_retrieve_body($response);
        $data = json_decode($body, true);
        
        if (empty($data) || !isset($data['products'])) {
            return new WP_Error('invalid_response', 'Invalid API response');
        }
        
        // Cache the results
        set_transient($cache_key, $data['products'], $this->cache_duration);
        
        return $data['products'];
    }
    
    /**
     * Map Viator tour data to WP Travel structure
     */
    public function map_viator_tour($viator_tour) {
        return array(
            'source' => 'viator',
            'id' => 'viator_' . $viator_tour['productCode'],
            'title' => $viator_tour['title'] ?? 'Untitled Tour',
            'description' => $viator_tour['description'] ?? '',
            'price' => $viator_tour['pricing']['summary']['fromPrice'] ?? 0,
            'currency' => $viator_tour['pricing']['summary']['fromPriceCurrency'] ?? 'USD',
            'duration' => $this->parse_duration($viator_tour['duration'] ?? ''),
            'location' => $viator_tour['destinations'][0]['destinationName'] ?? '',
            'image' => $viator_tour['images'][0]['variants'][0]['url'] ?? '',
            'rating' => $viator_tour['reviews']['combinedAverageRating'] ?? 0,
            'review_count' => $viator_tour['reviews']['totalReviews'] ?? 0,
            'product_code' => $viator_tour['productCode'],
            'product_url' => $viator_tour['productUrl'] ?? '',
            'categories' => $viator_tour['categories'] ?? array(),
            'tags' => $viator_tour['tags'] ?? array(),
            'bookingConfirmationType' => $viator_tour['bookingConfirmationType'] ?? 'INSTANT',
            'original_data' => $viator_tour
        );
    }
    
    /**
     * Parse duration from Viator format
     */
    private function parse_duration($duration) {
        if (empty($duration)) return array('days' => 1, 'nights' => 0);
        
        // Parse different duration formats from Viator
        preg_match('/(\d+)\s*(hour|day|minute)/i', $duration, $matches);
        
        if (!empty($matches)) {
            $value = intval($matches[1]);
            $unit = strtolower($matches[2]);
            
            switch ($unit) {
                case 'day':
                    return array('days' => $value, 'nights' => max(0, $value - 1));
                case 'hour':
                    return array('days' => $value > 6 ? 1 : 0, 'nights' => 0);
                default:
                    return array('days' => 1, 'nights' => 0);
            }
        }
        
        return array('days' => 1, 'nights' => 0);
    }
    
    /**
     * Sync Viator tours - create/update WP posts
     */
    public function sync_viator_tours() {
        if (!get_option('priyanshtours_viator_enabled', 0)) {
            error_log('Viator sync skipped: integration is disabled.');
            return false;
        }

        if (empty($this->api_key)) {
            error_log('Viator sync error: API key is not configured.');
            return false;
        }
        
        $viator_tours = $this->fetch_viator_tours();
        
        if (is_wp_error($viator_tours)) {
            error_log('Viator API fetch error: ' . $viator_tours->get_error_message());
            return false;
        }

        if (empty($viator_tours)) {
            error_log('Viator sync: No tours returned from the API.');
            // We don't return false here, as an empty response is not necessarily an error.
        }
        
        $synced_count = 0;
        $all_synced_product_codes = array();
        
        foreach ($viator_tours as $viator_tour) {
            $mapped_tour = $this->map_viator_tour($viator_tour);
            $all_synced_product_codes[] = $mapped_tour['product_code'];
            
            // Check if tour already exists
            $existing_post_query = new WP_Query(array(
                'post_type' => 'itineraries',
                'meta_key' => 'viator_product_code',
                'meta_value' => $mapped_tour['product_code'],
                'post_status' => 'any',
                'posts_per_page' => 1
            ));
            
            $existing_post = $existing_post_query->posts;

            if (!empty($existing_post)) {
                // Update existing tour
                $post_id = $existing_post[0]->ID;
                $result = wp_update_post(array(
                    'ID' => $post_id,
                    'post_title' => $mapped_tour['title'],
                    'post_content' => $mapped_tour['description'],
                    'post_status' => 'publish'
                ), true);

                if (is_wp_error($result)) {
                    error_log('Viator sync error: Failed to update post ' . $post_id . ' for product ' . $mapped_tour['product_code'] . '. Error: ' . $result->get_error_message());
                    continue; // Skip to the next tour
                }

            } else {
                // Create new tour
                $post_id = wp_insert_post(array(
                    'post_type' => 'itineraries',
                    'post_title' => $mapped_tour['title'],
                    'post_content' => $mapped_tour['description'],
                    'post_status' => 'publish',
                ), true);

                if (is_wp_error($post_id)) {
                    error_log('Viator sync error: Failed to insert new post for product ' . $mapped_tour['product_code'] . '. Error: ' . $post_id->get_error_message());
                    continue; // Skip to the next tour
                }
            }
            
            // Update tour metadata
            update_post_meta($post_id, 'viator_product_code', $mapped_tour['product_code']);
            update_post_meta($post_id, 'tour_source', 'viator');
            update_post_meta($post_id, 'wp_travel_trip_price', $mapped_tour['price']);
            update_post_meta($post_id, 'wp_travel_trip_duration', $mapped_tour['duration']['days']);
            update_post_meta($post_id, 'wp_travel_trip_duration_night', $mapped_tour['duration']['nights']);
            update_post_meta($post_id, 'wp_travel_trip_duration_days', $mapped_tour['duration']['days']);
            update_post_meta($post_id, 'viator_original_data', $mapped_tour['original_data']);
            update_post_meta($post_id, 'viator_rating', $mapped_tour['rating']);
            update_post_meta($post_id, 'viator_review_count', $mapped_tour['review_count']);

            // Set featured image from Viator
            if (!empty($mapped_tour['image']) && !has_post_thumbnail($post_id)) {
                $this->set_featured_image_from_url($post_id, $mapped_tour['image']);
            }

            // Set location taxonomy
            if (!empty($mapped_tour['location'])) {
                $location_term = get_term_by('name', $mapped_tour['location'], 'travel_locations');
                if (!$location_term) {
                    $location_term_data = wp_insert_term($mapped_tour['location'], 'travel_locations');
                    if (!is_wp_error($location_term_data)) {
                        $location_term = get_term($location_term_data['term_id']);
                    } else {
                        error_log('Viator sync error: Failed to insert taxonomy term ' . $mapped_tour['location'] . '. Error: ' . $location_term_data->get_error_message());
                    }
                }
                if ($location_term && !is_wp_error($location_term)) {
                    wp_set_post_terms($post_id, array($location_term->term_id), 'travel_locations');
                }
            }

            $synced_count++;
        }

        // De-sync old tours
        $this->desync_old_viator_tours($all_synced_product_codes);
        
        return $synced_count;
    }

    /**
     * De-sync old Viator tours that are no longer in the API feed.
     * @param array $current_product_codes An array of product codes from the current API feed.
     */
    public function desync_old_viator_tours($current_product_codes) {
        $args = array(
            'post_type' => 'itineraries',
            'posts_per_page' => -1,
            'meta_query' => array(
                array(
                    'key' => 'tour_source',
                    'value' => 'viator',
                    'compare' => '=',
                ),
            ),
        );

        $viator_posts = get_posts($args);
        $desynced_count = 0;

        foreach ($viator_posts as $post) {
            $product_code = get_post_meta($post->ID, 'viator_product_code', true);
            if (!in_array($product_code, $current_product_codes)) {
                // This tour is no longer in the feed, so set it to draft.
                $update_post = array(
                    'ID' => $post->ID,
                    'post_status' => 'draft',
                );
                wp_update_post($update_post);
                $desynced_count++;
            }
        }

        if ($desynced_count > 0) {
            error_log('Viator de-sync: ' . $desynced_count . ' old tours set to draft.');
        }
    }
    
    /**
     * Set featured image from URL
     */
    private function set_featured_image_from_url($post_id, $image_url) {
        if (!function_exists('media_sideload_image')) {
            require_once(ABSPATH . 'wp-admin/includes/media.php');
            require_once(ABSPATH . 'wp-admin/includes/file.php');
            require_once(ABSPATH . 'wp-admin/includes/image.php');
        }
        
        $image_id = media_sideload_image($image_url, $post_id, null, 'id');
        
        if (!is_wp_error($image_id)) {
            set_post_thumbnail($post_id, $image_id);
        }
    }
    
    /**
     * AJAX handler for manual tour sync
     */
    public function ajax_sync_tours() {
        if (!wp_verify_nonce($_POST['_wpnonce'] ?? '', 'viator_sync') || !current_user_can('manage_options')) {
            wp_send_json_error('Permission denied');
            return;
        }
        
        $synced_count = $this->sync_viator_tours();
        
        if ($synced_count === false) {
            wp_send_json_error('Sync failed - check error logs');
        } else {
            wp_send_json_success(array(
                'message' => sprintf('Successfully synced %d tours from Viator', $synced_count)
            ));
        }
    }
    
    /**
     * Get all tours (both WP Travel and Viator)
     */
    public function get_all_tours($args = array()) {
        $wp_travel_tours = array();
        $viator_tours = array();
        
        // Get WP Travel tours
        $wp_query_args = array_merge(array(
            'post_type' => 'itineraries',
            'post_status' => 'publish',
            'posts_per_page' => -1,
            'meta_query' => array(
                array(
                    'key' => 'tour_source',
                    'compare' => 'NOT EXISTS'
                )
            )
        ), $args);
        
        $wp_posts = get_posts($wp_query_args);
        foreach ($wp_posts as $post) {
            $wp_travel_tours[] = array(
                'source' => 'wp_travel',
                'post' => $post,
                'id' => $post->ID
            );
        }
        
        // Get Viator tours
        if (get_option('priyanshtours_viator_enabled', 0)) {
            $viator_query_args = array_merge(array(
                'post_type' => 'itineraries',
                'post_status' => 'publish',
                'posts_per_page' => -1,
                'meta_query' => array(
                    array(
                        'key' => 'tour_source',
                        'value' => 'viator',
                        'compare' => '='
                    )
                )
            ), $args);
            
            $viator_posts = get_posts($viator_query_args);
            foreach ($viator_posts as $post) {
                $viator_tours[] = array(
                    'source' => 'viator',
                    'post' => $post,
                    'id' => $post->ID
                );
            }
        }
        
        return array_merge($wp_travel_tours, $viator_tours);
    }
    
    /**
     * Check if a tour is from Viator
     */
    public function is_viator_tour($post_id) {
        return get_post_meta($post_id, 'tour_source', true) === 'viator';
    }
    
    /**
     * Get Viator tour data
     */
    public function get_viator_tour_data($post_id) {
        if (!$this->is_viator_tour($post_id)) {
            return false;
        }
        
        return array(
            'product_code' => get_post_meta($post_id, 'viator_product_code', true),
            'original_data' => get_post_meta($post_id, 'viator_original_data', true),
            'rating' => get_post_meta($post_id, 'viator_rating', true),
            'review_count' => get_post_meta($post_id, 'viator_review_count', true)
        );
    }
}

// Initialize the integration
new PriyanshTours_Viator_Integration(); 