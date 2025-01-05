# Happy Elementor Addons
[Happy Addons for Elementor](https://happyaddons.com/) come with Header Footer Builder, 500+ templates, 137 free & essential pro widgets - Theme Builder, Mega Menu, Testimonial, Slider, News Ticker, Blog Post Grid, Gallery, WooCommerce Products widgets, Modal Popup, Tooltip Google maps, Carousels & more. Features Like Equal Height, Text Stroke, Shape Dividers, Floating Effect, Grid Layout, 500+ Icons, etc.

# Requirements
- The required WordPress version is `5.0`
- The required PHP version is `7.4`

# Installation
## Development Install
- Goto `wp-content\plugins` folder from terminal
- Clone this repository
```
git clone git@github.com:HappyMonsters/happy-elementor-addons.git
```
or
```
git clone https://github.com/HappyMonsters/happy-elementor-addons
```
- Goto the `happy-elementor-addons` directory
```
cd .\happy-elementor-addons\
```
- Install dependencies (NPM must be installed)
```
npm install
```
- Generate assets
```
npm run prod
```
- It will be ready to use

# Usage
## Development usage
- Install dependencies (NPM must be installed)
```
npm install
```
- Generate assets and run watcher
```
npm run dev
```
- No cache assets: place this code in your `wp-config.php` file
```php
define( 'HAPPY_ADDONS_DEV', true );
```