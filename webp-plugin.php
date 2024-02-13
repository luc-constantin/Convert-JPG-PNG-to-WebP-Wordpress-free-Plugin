<?php
/*
Plugin Name: Convert JPG, PNG to WebP
Description: A simple yet powerful plugin. Convert PNG, JPG to WebP format easily with this plugin.
Version: 1.0.6
Author: Luc Constantin
Plugin URI:  https://accolades.dev
Author URI: https://accolades.dev
*/

// Prevent Direct File Access
if ( ! defined( 'ABSPATH' ) ) {
    exit; 
}


error_reporting(E_ALL);
ini_set('display_errors', 1);


// Enqueue CSS
function webp_plugin_enqueue_styles() {
    wp_enqueue_style('webp-plugin-styles', esc_url(plugin_dir_url(__FILE__) . 'styles.css'));
}
add_action('admin_enqueue_scripts', 'webp_plugin_enqueue_styles', 999); 

// Enqueue JavaScript for admin page with nonce
function webp_plugin_enqueue_admin_scripts() {
    wp_enqueue_script('webp-plugin-admin-script', esc_url(plugin_dir_url(__FILE__) . 'admin-script.js'), array('jquery'), '1.0', true);

    // Localize script with nonce
    wp_localize_script('webp-plugin-admin-script', 'webp_plugin_nonce', array(
        'nonce' => wp_create_nonce('webp-plugin-nonce')
    ));
}
add_action('admin_enqueue_scripts', 'webp_plugin_enqueue_admin_scripts');

// Add Admin Page
function webp_plugin_admin_page() {
    add_menu_page(
        esc_html__('Convert JPG, PNG to WebP', 'webp-plugin'),
        esc_html__('Convert to WebP', 'webp-plugin'),
        'manage_options',
        'webp-plugin-admin',
        'webp_plugin_render_admin_page'
    );

    // Add Settings Submenu Page
    add_submenu_page(
        'webp-plugin-admin',
        esc_html__('WebP Conversion Settings', 'webp-plugin'),
        esc_html__('Settings', 'webp-plugin'),
        'manage_options',
        'webp-plugin-settings',
        'webp_plugin_render_settings_page'
    );
    
    //   // Submenu for Bulk Actions
    //   add_submenu_page('webp-plugin-admin', 'Bulk Convert Images', 'Bulk Convert', 'manage_options', 'webp-plugin-bulk-convert', 'webp_plugin_bulk_convert_page');
}
add_action('admin_menu', 'webp_plugin_admin_page');

function webp_plugin_bulk_convert_page() {
    // Query all images from the Media Library
    $query_images_args = array(
        'post_type'      => 'attachment',
        'post_mime_type' => 'image',
        'post_status'    => 'inherit',
        'posts_per_page' => -1,
    );

    $query_images = new WP_Query($query_images_args);

    // Display images and checkboxes for selection
    echo '<div class="wrap"><h1>Bulk Convert Images to WebP</h1><form id="bulk-convert-form">';

    foreach ($query_images->posts as $image) {
        echo '<div class="image-container">';
        echo '<label>';
        echo '<input type="checkbox" name="images[]" value="' . esc_attr($image->ID) . '" />';
        echo wp_get_attachment_image($image->ID, 'thumbnail');
        echo '</label>';
        echo '</div>';
    }

    echo '<input type="submit" value="Convert Selected Images" class="button button-primary">';
    echo '</form></div>';

 
}


