<?php

//block direct access to ext
if(!defined( 'ABSPATH')) {
    exit;
}

class WP_Tracker_Helper
{

    public static function asset($filename) {
        return plugins_url('WP-Tracker/src/resources/' . $filename);
    }

}