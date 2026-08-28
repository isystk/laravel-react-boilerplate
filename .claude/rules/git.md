---
description: Git operating rules
alwaysApply: true
---

# Git Rules

## General

- Never commit, push, tag, or merge without the user's explicit instruction
- Only propose history-rewriting operations (rebase, reset --hard, force push); never run them unprompted
- Confirm before any operation that could overwrite someone else's changes

## Commits

- Keep each commit to one logical change
- Don't bundle unrelated changes into the same commit
- Only commit generated files when actually needed
- Don't leave debug code or temporary comments

## Commit Message

- Use Conventional Commits
- Keep the subject line concise
- Add a body explaining the reason for the change when needed

Types used: feat, fix, refactor, perf, docs, test, chore, ci, build

## Before Commit

- Run the formatter (`make format`)
- Run the linter
- Run tests if they exist (`make test`)
- Don't include unnecessary files
- Verify no secrets are included

## Pull Requests

- Describe the change, its purpose, and its impact in the PR description
- Attach screenshots when relevant
- Keep PRs a reviewable size

## Security

Never commit: API keys, passwords, access tokens, private keys, `.env`, personal information

## Generated Files

- Update lockfiles only when dependencies actually changed
- Follow project conventions for build artifacts
