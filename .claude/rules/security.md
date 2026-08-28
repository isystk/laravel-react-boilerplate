---
description: Security policy
alwaysApply: true
---

# Security Rules

## General

- Prioritize security over convenience
- When in doubt, choose the safer implementation
- Confirm with the user before any change with security implications

## Secrets

- Never write API keys, access tokens, passwords, or private keys into source code
- Read secrets from environment variables or a secrets manager
- Never log secrets
- Use dummy values in example code

## Authentication & Authorization

- Never bypass authentication or authorization
- Never skip a permission check
- Follow the principle of least privilege

## Input Validation

- Always validate external input
- Never trust input values
- Never embed input directly into SQL, HTML, or shell commands

## Database

- Use parameterized queries or the ORM (Eloquent)
- Never write code that enables SQL injection

## Output

- Escape output appropriately
- Never produce HTML output that enables XSS

## File Handling

- Prevent path traversal
- Validate size, extension, and MIME type on uploaded files
- Never build a file path from raw user input

## External APIs

- Use HTTPS
- Set timeouts
- Implement retries where appropriate
- Never trust an external response blindly

## Logging

- Never log personal information or secrets
- Log only the minimum information needed

## Dependencies

- Use trusted libraries
- Don't add unnecessary dependencies
- Avoid libraries with known vulnerabilities

## Git

Never commit: `.env`, API keys, private keys, certificates

## Safety

- Never disable a security feature to work around a problem
- Never carry dev-only settings into production
