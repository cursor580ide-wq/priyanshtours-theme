Priyansh Tours - Brownfield Enhancement Architecture
1. Introduction
Document Scope
This document outlines the technical architecture and implementation strategy for enhancing the existing priyanshtours-theme for the Priyansh Tours website. The primary goal is to integrate the features defined in the Product Requirements Document (PRD) and UI/UX Specification with the existing theme and the pre-installed wp-travel plugin.

This is a brownfield project, meaning all new development must be compatible with and extend the current codebase.

Existing Project Analysis
Platform: WordPress

Theme: priyanshtours-theme (a custom, mobile-first theme).

Core Plugin: wp-travel is installed and will be used as the base for tour management and booking.

Key Finding: The theme has a solid structural and stylistic foundation, including the required mobile bottom navigation. The primary work involves customizing and extending the functionality of the theme and the wp-travel plugin.

Change Log
Date

Version

Description

Author

2024-07-22

1.0

Initial Architecture document created.

Winston, Architect

2024-07-22

1.1

Added Tour Listings Page architecture.

Winston, Architect

2024-07-22

1.2

Added Dual Booking System architecture.

Winston, Architect

2024-07-22

1.3

Added Dashboard & Review System architecture.

Winston, Architect

2. Tech Stack & Integration Strategy
Core Technology Stack
The technology stack is defined by the existing WordPress environment and the provided theme. All new development will adhere to this stack.

Category

Technology / Plugin

Version

Purpose

Platform

WordPress

latest

Core Content Management System

Theme

priyanshtours-theme

1.0

Custom theme for styling and functionality

Booking Engine

wp-travel

latest

Core plugin for tours, booking, and user accounts

Language

PHP, JavaScript, CSS

-

Standard languages for WordPress theme development

Plugin Integration Strategy
The guiding principle is to leverage the wp-travel plugin's features wherever possible and create customizations within our priyanshtours-theme. This approach ensures maximum compatibility and maintainability.

Template Overrides: For visual changes, we will copy the plugin's template files from /wp-content/plugins/wp-travel/templates/ into /priyanshtours-theme/wp-travel/ and modify them there.

Action Hooks & Filters: For modifying behavior, we will use WordPress action and filter hooks in the theme's functions.php file.

Custom Page Templates: We will enhance existing templates like page-all-tours.php to integrate with wp-travel's data.

3. Component Architecture - Tour Listings Page
This section details the implementation plan for the advanced Tour Listings page (PRD: FR3, FR4, FR5, FR6).

File to be Modified
page-all-tours.php

Technical Approach
We will modify the template to create a dynamic tour browsing experience using PHP for the initial load and AJAX for updates.

Filter & Sort Form: An HTML form at the top of the page will trigger a JavaScript event on any input change.

Initial Tour Display: WP_Query will fetch and display all tours (post_type = 'itineraries') on page load.

Dynamic Updates with AJAX: A new script (assets/js/tour-filter.js) will listen for form changes, send an AJAX request (action: 'filter_tours'), and a PHP handler in functions.php will receive the data, build a new WP_Query, generate the HTML for the updated grid, and return it. The JavaScript will then replace the grid content.

4. Component Architecture - Dual Booking & Inquiry System
This section details the implementation for the dual booking system on the single tour page (PRD: FR7, FR8).

File to be Modified / Created
Create: /priyanshtours-theme/wp-travel/content-single-itineraries.php

Modify: functions.php

Technical Approach
We will customize the single tour template to add our custom "Inquire" button alongside the plugin's default booking form.

Override Template: Copy the template from /wp-content/plugins/wp-travel/templates/content-single-itineraries.php to our theme to modify its layout safely.

Add "Inquire" Button: In the new template, add HTML for the "Inquire" button, styled as a secondary action, which will trigger a pop-up modal.

Create Inquiry Modal: Add the hidden HTML for the inquiry form modal to the template.

Handle Submission: The form will be submitted via an AJAX request (action: 'submit_tour_inquiry'). A PHP handler in functions.php will validate the data, send a formatted email to the admin using wp_mail(), and return a success message.

5. Component Architecture - User Dashboard
This section details the plan for the user dashboard (PRD: FR1, FR2).

Technical Approach
We will leverage the existing dashboard provided by the wp-travel plugin and customize it to include the status of user inquiries.

Foundation: The wp-travel plugin already creates a user dashboard with booking history. We will use this as our base.

Storing Inquiry Data: To track inquiries, we will create a Custom Post Type (CPT) named inquiry. This will be done in functions.php. Each time a user submits an inquiry form, a new inquiry post will be created with the form data saved as post meta (e.g., user's name, email, message, and status - "New", "Responded").

Dashboard Customization: We will use the wp_travel_account_dashboard_nav filter in functions.php to add a new "My Inquiries" tab to the dashboard navigation.

Displaying Inquiries: We will create a custom function to display the content for this new tab. This function will perform a WP_Query to fetch all inquiry posts authored by the currently logged-in user and display them in a simple table showing the tour name, date of inquiry, and its status.

6. Component Architecture - Unified Review System
This section details the implementation of the custom moderation logic for reviews (PRD: FR9).

File to be Modified
functions.php

Technical Approach
We will use a WordPress filter hook to intercept new comments (reviews) before they are saved to the database and apply our custom logic.

Hook into Comment Submission: We will use the preprocess_comment filter. A new function, priyansh_moderate_guest_reviews, will be attached to this filter in functions.php.

Implement Moderation Logic: Inside this function, we will perform a simple check:

If is_user_logged_in() is true: The function will do nothing, allowing the review to be posted instantly as per the default behavior.

If is_user_logged_in() is false: The function will set the comment's approval status to 'hold' ($commentdata['comment_approved'] = 0;).

Result: This ensures that all reviews from guest users are automatically placed in WordPress's default comment moderation queue, where the site admin can approve or deny them, while logged-in customers have their reviews posted immediately. This is a secure and standard way to achieve our goal.