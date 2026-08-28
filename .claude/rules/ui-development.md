---
description: UI implementation rules (React + Tailwind CSS / CSS Modules, Blade admin)
paths:
  - "**/*.html"
  - "**/*.css"
  - "**/*.scss"
  - "**/*.jsx"
  - "**/*.tsx"
  - "**/*.blade.php"
alwaysApply: false
---

# UI Development Rules

## Styling

- Never use inline `style`
- Prefer the CSS framework already in use (Tailwind CSS)
- Respect the existing design system and CSS architecture
- Don't duplicate the same style definition in multiple places

## CSS Framework

- Prefer Tailwind CSS utility classes for the project's styling
- For React components under `resources/assets/front/`, follow the existing CSS Modules convention (`styles.module.scss`) instead of introducing Tailwind classes there — don't mix the two approaches within the same component
- Check whether an existing class/utility already covers the need before adding new CSS

## Component Design

- Design UI components as reusable units
- Don't give a single component too many responsibilities
- Extract shared UI into components
- Keep page-specific UI separate from shared UI

## Responsive Design

- Account for responsive behavior
- Avoid heavy use of fixed sizes
- Verify mobile display

## Accessibility

- Use semantic HTML
- Set appropriate `alt` attributes
- Support keyboard operation
- Never convey information through color alone

## Maintainability

- Avoid ad-hoc CSS additions
- Avoid `!important`
- Match existing naming conventions
- Keep design rules consistent

## Quality

- Check existing UI components before implementing something new
- Don't break the existing design
- Consider maintainability, not just appearance
