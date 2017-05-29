<?php

class Cuboree_Registration {

	/**
	 * Holds an instance of the object
	 *
	 * @var Cuboree_Registration_Admin
	 */
	protected static $instance = null;

	/**
	 * Product ID for the adult registration
	 *
	 * @var int Product ID
	 */
	protected $adult_product_id = 8980;

	/**
	 * Returns the running object
	 *
	 * @return Cuboree_Registration_Admin
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
		add_action( 'woocommerce_check_cart_items', [ $this, 'cart_validation' ] );
	}

	public function cart_validation() {
		if ( is_cart() || is_checkout() ) {
			global $woocommerce, $product;
			$youth_in_cart = false;
			$adult_in_cart = false;
			foreach ( $woocommerce->cart->cart_contents as $product ) {
				if ( $this->adult_product_id === $product['product_id'] ) {
					$adult_in_cart = true;
					break;
				}

				if ( has_term( 'cuboree', 'product_cat', $product['product_id'] ) ) {
					$youth_in_cart = true;
				}
			}
			if ( $adult_in_cart ) {
				return;
			}
			if ( $youth_in_cart ) {
				wc_add_notice( '<strong>You must register at least one adult with a youth.</strong> <a class="button" href="' . get_permalink( $this->adult_product_id ) . '">Register Adult</a>', 'error' );
			}
		}
	}
}
