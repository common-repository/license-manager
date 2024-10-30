<?php
/*
* @Author 		PickPlugins
* Copyright: 	2015 PickPlugins.com
*/

if ( ! defined('ABSPATH')) exit;  // if direct access 
	
	
	if(is_user_logged_in()){
		
		
		$logged_user	= wp_get_current_user();
		$logged_user_id	= get_current_user_id();	
		$logged_user_data 		= get_userdata($logged_user);
	
		
		//var_dump($question_author_id);
		
			$meta_query[] = array(
			
								'key' => 'user_id',
								'value' => sanitize_text_field($logged_user_id),
								'compare' => '=',
								
									);
		
		/*
				$tax_query[] = array(
									'taxonomy' => 'job_category',
									'field'    => 'slug',
									'terms'    => $job_category,
									//'operator'    => '',
									);
										
										
		
		
		*/
		
		$posts_per_page = get_option('posts_per_page');
		$license_edit_page = get_option('license_edit_page');	
		
		if ( get_query_var('paged') ) {$paged = get_query_var('paged');} 
		elseif ( get_query_var('page') ) {$paged = get_query_var('page');} 
		else {$paged = 1;}
	
		$wp_query = new WP_Query(
			array (
				'post_type' => 'license',
				'post_status' => 'publish',
				'orderby' => 'date',
				//'meta_query' => $meta_query,
				//'tax_query' => $tax_query,			
				'order' => 'DESC',
				'author' => $logged_user_id,			
				'posts_per_page' => $posts_per_page,
				'paged' => $paged,
				
				) );
		
		
?>

<div class="clients-license">

    <?php
		
	if ( $wp_query->have_posts() ) :
		while ( $wp_query->have_posts() ) : $wp_query->the_post();	
		
		$license_id 	= get_the_ID();
		$license_key 	= get_post_meta( $license_id, 'license_key', true);
		$license_status = get_post_meta( $license_id, 'license_status', true);		
		$domain_count 	= get_post_meta( $license_id, 'domain_count', true);			
		$domains_list 	= get_post_meta( $license_id, 'domains_list', true);	
		$license_email 	= get_post_meta( $license_id, 'license_email', true);
		$user_id 		= get_post_meta( $license_id, 'user_id', true);				
		$order_id 		= get_post_meta( $license_id, 'order_id', true);
		$product_id 	= get_post_meta( $license_id, 'product_id', true);			
		$date_created 	= get_post_meta( $license_id, 'date_created', true);	
		$date_renewed 	= get_post_meta( $license_id, 'date_renewed', true);		
		$date_expiry 	= get_post_meta( $license_id, 'date_expiry', true);

		if(!empty($domains_list)) $domains_list = implode(', ', $domains_list);
		
		?>
        <div class="single">
        	<div class=""><a href="<?php echo get_permalink($license_edit_page).'?license_id='.$license_id; ?>">#<?php echo $license_id; ?></a></div>
            
        	<div class="product_id">Product: <a href="<?php echo get_permalink($product_id); ?>"><?php echo get_the_title($product_id); ?></a></div>              
        	<div class="license_key">License key: <input onClick="select(this)" type="text" value="<?php echo $license_key; ?>" /></div>            
        	<div class="license_status ">License status: <span class="status <?php echo $license_status; ?>"><?php echo $license_status; ?></span></div>             
        	<div class="domain_count">Domain count: <?php echo $domain_count; ?></div> 
        	<div class="domains_list">Domain list: <?php echo $domains_list; ?></div>  
        	<div class="date_created">Date created: <?php echo $date_created; ?></div>            
        	<div class="date_renewed">Date renewed: <?php echo $date_renewed; ?></div>             
            <div class="date_expiry">Date expiry: <?php echo $date_expiry; ?></div>                      
            
        </div>
        <?php
	
	
		endwhile;
	
		echo '<div class="paginate">';
		$big = 999999999; // need an unlikely integer
		echo paginate_links( array(
			'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
			
			'format' => '?paged=%#%',
			'current' => max( 1, $paged ),
			'total' => $wp_query->max_num_pages
			) );
	
		echo '</div >';	

	
	
	wp_reset_query();
	else:
	
	echo __('No license found', LICENSE_MANAGER_PP_TEXTDOMAIN);	
	
	endif;	
	
	
	?>    
</div>
<?php
		
		
		
		
		
		
		}
	

	
	else{
		echo __('Please login first.', LICENSE_MANAGER_PP_TEXTDOMAIN);	
		
		
		}
