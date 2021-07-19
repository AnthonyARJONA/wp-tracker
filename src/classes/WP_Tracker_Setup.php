<?php

//block direct access to ext
if(!defined( 'ABSPATH')) {
    exit;
}

class WP_Tracker_Setup
{
    public static function settings($key = null) {
        $settings =  [
            'permission' => 'manage_options',
            'page_exclude' => [
               'wp-admin',
           ],
        ];

        if(!is_null($key)) {
            if (array_key_exists($key, $settings)) {
                return $settings[$key];
            }
        }
        return $settings;
    }

    public static function init() {
        self::register_menu();
    }

    public static function plugin_activated(){
        $database = new WP_Tracker_database();
        $database->create();
    }

    public static function plugin_deactivated(){
        $database = new WP_Tracker_database();
        $database->remove();
    }

    public static function register_menu() {
        function wp_tracker_autoloader_register_menu() {
            add_menu_page('WP Tracker', 'WP Tracker', WP_Tracker_Setup::settings('permission'), 'wp_tracker', '_wp_tracker_homepage', WP_Tracker_Helper::getMenuIcon(), 75);
        }
        function _wp_tracker_homepage(){
            echo '<pre>';
            $slug = explode('/', $_SERVER["REQUEST_URI"])[1];
            var_dump($slug);
            var_dump(WP_Tracker_Api::getIpInfo());
            echo '</pre>';
        }
        add_action('admin_menu', 'wp_tracker_autoloader_register_menu');
    }
}