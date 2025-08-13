Priyansh Tours UI/UX Specification
1. Introduction
This document defines the user experience goals, information architecture, user flows, and visual design specifications for the Priyansh Tours website. It serves as the foundation for visual design and frontend development, ensuring a cohesive and user-centered experience that is effortless, trustworthy, and inspiring for its target international audience.

Overall UX Goals & Principles
Target User Personas
The International Traveler: A diverse group of users from various countries, with different budget levels and technical abilities. They value authenticity, detailed information, and trust when booking travel online.

Usability Goals
Ease of Discovery: Users must be able to find relevant tours quickly and easily.

Trust & Confidence: The design must instill confidence, encouraging users to book directly. The dual booking/inquiry system is a key part of this.

Frictionless Transactions: The booking and payment process should be simple, clear, and secure.

Mobile-First Excellence: The experience on mobile devices is the highest priority and must be seamless.

Core Design Principles
Clarity Above All: Prioritize clear communication and intuitive navigation. Let the high-quality tour content be the focus.

Build Trust Through Transparency: Every design element, from reviews to booking options, should be transparent and straightforward.

Progressive Disclosure: Show only the information needed for the current step to avoid overwhelming the user.

Consistent & Simple Patterns: Use familiar UI patterns to ensure the site is easy to use for a global audience.

Change Log
Date

Version

Description

Author

2024-07-22

1.0

Initial UX Spec draft created.

Sally, UX Expert

2024-07-22

1.1

Added Information Architecture section.

Sally, UX Expert

2024-07-22

1.2

Added User Flows section.

Sally, UX Expert

2024-07-22

1.3

Added Tour Inquiry flow.

Sally, UX Expert

2024-07-22

1.4

Added User Signup & Login flow.

Sally, UX Expert

2024-07-22

1.5

Added Branding & Style Guide section.

Sally, UX Expert

2. Information Architecture (IA)
This section outlines the structure and organization of the website's content and features.

Site Map
The following diagram shows the primary pages of the website and their relationship to one another.

graph TD
    A[Home] --> B[Tour Listings];
    A --> F[Contact Us];
    B --> C[Tour Details];
    C --> D[Booking Flow];
    C --> E[Inquiry Form];
    
    subgraph User Account
        G[Login / Signup] --> H[User Dashboard];
        H --> I[Booking History];
        H --> J[Profile Management];
    end

    A --> G;
    B --> G;

Navigation Structure
The navigation is designed with a mobile-first approach. The theme will be built for phone screens first and then adapted for larger tablet and desktop screens.

Mobile Navigation (Primary)
A fixed bottom navigation bar will be present on all main screens to provide persistent access to core features. It will contain the following items:

Home: Links to the homepage.

Tours: Links to the main Tour Listings page.

Bookings: (Visible when logged in) Links to the User Dashboard's booking history.

Profile: Links to the User Dashboard or the Login/Signup page if the user is a guest.

Desktop Navigation
On larger screens (tablets and desktops), the fixed bottom navigation will be replaced by a traditional top header navigation bar. This bar will contain links to Home, Tours, Contact Us, and the User Profile/Login section.

3. User Flows
This section details the step-by-step paths users will take to complete key tasks on the website.

Flow: Instant Tour Booking
User Goal: To select a tour, provide necessary details, and pay for it online seamlessly.

Entry Points: The "Book Now" button on any Tour Details page.

Success Criteria: The user receives a booking confirmation on-screen and via email.

Flow Diagram
graph TD
    A[User on Tour Details Page] --> B{Clicks "Book Now"};
    B --> C[Select Date & Number of People];
    C --> D[Review Booking Summary & Price];
    D --> E{Proceed to Checkout};
    E --> F[Enter Personal & Payment Information];
    F --> G[Submit Payment];
    G --> H{Payment Successful?};
    H -->|Yes| I[Show Booking Confirmation Page];
    H -->|No| J[Show Payment Error Message];
    I --> K[Send Confirmation Email];

Edge Cases & Error Handling:

What happens if the selected date becomes unavailable while the user is in the checkout process?

The system must provide clear, user-friendly error messages if a payment fails.

The system must handle cases where the user's session times out.

Flow: Tour Inquiry
User Goal: To ask a question or request information about a specific tour to build trust before booking.

Entry Points: The "Inquire" button on any Tour Details page.

Success Criteria: The user receives an on-screen confirmation that their message has been sent and an email notification.

Flow Diagram
graph TD
    A[User on Tour Details Page] --> B{Clicks "Inquire"};
    B --> C[Display Inquiry Form];
    C -- Tour Name & Code Pre-filled --> C;
    C --> D[User fills in Name, Email, People, Message];
    D --> E{Submits Form};
    E --> F[Show "Thank You" Message On-Screen];
    F --> G[Send Inquiry to Priyansh Tours Team];
    G --> H[Send Confirmation Email to User];

Edge Cases & Error Handling:

The form must have clear validation for required fields (e.g., a valid email address is required).

If the form fails to send for a technical reason, a clear error message should be displayed, asking the user to try again later.

Flow: User Signup & Login
User Goal: To create a new account or log in to an existing account to access the personal dashboard.

Entry Points: The "Profile" navigation link, or being prompted to log in during checkout.

Success Criteria: The user is successfully authenticated and redirected to their dashboard or their previous page.

Flow Diagram
graph TD
    A[User clicks "Profile" or is prompted to log in] --> B[Login/Signup Page];
    B --> C{Has account?};
    C -->|Yes| D[Enters Email & Password];
    C -->|No| E[Clicks "Sign Up"];
    E --> F[Enters Name, Email, Password];
    F --> G{Submits Signup Form};
    G --> H[Create Account & Log User In];
    D --> I{Submits Login Form};
    I --> J{Credentials Valid?};
    J -->|Yes| K[User is Authenticated];
    J -->|No| L[Show Invalid Credentials Error];
    K --> M[Redirect to Dashboard or previous page];
    H --> M;

Edge Cases & Error Handling:

The system must handle forgotten password requests.

The signup form must validate that the email is not already in use.

Clear error messages should be provided for incorrect login attempts.

4. Branding & Style Guide
This section defines the visual identity of the website. It is based on the provided Priyansh Tours logo and the client's brand guidelines.

Color Palette
The color palette is designed to be simple, clean, and trustworthy, reflecting the brand's identity.

Color Type

Hex Code

Usage

Primary

#DD6B20

(Orange from logo) For buttons, links, and CTAs

Text

#2D3748

(Dark Gray from logo) For all body text and headings

Neutral

#F7FAFC

A very light gray for page backgrounds

White

#FFFFFF

For cards, form fields, and content backgrounds

Success

#38A169

For success messages and confirmations

Error

#E53E3E

For error messages and validation warnings

Typography
The typography will be clean, modern, and highly legible, suitable for a global audience.

Font Families
Primary (Headings & UI): "Poppins", sans-serif. (A modern, friendly, and geometric font)

Secondary (Body Text): "Roboto", sans-serif. (A highly legible and neutral font for longer text)

Type Scale
Element

Size (Mobile)

Size (Desktop)

Weight

H1

28px

48px

Semi-Bold

H2

24px

36px

Semi-Bold

H3

20px

24px

Medium

Body

16px

16px

Regular

Small

14px

14px

Regular

