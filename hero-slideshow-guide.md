# Guide: Implementing the Dynamic Hero Slideshow

This guide details the step-by-step process used to create the dynamic, movable hero image slideshow on the homepage. The implementation uses the powerful **Swiper.js** library to create a touch-friendly, autoplaying slideshow, with content that can be easily managed from the WordPress Customizer.

### 1. HTML Structure in the Template Part

First, we set up the HTML structure in a dedicated template file. This keeps the code organized and separate from the main `front-page.php`.

-   **File:** `template-parts/section-hero.php`
-   **Purpose:** This file contains the skeleton of our slideshow. It uses the specific class names that Swiper.js requires (`swiper`, `swiper-wrapper`, `swiper-slide`) and includes elements for navigation arrows and pagination dots.

The basic structure looks like this:

```html
<section class="hero-section">
    <!-- Swiper Container -->
    <div class="swiper hero-swiper">
        <div class="swiper-wrapper">
            <!-- Slides are dynamically generated here -->
        </div>
        <!-- Add Pagination -->
        <div class="swiper-pagination"></div>
        <!-- Add Navigation -->
        <div class="swiper-button-prev"></div>
        <div class="swiper-button-next"></div>
    </div>
    <div class="hero-content">
        <!-- Headline and button are placed here -->
    </div>
</section>
```

PHP logic within this file loops through the Customizer settings to generate a `swiper-slide` for each uploaded image.

### 2. Adding Dynamic Content via the Customizer

To make the hero section easily editable, we added controls to the WordPress Customizer.

-   **File:** `functions.php`
-   **Functionality:**
    1.  **New Panel:** A new "Hero Section" panel was created in the Customizer.
    2.  **Headline Control:** A text input control was added for the main headline that overlays the slideshow.
    3.  **Image Uploads:** We added three separate image upload controls, allowing the user to set up to three different slides (`hero_image_1`, `hero_image_2`, `hero_image_3`).

In `template-parts/section-hero.php`, we retrieve these values using `get_theme_mod()`:

```php
// Example for getting the headline
$headline = get_theme_mod('hero_headline', 'Default Headline');

// Example for getting an image
$image_url = get_theme_mod('hero_image_1');
if ($image_url) {
    // HTML for a slide is generated here
}
```

### 3. Enqueuing and Initializing Swiper.js

To bring the slideshow to life, we need to load the Swiper.js library and our own custom script to initialize it.

-   **File:** `functions.php`
-   **Functionality:** We use `wp_enqueue_script` and `wp_enqueue_style` to load the necessary files from a CDN. This is the standard WordPress way to add scripts and styles.

```php
function your_india_holidays_scripts() {
    // Enqueue Swiper JS
    wp_enqueue_script('swiper-js', 'https://unpkg.com/swiper/swiper-bundle.min.js', array(), null, true);
    
    // Enqueue Swiper CSS
    wp_enqueue_style('swiper-css', 'https://unpkg.com/swiper/swiper-bundle.min.css');

    // Enqueue our custom slider script
    wp_enqueue_script('hero-slider', get_template_directory_uri() . '/js/hero-slider.js', array('swiper-js'), null, true);
}
add_action('wp_enqueue_scripts', 'your_india_holidays_scripts');
```

-   **File:** `js/hero-slider.js`
-   **Purpose:** This file contains the JavaScript code to configure and initialize the Swiper instance.

```javascript
document.addEventListener('DOMContentLoaded', function () {
    const heroSwiper = new Swiper('.hero-swiper', {
        // Optional parameters
        loop: true,
        effect: 'fade', // Fading effect
        autoplay: {
            delay: 5000,
            disableOnInteraction: false,
        },

        // If we need pagination
        pagination: {
            el: '.swiper-pagination',
            clickable: true,
        },

        // Navigation arrows
        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
        },
    });
});
```

### 4. Styling the Slideshow

The final step is to apply CSS to make the hero section look good and integrate seamlessly with the site's design.

-   **File:** `style.css`
-   **Purpose:** Contains all the styling rules for the hero section.
-   **Key Styling Rules:**
    -   Making the hero section take up the full viewport height (`height: 100vh`).
    -   Styling the overlayed `hero-content` (headline and button) to be centered and legible.
    -   Customizing the appearance of the Swiper navigation arrows and pagination dots to match the theme's aesthetic.
    -   Ensuring the entire section is responsive and looks great on all screen sizes, from mobile to desktop.

This combination of a flexible template part, powerful Customizer options, and robust JavaScript library results in a professional and easily manageable hero slideshow. 