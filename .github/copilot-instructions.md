# Instructions for Creating a New Widget in HappyAddons

When requested to create a new Elementor widget for the HappyAddons plugin, you must strictly follow these structural and implementation rules to ensure compatibility and correct asset loading.

## 1. Core Rule: Naming Conventions

The widget's key name (`[widget-name]`) must be used consistently across all its related files and folder names. No exceptions.

- **Folder Name:** `/widgets/[widget-name]/`
- **SCSS File Name:** `/assets/dev/sass/[widget-name].scss`
- **Registration Key:** `[widget-name]` in the widget map mapping.

---

## 2. Widget Class and File Structure

Always create the main PHP file at `/widgets/[widget-name]/widget.php`.

**Widget Class Requirements:**
- **Namespace:** Must be `namespace Happy_Addons\Elementor\Widget;`
- **Extend:** Must extend the base class: `class Your_Widget_Name extends Base`
- **Methods to Implement:**
  - `get_title()`: Returns the translated widget name.
  - `get_icon()`: Returns the elementor/custom icon class.
  - `get_keywords()`: Returns an array of search keywords.
  - `register_content_controls()`: Registers the content tab controls (Text, Media, etc.).
  - `register_style_controls()`: Registers the style tab controls (Color, Typography, etc.).
  - `render()`: Outputs the final HTML markup using `$this->get_settings_for_display()`.

---

## 3. Registering the Widget to the Map

You must register the new widget in `/classes/widgets-manager.php`.

**Instructions:**
1. Open `/classes/widgets-manager.php`.
2. Find the method `get_local_widgets_map()`.
3. Add a new dictionary array using the strict `[widget-name]` key matching the folder name.
4. Set the attributes:
   - `'cat'`: Category (e.g., `'general'`, `'creative'`).
   - `'is_active'`: Default status (usually `true`).
   - `'title'`: Translated title.
   - `'icon'`: Same icon used in the widget class.
   - `'css'`: Must exactly match the SCSS file name, e.g., `['widget-name']`.
   - `'js'`: Array of JS files (if any are needed).
   - `'vendor'`: Array of 3rd party assets `['css' => [], 'js' => []]` if required.

---

## 4. Registering External/Vendor Assets

If the widget uses external libraries (like a slider engine or custom API scripts), register them in `/classes/assets-manager.php`.

**Instructions:**
1. Open `/classes/assets-manager.php`.
2. Find the method `frontend_register()`.
3. Register the new script/style using standard WordPress functions (e.g., `wp_register_script()` or `wp_register_style()`).
4. Ensure the dependency handle is used inside the widget map's `vendor` array.

---

## 5. Styling with SCSS

Write the widget styles in SCSS. Elementor controls often use dynamic CSS via `selectors`, but base styles should be in the SCSS file.

**Instructions:**
1. Create the SCSS file at exactly: `/assets/dev/sass/[widget-name].scss`.
2. Nest your styles inside the main widget wrapper class (which is usually `.ha-[widget-name]`).

---

## Example Action Plan when Asked to "Create a Widget"

If instructed to build a new widget called "Example Box", follow these precise steps:
1. Create `/widgets/example-box/widget.php`. Define the class extending `Base` with necessary controls.
2. Create `/assets/dev/sass/example-box.scss` for styling.
3. Open `/classes/widgets-manager.php`. In `get_local_widgets_map()`, add the key `'example-box'` mapping its `'css' => ['example-box']`.
4. (Optional) If it needs a vendor script like example.js, register it in `/classes/assets-manager.php` under `frontend_register()` and add the handle to `'example-box' => ['vendor' => ['js' => ['example-js']]]`.
