<?php

/*
* @Author 		pickplugins
* Copyright: 	2015 pickplugins
*/

if ( ! defined('ABSPATH')) exit;  // if direct access 

class class_license_manager_clients_license_edit{
	
    public function __construct(){
		add_shortcode( 'license_manager_license_edit', array( $this, 'license_manager_license_edit_display' ) );
   	}	
	
	public function license_manager_license_edit_display($atts, $content = null ) {
			
		$atts = shortcode_atts( array(
					
		), $atts);

		ob_start();
		include( LICENSE_MANAGER_PP_PLUGIN_DIR . 'templates/license-edit/license-edit.php');
		return ob_get_clean();
	}
	
} new class_license_manager_clients_license_edit();