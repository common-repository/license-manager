<?php
// Template Name: Page - Download
if ( ! defined('ABSPATH')) exit;  // if direct access

if ( isset( $_GET['key'] ) ):

	$license_key = $_GET['key'];

	$meta_query[] = array(
		'key' => 'license_key',
		'value' => $license_key,
		'compare' => 'LIKE',
	);

	$wp_query = new WP_Query(
		array (
			'post_type' => 'license',
			'post_status' => 'publish',
			'meta_query' => $meta_query,
			'order' => 'DESC',
			'posts_per_page' => -1,


		) );

	$license_post_data = array();

	if ( $wp_query->have_posts() ) :
		$count = 0;
		while ( $wp_query->have_posts() ) : $wp_query->the_post();
			$post_id = get_the_id();

			$license_post_data[$count]['license_post_id']   = get_the_id();
			$license_post_data[$count]['license_status']    = get_post_meta($post_id, 'license_status', true);
			$license_post_data[$count]['domain_count']      = get_post_meta($post_id, 'domain_count', true);
			$license_post_data[$count]['order_id']          = get_post_meta($post_id, 'order_id', true);
			$license_post_data[$count]['product_id']        = get_post_meta($post_id, 'product_id', true);
			$license_post_data[$count]['variation_id']      = get_post_meta($post_id, 'variation_id', true);
			$license_post_data[$count]['date_expiry']       = get_post_meta($post_id, 'date_expiry', true);
			$license_post_data[$count]['user_id']           = get_post_meta($post_id, 'user_id', true);

			$count++;
		endwhile;
	endif;



	if(!empty($license_post_data[0])){
		//var_dump($license_post_data[0]);

		$license_status     = isset($license_post_data[0]['license_status'])? $license_post_data[0]['license_status'] : '';

		if($license_status == 'active'):

			$upload_dir   = wp_upload_dir();

			$upload_dir_url = $upload_dir['url'];
			$upload_dir_path = $upload_dir['path'];
			$upload_dir_baseurl = $upload_dir['baseurl'];
			$upload_dir_basedir = $upload_dir['basedir'];


			$product_id         = isset($license_post_data[0]['product_id'])? $license_post_data[0]['product_id'] : '';
			$license_manager_autoupdate_plugin_zip_path = get_post_meta( $product_id, 'license_manager_autoupdate_plugin_zip_path', true );
			$license_manager_autoupdate_plugin_slug = get_post_meta( $product_id, 'license_manager_autoupdate_plugin_slug', true );

			//var_dump($license_manager_autoupdate_plugin_zip_path);
			//var_dump($upload_dir_basedir);

			$file_path = $upload_dir_basedir.'/'.$license_manager_autoupdate_plugin_zip_path;


			if ( file_exists( $file_path ) ) {

				//var_dump($file_path);
				$file = file_get_contents( $file_path );

				header( "Content-type: application/zip" );
				header( "Content-Disposition: attachment; filename=\"" . str_replace( " ", "_", $license_manager_autoupdate_plugin_slug.'.zip' ) . "\"" );

				echo $file;
			}

		endif;


	}


endif;