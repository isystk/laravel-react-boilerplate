---
description: Layer separation principles
alwaysApply: true
---

# Architecture

- Don't mix layer responsibilities (Controller/Presentation ⇔ Service/Domain ⇔ Repository/Infra)
- Controller and Job must never reference a Repository directly; go through a Service method instead
- A Job's `handle()` must take no arguments; resolve Services via `app(XxxService::class)` inside the method body (see existing Jobs like `BacktestRunJob`)
- Put business logic in framework-independent layers (Service/Domain); don't couple it to framework-specific APIs
- No circular dependencies. Upper layers may depend on lower layers, never the reverse
- Consolidate shared logic into Helpers/Utils; don't duplicate implementations
- Introduce new abstractions (interfaces, base classes) only when at least 2 real call sites need them
- Keep config values and constants out of scattered code; centralize them in config files or Enums/Constants
- Don't implement ahead of actual requirements (YAGNI)
