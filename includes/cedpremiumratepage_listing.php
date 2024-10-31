<?php if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}

?>

<div tabindex="0" aria-label="Main content" id="wpbody-content">
	<div id="icon-woocommerce" class="icon32 icon32-woocommerce-settings"><br></div>
		<h2 class="nav-tab-wrapper woo-nav-tab-wrapper">
			<a class="nav-tab  nav-tab-active" href="<?php echo site_url();?>/wp-admin/admin.php?page=ced_pmr_menu_pages"><?php _e('List Rates',PMR_DOMIAN);?></a>
		</h2>
	
<br class="clear">
<div class="wrap">
<div> 
<p></p>
</div>
<?php 
	global $wpdb;
	$premium_matrix_table_name 			=	 $wpdb->prefix.'ced_premium_matrix_rate';

	if(isset($_POST['delete'])){
		if(check_admin_referer(basename(__FILE__),'pmr_delete_rates_nonce')){
		$unique_id = $_POST['unique_matrix_rate_id'];
		
		if(count($unique_id) > 0){
			$ids 	   = implode("','",$unique_id);
			$qry1	   = "DELETE FROM `$premium_matrix_table_name` WHERE id IN ('$ids');";
			$wpdb->query($qry1);
			$_SESSION['pmr_delete_success'] = 'Rates deleted Successfully ';
		}else{
			$_SESSION['pmr_delete_error'] = 'Please Select any Rates for delete';
		}
	}
}
	
	$qry 								= 	"SELECT * FROM `$premium_matrix_table_name` where 1;";
	$matrix_rate_data 					= 	$wpdb->get_results($qry);
	
	?>

<?php if(isset($_SESSION['pmr_add_rates_updated'])){ ?>
		<div class="updated settings-error notice is-dismissible" id="setting-error-settings_updated">
			<?php echo $_SESSION['pmr_add_rates_updated'];?>
		</div>
	<?php }
	unset($_SESSION['pmr_add_rates_updated']);
?>	

<?php if(isset($_SESSION['pmr_delete_success'])){ ?>
		<div class="updated settings-error notice is-dismissible" id="setting-error-settings_updated">
			<?php echo $_SESSION['pmr_delete_success'];?>
		</div>
	<?php }
	unset($_SESSION['pmr_delete_success']);
?>	
<?php // unset($_SESSION['upload_product_error']);
	 if(isset($_SESSION['pmr_delete_error'])){
			$all_error = $_SESSION['pmr_delete_error'];	
				?><div class="error settings-error notice is-dismissible"><?php echo $all_error.'<br>';?></div><?php 
	}
	unset($_SESSION['pmr_delete_error']);
	?>
	
<form action="" method="post">
<div id="delete_profile">
	<a href="<?php echo site_url()?>/wp-admin/admin.php?page=pmr_rates_settings&action=add-rates" class="button" ><?php _e('Add Rates',PMR_DOMIAN);?> </a>
	<?php if(!empty($matrix_rate_data)){?>
	<input type="submit" name="delete"  value ="Delete rates" class="button" id="pmr_delete">
	<input type="hidden" name="pmr_delete_rates_nonce" value="<?php echo wp_create_nonce(basename(__FILE__))?>" />
	<?php }?>
</div>

