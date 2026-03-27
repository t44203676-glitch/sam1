# Implementation Plan - Universal Responsiveness (Mobile First)

The goal is to ensure all interfaces are fully responsive, with "excellent" mobile usability and "perfect" desktop layout. 90% of traffic is mobile, so touch-friendly interactions and vertical scrolling (avoiding horizontal scroll) are paramount.

## Proposed Changes

### 1. Global CSS Enhancements
#### [MODIFY] [style.css](file:///c:/xampp/htdocs/k/public/css/style.css)
- Add global responsive utility classes.
- Refine typography `clamp()` for headings to scale smoothly between mobile and desktop.
- Improve `.card` and `.stat-card` padding for small screens.
- Add `.touch-target` class for larger buttons/links on mobile.

#### [MODIFY] [forms.css](file:///c:/xampp/htdocs/k/public/css/forms.css)
- Standardize `.table-responsive-stack` classes.
- Ensure all forms use a grid that collapses properly to 1 column on mobile (already mostly there, but needs audit).

### 2. Header and Layout Standardizations
#### [MODIFY] [header.php](file:///c:/xampp/htdocs/k/views/header.php)
- **CRITICAL**: Remove `min-width: 1100px !important;` from `#partners-table`. Replace it with a more flexible approach (e.g., horizontal scroll container WITHOUT forcing width on the table itself, or better card-based wrapping).
- Ensure the `mobile-header` is robust and handles very narrow screens.

### 3. Dashboard and List Views
#### [MODIFY] [admin/shared/styles.php](file:///c:/xampp/htdocs/k/admin/shared/styles.php)
- Enhance the table-to-card conversion logic to be more generic and easier to apply.
- Add better spacing for `stat-cards` on mobile (maybe 2-column grid instead of horizontal list).

### 4. Service Detail Views (Edit/View Modes)
#### [MODIFY] [admin/serves/views/marriage_permits_view.php](file:///c:/xampp/htdocs/k/admin/serves/views/marriage_permits_view.php)
- (and similar files)
- Ensure all `<td>` elements in "Step 2" tables have `data-label` attributes to support the card-stacking view.
- Optimize the `enableEditMode()` JS to ensure inputs on mobile are `100%` width within their "card" container.

## Verification Plan

### Automated Tests
- I will use the `browser_subagent` to take screenshots of key pages at different viewport sizes (Mobile: 375px, Tablet: 768px, Desktop: 1440px).
- Verify that no horizontal scrollbar appears on the `<body>` at 375px width.

### Manual Verification
1.  **Dashboard**: Check that stat cards are readable and charts scale.
2.  **Service List**: Verify the requests table converts to cards on mobile.
3.  **Service View (e.g., Marriage Permit)**:
    - Check Step 1 fields (rows of 3-4 on desktop, 1-2 on mobile).
    - Check Step 2 table (horizontal scroll or cards on mobile).
    - Enable "Edit Mode" and ensure inputs are easy to tap and type in.
4.  **Public Site**: Check [inquiry.php](file:///c:/xampp/htdocs/k/smart_inquiry.php) and [index.php](file:///c:/xampp/htdocs/k/index.php) for responsiveness.

> [!IMPORTANT]
> I will prioritize fixing the "Step 2" tables because they are the most complex parts of the UI and currently the most broken on mobile due to the fixed 1100px width.
