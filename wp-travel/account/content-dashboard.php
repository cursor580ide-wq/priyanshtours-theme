<?php
/**
 * Custom Dashboard Content Template
 * 
 * @package Priyansh_Tours_Theme
 */

// If not logged in, use our custom login form
if (!is_user_logged_in()) {
    // Locate our custom template
    $template = get_template_directory() . '/wp-travel-templates/account/form-login.php';
    if (file_exists($template)) {
        include($template);
        exit;
    }
}

// Otherwise, include our dashboard content template
$template = get_template_directory() . '/wp-travel-templates/account/content-dashboard.php';
if (file_exists($template)) {
    include($template);
    exit;
}
