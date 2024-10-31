<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$path = WP_PLUGIN_DIR.'/woocommerce/woocommerce.php';
include_once($path);
class ced_register_premium_matrix extends WC_Shipping_Method {

	private static $_instance;
	public static function getInstance() {

		if( !self::$_instance instanceof self )
			self::$_instance = new self;

		return self::$_instance;
	}

	/**
	 * override Woocommerce shipping settings
	 * @name __construct()
	 * @param none
	 * @author CedCommerce<plugins@cedcommerce.com>
	 * @link http://cedcommerce.com/
	 */
	
	public function __construct() {
		
		add_filter( 'woocommerce_shipping_methods',array($this,'add_premium_matrix_rate'));
		
		$this->id                 			= 'ced_premium_matrix_rate'; // Id for your shipping method. Should be unique.
		$this->method_title       			= __( 'Premium Matrix Rate', PMR_DOMIAN );  // Title shown in admin
		$this->method_description 			= __( 'A shipping calculator for Premium Matrix rate', PMR_DOMIAN ); // Description shown in admin
			
		$this->init();
	}
	
	/**
	 * Register new shipping methods
	 * @name add_premium_matrix_rate()
	 * @param array $methods
	 * @return array $methods
	 * @author CedCommerce<plugins@cedcommerce.com>
	 * @link http://cedcommerce.com/
	 */
	
	public function add_premium_matrix_rate($methods){
		$methods[] = 'ced_register_premium_matrix';
		return $methods;
	}
	
	/**
	 * Initalize new shipping settings
	 * @name init()
	 * @param none
	 * @author CedCommerce<plugins@cedcommerce.com>
	 * @link http://cedcommerce.com/
	 */
	
	function init() {
			
		// Load the settings API
		$this->init_form_fields(); // This is part of the settings API. Override the method to add your own settings
		$this->init_settings(); // This is part of the settings API. Loads settings you previously init.
			
		$index = 'woocommerce_'.$this->id.'_settings';
		$pmr_admin_settings = get_option($index,true);
		
		if($pmr_admin_settings['cedpmr_enabled'] == 'yes'){
			$this->enabled = 'yes';
		}
		$this->title = $this->method_title; // Shown in drop down and admin order screen
	
		// Save settings in admin if you have any defined
		add_action( 'woocommerce_update_options_shipping_' . $this->id, array( $this, 'process_admin_options' ) );
			
	}
	
	/**
	 * Initialise form fields using WooCommerce API
	 * @name init_form_fields()
	 * @param none
	 * @author CedCommerce<plugins@cedcommerce.com>
	 * @link http://cedcommerce.com/
	 */
	
	function init_form_fields() {
		$this->form_fields = array(
				'cedpmr_enabled' => array(
						'title' 		=> __( 'Enable', PMR_DOMIAN ),
						'type' 			=> 'checkbox',
						'label' 		=> __( 'Enable this shipping method', PMR_DOMIAN ),
						'default' 		=> 'no',
				),
				'cedpmr_title' => array(
						'title' 		=> __( 'Title', PMR_DOMIAN ),
						'description' 		=> __( 'This controls the title which the user sees during checkout.', PMR_DOMIAN ),
						'type' 			=> 'text',
						'default' 		=> 'Premium Matrix Rate',
						'desc_tip'     => true,
				),
				'cedpmr_rate_type' => array(
						'title' 		=> __( 'Rate Calculation Condition', PMR_DOMIAN ),
						'type' 			=> 'select',
						'default' 		=> 'cedpmr_order',
						'options'		=> array(
								'cedpmr_quantity' 	=> __( 'Quantity and Destination', PMR_DOMIAN ),
						),
				),
				'cedpmr_virtual_type' => array(
						'title' 		=> __( 'Use Virtual Product For Calculation', PMR_DOMIAN ),
						'type' 			=> 'select',
						'default' 		=> 'no',
						'desc_tip'     => true,
						'description' 		=> __( 'If set to yes then calculate shipping for virtual product', PMR_DOMIAN ),
						'options'		=> array(
								'no' 	=> __( 'No', PMR_DOMIAN ),
								'yes' 	=> __( 'Yes', PMR_DOMIAN ),
						),
				),
				
				'cedpmr_skip_free_shipping' => array(
						'title' 		=> __( 'Skip Free shipping Product for calculation', PMR_DOMIAN ),
						'type' 			=> 'select',
						'desc_tip'     => true,
						'description' 		=> __( 'If set to yes then calculate shipping for free Shipping product', PMR_DOMIAN ),
						'default' 		=> 'yes',
						'options'		=> array(
								'no' 	=> __( 'No', PMR_DOMIAN ),
								'yes' 	=> __( 'Yes', PMR_DOMIAN ),
						),
				),
				
				'cedpmr_min_order_amount' => array(
						'title' 		=> __( 'Minimum order amount for free shipping', PMR_DOMIAN ),
						'type' 			=> 'price',
						'desc_tip'     => true,
						'description' 		=> __( 'Minimum order Amount For Free shipping',PMR_DOMIAN ),
				),
				
				'cedpmr_max_weight_free_ship' => array(
						'title' 		=> __( 'Maximum Weight For Free Shipping', PMR_DOMIAN ),
						'type' 			=> 'number',
						'desc_tip'     => true,
						'description' 		=> __( 'Maximum Allowed Weight For Free shipping',PMR_DOMIAN ),
				),
				
				"cedpmr_export_file" => array(
						"title" => __("Export", PMR_DOMIAN),
						'desc_tip'     => true,
						"description" => __("Exports the CSV Format For Premium Matrix Rate ", PMR_DOMIAN),
						"type" => "button",
						"default" => __("Export", PMR_DOMIAN),
						"class" => "button-secondary",
						"css" => "width: 8em",
						"desc_tip" => true,
				),
		);
	}
	
	
	/**
	 * Calculate shipping cost. This is called by WooCommerce
	 * @name calculate_shipping()
	 * @param array $package
	 * @author CedCommerce<plugins@cedcommerce.com>
	 * @link http://cedcommerce.com/
	 */
	
