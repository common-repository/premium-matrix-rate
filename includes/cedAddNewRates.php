<?php 
if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}

	if(isset($_POST['save_rates'])){
		if(check_admin_referer(basename(__FILE__),'pmr_add_rates_nonce')){
		
		$country_val   				= 	sanitize_text_field($_POST['pwr_country']);
		$region_state_val 			= 	sanitize_text_field($_POST['pwr_states']);
		$city_val		 			= 	sanitize_text_field($_POST['pwr_city']);
		$zip_postal_val	 			= 	sanitize_text_field($_POST['pwr_zipcode']);
		$weight_from_val 			= 	sanitize_text_field($_POST['pwr_weight_from']);
		$weight_to_val	 			= 	sanitize_text_field($_POST['pwr_weight_to']);
		$order_subtotal_from_val 	= 	sanitize_text_field($_POST['pwr_order_from']);
		$order_subtotal_to_val	 	= 	sanitize_text_field($_POST['pwr_order_to']);
		$quantity_from_val	 		= 	sanitize_text_field($_POST['pwr_quantity_from']);
		$quantity_to_val 			= 	sanitize_text_field($_POST['pwr_quantity_to']);
		$shipping_price_val 		= 	sanitize_text_field($_POST['pwr_shipping_price']);
		$shipping_method_val 		= 	sanitize_text_field($_POST['pwr_shipping_method']);
		
		if(empty($country_val) || empty($region_state_val) || empty($city_val) || empty($zip_postal_val) || empty($weight_from_val) || empty($weight_to_val) || empty($order_subtotal_from_val) || empty($order_subtotal_to_val) || empty($quantity_from_val) || empty($quantity_to_val) || empty($shipping_price_val) || empty($shipping_method_val)){
			$_SESSION['add_rates_error'] = __('Fill all the Feilds Values',PMR_DOMIAN);
		}else{
			global $wpdb;
			$premium_matrix_table_name 			=	 $wpdb->prefix.'ced_premium_matrix_rate';
			
			if(isset($_POST['rate_id']) && !empty($_POST['rate_id'])){
				$rat_id = $_POST['rate_id'];
				
				$qry = "UPDATE `$premium_matrix_table_name` SET 
								`country_id` = '$country_val',
								`region_id` = '$region_state_val',
								`city` = '$city_val',
								`zipcode` = '$zip_postal_val',
								`weight_to` = '$weight_to_val',
								`weight_from` = '$weight_from_val',
								`order_from` = '$order_subtotal_from_val',
								`order_to` = '$order_subtotal_to_val',
								`qty_from` = '$quantity_from_val',
								`qty_to` = '$quantity_to_val',
								`price` = '$shipping_price_val',
								`shipping_method` = '$shipping_method_val' where id = $rat_id;";
				//die($qry);
				$wpdb->query($qry);
				$_SESSION['pmr_add_rates_updated'] = __('Rates Updated successfully',PMR_DOMIAN);
			}else{
			$qry = "INSERT INTO `$premium_matrix_table_name` (
																`country_id`,
																`region_id`,
																`city`,
																`zipcode`,
																`weight_from`,
																`weight_to`,
																`order_from`,
																`order_to`,
																`qty_from`,
																`qty_to`,
																`price`,
																`shipping_method`
																) VALUES
			('".$country_val."',
			'".$region_state_val."',
			'".$city_val."',
			'".$zip_postal_val."',
			$weight_from_val,
			$weight_to_val,
			$order_subtotal_from_val,
			$order_subtotal_to_val,
			$quantity_from_val,
			$quantity_to_val,
			$shipping_price_val,
			'".$shipping_method_val."'
			);";
			
			$wpdb->query($qry);
			$_SESSION['pmr_add_rates_success'] = __('Rates Added Successfully',PMR_DOMIAN);
			
			}
		}
	}
 }
	

	if(isset($_GET['action']) && isset($_GET['rate_id'])){
		
		global $wpdb;
		$premium_matrix_table_name 		=	 $wpdb->prefix.'ced_premium_matrix_rate';
		$rate_id 						= 	$_GET['rate_id'];
		
			if(!empty($rate_id)){
				$qry 							= 	"SELECT * FROM `$premium_matrix_table_name` where id = $rate_id;";
				$rates_data 					= 	$wpdb->get_results($qry);
				
				$rate 							= 	$rates_data['0'];
				
				$rate_id 						=   $rate->id;
				$country_set   					= 	$rate->country_id;
				$region_state_set 				= 	$rate->region_id;
				$city_set		 				= 	$rate->city;
				$zip_postal_set	 				= 	$rate->zipcode;
				$weight_from_set 				= 	$rate->weight_from;
				$weight_to_set	 				= 	$rate->weight_to;
				$order_subtotal_from_set 		= 	$rate->order_from;
				$order_subtotal_to_set	 		= 	$rate->order_to;
				$quantity_from_set	 			= 	$rate->qty_from;
				$quantity_to_set 				= 	$rate->qty_to;
				$shipping_price_set 			= 	$rate->price;
				$shipping_method_set 			= 	$rate->shipping_method;
			}
		
		
	}?>

