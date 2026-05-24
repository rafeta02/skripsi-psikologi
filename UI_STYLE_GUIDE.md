# UI/UX Style Guide
## Aplikasi Skripsi - Fakultas Psikologi UNS

**Version:** 1.0  
**Last Updated:** January 2026  
**Theme:** Professional Academic

---

## Table of Contents

1. [Design Philosophy](#design-philosophy)
2. [Color Palette](#color-palette)
3. [Typography](#typography)
4. [Spacing System](#spacing-system)
5. [Components](#components)
6. [Layout Patterns](#layout-patterns)
7. [Accessibility Guidelines](#accessibility-guidelines)
8. [Responsive Design](#responsive-design)
9. [Animation & Transitions](#animation--transitions)
10. [Best Practices](#best-practices)

---

## Design Philosophy

Our design system prioritizes:

- **Clarity**: Clear visual hierarchy and easy-to-understand interfaces
- **Consistency**: Unified design language across mahasiswa and dosen interfaces
- **Accessibility**: WCAG 2.1 AA compliance for inclusive design
- **Professional**: Academic-appropriate, clean, and modern aesthetic
- **Efficiency**: Streamlined workflows and reduced cognitive load

---

## Color Palette

### Primary Colors

```css
--primary-500: #22004C    /* Main Primary - Deep Purple */
--primary-600: #1a0039    /* Primary Dark */
--primary-400: #8033cc    /* Primary Light */

--secondary-500: #4A0080  /* Main Secondary - Purple */
--secondary-600: #3d0066  /* Secondary Dark */
```

**Usage:**
- Primary buttons, active states, links
- Navigation bars, headers
- Focus indicators

### Semantic Colors

```css
--success: #28a745       /* Success actions, completed states */
--warning: #ffc107       /* Warnings, pending states */
--danger: #dc3545        /* Errors, destructive actions */
--info: #17a2b8          /* Informational messages */
```

### Neutral Colors

```css
--gray-50: #f8f9fa       /* Backgrounds */
--gray-100: #f4f6f9      /* Card backgrounds */
--gray-300: #dee2e6      /* Borders */
--gray-600: #6c757d      /* Secondary text */
--gray-900: #212529      /* Primary text */
--white: #ffffff
--black: #000000
```

**Color Contrast Guidelines:**
- Text on white background: minimum #4a5568
- White text requires dark background (#22004C or darker)
- Always test contrast ratio (minimum 4.5:1 for normal text)

---

## Typography

### Font Family

```css
--font-family-sans: 'Source Sans Pro', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
```

### Font Sizes

| Size | Value | Usage |
|------|-------|-------|
| xs | 0.75rem (12px) | Small labels, helper text |
| sm | 0.875rem (14px) | Secondary text, table data |
| base | 1rem (16px) | Body text, form inputs |
| lg | 1.125rem (18px) | Subheadings |
| xl | 1.25rem (20px) | Card titles |
| 2xl | 1.5rem (24px) | Section headings |
| 3xl | 1.875rem (30px) | Page titles |
| 4xl | 2.25rem (36px) | Hero headings |

### Font Weights

| Weight | Value | Usage |
|--------|-------|-------|
| Light | 300 | Decorative text |
| Normal | 400 | Body text |
| Medium | 500 | Emphasized text |
| Semibold | 600 | Subheadings |
| Bold | 700 | Headings, important text |

### Heading Styles

```html
<h1>Page Title</h1>          <!-- 2.25rem, bold -->
<h2>Section Heading</h2>      <!-- 1.875rem, bold -->
<h3>Card Title</h3>           <!-- 1.5rem, semibold -->
<h4>Subsection</h4>           <!-- 1.25rem, semibold -->
```

---

## Spacing System

Based on 8px grid system:

| Variable | Value | Usage |
|----------|-------|-------|
| --spacing-1 | 4px | Tight spacing |
| --spacing-2 | 8px | Base unit |
| --spacing-3 | 12px | Small padding |
| --spacing-4 | 16px | Standard padding |
| --spacing-5 | 20px | Medium padding |
| --spacing-6 | 24px | Large padding |
| --spacing-8 | 32px | Extra large padding |
| --spacing-10 | 40px | Section spacing |
| --spacing-12 | 48px | Page section spacing |

**Guidelines:**
- Use consistent spacing multiples (8px base)
- Maintain visual rhythm with even spacing
- Group related elements with smaller spacing
- Separate sections with larger spacing

---

## Components

### 1. Buttons

#### Primary Button
```html
<button class="btn-modern btn-modern-primary">
    <i class="fas fa-check"></i> Submit
</button>
```

**States:**
- Default: Primary color background
- Hover: Slightly darker, translateY(-2px)
- Active: No transform
- Disabled: 50% opacity
- Focus: 3px outline

#### Button Sizes
```html
<button class="btn-modern btn-modern-sm">Small</button>
<button class="btn-modern">Medium</button>
<button class="btn-modern btn-modern-lg">Large</button>
```

#### Button Variants
- `.btn-modern-primary` - Main actions
- `.btn-modern-secondary` - Secondary actions
- `.btn-modern-outline` - Tertiary actions
- `.btn-modern-ghost` - Subtle actions

### 2. Cards

#### Modern Card
```html
<div class="card-modern">
    <div class="card-modern-header bg-gradient-primary">
        <h3 class="mb-0 text-white">Card Title</h3>
    </div>
    <div class="card-modern-body">
        Content here
    </div>
    <div class="card-modern-footer">
        Footer content
    </div>
</div>
```

**Card Variants:**
- `.card-modern-elevated` - Extra shadow
- `.card-modern-flat` - No shadow
- `.card-modern-glass` - Glassmorphism effect
- `.hover-lift` - Lift on hover

### 3. Forms

#### Form Group
```html
<div class="form-group">
    <label class="required">Field Name</label>
    <input type="text" class="form-control-modern" placeholder="Enter value">
    <small class="help-text-modern">Helper text here</small>
</div>
```

#### Validation States
```html
<!-- Success -->
<input class="form-control-modern is-valid">
<div class="valid-feedback-modern">Looks good!</div>

<!-- Error -->
<input class="form-control-modern is-invalid">
<div class="invalid-feedback-modern">Please provide a valid input</div>
```

### 4. Tables

#### Modern Table
```html
<table class="table-modern">
    <thead>
        <tr>
            <th>Column 1</th>
            <th>Column 2</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>Data 1</td>
            <td>Data 2</td>
        </tr>
    </tbody>
</table>
```

**Table Variants:**
- `.table-modern-striped` - Alternating row colors
- Hover effect built-in

### 5. Badges & Status Pills

#### Modern Badge
```html
<span class="badge-modern badge-modern-success">Approved</span>
<span class="badge-modern badge-modern-warning">Pending</span>
<span class="badge-modern badge-modern-danger">Rejected</span>
```

#### Status Pill (with dot indicator)
```html
<span class="status-pill status-approved">
    <span class="status-pill-dot"></span>
    Approved
</span>
```

### 6. Alerts

#### Modern Alert
```html
<div class="alert-modern alert-modern-success">
    <div class="alert-modern-icon">
        <i class="fas fa-check-circle"></i>
    </div>
    <div class="alert-modern-content">
        <div class="alert-modern-title">Success!</div>
        <div class="alert-modern-message">Your action was completed successfully.</div>
    </div>
</div>
```

**Alert Types:**
- `.alert-modern-success`
- `.alert-modern-warning`
- `.alert-modern-danger`
- `.alert-modern-info`

### 7. Loading States

#### Skeleton Loader
```html
<div class="skeleton skeleton-title"></div>
<div class="skeleton skeleton-text"></div>
<div class="skeleton skeleton-text"></div>
<div class="skeleton skeleton-card"></div>
```

#### Page Loading Overlay
```html
<div class="loading" id="loadingSpinner">
    <div class="loading-content la-ball-spin-fade la-3x">
        <!-- Spinner animation -->
    </div>
</div>
```

### 8. Empty States

```html
<div class="empty-state">
    <div class="empty-state-icon">
        <i class="fas fa-clipboard-list"></i>
    </div>
    <div class="empty-state-title">No Data Found</div>
    <div class="empty-state-description">
        There are no items to display at this time.
    </div>
    <div class="empty-state-action">
        <button class="btn-modern btn-modern-primary">Create New</button>
    </div>
</div>
```

---

## Layout Patterns

### Dashboard Layout

```
┌─────────────────────────────────────┐
│  Hero Header (Gradient)             │
├─────────────────────────────────────┤
│  Progress Indicator                 │
├───────────┬───────────┬─────────────┤
│  Stat 1   │  Stat 2   │   Stat 3    │
├───────────┴───────────┴─────────────┤
│  Main Content Card                  │
│                                     │
│  • Tables                           │
│  • Data Visualization               │
│  • Action Items                     │
└─────────────────────────────────────┘
```

### Form Wizard Layout

```
┌─────────────────────────────────────┐
│  Header (Gradient)                  │
├─────────────────────────────────────┤
│  ●────●────○────○  Steps            │
├─────────────────────────────────────┤
│                                     │
│  Form Section                       │
│                                     │
│  [Input fields]                     │
│                                     │
├─────────────────────────────────────┤
│  [Prev]           [Next] [Submit]   │
└─────────────────────────────────────┘
```

### Content Card Grid

```
┌──────────┐ ┌──────────┐ ┌──────────┐
│  Card 1  │ │  Card 2  │ │  Card 3  │
│          │ │          │ │          │
│  [CTA]   │ │  [CTA]   │ │  [CTA]   │
└──────────┘ └──────────┘ └──────────┘
```

---

## Accessibility Guidelines

### 1. Keyboard Navigation

- All interactive elements must be keyboard accessible
- Visible focus indicators (3px outline)
- Logical tab order
- Skip links for main content

### 2. Screen Reader Support

```html
<!-- Screen reader only text -->
<span class="sr-only">Additional context</span>

<!-- ARIA labels -->
<button aria-label="Close dialog">×</button>

<!-- ARIA live regions -->
<div role="alert" aria-live="polite">
    Success message
</div>
```

### 3. Color Contrast

- Normal text: minimum 4.5:1
- Large text (18pt+): minimum 3:1
- Don't rely on color alone for information

### 4. Form Accessibility

```html
<!-- Associated labels -->
<label for="email">Email</label>
<input id="email" type="email" aria-required="true">

<!-- Error messages -->
<input aria-invalid="true" aria-describedby="email-error">
<span id="email-error" class="invalid-feedback-modern">
    Invalid email format
</span>
```

### 5. Touch Targets

- Minimum 44x44px for mobile
- Adequate spacing between clickable elements
- Clear tap/click feedback

---

## Responsive Design

### Breakpoints

```css
/* Mobile First Approach */
@media (min-width: 576px) { /* Small devices */ }
@media (min-width: 768px) { /* Tablets */ }
@media (min-width: 992px) { /* Desktop */ }
@media (min-width: 1200px) { /* Large desktop */ }
```

### Mobile Considerations

1. **Navigation**: Hamburger menu, collapsible sections
2. **Typography**: Slightly smaller for mobile
3. **Cards**: Full width on mobile, grid on desktop
4. **Tables**: Horizontal scroll or card view
5. **Forms**: Full-width inputs, larger touch targets

### Responsive Utilities

```css
.d-none.d-md-block     /* Hidden on mobile, visible on tablet+ */
.col-12.col-md-6       /* Full width mobile, half on tablet+ */
```

---

## Animation & Transitions

### Timing Functions

```css
--transition-fast: 150ms ease-in-out;
--transition-base: 300ms ease-in-out;
--transition-slow: 500ms ease-in-out;
```

### Common Animations

#### Fade In
```css
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}
.animate-fade-in { animation: fadeIn 0.5s ease-out; }
```

#### Hover Lift
```css
.hover-lift:hover {
    transform: translateY(-4px);
    box-shadow: var(--shadow-lg);
}
```

#### Pulse Animation
```css
@keyframes pulse {
    0%, 100% { opacity: 1; transform: scale(1); }
    50% { opacity: 0.8; transform: scale(1.05); }
}
.animate-pulse { animation: pulse 2s infinite; }
```

### Reduced Motion

```css
@media (prefers-reduced-motion: reduce) {
    * {
        animation-duration: 0.01ms !important;
        transition-duration: 0.01ms !important;
    }
}
```

---

## Best Practices

### Do's ✅

1. **Use consistent spacing** - Stick to the 8px grid system
2. **Maintain hierarchy** - Clear visual hierarchy with size and weight
3. **Test contrast** - Ensure WCAG AA compliance
4. **Mobile first** - Design for mobile, enhance for desktop
5. **Use semantic HTML** - Proper heading structure, lists, etc.
6. **Provide feedback** - Loading states, success messages
7. **Group related items** - Use cards and sections effectively
8. **Add helpful hints** - Tooltips and helper text
9. **Test keyboard navigation** - Tab through all forms
10. **Use icons consistently** - Same icon set throughout

### Don'ts ❌

1. **Don't use color alone** - for information or status
2. **Don't create tiny touch targets** - minimum 44px
3. **Don't nest too deeply** - keep DOM hierarchy simple
4. **Don't use ALL CAPS excessively** - reduces readability
5. **Don't forget alt text** - for images and icons
6. **Don't disable zoom** - on mobile devices
7. **Don't autoplay** - videos or animations
8. **Don't override browser defaults** - without good reason
9. **Don't use tooltips for critical info** - keep it visible
10. **Don't break scroll behavior** - smooth, predictable

### Component Checklist

Before implementing a new component, ensure:

- [ ] Keyboard accessible
- [ ] Screen reader friendly
- [ ] Mobile responsive
- [ ] Has hover/focus states
- [ ] Loading state defined
- [ ] Error state defined
- [ ] Empty state defined
- [ ] Meets contrast requirements
- [ ] Uses design tokens (CSS variables)
- [ ] Documented with examples

---

## Examples

### Complete Page Example

```html
@extends('layouts.mahasiswa')

@section('content')
<div class="container py-4">
    <!-- Hero Header -->
    <div class="card-modern mb-4" style="background: linear-gradient(135deg, var(--primary-500) 0%, var(--secondary-500) 100%); border: none;">
        <div class="card-modern-body" style="padding: var(--spacing-8);">
            <h2 class="mb-1 text-white font-weight-bold">Page Title</h2>
            <p class="mb-0 text-white">Description text</p>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="card-modern hover-lift">
                <div class="card-modern-body">
                    <div class="h2 mb-2">123</div>
                    <div class="text-sm text-gray-600">Total Items</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="card-modern">
        <div class="card-modern-header">
            <h3 class="mb-0">Content Section</h3>
        </div>
        <div class="card-modern-body">
            <!-- Content here -->
        </div>
    </div>
</div>
@endsection
```

---

## Resources

### CSS Files
- `public/css/design-system.css` - Core design tokens
- `public/css/form-components.css` - Form styling
- `public/css/shared-components.css` - Reusable components
- `public/css/accessibility.css` - A11y enhancements

### Icon Library
- Font Awesome 5.6.3 (https://fontawesome.com/v5/)

### References
- WCAG 2.1 Guidelines: https://www.w3.org/WAI/WCAG21/quickref/
- Material Design: https://material.io/design
- Bootstrap 4 Docs: https://getbootstrap.com/docs/4.6/

---

## Version History

| Version | Date | Changes |
|---------|------|---------|
| 1.0 | January 2026 | Initial release with comprehensive UI/UX improvements |

---

## Support & Feedback

For questions or suggestions regarding this style guide:
- Contact: Development Team
- Issues: Report in project repository
- Updates: Check documentation regularly

---

**Remember**: Consistency is key. When in doubt, refer to this guide and existing implementations.

