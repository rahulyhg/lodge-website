<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://mckernan.in
 * @since      1.0.0
 *
 * @package    Flashback
 * @subpackage Flashback/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Flashback
 * @subpackage Flashback/public
 * @author     Kevin McKernan <kevin@mckernan.in>
 */
class Flashback_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * The location of the public class.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $filepath    The location of the public class.
	 */
	private $filepath;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;
		$this->filepath    = plugin_dir_url( __FILE__ );
		add_shortcode( 'flashback-horizontal', array( $this, 'shortcode_horizontal_timeline' ) );
		add_shortcode( 'flashback-vertical', array( $this, 'shortcode_vertical_timeline' ) );

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Flashback_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Flashback_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_register_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/flashback-public.css', array(), $this->version, 'all' );
		wp_register_style( 'flashback_horizontal', plugin_dir_url( __FILE__ ) . 'css/horizontal/style.css', array(), $this->version, 'all' );
		wp_register_style( 'flashback_vertical', plugin_dir_url( __FILE__ ) . 'css/vertical/style.css', array(), $this->version, 'all' );
		wp_register_style( 'fontawesome', 'https://maxcdn.bootstrapcdn.com/font-awesome/4.6.1/css/font-awesome.min.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Flashback_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Flashback_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_register_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/flashback-public.js', array( 'jquery' ), $this->version, false );
		wp_register_script( 'flashback_horizontal', plugin_dir_url( __FILE__ ) . 'js/horizontal/main.js', array( 'jquery' ), $this->version, false );
		wp_register_script( 'flashback_vertical', plugin_dir_url( __FILE__ ) . 'js/vertical/main.js', array( 'jquery' ), $this->version, false );

	}

	/**
	 * Register the shortcode for the leadership position filter.
	 *
	 * @since    1.0.0
	 * @param array $atts Shortcode attributes.
	 */
	public function shortcode_horizontal_timeline( $atts ) {
		ob_start();
		wp_enqueue_style( 'flashback_horizontal' );
		wp_enqueue_script( 'flashback_horizontal' );
		wp_enqueue_style( 'fontawesome' );
		include( 'partials/shortcode-horizontal-timeline.php' );
		$shortcode_output = ob_get_clean();
		return $shortcode_output;
	}

	/**
	 * Register the shortcode for the leadership position filter.
	 *
	 * @since    1.0.0
	 * @param array $atts Shortcode attributes.
	 */
	public function shortcode_vertical_timeline( $atts ) {
		ob_start();
		wp_enqueue_style( 'flashback_vertical' );
		wp_enqueue_script( 'flashback_vertical' );
		wp_enqueue_style( 'fontawesome' );
		include( 'partials/shortcode-vertical-timeline.php' );
		$shortcode_output = ob_get_clean();
		return $shortcode_output;
	}
}
