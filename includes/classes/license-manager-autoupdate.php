<?php


if ( ! defined('ABSPATH')) exit;  // if direct access 


class LicenseManagerAutoupdate{
	
	public function __construct(){
		add_action('init', array( $this, 'check_update' ));


		
	}




	function check_update (){

	   //var_dump('Hello');

		if ( isset( $_POST['_action'] ) ):

            $license_key = $_POST['license_key'];

			update_option('license_post_data_test', $license_key.'-'.time());


			$meta_query[] = array(

				'key' => 'license_key',
				'value' => $license_key,
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


				while ( $wp_query->have_posts() ) : $wp_query->the_post();
                    $post_id = get_the_id();

                    $license_post_data['license_post_id'] = get_the_id();
                    $license_post_data['license_status'] = get_post_meta($post_id, 'license_status', true);
                    $license_post_data['domain_count'] = get_post_meta($post_id, 'domain_count', true);
                    $license_post_data['order_id'] = get_post_meta($post_id, 'order_id', true);
					$license_post_data['product_id'] = get_post_meta($post_id, 'product_id', true);
                    $license_post_data['variation_id'] = get_post_meta($post_id, 'variation_id', true);
                    $license_post_data['date_expiry'] = get_post_meta($post_id, 'date_expiry', true);
                    $license_post_data['user_id'] = get_post_meta($post_id, 'user_id', true);

				endwhile;

            endif;





            if(!empty($license_post_data)):

	            //update_option('license_post_data', $license_post_data);

	            $product_id = $license_post_data['product_id'];

	            $license_manager_autoupdate_enable = get_post_meta( $product_id, 'license_manager_autoupdate_enable', true );
	            $license_manager_autoupdate_plugin_requires = get_post_meta( $product_id, 'license_manager_autoupdate_plugin_requires', true );
	            $license_manager_autoupdate_plugin_tested = get_post_meta( $product_id, 'license_manager_autoupdate_plugin_tested', true );
	            $license_manager_autoupdate_plugin_last_updated = get_post_meta( $product_id, 'license_manager_autoupdate_plugin_last_updated', true );
	            $license_manager_autoupdate_plugin_downloaded = get_post_meta( $product_id, 'license_manager_autoupdate_plugin_downloaded', true );
	            $license_manager_autoupdate_plugin_download_link = get_post_meta( $product_id, 'license_manager_autoupdate_plugin_download_link', true );

	            $license_manager_autoupdate_plugin_name = get_post_meta( $product_id, 'license_manager_autoupdate_plugin_name', true );
	            $license_manager_autoupdate_plugin_slug = get_post_meta( $product_id, 'license_manager_autoupdate_plugin_slug', true );
	            $license_manager_autoupdate_plugin_latest_version = get_post_meta( $product_id, 'license_manager_autoupdate_plugin_latest_version', true );
	            $license_manager_autoupdate_plugin_url = get_post_meta( $product_id, 'license_manager_autoupdate_plugin_url', true );
	            $license_manager_autoupdate_plugin_description = get_post_meta( $product_id, 'license_manager_autoupdate_plugin_description', true );
	            $license_manager_autoupdate_plugin_changelog = get_post_meta( $product_id, 'license_manager_autoupdate_plugin_changelog', true );


	           //@$WC_Download_Handler = new WC_Download_Handler();
	            //@$WC_Download_Handler::download( $product->get_file_download_path( $_download_id ), $product_id );
	            //@$WC_Download_Handler::download_file_force( 'http://192.168.0.90/themes-dev/wp-content/uploads/woocommerce_uploads/2018/07/logo-collection-1.9.zip', 'logo-collection-1.9' );



	            //set up the properties common to both requests
	            $obj = new stdClass();
	            $obj->slug = $license_manager_autoupdate_plugin_slug.'.php';
	            $obj->name = $license_manager_autoupdate_plugin_name;
	            $obj->plugin_name = $license_manager_autoupdate_plugin_slug.'.php';
	            $obj->new_version = $license_manager_autoupdate_plugin_latest_version;
	            // the url for the plugin homepage
	            $obj->url = $license_manager_autoupdate_plugin_url;
	            //the download location for the plugin zip file (can be any internet host)
	            $obj->package = LICENSE_MANAGER_SERVER_URL.'?license_key='.$license_key;

                update_option('license_check_activate', $obj->package);


	            switch ( $_POST['_action'] ) {

		            case 'version':
			            echo serialize( $obj );
			            break;
		            case 'info':
			            $obj->requires = $license_manager_autoupdate_plugin_requires;
			            $obj->tested = $license_manager_autoupdate_plugin_tested;
			            $obj->downloaded = $license_manager_autoupdate_plugin_downloaded;
			            $obj->last_updated = $license_manager_autoupdate_plugin_last_updated;
			            $obj->sections = array(
				            'description' => $license_manager_autoupdate_plugin_description,
				            'changelog' => $license_manager_autoupdate_plugin_changelog,
			            );
			            $obj->download_link = $obj->package;
			            echo serialize($obj);
		            case 'license':
			            echo serialize( $obj );
			            break;
	            }

            endif;



		endif;
	}


} 

new LicenseManagerAutoupdate();











add_filter( 'woocommerce_product_data_tabs', 'woocommerce_product_data_tab_LicenseManagerAutoupdate', 10 );

function woocommerce_product_data_tab_LicenseManagerAutoupdate( $product_data_tabs ) {
	$product_data_tabs['LicenseManagerAutoupdate'] = array(
		'label' => __( 'Auto Update', 'LicenseManagerAutoupdate' ),
		'target' => 'LicenseManagerAutoupdate',
	);
	return $product_data_tabs;
}


add_action( 'woocommerce_product_data_panels', 'woocommerce_product_data_tab_LicenseManagerAutoupdate_fields' );

function woocommerce_product_data_tab_LicenseManagerAutoupdate_fields() {
	global $woocommerce, $post;

	$license_manager_autoupdate_enable = get_post_meta( $post->ID, 'license_manager_autoupdate_enable', true );
	$license_manager_autoupdate_plugin_requires = get_post_meta( $post->ID, 'license_manager_autoupdate_plugin_requires', true );
	$license_manager_autoupdate_plugin_tested = get_post_meta( $post->ID, 'license_manager_autoupdate_plugin_tested', true );
	$license_manager_autoupdate_plugin_last_updated = get_post_meta( $post->ID, 'license_manager_autoupdate_plugin_last_updated', true );
	$license_manager_autoupdate_plugin_downloaded = get_post_meta( $post->ID, 'license_manager_autoupdate_plugin_downloaded', true );
	$license_manager_autoupdate_plugin_download_link = get_post_meta( $post->ID, 'license_manager_autoupdate_plugin_download_link', true );
	$license_manager_autoupdate_plugin_zip_path = get_post_meta( $post->ID, 'license_manager_autoupdate_plugin_zip_path', true );

	$license_manager_autoupdate_plugin_name = get_post_meta( $post->ID, 'license_manager_autoupdate_plugin_name', true );
	$license_manager_autoupdate_plugin_slug = get_post_meta( $post->ID, 'license_manager_autoupdate_plugin_slug', true );
	$license_manager_autoupdate_plugin_latest_version = get_post_meta( $post->ID, 'license_manager_autoupdate_plugin_latest_version', true );
	$license_manager_autoupdate_plugin_url = get_post_meta( $post->ID, 'license_manager_autoupdate_plugin_url', true );
	$license_manager_autoupdate_plugin_description = get_post_meta( $post->ID, 'license_manager_autoupdate_plugin_description', true );
	$license_manager_autoupdate_plugin_changelog = get_post_meta( $post->ID, 'license_manager_autoupdate_plugin_changelog', true );


	$product = wc_get_product($post->ID);
	$is_variable = $product->is_type('variable');



	?>
	<div id="LicenseManagerAutoupdate" class="panel woocommerce_options_panel">
		<?php


		woocommerce_wp_select( array(
			'id'      => 'license_manager_autoupdate_enable',
			'label'   => __( 'Enable?', 'woocommerce' ),
			'options' =>  array('no'=>'No', 'yes'=>'Yes'), //this is where I am having trouble
			'value'   => $license_manager_autoupdate_enable,
		) );

		woocommerce_wp_text_input( array(
				'id'      => 'license_manager_autoupdate_plugin_requires',
				'label'   => __( 'Requires WP Version', 'LicenseManagerAutoupdate' ),
				'value'   => $license_manager_autoupdate_plugin_requires,
			)
		);

		woocommerce_wp_text_input( array(
				'id'      => 'license_manager_autoupdate_plugin_tested',
				'label'   => __( 'Tested WP Version', 'LicenseManagerAutoupdate' ),
				'value'   => $license_manager_autoupdate_plugin_tested,
			)
		);

		woocommerce_wp_text_input( array(
				'id'      => 'license_manager_autoupdate_plugin_last_updated',
				'label'   => __( 'Last Updated Date', 'LicenseManagerAutoupdate' ),
				'value'   => $license_manager_autoupdate_plugin_last_updated,
			)
		);

		woocommerce_wp_text_input( array(
				'id'      => 'license_manager_autoupdate_plugin_downloaded',
				'label'   => __( 'Total Download Count', 'LicenseManagerAutoupdate' ),
				'value'   => $license_manager_autoupdate_plugin_downloaded,
			)
		);

		woocommerce_wp_text_input( array(
				'id'      => 'license_manager_autoupdate_plugin_download_link',
				'label'   => __( 'Download Link', 'LicenseManagerAutoupdate' ),
				'value'   => $license_manager_autoupdate_plugin_download_link,
			)
		);

		woocommerce_wp_text_input( array(
				'id'      => 'license_manager_autoupdate_plugin_zip_path',
				'label'   => __( 'ZIP path', 'LicenseManagerAutoupdate' ),
				'value'   => $license_manager_autoupdate_plugin_zip_path,
			)
		);


		woocommerce_wp_text_input( array(
				'id'      => 'license_manager_autoupdate_plugin_name',
				'label'   => __( 'Plugin Name', 'LicenseManagerAutoupdate' ),
				'value'   => $license_manager_autoupdate_plugin_name,
			)
		);

		woocommerce_wp_text_input( array(
				'id'      => 'license_manager_autoupdate_plugin_slug',
				'label'   => __( 'Plugin Slug', 'LicenseManagerAutoupdate' ),
				'value'   => $license_manager_autoupdate_plugin_slug,
			)
		);

		woocommerce_wp_text_input( array(
				'id'      => 'license_manager_autoupdate_plugin_latest_version',
				'label'   => __( 'Plugin Latest Version', 'LicenseManagerAutoupdate' ),
				'value'   => $license_manager_autoupdate_plugin_latest_version,
			)
		);


		woocommerce_wp_text_input( array(
				'id'      => 'license_manager_autoupdate_plugin_url',
				'label'   => __( 'Plugin URL', 'LicenseManagerAutoupdate' ),
				'value'   => $license_manager_autoupdate_plugin_url,
			)
		);

		woocommerce_wp_textarea_input( array(
				'id'      => 'license_manager_autoupdate_plugin_description',
				'label'   => __( 'Plugin Description', 'LicenseManagerAutoupdate' ),
				'value'   => $license_manager_autoupdate_plugin_description,
			)
		);

		woocommerce_wp_textarea_input( array(
				'id'      => 'license_manager_autoupdate_plugin_changelog',
				'label'   => __( 'Plugin Changelog', 'LicenseManagerAutoupdate' ),
				'value'   => $license_manager_autoupdate_plugin_changelog,
			)
		);




		?>
	</div>
	<?php



}


add_action( 'woocommerce_process_product_meta', 'woocommerce_product_data_tab_LicenseManagerAutoupdate_save' );
function woocommerce_product_data_tab_LicenseManagerAutoupdate_save( $post_id ){


	// This is the case to save custom field data of checkbox. You have to do it as per your custom fields
	$license_manager_autoupdate_enable = isset( $_POST['license_manager_autoupdate_enable'] ) ? $_POST['license_manager_autoupdate_enable'] : '';
	update_post_meta( $post_id, 'license_manager_autoupdate_enable', $license_manager_autoupdate_enable );

	$license_manager_autoupdate_plugin_requires = isset( $_POST['license_manager_autoupdate_plugin_requires'] ) ? $_POST['license_manager_autoupdate_plugin_requires'] : '';
	update_post_meta( $post_id, 'license_manager_autoupdate_plugin_requires', $license_manager_autoupdate_plugin_requires );

	$license_manager_autoupdate_plugin_tested = isset( $_POST['license_manager_autoupdate_plugin_tested'] ) ? $_POST['license_manager_autoupdate_plugin_tested'] : '';
	update_post_meta( $post_id, 'license_manager_autoupdate_plugin_tested', $license_manager_autoupdate_plugin_tested );

	$license_manager_autoupdate_plugin_last_updated = isset( $_POST['license_manager_autoupdate_plugin_last_updated'] ) ? $_POST['license_manager_autoupdate_plugin_last_updated'] : '';
	update_post_meta( $post_id, 'license_manager_autoupdate_plugin_last_updated', $license_manager_autoupdate_plugin_last_updated );

	$license_manager_autoupdate_plugin_downloaded = isset( $_POST['license_manager_autoupdate_plugin_downloaded'] ) ? $_POST['license_manager_autoupdate_plugin_downloaded'] : '';
	update_post_meta( $post_id, 'license_manager_autoupdate_plugin_downloaded', $license_manager_autoupdate_plugin_downloaded );

	$license_manager_autoupdate_plugin_download_link = isset( $_POST['license_manager_autoupdate_plugin_download_link'] ) ? $_POST['license_manager_autoupdate_plugin_download_link'] : '';
	update_post_meta( $post_id, 'license_manager_autoupdate_plugin_download_link', $license_manager_autoupdate_plugin_download_link );

	$license_manager_autoupdate_plugin_zip_path = isset( $_POST['license_manager_autoupdate_plugin_zip_path'] ) ? $_POST['license_manager_autoupdate_plugin_zip_path'] : '';
	update_post_meta( $post_id, 'license_manager_autoupdate_plugin_zip_path', $license_manager_autoupdate_plugin_zip_path );


	$license_manager_autoupdate_plugin_name = isset( $_POST['license_manager_autoupdate_plugin_name'] ) ? $_POST['license_manager_autoupdate_plugin_name'] : '';
	update_post_meta( $post_id, 'license_manager_autoupdate_plugin_name', $license_manager_autoupdate_plugin_name );

	$license_manager_autoupdate_plugin_slug = isset( $_POST['license_manager_autoupdate_plugin_slug'] ) ? $_POST['license_manager_autoupdate_plugin_slug'] : '';
	update_post_meta( $post_id, 'license_manager_autoupdate_plugin_slug', $license_manager_autoupdate_plugin_slug );

	$license_manager_autoupdate_plugin_latest_version = isset( $_POST['license_manager_autoupdate_plugin_latest_version'] ) ? $_POST['license_manager_autoupdate_plugin_latest_version'] : '';
	update_post_meta( $post_id, 'license_manager_autoupdate_plugin_latest_version', $license_manager_autoupdate_plugin_latest_version );

	$license_manager_autoupdate_plugin_url = isset( $_POST['license_manager_autoupdate_plugin_url'] ) ? $_POST['license_manager_autoupdate_plugin_url'] : '';
	update_post_meta( $post_id, 'license_manager_autoupdate_plugin_url', $license_manager_autoupdate_plugin_url );

	$license_manager_autoupdate_plugin_description = isset( $_POST['license_manager_autoupdate_plugin_description'] ) ? $_POST['license_manager_autoupdate_plugin_description'] : '';
	update_post_meta( $post_id, 'license_manager_autoupdate_plugin_description', $license_manager_autoupdate_plugin_description );

	$license_manager_autoupdate_plugin_changelog = isset( $_POST['license_manager_autoupdate_plugin_changelog'] ) ? $_POST['license_manager_autoupdate_plugin_changelog'] : '';
	update_post_meta( $post_id, 'license_manager_autoupdate_plugin_changelog', $license_manager_autoupdate_plugin_changelog );



}







//add_shortcode('download_file_force','download_file_force_autoup');

function download_file_force_autoup(){

	$_download_id = '21514';
	$product_id = '10965';
	$product        = wc_get_product( $product_id );

	var_dump('Hello');

	@$WC_Download_Handler = new WC_Download_Handler();
	//@$WC_Download_Handler::download( $product->get_file_download_path( $_download_id ), $product_id );
	@$WC_Download_Handler::download_file_force( 'http://192.168.0.90/themes-dev/wp-content/uploads/woocommerce_uploads/2018/07/logo-collection-1.9.zip', 'logo-collection-1.9' );


}






add_action('init','getDownloadLinkByLicenseKey');

function getDownloadLinkByLicenseKey(){

    $licenseKey = isset($_GET['license_key']) ? $_GET['license_key'] : '';

    if(empty($licenseKey)) return;

    //var_dump($licenseKey);


    $query_args['post_type'] = array('license');
    $query_args['post_status'] = array('any');

    $query_args['meta_query'] =
        array(
            'key' => 'license_key',
            'value' => $licenseKey,
            'compare' => '='
        );


    $post_grid_wp_query = new WP_Query($query_args);

    if ( $post_grid_wp_query->have_posts() ) :
        while ( $post_grid_wp_query->have_posts() ) : $post_grid_wp_query->the_post();

            $license_status = get_post_meta(get_the_id(), 'license_status', true);
            $product_id = get_post_meta(get_the_id(), 'product_id', true);
            //echo $license_status;




        endwhile;
    endif;

    if($license_status=='active'){
        $license_manager_autoupdate_plugin_download_link = get_post_meta($product_id,'license_manager_autoupdate_plugin_download_link', true);
        //var_dump($license_manager_autoupdate_plugin_download_link);

        @$WC_Download_Handler = new WC_Download_Handler();
        @$WC_Download_Handler::download_file_force( $license_manager_autoupdate_plugin_download_link, basename($license_manager_autoupdate_plugin_download_link) );

        //wp_safe_redirect($license_manager_autoupdate_plugin_download_link);

    }




}





















