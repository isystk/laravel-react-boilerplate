---
description: Docker / docker-compose operating rules
paths:
  - "**/docker-compose*.yml"
  - "**/Dockerfile"
  - "**/Makefile"
alwaysApply: false
---

# Docker Rules

## General

- The dev environment is Docker-based. Don't install dependencies on the host; run everything inside containers
- Use the project's `docker/docker-compose.yml` and the `make` targets in `Makefile` (`make up`, `make down`, `make restart`, `make ps`, `make logs`, `make app`) rather than raw `docker compose` invocations
- **Local dev and production (Shin-VPS) share the same `docker/app/Dockerfile` and `docker/docker-compose.yml`.** Treat any change to these files as a production-impacting change

## Commands

- Run PHP/Node commands inside the container, e.g. `make app` to open a shell, or the existing `make test` / `make format` / `make migrate` / `make tinker` targets
- Use container/service names exactly as defined in `docker/docker-compose.yml`

## Images

- Respect the existing Dockerfile; don't make unnecessary changes
- Never change the base image without the user's instruction
- `docker/app/Dockerfile.ecs` is a leftover from the old AWS ECS setup and is unused — don't edit or reference it

## Volumes

- Never delete persistent volumes
- Confirm with the user before any volume reset

## Safety

- Don't run `docker compose down -v`
- Don't run `docker system prune`
- Don't run `docker volume rm`
- Don't run `docker image prune`
- Always confirm with the user before any operation that could delete data

## Logs

- Check container logs when investigating errors
- Prefer investigating the root cause over recreating the container

## Networking

- Prefer container-to-container communication over depending on `localhost`
- Use service names as hostnames

## Quality

- Keep `docker-compose.yml` / `Dockerfile` readable
- Don't add unnecessary layers or duplicate config
- Match the existing structure