<br>
	<table class="wp-list-table widefat fixed striped posts">
	<thead>
	<tr>
		<th style="width:6%" class="manage-column column-cb check-column">
				<label for="cb-select-all-1" class="screen-reader-text"><?php _e('Select All',PMR_DOMIAN);?></label>
					<input type="checkbox" id="cb-select-all-1">
		</th>
			 <th  class="manage-column column-name sortable desc pmr_list">
			  	 <span class="pmr_heading_clr"><?php _e('ID',PMR_DOMIAN);?></span>
			  </th>
			  
			  <th class="manage-column column-name sortable desc pmr_list">
			  	 <span class="pmr_heading_clr"><?php _e('Country',PMR_DOMIAN);?></span>
			  </th>
			  
			  <th class="manage-column column-name sortable desc pmr_list">
			  	 <span class="pmr_heading_clr"><?php _e('Region/States',PMR_DOMIAN);?></span>
			  </th>
			  
			  <th class="manage-column column-name sortable desc pmr_list">
			  	<span class="pmr_heading_clr"><?php _e('City',PMR_DOMIAN);?></span>
			  </th>
			  
			   <th  class="manage-column column-name sortable desc pmr_list">
				<span class="pmr_heading_clr"><?php _e('Zipcode',PMR_DOMIAN);?></span>
			  </th>
			  
			  <th class="manage-column column-name sortable desc pmr_list">
			  	<span class="pmr_heading_clr"><?php _e('Weight From',PMR_DOMIAN);?></span>
			  </th>
			  
			  <th class="manage-column column-name sortable desc pmr_list">
			  	 <span class="pmr_heading_clr"><?php _e('Weight To',PMR_DOMIAN);?></span>
			  </th>
			  
			  <th class="manage-column column-name sortable desc pmr_list">
			  	<span class="pmr_heading_clr"><?php _e('Order Subtotal From',PMR_DOMIAN);?></span>
			  </th>
			  
			  
			  <th class="manage-column column-name sortable desc pmr_list">
			  	<span class="pmr_heading_clr"><?php _e('Order Subtotal To',PMR_DOMIAN);?></span>
			  </th>
			  
			  
			  <th class="manage-column column-name sortable desc pmr_list">
			  	 <span class="pmr_heading_clr"><?php _e('Quantity From',PMR_DOMIAN);?></span>
			  </th>
			  
			  <th class="manage-column column-name sortable desc pmr_list">
			  	 <span class="pmr_heading_clr"><?php _e('Quatity To',PMR_DOMIAN);?></span>
			  </th>
			  
			  <th class="manage-column column-name sortable desc pmr_list" >
			  	<span class="pmr_heading_clr"><?php _e('Shipping Price',PMR_DOMIAN);?></span>
			  </th>
			  
			  <th class="manage-column column-name sortable desc pmr_list">
			  	 <span class="pmr_heading_clr"><?php _e('Shipping Method',PMR_DOMIAN);?></span>
			  </th>
		
			  <th class="manage-column column-edit sortable desc pmr_list">
			  		<span class="pmr_heading_clr"><?php _e('Edit',PMR_DOMIAN);?></span>
			  	</th>
			  
		</tr>
	</thead>
	
	
	
	
	<tbody>
	<?php 
	
	
	if(!empty($matrix_rate_data)){
			foreach($matrix_rate_data as $index => $single_rate){
		?>
		<tr class="iedit author-self level-0 post-<?php echo $single_rate->id; ?> type-product status-publish hentry product_row" id="<?php echo $single_rate->id; ?>">
			<th class="check-column">
				<label for="cb-select-<?php ?>" class="screen-reader-text"></label>
					<input type="checkbox" value="<?php echo $single_rate->id; ?>" class="unique_check" name="unique_matrix_rate_id[]" id="cb-select-<?php echo $single_rate->id;?>">
				<div class="locked-indicator"></div>
			</th>
			<td class="name column-name list_rate_id">
					<span class="name" ><?php echo $single_rate->id;?></span>
			</td>
			<td class="name column-name list_country">
					<span class="name" ><?php echo $single_rate->country_id;?></span>
			</td>
			<td class="name column-name list_region">
					<span class="name" ><?php echo $single_rate->region_id;?></span>
			</td>
			<td class="name column-name list_city">
					<span class="name" ><?php echo $single_rate->city;?></span>
			</td>
			<td class="name column-name list_city">
					<span class="name" ><?php echo $single_rate->zipcode;?></span>
			</td>
			<td class="name column-name list_weight_from">
					<span class="name" ><?php echo $single_rate->weight_from;?></span>
			</td>
			<td class="name column-name list_weight_to">
					<span class="name" ><?php echo $single_rate->weight_to;?></span>
			</td>
			<td class="name column-name list_order_from">
					<span class="name" ><?php echo $single_rate->order_from;?></span>
			</td>
			<td class="name column-name list_order_to">
					<span class="name" ><?php echo $single_rate->order_to;?></span>
			</td>
			<td class="name column-name list_qty_from">
					<span class="name" ><?php echo $single_rate->qty_from;?></span>
			</td>
			<td class="name column-name list_qty_to">
					<span class="name" ><?php echo $single_rate->qty_to;?></span>
			</td>
			
			<td class="name column-name list_price">
					<span class="name" ><?php echo $single_rate->price;?></span>
			</td>
			
			<td class="name column-name list_shipping_method">
					<span class="name" ><?php echo $single_rate->shipping_method;?></span>
			</td>
			
			
			<td class="name column-edit">
				<span class="name">
				<a href="<?php echo site_url()?>/wp-admin/admin.php?page=pmr_rates_settings&action=edit-rates&rate_id=<?php echo $single_rate->id;?>" ><?php _e('Edit Rate','woocommerce-jet-integration');?> </a>
				</span>
			</td>
	 </tr>
	 <?php }//foreach?>
	 <?php }else{ ?>
	 	<tr class="iedit author-self level-0 post type-product status-publish hentry product_row" >
	 	
	 	<td colspan="14"><?php _e('No Premium Matrix Rate Created',PMR_DOMIAN);?></td>
	 	</tr>	
	 	<?php }?>
	</tbody>
