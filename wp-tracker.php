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
        'WP_Tracker_Helper',
        'WP_Tracker_database',
        'WP_Tracker_Curl',
        'WP_Tracker_Api',
        'WP_Tracker_Client',
    ];

    if ( in_array($class_called, $classes,true)) {
        require_once(WP_TRACKER_PATH . 'src/classes/' . $class_called . '.php');
    }
}
spl_autoload_register('wp_tracker_autoloader');


function wp_tracker_autoloader_register_menu() {
    add_menu_page('WP Tracker', 'WP Tracker', 'manage_options', 'wp_tracker_homepage', '_wp_tracker_homepage', 'data:image/svg+xml;base64,' . base64_encode('<svg fill="#f0f0f1" width="2048" height="1792" viewBox="0 0 2048 1792" xmlns="http://www.w3.org/2000/svg"><path d="M640 896v512h-256v-512h256zm384-512v1024h-256v-1024h256zm1024 1152v128h-2048v-1536h128v1408h1920zm-640-896v768h-256v-768h256zm384-384v1152h-256v-1152h256z"/></svg>'), 75);
}
add_action('admin_menu', 'wp_tracker_autoloader_register_menu');

function _wp_tracker_homepage(){
    var_dump(WP_Tracker_Api::getIpInfo());
}

if (is_admin()) {
    WP_Tracker_setup::init();
    return;
}


// uninstall hook
register_uninstall_hook(
    __FILE__,
    array(
        'WP_Tracker_setup',
        'uninstall',
    )
);