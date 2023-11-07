<?php
/**
 * Plugin Name: APIP Load Assets
 * Plugin URI: https://apip.dev
 * Description: Load JS and CSS files using the [apip_load] shortcode.
 * Version: 1.0
 * Author: Your Name
 * Author URI: https://apip.dev
 */

 function apip_load_assets_shortcode($atts) {
    $atts = shortcode_atts(array(
        'type' => '', // 'js' or 'css'
        'url'  => '', // the URL of the script or style to load
    ), $atts, 'apip_load');

    // Sanitize the type and URL
    $type = sanitize_text_field($atts['type']);
    $url  = esc_url($atts['url']);

    // Enqueue the asset based on type
    if ($type === 'js') {
        wp_enqueue_script('apip-custom-js', $url, array(), null, true);
    } elseif ($type === 'css') {
        wp_enqueue_style('apip-custom-css', $url);
    }

    return ''; // Shortcode does not need to return content.
}
add_shortcode('apip_load', 'apip_load_assets_shortcode');
