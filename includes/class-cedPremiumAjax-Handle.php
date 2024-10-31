<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; 
}

class cedPremiumMatrixAjaxhandle{
	
	private static $_instance;
	
	public static function getInstance() {
		self::$_instance = new self;
		if( !self::$_instance instanceof self )
			self::$_instance = new self;
	     
		return self::$_instance;
	
	}
	public function __construct() {
		//call any action 	
	}
	
	/**
	 * Enqueue admin script 
	 * @name enqueue_premium_matrix_rate()
	 * @param none
	 * @author CedCommerce<plugins@cedcommerce.com>
	 * @link http://cedcommerce.com/
	 */
	
	public function enqueue_premium_matrix_rate(){
		wp_enqueue_script('admin-premium-matrix-rate',CEDPREMIUM_DIR_URL.'js/premium-matrix-rate.js');
		wp_enqueue_style( 'admin-premium-matrix-style',CEDPREMIUM_DIR_URL.'css/premium-matrix-rate.css' );
		$translation = array(
				'siteurl' 			=> 	site_url()
		);
		wp_localize_script('admin-premium-matrix-rate', 'helper', $translation);
	}
	
	/**
	 * Download csv format 
	 * @name download_pmr_csv_format()
	 * @param none
	 * @author CedCommerce<plugins@cedcommerce.com>
	 * @link http://cedcommerce.com/
	 */
	
	public function download_pmr_csv_format(){

		$dir_path = CEDPREMIUM_DIR_PATH.'/files/';
		
		// define the path to your download folder plus assign the file name
		$filename = 'ced-premium-matrix-rates.csv';
		$path = $dir_path.$filename;
		// check that file exists and is readable
		if (file_exists($path) && is_readable($path)) {
			// get the file size and send the http headers
			$size = filesize($path);
			header('Content-Type: application/octet-stream');
			header('Content-Length: '.$size);
			header('Content-Disposition: attachment; filename='.$filename);
			header('Content-Transfer-Encoding: binary');
			// open the file in binary read-only mode
			// display the error messages if the file can´t be opened
			$file = @ fopen($path, 'rb');
			if ($file) {
				// stream the file and exit the script when complete
				fpassthru($file);
				exit;
			} else {
				echo $err;
			}
		}
	}
	
}	
