<?php
/**
 * Plugin Name: WooCommerce Box Office
 * Version: 1.1.4
 * Plugin URI: https://www.woocommerce.com/products/woocommerce-box-office/
 * Description: The ultimate event ticket management system, built right on top of WooCommerce.
 * Author: WooCommerce
 * Author URI: https://woocommerce.com/
 * License: GPL-2.0+
 * Requires at least: 4.4
 * Tested up to: 4.6.1
 * Text Domain: woocommerce-box-office
 * Domain Path: /languages
 *
 * Copyright (c) 2017 WooCommerce
 *
 * @package WordPress
 * @author WooCommerce
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Required functions
 */
if ( ! function_exists( 'woothemes_queue_update' ) ) {
	require_once( 'woo-includes/woo-functions.php' );
}

/**
 * Plugin updates
 */
woothemes_queue_update( plugin_basename( __FILE__ ), 'e704c9160de318216a8fa657404b9131', '1628717' );

if ( is_woocommerce_active() ) {

	// Load main plugin class.
	require_once( 'includes/class-wc-box-office.php' );

	/**
	 * Returns the main instance of WC_Box_Office to prevent the need to use globals.
	 *
	 * @since  1.0.0
	 * @return object WC_Box_Office.
	 */
	function WCBO() {
		$instance = WC_Box_Office::instance( __FILE__, '1.1.4' );

		return $instance;
	}

	/**
	 * Init Box Office.
	 *
	 * @since 1.1.2
	 */
	function _wcbo_init() {
		WCBO()->init();
	}
	add_action( 'plugins_loaded', '_wcbo_init', 5 );
}
