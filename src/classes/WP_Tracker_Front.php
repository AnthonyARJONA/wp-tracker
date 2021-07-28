<?php

//block direct access to ext
if(!defined( 'ABSPATH')) {
    exit;
}

class WP_Tracker_Front {

    public static function register_homepage() {
        function wp_tracker_autoloader_register_menu() {
            add_menu_page('WP Tracker', 'WP Tracker', WP_Tracker_Setup::settings('permission'), 'wp_tracker', array(WP_Tracker_Front::class, '_wordpress_tracker_homepage'), WP_Tracker_Helper::getMenuIcon(), 75);
        }
        add_action('admin_menu','wp_tracker_autoloader_register_menu');
    }

    public function _wordpress_tracker_homepage() {
        echo '<pre>';
        $db = new WP_Tracker_Database();
        var_dump($db->insertVisitor());
        var_dump($db->getVisitedPageThisMonth());
        echo '</pre>';
    }

}