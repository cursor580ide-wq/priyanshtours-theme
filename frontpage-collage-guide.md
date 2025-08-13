# Guide: Implementing the "Find Your Travel Style" Section

This guide explains how the "Find Your Travel Style" section on the front page was implemented. This section is designed to be a dynamic, visually-engaging collage that pulls travel styles directly from the "WP-Travel" plugin and allows for easy customization through the WordPress Customizer.

### 1. Creating the Template Part

To keep the main `front-page.php` file organized, the HTML structure for this section was isolated into its own template part.

-   **File:** `template-parts/section-travel-style.php`
-   **Purpose:** This file contains the PHP and HTML required to render the "Find Your Travel Style" section. It is responsible for looping through the available travel styles and displaying each one as a styled card.

The main `front-page.php` includes this section with a single line of code:

```php
// In front-page.php
get_template_part('template-parts/section-travel-style');
```

### 2. Fetching Travel Styles Dynamically

To ensure the section updates automatically when new trip types are added in the WP-Travel plugin, the data is fetched dynamically from the WordPress database.

-   **File:** `functions.php`
-   **Functionality:** A helper function was created to retrieve all terms from the `itinerary_types` taxonomy. This is the custom taxonomy WP-Travel uses to organize its trip types.

Here is the core function:

```php
function your_india_holidays_get_travel_styles() {
    $travel_styles = get_terms( array(
        'taxonomy'   => 'itinerary_types',
        'hide_empty' => false,
    ) );
    // This function returns an array of WordPress term objects.
    return $travel_styles;
}
```

This function is then called from within `template-parts/section-travel-style.php` to get the list of travel styles to display.

### 3. Adding Customizer Controls for Images

To allow for easy visual customization, we integrated image upload controls into the WordPress Customizer.

-   **File:** `functions.php`
-   **Functionality:** We programmatically added a new panel to the Customizer named **"Travel Style Images."** This panel is automatically populated with an image upload field for every travel style registered in the WP-Travel plugin.

This was accomplished by:
1.  Retrieving all travel styles using the `your_india_holidays_get_travel_styles()` function.
2.  Looping through the returned array of travel styles.
3.  For each style, creating a new Customizer setting and control. The setting name is generated dynamically using the style's unique term ID (e.g., `travel_style_image_{term_id}`).
4.  In the `template-parts/section-travel-style.php` file, `get_theme_mod()` is used with the corresponding dynamic setting name to retrieve the uploaded image URL for each style.

### 4. Styling the Grid and Adding Animations

The final step was to style the section into a responsive and interactive collage.

-   **File:** `style.css`
-   **Implementation:**
    -   **Responsive Layout:** The section uses a combination of CSS Flexbox and Grid. On mobile devices, it is a horizontally scrollable list of items (`display: flex`). On desktop screens (768px and wider), it transforms into a static, multi-column grid (`display: grid`) for better visibility.
    -   **Card Styling:** Each travel style is presented as a "card." This card features a background image, a semi-transparent gradient overlay to ensure text is readable, and the centered title of the travel style.
    -   **Hover Animation:** To make the section more engaging, a subtle hover animation was added using CSS. When a user hovers over a card, the background image smoothly zooms in, and the overlay can be adjusted to enhance the effect. This is achieved using `transform: scale()` and `transition` properties.

A simplified example of the hover animation CSS:

```css
/* In style.css */
.travel-style-item .image-container {
    transition: transform 0.3s ease-in-out;
}

.travel-style-item:hover .image-container {
    transform: scale(1.05); /* Example zoom effect */
}
```

This comprehensive approach ensures the "Find Your Travel Style" section is not only visually appealing but also scalable and easy to manage from the WordPress backend. 