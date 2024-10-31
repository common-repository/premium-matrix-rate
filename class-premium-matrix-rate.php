<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; 
}

require_once CEDPREMIUM_DIR_PATH.'includes/class-cedPremiumDBHelper.php';
require_once CEDPREMIUM_DIR_PATH.'includes/class-cedPremiumAdmin-Settings.php';
require_once CEDPREMIUM_DIR_PATH.'includes/class-cedPremiumAjax-Handle.php';
require_once CEDPREMIUM_DIR_PATH.'includes/class-cedPremiumRatePage.php';

class cedPremiumMatrix{
	
	private static $_instance;
	
	public static function getInstance() {
		self::$_instance = new self;
		if( !self::$_instance instanceof self )
			self::$_instance = new self;
	     
		return self::$_instance;
	
	}
	
	/**
	 * create instance of all class'.
	 * @name __construct()
	 * @param none
	 * @author CedCommerce<plugins@cedcommerce.com>
	 * @link http://cedcommerce.com/
	 */
	
	public function __construct() {
		
		$this->premiumdbActions		=	cedPremiumDBHelper::getInstance();
		$this->pmr_ajaxHandler		=	cedPremiumMatrixAjaxhandle::getInstance();
		$this->pmr_pagecreation		=	cedPremiumRatePage::getInstance();
	}
	
	/**
	 * create database structure of table ced_premium_matrix_rate '.
	 * @name ced_Premium_activate()
	 * @param none
	 * @author CedCommerce<plugins@cedcommerce.com>
	 * @link http://cedcommerce.com/
	 */
	
	public function ced_Premium_activate(){
		$this->premiumdbActions->Create_Premium_Rate_Tables();
	}
	
	/**
	 * drop database structure of table ced_premium_matrix_rate'.
	 * @name ced_Premium_activate()
	 * @param none
	 * @author CedCommerce<plugins@cedcommerce.com>
	 * @link http://cedcommerce.com/
	 */
	
	public function ced_Premium_deactivate(){
		$this->premiumdbActions->Drop_Premium_Rate_Tables();
	}
	
	
	/**
	 * create admin settings page'.
	 * @name ced_create_admin_premium_matrix_settings()
	 * @param none
	 * @author CedCommerce<plugins@cedcommerce.com>
	 * @link http://cedcommerce.com/
	 */
	
	public function ced_create_admin_premium_matrix_settings(){
		$this->pmr_pagecreation->create_Premium_Rate_Pages();
		
	}
	
	/**
	 * create admin settings under Woocommerce shipping settings'.
	 * @name ced_create_shipping_settings()
	 * @param none
	 * @author CedCommerce<plugins@cedcommerce.com>
	 * @link http://cedcommerce.com/
	 */
	
	public function ced_create_shipping_settings(){
		ced_register_premium_matrix::getInstance();
	}
	
	/**
	 * Download a csv file format'.
	 * @name prefix_ajax_download_premium_matrix_csv()
	 * @param none
	 * @author CedCommerce<plugins@cedcommerce.com>
	 * @link http://cedcommerce.com/
	 */
	
	public function prefix_ajax_download_premium_matrix_csv(){
		$this->pmr_ajaxHandler->download_pmr_csv_format();
	}
	
	
	/**
	 * Enqueue admin script'.
	 * @name cedEnqueAdminScript()
	 * @param none
	 * @author CedCommerce<plugins@cedcommerce.com>
	 * @link http://cedcommerce.com/
	 */
	
	public function cedEnqueAdminScript(){
		$this->pmr_ajaxHandler->enqueue_premium_matrix_rate();
	}
	
	
	/**
	 * Start session
	 * @name pmr_StartSession()
	 * @param none
	 * @author CedCommerce<plugins@cedcommerce.com>
	 * @link http://cedcommerce.com/
	 */
	
	public function pmr_StartSession(){
		if(!session_id()){
			session_start();
		}
	}
	
	
	/**
	 * End session
	 * @name pmr_EndSession()
	 * @param none
	 * @author CedCommerce<plugins@cedcommerce.com>
	 * @link http://cedcommerce.com/
	 */
	
	public function pmr_EndSession(){
		session_destroy();
	}
	
	/**
	 * Add custom settings under product data shipping settings.
	 * @name add_custom_shipping_settings()
	 * @param none
	 * @author CedCommerce<plugins@cedcommerce.com>
	 * @link http://cedcommerce.com/
	 */
	
	public function add_custom_shipping_settings(){
		
		global $post;
		
		woocommerce_wp_select(
			array(
			'id'      		=> 'pmr_free_shipping',
			'label'   		=> __( 'Free Shipping :',PMR_DOMIAN ),
			'desc_tip'      => true,
			'default' 		=> 'no',
			'description' 	=> __( 'If set yes then,shipping charge will not included for this product',PMR_DOMIAN ),
			'value'       	=> get_post_meta( $post->ID,'pmr_free_shipping', true ),
			'options'		=> array(
									'no' 	=> __( 'No', PMR_DOMIAN ),
									'yes' 	=> __( 'Yes', PMR_DOMIAN ),
							),
			)
			);
	}
	
	/**
	 * save custom shipping settings.
	 * @name save_custom_shipping_settings()
	 * @param int $post_id 
	 * @author CedCommerce<plugins@cedcommerce.com>
	 * @link http://cedcommerce.com/
	 */
	public function save_custom_shipping_settings($post_id){
		$set_val				=	sanitize_text_field($_POST['pmr_free_shipping']);
		update_post_meta($post_id,'pmr_free_shipping',$set_val);
	}
	
}	