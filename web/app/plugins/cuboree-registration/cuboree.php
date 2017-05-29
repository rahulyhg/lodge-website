<?php
/**
 * Plugin Name: Cuboree Registration
 * Plugin URI:  https://mckernan.in
 * Description: Cuboree registration functionality.
 * Version:     1.0.0
 * Author:      Kevin McKernan
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once( 'classes/class-cuboree-registration.php' );

/**
 * Helper function to get/return the Cuboree_Registration object
 * @since  0.1.0
 * @return Cuboree_Registration object
 */
function cuboree_registration() {
	return Cuboree_Registration::get_instance();
}

// Get it started
cuboree_registration();
