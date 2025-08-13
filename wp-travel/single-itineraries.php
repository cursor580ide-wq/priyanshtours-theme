<?php
/**
 * Direct override of WP Travel's single-itineraries.php
 * 
 * This file forces the use of our theme's single-itineraries.php template.
 * 
 * @package Priyansh_Tours_Theme
 */

// Include our theme's template
include(get_template_directory() . '/single-itineraries.php');

// Exit to prevent further execution
exit;