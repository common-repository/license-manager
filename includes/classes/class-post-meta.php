<?php

/*
* @Author 		pickplugins
* Copyright: 	2015 pickplugins
*/

if ( ! defined('ABSPATH')) exit;  // if direct access 

class class_license_manager_post_meta_question{
	
	public function __construct(){

		add_action('add_meta_boxes', array($this, 'meta_boxes_question'));
		add_action('save_post', array($this, 'meta_boxes_question_save'));
	}
	
	public function meta_boxes_question($post_type) {
		
		$post_types = array('license');
		if (in_array($post_type, $post_types)) {
		
			add_meta_box('question_metabox',
				__( 'License Data', LICENSE_MANAGER_PP_TEXTDOMAIN ),
				array($this, 'question_meta_box_function'),
				$post_type,
				'normal',
				'high'
			);
		}
	}
	
	public function question_meta_box_function($post) {
 
        wp_nonce_field('question_nonce_check', 'question_nonce_check_value');
		echo '';
		
		
		$license_key 	= get_post_meta( $post -> ID, 'license_key', true);
		$license_status 	= get_post_meta( $post -> ID, 'license_status', true);		
		$domain_count 	= get_post_meta( $post -> ID, 'domain_count', true);			
		$domains_list 	= get_post_meta( $post -> ID, 'domains_list', true);	
		$license_email 	= get_post_meta( $post -> ID, 'license_email', true);
		$user_id 	= get_post_meta( $post -> ID, 'user_id', true);				
		$order_id 	= get_post_meta( $post -> ID, 'order_id', true);	
		$product_id 	= get_post_meta( $post -> ID, 'product_id', true);
		$variation_id 	= get_post_meta( $post -> ID, 'variation_id', true);
		$date_created 	= get_post_meta( $post -> ID, 'date_created', true);	
		$date_renewed 	= get_post_meta( $post -> ID, 'date_renewed', true);		
		$date_expiry 	= get_post_meta( $post -> ID, 'date_expiry', true);



		//var_dump($domains_list);
		
		
		?>
        <div class="para-settings question-meta">
            <div class="option-box">
                <p class="option-info"><?php echo __('License key', LICENSE_MANAGER_PP_TEXTDOMAIN); ?></p>
                <input onClick="select(this)" width="200" id="license_key" type="text" value="<?php echo $license_key; ?>" name="license_key" />
            </div>
            
            <div class="option-box">
                <p class="option-info"><?php echo __('License status', LICENSE_MANAGER_PP_TEXTDOMAIN); ?></p>
                
                <select name="license_status" >
                    <option <?php if($license_status=='pending') echo 'selected'; ?>  value="pending">Pending</option>
                    <option <?php if($license_status=='active') echo 'selected'; ?> value="active">Active</option>
                    <option <?php if($license_status=='blocked') echo 'selected'; ?> value="blocked">Blocked</option>
                    <option <?php if($license_status=='expired') echo 'selected'; ?> value="expired">Expired</option>
                </select>
                
            </div>        
        
        
            <div class="option-box">
                <p class="option-info"><?php echo __('Domain count', LICENSE_MANAGER_PP_TEXTDOMAIN); ?></p>
                <input id="domain_count" placeholder="5" class="domain_count" type="text" value="<?php echo $domain_count; ?>" name="domain_count" />
            </div>        
        
        
        
            <div class="option-box">
                <p class="option-info"><?php echo __('Domain, comma separated', LICENSE_MANAGER_PP_TEXTDOMAIN); ?></p>

                <ul>


                <?php
                if(!empty($domains_list) && is_array($domains_list)):
	                foreach ($domains_list as $domain_index=>$domain){
                        ?>
                        <li ><span class="remove-domain" index="<?php echo $domain_index; ?>">X</span> <input type="text" name="domains_list[]" value="<?php echo $domain; ?>"></li>
                        <?php

	                }
                endif;

                ?>
                </ul>
            </div>         
        
        
            <div class="option-box">
                <p class="option-info"><?php echo __('License email', LICENSE_MANAGER_PP_TEXTDOMAIN); ?></p>
                <input id="license_email" placeholder="hello@dummy.com" class="license_email" type="text" value="<?php echo $license_email; ?>" name="license_email" />
            </div>         
        
        
            <div class="option-box">
                <p class="option-info"><?php echo __('User ID', LICENSE_MANAGER_PP_TEXTDOMAIN); ?></p>
                <input id="user_id" placeholder="" class="user_id" type="text" value="<?php echo $user_id; ?>" name="user_id" />
            </div>         

        
            <div class="option-box">
                <p class="option-info"><?php echo __('Order id', LICENSE_MANAGER_PP_TEXTDOMAIN); ?></p>
                <input id="order_id" placeholder="86592" class="order_id" type="text" value="<?php echo $order_id; ?>" name="order_id" />
            </div>        
        
            <div class="option-box">
                <p class="option-info"><?php echo __('Product id', LICENSE_MANAGER_PP_TEXTDOMAIN); ?></p>
                <input id="product_id" placeholder="86592" class="product_id" type="text" value="<?php echo $product_id; ?>" name="product_id" />
            </div>

            <div class="option-box">
                <p class="option-info"><?php echo __('Variation id', LICENSE_MANAGER_PP_TEXTDOMAIN); ?></p>
                <input id="variation_id" placeholder="86592" class="variation_id" type="text" value="<?php echo $variation_id; ?>" name="variation_id" />
            </div>

            <div class="option-box">
                <p class="option-info"><?php echo __('Created date', LICENSE_MANAGER_PP_TEXTDOMAIN); ?></p>
                <input id="date_created" class="license-manager-date" type="text" value="<?php echo $date_created; ?>" name="date_created" />
            </div>        
        
            <div class="option-box">
                <p class="option-info"><?php echo __('Renewed date', LICENSE_MANAGER_PP_TEXTDOMAIN); ?></p>
                <input id="date_renewed" class="license-manager-date" type="text" value="<?php echo $date_renewed; ?>" name="date_renewed" />
            </div>         
        
        
            <div class="option-box">
                <p class="option-info"><?php echo __('Expiry date', LICENSE_MANAGER_PP_TEXTDOMAIN); ?></p>
                <input id="date_expiry" class="license-manager-date" type="text" value="<?php echo $date_expiry; ?>" name="date_expiry" />
            </div>         
        
        
        
        </div>
        
        
        <?php

   	}
	
