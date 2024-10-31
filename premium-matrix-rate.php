<?php


 /**
 *  
 * Plugin Name: Premium Matrix Rate
 * Plugin URI: http://cedcommerce.com/woocommerce-extensions/premium-matrix-rate
 * Description: Allow Merchant To Set multiple shipping charge on the basis of weight ,total order and total quantity 
 * Version: 1.0.0
 * Text Domain: premium-matrix-rate
 * Author: CedCommerce
 * Author URI: http://cedcommerce.com/
 * Developer URI: http://cedcommerce.com/
 * Domain Path: /languages
 */

if (!defined('ABSPATH'))
{
	exit; // Exit if accessed directly
}

/**
 * Check if WooCommerce is active
 */
if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) )
{
	define('CEDPREMIUM_DIR_URL', plugin_dir_url(__FILE__));
	define('CEDPREMIUM_DIR_PATH',plugin_dir_path(__FILE__));
	define('PMR_DOMIAN','premium-matrix-rate');
	define('PMR_PREFIX','cedcommerce_pmr');
	
	require_once(ABSPATH .'wp-settings.php');
	error_reporting(0);
	
	include_once('class-premium-matrix-rate.php');
	
	//instance of the main class-premium-matrix-rate class.
	$cedPremiumInstance = cedPremiumMatrix::getInstance();
	
	//end session on logout.
	add_action('wp_logout',array($cedPremiumInstance,'pmr_EndSession'));
	
	//end session on login.
	add_action('wp_login',array($cedPremiumInstance, 'pmr_StartSession'));
	
	//register activation hook.
	register_activation_hook( __FILE__ , array( $cedPremiumInstance ,'ced_Premium_activate' ) );
	
	//register de-activation hook.
	register_deactivation_hook(__FILE__ , array( $cedPremiumInstance ,'ced_Premium_deactivate' ) );
	
	add_action('init','add_common_settings',1);
	
	add_action( 'woocommerce_shipping_init', array( $cedPremiumInstance,'ced_create_shipping_settings'));
	
	//enqueing the script and style.
	add_action( 'admin_enqueue_scripts',array($cedPremiumInstance,'cedEnqueAdminScript'));
	
	
	//download the csv format 
	add_action( 'wp_ajax_download_premium_matrix_csv',array($cedPremiumInstance,'prefix_ajax_download_premium_matrix_csv'));
	add_action( 'wp_ajax_nopriv_download_premium_matrix_csv',array($cedPremiumInstance,'prefix_ajax_download_premium_matrix_csv'));
	
	
	/**
	 * Add common admin settings'.
	 * @name add_common_settings()
	 * @param none
	 * @author CedCommerce<plugins@cedcommerce.com>
	 * @link http://cedcommerce.com/
	 */
	
	function add_common_settings(){
		$cedPremiumInstance = cedPremiumMatrix::getInstance();
		add_action('admin_menu', array($cedPremiumInstance,'ced_create_admin_premium_matrix_settings'));
		add_action('woocommerce_product_options_shipping',array($cedPremiumInstance,'add_custom_shipping_settings'));
	}
	add_action('save_post', array($cedPremiumInstance,'save_custom_shipping_settings'));
	
	
	/**
	 * load language'.
	 * @name ced_wuoh_load_text_domain()
	 * @param none
	 * @author CedCommerce<plugins@cedcommerce.com>
	 * @link http://cedcommerce.com/
	 */
	
	function ced_pmr_load_text_domain($name)
	{
		$domain = PMR_DOMIAN;
		$locale = apply_filters( 'plugin_locale', get_locale(), $domain );
		load_textdomain( $domain, CEDPREMIUM_DIR_PATH .'languages/'.$domain.'-' . $locale . '.mo' );
		$var=load_plugin_textdomain( PMR_DOMIAN, false, plugin_basename( dirname(__FILE__) ) . '/languages' );
	}
	add_action('plugins_loaded', 'ced_pmr_load_text_domain');
}else{
 
	/**
	 * Show admin notice if Woocommerce is not activated.
	 * @name ced_pmr_plugin_error_notice()
	 * @param none
	 * @author CedCommerce<plugins@cedcommerce.com>
	 * @link http://cedcommerce.com/
	 */
	
 		function ced_pmr_plugin_error_notice(){ ?>
  			 <div class="error notice is-dismissible">
   				<p><?php _e( 'Woocommerce is not activated, Please activate Woocommerce first to install Premium Matrix .', PMR_DOMIAN ); ?></p>
   			</div>
   		<?php } ?>
  		<?php add_action( 'admin_init', 'ced_pmr_plugin_deactivate' );  
 
  		
  		/**
  		 * Deactive premium matrix rate if Woocommerce is not activated.
  		 * @name ced_pmr_plugin_deactivate()
  		 * @param none
  		 * @author CedCommerce<plugins@cedcommerce.com>
  		 * @link http://cedcommerce.com/
  		 */
  		function ced_pmr_plugin_deactivate(){
			  deactivate_plugins( plugin_basename( FILE ) );
			  add_action( 'admin_notices', 'ced_pmr_plugin_error_notice' );
		 }
	}	
