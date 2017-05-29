<?php

class TahosaLodge {

	function __construct() {
		add_action( 'send_headers', 		[ $this, 'custom_headers' ] );
		add_action( 'wp_enqueue_scripts', 	[ $this, 'scripts_styles' ] );

		add_filter( 'gettext', 				[ $this, 'replace_footer_text' ] );
		add_filter( 'upload_mimes', 		[ $this, 'mime_types' ] );
		add_filter( 'body_class',   		[ $this, 'add_slug_to_body_class' ] );
		add_filter( 'gform_column_input_content_18_14_4', [ $this, 'vigil_position_notes_field' ], 10, 6 );
		add_filter( 'gform_column_input_18_14_1', [ $this, 'vigil_position_type' ], 10, 6 );
		add_filter( 'the_seo_framework_indicator', '__return_false' );

		add_shortcode( 'tahosa_button', [ $this, 'shortcode_tahosa_button' ] );

		$this->roots_support();
	}

	/**
	 * Add custom allowed media upload file types.
	 */

	public function mime_types( $mimes ) {
		$mimes['svg'] = 'image/svg+xml';

		return $mimes;
	}

	/**
	 * Add page slug as a body class.
	 */

	public function add_slug_to_body_class( $classes ) {
		global $post;
		if ( isset( $post ) ) {
			$classes[] = $post->post_type . '-' . $post->post_name;
		}
		return $classes;
	}

	/**
	 * Add custom headers.
	 */
	public function custom_headers() {
		header( 'Access-Control-Allow-Origin: *' );
	}

	/**
	 * Add theme support for Soil functions. Helps clean up WordPress markup.
	 */

	public function roots_support() {
		add_theme_support( 'soil-clean-up' );
		// add_theme_support( 'soil-disable-asset-versioning' );
		add_theme_support( 'soil-disable-trackbacks' );
		add_theme_support( 'soil-google-analytics', 'UA-52435052-1' );
		add_theme_support( 'soil-jquery-cdn' );
		add_theme_support( 'soil-js-to-footer' );
		// add_theme_support( 'soil-nav-walker' );
		add_theme_support( 'soil-nice-search' );
		add_theme_support( 'soil-relative-urls' );
	}

	/**
	 * Add typekit support if needed. Replace $kit_id with one from the kit you create.
	 */
	public function scripts_styles() {
		$kit_id = 'xbk1ivk';
		wp_enqueue_script( 'tahosa_typekit', 'https://use.typekit.net/' . $kit_id . '.js' );
		wp_add_inline_script( 'tahosa_typekit', 'try{Typekit.load({ async: true });}catch(e){}' );
		wp_add_inline_script( 'tahosa_typekit', '!function(e,t,n,a,c,l,m,o,d,f,h,i){c[l]&&(d=e.createElement(t),d[n]=c[l],e[a]("head")[0].appendChild(d),e.documentElement.className+=" wf-cached"),function s(){for(d=e[a](t),f="",h=0;h<d.length;h++)i=d[h][n],i.match(m)&&(f+=i);f&&(c[l]="/**/"+f),setTimeout(s,o+=o)}()}(document,"style","innerHTML","getElementsByTagName",localStorage,"tk",/^@font|^\.tk-/,100);', 'before' );
		wp_enqueue_script( 'tahosalodge_scripts', get_theme_file_uri( '/assets/js/app.js' ) );
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

	public function replace_footer_text( $text ) {
		if ( 'Designed by %1$s | Powered by %2$s' === $text ) {
			$text = '&copy; ' . date( 'Y' ) . ' Tahosa Lodge | Need help? <a href="mailto:help@tahosalodge.org">Email Us</a> | Site by <a href="https://mckernan.in">Kevin McKernan</a>';
		}
		return $text;
	}

	public function shortcode_tahosa_button( $atts ) {
		$atts = shortcode_atts( array (
			'link' => '#',
			'text' => 'Button Text',
		), $atts );
		return '<a class="et_pb_button et_pb_promo_button button_shortcode" href="' . $atts['link'] . '">' . $atts['text'] . '</a>';
	}
}

new TahosaLodge();
