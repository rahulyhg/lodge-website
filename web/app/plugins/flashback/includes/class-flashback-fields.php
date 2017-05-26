<?php
/**
 * Include and setup custom metaboxes and fields.
 *
 * @category Flashback
 * @package  CMB2
 * @license  http://www.opensource.org/licenses/gpl-license.php GPL v2.0 (or later)
 * @link     https://github.com/WebDevStudios/CMB2
 */

class FlashbackField {

	/**
	 * Get the bootstrap!
	 */

	function __construct() {

		require_once dirname( __FILE__ ) . '/lib/cmb2/init.php';

		$this->load_fields();
	}

	/**
	 * Load all fields.
	 */
	public function load_fields() {
		add_filter( 'cmb2_localized_data', array( $this, 'update_date_picker_defaults' ) );
		add_action( 'cmb2_admin_init', array( $this, 'timeline_item_metaboxes' ) );
	}

	/**
	 * Static function for getting field data.
	 */
	static function get( $field_name = null, $id = null ) {
		if ( null === $id ) {
			$id = get_the_ID();
		}
		if ( false === strpos( $field_name, '_flb_' ) ) {
			$field_name = '_flb_' . $field_name;
		}
		$field = get_post_meta( $id, $field_name, true );
		return '' != $field ? $field : false;
	}

	static function get_icon( $field_name ) {
		$icon = self::get( $field_name );
		return '<i class="fa ' . $icon . '" aria-hidden="true"></i>';
	}

	/**
	 * Define the metabox and field configurations.
	 */
	public function timeline_item_metaboxes() {

		// Start with an underscore to hide fields from custom fields list
		$prefix = '_flb_';

		/**
		 * Initiate the metabox
		 */
		$cmb = new_cmb2_box( array(
			'id'            => 'timeline_fields',
			'title'         => __( 'Timeline Fields', 'Flashback' ),
			'object_types'  => array( 'flb_timeline_item' ), // Post type
			'context'       => 'normal',
			'priority'      => 'high',
			'show_names'    => true, // Show field names on the left
			// 'cmb_styles' => false, // false to disable the CMB stylesheet
			// 'closed'     => true, // Keep the metabox closed by default
		) );

		// Date field
		$cmb->add_field( array(
		    'name' => 'Date',
		    'id'   => $prefix . 'date',
		    'type' => 'text_date',
		    // 'timezone_meta_key' => 'wiki_test_timezone',
		    'date_format' => 'Ymd',
		) );

		$cmb->add_field( array(
		    'name'    => 'Date Format',
		    'id'      => $prefix . 'date_format',
		    'type'    => 'radio_inline',
		    'options' => array(
		        'F jS, Y' => __( 'Full', 'flashback' ),
		        'F Y'     => __( 'Month Only', 'flashback' ),
		        'Y'       => __( 'Year Only', 'flashback' ),
		    ),
			'default' => 'F jS, Y',
		) );

		// Icon field
		$cmb->add_field( array(
			'name'    => __( 'Select Icon', 'Flashback' ),
			'id'      => $prefix . 'icon',
			'type'    => 'fontawesome_icon',
			'default' => 'fa-history',
		) );
	}

	function update_date_picker_defaults( $l10n ) {

	    $l10n['defaults']['date_picker']['yearRange'] = '1900:+0';

	    return $l10n;
	}
}

new FlashbackField();