	public function meta_boxes_question_save($post_id){
	 
		if (!isset($_POST['question_nonce_check_value'])) return $post_id;
		$nonce = $_POST['question_nonce_check_value'];
		if (!wp_verify_nonce($nonce, 'question_nonce_check')) return $post_id;

		if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return $post_id;
	 
		if ('page' == $_POST['post_type']) {
			if (!current_user_can('edit_page', $post_id)) return $post_id;
		} else {
			if (!current_user_can('edit_post', $post_id)) return $post_id;
		}

		$license_key = sanitize_text_field( $_POST['license_key'] );
		update_post_meta( $post_id, 'license_key', $license_key );		

		$license_status = sanitize_text_field( $_POST['license_status'] );
		update_post_meta( $post_id, 'license_status', $license_status );

		$domain_count = sanitize_text_field( $_POST['domain_count'] );
		update_post_meta( $post_id, 'domain_count', $domain_count );
	
		$domains_list = stripslashes_deep( $_POST['domains_list'] );
		update_post_meta( $post_id, 'domains_list', $domains_list );	
	
		$license_email = sanitize_text_field( $_POST['license_email'] );
		update_post_meta( $post_id, 'license_email', $license_email );	
	
		$user_id = sanitize_text_field( $_POST['user_id'] );
		update_post_meta( $post_id, 'user_id', $user_id );		
	
		$order_id = sanitize_text_field( $_POST['order_id'] );
		update_post_meta( $post_id, 'order_id', $order_id );	
	
		$product_id = sanitize_text_field( $_POST['product_id'] );
		update_post_meta( $post_id, 'product_id', $product_id );	

		$date_created = sanitize_text_field( $_POST['date_created'] );
		update_post_meta( $post_id, 'date_created', $date_created );

		$date_renewed = sanitize_text_field( $_POST['date_renewed'] );
		update_post_meta( $post_id, 'date_renewed', $date_renewed );	
	
		$date_expiry = sanitize_text_field( $_POST['date_expiry'] );
		update_post_meta( $post_id, 'date_expiry', $date_expiry );	
	


	}
	
} 

new class_license_manager_post_meta_question();