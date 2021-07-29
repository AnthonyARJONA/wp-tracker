<?php

//block direct access to ext
if(!defined( 'ABSPATH')) {
    exit;
}

class WP_Tracker_Track
{
    public static function track() {
        add_action('wp_head', 'wordpress_tracker_track', 9);
        function wordpress_tracker_track() {
            //check if current page is not in page_exclude array
            if(!in_array(explode('/', $_SERVER["REQUEST_URI"])[1], WP_Tracker_Setup::settings('page_exclude'))) {
                $database = new WP_Tracker_Database();
                $database->insertVisitor();
            }
        }
    }

    public static function getSlug() {
        $current_url = get_permalink( get_the_ID() );
        if( is_category() ) $current_url = get_category_link( get_query_var( 'cat' ) );
        return $current_url;
    }

}