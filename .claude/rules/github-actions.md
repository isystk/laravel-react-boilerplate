---
description: CI design rules
paths:
  - ".github/workflows/**/*.yml"
  - ".github/workflows/**/*.yaml"
alwaysApply: false
---

# GitHub Actions Rules

## General

- Respect the existing workflow set (`claude.yml`, `geminiReview.yml`, `unitTest.yml`); change only what's needed
- Achieve the goal with a minimal diff
- Prioritize workflow readability

## Workflow Design

- Split jobs by responsibility
- Give steps clear, descriptive names
- Reuse common logic where possible
- Avoid unnecessary duplication

## Actions

- Prefer official or trusted actions
- Pin action versions where possible
- Prefer a major-version tag or SHA pin over `@latest`

## Security

- Never log secrets
- Never hardcode secrets
- Apply least-privilege; grant only the `permissions` actually needed

## Performance

- Use caching where applicable
- Don't run unnecessary jobs
- Parallelize jobs where possible

## Reliability

- Set sensible timeouts
- Consider retries where appropriate
- Distinguish transient failures from permanent ones

## Debugging

- Never disable CI to work around a failure
- Never delete a test to make CI pass
- Investigate and fix the actual cause

## Quality

- Include formatter/lint/test in CI
- Don't make code changes solely to satisfy CI
- Match existing naming conventions and structure
