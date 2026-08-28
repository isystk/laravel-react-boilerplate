---
description: Naming and comment conventions
alwaysApply: true
---

# Coding Style

## Naming

- Follow existing language/project conventions (PHP: camelCase/PascalCase, TS: camelCase)
- Use Enums/constants for magic numbers and magic strings, never inline
- Name Repository upsert/overwrite methods by operation style (`upsertXxx`), not by use-case (`markXxx`, `setXxx`) — keep sibling methods symmetric (e.g. `upsertSuccess` / `upsertFailed`)

## Design

- Prefer early returns
- Keep nesting shallow — nesting 2 or more levels deep (`for`/`foreach`/`if`) is forbidden; flatten with early returns, guard clauses, or by extracting a helper
- One function, one responsibility. Consider splitting past ~50 lines
- Don't duplicate code
- Avoid global state and overuse of static variables
- Extract shared logic into an appropriate module
- Precondition/guard checks (e.g. "skip if there's nothing to do") belong in the caller, not inside the callee — the callee should assume it's already relevant when invoked

## Formatting

- Follow the project's formatter/linter config (ESLint, PHP Rector/Pint/PHPStan, blade-formatter). Don't hand-format against it
- Never leave unused imports or variables
- Never introduce circular references
- Don't create unnecessary diffs for reasons unrelated to formatting
- Break array literals with one value per line, not all on one line (applies to validation rule arrays, config arrays, etc.)

## Exceptions

- Never swallow an exception silently
- Empty catch blocks are forbidden
- Catch the most specific exception type possible

## Comments

- Give each class a one-line PHPDoc/JSDoc describing its role
- Give each function a one-line doc describing what it does
- Function-body comments are usually unnecessary — don't comment what the code already makes obvious
- Express intent through code, not comments
- Only add a "why" comment inside a function for genuinely non-obvious implementations or workarounds
- For functions with complex logic, add a bullet-point flow summary to the function's doc
- For a function returning a boolean, phrase the doc as "Returns true when Xxxxx"
- Don't append supplementary notes in parentheses `（）`; fold them into a single concise sentence instead
- Keep comments concise — say only what's needed, not everything that could be said
- Never record change history in comments (e.g. "changed from X to Y", "removed as of 2026-08"); comments describe the current state only — history belongs in git/commit messages

## Quality

- Prioritize readability
- Match the existing code style
- Keep changes small
