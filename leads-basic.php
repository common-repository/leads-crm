<?php
	/**
	 *
	 * Plugin Name: Leads CRM for WordPress WooCommerce
	 * Plugin URI: https://www.bizswoop.com/wp/leads
	 * Description:  Add customer leads management and CRM functionality for WordPress and WooCommerce
	 * Version: 2.0.13
	 * Text Domain: zleadscrm
	 * WC requires at least: 2.4.0
	 * WC tested up  to: 5.5.2
	 * Author: BizSwoop a CPF Concepts, LLC Brand
	 * Author URI: http://www.bizswoop.com
	 */
	if ( ! defined( 'ABSPATH' ) ) {
		exit; // Exit if accessed directly.
	}

	define( 'ZLEADSCRM_BASIC_BASE_FILE', __FILE__ );
	define( 'ZLEADSCRM_BASIC_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );


	/* Loading Classes */
	require_once( ZLEADSCRM_BASIC_PLUGIN_DIR . 'helper/class-zleadscrm-basic-core.php' );

	register_activation_hook( __FILE__, array( 'ZLEADSCRM_BASIC_Core', 'plugin_activation' ) );
	register_deactivation_hook( __FILE__, array( 'ZLEADSCRM_BASIC_Core', 'plugin_deactivation' ) );

	add_action( 'init', array( 'ZLEADSCRM_BASIC_Core', 'init' ) );

	if ( is_admin() || ( defined( 'WP_CLI' ) && WP_CLI ) ) {
		require_once( ZLEADSCRM_BASIC_PLUGIN_DIR . 'helper/class-zleadscrm-basic-core-admin.php' );
		$adm_admin = new ZLEADSCRM_BASIC_Core_Admin();
	}

	// Functions
	require_once( ZLEADSCRM_BASIC_PLUGIN_DIR . 'leads-basic-functions.php' );
?>
