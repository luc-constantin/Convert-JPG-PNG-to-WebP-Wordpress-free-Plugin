=== Convert JPG, PNG to WebP ===

Contributors: Luc.Constantin
Tags: webp, image conversion, optimization, performance, jpg to webp, png to webp
Requires at least: 4.7
Tested up to: 6.4.3
Stable tag: 1.0.5
License: GPLv3 or later
License URI: https://www.gnu.org/licenses/gpl-3.0.html

== Description ==

The Convert JPG, PNG to WebP plugin simplifies the process of converting PNG, JPG, images to the WebP format within WordPress. Enhance your website's performance by reducing image file sizes without compromising quality.

== Installation ==

1. Upload the entire `jpg-png-to-webp` folder to the `/wp-content/plugins/` directory.
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. Configure the plugin settings via the 'WebP Conversion Settings' menu in the WordPress admin area.

== Frequently Asked Questions ==

= How does the plugin work? =

The plugin automatically converts supported image formats (JPEG, PNG, GIF) to WebP during the upload process. Users can configure general settings, adjust compression levels, and receive notifications for successful conversions or errors.

= Where can I find the plugin settings? =

Navigate to the WordPress admin area and go to **Settings > WebP Conversion Settings** to access and configure the plugin settings.

== Changelog ==

= 1.0.5 =
* Updated "Tested Up To" value to ensure compatibility with the latest WordPress version.
* Declared GPL-compatible license explicitly in the readme file.
* Applied escaping to all variables and options when echoed in the admin pages for improved security.
* Prevented direct file access to plugin files, enhancing security.
* Changed color of "Status: Enabled" to green and "Status: Disabled" to red for better visual distinction on the admin page.

= 1.0.4 =
* Added a screenshot on the admin page for a better user experience.
* Enhanced security by incorporating WordPress nonces to verify the origin of requests.
* Enqueued JavaScript for the admin page with nonce handling to prevent cross-site request forgery (CSRF) attacks.
* Localized the admin script with a nonce, providing a secure way for JavaScript to use it in AJAX requests or form submissions.
* Updated security measures for functions that modify settings or perform actions.

= 1.0.3 =
* Enhanced security by incorporating WordPress nonces to verify the origin of requests.
* Enqueued JavaScript for the admin page with nonce handling to prevent cross-site request forgery (CSRF) attacks.
* Localized the admin script with a nonce, providing a secure way for JavaScript to use it in AJAX requests or form submissions.
* Updated security measures for functions that modify settings or perform actions.

= 1.0.2 =
* Improved input validation and sanitization for enhanced security.
* Applied `sanitize_text_field` and `sanitize_textarea_field` functions to various input fields.
* Enhanced validation for the `image_compression` field using `intval` and ensuring the value is within the valid range.
* Updated security measures to prevent SQL injection, XSS, and other vulnerabilities.

= 1.0.1 =
* Fixed minor bugs and improved plugin performance.

= 1.0 =
* Initial release of the Convert JPG, PNG to WebP plugin.

== Screenshots ==

1. Screenshot 1: Admin settings page showing configuration options for image conversion.
2. Screenshot 2: Conversion statistics and reports.

== License ==

This plugin is licensed under the GNU General Public License, version 3 (GPLv3) or later. See the license details at https://www.gnu.org/licenses/gpl-3.0.html.

== Support ==

For support, feature requests, or to report bugs, please visit https://accolades.dev.
