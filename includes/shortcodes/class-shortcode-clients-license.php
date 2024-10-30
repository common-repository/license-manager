<?php

/*
* @Author 		pickplugins
* Copyright: 	2015 pickplugins
*/

if ( ! defined('ABSPATH')) exit;  // if direct access 

class class_license_manager_shortcode_clients_license{
	
    public function __construct(){
		add_shortcode( 'license_manager_license_list', array( $this, 'license_list_display' ) );
   	}	
	
	public function license_list_display($atts, $content = null ) {
			
		$atts = shortcode_atts( array(
					
		), $atts);

		ob_start();
		include( LICENSE_MANAGER_PP_PLUGIN_DIR . 'templates/license-list/license-list.php');
		return ob_get_clean();
	}
	
} new class_license_manager_shortcode_clients_license();