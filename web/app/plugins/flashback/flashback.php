<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://mckernan.in
 * @since             1.0.0
 * @package           Flashback
 *
 * @wordpress-plugin
 * Plugin Name:       Flashback
 * Plugin URI:        https://mckernan.in/flashback
 * Description:       Display simple responsive timelines on your website.
 * Version:           1.0.7
 * Author:            Kevin McKernan
 * Author URI:        https://mckernan.in
 * GitHub Plugin URI: mckernanin/flashback
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       flashback
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-flashback-activator.php
 */
function activate_flashback() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-flashback-activator.php';
	Flashback_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-flashback-deactivator.php
 */
function deactivate_flashback() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-flashback-deactivator.php';
	Flashback_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_flashback' );
register_deactivation_hook( __FILE__, 'deactivate_flashback' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-flashback.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_flashback() {

	$plugin = new Flashback();
	$plugin->run();

}
run_flashback();