// Render Admin Page
function webp_plugin_render_admin_page() {
    ?>
    <div class="wrap">
        <h1><?php echo esc_html__('WebP Plugin Admin Page', 'webp-plugin'); ?></h1>
        <h3><?php echo esc_html__('License Information', 'webp-plugin'); ?></h3>
        <p>
            This plugin is licensed under the <a href="<?php echo esc_url('https://www.gnu.org/licenses/gpl-3.0.html'); ?>" target="_blank">GNU General Public License, Version 3.0</a>.
        </p>

        <!-- Display Icon -->
        <h2><?php echo esc_html__('Plugin Icon', 'webp-plugin'); ?></h2>
        <p><img src="<?php echo esc_url(plugin_dir_url(__FILE__) . 'icon-128x128.png'); ?>" alt="<?php esc_attr_e('Plugin Icon', 'webp-plugin'); ?>" /></p>

        <!-- Display Screenshots -->
        <h2><?php echo esc_html__('Plugin Screenshots', 'webp-plugin'); ?></h2>
        <p><?php echo esc_html__('These screenshots showcase the functionality of the plugin:', 'webp-plugin'); ?></p>
        <div class="screenshots-container">
        <img src="<?php echo esc_url(plugin_dir_url(__FILE__) . 'Screenshot.png'); ?>" alt="<?php esc_attr_e('Screenshot', 'webp-plugin'); ?>" />
        </div>

        <!-- Display WebP Conversion Status -->
        <h2><?php echo esc_html__('WebP Conversion Status', 'webp-plugin'); ?></h2>
        <p>
            <strong><?php echo esc_html__('Status:', 'webp-plugin'); ?></strong>
            <?php
            $options = get_option('webp_plugin_options');
            $status = isset($options['enable_conversion']) && $options['enable_conversion'] ? 'Enabled' : 'Disabled';
            $color = $status === 'Enabled' ? 'green' : 'red';
            echo '<span style="color: ' . esc_attr($color) . '; font-weight: 600;">' . esc_html__($status, 'webp-plugin') . '</span>';

            ?>
        </p>

        <!-- WebP Conversion Form -->
        <h2><?php echo esc_html__('WebP Conversion Settings', 'webp-plugin'); ?></h2>
        <form method="post" action="options.php">
            <?php
            settings_fields('webp_plugin_settings');
            do_settings_sections('webp_plugin_settings');
            submit_button(esc_html__('Save Settings', 'webp-plugin'));
            ?>
        </form>

        <!-- Conversion Statistics -->
        <?php
        // Check if the function exists before calling it
        if (function_exists('get_total_webp_conversions')) {
            echo '<h2>' . esc_html__('Conversion Statistics', 'webp-plugin') . '</h2>';
            echo '<p><strong>' . esc_html__('Total Images Converted:', 'webp-plugin') . '</strong> ' . esc_html(get_total_webp_conversions()) . '</p>';
        }
        ?>

        <!-- Usage Instructions -->
        <h2><?php echo esc_html__('Usage Instructions', 'webp-plugin'); ?></h2>
        <p>
            <?php echo esc_html__('To enable WebP conversion, check the "Enable WebP Conversion" option and adjust settings as needed.', 'webp-plugin'); ?>
            <?php echo sprintf( wp_kses( __('For more feature request, visit <a href="%s" target="_blank">documentation page</a>.', 'webp-plugin'), array( 'a' => array( 'href' => array(), 'target' => array() ) ) ), esc_url('https://accolades.dev/schedule/') ); ?>
        </p>
    </div>
    <?php
}

// Render Settings Page
function webp_plugin_render_settings_page() {
    ?>
    <div class="wrap">
        <h1><?php echo esc_html__('WebP Plugin Settings', 'webp-plugin'); ?></h1>
        <form method="post" action="options.php">
            <?php
            settings_fields('webp_plugin_settings');
            do_settings_sections('webp_plugin_settings');
            submit_button();
            ?>
        </form>
    </div>
    <?php
}

// Register Settings
function webp_plugin_register_settings() {
    register_setting(
        'webp_plugin_settings',
        'webp_plugin_options',
        array(
            'sanitize_callback' => 'webp_plugin_sanitize_options',
        )
    );

    add_settings_section(
        'webp_plugin_general_settings',
        esc_html__('General Settings', 'webp-plugin'),
        'webp_plugin_general_settings_callback',
        'webp_plugin_settings'
    );

    // Enable WebP Conversion Field
    add_settings_field(
        'webp_plugin_enable_conversion',
        esc_html__('Enable WebP Conversion', 'webp-plugin'),
        'webp_plugin_enable_conversion_callback',
        'webp_plugin_settings',
        'webp_plugin_general_settings'
    );

    // Image Compression Field
    add_settings_field(
        'webp_plugin_image_compression',
        esc_html__('Image Compression Level', 'webp-plugin'),
        'webp_plugin_image_compression_callback',
        'webp_plugin_settings',
        'webp_plugin_general_settings'
    );

    // User Notifications Field
    add_settings_field(
        'webp_plugin_user_notifications',
        esc_html__('User Notifications', 'webp-plugin'),
        'webp_plugin_user_notifications_callback',
        'webp_plugin_settings',
        'webp_plugin_general_settings'
    );

    // File Size Reduction Tips Field
    add_settings_field(
        'webp_plugin_file_size_reduction_tips',
        esc_html__('File Size Reduction Tips', 'webp-plugin'),
        'webp_plugin_file_size_reduction_tips_callback',
        'webp_plugin_settings',
        'webp_plugin_general_settings'
    );
}
add_action('admin_init', 'webp_plugin_register_settings');

// Add filter to handle image upload and convert to WebP
add_filter('wp_handle_upload', 'webp_plugin_handle_upload_convert_to_webp');

