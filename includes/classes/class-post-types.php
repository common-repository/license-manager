<?php

/*
* @Author 		pickplugins
* Copyright: 	pickplugins.com
*/

if ( ! defined('ABSPATH')) exit;  // if direct access 

class class_license_manager_post_types{
	
	public function __construct(){
		
		add_action( 'init', array( $this, 'license_manager_posttype_license' ), 0 );
		
	}
	
	public function license_manager_posttype_license(){
		if ( post_type_exists( "license" ))
		return;

		$singular  = __( 'License', LICENSE_MANAGER_PP_TEXTDOMAIN );
		$plural    = __( 'Licenses', LICENSE_MANAGER_PP_TEXTDOMAIN );
	 
	 
		register_post_type( "license",
			apply_filters( "register_post_type_license", array(
				'labels' => array(
					'name' 					=> $plural,
					'singular_name' 		=> $singular,
					'menu_name'             => __( 'License Manager', LICENSE_MANAGER_PP_TEXTDOMAIN ),
					'all_items'             => sprintf( __( 'All %s', LICENSE_MANAGER_PP_TEXTDOMAIN ), $plural ),
					'add_new' 				=> __( 'Add '.$singular, LICENSE_MANAGER_PP_TEXTDOMAIN ),
					'add_new_item' 			=> sprintf( __( 'Add %s', LICENSE_MANAGER_PP_TEXTDOMAIN ), $singular ),
					'edit' 					=> __( 'Edit', LICENSE_MANAGER_PP_TEXTDOMAIN ),
					'edit_item' 			=> sprintf( __( 'Edit %s', LICENSE_MANAGER_PP_TEXTDOMAIN ), $singular ),
					'new_item' 				=> sprintf( __( 'New %s', LICENSE_MANAGER_PP_TEXTDOMAIN ), $singular ),
					'view' 					=> sprintf( __( 'View %s', LICENSE_MANAGER_PP_TEXTDOMAIN ), $singular ),
					'view_item' 			=> sprintf( __( 'View %s', LICENSE_MANAGER_PP_TEXTDOMAIN ), $singular ),
					'search_items' 			=> sprintf( __( 'Search %s', LICENSE_MANAGER_PP_TEXTDOMAIN ), $plural ),
					'not_found' 			=> sprintf( __( 'No %s found', LICENSE_MANAGER_PP_TEXTDOMAIN ), $plural ),
					'not_found_in_trash' 	=> sprintf( __( 'No %s found in trash', LICENSE_MANAGER_PP_TEXTDOMAIN ), $plural ),
					'parent' 				=> sprintf( __( 'Parent %s', LICENSE_MANAGER_PP_TEXTDOMAIN ), $singular )
				),
				'description' => sprintf( __( 'This is where you can create and manage %s.', LICENSE_MANAGER_PP_TEXTDOMAIN ), $plural ),
				'public' 				=> true,
				'show_ui' 				=> true,
				'capability_type' 		=> 'post',
				'map_meta_cap'          => true,
				'publicly_queryable' 	=> true,
				'exclude_from_search' 	=> false,
				'hierarchical' 			=> false,
				'rewrite' 				=> true,
				'query_var' 			=> true,
				'supports' 				=> array('title','author','comments','custom-fields'),
				'show_in_nav_menus' 	=> false,
				//'taxonomies' => array('license_tags'),
				'menu_icon' => 'dashicons-editor-help',
			) )
		); 
			
			
			
	}
		

	
	
} 

new class_license_manager_post_types();