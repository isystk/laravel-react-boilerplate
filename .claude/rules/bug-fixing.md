---
description: Bug-fixing policy
alwaysApply: true
---

# Bug Fixing Rules

## Root Cause

- Fix the root cause, not the symptom
- Analyze the cause before fixing; think about preventing recurrence
- Never guess-fix. If anything is unclear, ask the user

## Similar Patterns

- After fixing a bug, check whether the same implementation pattern exists elsewhere
- Investigate other locations that could fail for the same underlying cause
- List all locations needing the same fix and batch them when appropriate

## Scope

- Keep the fix scope explicit
- Check impact on related code
- Prioritize not breaking existing functionality

## Regression

- Check for side effects from the fix
- Add or update related tests as needed
- Consider tests that prevent the same class of bug

## Quality

- Prefer a maintainable fix over a quick workaround
- Consider extracting a shared fix when the same change is needed in multiple places
