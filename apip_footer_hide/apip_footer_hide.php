<?php
/*
Plugin Name: Apip Hide Footer
Plugin URI: https://apip.dev
Description: Plugin sederhana untuk hide footer default WordPress.
Version: 1.0
Author: Afifudin Ma'arif
Author URI: https://apip.dev
*/

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Function to hide the footer
function apip_footer_hide() {
    // Add inline CSS to the head to hide the footer
    echo '<style>footer{ display: none !important; }</style>';
}

// Add the above function to the wp_head action hook
add_action('wp_head', 'apip_footer_hide');
?>
