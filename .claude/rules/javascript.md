---
description: JavaScript/TypeScript and React coding conventions
paths:
  - "**/*.js"
  - "**/*.jsx"
  - "**/*.mjs"
  - "**/*.cjs"
  - "**/*.ts"
  - "**/*.tsx"
alwaysApply: false
---

# JavaScript / TypeScript Rules

## Language

- Prefer TypeScript; define types wherever possible in new code
- `any` is forbidden. If unavoidable, leave a comment explaining why
- Prefer `unknown` and narrow it, over `any`
- Rely on type inference; avoid redundant type annotations
- Prefer `const`; use `let` only when reassignment is required
- Never use `var`

## Style

- Follow the project's ESLint and Prettier configuration

## Async

- Prefer `async/await` over raw Promises
- Handle async errors properly
- Use `Promise.all()` for independent async work that can run in parallel

## Error Handling

- In `catch`, either log appropriately or re-throw as appropriate

## Performance

- Avoid unnecessary recomputation
- Avoid unnecessary loops or array copies
- Don't over-optimize at the cost of readability

## Imports

- Follow import ordering from the ESLint config

## Quality

- Prioritize type safety

Language-agnostic design, comments, and exception-handling policy: see `coding-style.md`.

# React

Applies to `resources/assets/front/` (the React SPA). Does not apply to the Blade admin screens under `resources/assets/admin/`, which use plain JS/SASS.

- One component per file, one responsibility. Consider splitting past ~300 lines
- Extract logic into custom Hooks; keep the component body focused on rendering
- Always type props explicitly with TypeScript. `any` is forbidden
- Never omit the `useEffect` dependency array — write it accurately. Don't ignore ESLint's `exhaustive-deps` warning
- Keep state local by default; lift it up / move to Context only when multiple components actually need to share it
- Always give list items a stable `key` (never an index key, especially when the array can be reordered)
- Use CSS Modules for component styles, matching the existing components — don't mix in another styling approach
- Structure each component as a `{Category}/{ComponentName}/` directory with 4 files: `index.tsx` (implementation), `index.test.tsx` (test), `index.stories.tsx` (Storybook), `styles.module.scss` (CSS Modules)
