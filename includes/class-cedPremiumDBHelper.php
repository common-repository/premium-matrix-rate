<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


class cedPremiumDBHelper{

	private static $_instance;

	public static function getInstance() {

		if( !self::$_instance instanceof self )
			self::$_instance = new self;

		return self::$_instance;

	}
	
	public function __construct() {
		// call any action
	}
	
	/**
	 * create premium rate table 
	 * @name Create_Premium_Rate_Tables()
	 * @param none
	 * @author CedCommerce<plugins@cedcommerce.com>
	 * @link http://cedcommerce.com/
	 */
	
	public function Create_Premium_Rate_Tables(){
		global $wpdb;
		
		$premium_matrix_table_name 			=	 $wpdb->prefix.'ced_premium_matrix_rate';
		
		/*
		 * We'll set the default character set and collation for this table.
		* If we don't do this, some characters could end up being converted
		* to just ?'s when saved in our table.
		*/
		$charset_collate = '';
		
		if ( ! empty( $wpdb->charset ) ) {
			$charset_collate = "DEFAULT CHARACTER SET {$wpdb->charset}";
		}
			
		if ( ! empty( $wpdb->collate ) ) {
			$charset_collate .= " COLLATE {$wpdb->collate}";
		}
		
		$create_tbl = "
		CREATE TABLE IF NOT EXISTS `$premium_matrix_table_name` (
		`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
 		`website_id` int(6) NOT NULL DEFAULT '0',
		`vendor_id` varchar(10) NOT NULL DEFAULT 'admin',
  		`country_id` text,
  		`region_id` varchar(10) NOT NULL DEFAULT '',
  		`city` varchar(30) NOT NULL DEFAULT '',
  		`zipcode` varchar(10) NOT NULL DEFAULT '',
  		`weight_from` decimal(12,4) NOT NULL DEFAULT '0.0000',
  		`weight_to` decimal(12,4) NOT NULL DEFAULT '0.0000',
  		`order_from` decimal(12,4) NOT NULL DEFAULT '0.0000',
  		`order_to` decimal(12,4) NOT NULL DEFAULT '0.0000',
  		`qty_from` int(6) NOT NULL DEFAULT '0',
  		`qty_to` int(6) NOT NULL DEFAULT '0',
  		`price` decimal(12,4) NOT NULL DEFAULT '0.0000',
  		`shipping_method` varchar(30) NOT NULL DEFAULT '',
  		`shipping_label` varchar(30) NOT NULL DEFAULT '',
		PRIMARY KEY (`id`)
		);";
		
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $create_tbl );
	}
	
	/**
	 * Drop premium rate table
	 * @name Drop_Premium_Rate_Tables()
	 * @param none
	 * @author CedCommerce<plugins@cedcommerce.com>
	 * @link http://cedcommerce.com/
	 */
	public function Drop_Premium_Rate_Tables(){
		global $wpdb;
		$premium_matrix_table_name 		=	 $wpdb->prefix.'ced_premium_matrix_rate';
		$drop_qry						= "DROP TABLE ". $premium_matrix_table_name;
		$wpdb->query($drop_qry);
	}
}	