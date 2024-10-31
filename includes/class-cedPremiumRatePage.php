<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class cedPremiumRatePage{

	private static $_instance;

	public static function getInstance() {

		if( !self::$_instance instanceof self )
			self::$_instance = new self;

		return self::$_instance;

	}
	
	/**
	 * Register Admin menu
	 * @name create_Premium_Rate_Pages()
	 * @param none
	 * @author CedCommerce<plugins@cedcommerce.com>
	 * @link http://cedcommerce.com/
	 */
	
	public function create_Premium_Rate_Pages(){
		add_menu_page(__('Premium Matrix Rates',PMR_DOMIAN),__('Premium Matrix Rates',PMR_DOMIAN), 'manage_options','ced_pmr_menu_pages','', '','27.8');
		$this->add_all_submenu_menu();
	}
	
	/**
	 * Register Admin sub menu menu
	 * @name add_all_submenu_menu()
	 * @param none
	 * @author CedCommerce<plugins@cedcommerce.com>
	 * @link http://cedcommerce.com/
	 */ 
	 
	public function add_all_submenu_menu(){
		add_submenu_page( 'ced_pmr_menu_pages',__('List Rates',PMR_DOMIAN),__('List Rates',PMR_DOMIAN),'manage_options','ced_pmr_menu_pages' ,array($this,'list_premium_matrix_rates'));
		add_options_page(__('New Rates',PMR_DOMIAN),__('New Rates',PMR_DOMIAN), 'manage_options', 'pmr_rates_settings', array($this,'add_new_matrix_rates'));
	}
	
	/**
	 * Loads Premium matrix rate listing
	 * @name list_premium_matrix_rates()
	 * @param none
	 * @author CedCommerce<plugins@cedcommerce.com>
	 * @link http://cedcommerce.com/
	 */
	
	public function list_premium_matrix_rates(){
		if(!current_user_can('manage_options'))
		{
			wp_die(__('You don\'t have sufficient permissions to access this page.',PMR_DOMIAN));
		}
		include_once('cedpremiumratepage_listing.php');
	}
	
	/**
	 * Add new rate
	 * @name add_new_matrix_rates()
	 * @param none
	 * @author CedCommerce<plugins@cedcommerce.com>
	 * @link http://cedcommerce.com/
	 */
	
	public function add_new_matrix_rates(){
		if(!current_user_can('manage_options'))
		{
			wp_die(__('You don\'t have sufficient permissions to access this page.',PMR_DOMIAN));
		}
		include_once('cedAddNewRates.php');
	}
	
}	
