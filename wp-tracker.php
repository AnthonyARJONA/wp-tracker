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

define( 'WP_TRACKER_VERSION', '1.0' );
define( 'WP_TRACKER_REQUIRED_PHP_VERSION', '7.2' );
define( 'WP_TRACKER_PATH', trailingslashit( dirname(  __FILE__ ) ) );

//block direct access to ext
if(!defined( 'ABSPATH')) {
    exit;
}

//add message in admin panel if php version is invalid
if (!version_compare(phpversion(), WP_TRACKER_REQUIRED_PHP_VERSION, ">=")) {
    if ( is_admin() ) {
        function wp_tracker_php_notice() {
            printf( '<div class="notice notice-error"><p>WP Tracker plugin requires at least PHP version ' . WP_TRACKER_REQUIRED_PHP_VERSION . ', but installed is version ' . PHP_VERSION . '.</p></div>');
        }
        add_action('admin_notices', 'wp_tracker_php_notice');
    }
    return;
}

//autoloader
function wp_tracker_autoloader( $class_called ) {

    $classes = [
        'WP_Tracker_setup',
    ];

    if ( in_array($class_called, $classes,true)) {
        require_once('src/classes/' . strtolower($class_called) . '.php');
    }
}
spl_autoload_register('wp_tracker_autoloader');

// uninstall hook
register_uninstall_hook(
    __FILE__,
    array(
        'WP_Tracker_setup',
        'uninstall',
    )
);