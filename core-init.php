<?php 
/*
*
*	***** Aspire Woocommerce Procashier  *****
*
*	This file initializes all AWP Core components
*	
*/
// If this file is called directly, abort. //
if ( ! defined( 'WPINC' ) ) {die;} // end if
// Define Our Constants
define('AWP_CORE_INC',dirname( __FILE__ ).'/assets/inc/');
define('AWP_CORE_IMG',plugins_url( 'assets/img/', __FILE__ ));
define('AWP_CORE_CSS',plugins_url( 'assets/css/', __FILE__ ));
define('AWP_CORE_JS',plugins_url( 'assets/js/', __FILE__ ));
/*
*
*  Register CSS
*
*/
function awp_register_core_css(){
wp_enqueue_style('awp-core', AWP_CORE_CSS . 'awp-core.css',null,time(),'all');
};
add_action( 'wp_enqueue_scripts', 'awp_register_core_css' );    
/*
*
*  Register JS/Jquery Ready
*
*/
function awp_register_core_js(){
// Register Core Plugin JS	
wp_enqueue_script('awp-core', AWP_CORE_JS . 'awp-core.js','jquery',time(),true);
};
add_action( 'wp_enqueue_scripts', 'awp_register_core_js' );    
/*
*
*  Includes
*
*/ 
// Load the Functions
if ( file_exists( AWP_CORE_INC . 'awp-core-functions.php' ) ) {
	require_once AWP_CORE_INC . 'awp-core-functions.php';
}     
// Load the ajax Request
if ( file_exists( AWP_CORE_INC . 'awp-ajax-request.php' ) ) {
	require_once AWP_CORE_INC . 'awp-ajax-request.php';
} 
// Load the Shortcodes
if ( file_exists( AWP_CORE_INC . 'awp-shortcodes.php' ) ) {
	require_once AWP_CORE_INC . 'awp-shortcodes.php';
}