function webp_plugin_handle_upload_convert_to_webp($upload) {
    if ($upload['type'] == 'image/jpeg' || $upload['type'] == 'image/png' || $upload['type'] == 'image/gif') {
        $file_path = $upload['file'];

        // Check if ImageMagick or GD is available
        if (extension_loaded('imagick') || extension_loaded('gd')) {
            $image_editor = wp_get_image_editor($file_path);
            if (!is_wp_error($image_editor)) {
                $file_info = pathinfo($file_path);
                $dirname = $file_info['dirname'];
                $filename = $file_info['filename'];

                // Create a new file path for the WebP image
                $new_file_path = $dirname . '/' . $filename . '.webp';

                // Attempt to save the image in WebP format
                $saved_image = $image_editor->save($new_file_path, 'image/webp');
                if (!is_wp_error($saved_image) && file_exists($saved_image['path'])) {
                    // Success: replace the uploaded image with the WebP image
                    $upload['file'] = $saved_image['path'];
                    $upload['url']  = str_replace(basename($upload['url']), basename($saved_image['path']), $upload['url']);
                    $upload['type'] = 'image/webp';

                    // Optionally remove the original image
                    @unlink($file_path);

                    // Display success notice
                    add_action('admin_notices', 'webp_plugin_display_success_notice');
                } else {
                    // Display error notice
                    add_action('admin_notices', 'webp_plugin_display_error_notice');
                }
            }
        }
    }

    return $upload;
}

// File Size Reduction Tips Field Callback
function webp_plugin_file_size_reduction_tips_callback() {
    $options = get_option('webp_plugin_options');
    $file_size_reduction_tips = isset($options['file_size_reduction_tips']) ? $options['file_size_reduction_tips'] : '';
    $default_message = esc_html__("Upload, Install and Activate the plugin <strong>BEFORE</strong> uploading the images you would like to convert.", 'webp-plugin');

    echo '<textarea name="webp_plugin_options[file_size_reduction_tips]" rows="5" cols="50">' . esc_textarea($file_size_reduction_tips ? $file_size_reduction_tips : $default_message) . '</textarea>';
}

// Compression level
function webp_plugin_image_compression_callback() {
    $options = get_option('webp_plugin_options');
    $compression_level = isset($options['image_compression']) ? esc_attr($options['image_compression']) : 74; // Default to 74 if not set

    // HTML for the range input and display percent
    echo '<input type="range" id="webp_plugin_image_compression" name="webp_plugin_options[image_compression]" min="1" max="100" value="' . $compression_level . '" oninput="compressionLevelDisplay.value=this.value" />';
    echo '<span id="compressionLevelDisplay"> ' . $compression_level . ' %</span>'; // Added spaces
    echo '<p class="description">' . esc_html__('Drag the slider to set the image compression level. 1 (low compression) to 100 (high compression).', 'webp-plugin') . '</p>';

    // Inline JavaScript to update the display percent
    echo '<script type="text/javascript">
        var compressionSlider = document.getElementById("webp_plugin_image_compression");
        var compressionLevelDisplay = document.getElementById("compressionLevelDisplay");
        compressionSlider.oninput = function() {
            compressionLevelDisplay.innerHTML = " " + this.value + " %"; 
        }
    </script>';
}




// Settings Section Callback
function webp_plugin_general_settings_callback() {
    echo '<p>' . esc_html__('Configure general settings for WebP conversion.', 'webp-plugin') . '</p>';
}

// Enable WebP Conversion Field Callback
function webp_plugin_enable_conversion_callback() {
    $options = get_option('webp_plugin_options');
    $checked = isset($options['enable_conversion']) ? checked($options['enable_conversion'], 1, false) : '';
    echo '<label><input type="checkbox" name="webp_plugin_options[enable_conversion]" value="1" ' . $checked . '> ' . esc_html__('Enable WebP Conversion', 'webp-plugin') . '</label>';
}

// User Notifications Field Callback
function webp_plugin_user_notifications_callback() {
    $options = get_option('webp_plugin_options');
    $notifications_enabled = isset($options['user_notifications']) ? checked($options['user_notifications'], 1, false) : '';
    echo '<label><input type="checkbox" name="webp_plugin_options[user_notifications]" value="1" ' . $notifications_enabled . '> ' . esc_html__('Enable User Notifications', 'webp-plugin') . '</label>';
    echo '<p class="description">' . esc_html__('Receive notifications for successful conversions, errors, or important information.', 'webp-plugin') . '</p>';
}

// Sanitize Options
function webp_plugin_sanitize_options($input) {
    $output = array();

    // Sanitize Enable WebP Conversion
    if (isset($input['enable_conversion'])) {
        $output['enable_conversion'] = sanitize_text_field($input['enable_conversion']);
    }

    // Sanitize Image Compression Level
    if (isset($input['image_compression'])) {
        $output['image_compression'] = intval($input['image_compression']);
        $output['image_compression'] = max(1, min(100, $output['image_compression']));
    }

    // Sanitize User Notifications
    if (isset($input['user_notifications'])) {
        $output['user_notifications'] = sanitize_text_field($input['user_notifications']);
    }

    // Sanitize File Size Reduction Tips
    if (isset($input['file_size_reduction_tips'])) {
        $output['file_size_reduction_tips'] = sanitize_textarea_field($input['file_size_reduction_tips']);
    }

    return $output;
}


// carefully created by Luc Constantin
