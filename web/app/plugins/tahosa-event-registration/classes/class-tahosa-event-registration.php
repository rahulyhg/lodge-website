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
		add_filter( 'product_type_options', [ $this, 'ticket_type_option'], 20 );
	}

	static public function is_ticket( $id = null ) {
		if ( null === $id ) {
			$id = get_id();
		}
		$ticket = false;
		$ticket_meta = get_post_meta( $id, '_ticket', true );
		if ( 'yes' === $ticket_meta ) {
			$ticket = true;
		}
		return $ticket;
	}

	public function ticket_body_class( $classes ) {
		global $post;
		$data = [
			'id' => $post->ID,
			'is_ticket' => $this->is_ticket( $post->ID ),
		];
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

		register_rest_route( 'tahosa-events/v1', '/details/(?P<id>\d+)', array(
			'methods'  => 'GET',
			'callback' => [ $this, 'details_endpoint' ],
		));
	}

	static function js_friendly_array($array) {
		foreach ( $array as $key => $value ) {
			if ( is_array( $value ) ) {
				$newarray = [];
				foreach ($value as $key2 => $value2) {
					$newarray[] = [
						'label' => $key2,
						'value' => $value2,
					];
				}
				$array[$key] = $newarray;
			}
		}
		return $array;
	}

	public function ticket_endpoint() {
		$args = [
			'post_type'      => 'event_ticket',
			'posts_per_page' => 1000,
		];
		$query = new WP_Query($args);
		$events = [
			'cuboree-staff' => [
				'name' => 'Cuboree Staff',
				'total' => 0,
				'by-reg-type' => [],
			],
			'cuboree' => [
				'name' => 'Cuboree',
				'total' => 0,
				'by-reg-type' => [],
			],
			'fall-fellowship' => [
				'name' => 'Fall Fellowship',
				'total' => 0,
				'by-chapter' => [
					'kodiak' => 0,
					'medicine-bear' => 0,
					'medicine-pipe' => 0,
					'running-antelope' => 0,
					'spirit-eagle' => 0,
					'white-buffalo' => 0,
					'white-eagle' => 0,
				],
				'by-age' => [
					'youth' => 0,
					'adult' => 0,
				],
				'by-level' => [
					'ordeal' => 0,
					'brotherhood' => 0,
					'vigil' => 0,
				],
				'by-reg-type' => [],
			],
			'lld' => [
				'name' => 'LLD',
				'total' => 0,
			],
			'noac' => [
				'name' => 'NOAC',
				'total' => 0,
			]
		];
		foreach ( $query->posts as $ticket ) {
			$post_title = sanitize_title( $ticket->post_title );
			foreach ( $events as $key => $value ) {
				if ( false !== strpos( $post_title, $key ) ) {
					$events[$key]['total']++;
					$post_title = str_replace( $key . '-', '', $post_title );
					$event = $key;
					break;
				}
			}
			if ( isset( $events[$event]['by-reg-type'][ $post_title ] ) && '' !== $event ) {
				$events[$event]['by-reg-type'][ $post_title ]++;
			} else {
				$events[$event]['by-reg-type'][ $post_title ] = 1;
			}

			if ( 'fall-fellowship' === $event ) {
				$chapter = sanitize_title( get_post_meta( $ticket->ID, 'b0d31d24234398f1bb20d045ce19501c', true ) );
				$level = sanitize_title( get_post_meta( $ticket->ID, 'ef4e5bbc80fc1d0acdfb84f27f591657', true ) );
				$age = sanitize_title( get_post_meta( $ticket->ID, '47c172cd25856ce5759c249a929c276e', true ) );
				$events[$event]['by-chapter'][$chapter]++;
				$events[$event]['by-age'][$age]++;
				$events[$event]['by-level'][$level]++;
			}
		}

		$finaldata = [];
		foreach ($events as $key => $value) {
			$value = $this->js_friendly_array($value);
			$finaldata[] = $value;
		}
		$response = new WP_REST_Response( $finaldata );
		$response->header( 'Access-Control-Allow-Origin', apply_filters( 'spe_access_control_allow_origin', '*' ) );
		return $response;
	}

	public function details_endpoint($data) {
		if ( ! is_user_logged_in()) {
			$response = new WP_REST_Response( 'unauthorized' );
			return $response;
		}
		$args = [
			'post_type'      => 'event_ticket',
			'posts_per_page' => 200,
			'orderby'        => 'ID',
			'order'          => 'ASC',
			'cache_results'  => false,
			'meta_query' => array(
				array(
					'key' => '_product',
					'value' => $data['id'],
					'compare' => 'IN',
				),
			),
		];
		$tickets = get_posts($args);
		$output = [];
		foreach ( $tickets as $ticket ) {
			$output[] = [
				'ticket_id'     => $ticket->ID,
				'ticket_status' => get_post_status( $ticket_id ),
				'ticket_name'   => $ticket->post_title,
				'ticket_url'    => $ticket_url,
				'purchase_date' => $purchase_time,
				'order_id'      => $order_id,
				'order_status'  => wc_get_order_status_name( get_post_status( $order_id ) ),
				'user_id'       => $user_id,
			];
		}
		$response = new WP_REST_Response( $query->posts );
		$response->header( 'Access-Control-Allow-Origin', apply_filters( 'spe_access_control_allow_origin', '*' ) );
		return $response;
	}

	/**
	 * Add 'Ticket' option to products.
	 *
	 * @param  array  $options Default options
	 * @return array           Modified options
	 */
	public function ticket_type_option( $options = array() ) {
		$options['ticket'] = array(
			'id'            => '_ticket',
			'wrapper_class' => 'show_if_simple show_if_variable hide_if_deposit',
			'label'         => __( 'Ticket', 'woocommerce-box-office' ),
			'description'   => __( 'Each ticket purchased will have attendee details added to it.', 'woocommerce-box-office' ),
			'default'       => 'no',
		);

		return $options;
	}
}
