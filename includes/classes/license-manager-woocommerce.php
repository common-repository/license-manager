<?php
/*
Plugin Name: License Manager - Woocommerce
Plugin URI: http://pickplugins.com
Description: Awesome Question and Answer.
Version: 1.0.28
Text Domain: question-answer
Author: pickplugins
Author URI: http://pickplugins.com
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

if ( ! defined('ABSPATH')) exit;  // if direct access 


class LicenseManagerWoocommerce{
	
	public function __construct(){
	
		
		add_action( 'woocommerce_order_status_completed', array( $this, '_on_order_complete' ) );
		add_action( 'woocommerce_order_status_cancelled', array( $this, '_order_cancelled' ) );
		add_action( 'woocommerce_order_status_refunded', array( $this, '_order_cancelled' ) );
		
		add_shortcode( '_on_order_complete', array( $this, '_on_order_complete' ) );		
		
	}
	
	
	public function _on_order_complete( $order_id ) {
		
		$class_license_manager_manage_license = new class_license_manager_manage_license();
		
		//$order_id=60;
		$order           = new WC_Order( $order_id );
		
		$user_id         = $order->user_id;
		$items           = $order->get_items();
		$first_name      = $order->billing_first_name;
		$last_name       = $order->billing_last_name;
		$billing_company = $order->billing_company;
		$billing_email   = $order->billing_email;
		
		$user_info       = get_user_by( 'id', $user_id );
		$user_email      = $user_info->user_email;
		
		//echo '<pre>'.var_export( $order_id, true).'</pre>';
		
		
		if ( $user_id == 0 ) {
		} else {
			foreach ( $items as $item ) {
				$product_id           = $item[ 'product_id' ];
				$product_variation_id = $item[ 'variation_id' ];

				$product = wc_get_product($product_id);
				$is_variable = $product->is_type('variable');

				if($is_variable){
					$args['variation_id'] = $product_variation_id;
					$lm_domain_count = get_post_meta($product_variation_id, 'lm_domain_count', true);
					$args['domain_count'] = $lm_domain_count;
                }
                else{
	                $lm_domain_count = get_post_meta($product_id, 'lm_domain_count', true);
	                $args['domain_count'] = $lm_domain_count;
	                $args['variation_id'] = '';
                }



				$args['license_key'] = md5(time());
				$args['license_status'] = 'active';

				$args['domains_list'] = '';
				$args['license_email'] = $user_email;
				$args['user_id'] = $user_id;
				$args['order_id'] = $order_id;
				$args['product_id'] = $product_id;

				$args['date_created'] = date( 'Y-m-d' );
				$args['date_renewed'] = date( 'Y-m-d' );
				$args['date_expiry'] = date( 'Y-m-d', strtotime( '+1 years' ) );
				$args['meta_data'] = array();
				 
				 
				//echo '<pre>'.var_export( $args, true).'</pre>';
				 
				$create_license = $class_license_manager_manage_license->create_license($args);

				//echo '<pre>'.var_export( $create_license, true).'</pre>';	
			}
		}
	}
	
	
	
	
	
	
	public function _order_cancelled( $order_id ){
		
		$order         = new WC_Order( $order_id );
		$user_id       = $order->user_id;
		$items         = $order->get_items();
		$billing_email = $order->billing_email;
		
		if ( $user_id == 0 ) {
			return;
		} else {
			
			foreach ( $items as $item ){
				
				$product_id           = $item[ 'product_id' ];
				$product_variation_id = $item[ 'variation_id' ];
				
				$meta_query = array();
				
				$meta_query[] = array(
			
									'key' => 'order_id',
									'value' => $order_id,
									);
				
				$meta_query[] = array(
			
									'key' => 'product_id',
									'value' => $product_id,
									);				
				
				
				$query_args = array (
									'post_type' => 'license',
									'post_status' => 'publish',
									'posts_per_page' => -1,
									'meta_query' => $meta_query,
									
									);
				

				

				
				
			}
		}
	}
	
	
} 

new LicenseManagerWoocommerce();







add_filter( 'woocommerce_product_data_tabs', 'woocommerce_product_data_tab_product_designer', 10 );

function woocommerce_product_data_tab_product_designer( $product_data_tabs ) {
	$product_data_tabs['LicenseManagerWoo'] = array(
		'label' => __( 'License Manager', 'LicenseManagerWoo' ),
		'target' => 'LicenseManagerWoo',
	);
	return $product_data_tabs;
}


add_action( 'woocommerce_product_data_panels', 'woocommerce_product_data_tab_product_designer_fields' );

function woocommerce_product_data_tab_product_designer_fields() {
	global $woocommerce, $post;

	$lm_domain_count = get_post_meta( $post->ID, 'lm_domain_count', true );
	$product = wc_get_product($post->ID);
	$is_variable = $product->is_type('variable');

	if($is_variable):

		?>
		<div id="LicenseManagerWoo" class="panel woocommerce_options_panel">
			<p class="form-field lm_domain_count_field ">
				Please check variations tabs for choosing product designer templates.
			</p>
		</div>
		<?php
	else:
		?>

		<div id="LicenseManagerWoo" class="panel woocommerce_options_panel">
			<?php

			woocommerce_wp_text_input( array(
					'id'      => 'lm_domain_count',
					'label'   => __( 'Maximum Domain Count', 'LicenseManagerWoo' ),
					'value'   => $lm_domain_count,
				)
			);
			?>
		</div>
		<?php

	endif;
	?>

	<?php


}


add_action( 'woocommerce_process_product_meta', 'woocommerce_product_data_tab_product_designer_save' );
function woocommerce_product_data_tab_product_designer_save( $post_id ){
	// This is the case to save custom field data of checkbox. You have to do it as per your custom fields
	$pd_template = isset( $_POST['lm_domain_count'] ) ? $_POST['lm_domain_count'] : '';
	update_post_meta( $post_id, 'lm_domain_count', $pd_template );
}








// Add Variation Settings
add_action( 'woocommerce_product_after_variable_attributes', 'product_designer_variation_settings_fields', 10, 3 );
// Save Variation Settings
add_action( 'woocommerce_save_product_variation', 'save_product_designer_variation_settings_fields', 10, 2 );
/**
 * Create new fields for variations
 *
 */
function product_designer_variation_settings_fields( $loop, $variation_data, $variation ) {

	// Select
	woocommerce_wp_text_input(
		array(
			'id'          => 'lm_domain_count[' . $variation->ID . ']',
			'label'       => __( 'License Manager: Maximum Domain Count', 'woocommerce' ),
			//'description' => __( 'Choose a value.', 'woocommerce' ),
			'value'       => get_post_meta( $variation->ID, 'lm_domain_count', true ),

		)
	);


}
/**
 * Save new fields for variations
 *
 */
function save_product_designer_variation_settings_fields( $post_id ) {
	// Text Field


	// Select
	$select = $_POST['lm_domain_count'][ $post_id ];
	if( ! empty( $select ) ) {
		update_post_meta( $post_id, 'lm_domain_count', esc_attr( $select ) );
	}

}











