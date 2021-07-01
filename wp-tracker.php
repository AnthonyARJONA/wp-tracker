<?php
/**
 * Plugin Name: WP Tracker
 * Plugin URI: https://github.com/Mimso/wp-tracker
 * Description: Detailed web tracker of your website
 * Version: 1.0
 * Author: Renoux Oceane, Arjona Anthony, Millot Nils
 * Author URI: https://github.com/Mimso
 * License: ''
 * Text Domain: wp-tracker
 */

define( 'TRACKER_VERSION', '1.0' );
define( 'REQUIRED_PHP_VERSION', '7.2' );

//block direct access to ext
if(!defined( 'ABSPATH')) {
    exit;
}

//add message in admin panel if php version is invalid
if (!version_compare(phpversion(), REQUIRED_PHP_VERSION, "<=")) {
    if ( is_admin() ) {
        function wp_tracker_php_notice() {
            printf( '<div class="notice notice-error"><p>WP Tracker plugin requires at least PHP version ' . REQUIRED_PHP_VERSION . ', but installed is version ' . PHP_VERSION . '.</p></div>');
        }
        add_action('admin_notices', 'wp_tracker_php_notice');
    }
    return;
}