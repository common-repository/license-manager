<?php

/*
* @Author 		pickplugins
* Copyright: 	2015 pickplugins
*/

if ( ! defined('ABSPATH')) exit;  // if direct access 


class class_license_manager_settings  {
	
	public function __construct(){

		add_action( 'admin_menu', array( $this, 'admin_menu' ), 12 );
    }
	
	public function admin_menu() {
		
		add_submenu_page( 'edit.php?post_type=license', __( 'Settings', LICENSE_MANAGER_PP_TEXTDOMAIN ), __( 'Settings', LICENSE_MANAGER_PP_TEXTDOMAIN ), 'manage_options', 'settings', array( $this, 'settings' ) );

		
	}
	
	public function settings(){
		include( LICENSE_MANAGER_PP_PLUGIN_DIR. 'includes/menus/settings.php' );
	}	

	

	
	
	
	
	
	
} new class_license_manager_settings();

