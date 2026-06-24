# Happy Elementor Addons – Architecture Overview (Free)

## 1️⃣ Core Directory Layout
| Folder | Purpose |
|--------|---------|
| **`assets/`** | CSS/JS, images, fonts, and third‑party vendor libraries used by the front‑end UI of widgets and the admin panel. |
| **`base/`** | Base widget class (`widget-base.php`) that all Elementor widgets extend. Provides common helper methods (enqueue scripts, render wrappers). |
| **`classes/`** | Service layer handling admin UI, AJAX/REST, asset management, caching, conditions, dashboard/updater, WPML support, etc. |
| **`controls/`** | Custom Elementor controls (e.g., foreground group, select2, widget‑list). |
| **`extensions/`** | Small kits that add extra Elementor editor capabilities (background overlay, custom JS, scroll effects, etc.). |
| **`inc/`** | Miscellaneous helper functions (`functions.php`, `functions-forms.php`, `functions-extensions.php`). |
| **`traits/`** | Re‑usable code fragments mixed into widgets or classes (button rendering, markup helpers). |
| **`templates/`** | PHP view files for admin pages, wizards, and the Elementor library UI. |
| **`vendor/`** | Composer‑managed external dependencies (e.g., Appsero analytics). |
| **`widgets/`** | One directory per Elementor widget. Each contains a PHP file that extends the base widget and registers controls, render logic, and style scripts. |
| **`wpml/`** | WPML integration files that make widget strings translatable. |
| **Root files** | `plugin.php` – bootstrap, registers core managers with Elementor. `README.md`, `package.json`, `gulpfile.mjs` – documentation and build tooling. |

## 2️⃣ High‑Level Flow
1. **Bootstrap** (`plugin.php`)
   - Checks Elementor is active.
   - Instantiates core managers (`Widgets_Manager`, `Extensions_Manager`, `Assets_Manager`, etc.).
   - Hooks into Elementor’s `init` action.
2. **Registration**
   - **Widgets** register via `Elementor\Plugin::instance()->widgets_manager->register_widget_type( new My_Widget() );`.
   - **Controls & Extensions** are added to Elementor’s managers during appropriate hooks.
   - **Conditions** (free version has basic ones) are read from `extensions/` files and injected into Elementor’s UI.
3. **Rendering**
   - Elementor calls a widget’s `render()` method.
   - Widget may use traits/helpers to fetch data, enqueue assets, and output markup (often via small template files).
4. **Front‑End Assets**
   - `Assets_Manager` enqueues scripts/styles only when the widget appears on the page.
   - Caching (`Assets_Cache`, `Widgets_Cache`) stores generated CSS/JS to avoid duplicate work.
5. **Admin & AJAX**
   - Admin pages (wizard, library, license) live in `templates/admin/` and are served by `Classes\Dashboard_Manager`.
   - AJAX endpoints (`Classes\Ajax_Handler`) handle dynamic UI actions.
   - REST endpoints (`Classes\Api_Handler`) expose data for licensing and other services.
6. **Internationalization (WPML)**
   - Files under `wpml/` map widget settings to translatable strings, hooking into WPML callbacks.

## 3️⃣ Component Interaction Diagram
```mermaid
graph TD
    A[Plugin Bootstrap] --> B[Core Managers]
    B --> C[Widgets Manager]
    B --> D[Extensions Manager]
    B --> E[Assets Manager]
    B --> F[Ajax / API Handlers]
    C --> G[Individual Widget Classes]
    D --> H[Extension Files]
    G --> I[Traits / Helper Functions]
    G --> J[Controls (via Controls Manager)]
    G --> K[Render → Front‑end Assets]
    K --> L[Assets Cache]
    F --> M[License / Pro Services]
    subgraph WPML
        N[WPML Manager] --> G
    end
```

## 4️⃣ Testing Hot‑Spots (what to focus on when writing tests)
| Layer | Typical Test Targets |
|-------|----------------------|
| **Bootstrap** | Plugin does not load without Elementor; managers are instantiated correctly. |
| **Widgets** | `get_*_controls()`, `render()` output (HTML snapshots), lazy‑query logic. |
| **Controls** | Registration and default values. |
| **Extensions** | Hook registration and DOM/CSS modifications. |
| **Assets Manager** | Conditional enqueueing, cache key generation. |
| **Ajax / REST** | Simulated requests, JSON responses, error handling. |
| **WPML** | Correct string mapping for different locales. |

## 5️⃣ Where to Add New Code
- **New widget** → `widgets/<slug>/<widget>.php` (extend `Base\Widget_Base`).
- **New control** → `controls/<control>.php` (register via `Controls_Manager`).
- **New extension** → `extensions/<extension>.php` (add filters/actions).
- **Utility function** → `inc/functions-*.php`.
- **Pro‑only features** (license, premium widgets) live under the `happy-elementor-addons-pro` counterpart directories.

---

*This document is intended to give new developers a complete, high‑level understanding of the plugin’s architecture without needing follow‑up questions.*