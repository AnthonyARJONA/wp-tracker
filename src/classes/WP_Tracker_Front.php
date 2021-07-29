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
        $db = new WP_Tracker_Database();
        echo '<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous"><style>div.wpvf_info {
                margin:5px;
                background-color: #578dbd;
                padding: 1em 0.5em 1em 0.5em;
                border-left:8px solid #49779f;
                -webkit-box-shadow: 1px 2px 2px 0 rgba(0,0,0,0.15);
                   -moz-box-shadow: 1px 2px 2px 0 rgba(0,0,0,0.15);
                        box-shadow: 1px 2px 2px 0 rgba(0,0,0,0.15);
                font-weight:normal;
                color:#fff;
                text-align:right;
            }
            
            div.wpvf_info_blue {
                background-color: #578dbd;
                border-left:8px solid #49779f;
            }
            
            div.wpvf_info_darkblue {
                background-color: #49779f;
                border-left:8px solid #3e6486;
            }
            
            div.wpvf_info_darkerblue {
                background-color: #3e6486;
                border-left:8px solid #345471;
            }
            
            div.wpvf_info_pink {
                background-color: #8775a6;
                border-left:8px solid #6c5e85;
            }
            
            div.wpvf_info_darkpink {
                background-color: #6c5e85;
                border-left:8px solid #5b4f70;
            }
            
            div.wpvf_info_darkerpink {
                background-color: #5b4f70;
                border-left:8px solid #4d435e;
            }
            
            div.wpvf_info_green {
                background-color: #43b5ad;
                border-left:8px solid #36918b;
            }
            
            div.wpvf_info_red {
                background-color: #e25a59;
                border-left:8px solid #b64847;
            }
            
            div.wpvf_info_darkred {
                background-color: #b64847;
                border-left:8px solid #993d3c;
            }
            
            .wpvf_info_title {
                font-size: 28px;
                color:#fff;
            }</style>';
        echo '<h1 style="margin-top: 1em; margin-bottom: 1em">Statistique du site</h1>';
        echo '<div class="row" style="margin: 0 1em"><div class="col-xs-6 col-sm-6 col-md-6 col-lg-4" style="padding:0;">
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