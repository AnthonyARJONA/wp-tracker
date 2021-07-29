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
        echo '<h1>Statistique du site</h1>';
        echo '<div class="col-xs-6 col-sm-6 col-md-6 col-lg-4" style="padding:0;">
			<div class="wpvf_info wpvf_info_blue wpvf_info_mobile">
				<span class="wpvf_info_title_mobile">'.$db->getTodayVisit() .'</span><br>
			       <br>
				<br>
				Nombre de visite Aujourd\'hui<br>
			</div>
		</div>';
        echo '<div class="col-xs-6 col-sm-6 col-md-6 col-lg-4" style="padding:0;">
			<div class="wpvf_info wpvf_info_pink">
				<span class="wpvf_info_title_mobile">'.$db->getVisitLastSevenDays() .'</span><br>
			       <br>
				<br>
				Nombre de visite sur 7 jour<br>
			</div>
		</div>';
        echo '<div class="col-xs-6 col-sm-6 col-md-6 col-lg-4" style="padding:0;">
			<div class="wpvf_info wpvf_info_red">
				<span class="wpvf_info_title_mobile">'.$db->getUniqueVisitThisMonth() .'</span><br>
			       <br>
				<br>
				Nombre de visite sur un mois<br>
			</div>
		</div>';
        echo '<div class="col-xs-6 col-sm-6 col-md-6 col-lg-4" style="padding:0;">
			<div class="wpvf_info wpvf_info_darkblue">
				<span class="wpvf_info_title_mobile">'.$db->getTodayVisitedPage() .'</span><br>
			       <br>
				<br>
				Nombre de page vue aujourd\'hui<br>
			</div>
		</div>';
        echo '<div class="col-xs-6 col-sm-6 col-md-6 col-lg-4" style="padding:0;">
			<div class="wpvf_info wpvf_info_darkpink">
				<span class="wpvf_info_title_mobile">'.$db->getVisitedPageLastSevenDays() .'</span><br>
			       <br>
				<br>
				Nombre de page vue sur 7 jour<br>
			</div>
		</div>';
        echo '<div class="col-xs-6 col-sm-6 col-md-6 col-lg-4" style="padding:0;">
			<div class="wpvf_info wpvf_info_darkred">
				<span class="wpvf_info_title_mobile">'.$db->getVisitedPageThisMonth() .'</span><br>
			       <br>
				<br>
				Nombre de page vue sur un mois<br>
			</div>
		</div>';
        echo  '<div class="col-xs-6 col-sm-6 col-md-6 col-lg-3" style="padding:0;">
			   <div class="wpvf_info wpvf_info_green">
				<span class="wpvf_info_title_mobile"> '. $db->getMaxVisit() .'</span><br>
                 <br>
                Nombre record de visite sur le site
                 </div>
                </div>';

    }

}