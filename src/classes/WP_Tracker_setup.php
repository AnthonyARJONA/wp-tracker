<?php

//block direct access to ext
if(!defined( 'ABSPATH')) {
    exit;
}

class WP_Tracker_setup
{

    public static function init() {
        $database = new WP_Tracker_database();
        $database->init();
    }

    public function uninstall() {}

}