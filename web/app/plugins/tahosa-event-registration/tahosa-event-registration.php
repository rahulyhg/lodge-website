<?php
/**
 * Plugin Name: Tahosa Lodge Registration
 * Plugin URI:  https://mckernan.in
 * Description: Tahosa Lodge registration functionality.
 * Version:     1.0.0
 * Author:      Kevin McKernan
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once( 'classes/class-tahosa-event-registration.php' );

/**
 * Helper function to get/return the Tahosa_Lodge_Registration object
 * @since  0.1.0
 * @return Tahosa_Lodge_Registration object
 */
function tahosa_event_registration() {
	return Tahosa_Event_Registration::get_instance();
}

// Get it started
tahosa_event_registration();
