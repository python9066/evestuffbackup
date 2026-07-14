---
name: quasar-development
description: >-
  Use when writing or debugging Quasar 2 components in Vue 3 (QSelect, QInput,
  QDialog, QBanner, QBtn, Notify, useQuasar). This app uses Laravel Vite +
  Vue Router + Pinia, not Quasar CLI. Skip for pure Vue Router/API/PHP, or
  Tailwind-only styling tasks.
metadata:
  version: 2.18.7
  project_quasar: "^2.15.1"
  generated_at: 2026-03-18
  references_synced_at: 2026-03-18
---

# Quasar development (laravel11)

Upstream skill for [Quasar](https://quasar.dev/) 2.x. Use **this skill** for Quasar components and `$q` plugins; use **Vue Router** patterns in `resources/js/router.js` for navigation.

## This project

- **Stack:** Laravel + **Vite** (`@quasar/vite-plugin` in `vite.config.js`), **Vue Router** + Pinia + Vue 3 — not Quasar CLI. Do not use `#q-app/wrappers` or `quasar/wrappers` boot-file patterns from Quasar CLI docs.
- **Installed:** `quasar` ^2.15.1 (skill references target 2.18.x; API notes below still apply).
- **Plugins registered** in `resources/js/app.js`: `Notify`, `LoadingBar`, `AppFullscreen`, `Dialog`.
- **Styling:** Quasar Sass (`quasar-variables.sass`) plus **Tailwind** in templates. Match sibling `.vue` files; do not replace existing Tailwind layout with Quasar-only patterns unless intentional.
- **Validation:** Prefer existing app patterns (inline rules, `status` / `error` on fields). **Regle** is optional upstream guidance — not required in this codebase.
- **Examples to copy:**
  - `resources/js/Components/SampleDialog.vue` — `QDialog`, `q-slide-transition`, `QSelect` + `use-input` (object model)
  - `resources/js/Pages/SamplePage.vue` — page layout with Quasar components
- **Deep docs:** [references/docs/_INDEX.md](./references/docs/_INDEX.md) — open component pages (e.g. `references/docs/vue-components/select.md`) when debugging a specific prop or API.

**Version:** 2.18.7 (reference) · **Project:** ^2.15.1

## API changes (Vue 3 / Quasar 2)

Prioritize these when editing components; full upgrade guide in [references](./references/docs/start/upgrade-guide/upgrade-guide.md).

- **v-model:** `model-value` + `@update:model-value` (not `value` / `@input`).
- **QDrawer / QDialog / QMenu / QTooltip:** prefer `class` / `style` on the component, not `content-class` / `content-style`.
- **QTable:** `rows` prop, not `data`.
- **QScrollArea:** `getScrollPosition` returns `{ top, left }`; `setScrollPosition` / `setScrollPercentage` need an `axis`.
- **Colors:** `getCssVar` / `setCssVar`, not `getBrand` / `setBrand`.
- **Composition API:** `useQuasar()` for `$q` (Notify, LoadingBar, Dialog, etc.).
- **Dialog plugin:** custom component props go in `componentProps`.
- **QSelect:** `disable-tab-select` (v2.17+); with `use-input` + `hide-selected`, add **`fill-input`** so the chosen label stays visible when `v-model` is a plain string.

**Also changed:** `useFormChild()` · `QOptionsGroup` option props · `QPopupEdit` requires `v-slot="scope"` · `GoBack` directive removed — use Vue Router · `.sync` → `v-model:propName`

## Best practices (use in this template)

- Prefer **responsive CSS classes** (`gt-sm`, `lt-md`) over the Screen plugin in JS when layout allows.
- Bootstrap dialog content with **`useDialogPluginComponent`** when using the Dialog plugin with custom Vue components.
- Use Quasar **`useInterval`** / **`useTimeout`** instead of raw `setInterval` / `setTimeout` in components.
- Prefer the **LoadingBar** plugin (already registered) over wiring `QAjaxBar` per page unless you need a local instance.
- **`QSelect` + `use-input` + `hide-selected`:** always pair with **`fill-input`** when the model is a **string**; object models with `option-label` usually do not need `hide-selected`/`fill-input`.
- Enable **`no-transition`** on `QTree` for large trees if performance matters.
- **Panel dock/expand:** [`morph()`](https://quasar.dev/quasar-utils/morph-utils) can animate one element between two layout states; skip animation when `prefers-reduced-motion: reduce`. Defer layout measurements (e.g. page inset) until morph `onEnd` if other UI depends on final size.

## Upstream only (not this app’s stack)

Skip or deprioritize unless we explicitly adopt the feature:

- **Quasar CLI** boot files, `#q-app/wrappers`, `quasar/wrappers` — we use Laravel Vite, not `quasar dev` / CLI project layout.
- **Regle** as the default validation library — optional; follow existing inline / custom validation in components.
- **SSR / `Dark.set('auto')`** flicker notes — Blade-gated SPA, not Quasar SSR.
- **`QPullToRefresh` as child of `QPage`** — requires `QLayout` / `QPage` shell we do not use app-wide.
- **Inertia-specific** routing or form patterns — this template uses Vue Router.
