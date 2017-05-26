<?php

class TahosaLodgeTheme {

	function __construct() {

		require_once( 'inc/tahosa-button.php' );

		add_filter( 'upload_mimes', array( $this, 'mime_types' ) );
		add_filter( 'body_class',   array( $this, 'add_slug_body_class' ) );
		add_filter( 'gform_column_input_content_18_14_4', array( $this, 'vigil_position_notes_field' ), 10, 6 );
		add_filter( 'gform_column_input_18_14_1', array( $this, 'vigil_position_type' ), 10, 6 );

		add_action( 'send_headers', array( $this, 'custom_headers' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'typekit' ) );

		$this->roots_support();
	}

	public function mime_types( $mimes ) {
		$mimes['svg'] = 'image/svg+xml';

		return $mimes;
	}

	public function add_slug_body_class( $classes ) {
		global $post;
		if ( isset( $post ) ) {
			$classes[] = $post->post_type . '-' . $post->post_name;
		}
		return $classes;
	}

	public function custom_headers() {
		header( 'Access-Control-Allow-Origin: *' );
	}

	public function roots_support() {
		add_theme_support( 'soil-clean-up' );
		add_theme_support( 'soil-disable-asset-versioning' );
		add_theme_support( 'soil-disable-trackbacks' );
		// add_theme_support( 'soil-google-analytics', '' );
		add_theme_support( 'soil-jquery-cdn' );
		// add_theme_support( 'soil-js-to-footer' );
		add_theme_support( 'soil-nav-walker' );
		add_theme_support( 'soil-nice-search' );
		add_theme_support( 'soil-relative-urls' );
	}

	public function typekit() {
		wp_enqueue_script( 'tahosa_typekit', 'https://use.typekit.net/xbk1ivk.js', array(), '1.0' );
		wp_add_inline_script( 'tahosa_typekit', 'try{Typekit.load({ async: true });}catch(e){}' );
	}

	public function vigil_position_notes_field( $input, $input_info, $field, $text, $value, $form_id ) {
	    //build field name, must match List field syntax to be processed correctly
	    $input_field_name = 'input_' . $field->id . '[]';
	    $tabindex         = GFCommon::get_tabindex();
	    $new_input        = '<textarea name="' . $input_field_name . '" ' . $tabindex . ' class="textarea medium" cols="50" rows="10">' . $value . '</textarea>';
	    return $new_input;
	}

	public function vigil_position_type( $input_info, $field, $column, $value, $form_id ) {
		$data = array(
			'type'    => 'select',
			'choices' => 'Unit,Chapter,Lodge,Section,District,Council,Other'
		);
	    return $data;
	}

	public function vigil_eligible_validation() {
		$current_page = rgpost( 'gform_source_page_number_' . $form['id'] ) ? rgpost( 'gform_source_page_number_' . $form['id'] ) : 1;
		foreach( $form['fields'] as &$field ) {
			if ( strpos( $field->cssClass, 'nominee-name' ) === false ) {
			    continue;
			}

			$field_page = $field->pageNumber;
			$is_hidden = RGFormsModel::is_field_hidden( $form, $field, array() );
			if ( $field_page != $current_page || $is_hidden ) {
			    continue;
			}

			$field_value = rgpost( "input_{$field['id']}" );
			$is_valid = is_vin( $field_value );
		}
	}
}

new TahosaLodgeTheme();
