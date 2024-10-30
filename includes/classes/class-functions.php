<?php

/*
* @Author 		pickplugins
* Copyright: 	2015 pickplugins
*/

if ( ! defined('ABSPATH')) exit;  // if direct access 


class class_license_manager_functions{
	
	public function __construct() {

		//add_action('add_meta_boxes', array($this, 'meta_boxes_question'));
		//add_action('save_post', array($this, 'meta_boxes_question_save'));

	}
	
	

		
	public function license_manager_get_pages() {
		$array_pages[''] = __('None',LICENSE_MANAGER_PP_TEXTDOMAIN);
		
		foreach( get_pages() as $page )
		if ( $page->post_title ) $array_pages[$page->ID] = $page->post_title;
		
		return $array_pages;
	}




	public function days_remaining($date_expiry){



		$gmt_offset = get_option('gmt_offset');
		$today = date('Y-m-d h:i:s', strtotime('+'.$gmt_offset.' hour'));
		$today = strtotime($today);
		//var_dump(strtotime($today));
		$response = array();
		$response['class'] = 'active';


		$date_expiry = strtotime($date_expiry);
		//$today = time();

		//var_dump($today);


		$diff = $date_expiry-$today;

		if($diff<0){

			$response['diff'] = $diff;
			$response['text'] = __('Expired', LICENSE_MANAGER_PP_TEXTDOMAIN);
			$response['class'] = 'expired';
		}
		else{

			$minute = floor(($diff % 3600)/60);
			$hour = floor(($diff % 86400)/3600);
			$day = floor(($diff % 2592000)/86400);
			$month = floor($diff/2592000);
			$year = floor($diff/(86400*365));

			if($year>0){
				$response['text'] =  number_format_i18n($year) .' '.__('year', LICENSE_MANAGER_PP_TEXTDOMAIN);
			}

			elseif($month > 0 && $month<=12 ){
				$response['text'] = number_format_i18n($month) .' '.__('month', LICENSE_MANAGER_PP_TEXTDOMAIN);
			}

			elseif($day > 0 && $day<=30){
				$response['text'] = number_format_i18n($day).' '.__('day', LICENSE_MANAGER_PP_TEXTDOMAIN);
			}

			elseif($hour > 0 && $hour<=24){
				$response['text'] = number_format_i18n($hour).' '.__('hour', LICENSE_MANAGER_PP_TEXTDOMAIN);
			}

			elseif($minute > 0 && $minute<60){
				$response['text'] = number_format_i18n($minute).' '.__('minute', LICENSE_MANAGER_PP_TEXTDOMAIN);
			}

			else{
				$response['text'] = $diff.' '.__('second', LICENSE_MANAGER_PP_TEXTDOMAIN);;
			}

		}

		return $response;


	}








	

} new class_license_manager_functions();