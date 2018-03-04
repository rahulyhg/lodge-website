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
	 * Product ID for staff registration
	 *
	 * @var int Product ID
	 */
	protected $staff_product_id = 9058;

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
		add_action( 'woocommerce_after_order_notes', [ $this, 'custom_checkout_fields' ] );
		add_action( 'woocommerce_checkout_update_order_meta', [ $this, 'custom_checkout_field_processing' ] );

		add_action( 'th_before_body', [ $this, 'breadcrumbs' ] );
	}

	public function cart_validation() {
		if ( is_cart() || is_checkout() ) {
			global $woocommerce, $product;
			$youth_in_cart = false;
			$adult_in_cart = false;
			$staff_in_cart = false;
			foreach ( $woocommerce->cart->cart_contents as $product ) {
				if ( $this->adult_product_id === $product['product_id'] ) {
					$adult_in_cart = true;
					break;
				}

				if ( $this->staff_product_id === $product['product_id'] ) {
					$staff_in_cart = true;
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

	public function custom_checkout_fields( $checkout ) {

		global $woocommerce, $product;
		$cuboree_registration = false;
		foreach ( $woocommerce->cart->cart_contents as $product ) {
			if ( has_term( 'cuboree', 'product_cat', $product['product_id'] ) ) {
				$cuboree_registration = true;
				break;
			}
		}

		if ( ! $cuboree_registration ) {
			return;
		}

		echo '<div id="cuboree_registration_details" class="custom-registration-fields"><h2>' . esc_html( 'Registration Details' ) . '</h2>';

		woocommerce_form_field( 'unit_number', [
			'type'          => 'number',
			'class'         => array( 'my-field-class form-row-wide' ),
			'label'         => esc_html( 'Unit Number' ),
			'required' => true,
		], $checkout->get_value( 'unit_number' ));

		woocommerce_form_field( 'district', [
			'type'  		=> 'select',
			'class' 		=> array( 'my-field-class form-row-wide' ),
			'label' 		=> 'District <em>(Not sure? <a href="http://www.denverboyscouts.org/geography/49937" target="_blank">Click here</a>)</em>',
			'required'    	=> true,
			'options'     	=> [
				'' => '---',
				'Arapahoe',
				'Centennial',
				'Frontier',
				'Gateway',
				'Pioneer Trails',
				'Timberline',
				'Valley',
				'Out of council',
			],
		], $checkout->get_value( 'district' ));

		echo '</div>';
	}

	public function custom_checkout_field_processing( $order_id ) {
		if ( ! empty( $_POST['unit_number'] ) ) {
			update_post_meta( $order_id, 'unit_number', sanitize_text_field( $_POST['unit_number'] ) );
		}

		if ( ! empty( $_POST['district'] ) ) {
			update_post_meta( $order_id, 'district', sanitize_text_field( $_POST['district'] ) );
		}
	}

	public function breadcrumbs() {
		include( plugin_dir_path( __DIR__ ) . '/templates/breadcrumbs.php' );
	}

	static function unit_from_order_id( $id ) {
		return get_post_meta( $id, 'unit_number', true );
	}

	static function district_from_order_id( $id ) {
		return get_post_meta( $id, 'district', true );
	}
}
