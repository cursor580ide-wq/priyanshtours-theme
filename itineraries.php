<?php
/**
 * The template for displaying tour archives
 * This file is used as a fallback for all tour archive pages
 * 
 * @package Priyansh_Tours_Theme
 */

get_header(); 
?>

<div class="container">
    <h1>Our Tours</h1>
    <p>Please visit our <a href="<?php echo esc_url(home_url('/tour-packages/')); ?>">Tour Packages</a> page to see all available tours.</p>
</div>

<?php
get_footer();
?> 