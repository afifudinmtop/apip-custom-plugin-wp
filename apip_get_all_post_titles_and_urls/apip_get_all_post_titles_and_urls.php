<?php
/**
 * Plugin Name: Apip List Post Titles and URLs
 * Description: Retrieves a list of all post titles and their URLs.
 * Version: 1.0
 * Author: Afifudin Ma'arif
 */

//  Anda sekarang dapat menggunakan shortcode [list_post_titles_urls] di post atau halaman mana pun untuk menampilkan daftar judul dan URL.


 function get_all_post_titles_and_urls() {
    // WP_Query arguments
    $args = array(
      'post_type'              => 'post',
      'post_status'            => 'publish',
      'posts_per_page'         => -1, // Retrieve all posts
      'ignore_sticky_posts'    => true,
    );
  
    // The Query
    $query = new WP_Query($args);
    $post_list = array();
  
    // The Loop
    if ($query->have_posts()) {
      while ($query->have_posts()) {
        $query->the_post();
        $post_list[] = array(
          'title' => get_the_title(),
          'url'   => get_permalink()
        );
      }
    }
  
    // Restore original Post Data
    wp_reset_postdata();
  
    return $post_list;
  }
  
  // Function to display the list (optional)
  function display_post_titles_and_urls() {
    $posts = get_all_post_titles_and_urls();
    if (!empty($posts)) {
      echo '<ul>';
      foreach ($posts as $post) {
        echo '<li><a href="' . esc_url($post['url']) . '">' . esc_html($post['title']) . '</a></li>';
      }
      echo '</ul>';
    }
  }
  
  // Shortcode to output the list
  function post_titles_urls_shortcode() {
    ob_start();
    display_post_titles_and_urls();
    return ob_get_clean();
  }
  add_shortcode('list_post_titles_urls', 'post_titles_urls_shortcode');
  