<?php
/*
* @Author 		PickPlugins
* Copyright: 	2015 PickPlugins.com
*/

if ( ! defined('ABSPATH')) exit;  // if direct access 
	

	if(isset($_GET['license_id']) && is_user_logged_in()){
		
		$license_id = (int) sanitize_text_field($_GET['license_id']);
			
			
		$license_post = get_post($license_id);
			
		$license_author = $license_post->post_author;
			
			//var_dump($license_author);

		$logged_user_id		= get_current_user_id();	
		
		if($logged_user_id == $license_author){
			
				
			$logged_user		= wp_get_current_user();
			$logged_user_data 	= get_userdata($logged_user_id);
			
			$domains_list 		= get_post_meta( $license_id, 'domains_list', true);
			$license_key 		= get_post_meta( $license_id, 'license_key', true);
			$license_status 	= get_post_meta( $license_id, 'license_status', true);		
			$domain_count 		= get_post_meta( $license_id, 'domain_count', true);			
			$license_email 		= get_post_meta( $license_id, 'license_email', true);
			$user_id 			= get_post_meta( $license_id, 'user_id', true);				
			$order_id 			= get_post_meta( $license_id, 'order_id', true);
			$product_id 		= get_post_meta( $license_id, 'product_id', true);			
			$date_created 		= get_post_meta( $license_id, 'date_created', true);	
			$date_renewed 		= get_post_meta( $license_id, 'date_renewed', true);		
			$date_expiry 		= get_post_meta( $license_id, 'date_expiry', true);
	
			//var_dump($license_id);
			
			if(isset($_POST['license_edit_hidden'])){
		
				$nonce = $_POST['_wpnonce'];
				if(wp_verify_nonce( $nonce, 'nonce_license_edit' )){
					
					$domains_list = stripslashes_deep( $_POST['domains_list'] );
					update_post_meta( $license_id, 'domains_list', $domains_list );
					
					if(current_user_can('administrator')):	
				
						$license_status = sanitize_text_field( $_POST['license_status'] );
						update_post_meta( $license_id, 'license_status', $license_status );
				
						$domain_count = sanitize_text_field( $_POST['domain_count'] );
						update_post_meta( $license_id, 'domain_count', $domain_count );
	
						$license_email = sanitize_text_field( $_POST['license_email'] );
						update_post_meta( $license_id, 'license_email', $license_email );	
					
						$user_id = sanitize_text_field( $_POST['user_id'] );
						update_post_meta( $license_id, 'user_id', $user_id );		
					
						$order_id = sanitize_text_field( $_POST['order_id'] );
						update_post_meta( $license_id, 'order_id', $order_id );	
					
						$product_id = sanitize_text_field( $_POST['product_id'] );
						update_post_meta( $license_id, 'product_id', $product_id );	
				
						$date_created = sanitize_text_field( $_POST['date_created'] );
						update_post_meta( $license_id, 'date_created', $date_created );
				
						$date_renewed = sanitize_text_field( $_POST['date_renewed'] );
						update_post_meta( $license_id, 'date_renewed', $date_renewed );	
					
						$date_expiry = sanitize_text_field( $_POST['date_expiry'] );
						update_post_meta( $license_id, 'date_expiry', $date_expiry );
					
					endif;
					
					
					
					
					
					}
				
				}	
			
			?>
            
			<div class="clients-license-edit">
				<form id="license-edit" class="" action="#license-edit" method="post">
					<input name="license_edit_hidden" type="hidden" value="Y" />


                        <div class="option-box">
                            <div class="option-info"><?php echo __('Domains', LICENSE_MANAGER_PP_TEXTDOMAIN); ?></div>

                            <?php

                            //var_dump($domains_list);



                            if(is_array($domains_list) && !empty($domains_list)):
                                foreach ($domains_list as $domain):
                                    ?>
                                    <input name="domains_list[]" value="<?php echo $domain; ?>">
                                    <?php
                                endforeach;
                            else:
                                ?>
                                <input name="domains_list[]" value="">
                            <?php
                            endif;

                            ?>
                        </div>

					<?php
                    if(current_user_can('administrator')):
					
					?>

                        <div class="option-box">
                            <div class="option-info"><?php echo __('License status', LICENSE_MANAGER_PP_TEXTDOMAIN); ?></div>
                            
                            <select name="license_status" >
                                <option <?php if($license_status=='pending') echo 'selected'; ?>  value="pending">Pending</option>
                                <option <?php if($license_status=='active') echo 'selected'; ?> value="active">Active</option>
                                <option <?php if($license_status=='blocked') echo 'selected'; ?> value="blocked">Blocked</option>
                                <option <?php if($license_status=='expired') echo 'selected'; ?> value="expired">Expired</option>
                            </select>
                            
                        </div>        
                    
                    
                        <div class="option-box">
                            <div class="option-info"><?php echo __('Domain count', LICENSE_MANAGER_PP_TEXTDOMAIN); ?></div>
                            <input id="domain_count" placeholder="5" class="domain_count" type="text" value="<?php echo $domain_count; ?>" name="domain_count" />
                        </div>        

                        <div class="option-box">
                            <div class="option-info"><?php echo __('License email', LICENSE_MANAGER_PP_TEXTDOMAIN); ?></div>
                            <input id="license_email" placeholder="hello@dummy.com" class="license_email" type="text" value="<?php echo $license_email; ?>" name="license_email" />
                        </div>         
                    
                    
                        <div class="option-box">
                            <div class="option-info"><?php echo __('User ID', LICENSE_MANAGER_PP_TEXTDOMAIN); ?></div>
                            <input id="user_id" placeholder="" class="user_id" type="text" value="<?php echo $user_id; ?>" name="user_id" />
                        </div>         
            
                    
                        <div class="option-box">
                            <div class="option-info"><?php echo __('Order id', LICENSE_MANAGER_PP_TEXTDOMAIN); ?></div>
                            <input id="order_id" placeholder="86592" class="order_id" type="text" value="<?php echo $order_id; ?>" name="order_id" />
                        </div>        
                    
                        <div class="option-box">
                            <div class="option-info"><?php echo __('Product id', LICENSE_MANAGER_PP_TEXTDOMAIN); ?></div>
                            <input id="product_id" placeholder="86592" class="product_id" type="text" value="<?php echo $product_id; ?>" name="product_id" />
                        </div>          
                    
                    
                    
                        <div class="option-box">
                            <div class="option-info"><?php echo __('Created date', LICENSE_MANAGER_PP_TEXTDOMAIN); ?></div>
                            <input id="date_created" class="license-manager-date" type="text" value="<?php echo $date_created; ?>" name="date_created" />
                        </div>        
                    
                        <div class="option-box">
                            <div class="option-info"><?php echo __('Renewed date', LICENSE_MANAGER_PP_TEXTDOMAIN); ?></div>
                            <input id="date_renewed" class="license-manager-date" type="text" value="<?php echo $date_renewed; ?>" name="date_renewed" />
                        </div>         
                    
                    
                        <div class="option-box">
                            <div class="option-info"><?php echo __('Expiry date', LICENSE_MANAGER_PP_TEXTDOMAIN); ?></div>
                            <input id="date_expiry" class="license-manager-date" type="text" value="<?php echo $date_expiry; ?>" name="date_expiry" />
                        </div>
                    
                    <?php
					endif;
					?>

			  
					<br>
					<?php wp_nonce_field( 'nonce_license_edit' ); ?>
					<input type="submit" value="<?php echo __('Update', LICENSE_MANAGER_PP_TEXTDOMAIN); ?>" />
				
				</form>
				 
			</div>
            
            
            
            
            <?php
			
			}
		
		}
	else{
		
		return;
		
		}
	
	
	

	

	
	//var_dump($question_author_id);
	
	
	
/*
		$tax_query[] = array(
							'taxonomy' => 'job_category',
							'field'    => 'slug',
							'terms'    => $job_category,
							//'operator'    => '',
							);
								
								
		$meta_query[] = array(
		
							'key' => 'job_bm_job_status',
							'value' => sanitize_text_field($job_status),
							'compare' => '=',
							
								);

*/
	

	
	?>
    
