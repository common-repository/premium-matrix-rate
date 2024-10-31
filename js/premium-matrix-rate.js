
jQuery.noConflict();
jQuery(document).ready(function(){
	
	/**
	 * Download Premium Matrix Rate CSV Format
	 */
	jQuery('#woocommerce_ced_premium_matrix_rate_cedpmr_export_file').click(function(){
		
		jQuery.post(
				ajaxurl,
				{
					'action' : 'download_premium_matrix_csv',
					'download_premium_rate_csv_format':'download_csv',
				},
				function(response){
					jQuery('.license_loading_image').remove();
					window.location.href = helper.siteurl+'/wp-content/plugins/premium-matrix-rate/files/ced-premium-matrix-rates.csv';
					return;
				}
			);
	}); // End Download CSV
	
	jQuery('a').each(function() {
		  jQuery("a[href^='options-general.php?page=pmr_rates_settings']").remove();
	});
	
});