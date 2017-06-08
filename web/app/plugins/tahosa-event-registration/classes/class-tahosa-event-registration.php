<?php

class Tahosa_Event_Registration {

	/**
	 * Holds an instance of the object
	 *
	 * @var Tahosa_Lodge_Registration_Admin
	 */
	protected static $instance = null;

	/**
	 * Returns the running object
	 *
	 * @return Tahosa_Lodge_Registration_Admin
	 */
	public static function get_instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
			self::$instance->hooks();
		}

		return self::$instance;
	}

	/**
	 * Initiate our hooks
	 * @since 0.1.0
	 */
	public function hooks() {
		add_filter( 'body_class', [ $this, 'ticket_body_class' ] );
		add_filter( 'woocommerce_placeholder_img_src', [ $this, 'custom_woocommerce_placeholder' ] );
		add_filter( 'woocommerce_sale_flash', [ $this, 'custom_sale_text' ] );
		add_filter( 'wocommerce_box_office_input_field_template_vars', [ $this, 'class_to_ticket_fields' ] );
		add_filter( 'wocommerce_box_office_option_field_template_vars', [ $this, 'class_to_ticket_fields' ] );
		add_action( 'wp_print_scripts', function() {
			if ( wp_script_is( 'wc-password-strength-meter', 'enqueued' ) ) {
				wp_dequeue_script( 'wc-password-strength-meter' );
			}
		});


	}

	static public function is_ticket( $id = null ) {
		if ( null === $id ) {
			$id = get_id();
		}
		$ticket = false;
		$ticket_meta = get_post_meta( $id, '_ticket', true );
		if ( $ticket_meta ) {
			$ticket = true;
		}
		return $ticket;
	}

	public function ticket_body_class( $classes ) {
		global $post;
		if ( $this->is_ticket( $post->ID ) ) {
			$classes[] = 'woocommerce-ticket';
		}
		return $classes;
	}

	/**
	 * Function to return new placeholder image URL.
	 */
	public function custom_woocommerce_placeholder( $image_url ) {
	  $image_url = 'https://tahosawp.s3.amazonaws.com/uploads/2017/06/tahosa-og.png';
	  return $image_url;
	}

	public function custom_sale_text( $html ) {
		global $post;
		if ( $this->is_ticket( $post->ID ) ) {
			return str_replace( __( 'Sale!', 'woocommerce' ), __( 'Early Discount!', 'woocommerce' ), $html );
		}
		return $html;
	}

	public function class_to_ticket_fields( $vars ) {
		$slug = sanitize_title( $vars['label'] );
		$vars['before_field'] = "<p class='form-row ${slug}'>";
		return $vars;
	}
}
