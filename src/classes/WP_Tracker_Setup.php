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
        WP_Tracker_Front::register_homepage();
    }

    public static function plugin_activated(){
        $database = new WP_Tracker_Database();
        $database->createQueryBuilder();
    }

    public static function plugin_deactivated(){
        $database = new WP_Tracker_Database();
        $database->deleteQueryBuilder();
    }


}