</form>	




		<tfoot>
			<tr>
		<th style="width:6%" class="manage-column column-cb check-column">
				<label for="cb-select-all-1" class="screen-reader-text"><?php _e('Select All',PMR_DOMIAN);?></label>
					<input type="checkbox" id="cb-select-all-1">
			</th>
			 <th  class="manage-column column-name sortable desc pmr_list">
			  	 <span class="pmr_heading_clr"><?php _e('ID',PMR_DOMIAN);?></span>
			  </th>
			  
			  <th  class="manage-column column-name sortable desc pmr_list">
			  	<span class="pmr_heading_clr"><?php _e('Country',PMR_DOMIAN);?></span>
			  </th>
			  
			  <th class="manage-column column-name sortable desc pmr_list">
			  	 <span class="pmr_heading_clr"><?php _e('Region/States',PMR_DOMIAN);?></span>
			  </th>
			  
			  <th class="manage-column column-name sortable desc pmr_list">
			  	 <span class="pmr_heading_clr"><?php _e('City',PMR_DOMIAN);?></span>
			  </th>
			  
			    <th class="manage-column column-name sortable desc pmr_list">
			  	 <span class="pmr_heading_clr"><?php _e('Zipcode',PMR_DOMIAN);?></span>
			  </th>
			  
			  <th  class="manage-column column-name sortable desc pmr_list">
			  	 <span class="pmr_heading_clr"><?php _e('Weight From',PMR_DOMIAN);?></span>
			  </th>
			  
			  <th class="manage-column column-name sortable desc pmr_list">
			  	 <span class="pmr_heading_clr"><?php _e('Weight To',PMR_DOMIAN);?></span>
			  </th>
			  
			  <th class="manage-column column-name sortable desc pmr_list">
			  	 <span class="pmr_heading_clr"><?php _e('Order Subtotal From',PMR_DOMIAN);?></span>
			  </th>
			  
			  
			  <th class="manage-column column-name sortable desc pmr_list">
			  	 <span class="pmr_heading_clr"><?php _e('Order Subtotal To',PMR_DOMIAN);?></span>
			  </th>
			  
			  
			  <th  class="manage-column column-name sortable desc pmr_list">
			  	<span class="pmr_heading_clr"><?php _e('Quantity From',PMR_DOMIAN);?></span>
			  </th>
			  
			  <th class="manage-column column-name sortable desc pmr_list">
			  	 <span class="pmr_heading_clr"><?php _e('Quatity To',PMR_DOMIAN);?></span>
			  </th>
			  
			  <th class="manage-column column-name sortable desc pmr_list">
			  	 <span class="pmr_heading_clr"><?php _e('Shipping Price',PMR_DOMIAN);?></span>
			  </th>
			  
			  <th class="manage-column column-name sortable desc pmr_list">
			  	<span class="pmr_heading_clr"><?php _e('Shipping Method',PMR_DOMIAN);?></span>
			  </th>
		
			  <th class="manage-column column-edit sortable desc pmr_list">
			  		<span class="pmr_heading_clr"><?php _e('Edit',PMR_DOMIAN);?></span>
			  		
			  	</th>
		</tr>
		</tfoot>
   	 </table>
   </div>
   </div>
