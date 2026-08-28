---
description: Testing policy (PHPUnit / Vitest)
paths:
  - "**/tests/**/*.php"
  - "**/*Test.php"
  - "**/*Tests.php"
  - "**/*.test.js"
  - "**/*.test.ts"
  - "**/*.spec.js"
  - "**/*.spec.ts"
  - "**/*.spec.tsx"
alwaysApply: false
---

# Testing Rules

## General

- Add or update tests as needed for new implementations or spec changes
- Tests exist to guarantee behavior
- Test behavior, not implementation details

## Test Design

- One test verifies one behavior
- Name tests so the expected result is clear from the name
- Structure tests around Arrange / Act / Assert
- Tests must not depend on each other
- When every test in a class resolves the same subject-under-test (e.g. `app(FooService::class)`) with no per-test variation, do it once in `setUp()` instead of repeating it in each test. If a specific test needs a different binding (e.g. a mock), rebind and re-resolve within that test

## Assertions

- Keep assertions to the necessary minimum
- Avoid vague assertions
- Verify side effects as well as return values, when relevant

## Test Data

- Keep test data independent per test
- Don't overuse magic numbers or hardcoded strings
- Use Factories/Fixtures/Builders — for PHP, go through the `createDefaultXxx()` helpers in `tests/BaseTest.php` rather than calling `Model::factory()` directly

## Mocking

- Keep mocking to the minimum necessary
- Mock only non-deterministic elements (external APIs, time, etc.)
- Avoid mocks that couple tightly to implementation details

## Quality

- Cover error paths as well as the happy path
- Consider boundary values
- Prioritize test readability
- Avoid duplicated test code

## Maintenance

- Delete tests that are no longer needed
- Update tests when implementation changes
- Never disable a test to work around a failure
- Prefer running only the affected tests (`make test staged` / `make test branch` / `make test [file_paths...]`) over a full run when possible, to keep turnaround fast
