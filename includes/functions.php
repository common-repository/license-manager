<?php

/*
* @Author 		pickplugins
* Copyright: 	2015 pickplugins
*/

if ( ! defined('ABSPATH')) exit;  // if direct access 




function license_add_shortcode_column( $columns ) {
	
	unset($columns['author']);
	unset($columns['comments']);	
	unset($columns['date']);	
	
   //return array_merge( $columns, array( 'title' => __( 'Shortcode', 'accordions' ) ) );
		
	$custom_col = array( 'license_key' => __( 'License', 'accordions' ),  'license_status' => __( 'License status', 'accordions' ), 'domain_count' => __( 'Domain count', 'accordions' ), 'dates' => __( 'Date', 'accordions' ), 'author' => __( 'Author', 'accordions' ) );
		
		
	return array_merge( $columns, $custom_col );
		
		
}
add_filter( 'manage_license_posts_columns' , 'license_add_shortcode_column' );


function license_posts_extra_display( $column, $post_id ) {
	
	
	
	
    if ($column == 'license_key'){
		
		$license_key = get_post_meta($post_id, 'license_key', true);
		
		?>
        <input style="background:#bfefff" type="text" onClick="this.select();" value="<?php echo $license_key; ?>" /><br />
        <?php		
		
    }
	
	
    if ($column == 'license_status'){
		
		$license_status = get_post_meta($post_id, 'license_status', true);
		
		echo '<span class="license-status '.$license_status.'">'.$license_status.'</span>';		
		
		
    }
	
	
    if ($column == 'domain_count'){
		
		$domain_count = get_post_meta($post_id, 'domain_count', true);
		
		echo '<span class="domain-count">'.$domain_count.'</span>';		
		
		
    }	
	
	
    if ($column == 'dates'){
		
		$date_created = get_post_meta($post_id, 'date_created', true);
		$date_renewed = get_post_meta($post_id, 'date_renewed', true);		
		$date_expiry = get_post_meta($post_id, 'date_expiry', true);		
		
		$date_created = new DateTime($date_created);
		$date_renewed = new DateTime($date_renewed);		
		$date_expiry = new DateTime($date_expiry);
		
				
		echo '<div class="created">Created: '.$date_created->format('d M, Y').'</div>';		
		echo '<div class="renewed">Renewe: '.$date_renewed->format('d M, Y').'</div>';		
		echo '<div class="expiry">Expiry: '.$date_expiry->format('d M, Y').'</div>';	
		
		
			
    }	
	
	
	
	
	
	
	
	
	
	
}
add_action( 'manage_license_posts_custom_column' , 'license_posts_extra_display', 10, 2 );
