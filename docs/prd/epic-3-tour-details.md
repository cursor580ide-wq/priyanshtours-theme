# Epic: Tour Details Page Enhancement - Brownfield Enhancement

## Epic Goal
Enhance the tour details page with a dual booking system, unified review display, and optimized content presentation to build trust and drive conversions while providing visitors with comprehensive tour information.

## Epic Description

### Existing System Context:
- Current relevant functionality: Basic wp-travel single tour template exists but needs customization
- Technology stack: WordPress, wp-travel plugin, custom CSS, JavaScript
- Integration points: wp-travel single tour template, booking system, reviews/comments system

### Enhancement Details:
- What's being added/changed: Dual booking system (Instant Book + Inquiry), unified review system with moderation, and optimized content layout
- How it integrates: Overrides wp-travel single tour template while maintaining compatibility with core functionality
- Success criteria: Users can easily book tours or submit inquiries, read authentic reviews, and access comprehensive tour information

## Stories

1. **Story TD-1: Implement Dual Booking System UI** - Add "Instant Book" and "Inquire" buttons to the tour details page, with appropriate visual hierarchy

2. **Story TD-2: Create Tour Inquiry Modal & Form** - Develop the inquiry form modal triggered by the "Inquire" button, collecting user information and tour-specific questions

3. **Story TD-3: Implement Inquiry Form Submission** - Create the backend functionality to process and store inquiry submissions and send notifications

4. **Story TD-4: Enhance Tour Content Layout** - Optimize the layout and presentation of tour information, including itinerary, inclusions/exclusions, and photo gallery

5. **Story TD-5: Implement Unified Review System** - Create a review display system with authenticated user instant publishing and guest moderation queue

## Compatibility Requirements
- [x] Maintains wp-travel booking functionality
- [x] Works with WordPress comment system
- [x] Mobile-first design approach is maintained
- [x] UI components match existing design patterns

## Risk Mitigation
- **Primary Risk:** Conflicts with wp-travel plugin updates affecting booking system
- **Mitigation:** Use proper template overrides and hooks rather than direct modifications
- **Rollback Plan:** Maintain backup of original templates for quick restoration if needed

## Definition of Done
- [ ] Dual booking system UI is implemented and visually distinct
- [ ] Inquiry form collects all required information and validates input
- [ ] Inquiry submissions are properly stored and notifications sent
- [ ] Tour content is well-organized and visually appealing on all devices
- [ ] Review system correctly distinguishes between authenticated and guest users
- [ ] All components maintain consistent styling with the rest of the site 