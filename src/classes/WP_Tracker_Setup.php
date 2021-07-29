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

    public static function initAdmin() {
    }

    public static function init() {
        if (is_admin()) {
            WP_Tracker_Front::register_homepage();
        } else {
            WP_Tracker_Track::track();
        }
        WP_Tracker_Widget::register_widget();
        return;
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