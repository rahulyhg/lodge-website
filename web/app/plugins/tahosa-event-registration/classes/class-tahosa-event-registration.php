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
}
