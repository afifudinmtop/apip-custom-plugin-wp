<?php
/*
Plugin Name: Apip Visitor Tracker
Description: Tracks visitor's IP, location, date, time, and visited page.
Version: 1.0
Author: Afifudin Ma'arif
*/

// Hook ke event 'wp_head' untuk menjalankan fungsi tracking ketika halaman dimuat
add_action('wp_head', 'track_visitor_info');

// Fungsi untuk mencatat informasi pengunjung
function track_visitor_info() {
    global $wpdb;
    
    // Mendapatkan IP address pengunjung
    $user_ip = $_SERVER['REMOTE_ADDR'];

    // Jika IP bukan localhost maka lakukan request ke IP-API
    if (!in_array($user_ip, array('127.0.0.1', '::1'))) {
        $api_url = 'http://ip-api.com/json/' . $user_ip;
        $response = wp_remote_get($api_url);
        
        // Periksa apakah response adalah array dan bukan error
        if (is_array($response) && !is_wp_error($response)) {
            $data = json_decode(wp_remote_retrieve_body($response), true);
            $user_location = $data['country'] . ', ' . $data['city'];
        } else {
            $user_location = 'Unknown';
        }
    } else {
        $user_location = 'Localhost';
    }

    // Mendapatkan informasi waktu dan halaman yang dikunjungi
    $visit_time = current_time('mysql');
    $visit_date = current_time('Y-m-d');
    $page_visited = esc_url(home_url($_SERVER['REQUEST_URI']));
    $user_logged_in = is_user_logged_in() ? 'Yes' : 'No';

    // Menyimpan data ke dalam tabel database
    $wpdb->insert(
        $wpdb->prefix . 'visitor_tracker',
        array(
            'ip_address' => $user_ip,
            'location' => $user_location,
            'visit_time' => $visit_time,
            'visit_date' => $visit_date,
            'page_visited' => $page_visited,
            'user_logged_in' => $user_logged_in
        )
    );
}

// Hook untuk membuat tabel ketika plugin diaktifkan
register_activation_hook(__FILE__, 'create_visitor_tracker_table');
function create_visitor_tracker_table() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'visitor_tracker';
    $charset_collate = $wpdb->get_charset_collate();
    
    $sql = "CREATE TABLE $table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        ip_address VARCHAR(100) NOT NULL,
        location VARCHAR(100) NOT NULL,
        visit_time DATETIME NOT NULL,
        visit_date DATE NOT NULL,
        page_visited TEXT NOT NULL,
        user_logged_in VARCHAR(3) NOT NULL,
        PRIMARY KEY (id)
    ) $charset_collate;";
    
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}

// Hook untuk menghapus tabel ketika plugin dinonaktifkan
register_deactivation_hook(__FILE__, 'drop_visitor_tracker_table');
function drop_visitor_tracker_table() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'visitor_tracker';
    $sql = "DROP TABLE IF EXISTS $table_name;";
    $wpdb->query($sql);
}