<div tabindex="0" aria-label="Main content" id="wpbody-content">
<div id="icon-woocommerce" class="icon32 icon32-woocommerce-settings"><br></div>
<h2 class="nav-tab-wrapper woo-nav-tab-wrapper">
	<a class="nav-tab nav-tab-active " href="<?php echo site_url();?>/wp-admin/admin.php?page=ced_pmr_menu_pages"><?php _e('List Rates',PMR_DOMIAN);?></a>
</h2>
<div >
	<?php // unset($_SESSION['upload_product_error']);
	 if(isset($_SESSION['add_rates_error'])){
			$all_error = $_SESSION['add_rates_error'];	
				?><div class="error settings-error notice is-dismissible"><?php echo $all_error.'<br>';?></div><?php 
	}
	unset($_SESSION['add_rates_error']);
	?>
	
	</div>
	<?php if(isset($_SESSION['pmr_add_rates_success'])){ ?>
		<div class="updated settings-error notice is-dismissible" id="setting-error-settings_updated">
			<?php echo $_SESSION['pmr_add_rates_success'];?>
		</div>
	<?php }
	unset($_SESSION['pmr_add_rates_success']);
	?>	
<br class="clear">
	<div class="wrap jet-page">
		<h2><?php _e('New Rates',PMR_DOMIAN)?></h2>
			<div style="display:block !important" class="updated" id="message">
				<p><b><?php _e('You Can Create and edit Matrix Rates Here',PMR_DOMIAN)?></b></p>
			</div>
	
<form action="" method="post">
<div id="poststuff">
   <div class="metabox-holder columns-2" id="post-body">
   <!-- #postbox-container-2 -->
