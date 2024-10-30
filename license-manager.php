<?php
/*
Plugin Name: License Manager
Plugin URI: http://pickplugins.com
Description: License manager for digital product online or remote activation.
Version: 1.0.0
Text Domain: license-manager
Author: PickPlugins
Author URI: http://pickplugins.com
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

if ( ! defined('ABSPATH')) exit;  // if direct access 


class LicenseManager{
	
	public function __construct(){
	
		$this->define_constants();
		$this->declare_classes();
		$this->declare_shortcodes();	
		$this->loading_script();
		$this->declare_functions();	
		
		
		register_activation_hook( __FILE__, array( $this, 'activation' ) );
		add_action( 'plugins_loaded', array( $this, 'load_textdomain' ));

	}

    public function define_constants(){

        $this->define('LICENSE_MANAGER_PP_PLUGIN_URL', plugins_url('/', __FILE__)  );
        $this->define('LICENSE_MANAGER_PP_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
        $this->define('LICENSE_MANAGER_PP_TEXTDOMAIN', 'license-manager' );
        $this->define('LICENSE_MANAGER_PP_PLUGIN_NAME', __('License Manager', LICENSE_MANAGER_PP_TEXTDOMAIN) );
        $this->define('LICENSE_MANAGER_PP_PLUGIN_SUPPORT', 'http://www.pickplugins.com/questions/'  );
        $this->define('LICENSE_MANAGER_PP_VERSION', '1.0.0' );
        $this->define('LICENSE_MANAGER_SERVER_URL', home_url() );

    }

    private function define( $name, $value ){
        if( $name && $value )
            if ( ! defined( $name ) ) {
                define( $name, $value );
            }
    }








	public function activation(){}
	
	public function load_textdomain(){
		
		load_plugin_textdomain( LICENSE_MANAGER_PP_TEXTDOMAIN, false, plugin_basename( dirname( __FILE__ ) ) . '/languages/' ); 
	}

	

	
	public function loading_script(){
	
		add_action( 'wp_enqueue_scripts', array( $this, 'front_scripts' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ) );
	}

	
	public function declare_functions(){
		
		require_once( LICENSE_MANAGER_PP_PLUGIN_DIR . 'includes/functions.php');				
		
	}	
	
	
	public function declare_shortcodes(){
		
		require_once( LICENSE_MANAGER_PP_PLUGIN_DIR . 'includes/shortcodes/class-shortcode-clients-license.php');
		require_once( LICENSE_MANAGER_PP_PLUGIN_DIR . 'includes/shortcodes/class-shortcode-clients-license-edit.php');				
		
	}
	
	public function declare_classes(){
		
		require_once( LICENSE_MANAGER_PP_PLUGIN_DIR . 'includes/classes/class-functions.php');
		require_once( LICENSE_MANAGER_PP_PLUGIN_DIR . 'includes/classes/class-post-types.php');	
		require_once( LICENSE_MANAGER_PP_PLUGIN_DIR . 'includes/classes/class-post-meta.php');
		require_once( LICENSE_MANAGER_PP_PLUGIN_DIR . 'includes/classes/class-settings.php');	
		require_once( LICENSE_MANAGER_PP_PLUGIN_DIR . 'includes/classes/class-manage-license.php');
        require_once( LICENSE_MANAGER_PP_PLUGIN_DIR . 'includes/classes/license-manager-woocommerce.php');

        require_once( LICENSE_MANAGER_PP_PLUGIN_DIR . 'includes/classes/license-manager-autoupdate.php');

	}
	


		

		
		
	public function front_scripts(){
		
		wp_enqueue_script('jquery');
		wp_enqueue_script('jquery-ui-datepicker');
		
		wp_enqueue_script('lm_front_js', plugins_url( '/assets/front/js/scripts.js' , __FILE__ ) , array( 'jquery' ));

		
		wp_enqueue_style('clients-license', LICENSE_MANAGER_PP_PLUGIN_URL.'assets/front/css/clients-license.css');
		//wp_enqueue_style('clients-license-edit', LICENSE_MANAGER_PP_PLUGIN_URL.'assets/front/css/clients-license-edit.css');
		
		//wp_enqueue_style('jquery-ui', LICENSE_MANAGER_PP_PLUGIN_URL.'assets/admin/css/jquery-ui.css');
		
		
		// ParaAdmin
		//wp_enqueue_script('lm_ParaAdmin', plugins_url( '/assets/admin/ParaAdmin/ParaAdmin.js' , __FILE__ ) , array( 'jquery' ));
		//wp_enqueue_style('lm_paraAdmin', LICENSE_MANAGER_PP_PLUGIN_URL.'assets/admin/ParaAdmin/js/ParaAdmin.css');
		

	}

	public function admin_scripts(){
		
		wp_enqueue_script('jquery');
		wp_enqueue_script('jquery-ui-datepicker');
		wp_enqueue_script('jquery-ui-core');

		wp_enqueue_script('lm_admin_js', plugins_url( '/assets/admin/js/scripts.js' , __FILE__ ) , array( 'jquery' ));
		//wp_localize_script( 'lm_admin_js', 'lm_ajax', array( 'lm_ajaxurl' => admin_url( 'admin-ajax.php')));
		
		wp_enqueue_style('jquery-ui', LICENSE_MANAGER_PP_PLUGIN_URL.'assets/admin/css/jquery-ui.css');
	
		
		wp_enqueue_script('lm_ParaAdmin', plugins_url( '/assets/admin/ParaAdmin/js/ParaAdmin.js' , __FILE__ ) , array( 'jquery' ));
		wp_enqueue_style('lm_paraAdmin', LICENSE_MANAGER_PP_PLUGIN_URL.'assets/admin/ParaAdmin/css/ParaAdmin.css');
		
	}
	
	
} 

new LicenseManager();