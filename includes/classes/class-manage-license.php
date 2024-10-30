<?php

/*
* @Author 		pickplugins
* Copyright: 	2015 pickplugins
*/

if ( ! defined('ABSPATH')) exit;  // if direct access 


class class_license_manager_manage_license{
	
	public function __construct() {

		add_action('init', array( $this, 'activation_license' ));


	}
	
	
	public function create_license($args){

		$response = array();

		$license_key = isset($args['license_key']) ? sanitize_text_field($args['license_key']) : '';
		$license_status = isset($args['license_status']) ? sanitize_text_field($args['license_status']) : '';
		$domain_count = isset($args['domain_count']) ? sanitize_text_field($args['domain_count']) : '';
		$domains_list = isset($args['domains_list']) ? stripslashes_deep($args['domains_list']) : '';
		$license_email = isset($args['license_email']) ? sanitize_email($args['license_email']) : '';
		$user_id = isset($args['user_id']) ? (int) sanitize_text_field($args['user_id']) : '';
		$order_id = isset($args['order_id']) ? (int) sanitize_text_field($args['order_id']) : '';
		$product_id = isset($args['product_id']) ? (int) sanitize_text_field($args['product_id']) : '';
		$variation_id = isset($args['variation_id']) ? (int) sanitize_text_field($args['variation_id']) : '';
		$date_created = isset($args['date_created']) ? sanitize_text_field($args['date_created']) : '';
		$date_renewed = isset($args['date_renewed']) ? sanitize_text_field($args['date_renewed']) : '';
		$date_expiry = isset($args['date_expiry']) ? sanitize_text_field($args['date_expiry']) : '';
		$meta_data = isset($args['meta_data']) ? stripslashes_deep($args['meta_data']) : array();

		
		//echo '<pre>'.var_export( $args, true).'</pre>';
		//echo '<pre>'.var_export( $license_key, true).'</pre>';
		
		
		if(!empty($license_key)):
		
			$post_data = array(
				'post_author' => $user_id,
				'post_status' => 'publish',
				'post_type' => 'license',
			);
			
			$post_id = wp_insert_post($post_data);
			
			
			$post_data = array(
			  'ID'           => $post_id,
			  'post_title'   => '#'.$post_id,
			 // 'post_content' => 'This is the updated content.',
			);
			
			// Update the post into the database
			wp_update_post( $post_data );	
	
			update_post_meta( $post_id, 'license_key', $license_key );
			update_post_meta( $post_id, 'license_status', $license_status );
			update_post_meta( $post_id, 'domain_count', $domain_count );
			update_post_meta( $post_id, 'domains_list', $domains_list );
			update_post_meta( $post_id, 'license_email', $license_email );
			update_post_meta( $post_id, 'user_id', $user_id );
			update_post_meta( $post_id, 'order_id', $order_id );
			update_post_meta( $post_id, 'product_id', $product_id );
			update_post_meta( $post_id, 'variation_id', $variation_id );
			update_post_meta( $post_id, 'date_created', $date_created );
			update_post_meta( $post_id, 'date_renewed', $date_renewed );	
			update_post_meta( $post_id, 'date_expiry', $date_expiry );	
	
			if(!empty($meta_data))
			foreach($meta_data as $meta_key=>$meta_value){
				
				update_post_meta( $post_id, $meta_key, $meta_value );
				}
							
			$response['message'] = __('License created.', LICENSE_MANAGER_PP_TEXTDOMAIN);
			$response['status'] = 'success';

		else:
			$response['message'] = __('License create failed.', LICENSE_MANAGER_PP_TEXTDOMAIN);
			$response['status'] = 'fail';	
		
		endif;
		

			
		return $response;
			
		do_action('license_manager_create_license');
		
		}

		

		
		
public function activation_license(){

	if (isset($_REQUEST['license_manager_action']) && trim($_REQUEST['license_manager_action']) == '_activate') {

		$class_license_manager_functions = new class_license_manager_functions();

		$fields = array();

		//$fields['secret_key'] = strip_tags($_REQUEST['secret_key']);


		//$fields['admin_email'] = strip_tags(sanitize_email($_REQUEST['admin_email']));
		$fields['license_key'] = strip_tags(sanitize_text_field($_REQUEST['license_key']));
		$fields['registered_domain'] = strip_tags(sanitize_text_field($_REQUEST['registered_domain']));


		$meta_query[] = array(
			'key' => 'license_key',
			'value' => sanitize_text_field($fields['license_key']),
			'compare' => '=',
			);


		$wp_query = new WP_Query(
			array (
				'post_type' => 'license',
				'post_status' => 'publish',
				'orderby' => 'date',
				'meta_query' => $meta_query,
				'order' => 'DESC',
				'posts_per_page' => -1,


				) );
		
		if ($wp_query->have_posts()):

			$args['license_found'] = 'yes';

			while ( $wp_query->have_posts() ) : $wp_query->the_post();

				$license_status = get_post_meta(get_the_ID(), 'license_status', true);
				$date_expiry = get_post_meta(get_the_ID(), 'date_expiry', true);
				$date_created = get_post_meta(get_the_ID(), 'date_created', true);
				$license_key = get_post_meta(get_the_ID(), 'license_key', true);
				$domains_list = get_post_meta(get_the_ID(), 'domains_list', true);
				$domain_count 	= get_post_meta( get_the_ID(), 'domain_count', true);
				$days_remaining = $class_license_manager_functions->days_remaining($date_created);


				if(!empty($domains_list) && is_array($domains_list)){
					$domains_list_count = count($domains_list);

					if (!in_array($fields['registered_domain'], $domains_list) && $domain_count > $domains_list_count) {
						$domains_list = array_merge($domains_list, array($fields['registered_domain']));
						update_post_meta(get_the_ID(), 'domains_list', $domains_list );
						$args['mgs'] = 'License activated';
					}
					else{
						$args['mgs'] = 'Sorry, Max domain limit reached.';

					}


				}
				else{

					$domains_list = array($fields['registered_domain']);
					update_post_meta(get_the_ID(), 'domains_list', $domains_list );
					$args['mgs'] = 'License activated';
				}


				$args['license_status'] = $license_status;
				$args['date_expiry'] = $date_expiry;
				$args['date_created'] = $date_created;
				$args['license_key'] = $license_key;
				//$args['days_remaining'] = $days_remaining;



			endwhile;

		wp_reset_query();
		else:
		$args['license_found'] = 'no';
		//echo __('No license found', LICENSE_MANAGER_PP_TEXTDOMAIN);
		//update_option('plugin_slug_license_int', 'no' );
		endif;
		
		//$args['registered_domain'] = $fields['registered_domain'];
		//$args['admin_email'] = $fields['admin_email'];
		//$args['result'] = 'success';
		//$args['message'] = 'License successfully created';
		//$args['key'] = $fields['license_key'];

		//$args = (array('result' => 'success', 'message' => 'License successfully created', 'key' => $fields['license_key']));






		echo json_encode($args);
		exit(0);
		}

		//echo 'hello';

	}



}

new class_license_manager_manage_license();