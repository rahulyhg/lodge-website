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
		add_filter( 'woocommerce_checkout_fields', [ $this, 'checkout_fields' ] );
		add_action( 'wp_print_scripts', function() {
			if ( wp_script_is( 'wc-password-strength-meter', 'enqueued' ) ) {
				wp_dequeue_script( 'wc-password-strength-meter' );
			}
		});
		add_action( 'rest_api_init', [ $this, 'register_api_hooks' ] );
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
		global $product;
		if ( is_object( $product ) &&  in_array( 107, $product->get_category_ids() ) ) {
			return 'https://tahosawp.s3.amazonaws.com/uploads/2017/06/cuboree-product.png';
		}
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

	public function checkout_fields( $fields ) {
		unset( $fields['billing']['billing_company'] );
		unset( $fields['shipping']['billing_company'] );
		return $fields;
	}

	public function register_api_hooks() {
		register_rest_route( 'tahosa-events/v1', '/tickets/', array(
			'methods'  => 'GET',
			'callback' => [ $this, 'ticket_endpoint' ],
		));
	}

	public function ticket_endpoint() {
		$args = [
			'post_type'      => 'event_ticket',
			'posts_per_page' => 1000,
		];
		$query = new WP_Query($args);
		$data = [
			'cuboree' => [
				'name' => 'Cuboree',
				'total' => 0,
			],
			'fall-fellowship' => [
				'name' => 'Fall Fellowship',
				'total' => 0,
			],
		];
		foreach ( $query->posts as $ticket ) {
			$post_title = sanitize_title( $ticket->post_title );
			if ( false !== strpos( $post_title, 'cuboree-' ) ) {
				$data['cuboree']['total']++;
				$post_title = str_replace( 'cuboree-', '', $post_title );
				$event = 'cuboree';
			} elseif ( false !== strpos( $post_title, 'fall-fellowship-' ) ) {
				$data['fall-fellowship']['total']++;
				$post_title = str_replace( 'fall-fellowship-', '', $post_title );
				$event = 'fall-fellowship';
			}
			if ( isset( $data[$event][ $post_title ] ) ) {
				$data[$event][ $post_title ]++;
			} else {
				$data[$event][ $post_title ] = 1;
			}
		}
		$finaldata = [];
		foreach ($data as $key => $value) {
			$finaldata[] = $value;
		}
		$response = new WP_REST_Response( $finaldata );
		$response->header( 'Access-Control-Allow-Origin', apply_filters( 'spe_access_control_allow_origin', '*' ) );
		return $response;
	}
}