<div class="postbox-container" id="postbox-container-2">
	<div class="meta-box-sortables ui-sortable">
		<div id="profileGeneralSettings" class="postbox">
			<h3><span><?php _e('Matrix Rates settings',PMR_DOMIAN)?></span></h3>
			<div class="inside">
				<div style="margin-bottom:5px;" id="pmr_custom" >
				<input type="hidden" name="pmr_add_rates_nonce" value="<?php echo wp_create_nonce(basename(__FILE__))?>" />
					<div>
						<label class="pmr_text_label"><?php _e('Country',PMR_DOMIAN)?><span class="pmr_required">*</span></label>
							<input type="text" class="pmr_add_input" value="<?php echo $country_set;?>" size="30" name="pwr_country">
					</div>
					
					<div>
						<label class="pmr_text_label"><?php _e('Region/States',PMR_DOMIAN)?><span class="pmr_required">*</span></label>
							<input type="text" class="pmr_add_input" value="<?php echo $region_state_set; ?>" size="30" name="pwr_states">
					</div>
					
					<div>
						<label class="pmr_text_label"><?php _e('City',PMR_DOMIAN)?><span class="pmr_required">*</span></label>
							<input type="text"  class="pmr_add_input" value="<?php echo $city_set;?>" size="30" name="pwr_city">
					</div>
					
					<div>
						<label class="pmr_text_label"><?php _e('Zipcode',PMR_DOMIAN)?><span class="pmr_required">*</span></label>
							<input type="text" class="pmr_add_input" value="<?php echo $zip_postal_set; ?>" size="30" name="pwr_zipcode">
					</div>
					
					<div>
						<label class="pmr_text_label"><?php _e('Weight From',PMR_DOMIAN)?><span class="pmr_required">*</span></label>
							<input type="text"  class="pmr_add_input" min='0' value="<?php echo $weight_from_set;?>" size="30" name="pwr_weight_from">
					</div>
					
					<div>
						<label class="pmr_text_label"><?php _e('Weight To',PMR_DOMIAN)?><span class="pmr_required">*</span></label>
							<input type="text"  class="pmr_add_input" min='0' value="<?php echo $weight_to_set?>" size="30" name="pwr_weight_to">
					</div>
					
					<div>
						<label class="pmr_text_label"><?php _e('Order Subtotal From',PMR_DOMIAN)?><span class="pmr_required">*</span></label>
							<input type="text" class="pmr_add_input" min='0' value="<?php echo $order_subtotal_from_set?>" size="30" name="pwr_order_from">
					</div>
					
					<div>
						<label class="pmr_text_label"><?php _e('Order Subtotal To',PMR_DOMIAN)?><span class="pmr_required">*</span></label>
							<input type="text"  class="pmr_add_input" min='0' value="<?php echo $order_subtotal_to_set;?>" size="30" name="pwr_order_to">
					</div>
					
					<div>
						<label class="pmr_text_label"><?php _e('Quantity From',PMR_DOMIAN)?><span class="pmr_required">*</span></label>
							<input type="number" class="pmr_add_input" min='0' value="<?php echo $quantity_from_set;?>" size="30" name="pwr_quantity_from">
					</div>
					
					<div>
						<label class="pmr_text_label"><?php _e('Quatity To',PMR_DOMIAN)?><span class="pmr_required">*</span></label>
							<input type="number"  class="pmr_add_input" min='0' value="<?php echo $quantity_to_set;?>" size="30" name="pwr_quantity_to">
					</div>
					
					<div>
						<label class="pmr_text_label"><?php _e('Shipping Price',PMR_DOMIAN)?><span class="pmr_required">*</span></label>
							<input type="text" min='0' class="pmr_add_input" value="<?php echo $shipping_price_set;?>" size="30" name="pwr_shipping_price">
					</div>
					
					<div>
						<label class="pmr_text_label"><?php _e('Shipping Method ',PMR_DOMIAN)?><span class="pmr_required">*</span></label>
							<input type="text" class="pmr_add_input" value="<?php echo $shipping_method_set;?>" size="30" name="pwr_shipping_method">
					</div>
					
				</div>
				<br class="clear">
			</div>
		</div>
		</div>
		
		</div> <!-- .meta-box-sortables -->
	<div class="postbox-container" id="postbox-container-1">
		<div>
			<!-- first sidebox -->
		<div class="postbox">
			<h3><span><?php _e('Use',PMR_DOMIAN)?></span></h3>
			<div class="inside">
				<div class="submitbox" id="ratessubmitpost">
					<div class="misc-pub-section">
						<p><?php _e('You can create new rates and All Values Should be Filled',PMR_DOMIAN)?></p>
					</div>
					<div id="major-publishing-actions">
						<div id="publishing-action">
							<input type="hidden" value="<?php echo $rate_id;?>" name="rate_id">
							<input type="submit" name="save_rates" class="button-primary" id="publish" value="Save rates">
						</div>
						<div class="clear"></div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div> <!-- #postbox-container-1 -->
</div> 
	</div> <!-- #post-body -->
		<br class="clear">
		</form>
	</div> <!-- #poststuff -->
</div>

