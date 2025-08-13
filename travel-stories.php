<?php
/**
 * Template for displaying the Travel Stories page
 * 
 * This is a special template that will be used by the template_redirect hook
 * to show the travel stories page at /travel-stories/ URL
 *
 * @package PriyanshTours
 */

get_header();
?>

<div class="container travel-stories-page">
    <header class="page-header">
        <h1 class="page-title">Travel Stories</h1>
        <div class="archive-description">
            <p>Read inspiring travel stories and experiences from around the world</p>
        </div>
    </header>

    <?php include(get_template_directory() . '/template-parts/travel-stories-content.php'); ?>
</div>

<?php
get_footer(); 