---
description: PHP/Laravel coding conventions
paths:
  - "**/*.php"
alwaysApply: false
---

# PHP Rules

## Language

- Target PHP 8.3+
- `declare(strict_types=1);` is required
- Type declarations (params, return type, properties) are required
- Use `readonly` wherever applicable

## Style

- Follow PSR-12

## Design

- Prefer Value Objects, Enums, and DTOs
- Avoid passing data around as raw arrays

Language-agnostic design, comments, and exception-handling policy: see `coding-style.md`.

# Laravel

- Business logic lives in the Service layer. Keep Controllers thin (request handling and response shaping only)
- Domain logic lives in `app/Domain`/`app/Services`; don't put complex logic in Eloquent models
- External API calls and file I/O live under `app/FileIO/Services`, not called directly from Controllers
- Request validation always goes in a FormRequest class. Never validate inside a Controller
- For actions with input validation (store/update, etc.), create a dedicated 1:1 FormRequest such as `StoreRequest`/`UpdateRequest`
- Cross-layer data uses DTOs defined under `app/Dto`
- Throw Laravel's standard exception classes or custom exceptions under `app/Exceptions`. Never throw a raw `Exception`
- Enums live under `app/Enums`; never hardcode magic numbers/strings
- Enums implement a shared `HasLabel`-style interface and expose labels via `label()`, resolving to a `__('enums.ClassName_value')` translation key. Never hardcode label strings
- Cast Entity attributes holding an enumerable value to their Enum type in `casts()` wherever possible; give a new column an Enum cast from the start
- Prefer an Enum type over a `string` type hint for function/method parameters carrying an enumerable value (e.g. symbol, direction, exchange)
- DB rules (N+1, migrations, etc.): see `database.md`
- Controllers/Jobs/Batches never call Repositories or Eloquent directly — always go through a Service
- Instantiate Services with `app(XxxService::class)`
- Create Services 1:1 with their caller **method** (a Controller action method, Job's `handle`, or Batch), not with the Controller/Job class as a whole; name the Service class after the action **method name**, not after the Controller's class name (e.g. an `edit()` action gets an `EditService`, a `store()` action gets a `StoreService` — never name it after an unrelated word like the Controller class name)
- Keep each Service method as thin and independently testable as possible; Controllers/Jobs/Batches orchestrate by calling Service methods, never by embedding business logic themselves
- A Service reused across multiple callers is the one exception to the 1:1 rule — place it under `app/Services/Common` (e.g. `OpenPositionCheckerService`, used by both `QuickSettingsService` and `CustomStrategyService`)
- Controllers must never call an `app/Services/Common` Service directly — call it only from within the dedicated 1:1 Service
- Services inject `XxxRepositoryInterface` via the constructor; never call Eloquent models directly
- Services never call request-scoped global helpers (`request()`, `auth()`, `session()`, etc.) directly — Controllers extract the needed value (e.g. `$request->ip()`) and pass it as a method argument or DTO field
- Entities (Eloquent models) may use `belongsTo` toward a parent, but avoid `hasMany` toward children — fetch children via their Repository instead
- When tests need `factory()`, go through the `createDefaultXxx()` helpers in `tests/BaseTest.php`; don't call `Model::factory()` directly
- Every new function must have a corresponding test
- Only add a step-by-step bullet list to a PHPDoc when the function's logic is genuinely complex
- Keep Larastan (level 6) clean — write type declarations that satisfy it
- Write array literals with one value per line (not inline)
- Entity (Eloquent model) methods must not reach into another model's properties/methods (e.g. `$this->plan->canAutoTrade()` inside a `User` method) — that kind of cross-model composition belongs in the Service/Controller layer, not inside an Entity method

## Doc Comments (layer-specific, overrides the general PHPDoc rule above)

- Controller classes: no class-level PHPDoc
- Controller methods: require a concise Japanese one-line doc, except `__construct`, which needs none
- Controllers must not define `private` methods — inline the logic at each call site, or move shared logic into a Service (or an Entity method, as long as it doesn't violate the cross-model rule above)
- Service classes (`app/Services/**`): no class-level PHPDoc, **except** classes under `app/Services/Common`, which require a concise Japanese class-level doc
- DTO classes (`app/Dto/**`): require a concise Japanese class-level doc, and every field/property (including constructor-promoted properties) requires a concise Japanese doc too
