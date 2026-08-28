---
description: DB design and query performance rules (MySQL / Eloquent)
paths:
  - "**/*.php"
  - "**/*.sql"
alwaysApply: false
---

# Database Rules

## Query

- Never use `SELECT *`
- Fetch only the columns you need
- Don't load an entire large result set at once
- Avoid unnecessary JOINs
- Be mindful of the number of queries issued

## Data Loading

- Consider memory usage when processing large datasets
- Use appropriate chunking instead of loading everything at once:
  - pagination
  - chunk
  - cursor pagination

## Large Data

- Fetch TEXT/BLOB-like large columns only when actually needed
- Don't fetch TEXT/BLOB columns in list views
- Consider lazy-loading them in detail views
- Never expand large data fully into memory at once

## N+1 Prevention

- Never introduce N+1 queries
- Use eager loading (`with()`) when fetching relations
- Avoid issuing queries inside loops
- Stay conscious of query counts

## Streaming

- Consider streaming responses for large datasets
- Consider memory efficiency for large exports (e.g. CSV)
- Don't serialize large datasets to JSON all at once

## Index

- Design indexes based on actual search/sort conditions
- Check the query execution plan before adding an index
- Don't add unnecessary indexes

## Transaction

- Wrap multi-table updates in a transaction (`DB::transaction()`)
- Keep transaction scope as small as possible
- Avoid long-running transactions

## Migration

- Never modify a migration that has already run in production
- Make schema changes via a new migration
- Consider lock duration for migrations touching large tables

## Quality

- Balance readability against performance
- Avoid premature optimization
- Design for continued correctness as data volume grows
