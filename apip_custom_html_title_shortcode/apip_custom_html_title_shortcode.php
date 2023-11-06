<?php
/**
 * Plugin Name: Apip Custom HTML Title
 * Description: Changes the HTML title tag of the page using a shortcode.
 * Version: 1.0
 * Author: Afifudin Ma'arif
 */

//  [custom_html_title]Your New Custom Title Here[/custom_html_title]

 function custom_html_title_shortcode($atts, $content = null) {
    add_filter('pre_get_document_title', function () use ($content) {
      return $content ?: get_the_title();
    }, 999);
  }
  add_shortcode('custom_html_title', 'custom_html_title_shortcode');
  