	public function calculate_shipping( $package ) {
	
		$index = 'woocommerce_'.$this->id.'_settings';
		$pmr_admin_settings = get_option($index,true);
		
		
		if($pmr_admin_settings['cedpmr_enabled'] == 'yes'){
			
			$premium_rates = $this->ced_get_premium_matrix_rates();
			
			if(!empty($premium_rates)){
				
				if(isset($premium_rates['free_shipping']) && $premium_rates['free_shipping'] == 'yes'){
					$rate = array(
							'id' => $this->id,
							'label' => 'Premium Matrix Rate',
							'cost' => 0,
					);
					//Register the rate
					$this->add_rate( $rate );
				}elseif($premium_rates['empty_details'] == 'yes'){
						return;
				}else{
					foreach($premium_rates as $key => $val){
						$rate = array(
								'id' => $this->id.$key,
								'label' => $val->shipping_method,
								'cost' => $val->price,
						);
						//Register the rate
						$this->add_rate( $rate );
					}
				}
				
			}else{
				return;
			}
		}	
	}
	
	/**
	 * Calculate shipping cost.
	 * @name ced_get_premium_matrix_rates()
	 * @param none
	 * @return array mixed
	 * @author CedCommerce<plugins@cedcommerce.com>
	 * @link http://cedcommerce.com/
	 */
	
