Priyansh Tours - Brownfield Product Requirements Document (PRD)
1. Goals and Background Context
Goals
Business Goal: To significantly increase the number of direct tour bookings made through the website.

User Goal: For international tourists to easily find, book, and manage authentic cultural tours that align with their specific interests and needs.

Project Goal: To complete and enhance the existing priyanshtours-theme to create a secure, trustworthy, and user-friendly WordPress site that effectively showcases Priyansh Tours' unique local expertise.

Background Context
This project focuses on the enhancement of a partially completed, mobile-first WordPress theme (priyanshtours-theme) and the integration of the wp-travel plugin. The existing theme provides a solid visual and structural foundation. The goal is to build upon this foundation to implement the full feature set required for a successful international tourism website.

The core of the project is to implement a user-centric booking model, a transparent review platform, a personalized user dashboard, and advanced tour discovery features, ensuring they integrate seamlessly with the existing codebase.

Existing System Context
Theme: priyanshtours-theme is partially complete. The home page hero section and mobile bottom navigation are already implemented.

Plugin: The wp-travel plugin is installed and will serve as the core engine for tour management, booking, and user accounts.

Status: The project is a "brownfield" enhancement. All new features must be developed in a way that is compatible and consistent with the existing theme and plugin architecture.

Change Log
Date

Version

Description

Author

2024-07-22

1.0

Brownfield PRD draft created.

John, Product Manager

2024-07-22

1.1

Completed all sections.

John, Product Manager

2. Requirements
Functional
FR1: User Account Management: The system shall allow users to sign up for a new account, log in, and manage their profile information (name, contact details) via a personal dashboard.

FR2: User Dashboard: Logged-in users shall have access to a dashboard to view their complete booking history and check the status of any inquiries they have submitted.

FR3: Tour Discovery: The website shall feature a main "Tour Listings" page displaying all available tours in a grid layout.

FR4: Tour Filtering: The Tour Listings page must include a comprehensive filtering system, allowing users to narrow results by Destination, Date, Tour Type, Duration, and Price Range.

FR5: Tour Sorting: The Tour Listings page must allow users to sort the displayed tours by criteria such as Price (low to high, high to low), Popularity, and Duration.

FR6: Pagination: The Tour Listings page shall use pagination to manage and display a large number of tours effectively.

FR7: Dual Booking System: Each tour page shall provide users with two distinct options:

An "Instant Book" button that initiates a direct booking and payment workflow.

An "Inquire" button that opens a form for submitting questions to the team.

FR8: Inquiry Form: The inquiry form shall collect the user's Name, Email, the Tour of Interest (pre-filled), Number of People, and a free-text message.

FR9: Unified Review System: The system shall allow users to submit reviews on tour pages.

Reviews from authenticated (logged-in) users shall be published instantly.

Reviews from guest users shall be submitted to a moderation queue for team approval before being published.

FR10: Internationalization: The website must support both English and Spanish languages.

FR11: Multi-Currency Display: The website must be able to display tour prices in multiple currencies.

Non-Functional
NFR1: Platform: The entire website shall be built on the WordPress platform, enhancing the existing priyanshtours-theme.

NFR2: Mobile-First Design: The website design and user experience must be optimized for mobile devices as a top priority, building upon the existing mobile-first foundation.

NFR3: Usability: The website shall have a simple, clean, and intuitive look and feel, ensuring ease of use for a non-technical, international audience.

NFR4: Security: The system must be secure, protecting user data (especially personal information and passwords) and payment transactions according to industry best practices.

NFR5: Maintainability: The custom theme and plugin configurations shall be well-documented and structured to allow the client's team to manage technical maintenance internally.

3. User Interface Design Goals
This section outlines the high-level vision for the website's user experience (UX) and user interface (UI) to guide the enhancement and design process.

Overall UX Vision
The user experience should be effortless, trustworthy, and inspiring. A first-time international visitor should feel confident and excited about booking a tour. The design will remain clean and simple, prioritizing clarity and ease of use. The core philosophy is to let the high-quality tour photos and detailed itineraries be the heroes of the experience.

Key Interaction Paradigms
Discovery: Users should be able to find relevant tours quickly through a prominent search bar and intuitive, powerful filtering and sorting on the main listings page.

Trust Building: The dual inquiry/booking system and authentic reviews are central to building user confidence. The design must present these options clearly.

Seamless Booking: The instant booking process should be as frictionless as possible, with minimal steps and clear instructions.

Core Screens and Views to Enhance/Complete
Home Page (front-page.php): Complete the page by adding the Search Bar, "Why Choose Us?", Featured Tours, and CTA sections.

Tour Listings Page (page-all-tours.php): Implement the advanced filtering, sorting, and pagination functionalities.

Tour Details Page: Customize the wp-travel single tour template to include the dual booking/inquiry buttons.

Booking & Checkout Flow: Utilize and style the flow provided by the wp-travel plugin.

User Login & Signup Page: Utilize and style the pages provided by the wp-travel plugin.

User Dashboard: Customize the wp-travel dashboard to add a section for viewing inquiry history.

Accessibility & Branding
Accessibility Target: The website should aim for WCAG 2.1 Level AA compliance.

Branding: The design will continue to incorporate the Priyansh Tours logo and adhere to the client's brand color guide.

Target Devices: The primary focus is a responsive experience for mobile, which will adapt gracefully to tablets and desktops.

4. Technical Assumptions
These technical decisions will guide the Architect in creating the enhancement plan.

Repository Structure
Monorepo: The existing single repository containing the WordPress installation and the custom theme will be maintained.

Service Architecture
Monolith: The architecture will remain a traditional WordPress monolith. All new functionality will be integrated into the existing application via the custom theme or selected plugins.

Testing Requirements
Unit + Integration: The testing strategy will include unit tests for new critical functions within the custom theme and integration tests to ensure that the theme, plugins, and WordPress core work together correctly.

Additional Technical Assumptions and Requests
Plugin-First Approach: For complex functionalities like booking, payments, and multilingual support, the primary approach will be to leverage and extend the capabilities of the pre-installed wp-travel plugin. Custom code will be written within the theme to achieve the specific functionalities not offered by the plugin out-of-the-box.

5. Epic List
This high-level list outlines the major phases of the enhancement project. Each epic represents a significant, deployable increment of functionality.

Epic 1: Complete Core User Features & Dashboard: Finalize the user account system provided by wp-travel. This includes styling the login/signup pages and customizing the user dashboard to display booking history and the status of inquiries.

Epic 2: Enhance Tour Discovery & Engagement: Implement all features related to how users find and interact with tours. This includes completing the home page, implementing the advanced filtering and sorting on the tour listings page, and customizing the unified review system with our moderation logic.

Epic 3: Implement Dual Booking & Payments: Develop the complete dual booking functionality. This epic covers customizing the single tour template to add the "Inquire" button and its associated form/modal, ensuring the "Instant Book" flow works seamlessly, and integrating the selected payment gateway with the wp-travel plugin.

Epic 4: Internationalization & Launch Readiness: Implement the final features required for the international audience. This includes adding multi-currency support and the Spanish language translation, followed by final testing and launch preparations.