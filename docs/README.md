# Tour Listings Page - Front-end Architecture

This document outlines the front-end architecture for the tour listings page (`archive-itineraries.php`), which features an AJAX-powered filtering and sorting system.

## Overview

The tour listings page allows users to find tours and filter them by various criteria without requiring a full page reload. This is achieved by using JavaScript to fetch and display the updated tour results asynchronously.

## File Structure

-   **`archive-itineraries.php`**: The main template file for the tour listings page. It contains the initial HTML structure, including the filter form, sorting dropdown, and the main container for the tour grid.
-   **`functions.php`**: Contains the back-end logic for handling the AJAX requests. The `priyanshtours_filter_tours_ajax` function is responsible for querying the database and returning the filtered tour data.
-   **`assets/js/tours.js`**: This file contains the JavaScript code that powers the AJAX functionality. It handles form submissions, sends requests to the server, and updates the tour grid with the new results.
-   **`wp-travel-templates/content-archive-itineraries-custom.php`**: This template part is used to render a single tour card in the grid. It is used by both the initial page load and the AJAX handler to ensure consistent output.

## How it Works

1.  **Initial Page Load**: When a user visits the tour listings page, WordPress loads the `archive-itineraries.php` template. This template displays the initial set of tours based on the default query.
2.  **User Interaction**: The user interacts with the filter form or the sorting dropdown.
3.  **AJAX Request**: The JavaScript in `assets/js/tours.js` intercepts these interactions, prevents the default form submission, and sends an AJAX request to the `priyanshtours_filter_tours_ajax` function in `functions.php`. The request includes the selected filter and sort parameters.
4.  **Server-side Processing**: The `priyanshtours_filter_tours_ajax` function receives the request, queries the database for the matching tours using `WP_Query`, and generates the HTML for the new tour grid and pagination.
5.  **AJAX Response**: The server sends back a JSON response containing the new HTML for the tour grid and pagination.
6.  **DOM Update**: The JavaScript receives the response and updates the content of the tour grid and pagination containers with the new HTML. A loading spinner is displayed while the request is in progress.
7.  **URL Update**: The URL in the browser's address bar is updated with the new filter parameters using the History API. This allows users to bookmark or share the filtered results.