	public function ced_get_premium_matrix_rates(){
		
		global $woocommerce,$wpdb;
		
		$index 							=	'woocommerce_'.$this->id.'_settings';
		$pmr_admin_settings				= 	get_option($index,true);
		
		$premium_matrix_table_name 		=	$wpdb->prefix.'ced_premium_matrix_rate';
		$all_cart_items					=   $woocommerce->cart->get_cart();
		
		$country				  		=  	$woocommerce->customer->get_shipping_country();
		$state    						=  	$woocommerce->customer->get_shipping_state();
		
		$zip			 				=	$woocommerce->customer->get_shipping_postcode();
		
		$total_cart_quantity 			= 	$woocommerce->cart->get_cart_contents_count();
		
		$total_cart_price				= 	$woocommerce->cart->cart_contents_total;
		$cart_weight					=	$woocommerce->cart->cart_contents_weight;
		
		$virtual_settings 				=   $pmr_admin_settings['cedpmr_virtual_type'];
		
		if(empty($country) || empty($state) || empty($zip)){
			return $free_check['empty_details'] = 'yes';
		}
		
	foreach($all_cart_items as $key => $single_item){

		$product_id			= 	$single_item['product_id'];
		$product_check 		= 	get_product($product_id);
		
		$qty 				= 	0;
		$price 				= 	0;
		$total_weight 		= 	0;
		$total_cost 		= 	0;
		
		/****************************** skip virtual from cart according to admin settings  ****************************************/
		
		if($virtual_settings == 'no'){
				
				if($product_check->is_type('simple') && $product_check->is_virtual('yes'))
				{
					$qty 					=	$single_item['quantity'];
					$total_cost 			=	$single_item['line_subtotal'];
					
					$total_cart_quantity 	= 	(int)($total_cart_quantity - $qty);
					$total_cart_price	    = 	floatval($total_cart_price) - floatval($total_cost);
				}
				
				if($product_check->is_type('variable') && $product_check->is_virtual('yes'))
				{
						$qty 					=	$single_item['quantity'];
						$total_cost 			=	$single_item['line_subtotal'];
							
						$total_cart_quantity 	= 	(int)($total_cart_quantity - $qty);
						$total_cart_price	    = 	floatval($total_cart_price) - floatval($total_cost);
						
				}
				
		}
		
		/****************************** End Virtual Settings ****************************************/	

		
		/****************************** free shipping check and exclude free shipping charge  ************/
		
		$free_ship_admin_setting 			= 	$pmr_admin_settings['cedpmr_skip_free_shipping'];
		$pro_ship_settings					=	get_post_meta($product_id,'pmr_free_shipping',true);
		
		
		
			if($free_ship_admin_setting == 'yes'){
				
				if($pro_ship_settings == 'yes'){
					
					if($product_check->is_type('simple')){
						
						$qty 					=	$single_item['quantity'];
						$total_cost 			=	(float)$single_item['line_subtotal'];
							
						$total_cart_quantity 	= 	(int)($total_cart_quantity - $qty);
						$total_cart_price	    = 	floatval($total_cart_price) - floatval($total_cost);
						
						$pro_weight				=	get_post_meta($product_id,'_weight',true);
						$total_weight			=	$pro_weight*$qty;
						$cart_weight		   -=	$total_weight;
					}
		
					if($product_check->is_type('variable')){
						
							$sku					=	$single_item['variation_id'];
							$qty 					=	$single_item['quantity'];
	
							$total_cost 			=	$single_item['line_subtotal'];
								
							$total_cart_quantity 	= 	(int)($total_cart_quantity - $qty);
							$total_cart_price	    = 	floatval($total_cart_price) - floatval($total_cost);
							
							$pro_weight				=	get_post_meta($sku,'_weight',true);
							$total_weight			=	(float)$pro_weight*$qty;
							
							$cart_weight		   -=	$total_weight;
						
					}
				}
			 }	
		   
		 /****************************** free shipping check and exclude free shipping charge  ************/
	  }//foreach
	  
	 	$condition 						=	$pmr_admin_settings['cedpmr_rate_type'];
		$query_condition 				=   '';	
		if(empty($state))
			$state = '';
		
		if($condition == 'cedpmr_quantity'){
			$query_condition = ' `qty_from` <= '.$total_cart_quantity.' AND `qty_to` >= '.$total_cart_quantity;
		}
		
		$orWhereclause = '(' . implode(') OR (', array(
				$query_condition,
				"`country_id` LIKE '$country' AND `region_id` = '$state' AND `zipcode` = $zip",
				"`country_id` LIKE '$country' AND `region_id` = '*' AND `zipcode` = $zip",
				"`country_id` LIKE '$country' AND `region_id` = 0 AND `zipcode` = $zip",
					
				"`country_id` LIKE '$country' AND `region_id` = '$state' AND `zipcode` = '*'",
				"`country_id` LIKE '$country' AND `region_id` = '*' AND `zipcode` = '*'",
				"`country_id` LIKE '$country' AND `region_id` = 0 AND `zipcode` = '*'",
				
					
				"`country_id` LIKE '0' AND `region_id` = '$state' AND `zipcode` = '*'",
				"`country_id` LIKE '0' AND `region_id` = 0 AND `zipcode` = '*'",
					
		)) . ')';
		
		$qry 			= 	"SELECT * FROM `$premium_matrix_table_name` where $orWhereclause";
		
		$all_rate_data 	= 	$wpdb->get_results($qry);
		
		
		$free_check = array();
		
		$cedpmr_min_order_amount 		= $pmr_admin_settings['cedpmr_min_order_amount'];
		$cedpmr_max_weight_free_ship 	= $pmr_admin_settings['cedpmr_max_weight_free_ship'];
		
	
	
		if($cart_weight >= $cedpmr_max_weight_free_ship || $total_cart_price >= $cedpmr_min_order_amount ){
			$free_check['free_shipping'] = 'yes';
			return  $free_check;
		}else{
			return	$all_rate_data;
		}
		
		
	}
}